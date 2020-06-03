<?php
/**
 * A banner
 *
 * @author     Marco Zanella <mzanella@ittweb.net>
 * @copyright  2020 Ittweb
 * @license    https://opensource.org/licenses/MIT MIT
 * @package    accelasearch-product-mapper
 */
namespace Ittweb\AccelaSearch\Model;

/**
 * A banner
 *
 * @author     Marco Zanella <mzanella@ittweb.net>
 * @copyright  2020 Ittweb
 * @license    https://opensource.org/licenses/MIT MIT
 * @package    accelasearch-product-mapper
 */
class Banner extends Entity {
    /**
     * Accepts a visitor.
     *
     * @param $visitor The visitor to accept
     */
     public function accept(EntityVisitorInterface $visitor) {
         $visitor->visitBanner($this);
     }
}
