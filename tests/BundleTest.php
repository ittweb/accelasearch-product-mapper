<?php declare(strict_types=1);
use PHPUnit\Framework\TestCase;
use \Ittweb\AccelaSearch\ProductMapper\Model\Bundle;
use \Ittweb\AccelaSearch\ProductMapper\Model\Simple;
use \Ittweb\AccelaSearch\ProductMapper\Model\Stock\StockInfo;
use \Ittweb\AccelaSearch\ProductMapper\Model\Price\MultiGroupPrice;

final class BundleTest extends TestCase {
    public function testAsArray() {
        $stock = new StockInfo();
        $price = new MultiGroupPrice();
        $simple = new Simple($stock, $price);
        $simple->url = 'https://www.site.com';
        $item = new Bundle($stock, $price);
        $item->addComponent($simple);
        $this->assertEquals(['header' => ['type' => 'bundle'], 'data' => [], 'configurable_attributes' => [], 'warehouses' => [], 'pricing' => [], 'bundles' => [['header' => ['type' => 'simple'], 'data' => ['url' => 'https://www.site.com'], 'configurable_attributes' => [], 'warehouses' => [], 'pricing' => []]]], $item->asArray());
    }


    public function testGetComponentsAsArray() {
        $stock = new StockInfo();
        $price = new MultiGroupPrice();
        $simple = new Simple($stock, $price);
        $simple->url = 'https://www.site.com';
        $item = new Bundle($stock, $price);
        $item->addComponent($simple);
        $this->assertEquals([$simple], $item->getComponentsAsArray());
    }


    public function testAddComponent() {
        $stock = new StockInfo();
        $price = new MultiGroupPrice();
        $simple = new Simple($stock, $price);
        $simple->url = 'https://www.site.com';
        $item = new Bundle($stock, $price);
        $size = count($item->getComponentsAsArray());
        $item->addComponent($simple);
        $this->assertEquals($size + 1, count($item->getComponentsAsArray()));
    }
}
