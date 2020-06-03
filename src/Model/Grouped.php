<?php
/**
 * A group of entities
 *
 * @author     Marco Zanella <mzanella@ittweb.net>
 * @copyright  2020 Ittweb
 * @license    https://opensource.org/licenses/MIT MIT
 * @package    accelasearch-product-mapper
 */
namespace Ittweb\AccelaSearch\Model;

/**
 * A group of entities
 *
 * @author     Marco Zanella <mzanella@ittweb.net>
 * @copyright  2020 Ittweb
 * @license    https://opensource.org/licenses/MIT MIT
 * @package    accelasearch-product-mapper
 */
class Grouped extends Bundle {
    /**
     * Accepts a visitor
     *
     * @param EntityVisitorInterface $visitor The visitor to accept
     */
    public function accept(EntityVisitorInterface $visitor) {
        $visitor->visitGrouped($this);
    }
}