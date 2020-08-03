<?php
namespace Ittweb\AccelaSearch\ProductMapper\Model\Price;
use \JsonSerializable;
use \Ittweb\AccelaSearch\ProductMapper\Model\ArrayConvertibleInterface;
use \Ittweb\AccelaSearch\ProductMapper\Model\ArrayToJsonTrait;

class MultiCurrencyPrice implements ArrayConvertibleInterface, JsonSerializable {
    use ArrayToJsonTrait;
    private $prices;

    public function __construct() {
        $this->prices = [];
    }

    public function asArray(): array {
        $prices = [];
        foreach ($this->prices as $currency => $price) {
            $prices[$currency] = $price->asArray();
        }
        return $prices;
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
