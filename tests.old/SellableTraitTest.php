<?php declare(strict_types=1);
use PHPUnit\Framework\TestCase;

final class SellableTraitTest extends TestCase {
    public function testGetPriceInfo() {
        $entity = new \Ittweb\AccelaSearch\Model\Simple();
        $this->assertInstanceOf('\Ittweb\AccelaSearch\Model\PriceInfo', $entity->getPriceInfo());
    }
}
