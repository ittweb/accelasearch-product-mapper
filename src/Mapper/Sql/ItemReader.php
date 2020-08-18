<?php
namespace Ittweb\AccelaSearch\ProductMapper\Mapper\Sql;
use \OutOfBoundsException;
use \PDO;
use \Ittweb\AccelaSearch\ProductMapper\Model\ItemInterface;

class ItemReader {
    const HAS_STOCK_AND_PRICE = ['simple', 'virtual', 'downloadable', 'configurable', 'grouped', 'bundle'];
    const HAS_CONFIGURATIONS = ['configurable'];
    const HAS_COMPONENTS = ['bundle', 'grouped'];
    private $dbh;
    private $shop_id;
    private $product_types;
    private $attribute_types;
    private $stock;
    private $price;
    private $read_product_sth;
    private $search_by_parent_sth;

    public function __construct(
        PDO $dbh,
        int $shop_id,
        array $product_types,
        array $attribute_types,
        Stock $stock,
        Price $price
    ) {
        $this->dbh = $dbh;
        $this->shop_id = $shop_id;
        $this->product_types = $product_types;
        $this->attribute_types = $attribute_types;
        $this->stock = $stock;
        $this->price = $price;
        $this->prepareStatements();
    }

    public function read(int $identifier): ItemInterface {
        $this->read_product_sth->execute([
            ':shop_id' => $this->shop_id,
            ':id' => $identifier
        ]);
        $data = $this->read_product_sth->fetchAll();
        if (empty($data)) {
            throw new OutOfBoundsException('No products with id "' . $identifier . '".');
        }
        $product_type = array_search($data[0]['product_type'], $this->product_types);
        if ($product_type === false) {
            throw new OutOfBoundsException('Unsupported product type.');
        }
        $class_name = $this->getClassNameFromType($product_type);
        $product_id = $data[0]['id'];
        $item = $this->hasStockAndPrice($product_type)
              ? new $class_name($this->stock->read($product_id), $this->price->read($product_id))
              : new $class_name();
        $this->readAttributes($item, $data);
        if ($this->hasConfigurations($product_type)) {
            $this->readConfigurations($item, $product_id);
        }
        if ($this->hasComponents($product_type)) {
            $this->readComponents($item, $product_id);
        }
        return $item;
    }

    private function prepareStatements() {
        $this->read_product_sth = $this->dbh->prepare(
            'SELECT id, product.type_id AS product_type, attribute_integer.type_id AS attribute_type, name, value FROM product JOIN attribute_integer ON product.id = attribute_integer.product_id WHERE product.shop_id = :shop_id AND product.id = :id AND product.deleted_at IS NULL '
            . 'UNION '
            . 'SELECT id, product.type_id AS product_type, attribute_real.type_id AS attribute_type, name, value FROM product JOIN attribute_real ON product.id = attribute_real.product_id WHERE product.shop_id = :shop_id AND product.id = :id AND product.deleted_at IS NULL '
            . 'UNION '
            . 'SELECT id, product.type_id AS product_type, attribute_date.type_id AS attribute_type, name, value FROM product JOIN attribute_date ON product.id = attribute_date.product_id WHERE product.shop_id = :shop_id AND product.id = :id AND product.deleted_at IS NULL '
            . 'UNION '
            . 'SELECT id, product.type_id AS product_type, attribute_text.type_id AS attribute_type, name, value FROM product JOIN attribute_text ON product.id = attribute_text.product_id WHERE product.shop_id = :shop_id AND product.id = :id AND product.deleted_at IS NULL '
        );
        $this->search_by_parent_sth = $this->dbh->prepare(
            'SELECT id FROM product WHERE parent_id = :parent_id'
        );
    }

    private function getClassNameFromType(string $type): string {
        return '\\Ittweb\\AccelaSearch\\ProductMapper\\Model\\' . ucfirst($type);
    }

    private function hasStockAndPrice(string $type): bool {
        return in_array($type, self::HAS_STOCK_AND_PRICE);
    }

    private function hasConfigurations(string $type): bool {
        return in_array($type, self::HAS_CONFIGURATIONS);
    }

    private function hasComponents(string $type): bool {
        return in_array($type, self::HAS_COMPONENTS);
    }

    private function searchByParentId(int $parent_id): array {
        $this->search_by_parent_sth->execute([':parent_id' => $parent_id]);
        $children = [];
        foreach ($this->search_by_parent_sth->fetchAll() as $record) {
            $children[] = $this->read($record['id']);
        }
        return $children;
    }

    private function readAttributes(ItemInterface $item, array $data) {
        foreach ($data as $record) {
            $name = $record['name'];
            $value = $record['value'];
            $type = array_search($record['attribute_type'], $this->attribute_types);
            $attributes[$name][] = $value;
            if ($type === 'variant') {
                $item->addConfigurableAttribute($name);
            }
        }
        foreach ($attributes as $name => $values) {
            $item->$name = count($values) === 1 ? $values[0] : $values;
        }
    }

    private function readConfigurations(ItemInterface $item, int $product_id) {
        foreach ($this->searchByParentId($product_id) as $child) {
            $item->addConfiguration($child);
        }
    }

    private function readComponents(ItemInterface $item, int $product_id) {
        foreach ($this->searchByParentId($product_id) as $child) {
            $item->addComponent($child);
        }
    }
}
