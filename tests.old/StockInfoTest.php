<?php declare(strict_types=1);
use PHPUnit\Framework\TestCase;

final class StockInfoTest extends TestCase {
    public function testIsUnlimited() {
        $stock_info = new \Ittweb\AccelaSearch\Model\StockInfo(0.0, true);
        $this->assertTrue($stock_info->isUnlimited());
    }
    
    public function testgetQuantity() {
        $stock_info = new \Ittweb\AccelaSearch\Model\StockInfo(10.0, false);
        $this->assertEquals($stock_info->getQuantity(), 10.0);
    }
    
    public function testMakeUnlimited() {
        $stock_info = new \Ittweb\AccelaSearch\Model\StockInfo(0.0, false);
        $stock_info->makeUnlimited();
        $this->assertTrue($stock_info->isUnlimited());
    }
    
    public function testMakeLimited() {
        $stock_info = new \Ittweb\AccelaSearch\Model\StockInfo(0.0, true);
        $stock_info->makeLimited();
        $this->assertFalse($stock_info->isUnlimited());
    }
    
    public function testSetQuantity() {
        $stock_info = new \Ittweb\AccelaSearch\Model\StockInfo(0.0, false);
        $stock_info->setQuantity(10.0);
        $this->assertEquals(10.0, $stock_info->getQuantity());
    }
}
