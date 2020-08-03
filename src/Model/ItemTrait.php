<?php
namespace Ittweb\AccelaSearch\Model;

trait ItemTrait {
    use ArrayAccessTrait;
    use ObjectAccessTrait;
    use ArrayToJsonTrait;

    private $data;

    public function getAttributesAsDictionary(): array {
        return $this->data;
    }
}
