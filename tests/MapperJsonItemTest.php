<?php declare(strict_types=1);
use PHPUnit\Framework\TestCase;
use \Ittweb\AccelaSearch\ProductMapper\Model\Simple;
use \Ittweb\AccelaSearch\ProductMapper\Model\Configurable;
use \Ittweb\AccelaSearch\ProductMapper\Model\Stock\StockInfo as Stock;
use \Ittweb\AccelaSearch\ProductMapper\Model\Price\MultiGroupPrice as Price;
use \Ittweb\AccelaSearch\ProductMapper\Mapper\Dictionary\Stock as StockMapper;
use \Ittweb\AccelaSearch\ProductMapper\Mapper\Dictionary\Price as PriceMapper;
use \Ittweb\AccelaSearch\ProductMapper\Mapper\Dictionary\Item as DictionaryMapper;
use \Ittweb\AccelaSearch\ProductMapper\Mapper\Json\DictionaryToJsonAdapter as ItemMapper;

final class MapperJsonItemTest extends TestCase {
    public function testCreate() {
        $stock = new Stock();
        $price = new Price();
        $item = new Simple($stock, $price);
        $stock_mapper = new StockMapper();
        $price_mapper = new PriceMapper();
        $dictionary_mapper = new DictionaryMapper($stock_mapper, $price_mapper);
        $item_mapper = new ItemMapper($dictionary_mapper);
        $this->assertEquals(json_encode($item), $item_mapper->create($item));
    }

    public function testRead() {
        $stock = new Stock();
        $price = new Price();
        $simple = new Simple($stock, $price);
        $item = new Configurable($stock, $price);
        $item->addConfiguration($simple);
        $item->name = 'value';
        $item->addConfigurableAttribute('name');
        $stock_mapper = new StockMapper();
        $price_mapper = new PriceMapper();
        $dictionary_mapper = new DictionaryMapper($stock_mapper, $price_mapper);
        $item_mapper = new ItemMapper($dictionary_mapper);
        $this->assertEquals($item, $item_mapper->read(json_encode($item)));
    }
}
