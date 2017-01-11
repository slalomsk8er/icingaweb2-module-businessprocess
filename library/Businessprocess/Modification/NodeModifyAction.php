<?php

namespace Icinga\Module\Businessprocess\Modification;

use Icinga\Module\Businessprocess\BpConfig;
use Icinga\Module\Businessprocess\Node;

class NodeModifyAction extends NodeAction
{
    protected $properties = array();

    protected $formerProperties = array();

    protected $preserveProperties = array('formerProperties', 'properties');

    /**
     * Set properties for a specific node
     *
     * Can be called multiple times
     *
     * @param Node $node
     * @param array $properties
     *
     * @return $this
     */
    public function setNodeProperties(Node $node, array $properties)
    {
        foreach (array_keys($properties) as $key) {
            $this->properties[$key] = $properties[$key];

            if (array_key_exists($key, $this->formerProperties)) {
                continue;
            }

            $func = 'get' . ucfirst($key);
            $this->formerProperties[$key] = $node->$func();
        }

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function appliesTo(BpConfig $bp)
    {
        $name = $this->getNodeName();

        if (! $bp->hasNode($name)) {
            return false;
        }

        $node = $bp->getNode($name);

        foreach ($this->properties as $key => $val) {
            $func = 'get' . ucfirst($key);
            if ($this->formerProperties[$key] !== $node->$func()) {
                return false;
            }
        }

        return true;
    }

    /**
     * @inheritdoc
     */
    public function applyTo(BpConfig $bp)
    {
        $node = $bp->getNode($this->getNodeName());

        foreach ($this->properties as $key => $val) {
            $func = 'set' . ucfirst($key);
            $node->$func($val);
        }

        return $this;
    }

    /**
     * @param $properties
     * @return $this
     */
    public function setProperties($properties)
    {
        $this->properties = $properties;
        return $this;
    }

    /**
     * @param $properties
     * @return $this
     */
    public function setFormerProperties($properties)
    {
        $this->formerProperties = $properties;
        return $this;
    }

    /**
     * @return array
     */
    public function getProperties()
    {
        return $this->properties;
    }

    /**
     * @return array
     */
    public function getFormerProperties()
    {
        return $this->formerProperties;
    }
}
