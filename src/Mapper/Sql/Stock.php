<?php
namespace Ittweb\AccelaSearch\ProductMapper\Mapper\Sql;
use \PDO;
use \Ittweb\AccelaSearch\ProductMapper\Model\Stock\StockInfo;
use \Ittweb\AccelaSearch\ProductMapper\Model\Stock\Virtual;
use \Ittweb\AccelaSearch\ProductMapper\Model\Stock\Physical;
use \Ittweb\AccelaSearch\ProductMapper\Model\Stock\Limited;
use \Ittweb\AccelaSearch\ProductMapper\Model\Stock\Unlimited;

class Stock {
    private $dbh;
    private $read_sth;

    public function __construct(PDO $dbh) {
        $this->dbh = $dbh;
        $this->prepareStatements();
    }

    public function read(int $product_id): StockInfo {
        $this->read_sth->execute([
            ':id' => $product_id
        ]);
        $stock = new StockInfo();
        foreach ($this->read_sth->fetchAll() as $record) {
            $warehouse = $record['is_virtual']
                       ? new Virtual($record['warehouse_id'])
                       : new Physical($record['warehouse_id'], $record['latitude'], $record['longitude']);
            $quantity = $record['is_unlimited']
                      ? new Unlimited()
                      : new Limited($record['quantity']);
            $stock->add($warehouse, $quantity);
        }
        return $stock;
    }

    private function prepareStatements() {
        $this->read_sth = $this->dbh->prepare(
            'SELECT warehouse_id, is_virtual, latitude, longitude, is_unlimited, quantity '
            . 'FROM stock_info JOIN warehouse ON stock_info.warehouse_id = warehouse.id '
            . 'WHERE stock_info.product_id = :id'
        );
    }
}
