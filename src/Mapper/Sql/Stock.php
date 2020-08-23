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
    private $create_sth;
    private $read_sth;
    private $delete_external_sth;

    public function __construct(PDO $dbh) {
        $this->dbh = $dbh;
        $this->prepareStatements();
    }

    public function create(StockInfo $stock, int $product_id, string $product_external_id, int $shop_id) {
        foreach ($stock->getStockAsDisctionary() as $warehouse_id => $stock_info) {
            $this->create_sth->execute([
                ':product_id' => $product_id,
                ':product_external_id' => $product_external_id,
                ':shop_id' => $shop_id,
                ':warehouse_id' => $warehouse_id,
                ':quantity' => !$stock_info['quantity']->isUnlimited() ? $stock_info['quantity']->getQuantity() : 0.0,
                ':is_unlimited' => $stock_info['quantity']->isUnlimited() ? 1 : 0
            ]);
        }
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

    public function deleteByExternalId(string $external_id, int $shop_id) {
        $this->delete_external_sth->execute([
            ':product_external_id' => $external_id,
            ':shop_id' => $shop_id
        ]);
    }

    private function prepareStatements() {
        $this->create_sth = $this->dbh->prepare(
            'INSERT INTO stock_info(product_id, product_external_id, shop_id, warehouse_id, quantity, is_unlimited) '
          . 'VALUES(:product_id, :product_external_id, :shop_id, :warehouse_id, :quantity, :is_unlimited)'
        );
        $this->read_sth = $this->dbh->prepare(
            'SELECT warehouse_id, is_virtual, latitude, longitude, is_unlimited, quantity '
            . 'FROM stock_info JOIN warehouse ON stock_info.warehouse_id = warehouse.id '
            . 'WHERE stock_info.product_id = :id'
        );
        $this->delete_external_sth = $this->dbh->prepare(
            'DELETE FROM stock_info WHERE product_external_id = :product_external_id AND shop_id = :shop_id'
        );
    }
}
