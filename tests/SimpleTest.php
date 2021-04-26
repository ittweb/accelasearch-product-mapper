<?php declare(strict_types=1);
use PHPUnit\Framework\TestCase;
use \Ittweb\AccelaSearch\ProductMapper\Simple;
use \Ittweb\AccelaSearch\ProductMapper\Stock\Availability;
use \Ittweb\AccelaSearch\ProductMapper\Price\Pricing;
use \Ittweb\AccelaSearch\ProductMapper\ImageInfo;

final class SimpleTest extends TestCase {
    public function testDefaultIsActive() {
        $product = $this->create();
        $this->assertEquals('url', $product->getUrl());
    }

    private function create(): Simple {
        return new Simple('url', 'id', new Availability(), new Pricing(), new ImageInfo());
    }
}
