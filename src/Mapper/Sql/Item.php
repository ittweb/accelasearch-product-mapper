<?php
namespace Ittweb\AccelaSearch\ProductMapper\Mapper\Sql;
use \Ittweb\AccelaSearch\ProductMapper\Model\ItemInterface;
use \Ittweb\AccelaSearch\ProductMapper\Model\ProductInterface;
use \Ittweb\AccelaSearch\ProductMapper\Model\Simple;
use \Ittweb\AccelaSearch\ProductMapper\Model\Virtual;
use \Ittweb\AccelaSearch\ProductMapper\Model\Downloadable;
use \Ittweb\AccelaSearch\ProductMapper\Model\Configurable;
use \Ittweb\AccelaSearch\ProductMapper\Model\Bundle;
use \Ittweb\AccelaSearch\ProductMapper\Model\Grouped;
use \Ittweb\AccelaSearch\ProductMapper\Model\Page;
use \Ittweb\AccelaSearch\ProductMapper\Model\Category;
use \Ittweb\AccelaSearch\ProductMapper\Model\Banner;
use \Ittweb\AccelaSearch\ProductMapper\Model\Stock\Physical as PhysicalWarehouse;
use \Ittweb\AccelaSearch\ProductMapper\Model\Stock\Virtual as VirtualWarehouse;
use \Ittweb\AccelaSearch\ProductMapper\Model\Stock\Limited;
use \Ittweb\AccelaSearch\ProductMapper\Model\Stock\Unlimited;
use \Ittweb\AccelaSearch\ProductMapper\Model\Stock\StockInfo;
use \Ittweb\AccelaSearch\ProductMapper\Model\Price\Price;
use \Ittweb\AccelaSearch\ProductMapper\Model\Price\MultiCurrencyPrice;
use \Ittweb\AccelaSearch\ProductMapper\Model\Price\MultiTierPrice;
use \Ittweb\AccelaSearch\ProductMapper\Model\Price\MultiGroupPrice;
use \BadFunctionCallException;
use \OutOfBoundsException;
use \PDO;

class Item {
    const API_HOST = 'http://accelasearch.dev1.accelasearch.net';
    private $dbh;
    private $shop_id;
    private $identifier_attribute;
    private $category_field;
    private $product_types;
    private $attribute_types;
    private $read_product_sth;
    private $read_stock_sth;
    private $read_price_sth;
    private $search_by_parent_sth;

    public function __construct(PDO $dbh, int $shop_id, string $identifier_attribute) {
        $this->dbh = $dbh;
        $this->shop_id = $shop_id;
        $this->identifier_attribute = $identifier_attribute;
        $this->category_field = null;

        // Reads product types
        $this->product_types = [];
$s = microtime(true);
        $sth = $dbh->query('SELECT id, name FROM product_type');
echo "rPTypes: " . (microtime(true) - $s) . "\n";
        foreach ($sth->fetchAll() as $type) {
            $this->product_types[$type['name']] = $type['id'];
        };

        // Reads attribute types
        $this->attribute_types = [];
        $sth = $dbh->query('SELECT id, name FROM attribute_type');
        foreach ($sth->fetchAll() as $type) {
            $this->attribute_types[$type['name']] = $type['id'];
        };

        // Prepares statements
        $this->read_product_sth = $dbh->prepare(
            'SELECT id, product.type_id AS product_type, attribute_integer.type_id AS attribute_type, name, value FROM product JOIN attribute_integer ON product.id = attribute_integer.product_id WHERE product.shop_id = :shop_id AND product.id = :id AND product.deleted_at IS NULL '
            . 'UNION '
            . 'SELECT id, product.type_id AS product_type, attribute_real.type_id AS attribute_type, name, value FROM product JOIN attribute_real ON product.id = attribute_real.product_id WHERE product.shop_id = :shop_id AND product.id = :id AND product.deleted_at IS NULL '
            . 'UNION '
            . 'SELECT id, product.type_id AS product_type, attribute_date.type_id AS attribute_type, name, value FROM product JOIN attribute_date ON product.id = attribute_date.product_id WHERE product.shop_id = :shop_id AND product.id = :id AND product.deleted_at IS NULL '
            . 'UNION '
            . 'SELECT id, product.type_id AS product_type, attribute_text.type_id AS attribute_type, name, value FROM product JOIN attribute_text ON product.id = attribute_text.product_id WHERE product.shop_id = :shop_id AND product.id = :id AND product.deleted_at IS NULL '
        );
        $this->read_stock_sth = $dbh->prepare(
            'SELECT warehouse_id, is_virtual, latitude, longitude, is_unlimited, quantity '
            . 'FROM stock_info JOIN warehouse ON stock_info.warehouse_id = warehouse.id '
            . 'WHERE stock_info.product_id = :id'
        );
        $this->read_price_sth = $dbh->prepare(
            'SELECT currency, minimum_quantity, group_id, listing_price, selling_price '
            . 'FROM price_info WHERE product_id = :id'
        );
        $this->search_by_parent_sth = $dbh->prepare(
            'SELECT id FROM product WHERE parent_id = :parent_id'
        );
    }

    public static function getCredentialFromApiKey(string $api_key): array {
        $curl = curl_init();
        curl_setopt_array($curl, array(
          CURLOPT_URL => self::API_HOST . '/API/collector',
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => "GET",
          CURLOPT_HTTPHEADER => [
            'X-accelasearch-apikey: ' . $api_key
          ],
        ));
        $response = curl_exec($curl);
        curl_close($curl);
        return json_decode($response, true);
    }

    public function setShopId(int $shop_id) {
        $this->shop_id = $shop_id;
    }

    public function setIdentifierAttribute(string $name) {
        $this->identifier_attribute = $name;
    }

    public function setCategoryField(string $category_field) {
        $this->category_field = $category_field;
    }

    //public function create()

    public function read(string $identifier): ItemInterface {
$s = microtime(true);
        $this->read_product_sth->execute([
            ':shop_id' => $this->shop_id,
            ':id' => $identifier
        ]);
echo "rReadQuery: " . (microtime(true) - $s) . "\n";
        $data = $this->read_product_sth->fetchAll();
        if (empty($data)) {
            throw new OutOfBoundsException('No products with id "' . $identifier . '".');
        }
        //var_dump($data);
        switch (array_search($data[0]['product_type'], $this->product_types)) {
            case 'simple': return $this->readSimple($data);
            case 'virtual': return $this->readVirtual($data);
            case 'downloadable': return $this->readDownloadable($data);
            case 'configurable': return $this->readConfigurable($data);
            case 'bundle': return $this->readBundle($data);
            case 'grouped': return $this->readGrouped($data);
            case 'page': return $this->readPage($data);
            case 'category': return $this->readCategory($data);
            case 'banner': return $this->readBanner($data);
            default:
                throw new OutOfBoundsException('Unkknown product type.');
        }
    }

    //public function update()

    //public function delete()

    //public function hardDelete()

    public function searchByParentId(int $parent_id): array {
        $this->search_by_parent_sth->execute([':parent_id' => $parent_id]);
        $children = [];
        foreach ($this->search_by_parent_sth->fetchAll() as $record) {
            $children[] = $this->read($record['id']);
        }
        return $children;
    }

    private function readStock(int $product_id): StockInfo {
$s = microtime(true);
        $this->read_stock_sth->execute([
            ':id' => $product_id
        ]);
        $stock = new StockInfo();
        foreach ($this->read_stock_sth->fetchAll() as $record) {
            $warehouse = $record['is_virtual']
                       ? new VirtualWarehouse($record['warehouse_id'])
                       : new PhysicalWarehouse($record['warehouse_id'], $record['latitude'], $record['longitude']);
            $quantity = $record['is_unlimited']
                      ? new Unlimited()
                      : new Limited($record['quantity']);
            $stock->add($warehouse, $quantity);
        }
echo "rStock: " . (microtime(true) - $s) . "\n";
        return $stock;
    }

    private function readPrice(int $product_id): MultiGroupPrice {
$s = microtime(true);
        $this->read_price_sth->execute([':id' => $product_id]);
        $data = [];
        foreach ($this->read_price_sth->fetchAll() as $record) {
            $data[$record['group_id']][$record['minimum_quantity']][$record['currency']] = [
                'listing_price' => $record['listing_price'],
                'selling_price' => $record['selling_price']
            ];
        }
        $group_price = new MultiGroupPrice();
        foreach ($data as $group_id => $group_data) {
            $tier_price = new MultiTierPrice();
            foreach ($group_data as $minimum_quantity => $tier_data) {
                $currency_price = new MultiCurrencyPrice();
                foreach ($tier_data as $currency => $price_data) {
                    $price = new Price($price_data['listing_price'], $price_data['selling_price']);
                    $currency_price->add($currency, $price);
                }
                $tier_price->add($minimum_quantity, $currency_price);
            }
            $group_price->add($group_id, $tier_price);
        }
echo "rPrice: " . (microtime(true) - $s) . "\n";
        return $group_price;
    }

    private function readSimple($data): Simple {
        $product_id = $data[0]['id'];
        $stock = $this->readStock($product_id);
        $price = $this->readPrice($product_id);
$s = microtime(true);
        $item = new Simple($stock, $price);
        foreach ($data as $record) {
            $name = $record['name'];
            $value = $record['value'];
            $attributes[$name][] = $value;
            if (array_search($record['attribute_type'], $this->attribute_types) === 'variant') {
                $item->addConfigurableAttribute($name);
            }
        }
        foreach ($attributes as $name => $values) {
            $item->$name = count($values) === 1 ? $values[0] : $values;
        }
echo "rSimple: " . (microtime(true) - $s) . "\n";
        return $item;
    }

    private function readVirtual($data): Virtual {
        $product_id = $data[0]['id'];
        $stock = $this->readStock($product_id);
        $price = $this->readPrice($product_id);
        $item = new Virtual($stock, $price);
        foreach ($data as $record) {
            $name = $record['name'];
            $value = $record['value'];
            $attributes[$name][] = $value;
            if (array_search($record['attribute_type'], $this->attribute_types) === 'variant') {
                $item->addConfigurableAttribute($name);
            }
        }
        foreach ($attributes as $name => $values) {
            $item->$name = count($values) === 1 ? $values[0] : $values;
        }
        return $item;
    }

    private function readDownloadable($data): Downloadable {
        $product_id = $data[0]['id'];
        $stock = $this->readStock($product_id);
        $price = $this->readPrice($product_id);
        $item = new Downloadable($stock, $price);
        foreach ($data as $record) {
            $name = $record['name'];
            $value = $record['value'];
            $attributes[$name][] = $value;
            if (array_search($record['attribute_type'], $this->attribute_types) === 'variant') {
                $item->addConfigurableAttribute($name);
            }
        }
        foreach ($attributes as $name => $values) {
            $item->$name = count($values) === 1 ? $values[0] : $values;
        }
        return $item;
    }

    private function readConfigurable($data): Configurable {
        $product_id = $data[0]['id'];
        $stock = $this->readStock($product_id);
        $price = $this->readPrice($product_id);
        $item = new Configurable($stock, $price);
        foreach ($data as $record) {
            $name = $record['name'];
            $value = $record['value'];
            $attributes[$name][] = $value;
            if (array_search($record['attribute_type'], $this->attribute_types) === 'variant') {
                $item->addConfigurableAttribute($name);
            }
        }
        foreach ($attributes as $name => $values) {
            $item->$name = count($values) === 1 ? $values[0] : $values;
        }
        foreach ($this->searchByParentId($product_id) as $child) {
            $item->addConfiguration($child);
        }
        return $item;
    }

    private function readBundle($data): Bundle {
        $product_id = $data[0]['id'];
        $stock = $this->readStock($product_id);
        $price = $this->readPrice($product_id);
        $item = new Bundle($stock, $price);
        $attributes = [];
        foreach ($data as $record) {
            $name = $record['name'];
            $value = $record['value'];
            $attributes[$name][] = $value;
            if (array_search($record['attribute_type'], $this->attribute_types) === 'variant') {
                $item->addConfigurableAttribute($name);
            }
        }
        foreach ($attributes as $name => $values) {
            $item->$name = count($values) === 1 ? $values[0] : $values;
        }
        foreach ($this->searchByParentId($product_id) as $child) {
            $item->addComponent($child);
        }
        return $item;
    }

    private function readGrouped($data): Grouped {
        $product_id = $data[0]['id'];
        $stock = $this->readStock($product_id);
        $price = $this->readPrice($product_id);
        $item = new Grouped($stock, $price);
        foreach ($data as $record) {
            $name = $record['name'];
            $value = $record['value'];
            $attributes[$name][] = $value;
            if (array_search($record['attribute_type'], $this->attribute_types) === 'variant') {
                $item->addConfigurableAttribute($name);
            }
        }
        foreach ($attributes as $name => $values) {
            $item->$name = count($values) === 1 ? $values[0] : $values;
        }
        foreach ($this->searchByParentId($product_id) as $child) {
            $item->addComponent($child);
        }
        return $item;
    }

    private function readPage($data): Page {
        $item = new Page();
        foreach ($data as $record) {
            $name = $record['name'];
            $value = $record['value'];
            $item->$name = $value;
        }
        return $item;
    }

    private function readCategory($data): Category {
        $item = new Category();
        foreach ($data as $record) {
            $name = $record['name'];
            $value = $record['value'];
            $item->$name = $value;
        }
        return $item;
    }

    private function readBanner($data): Banner {
        $item = new Banner();
        foreach ($data as $record) {
            $name = $record['name'];
            $value = $record['value'];
            $item->$name = $value;
        }
        return $item;
    }
}
