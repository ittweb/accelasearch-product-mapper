<?php declare(strict_types=1);
use PHPUnit\Framework\TestCase;

final class StockableTraitTest extends TestCase {
    public function testGetStockInfo() {
        $entity = new \Ittweb\AccelaSearch\Model\Simple();
        $this->assertTrue(empty($entity->getStockInfo()));
    }
    
    public function testSetGetStockInfo() {
        $entity = new \Ittweb\AccelaSearch\Model\Simple();
        $stock_info = new \Ittweb\AccelaSearch\Model\StockInfo();
        $entity->addStockInfo('warehouse', $stock_info);
        $this->assertInstanceOf('\Ittweb\AccelaSearch\Model\StockInfo', $entity->getStockInfoForWarehouse('warehouse'));
    }
    
    public function testRemoveStockInfo() {
        $entity = new \Ittweb\AccelaSearch\Model\Simple();
        $stock_info = new \Ittweb\AccelaSearch\Model\StockInfo();
        $entity->addStockInfo('warehouse', $stock_info);
        $entity->removeStockInfoForWarehouse('warehouse');
        $this->assertTrue(empty($entity->getStockInfo()));
    }
}
