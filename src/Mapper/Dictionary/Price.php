<?php
namespace Ittweb\AccelaSearch\ProductMapper\Mapper\Dictionary;
use \Ittweb\AccelaSearch\ProductMapper\Model\Price\Price as BasePrice;
use \Ittweb\AccelaSearch\ProductMapper\Model\Price\MultiCurrencyPrice;
use \Ittweb\AccelaSearch\ProductMapper\Model\Price\MultiTierPrice;
use \Ittweb\AccelaSearch\ProductMapper\Model\Price\MultiGroupPrice;
use \BadFunctionCallException;
use \OutOfBoundsException;

class Price {
    public function create(MultiGroupPrice $price): array {
        return $price->asArray();
    }

    public function read(array $data): MultiGroupPrice {
        $price = new MultiGroupPrice();
        foreach ($data as $identifier => $multi_tier) {
            $price->add($identifier, $this->readMultiTier($multi_tier));
        }
        return $price;
    }

    private function readMultiTier(array $data): MultiTierPrice {
        $price = new MultiTierPrice();
        foreach ($data as $quantity => $multi_currency) {
            $price->add(floatval($quantity), $this->readMultiCurrency($multi_currency));
        }
        return $price;
    }

    private function readMultiCurrency(array $data): MultiCurrencyPrice {
        $price = new MultiCurrencyPrice();
        foreach ($data as $currency => $base_price) {
            $price->add($currency, $this->readBasePrice($base_price));
        }
        return $price;
    }

    private function readBasePrice(array $data): BasePrice {
        return new BasePrice($data['listing_price'], $data['selling_price']);
    }
}
