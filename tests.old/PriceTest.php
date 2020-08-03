<?php declare(strict_types=1);
use PHPUnit\Framework\TestCase;

final class PriceTest extends TestCase {
    public function testGetListingPrice() {
        $price = new \Ittweb\AccelaSearch\Model\Price(1.0);
        $this->assertEquals($price->getListingPrice(), 1.0);
    }
    
    public function testGetSellingPrice() {
        $price = new \Ittweb\AccelaSearch\Model\Price(1.0, 0.5);
        $this->assertEquals($price->getSellingPrice(), 0.5);
    }
    
    public function testGetDefaultSellingPrice() {
        $price = new \Ittweb\AccelaSearch\Model\Price(1.0);
        $this->assertEquals($price->getSellingPrice(), 1.0);
    }
    
    public function testSetListingPrice() {
        $price = new \Ittweb\AccelaSearch\Model\Price(1.0);
        $price->setListingPrice(10.0);
        $this->assertEquals($price->getListingPrice(), 10.0);
    }
    
    public function testSetSellingPrice() {
        $price = new \Ittweb\AccelaSearch\Model\Price(1.0, 0.5);
        $price->setSellingPrice(10.0);
        $this->assertEquals($price->getSellingPrice(), 10.0);
    }
}
