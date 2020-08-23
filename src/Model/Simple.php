<?php
namespace Ittweb\AccelaSearch\ProductMapper\Model;
use \Ittweb\AccelaSearch\ProductMapper\Model\Stock\StockInfo as Stock;
use \Ittweb\AccelaSearch\ProductMapper\Model\Price\MultiGroupPrice as Price;

class Simple implements ProductInterface {
    use ProductTrait;

    public function __construct(Stock $stock, Price $price) {
        $this->setStock($stock);
        $this->setPrice($price);
    }

    public function asArray(): array {
        return [
            'header' => [
                'type' => 'simple'
            ],
            'data' => $this->getAttributesAsDictionary(),
            'configurable_attributes' => $this->getConfigurableAttributesAsArray(),
            'warehouses' => $this->getStock()->asArray(),
            'pricing' => $this->getPrice()->asArray()
        ];
    }

    public function accept(ItemVisitorInterface $visitor) {
        $visitor->visitSimple($this);
    }
}
