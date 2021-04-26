<?php
namespace Ittweb\AccelaSearch\ProductMapper;
use \Ittweb\AccelaSearch\ProductMapper\Price\Pricing;

interface SellableInterface {
    public function getPricing(): Pricing;
}