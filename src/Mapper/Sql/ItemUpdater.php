<?php
namespace Ittweb\AccelaSearch\ProductMapper\Mapper\Sql;
use \PDO;
use \OutOfBoundsException;
use \Ittweb\AccelaSearch\ProductMapper\Model\ItemInterface as Item;

class ItemUpdater {
    const HAS_STOCK_AND_PRICE = ['simple', 'virtual', 'downloadable', 'configurable', 'grouped', 'bundle'];
    const HAS_CONFIGURATIONS = ['configurable'];
    const HAS_COMPONENTS = ['bundle', 'grouped'];
    private $dbh;
    private $shop_id;
    private $identifier_attribute;
    private $stock_mapper;
    private $price_mapper;
    private $item_writer;
    private $get_identifiers_sth;
    private $update_product_sth;
    private $attributes_sth;
    private $delete_children_sth;

    public function __construct (
        PDO $dbh,
        int $shop_id,
        string $identifier_attribute,
        Stock $stock_mapper,
        Price $price_mapper,
        ItemWriter $item_writer
    ) {
        $this->dbh = $dbh;
        $this->shop_id = $shop_id;
        $this->identifier_attribute = $identifier_attribute;
        $this->stock_mapper = $stock_mapper;
        $this->price_mapper = $price_mapper;
        $this->item_writer = $item_writer;
        $this->prepareStatements();
    }

    public function update(Item $item) {
        $external_id = $item[$this->identifier_attribute];
        $type = $this->item_writer->getItemType($item);
        $identifiers = $this->getIdentifiers($item);
        $this->attributes_sth->execute([
            ':product_external_id' => $external_id,
            ':shop_id' => $this->shop_id
        ]);
        $this->stock_mapper->deleteByExternalId($external_id, $this->shop_id);
        $this->price_mapper->deleteByExternalId($external_id, $this->shop_id);
        $this->delete_children_sth->execute([
            ':parent_external_id' => $external_id,
            ':shop_id' => $this->shop_id
        ]);
        foreach ($identifiers as $identifier) {
            $this->item_writer->writeAttributes($item, $identifier);
            if (in_array($type, self::HAS_STOCK_AND_PRICE)) {
                $this->stock_mapper->create($item->getStock(), $identifier, $external_id, $this->shop_id);
                $this->price_mapper->create($item->getPrice(), $identifier, $external_id, $this->shop_id);
            }
            if (in_array($type, self::HAS_CONFIGURATIONS)) {
                foreach ($item->getConfigurationsAsArray() as $child) {
                    $this->item_writer->write($child, $item, $identifier);
                }
            }
            if (in_array($type, self::HAS_COMPONENTS)) {
                foreach ($item->getComponentsAsArray() as $child) {
                    $this->item_writer->write($child, $item, $identifier);
                }
            }
        }
        $this->update_product_sth->execute([
            ':external_id' => $external_id,
            ':shop_id' => $this->shop_id
        ]);
    }

    private function prepareStatements() {
        $this->update_product_sth = $this->dbh->prepare(
            'UPDATE product SET updated_at = CURRENT_TIMESTAMP() '
          . 'WHERE external_id = :external_id AND shop_id = :shop_id AND deleted_at IS NULL'
        );
        $this->get_identifiers_sth = $this->dbh->prepare(
            'SELECT id FROM product '
          . 'WHERE external_id = :external_id AND shop_id = :shop_id AND deleted_at IS NULL'
        );
        $this->attributes_sth = $this->dbh->prepare(
            'DELETE FROM attribute_text '
          . 'WHERE product_external_id = :product_external_id AND shop_id = :shop_id'
        );
        $this->delete_children_sth = $this->dbh->prepare(
            'DELETE FROM product '
          . 'WHERE parent_external_id = :parent_external_id AND shop_id = :shop_id AND deleted_at IS NULL'
        );
    }

    public function getIdentifiers(Item $item): array {
        $this->get_identifiers_sth->execute([
            ':external_id' => $item[$this->identifier_attribute],
            ':shop_id' => $this->shop_id
        ]);
        $identifiers = [];
        foreach ($this->get_identifiers_sth->fetchAll() as $record) {
            $identifiers[] = $record['id'];
        }
        return $identifiers;
    }
}
