<?php declare(strict_types=1);
use PHPUnit\Framework\TestCase;
use \Ittweb\AccelaSearch\ProductMapper\Model\Price\Price;
use \Ittweb\AccelaSearch\ProductMapper\Model\Price\MultiCurrencyPrice;
use \Ittweb\AccelaSearch\ProductMapper\Model\Price\MultiTierPrice;
use \Ittweb\AccelaSearch\ProductMapper\Model\Price\MultiGroupPrice;

final class MultiGroupPriceTest extends TestCase {
    public function testAsDictionary() {
        $price_eur = new Price(10.0);
        $price_tier = new MultiCurrencyPrice();
        $price_tier->add('eur', $price_eur);
        $price_group = new MultiTierPrice();
        $price_group->add(0.0, $price_tier);
        $price = new MultiGroupPrice();
        $price->add('group_id', $price_group);
        $this->assertEquals(['group_id' => $price_group], $price->asDictionary());
    }

    public function testAdd() {
        $price_eur = new Price(10.0);
        $price_tier = new MultiCurrencyPrice();
        $price_tier->add('eur', $price_eur);
        $price_group = new MultiTierPrice();
        $price_group->add(0.0, $price_tier);
        $price = new MultiGroupPrice();
        $size = count($price->asDictionary());
        $price->add('group_id', $price_group);
        $this->assertEquals($size + 1, count($price->asDictionary()));
    }

    public function testRemove() {
        $price_eur = new Price(10.0);
        $price_tier = new MultiCurrencyPrice();
        $price_tier->add('eur', $price_eur);
        $price_group = new MultiTierPrice();
        $price_group->add(0.0, $price_tier);
        $price = new MultiGroupPrice();
        $price->add('group_id', $price_group);
        $size = count($price->asDictionary());
        $price->remove('group_id');
        $this->assertEquals($size - 1, count($price->asDictionary()));
    }

    public function testAsArray() {
        $price_eur = new Price(10.0);
        $price_tier = new MultiCurrencyPrice();
        $price_tier->add('eur', $price_eur);
        $price_group = new MultiTierPrice();
        $price_group->add(0.0, $price_tier);
        $price = new MultiGroupPrice();
        $price->add('group_id', $price_group);
        $this->assertEquals(['group_id' => ['0.0000' => ['EUR' => ['listing_price' => 10.0, 'selling_price' => 10.0]]]], $price->asArray());
    }
}
