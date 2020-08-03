<?php
namespace Ittweb\AccelaSearch\ProductMapper\Model;

class Downloadable extends Virtual {

    public function asArray(): array {
        return [
            'header' => [
                'type' => 'downloadable'
            ],
            'data' => $this->getAttributesAsDictionary(),
            'configurable_attributes' => $this->getConfigurableAttributesAsArray(),
            'warehouses' => $this->getStock()->asArray(),
            'pricing' => $this->getPrice()->asArray()
        ];
    }

    public function accept(ItemVisitorInterface $visitor) {
        $visitor->visitDownloadable($this);
    }
}
