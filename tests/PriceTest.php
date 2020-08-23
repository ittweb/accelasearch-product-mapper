<?php declare(strict_types=1);
use PHPUnit\Framework\TestCase;
use \Ittweb\AccelaSearch\ProductMapper\Model\Price\Price;

final class PriceTest extends TestCase {
    public function testGetListingPrice() {
        $price = new Price(10.0);
        $this->assertEquals(10.0, $price->getListingPrice());
    }

    public function testGetSellingPrice() {
        $price = new Price(10.0);
        $this->assertEquals(10.0, $price->getSellingPrice());
    }

    public function testSetSellingPrice() {
        $price = new Price(10.0);
        $price->setSellingPrice(5.0);
        $this->assertEquals(5.0, $price->getSellingPrice());
    }

    public function testAsArray() {
        $price = new Price(10.0);
        $price->setSellingPrice(5.0);
        $this->assertEquals(['listing_price' => 10.0, 'selling_price' => 5.0], $price->asArray());
    }
}
