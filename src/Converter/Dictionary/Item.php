<?php
/**
 * @todo This class is not fully implemented
 */
namespace Ittweb\AccelaSearch\ProductMapper\Converter\Dictionary;
use \BadFunctionCallException;
use \InvalidArgumentException;
use \Ittweb\AccelaSearch\ProductMapper\Converter\ItemInterface;
use \Ittweb\AccelaSearch\ProductMapper\ItemInterface as Subject;
use \Ittweb\AccelaSearch\ProductMapper\Banner;
use \Ittweb\AccelaSearch\ProductMapper\Page;
use \Ittweb\AccelaSearch\ProductMapper\CategoryPage;
use \Ittweb\AccelaSearch\ProductMapper\ProductFactory;

class Item implements ItemInterface {
    private $visitor;

    public function __construct(Visitor $visitor) {
        $this->visitor = $visitor;
    }

    public static function fromDefault(): self {
        return new Item(new Visitor());
    }

    public function fromObject(Subject $item) {
        return $item->accept($this->visitor);
    }

    public function toObject($item): Subject {
        if (!isset($item['header']['type'])) {
            throw new BadFunctionCallException('Missing mandatory key "type".');
        }
        switch ($item['header']['type']) {
            /*
            case 'banner': return $this->banner($item);
            case 'page': return $this->page($item);
            case 'categoryPage': return $this->categoryPage($item);
            case 'simple': return $this->simple($item);
            case 'virtual': return $this->virtual($item);
            case 'downloadable': return $this->downloadable($item);
            case 'configurable': return $this->configurable($item);
            case 'bundle': return $this->bundle($item);
            case 'grouped': return $this->grouped($item);
            */
            default: throw new InvalidArgumentException('Unknown type "' . $item['header']['type'] . '".');
        }
    }
}