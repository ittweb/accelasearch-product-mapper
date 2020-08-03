<?php
namespace Ittweb\AccelaSearch\ProductMapper\Model\Price;
use \JsonSerializable;
use \Ittweb\AccelaSearch\ProductMapper\Model\ArrayConvertibleInterface;
use \Ittweb\AccelaSearch\ProductMapper\Model\ArrayToJsonTrait;

class Price implements JsonSerializable, ArrayConvertibleInterface {
    use ArrayToJsonTrait;
    private $listing_price;
    private $selling_price;

    public function __construct(float $listing_price) {
        $this->listing_price = $listing_price;
        $this->selling_price = $listing_price;
    }

    public function getListingPrice(): float {
        return $this->listing_price;
    }

    public function getSellingPrice(): float {
        return $this->selling_price;
    }

    public function asArray(): array {
        return [
            'listing_price' => $this->listing_price,
            'selling_price' => $this->selling_price
        ];
    }

    public function setSellingPrice(float $selling_price) {
        $this->selling_price = $selling_price;
    }
}
