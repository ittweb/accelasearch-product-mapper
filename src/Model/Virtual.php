<?php
namespace Ittweb\AccelaSearch\ProductMapper\Model;

class Virtual extends Simple {

    public function asArray(): array {
        return [
            'header' => [
                'type' => 'virtual'
            ],
            'data' => $this->getAttributesAsDictionary(),
            'configurable_attributes' => $this->getConfigurableAttributesAsArray(),
            'warehouses' => $this->getStock()->asArray(),
            'pricing' => $this->getPrice()->asArray()
        ];
    }

    public function accept(ItemVisitorInterface $visitor) {
        $visitor->visitVirtual($this);
    }
}
