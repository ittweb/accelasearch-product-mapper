<?php declare(strict_types=1);
use PHPUnit\Framework\TestCase;

final class CategoryTest extends TestCase {
    public function testConstruct() {
        $entity = new \Ittweb\AccelaSearch\Model\Category();
        $this->assertInstanceOf('\Ittweb\AccelaSearch\Model\Category', $entity);
    }
}
