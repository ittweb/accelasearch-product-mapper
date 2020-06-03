<?php declare(strict_types=1);
use PHPUnit\Framework\TestCase;

final class VirtualTest extends TestCase {
    public function testConstruct() {
        $entity = new \Ittweb\AccelaSearch\Model\Virtual();
        $this->assertInstanceOf('\Ittweb\AccelaSearch\Model\Virtual', $entity);
    }
}
