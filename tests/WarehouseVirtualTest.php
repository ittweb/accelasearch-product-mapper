<?php declare(strict_types=1);
use PHPUnit\Framework\TestCase;
use \Ittweb\AccelaSearch\ProductMapper\Model\Stock\Virtual;

final class WarehouseVirtualTest extends TestCase {
    public function testIsVirtual() {
        $warehouse = new Virtual('warehouse_id');
        $this->assertTrue($warehouse->isVirtual());
    }

    public function testGetIdentifier() {
        $warehouse = new Virtual('warehouse_id');
        $this->assertEquals('warehouse_id', $warehouse->getIdentifier());
    }
}
