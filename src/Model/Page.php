<?php
namespace Ittweb\AccelaSearch\ProductMapper\Model;

class Page implements ItemInterface {
    use SingleItemTrait;

    public function asArray(): array {
        return [
            'header' => [
                'type' => 'page'
            ],
            'data' => $this->getAttributesAsDictionary()
        ];
    }

    public function accept(ItemVisitorInterface $visitor) {
        $visitor->visitPage($this);
    }
}
