<?php declare(strict_types=1);
use PHPUnit\Framework\TestCase;

final class DownloadableTest extends TestCase {
    public function testConstruct() {
        $entity = new \Ittweb\AccelaSearch\Model\Downloadable();
        $this->assertInstanceOf('\Ittweb\AccelaSearch\Model\Downloadable', $entity);
    }
}
