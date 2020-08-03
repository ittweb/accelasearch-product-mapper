<?php declare(strict_types=1);
use PHPUnit\Framework\TestCase;
use \Ittweb\AccelaSearch\ProductMapper\Model\Stock\Physical;

final class WarehousePhysicalTest extends TestCase {
    public function testIsVirtual() {
        $warehouse = new Physical('warehouse_id', 1.0, 2.0);
        $this->assertFalse($warehouse->isVirtual());
    }

    public function testGetIdentifier() {
        $warehouse = new Physical('warehouse_id', 1.0, 2.0);
        $this->assertEquals('warehouse_id', $warehouse->getIdentifier());
    }

    public function testGetLatitude() {
        $warehouse = new Physical('warehouse_id', 1.0, 2.0);
        $this->assertEquals(1.0, $warehouse->getLatitude());
    }

    public function testGetLongitude() {
        $warehouse = new Physical('warehouse_id', 1.0, 2.0);
        $this->assertEquals(2.0, $warehouse->getLongitude());
    }
}
