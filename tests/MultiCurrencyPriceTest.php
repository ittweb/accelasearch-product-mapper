<?php declare(strict_types=1);
use PHPUnit\Framework\TestCase;
use \Ittweb\AccelaSearch\ProductMapper\Model\Price\Price;
use \Ittweb\AccelaSearch\ProductMapper\Model\Price\MultiCurrencyPrice;

final class MultiCurrencyPriceTest extends TestCase {
    public function testGetPricesAsDictionary() {
        $price_eur = new Price(10.0);
        $price_usd = new Price(15.0);
        $price = new MultiCurrencyPrice();
        $price->add('eur', $price_eur);
        $price->add('usd', $price_usd);
        $this->assertEquals(['EUR' => $price_eur, 'USD' => $price_usd], $price->asDictionary());
    }

    public function testAdd() {
        $price_eur = new Price(10.0);
        $price = new MultiCurrencyPrice();
        $size = count($price->asDictionary());
        $price->add('eur', $price_eur);
        $this->assertEquals($size + 1, count($price->asDictionary()));
    }

    public function testRemove() {
        $price_eur = new Price(10.0);
        $price = new MultiCurrencyPrice();
        $price->add('eur', $price_eur);
        $size = count($price->asDictionary());
        $price->remove('eur');
        $this->assertEquals($size - 1, count($price->asDictionary()));
    }

    public function testAsArray() {
        $price_eur = new Price(10.0);
        $price = new MultiCurrencyPrice();
        $price->add('eur', $price_eur);
        $this->assertEquals(['EUR' => ['listing_price' => 10.0, 'selling_price' => 10.0]], $price->asArray());
    }
}
