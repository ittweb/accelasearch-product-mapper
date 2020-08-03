<?php
/**
 * Interface of a data mapper for an entity
 *
 * @author     Marco Zanella <mzanella@ittweb.net>
 * @copyright  2020 Ittweb
 * @license    https://opensource.org/licenses/MIT MIT
 * @package    accelasearch-product-mapper
 */
namespace Ittweb\AccelaSearch\DataMapper;

/**
 * Interface of a data mapper for an entity
 *
 * @author     Marco Zanella <mzanella@ittweb.net>
 * @copyright  2020 Ittweb
 * @license    https://opensource.org/licenses/MIT MIT
 * @package    accelasearch-product-mapper
 */
interface EntityInterface extends BulkInterface {
    /**
     * Creates a new entity
     *
     * @param \Ittweb\AccelaSearch\Model\Entity $entity Entity to create
     */
    public function create(\Ittweb\AccelaSearch\Model\Entity $entity);
    
    /**
     * Reads an entity
     *
     * @param mixed $identifier Identifier of an entity
     * @return \Ittweb\AccelaSearch\Model\Entity Entity
     * @throw \OutOfBoundsException if entity is not present in the data set
     */
    public function read($identifier): \Ittweb\AccelaSearch\Model\Entity;
    
    /**
     * Updates an entity
     *
     * @param \Ittweb\AccelaSearch\Model\Entity $entity Entity to update
     */
    public function update(\Ittweb\AccelaSearch\Model\Entity $entity);
    
    /**
     * Delete an entity
     *
     * @param \Ittweb\AccelaSearch\Model\Entity $entity Entity to delete
     */
    public function delete(\Ittweb\AccelaSearch\Model\Entity $entity);
}