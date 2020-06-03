<?php declare(strict_types=1);
use PHPUnit\Framework\TestCase;

final class GroupPriceTest extends TestCase {
    public function testGetTiers() {
        $price = new \Ittweb\AccelaSearch\Model\GroupPrice();
        $this->assertTrue(empty($price->getTiers()));
    }
    
    public function testSetGetTier() {
        $price = new \Ittweb\AccelaSearch\Model\GroupPrice();
        $tier = new \Ittweb\AccelaSearch\Model\TierPrice();
        $price->setTierForQuantity(0.0, $tier);
        $tier = $price->getTierByQuantity(0.0);
        $this->assertInstanceOf('\Ittweb\AccelaSearch\Model\TierPrice', $tier);
    }
    
    public function testUnsetTier() {
        $price = new \Ittweb\AccelaSearch\Model\GroupPrice();
        $tier = new \Ittweb\AccelaSearch\Model\TierPrice();
        $price->setTierForQuantity(5.0, $tier);
        $price->unsetTierForQuantity(0.0);
        $this->assertTrue(empty($price->getTiers()));
    }
}
