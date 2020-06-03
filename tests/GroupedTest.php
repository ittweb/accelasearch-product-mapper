<?php declare(strict_types=1);
use PHPUnit\Framework\TestCase;

final class GroupedTest extends TestCase {
    public function testConstruct() {
        $entity = new \Ittweb\AccelaSearch\Model\Grouped();
        $this->assertInstanceOf('\Ittweb\AccelaSearch\Model\Grouped', $entity);
    }
}
