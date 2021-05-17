<?php
namespace AccelaSearch\ProductMapper;
use \AccelaSearch\ProductMapper\Stock\Availability;

interface StockableInterface {
    public function getAvailability(): Availability;
}