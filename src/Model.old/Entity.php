<?php
/**
 * A generic entity
 *
 * @author     Marco Zanella <mzanella@ittweb.net>
 * @copyright  2020 Ittweb
 * @license    https://opensource.org/licenses/MIT MIT
 * @package    accelasearch-product-mapper
 */
namespace Ittweb\AccelaSearch\Model;
use \OutOfBoundsException;

/**
 * A generic entity
 *
 * @author     Marco Zanella <mzanella@ittweb.net>
 * @copyright  2020 Ittweb
 * @license    https://opensource.org/licenses/MIT MIT
 * @package    accelasearch-product-mapper
 */
abstract class Entity implements \ArrayAccess {
    /**
     * Collection of attributes
     *
     * @var array
     */
    private $data = [];

    /**
     * Tells whether an attribute is set
     *
     * @param mixed $name Attribute name
     * @return bool True if and only if attribute is set
     */
    public function offsetExists($name): bool {
        return array_key_exists($name, $this->data);
    }


    /**
     * Tells whether an attribute is set
     *
     * @param mixed $name Attribute name
     * @return bool True if and only if attribute is set
     * @see Entity::offsetExists()
     */
    public function __isset(string $name): bool {
        return $this->offsetExists($name);
    }

    /**
     * Returns value of an attribute
     *
     * @param mixed $offset Name of the attribute
     * @return Vale of the attribute
     * @throw \OutOfBoundsException if attribute is not set
     */
    public function offsetGet($offset) {
        if (!array_key_exists($offset, $this->data)) {
            throw new OutOfBoundsException("Attribute \"$offset\" is not defined.");
        }

        return $this->data[$offset];
    }

    /**
     * Returns value of an attribute
     *
     * @param string $name Name of the attribute
     * @return Vale of the attribute
     * @throw \OutOfBoundsException if attribute is not set
     * @see Entity::offsetGet()
     */
    public function __get(string $name) {
        return $this->offsetGet($name);
    }

    /**
     * Returns attributes as associative array
     *
     * @return array Attributes
     */
    public function getAttributesAsArray(): array {
        return $this->data;
    }

    /**
     * Sets an attribute
     *
     * @param mixed $offset Name of the attribute
     * @param mixed $value Value of the attribute
     */
    public function offsetSet($offset, $value) {
        $this->data[$offset] = $value;
    }

    /**
     * Sets an attribute
     *
     * @param string $name Name of the attribute
     * @param mixed $value Value of the attribute
     * @see Entity::offsetSet()
     */
    public function __set(string $name, $value) {
        $this->offsetSet($name, $value);
    }

    /**
     * Unsets an attribute
     *
     * @param mixed $offset Name of the attribute
     */
    public function offsetUnset($offset) {
        unset($this->data[$offset]);
    }

    /**
     * Unsets an attribute
     *
     * @param string $name Name of the attribute
     * @see Entity::offsetUnset()
     */
    public function __unset(string $name) {
        $this->offsetUnset($name);
    }

    /**
     * Accepts a visitor.
     *
     * @param $visitor The visitor to accept
     */
    abstract public function accept(EntityVisitorInterface $visitor);
}
