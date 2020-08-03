<?php
namespace Ittweb\AccelaSearch\ProductMapper\Model;
use \Ittweb\AccelaSearch\ProductMapper\Model\Price\MultiGroupPrice as Price;

trait SellableTrait {
    private $price;

    public function getPrice(): Price {
        return $this->price;
    }
}
