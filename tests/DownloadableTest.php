<?php declare(strict_types=1);
use PHPUnit\Framework\TestCase;
use \Ittweb\AccelaSearch\ProductMapper\Model\Downloadable;

final class DownloadableTest extends TestCase {
    public function testAsArray() {
        $item = new Downloadable();
        $item->url = 'https://www.site.com';
        $this->assertEquals(['header' => ['type' => 'downloadable'], 'data' => ['url' => 'https://www.site.com']], $item->asArray());
    }
}
