<?php declare(strict_types=1);
use PHPUnit\Framework\TestCase;
use \Ittweb\AccelaSearch\ProductMapper\Model\Stock\StockInfo;
use \Ittweb\AccelaSearch\ProductMapper\Model\Stock\Physical;
use \Ittweb\AccelaSearch\ProductMapper\Model\Stock\Limited;

final class StockInfoTest extends TestCase {
    public function testGetStockAsDictionary() {
        $quantity = new Limited(10.0);
        $warehouse = new Physical('warehouse_id', 1.0, 2.0);
        $info = new StockInfo();
        $info->add($warehouse, $quantity);
        $this->assertEquals(['warehouse_id' => ['warehouse' => $warehouse, 'quantity' => $quantity]], $info->getStockAsDisctionary());
    }

    public function testAsArray() {
        $quantity = new Limited(10.0);
        $warehouse = new Physical('warehouse_id', 1.0, 2.0);
        $info = new StockInfo();
        $info->add($warehouse, $quantity);
        $this->assertEquals([['warehouse_id' => 'warehouse_id', 'is_virtual' => false, 'is_unlimited' => false, 'position' => [1.0, 2.0], 'quantity' => 10.0]], $info->asArray());
    }

    public function testAdd() {
        $quantity = new Limited(10.0);
        $warehouse = new Physical('warehouse_id', 1.0, 2.0);
        $info = new StockInfo();
        $size = count($info->getStockAsDisctionary());
        $info->add($warehouse, $quantity);
        $this->assertEquals($size + 1, count($info->getStockAsDisctionary()));
    }

    public function testRemove() {
        $quantity = new Limited(10.0);
        $warehouse = new Physical('warehouse_id', 1.0, 2.0);
        $info = new StockInfo();
        $info->add($warehouse, $quantity);
        $size = count($info->getStockAsDisctionary());
        $info->remove($warehouse);
        $this->assertEquals($size - 1, count($info->getStockAsDisctionary()));
    }
}
