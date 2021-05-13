<?php
namespace Ittweb\AccelaSearch\ProductMapper;
use \Ittweb\AccelaSearch\ProductMapper\Stock\Availability;

interface StockableInterface {
    public function getAvailability(): Availability;
}