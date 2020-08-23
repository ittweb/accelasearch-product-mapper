<?php
namespace Ittweb\AccelaSearch\ProductMapper\Model;
use \ArrayAccess;
use \JsonSerializable;

interface ItemInterface extends ArrayAccess, ObjectAccessInterface, ArrayConvertibleInterface, JsonSerializable {
    public function getAttributesAsDictionary(): array;
    public function accept(ItemVisitorInterface $visitor);
}
