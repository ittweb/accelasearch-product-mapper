<?php
namespace AccelaSearch\ProductMapper;
use \AccelaSearch\ProductMapper\Price\Pricing;

interface SellableInterface {
    public function getPricing(): Pricing;
}