<?php
/**
 * Associative array data mapper for an entity
 *
 * @author     Marco Zanella <mzanella@ittweb.net>
 * @copyright  2020 Ittweb
 * @license    https://opensource.org/licenses/MIT MIT
 * @package    accelasearch-product-mapper
 */
namespace Ittweb\AccelaSearch\DataMapper\AssociativeArray;

/**
 * Associative array data mapper for an entity
 *
 * @author     Marco Zanella <mzanella@ittweb.net>
 * @copyright  2020 Ittweb
 * @license    https://opensource.org/licenses/MIT MIT
 * @package    accelasearch-product-mapper
 */
class Entity implements \Ittweb\AccelaSearch\DataMapper\EntityInterface {
    use \Ittweb\AccelaSearch\DataMapper\BulkTrait;
    
    /**
     * Reference to dataset
     *
     * @var array
     */
    private $dataset;
    
    /**
     * Name of the field used as identifier
     *
     * @var string
     */
    private $identifier_field;
    
    /**
     * Pool of mappers
     *
     * @var array
     */
    private $mapper_pool;
    
    /**
     * Constructor
     *
     * @param array $dataset Reference to dataset
     * @param string $identifier_field Name of field used as identifier
     */
    public function __construct(array &$dataset, string $identifier_field) {
        $this->dataset = $dataset;
        $this->identifier_field = $identifier_field;
        $this->mapper_pool = [
            'Ittweb\AccelaSearch\Model\Simple' => new Simple($dataset, $identifier_field)
        ];
    }
    
    /**
     * Creates a new entity
     *
     * @param \Ittweb\AccelaSearch\Model\Entity $entity Entity to create
     */
    public function create(\Ittweb\AccelaSearch\Model\Entity $entity) {
        $this->getMapperFor($entity)->create($entity);
    }
    
    /**
     * Reads an entity
     *
     * @param mixed $identifier Identifier of an entity
     * @return \Ittweb\AccelaSearch\Model\Entity Entity
     * @throw \OutOfBoundsException if entity is not present in the data set
     */
    public function read($identifier): \Ittweb\AccelaSearch\Model\Entity {
        if (!array_key_exists($identifier, $this->dataset)) {
            throw new \OutOfBoundsException("Cannot find entity identified by \"$identifier\".");
        }
        $record = $this->dataset[$identifier];
        return $this->getMapperFromType($record['_type'])->read($record);
    }
    
    /**
     * Updates an entity
     *
     * @param \Ittweb\AccelaSearch\Model\Entity $entity Entity to update
     */
    public function update(\Ittweb\AccelaSearch\Model\Entity $entity) {
        $this->getMapperFor($entity)->update($entity);
    }
    
    /**
     * Delete an entity
     *
     * @param \Ittweb\AccelaSearch\Model\Entity $entity Entity to delete
     */
    public function delete(\Ittweb\AccelaSearch\Model\Entity $entity) {
        $this->getMapperFor($entity)->delete($entity);
    }
    
    /**
     * Returns appropriate data mapper for an entity
     *
     * @param \Ittweb\AccelaSearch\Model\Entity $entity Entity
     * @return mixed Data mapper for entity
     * @throw \InvalidArgumentException if no mapper can be used
     */
    private function getMapperFor(\Ittweb\AccelaSearch\Model\Entity $entity) {
        $class_name = get_class($entity);
        if (array_key_exists($class_name, $this->mapper_pool)) {
            return $this->mapper_pool[$class_name];
        }
        else {
            throw new \InvalidArgumentException("No mapper found for instances of class \"$class_name\".");
        }
    }
    
    /**
     * Returns appropriate data mapper for an array
     *
     * @param string $type Type of entity
     * @return mixed Data mapper for entity
     * @throw \InvalidArgumentException if no mapper can be used
     */
    private function getMapperFromType(string $type) {
        foreach ($this->mapper_pool as $class => $mapper) {
            if (strtolower(end(explode('\\', $class))) == strtolower($type)) {
                return $mapper;
            }
        }
        throw new \InvalidArgumentException("No mapper found for type \"$type\".");
    }
}