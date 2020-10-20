<?php
namespace Ittweb\AccelaSearch\ProductMapper\Model;
use \ArrayAccess;
use \JsonSerializable;

interface ItemInterface extends ArrayAccess, ObjectAccessInterface, ArrayConvertibleInterface, JsonSerializable {
    public function hasChildren(): bool;
    public function getAttributesAsDictionary(): array;
    public function getChildrenAsArray(): array;
    public function accept(ItemVisitorInterface $visitor);
}
