<?php
namespace Ittweb\AccelaSearch\Model;
use \ArrayAccess;
use \JsonSerializable;

interface ItemInterface extends ArrayAccess, ObjectAccessInterface, ArrayConvertibleInterface, JsonSerializable {
    public function getAttributesAsDictionary(): array;
    public function accept(ItemVisitorInterface $visitor);
}
