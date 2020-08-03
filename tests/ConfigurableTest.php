<?php declare(strict_types=1);
use PHPUnit\Framework\TestCase;
use \Ittweb\AccelaSearch\ProductMapper\Model\Configurable;
use \Ittweb\AccelaSearch\ProductMapper\Model\Simple;
use \Ittweb\AccelaSearch\ProductMapper\Model\Stock\StockInfo;
use \Ittweb\AccelaSearch\ProductMapper\Model\Price\MultiGroupPrice;

final class ConfigurableTest extends TestCase {
    public function testAsArray() {
        $stock = new StockInfo();
        $price = new MultiGroupPrice();
        $simple = new Simple($stock, $price);
        $simple->url = 'https://www.site.com';
        $item = new Configurable($stock, $price);
        $item->addConfiguration($simple);
        $this->assertEquals(['header' => ['type' => 'configurable'], 'data' => [], 'configurable_attributes' => [], 'warehouses' => [], 'pricing' => [], 'variants' => [['header' => ['type' => 'simple'], 'data' => ['url' => 'https://www.site.com'], 'configurable_attributes' => [], 'warehouses' => [], 'pricing' => []]]], $item->asArray());
    }


    public function testGetConfigurationsAsArray() {
        $stock = new StockInfo();
        $price = new MultiGroupPrice();
        $simple = new Simple($stock, $price);
        $simple->url = 'https://www.site.com';
        $item = new Configurable($stock, $price);
        $item->addConfiguration($simple);
        $this->assertEquals([$simple], $item->getConfigurationsAsArray());
    }


    public function testAddConfiguration() {
        $stock = new StockInfo();
        $price = new MultiGroupPrice();
        $simple = new Simple($stock, $price);
        $simple->url = 'https://www.site.com';
        $item = new Configurable($stock, $price);
        $size = count($item->getConfigurationsAsArray());
        $item->addConfiguration($simple);
        $this->assertEquals($size + 1, count($item->getConfigurationsAsArray()));
    }
}
