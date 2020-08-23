<?php declare(strict_types=1);
use PHPUnit\Framework\TestCase;
use \Ittweb\AccelaSearch\ProductMapper\Model\Page;

final class PageTest extends TestCase {
    public function testAsArray() {
        $page = new Page();
        $page->url = 'https://www.site.com';
        $this->assertEquals(['header' => ['type' => 'page'], 'data' => ['url' => 'https://www.site.com']], $page->asArray());
    }
}
