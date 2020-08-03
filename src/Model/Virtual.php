<?php
namespace Ittweb\AccelaSearch\ProductMapper\Model;

class Virtual implements ItemInterface {
    use ItemTrait;

    public function asArray(): array {
        return [
            'header' => [
                'type' => 'virtual'
            ],
            'data' => $this->getAttributesAsDictionary()
        ];
    }

    public function accept(ItemVisitorInterface $visitor) {
        $visitor->visitVirtual($this);
    }
}
