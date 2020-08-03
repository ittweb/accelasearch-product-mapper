<?php
namespace Ittweb\AccelaSearch\ProductMapper\Model\Price;

class MultiCurrencyPrice {
    private $prices;

    public function __construct() {
        $this->prices = [];
    }

    public function asDictionary(): array {
        return $this->prices;
    }

    public function add(string $currency, Price $price) {
        $this->prices[strtoupper($currency)] = $price;
    }

    public function remove(string $currency) {
        unset($this->prices[strtoupper($currency)]);
    }
}
