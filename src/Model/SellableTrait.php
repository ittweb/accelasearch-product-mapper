<?php
/**
 * Trait for a sellable entity
 *
 * @author     Marco Zanella <mzanella@ittweb.net>
 * @copyright  2020 Ittweb
 * @license    https://opensource.org/licenses/MIT MIT
 * @package    accelasearch-product-mapper
 */
namespace Ittweb\AccelaSearch\Model;

/**
 * Trait for a sellable entity
 *
 * @author     Marco Zanella <mzanella@ittweb.net>
 * @copyright  2020 Ittweb
 * @license    https://opensource.org/licenses/MIT MIT
 * @package    accelasearch-product-mapper
 */
trait SellableTrait {
    /**
     * Price information
     *
     * @var PriceInfo
     */
    private $price_info = null;

    /**
     * Returns price information
     *
     * @return PriceInfo Price information
     */
    public function getPriceInfo(): PriceInfo {
        return $this->price_info;
    }
}
