<?php declare(strict_types=1);
use PHPUnit\Framework\TestCase;

final class BannerTest extends TestCase {
    public function testConstructor() {
        $entity = new \Ittweb\AccelaSearch\Model\Banner();
        $this->assertInstanceOf('\Ittweb\AccelaSearch\Model\Banner', $entity);
    }
}
