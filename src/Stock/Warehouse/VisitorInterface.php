<?php
namespace Ittweb\AccelaSearch\ProductMapper\Stock\Warehouse;

interface VisitorInterface {
    public function visitPhysical(Physical $warehouse);
    public function visitVirtual(Virtual $virtual);
}