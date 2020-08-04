<?php declare(strict_types=1);
use PHPUnit\Framework\TestCase;
use \Ittweb\AccelaSearch\ProductMapper\Model\Price\Price;
use \Ittweb\AccelaSearch\ProductMapper\Model\Price\MultiCurrencyPrice;
use \Ittweb\AccelaSearch\ProductMapper\Model\Price\MultiTierPrice;
use \Ittweb\AccelaSearch\ProductMapper\Model\Price\MultiGroupPrice;
use \Ittweb\AccelaSearch\ProductMapper\Mapper\Dictionary\Price as PriceMapper;

final class MapperDictionaryPriceTest extends TestCase {
    public function testCreate() {
        $price = new Price(10.0, 6.0);
        $multi_currency = new MultiCurrencyPrice();
        $multi_currency->add('eur', $price);
        $multi_tier = new MultiTierPrice();
        $multi_tier->add(10.0, $multi_currency);
        $multi_group = new MultiGroupPrice();
        $multi_group->add('group_id', $multi_tier);
        $mapper = new PriceMapper();
        $this->assertEquals($multi_group->asArray(), $mapper->create($multi_group));
    }

    public function testRead() {
        $price = new Price(10.0, 6.0);
        $multi_currency = new MultiCurrencyPrice();
        $multi_currency->add('eur', $price);
        $multi_tier = new MultiTierPrice();
        $multi_tier->add(10.0, $multi_currency);
        $multi_group = new MultiGroupPrice();
        $multi_group->add('group_id', $multi_tier);
        $mapper = new PriceMapper();
        $this->assertEquals($multi_group, $mapper->read($multi_group->asArray()));
    }
}
