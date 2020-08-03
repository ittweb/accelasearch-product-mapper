<?php
namespace Ittweb\AccelaSearch\ProductMapper\Model;

class Simple implements ItemInterface {
    use ItemTrait;

    public function asArray(): array {
        return [
            'header' => [
                'type' => 'simple'
            ],
            'data' => $this->getAttributesAsDictionary()
        ];
    }

    public function accept(ItemVisitorInterface $visitor) {
        $visitor->visitSimple($this);
    }
}
