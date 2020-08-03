<?php
namespace Ittweb\AccelaSearch\ProductMapper\Model;
use \Ittweb\AccelaSearch\ProductMapper\Model\Stock\StockInfo as Stock;

trait StockableTrait {
    private $stock;

    public function getStock(): Stock {
        return $this->stock;
    }
}
