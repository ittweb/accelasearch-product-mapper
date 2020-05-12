<?php
/**
 * Products Data Mapper for AccelaSearch.
 *
 * Facilitates integration with AccelaSearch
 */
namespace Ittweb\AccelaSearch;

/**
 * Products Data Mapper for AccelaSearch.
 */
class ProductsMapper
{
    /** string API key provided by AccelaSearch. */
    private $api_key;

    /** Name of the field used as identifier. */
    private $identifier_field_name;

    /** Identifier of the shop. */
    private $shop_identifier;

    /** Lookup table of product types. */
    private $product_types;

    /** Connection to collector database. */
    private $dbh;

    /** Prepared product insertion statement. */
    private $sth_insert;

    /** Prepared product update statement. */
    private $sth_update;

    /** Prepared stock info insertion statement. */
    private $sth_stock;

    /** Prepared price info insertion statement. */
    private $sth_price;


    /**
     * Constructor.
     *
     * @param string $api_key API key provided by AccelaSearch
     * @param string $identifier_field_name Name of an attribute which may
     *                                      be used as identifier
     * @param int|null $shop_identifier Shop identifier provided by
     *                                 AccelaSearch; if seet to null or
     *                                 omitted, any shop will be used
     * @throws AccelaSearchException
     */
    public function __construct(string $api_key, string $identifier_field_name, int $shop_identifier = null) {
        $this->api_key = $api_key;
        $this->identifier_field_name = $identifier_field_name;

        // If no shop identifier was provided, a random one is chosen
        if (is_null($shop_identifier)) {
            $shops = $this->api('/shops');
            if (empty($shops)) {
                throw new AccelaSearchException("No shops for this customer.", 0);
            }
            $shop_identifier = $shops[0]['id'];
        }
        $this->shop_identifier = $shop_identifier;

        // Connects to collector
        $collector = $this->getCollector();

        // Connects to collector database
        $this->dbh = new \PDO('mysql:dbname=' . $collector['name']  . ';host=' . $collector['hostname'] . ';charset=UTF8', $collector['username'], $collector['password']);
        $this->dbh->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

        // Reads product types
        $sth = $this->dbh->query('SELECT id, name FROM product_type');
        $this->product_types = $sth->fetchAll(\PDO::FETCH_ASSOC);

        // Prepares statements
        $this->sth_insert = $this->dbh->prepare('INSERT INTO product(type_id, parent_id, shop_id) VALUES(:type_id, :parent_id, :shop_id)');
        $this->sth_stock = $this->dbh->prepare('INSERT INTO stock_info(product_id, warehouse_id, quantity, is_unlimited) VALUES(:product_id, :warehouse_id, :quantity, :is_unlimited)');
        $this->sth_price = $this->dbh->prepare('INSERT INTO price_info(product_id, group_id, minimum_quantity, currency, listing_price, selling_price) VALUES(:product_id, :group_id, :minimum_quantity, :currency, :listing_price, :selling_price)');

        $this->sth_read_ids = $this->dbh->prepare('SELECT product_id FROM attribute_text WHERE name = :name AND value = :value');
        $this->sth_update = $this->dbh->prepare('UPDATE product SET updated_at = CURRENT_TIMESTAMP WHERE id = :id');
        $this->sth_clear_stock = $this->dbh->prepare('DELETE FROM stock_info WHERE product_id = :id');
        $this->sth_clear_price = $this->dbh->prepare('DELETE FROM price_info WHERE product_id = :id');
        $this->sth_clear_children = $this->dbh->prepare('DELETE FROM product WHERE parent_id = :id');
        $this->sth_clear_attribute_integer = $this->dbh->prepare('DELETE FROM attribute_integer WHERE product_id = :id');
        $this->sth_clear_attribute_real = $this->dbh->prepare('DELETE FROM attribute_real WHERE product_id = :id');
        $this->sth_clear_attribute_date = $this->dbh->prepare('DELETE FROM attribute_date WHERE product_id = :id');
        $this->sth_clear_attribute_text = $this->dbh->prepare('DELETE FROM attribute_text WHERE product_id = :id');
    }


    /**
     * Tells whether an array is a valid product.
     *
     * @param array $data Data to check
     * @return bool True if and only if array is a product
     */
    protected function isProduct(array $data): bool {
        return array_key_exists($this->identifier_field_name, $data);
    }


    protected function getTypeId(string $type_name): int {
        foreach ($this->product_types as $type) {
            if ($type['name'] === $type_name) {
                return $type['id'];
            }
        }

        throw new AccelaSearchException('Invalid type name "' . $type_name . '".');
    }


    /**
     * Performs an API call.
     *
     * @param string $endpoint Endpoint to call
     * @param string $method Request method, default GET
     * @return array Response of API
     * @throws AccelaSearchException In case of API error
     */
    protected function api(string $endpoint, string $method = 'GET'): array {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://accelasearch.dev1.accelasearch.net/API' . $endpoint);
        curl_setopt($ch, CURLOPT_POST, $method === 'POST');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'X-AccelaSearch-apikey: ' . $this->api_key
        ]);
        $response = curl_exec($ch);

        if (curl_errno($ch)) {
            throw new AccelaSearchException(curl_error($ch));
        }
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        if ($http_code !== 200) {
            throw new AccelaSearchException('HTTP error, endopoint replied with code ' . $http_code);
        }
        curl_close($ch);

        $data = json_decode($response, true);
        if (array_key_exists('error', $data)) {
            throw new AccelaSearchException($data['error'], 0);
        }

        return $data;
    }


    protected function insertTypedAttributes(array $attributes, int $product_id, string $type): self {
        if (!empty($attributes)) {
            $query = 'INSERT INTO attribute_' . $type . '(product_id, name, value) VALUES (?, ?, ?)'
                   . str_repeat(', (?, ?, ?)', count($attributes) - 1);
            $sth = $this->dbh->prepare($query);
            $values = [];
            foreach ($attributes as $attribute) {
                if (!is_null($attribute['value']) && $attribute['value'] !== '') {
                    $values[] = $product_id;
                    $values[] = $attribute['name'];
                    $values[] = $attribute['value'];
                }
            }
            $sth->execute($values);
        }

        return $this;
    }



    protected function insertAttributes(array $product, int $product_id): self {
        $text_attributes = [];
        $integer_attributes = [];
        $real_attributes = [];
        $date_attributes = [];
        foreach ($product as $name => $values) {
            if (strpos($name, '_') !== false) {
                continue;
            }

            if (!is_array($values)) {
                $values = [$values];
            }

            foreach ($values as $value) {
                if (preg_match('/\d{4}-\d{2}-\d{2}( \d{2}:\d{2}:\d{2})?/', $value) === 1 && $name !== $this->identifier_field_name) {
                    $date_attributes[] = ['name' => $name, 'value' => $value];
                }

                elseif (ctype_digit(strval($value)) && $name !== $this->identifier_field_name) {
                    $integer_attributes[] = ['name' => $name, 'value' => $value];
                }

                elseif (is_numeric($value) && floatval($value) && $name !== $this->identifier_field_name) {
                    $real_attributes[] = ['name' => $name, 'value' => floatval($value)];
                }

                else {
                    $text_attributes[] = ['name' => $name, 'value' => $value];
                }
            }
        }


        $this->insertTypedAttributes($text_attributes, $product_id, 'text');
        $this->insertTypedAttributes($integer_attributes, $product_id, 'integer');
        $this->insertTypedAttributes($real_attributes, $product_id, 'real');
        $this->insertTypedAttributes($date_attributes, $product_id, 'date');

        return $this;
    }



    protected function insertStockInfo(array $product, int $product_id): self {
        if (!array_key_exists('_warehouse', $product)) {
            return $this;
        }

        foreach ($product['_warehouse'] as $warehouse_id => $stock_info) {
            $is_unlimited = array_key_exists('is_unlimited', $stock_info)
                          ? $stock_info['is_unlimited'] == true
                          : !array_key_exists('quantity', $stock_info);
            $quantity = $is_unlimited
                      ? null
                      : (array_key_exists('quantity', $stock_info) && !empty($stock_info['quantity']) ? $stock_info['quantity'] : 0.0);
            $this->sth_stock->execute([
                ':product_id' => $product_id,
                ':warehouse_id' => $warehouse_id,
                ':quantity' => $quantity,
                ':is_unlimited' => $is_unlimited ? 1 : 0
            ]);
        }

        return $this;
    }



    protected function insertPriceInfo(array $product, int $product_id): self {
        if (!array_key_exists('_pricing', $product)) {
            return $this;
        }


        foreach ($product['_pricing'] as $group_id => $group_info) {
            foreach ($group_info as $tier_info) {
                $minimum_quantity = array_key_exists('minimum_quantity', $tier_info)
                                  ? $tier_info['minimum_quantity'] 
                                  : 0.0;
                $currency = array_key_exists('currency', $tier_info)
                          ? $tier_info['currency']
                          : 'EUR';
                $listing_price = array_key_exists('listing_price', $tier_info)
                               ? $tier_info['listing_price']
                               : 0.0;
                $selling_price = array_key_exists('selling_price', $tier_info)
                               ? $tier_info['selling_price']
                               : $listing_price;
                $this->sth_price->execute([
                    ':product_id' => $product_id,
                    ':group_id' => $group_id,
                    ':minimum_quantity' => $minimum_quantity,
                    ':currency' => strtoupper($currency),
                    ':listing_price' => $listing_price,
                    ':selling_price' => $selling_price
                ]);
            }
        }


        return $this;
    }



    protected function insertProduct(array $product, int $parent_id = null): self {
        // Ensures type is set, default is 'simple'
        if (!array_key_exists('_type', $product)) {
            $product['_type'] = 'simple';
        }

        $type_id = $this->getTypeId($product['_type']);
        $this->sth_insert->execute([
            ':type_id' => $type_id,
            ':parent_id' => $parent_id,
            ':shop_id' => 'shop_' . $this->shop_identifier
        ]);
        $product_id = $this->dbh->lastInsertId();

        $this->insertAttributes($product, $product_id);
        $this->insertStockInfo($product, $product_id);
        $this->insertPriceInfo($product, $product_id);


        if ($product['_type'] === 'configurable' && array_key_exists('_variants', $product)) {
            foreach ($product['_variants'] as $variant) {
                $this->insertProduct($variant, $product_id);
            }
        }

        if ($product['_type'] === 'bundle' && array_key_exists('_bundle', $product)) {
            foreach ($product['_bundle'] as $bundle) {
                $this->insertProduct($bundle, $product_id);
            }
        }

        return $this;
    }



    protected function updateProduct(array $product): self {
        // Ensures type is set, default is 'simple'
        if (!array_key_exists('_type', $product)) {
            $product['_type'] = 'simple';
        }


        $this->sth_read_ids->execute([
            ':name' => $this->identifier_field_name,
            ':value' => $product[$this->identifier_field_name]
        ]);
        $ids = $this->sth_read_ids->fetchAll(\PDO::FETCH_ASSOC);

        foreach ($ids as $row) {
            $product_id = $row['product_id'];
            $identifier = [':id' => $product_id];

            $this->sth_update->execute($identifier);
            $this->sth_clear_stock->execute($identifier);
            $this->sth_clear_price->execute($identifier);
            $this->sth_clear_attribute_integer->execute($identifier);
            $this->sth_clear_attribute_real->execute($identifier);
            $this->sth_clear_attribute_date->execute($identifier);
            $this->sth_clear_attribute_text->execute($identifier);
            $this->sth_clear_children->execute($identifier);

            $this->insertAttributes($product, $product_id);
            $this->insertStockInfo($product, $product_id);
            $this->insertPriceInfo($product, $product_id);

            if ($product['_type'] === 'configurable' && array_key_exists('_variants', $product)) {
                foreach ($product['_variants'] as $variant) {
                    $this->insertProduct($variant, $product_id);
                }
            }

            if ($product['_type'] === 'bundle' && array_key_exists('_bundle', $product)) {
                foreach ($product['_bundle'] as $bundle) {
                    $this->insertProduct($bundle, $product_id);
                }
            }
        }

        return $this;
    }



    protected function deleteProduct(array $product): self {
        $query = 'UPDATE product SET deleted_at = CURRENT_TIMESTAMP WHERE id IN (SELECT product_id FROM attribute_text WHERE name = :name AND value = :value)';
        $sth = $this->dbh->prepare($query);
        $sth->execute([':name' => $this->identifier_field_name, ':value' => $product[$this->identifier_field_name]]);

        return $this;
    }



    /**
     * Acquires lock to insert/update/delete product information.
     *
     * @return self This mapper
     */
    public function lock(): self {
        $this->api('/shops/' . $this->shop_identifier . '/synchronization/start', 'POST');

        return $this;
    }


    /**
     * Releases lock to insert/update/delete product information.
     *
     * @return self This mapper
     */
    public function unlock(): self {
        $this->api('/shops/' . $this->shop_identifier . '/synchronization/end', 'POST');

        return $this;
    }


    /**
     * Returns collector of customer.
     *
     * @return array Collector database
     */
    public function getCollector(): array {
        return $this->api('/collector');
    }


    /**
     * Returns selected shop of customer.
     *
     * @return array Shop information
     */
    public function getShop(): array {
        return $this->api('/shops/' . $this->shop_identifier);
    }


    /**
     * Returns warehouses of customer.
     *
     * @return array List of warehouses
     */
    public function getWarehouses(): array {
        $query = 'SELECT id, name FROM warehouse';
        $sth = $this->dbh->query($query);

        $warehouses = [];
        while (($row = $sth->fetch(\PDO::FETCH_ASSOC)) !== false) {
            $warehouses[$row['id']] = $row['name'];
        }

        return $warehouses;
    }


    /**
     * Returns customer groups.
     *
     * @return array List of customer groups
     */
    public function getCustomerGroups(): array {
        $query = 'SELECT * FROM `group`';
        $sth = $this->dbh->query($query);

        $groups = [];
        while (($row = $sth->fetch(\PDO::FETCH_ASSOC)) !== false) {
            $groups[$row['id']] = $row['name'];
        }

        return $groups;
    }


    public function insert(array $data): self {
        if ($this->isProduct($data)) {
            $data = [$data];
        }

        foreach ($data as $product) {
            if (!is_array($product) || !$this->isProduct($product)) {
                throw new AccelaSearchException('Missing identifier field');
            }
            $this->insertProduct($product);
        }

        return $this;;
    }


    public function update(array $data): self {
        if ($this->isProduct($data)) {
            $data = [$data];
        }

        foreach ($data as $product) {
            if (!is_array($product) || !$this->isProduct($product)) {
                throw new AccelaSearchException('Missing identifier field');
            }
            $this->updateProduct($product);
        }

        return $this;
    }


    public function delete(array $data): self {
        if ($this->isProduct($data)) {
            $data = [$data];
        }

        foreach ($data as $product) {
            if (!is_array($product) || !$this->isProduct($product)) {
                throw new AccelaSearchException('Missing identifier field');
            }
            $this->deleteProduct($product);
        }

        return $this;
    }


    /**
     * Soft deletes every product in the shop.
     *
     * @return self This mapper
     */
    public function clear(): self {
        $sth = $this->dbh->prepare('UPDATE product SET deleted_at = CURRENT_TIMESTAMP() WHERE shop_id = :shop_id');
        $sth->execute([':shop_id' => $this->shop_identifier]);

        return $this;
    }
}
