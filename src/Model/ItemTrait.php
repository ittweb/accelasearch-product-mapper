<?php
namespace Ittweb\AccelaSearch\ProductMapper\Model;

trait ItemTrait {
    use ArrayAccessTrait;
    use ObjectAccessTrait;
    use ArrayToJsonTrait;

    private $data = [];

    public function getAttributesAsDictionary(): array {
        return $this->data;
    }
}
