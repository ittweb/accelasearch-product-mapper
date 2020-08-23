<?php declare(strict_types=1);
use PHPUnit\Framework\TestCase;
use \Ittweb\AccelaSearch\ProductMapper\Model\Stock\Unlimited;

final class QuantityUnlimitedTest extends TestCase {
    public function testIsUnlimited() {
        $quantity = new Unlimited();
        $this->assertTrue($quantity->isUnlimited());
    }
}
