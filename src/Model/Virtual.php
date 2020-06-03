<?php
/**
 * Virtual product
 *
 * @author     Marco Zanella <mzanella@ittweb.net>
 * @copyright  2020 Ittweb
 * @license    https://opensource.org/licenses/MIT MIT
 * @package    accelasearch-product-mapper
 */
namespace Ittweb\AccelaSearch\Model;

/**
 * Virtual product
 *
 * @author     Marco Zanella <mzanella@ittweb.net>
 * @copyright  2020 Ittweb
 * @license    https://opensource.org/licenses/MIT MIT
 * @package    accelasearch-product-mapper
 */
class Virtual extends Simple {
    /**
     * Accepts a visitor
     *
     * @param EntityVisitorInterface $visitor The visitor to accept
     */
    public function accept(EntityVisitorInterface $visitor) {
        $visitor->visitVirtual($this);
    }
}
