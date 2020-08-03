<?php
namespace Ittweb\AccelaSearch\ProductMapper\Model;

class Banner implements ItemInterface {
    use ItemTrait;

    public function asArray(): array {
        return [
            'header' => [
                'type' => 'banner'
            ],
            'data' => $this->getAttributesAsDictionary()
        ];
    }

    public function accept(ItemVisitorInterface $visitor) {
        $visitor->visitBanner();
    }
}