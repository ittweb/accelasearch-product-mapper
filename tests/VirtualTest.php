<?php declare(strict_types=1);
use PHPUnit\Framework\TestCase;
use \Ittweb\AccelaSearch\ProductMapper\Model\Virtual;

final class VirtualTest extends TestCase {
    public function testAsArray() {
        $item = new Virtual();
        $item->url = 'https://www.site.com';
        $this->assertEquals(['header' => ['type' => 'virtual'], 'data' => ['url' => 'https://www.site.com']], $item->asArray());
    }
}
