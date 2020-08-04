<?php
namespace Ittweb\AccelaSearch\ProductMapper\Model;
use \Ittweb\AccelaSearch\ProductMapper\Model\Stock\StockInfo as Stock;
use \Ittweb\AccelaSearch\ProductMapper\Model\Price\MultiGroupPrice as Price;

class Grouped extends Bundle implements ProductInterface {
    use ProductTrait;

    public function __construct(Stock $stock, Price $price) {
        parent::__construct($stock, $price);
    }

    public function asArray(): array {
        $components = [];
        foreach ($this->getComponentsAsArray() as $product) {
            $components[] = $product->asArray();
        }
        return [
            'header' => [
                'type' => 'grouped'
            ],
            'data' => $this->getAttributesAsDictionary(),
            'configurable_attributes' => $this->getConfigurableAttributesAsArray(),
            'bundles' => $components,
            'warehouses' => $this->getStock()->asArray(),
            'pricing' => $this->getPrice()->asArray()
        ];
    }

    public function accept(ItemVisitorInterface $visitor) {
        $visitor->visitGrouped($this);
    }
}
