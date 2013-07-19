<?php

namespace Arte\Ober2doctrineBundle\Entity;

/**
 * OberIndex
 *
 */
class OberIndex
{
    private $id;
    private $logicalName;
    private $physicalName;
    private $type;
    private $columns;

    public function __construct()
    {
        $this->columns = array();
    }

    public function setColumns($columns)
    {
        $this->columns = $columns;
    }

    public function getColumns()
    {
        return $this->columns;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setLogicalName($logicalName)
    {
        $this->logicalName = $logicalName;
    }

    public function getLogicalName()
    {
        return $this->logicalName;
    }

    public function setPhysicalName($physicalName)
    {
        $this->physicalName = $physicalName;
    }

    public function getPhysicalName()
    {
        return $this->physicalName;
    }

    public function setType($type)
    {
        $this->type = $type;
    }

    public function getType()
    {
        return $this->type;
    }


}
