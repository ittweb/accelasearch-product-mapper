<?php declare(strict_types=1);
use PHPUnit\Framework\TestCase;

final class SimpleTest extends TestCase {
    public function testConstruct() {
        $entity = new \Ittweb\AccelaSearch\Model\Simple();
        $this->assertInstanceOf('\Ittweb\AccelaSearch\Model\Simple', $entity);
    }
}
