<?php
/**
 * Simple, regular, plain, standard product
 *
 * @author     Marco Zanella <mzanella@ittweb.net>
 * @copyright  2020 Ittweb
 * @license    https://opensource.org/licenses/MIT MIT
 * @package    accelasearch-product-mapper
 */
namespace Ittweb\AccelaSearch\Model;

/**
 * Simple, regular, plain, standard product
 *
 * @author     Marco Zanella <mzanella@ittweb.net>
 * @copyright  2020 Ittweb
 * @license    https://opensource.org/licenses/MIT MIT
 * @package    accelasearch-product-mapper
 */
class Simple extends Product {
    /**
     * Accepts a visitor
     *
     * @param EntityVisitorInterface $visitor The visitor to accept
     */
    public function accept(EntityVisitorInterface $visitor) {
        $visitor->visitSimple($this);
    }
}
