<?php
namespace Ittweb\AccelaSearch\ProductMapper\Model;

class Category implements ItemInterface {
    use ItemTrait;

    public function asArray(): array {
        return [
            'header' => [
                'type' => 'category'
            ],
            'data' => $this->getAttributesAsDictionary()
        ];
    }

    public function accept(ItemVisitorInterface $visitor) {
        $visitor->visitCategory($this);
    }
}
