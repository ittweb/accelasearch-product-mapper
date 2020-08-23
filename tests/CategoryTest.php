<?php declare(strict_types=1);
use PHPUnit\Framework\TestCase;
use \Ittweb\AccelaSearch\ProductMapper\Model\Category;

final class CategoryTest extends TestCase {
    public function testAsArray() {
        $item = new Category();
        $item->url = 'https://www.site.com';
        $this->assertEquals(['header' => ['type' => 'category'], 'data' => ['url' => 'https://www.site.com']], $item->asArray());
    }
}
