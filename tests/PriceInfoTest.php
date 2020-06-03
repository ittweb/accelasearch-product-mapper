<?php declare(strict_types=1);
use PHPUnit\Framework\TestCase;

final class PriceInfoTest extends TestCase {
    public function testConstruct() {
        $price = new \Ittweb\AccelaSearch\Model\PriceInfo();
        $this->assertInstanceOf('\Ittweb\AccelaSearch\Model\PriceInfo', $price);
    }
}
