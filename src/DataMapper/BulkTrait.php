<?php
/**
 * Trait implementing a bulk interface
 *
 * @author     Marco Zanella <mzanella@ittweb.net>
 * @copyright  2020 Ittweb
 * @license    https://opensource.org/licenses/MIT MIT
 * @package    accelasearch-product-mapper
 */
namespace Ittweb\AccelaSearch\DataMapper;

/**
 * Trait implementing a bulk interface
 *
 * @author     Marco Zanella <mzanella@ittweb.net>
 * @copyright  2020 Ittweb
 * @license    https://opensource.org/licenses/MIT MIT
 * @package    accelasearch-product-mapper
 */
trait BulkTrait {
    /**
     * Creates a set of entities
     *
     * @param array $entities Set of entities to create
     */
    public function bulkCreate(array $entities) {
        array_map([$this, 'create'], $entities);
    }
    
    /**
     * Updates a set of entities
     *
     * @param array $entities Set of entities to update
     */
    public function bulkUpdate(array $entities) {
        array_map([$this, 'update'], $entities);
    }
    
    /**
     * Deletes a set of entities
     *
     * @param array $entities Set of entities to delete
     */
    public function bulkDelete(array $entities) {
        array_map([$this, 'delete'], $entities);
    }
}