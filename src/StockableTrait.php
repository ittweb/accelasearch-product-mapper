<?php
namespace Ittweb\AccelaSearch\ProductMapper;
use \Ittweb\AccelaSearch\ProductMapper\Stock\Availability;

trait StockableTrait {
    private $availability;

    public function getAvailability(): Availability {
        return $this->availability;
    }
}