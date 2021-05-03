<?php
namespace Ittweb\AccelaSearch\ProductMapper\DataMapper\Sql;
use \Ittweb\AccelaSearch\ProductMapper\ItemInterface;
use \Ittweb\AccelaSearch\ProductMapper\ProductInterface;
use \Ittweb\AccelaSearch\ProductMapper\StockableInterface;
use \Ittweb\AccelaSearch\ProductMapper\SellableInterface;
use \Ittweb\AccelaSearch\ProductMapper\DataMapper\ItemInterface as ItemMapperInterface;
use \Ittweb\AccelaSearch\ProductMapper\DataMapper\Query;

class Item implements ItemMapperInterface {
    private $connection;
    private $attribute_lookup;
    private $item_get_type;
    private $item_is_product;
    private $item_get_children;
    private $quantity_converter;

    public function __construct(
        Connection $connection,
        ItemGetTypeVisitor $item_get_type,
        ItemIsProductVisitor $item_is_product,
        ItemGetChildrenVisitor $item_get_children,
        QuantityConverterVisitor $quantity_converter
    ) {
        $this->connection = $connection;
        $this->item_get_type = $item_get_type;
        $this->item_is_product = $item_is_product;
        $this->attribute_lookup = [];
        $this->item_get_children = $item_get_children;
        $this->quantity_converter = $quantity_converter;

        $this->initializeAttributeLookup();
    }

    public static function fromConnection(Connection $connection): self {
        return new Item(
            $connection,
            new ItemGetTypeVisitor(),
            new ItemIsProductVisitor(),
            new ItemGetChildrenVisitor(),
            new QuantityConverterVisitor()
        );
    }

    public function create(ItemInterface $item, ?int $parent_identifier = null): self {
        $children = $item->accept($this->item_get_children);
        $query = 'INSERT INTO products(sku, siteid, parentid, haschild, typeid, externalidstr, url) '
            . 'VALUES(:sku, :shop_identifier, :parent_identifier, :has_children, :type_identifier, :external_identifier, :url)';
        $sth = $this->connection->getDbh()->prepare($query);
        $sth->execute([
            ':sku' => $item->getSku(),
            ':shop_identifier' => $this->connection->getShopIdentifier(),
            ':parent_identifier' => $parent_identifier,
            ':has_children' => count($children),
            ':type_identifier' => $item->accept($this->item_get_type),
            ':external_identifier' => $item->accept($this->item_is_product) ? $item->getExternalIdentifier() : null,
            ':url' => $item->getUrl()
        ]);
        $item->setIdentifier($this->connection->getDbh()->lastInsertId());
        if ($item->accept($this->item_is_product)) {
            $this->insertAttributes($item);
            $this->insertCategories($item);
            $this->insertImageInfo($item);
            $this->insertAvailability($item);
            $this->insertPricing($item);
        }
        return $this;
    }

    public function read(int $identifier): ItemInterface {
        return new \Ittweb\AccelaSearch\ProductMapper\Page('...');
    }

    public function update(ItemInterface $item): self {
        return $this;
    }

    public function delete(Query $query): self {
        return $this;
    }

    public function softDelete(Query $query): self {
        return $this;
    }

    public function search(Query $query): array {
        return [];
    }

    private function initializeAttributeLookup(): self {
        $query = 'SELECT id, label FROM products_attr_label WHERE storeviewid = :shop_identifier AND deleted = 0';
        $sth = $this->connection->getDbh()->prepare($query);
        $sth->execute([':shop_identifier' => $this->connection->getShopIdentifier()]);
        while ($row = $sth->fetch()) {
            $this->attribute_lookup[$row['label']] = $row['id'];
        }
        return $this;
    }

    private function insertAttributes(ProductInterface $item): self {
        $clauses = [];
        $binders = [];
        foreach ($item->getAttributesAsArray() as $attribute) {
            $name = strtolower(trim(str_replace(' ', '_', $attribute->getName())));
            if (!isset($this->attribute_lookup[$name])) {
                $query = 'INSERT INTO products_attr_label(label, storeviewid) '
                    . 'VALUES(:label, :shop_identifier) '
                    . 'ON DUPLICATE KEY UPDATE deleted = 0';
                $sth = $this->connection->getDbh()->prepare($query);
                $sth->execute([
                    ':label' => $attribute->getName(),
                    ':shop_identifier' => $this->connection->getShopIdentifier()
                ]);
                $this->attribute_lookup[$name] = $this->connection->getDbh()->lastInsertId();
            }
            foreach ($attribute->getValuesAsArray() as $value) {
                $progressive_identifier = count($binders) / 4 + 1;
                $clauses[] = '(:label_identifier_'  . $progressive_identifier
                    . ', :product_identifier_' . $progressive_identifier
                    . ', :value_' . $progressive_identifier
                    . ', :is_configurable_' . $progressive_identifier . ')'
                ;
                $binders[':label_identifier_' . $progressive_identifier] = $this->attribute_lookup[$name];
                $binders[':product_identifier_' . $progressive_identifier] = $item->getIdentifier();
                $binders[':value_' . $progressive_identifier] = $value;
                $binders[':is_configurable_' . $progressive_identifier] = $attribute->isConfigurable() ? 1 : 0;
            }
        }
        if (!empty($clauses)) {
            $query = 'INSERT INTO products_attr_text(labelid, productid, value, configurable) VALUES '
                . implode(', ', $clauses);
            $sth = $this->connection->getDbh()->prepare($query);
            $sth->execute($binders);
        }
        return $this;
    }

    private function insertCategories(ProductInterface $item): self {
        $clauses = [];
        $binders = [];
        foreach ($item->getCategoriesAsArray() as $category) {
            $progressive_identifier = count($clauses) + 1;
            $clauses[] = '(:category_identifier_' . $progressive_identifier
                . ', :product_identifier_' . $progressive_identifier . ')'
            ;
            $binders[':category_identifier_' . $progressive_identifier] = $category->getIdentifier();
            $binders[':product_identifier_' . $progressive_identifier] = $item->getIdentifier();
        }
        if (!empty($clauses)) {
            $query = 'INSERT INTO products_categories(categoryid, productid) VALUES '
                . implode(', ', $clauses);
            $sth = $this->connection->getDbh()->prepare($query);
            $sth->execute($binders);
        }
        return $this;
    }

    private function insertImageInfo(ProductInterface $item): self {
        $clauses = [];
        $binders = [];
        $push = function (string $url, bool $is_main, bool $is_over) use($item, &$clauses, &$binders) {
            $progressive_identifier = count($clauses) + 1;
            $clauses[] = '(:product_identifier_' . $progressive_identifier
                . ', :product_external_identifier_' . $progressive_identifier
                . ', :is_main_' . $progressive_identifier
                . ', :is_over_' . $progressive_identifier
                . ', :url_' . $progressive_identifier . ')'
            ;
            $binders[':product_identifier_' . $progressive_identifier] = $item->getIdentifier();
            $binders[':product_external_identifier_' . $progressive_identifier] = $item->getExternalIdentifier();
            $binders[':is_main_' . $progressive_identifier] = $is_main ? 1 : 0;
            $binders[':is_over_' . $progressive_identifier] = $is_over ? 1 : 0;
            $binders[':url_' . $progressive_identifier] = $url;
        };
        if (!is_null($item->getImageInfo()->getMain())) {
            $push($item->getImageInfo()->getMain(), true, false);
        }
        if (!is_null($item->getImageInfo()->getOver())) {
            $push($item->getImageInfo()->getOver(), false, true);
        }
        foreach ($item->getImageInfo()->getOtherAsArray() as $other) {
            $push($other, false, false);
        }
        if (!empty($clauses)) {
            $query = 'INSERT INTO products_images(productid, externalproductidstr, main, `over`, url) VALUES '
                . implode(', ', $clauses);
            $sth = $this->connection->getDbh()->prepare($query);
            $sth->execute($binders);
        }
        return $this;
    }

    private function insertAvailability(StockableInterface $item): self {
        $clauses = [];
        $binders = [];
        foreach ($item->getAvailability()->asArray() as $stock) {
            $progressive_identifier = count($clauses) + 1;
            $clauses[] = '(:warehouse_identifier_' . $progressive_identifier
                . ', :product_identifier_' . $progressive_identifier
                . ', :quantity_' . $progressive_identifier
                . ', :is_unlimited_' . $progressive_identifier . ')'
            ;
            $quantity = $stock->getQuantity()->accept($this->quantity_converter);
            $binders[':warehouse_identifier_' . $progressive_identifier] = $stock->getWarehouse()->getIdentifier();
            $binders[':product_identifier_' . $progressive_identifier] = $item->getIdentifier();
            $binders[':quantity_' . $progressive_identifier] = $quantity['quantity'];
            $binders[':is_unlimited_' . $progressive_identifier] = $quantity['is_unlimited'] ? 1 : 0;
        }
        if (!empty($clauses)) {
            $query = 'INSERT INTO stocks(wharehouseid, productid, quantity, unlimited) VALUES '
                . implode(', ', $clauses);
            $sth = $this->connection->getDbh()->prepare($query);
            $sth->execute($binders);
        }
        return $this;
    }

    private function insertPricing(SellableInterface $item): self {
        $clauses = [];
        $binders = [];
        foreach ($item->getPricing()->asArray() as $price) {
            $progressive_identifier = count($clauses) + 1;
            $clauses[] = '(:group_identifier_' . $progressive_identifier
                . ', :currency_' . $progressive_identifier
                . ', :listing_price_' . $progressive_identifier
                . ', :selling_price_' . $progressive_identifier
                . ', :product_identifier_' . $progressive_identifier
                . ', :product_external_identifier_' . $progressive_identifier
                . ', :minimum_quantity_' . $progressive_identifier . ')'
            ;
            $binders[':group_identifier_' . $progressive_identifier] = $price->getCustomerGroup()->getIdentifier();
            $binders[':currency_' . $progressive_identifier] = $price->getCurrency();
            $binders[':listing_price_' . $progressive_identifier] = $price->getListingPrice();
            $binders[':selling_price_' . $progressive_identifier] = $price->getSellingPrice();
            $binders[':product_identifier_' . $progressive_identifier] = $item->getIdentifier();
            $binders[':product_external_identifier_' . $progressive_identifier] = $item->getExternalIdentifier();
            $binders[':minimum_quantity_' . $progressive_identifier] = $price->getMinimumQuantity();
        }
        if (!empty($clauses)) {
            $query = 'INSERT INTO prices(groupid, currency, price, specialprice, productid, externalproductidstr, quantityfrom) VALUES '
                . implode(', ', $clauses);
            $sth = $this->connection->getDbh()->prepare($query);
            $sth->execute($binders);
        }
        return $this;
    }
}