<?php declare(strict_types=1);
use PHPUnit\Framework\TestCase;
use \Ittweb\AccelaSearch\ProductMapper\Model\Simple;

final class SimpleTest extends TestCase {
    public function testAsArray() {
        $item = new Simple();
        $item->url = 'https://www.site.com';
        $this->assertEquals(['header' => ['type' => 'simple'], 'data' => ['url' => 'https://www.site.com']], $item->asArray());
    }
}
