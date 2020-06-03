<?php declare(strict_types=1);
use PHPUnit\Framework\TestCase;

final class TierPriceTest extends TestCase {
    public function testGetPrices() {
        $tier_price = new \Ittweb\AccelaSearch\Model\TierPrice();
        $this->assertTrue(empty($tier_price->getPrices()));
    }
    
    public function testSetGetPriceForCurrency() {
        $tier_price = new \Ittweb\AccelaSearch\Model\TierPrice();
        $price = new \Ittweb\AccelaSearch\Model\Price(10.0);
        $tier_price->setPriceForCurrency('eur', $price);
        $this->assertEquals(10.0, $tier_price->getPriceForCurrency('eur')->getListingPrice());
    }
    
    public function testUnsetPriceForCurrency() {
        $tier_price = new \Ittweb\AccelaSearch\Model\TierPrice();
        $price = new \Ittweb\AccelaSearch\Model\Price(10.0);
        $tier_price->setPriceForCurrency('eur', $price);
        $tier_price->unsetPriceForCurrency('eur');
        $this->expectException('\OutOfBoundsException');
        $tier_price->getPriceForCurrency('eur');
    }
}
