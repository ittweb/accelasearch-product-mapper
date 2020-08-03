<?php declare(strict_types=1);
use PHPUnit\Framework\TestCase;

final class PageTest extends TestCase {
    public function testConstruct() {
        $entity = new \Ittweb\AccelaSearch\Model\Page();
        $this->assertInstanceOf('\Ittweb\AccelaSearch\Model\Page', $entity);
    }
}
