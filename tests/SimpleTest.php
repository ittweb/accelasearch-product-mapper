<?php declare(strict_types=1);
use PHPUnit\Framework\TestCase;
use \Ittweb\AccelaSearch\ProductMapper\Model\Simple;
use \Ittweb\AccelaSearch\ProductMapper\Model\Price\MultiGroupPrice;
use \Ittweb\AccelaSearch\ProductMapper\Model\Stock\StockInfo;

final class SimpleTest extends TestCase {
    public function testAsArray() {
        $stock = new StockInfo();
        $price = new MultiGroupPrice();
        $item = new Simple($stock, $price);
        $item->url = 'https://www.site.com';
        $this->assertEquals(['header' => ['type' => 'simple'], 'data' => ['url' => 'https://www.site.com'], 'configurable_attributes' => [], 'warehouses' => [], 'pricing' => []], $item->asArray());
    }
}
