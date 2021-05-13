<?php
namespace Ittweb\AccelaSearch\ProductMapper\Converter;
use \Ittweb\AccelaSearch\ProductMapper\ItemInterface as Subject;

interface ItemInterface {
    public function fromObject(Subject $item);
    public function toObject($item): Subject;
}