<?php
namespace Ittweb\AccelaSearch\ProductMapper\Model;

class Downloadable implements ItemInterface {
    use ItemTrait;

    public function asArray(): array {
        return [
            'header' => [
                'type' => 'downloadable'
            ],
            'data' => $this->getAttributesAsDictionary()
        ];
    }

    public function accept(ItemVisitorInterface $visitor) {
        $visitor->visitDownloadable($this);
    }
}
