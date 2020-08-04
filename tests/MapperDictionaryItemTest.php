<?php declare(strict_types=1);
use PHPUnit\Framework\TestCase;
use \Ittweb\AccelaSearch\ProductMapper\Model\Simple;
use \Ittweb\AccelaSearch\ProductMapper\Model\Virtual;
use \Ittweb\AccelaSearch\ProductMapper\Model\Downloadable;
use \Ittweb\AccelaSearch\ProductMapper\Model\Configurable;
use \Ittweb\AccelaSearch\ProductMapper\Model\Bundle;
use \Ittweb\AccelaSearch\ProductMapper\Model\Grouped;
use \Ittweb\AccelaSearch\ProductMapper\Model\Page;
use \Ittweb\AccelaSearch\ProductMapper\Model\Category;
use \Ittweb\AccelaSearch\ProductMapper\Model\Banner;
use \Ittweb\AccelaSearch\ProductMapper\Model\Stock\StockInfo as Stock;
use \Ittweb\AccelaSearch\ProductMapper\Model\Price\MultiGroupPrice as Price;
use \Ittweb\AccelaSearch\ProductMapper\Mapper\Dictionary\Stock as StockMapper;
use \Ittweb\AccelaSearch\ProductMapper\Mapper\Dictionary\Price as PriceMapper;
use \Ittweb\AccelaSearch\ProductMapper\Mapper\Dictionary\Item as ItemMapper;

final class MapperDictionaryItemTest extends TestCase {
    public function testCreate() {
        $banner = new Banner();
        $stock_mapper = new StockMapper();
        $price_mapper = new PriceMapper();
        $item_mapper = new ItemMapper($stock_mapper, $price_mapper);
        $this->assertEquals($banner->asArray(), $item_mapper->create($banner));
    }

    public function testReadSimple() {
        $stock = new Stock();
        $price = new Price();
        $item = new Simple($stock, $price);
        $item->name = 'value';
        $item->addConfigurableAttribute('name');
        $stock_mapper = new StockMapper();
        $price_mapper = new PriceMapper();
        $item_mapper = new ItemMapper($stock_mapper, $price_mapper);
        $this->assertEquals($item, $item_mapper->read($item->asArray()));
    }

    public function testReadVirtual() {
        $stock = new Stock();
        $price = new Price();
        $item = new Virtual($stock, $price);
        $item->name = 'value';
        $item->addConfigurableAttribute('name');
        $stock_mapper = new StockMapper();
        $price_mapper = new PriceMapper();
        $item_mapper = new ItemMapper($stock_mapper, $price_mapper);
        $this->assertEquals($item, $item_mapper->read($item->asArray()));
    }

    public function testReadDownloadable() {
        $stock = new Stock();
        $price = new Price();
        $item = new Downloadable($stock, $price);
        $item->name = 'value';
        $item->addConfigurableAttribute('name');
        $stock_mapper = new StockMapper();
        $price_mapper = new PriceMapper();
        $item_mapper = new ItemMapper($stock_mapper, $price_mapper);
        $this->assertEquals($item, $item_mapper->read($item->asArray()));
    }

    public function testReadConfigurable() {
        $stock = new Stock();
        $price = new Price();
        $simple = new Simple($stock, $price);
        $item = new Configurable($stock, $price);
        $item->addConfiguration($simple);
        $item->name = 'value';
        $item->addConfigurableAttribute('name');
        $stock_mapper = new StockMapper();
        $price_mapper = new PriceMapper();
        $item_mapper = new ItemMapper($stock_mapper, $price_mapper);
        $this->assertEquals($item, $item_mapper->read($item->asArray()));
    }

    public function testReadBundle() {
        $stock = new Stock();
        $price = new Price();
        $simple = new Simple($stock, $price);
        $item = new Bundle($stock, $price);
        $item->addComponent($simple);
        $item->name = 'value';
        $item->addConfigurableAttribute('name');
        $stock_mapper = new StockMapper();
        $price_mapper = new PriceMapper();
        $item_mapper = new ItemMapper($stock_mapper, $price_mapper);
        $this->assertEquals($item, $item_mapper->read($item->asArray()));
    }

    public function testReadGrouped() {
        $stock = new Stock();
        $price = new Price();
        $simple = new Simple($stock, $price);
        $item = new Grouped($stock, $price);
        $item->addComponent($simple);
        $item->name = 'value';
        $item->addConfigurableAttribute('name');
        $stock_mapper = new StockMapper();
        $price_mapper = new PriceMapper();
        $item_mapper = new ItemMapper($stock_mapper, $price_mapper);
        $this->assertEquals($item, $item_mapper->read($item->asArray()));
    }

    public function testReadPage() {
        $item = new Page();
        $item->name = 'value';
        $stock_mapper = new StockMapper();
        $price_mapper = new PriceMapper();
        $item_mapper = new ItemMapper($stock_mapper, $price_mapper);
        $this->assertEquals($item, $item_mapper->read($item->asArray()));
    }

    public function testReadCategory() {
        $item = new Category();
        $item->name = 'value';
        $stock_mapper = new StockMapper();
        $price_mapper = new PriceMapper();
        $item_mapper = new ItemMapper($stock_mapper, $price_mapper);
        $this->assertEquals($item, $item_mapper->read($item->asArray()));
    }

    public function testReadBanner() {
        $item = new Banner();
        $item->name = 'value';
        $stock_mapper = new StockMapper();
        $price_mapper = new PriceMapper();
        $item_mapper = new ItemMapper($stock_mapper, $price_mapper);
        $this->assertEquals($item, $item_mapper->read($item->asArray()));
    }
}
