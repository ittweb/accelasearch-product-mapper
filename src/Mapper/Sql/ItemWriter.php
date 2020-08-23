<?php
namespace Ittweb\AccelaSearch\ProductMapper\Mapper\Sql;
use \PDO;
use \OutOfBoundsException;
use \Ittweb\AccelaSearch\ProductMapper\Model\ItemInterface as Item;
use \Ittweb\AccelaSearch\ProductMapper\Model\Simple;
use \Ittweb\AccelaSearch\ProductMapper\Model\Virtual;
use \Ittweb\AccelaSearch\ProductMapper\Model\Downloadable;
use \Ittweb\AccelaSearch\ProductMapper\Model\Configurable;
use \Ittweb\AccelaSearch\ProductMapper\Model\Bundle;
use \Ittweb\AccelaSearch\ProductMapper\Model\Grouped;
use \Ittweb\AccelaSearch\ProductMapper\Model\Page;
use \Ittweb\AccelaSearch\ProductMapper\Model\Category;
use \Ittweb\AccelaSearch\ProductMapper\Model\Banner;

class ItemWriter {
    const HAS_STOCK_AND_PRICE = ['simple', 'virtual', 'downloadable', 'configurable', 'grouped', 'bundle'];
    const HAS_CONFIGURATIONS = ['configurable'];
    const HAS_COMPONENTS = ['bundle', 'grouped'];
    private $dbh;
    private $shop_id;
    private $identifier_attribute;
    private $category_fields;
    private $configurable_fields;
    private $product_types;
    private $attribute_types;
    private $stock_mapper;
    private $price_mapper;
    private $product_sth;

    public function __construct (
        PDO $dbh,
        int $shop_id,
        string $identifier_attribute,
        array $category_fields,
        array $configurable_fields,
        array $product_types,
        array $attribute_types,
        Stock $stock_mapper,
        Price $price_mapper
    ) {
        $this->dbh = $dbh;
        $this->shop_id = $shop_id;
        $this->identifier_attribute = $identifier_attribute;
        $this->category_fields = $category_fields;
        $this->configurable_fields = $configurable_fields;
        $this->product_types = $product_types;
        $this->attribute_types = $attribute_types;
        $this->stock_mapper = $stock_mapper;
        $this->price_mapper = $price_mapper;
        $this->prepareStatements();
    }

    public function write(Item $item, Item $parent = null, int $parent_id = null) {
        $type = $this->getItemType($item);
        $item_id = $this->writeItem($item, $type, $parent, $parent_id);
        if (in_array($type, self::HAS_STOCK_AND_PRICE)) {
            $this->stock_mapper->create($item->getStock(), $item_id, $item[$this->identifier_attribute], $this->shop_id);
            $this->price_mapper->create($item->getPrice(), $item_id, $item[$this->identifier_attribute], $this->shop_id);
        }
        if (in_array($type, self::HAS_CONFIGURATIONS)) {
            foreach ($item->getConfigurationsAsArray() as $child) {
                $this->write($child, $item, $item_id);
            }
        }
        if (in_array($type, self::HAS_COMPONENTS)) {
            foreach ($item->getComponentsAsArray() as $child) {
                $this->write($child, $item, $item_id);
            }
        }
    }

    private function prepareStatements() {
        $this->product_sth = $this->dbh->prepare(
            'INSERT INTO product(external_id, shop_id, type_id, parent_id, parent_external_id) '
          . 'VALUES(:external_id, :shop_id, :type_id, :parent_id, :parent_external_id)'
        );
    }

    public function getItemType(Item $item): string {
        return strtolower(basename(str_replace('\\', '/', get_class($item))));
    }

    private function writeItem(Item $item, string $type, Item $parent = null, int $parent_id = null): int {
        if (!array_key_exists($type, $this->product_types)) {
            throw new OutOfBoundsException('Unknown product type "' . $type . '"');
        }
        $this->product_sth->execute([
            ':external_id' => $item[$this->identifier_attribute],
            ':shop_id' => $this->shop_id,
            ':type_id' => $this->product_types[$type],
            ':parent_id' => $parent_id,
            ':parent_external_id' => !is_null($parent) ? $parent[$this->identifier_attribute] : null
        ]);
        $item_id = $this->dbh->lastInsertId();
        $this->writeAttributes($item, $item_id);
        return $item_id;
    }

    public function writeAttributes(Item $item, int $item_id) {
        $query = 'INSERT INTO attribute_text(product_id, product_external_id, shop_id, type_id, name, value) VALUES ';
        $clauses = [];
        $bound_parameters = [];
        foreach ($item->getAttributesAsDictionary() as $name => $values) {
            $type = 'simple';
            if (in_array($name, $this->configurable_fields)) {
                $type = 'variant';
            }
            elseif (in_array($name, $this->category_fields)) {
                $type = 'category';
            }
            if (!is_array($values)) {
                $values = [$values];
            }
            foreach ($values as $value) {
                $clauses[] = '(?, ?, ?, ?, ?, ?)';
                $bound_parameters[] = $item_id;
                $bound_parameters[] = $item[$this->identifier_attribute];
                $bound_parameters[] = $this->shop_id;
                $bound_parameters[] = $this->attribute_types[$type];
                $bound_parameters[] = $name;
                $bound_parameters[] = $value;
            }
        }
        $query .= implode(', ', $clauses);
        $sth = $this->dbh->prepare($query);
        $sth->execute($bound_parameters);
    }
}
