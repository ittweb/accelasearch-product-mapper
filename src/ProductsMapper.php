<?php
/**
 * Products Data Mapper for AccelaSearch.
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

    /** Connection to collector database. */
    private $dbh;


    /**
     * Constructor.
     *
     * @param string $api_key API key provided by AccelaSearch
     * @param string $identifier_field_name Name of an attribute which may
     *                                      be used as identifier
     * @param int|null $shop_identifier Shop identifier provided by
     *                                 AccelaSearch; if seet to null or
     *                                 omitted, any shop will be used
     */
    public function __construct(string $api_key, string $identifier_field_name, int $shop_identifier = null) {
        $this->api_key = $api_key;
        $this->identifier_field_name = $identifier_field_name;

        // If no shop identifier was provided, a random one is chosen
        if (is_null($shop_identifier)) {
            $shops = $this->api('/shops');
            $shop_identifier = !empty($shops) ? $shops[0]['id'] : 0;
        }
        $this->shop_identifier = 'shop_' . $shop_identifier;

        // Connects to collector
        $collector = $this->getCollector();

        // Connects to collector database
        $this->dbh = new \PDO('mysql:dbname=' . $collector['name']  . ';host=' . $collector['hostname'] . ';charset=UTF8', $collector['username'], $collector['password']);
        $this->dbh->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
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


    /**
     * Performs an API call.
     *
     * @param string $endpoint Endpoint to call
     * @return array Response of API
     */
    protected function api(string $endpoint): array {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://accelasearch.dev1.accelasearch.net/API' . $endpoint);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'X-AccelaSearch-apikey: ' . $this->api_key
        ]);

        return json_decode(curl_exec($ch), true);
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

        return $this;
    }


    public function update(array $data): self {
        if ($this->isProduct($data)) {
            $data = [$data];
        }

        return $this;
    }


    public function delete(array $data): self {
        if ($this->isProduct($data)) {
            $data = [$data];
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
