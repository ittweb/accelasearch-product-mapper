<?php declare(strict_types=1);
use PHPUnit\Framework\TestCase;
use \Ittweb\AccelaSearch\ProductMapper\Model\Stock\Limited;
use \Ittweb\AccelaSearch\ProductMapper\Model\Stock\Unlimited;
use \Ittweb\AccelaSearch\ProductMapper\Model\Stock\Virtual;
use \Ittweb\AccelaSearch\ProductMapper\Model\Stock\Physical;
use \Ittweb\AccelaSearch\ProductMapper\Model\Stock\StockInfo;
use \Ittweb\AccelaSearch\ProductMapper\Mapper\Dictionary\Stock as StockMapper;

final class MapperDictionaryStockTest extends TestCase {
    public function testCreate() {
        $stock = new StockInfo();
        $warehouse = new Physical('warehouse_id', 1.0, 2.0);
        $quantity = new Limited(10.0);
        $stock->add($warehouse, $quantity);
        $mapper = new StockMapper();
        $this->assertEquals($stock->asArray(), $mapper->create($stock));
    }

    public function testRead() {
        $stock = new StockInfo();
        $warehouse = new Physical('warehouse_id', 1.0, 2.0);
        $quantity = new Limited(10.0);
        $stock->add($warehouse, $quantity);
        $mapper = new StockMapper();
        $this->assertEquals($stock, $mapper->read($stock->asArray()));
    }
}
