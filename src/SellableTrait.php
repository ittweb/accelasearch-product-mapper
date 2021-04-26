<?php
namespace Ittweb\AccelaSearch\ProductMapper;
use \Ittweb\AccelaSearch\ProductMapper\Price\Pricing;

trait SellableTrait {
    private $pricing;

    public function getPricing(): Pricing {
        return $this->pricing;
    }
}