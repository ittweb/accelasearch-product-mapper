<?php declare(strict_types=1);
use PHPUnit\Framework\TestCase;
use \Ittweb\AccelaSearch\ProductMapper\Model\Price\Price;
use \Ittweb\AccelaSearch\ProductMapper\Model\Price\MultiCurrencyPrice;
use \Ittweb\AccelaSearch\ProductMapper\Model\Price\MultiTierPrice;

final class MultiTierPriceTest extends TestCase {
    public function testGetPricesAsDictionary() {
        $price_eur = new Price(10.0);
        $price_tier = new MultiCurrencyPrice();
        $price_tier->add('eur', $price_eur);
        $price = new MultiTierPrice();
        $price->add(0.0, $price_tier);
        $this->assertEquals(['0' => $price_tier], $price->asDictionary());
    }

    public function testAdd() {
        $price_eur = new Price(10.0);
        $price_tier = new MultiCurrencyPrice();
        $price_tier->add('eur', $price_eur);
        $price = new MultiTierPrice();
        $size = count($price->asDictionary());
        $price->add(0.0, $price_tier);
        $this->assertEquals($size + 1, count($price->asDictionary()));
    }

    public function testRemove() {
        $price_eur = new Price(10.0);
        $price_tier = new MultiCurrencyPrice();
        $price_tier->add('eur', $price_eur);
        $price = new MultiTierPrice();
        $price->add(0.0, $price_tier);
        $size = count($price->asDictionary());
        $price->remove(0.0);
        $this->assertEquals($size - 1, count($price->asDictionary()));
    }

    public function testAsArray() {
        $price_eur = new Price(10.0);
        $price_tier = new MultiCurrencyPrice();
        $price_tier->add('eur', $price_eur);
        $price = new MultiTierPrice();
        $price->add(0.0, $price_tier);
        $this->assertEquals(['0.0000' => ['EUR' => ['listing_price' => 10.0, 'selling_price' => 10.0]]], $price->asArray());
    }
}
