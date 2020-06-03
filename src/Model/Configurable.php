<?php
/**
 * A configurable product
 *
 * @author     Marco Zanella <mzanella@ittweb.net>
 * @copyright  2020 Ittweb
 * @license    https://opensource.org/licenses/MIT MIT
 * @package    accelasearch-product-mapper
 */
namespace Ittweb\AccelaSearch\Model;

/**
 * A configurable product
 *
 * @author     Marco Zanella <mzanella@ittweb.net>
 * @copyright  2020 Ittweb
 * @license    https://opensource.org/licenses/MIT MIT
 * @package    accelasearch-product-mapper
 */
class Configurable extends Product {
    /**
     * Array of configurations
     *
     * @var array
     */
    private $configurations = [];
    
    /**
     * Array of names of configurable attributes
     *
     * @var array
     */
    private $configurable_attributes = [];

    /**
     * Returns array of configurations
     *
     * @return array Array of configurations
     */
    public function getConfigurations(): array {
        return $this->configurations;
    }

    /**
     * Returns array of names of configurale attributes
     *
     * @return array Names of configurable attributes
     */
    public function getConfigurableAttributes(): array {
        return $this->configurable_attributes;
    }

    /**
     * Adds a configuration
     *
     * @param Product $product Configuration
     */
    public function addConfiguration(Product $product) {
        $this->configurations[] = $product;
    }

    /**
     * Adds a configurable attribute
     *
     * @param string $name Name of configurable attribute
     */
    public function addConfigurableAttribute(string $name) {
        if (!in_array($name, $this->configurable_attributes)) {
            $this->configurable_attributes[] = $name;
        }
    }

    /**
     * Removes a configurable attribute
     *
     * @param string $name name of configurable attribute
     */
    public function unsetConfigurableAttribute(string $name) {
        if (($key = array_search($name, $this->configurable_attributes)) !== false) {
            unset($this->configurable_attributes[$key]);
        }
    }

    /**
     * Removes every configuration
     */
    public function clearConfigurations() {
        $this->configurations = [];
    }

    /**
     * Removes every name of configurable attribute
     */
    public function clearConfigurableAttributes() {
        $this->configurable_attributes = [];
    }
    
    /**
     * Accepts a visitor.
     *
     * @param EntityVisitorInterface $visitor The visitor to accept
     */
    public function accept(EntityVisitorInterface $visitor) {
        $visitor->visitConfigurable($this);
    }
}
