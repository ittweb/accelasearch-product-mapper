<?php declare(strict_types=1);
use PHPUnit\Framework\TestCase;
use \Ittweb\AccelaSearch\ProductMapper\Model\Stock\Limited;

final class QuantityLimitedTest extends TestCase {
    public function testIsUnlimited() {
        $quantity = new Limited(10.0);
        $this->assertFalse($quantity->isUnlimited());
    }

    public function testGetQuantity() {
        $quantity = new Limited(10.0);
        $this->assertEquals(10.0, $quantity->getQuantity());
    }

    public function testSetQuantity() {
        $quantity = new Limited(10.0);
        $quantity->setQuantity(20.0);
        $this->assertEquals(20.0, $quantity->getQuantity());
    }
}
