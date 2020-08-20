<?php
namespace Ittweb\AccelaSearch\ProductMapper\Mapper\Sql;
use \Ittweb\AccelaSearch\ProductMapper\Model\ItemInterface;
use \BadFunctionCallException;
use \OutOfBoundsException;
use \PDO;

class Item {
    const API_HOST = 'http://accelasearch.dev1.accelasearch.net';
    private $dbh;
    private $shop_id;
    private $identifier_attribute;
    private $category_fields;
    private $configurable_fields;
    private $product_types;
    private $attribute_types;
    private $stock_mapper;
    private $price_mapper;
    private $writer;
    private $reader;
    private $updater;

    public function __construct(PDO $dbh, int $shop_id, string $identifier_attribute) {
        $this->dbh = $dbh;
        $this->shop_id = $shop_id;
        $this->identifier_attribute = $identifier_attribute;
        $this->category_fields = [];
        $this->configurable_fields = [];
        $this->readProductTypes();
        $this->readAttributeTypes();
        $this->stock_mapper = new Stock($dbh);
        $this->price_mapper = new Price($dbh);
        $this->writer = null;
        $this->reader = null;
        $this->updater = null;
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

    public function addCategoryField(string $category_field) {
        $this->category_fields[] = $category_field;
    }

    public function addConfigurableField(string $configurable_field) {
        $this->configurable_fields[] = $configurable_field;
    }

    public function create(ItemInterface $item) {
        $this->getWriter()->write($item);
    }

    public function read(int $identifier): ItemInterface {
        return $this->getReader()->read($identifier);
    }

    public function update(ItemInterface $item) {
        $this->getUpdater()->update($item);
    }

    public function delete(int $identifier) {
        $sth = $this->dbh->prepare(
            'UPDATE product SET deleted_at = CURRENT_TIMESTAMP() '
          . 'WHERE id = :product_id'
        );
        $sth->execute([':product_id' => $identifier]);
    }

    public function hardDelete() {
        $query = 'DELETE FROM product WHERE deleted_at IS NOT NULL';
        $this->dbh->query($query);
    }

    private function readProductTypes() {
        $this->product_types = [];
        $sth = $this->dbh->query('SELECT id, name FROM product_type');
        foreach ($sth->fetchAll() as $type) {
            $this->product_types[$type['name']] = $type['id'];
        };
    }

    private function readAttributeTypes() {
        $this->attribute_types = [];
        $sth = $this->dbh->query('SELECT id, name FROM attribute_type');
        foreach ($sth->fetchAll() as $type) {
            $this->attribute_types[$type['name']] = $type['id'];
        };
    }

    private function getReader(): ItemReader {
        if (is_null($this->reader)) {
            $this->reader = new ItemReader(
                $this->dbh,
                $this->shop_id,
                $this->product_types,
                $this->attribute_types,
                $this->stock_mapper,
                $this->price_mapper
            );
        }
        return $this->reader;
    }

    private function getWriter(): ItemWriter {
        if (is_null($this->writer)) {
            $this->writer = new ItemWriter(
                $this->dbh,
                $this->shop_id,
                $this->identifier_attribute,
                $this->category_fields,
                $this->configurable_fields,
                $this->product_types,
                $this->attribute_types,
                $this->stock_mapper,
                $this->price_mapper
            );
        }
        return $this->writer;
    }

    public function getUpdater(): ItemUpdater {
        if (is_null($this->updater)) {
            $this->updater = new ItemUpdater(
                $this->dbh,
                $this->shop_id,
                $this->identifier_attribute,
                $this->stock_mapper,
                $this->price_mapper,
                $this->getWriter()
            );
        }
        return $this->updater;
    }
}
