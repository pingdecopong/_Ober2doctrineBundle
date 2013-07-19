<?php

namespace Arte\Ober2doctrineBundle\Entity;


/**
 * OberEntity
 *
 */
class OberEntity
{
    private $id;
    private $logicalName;
    private $physicalName;
    private $showType;
    private $attributes;
    private $indexes;

    public function __construct()
    {
        $this->attributes = array();
        $this->indexes = array();
    }

    public function setIndexes($indexes)
    {
        $this->indexes = $indexes;
    }

    public function getIndexes()
    {
        return $this->indexes;
    }

    public function setAttributes($attributes)
    {
        $this->attributes = $attributes;
    }

    public function getAttributes()
    {
        return $this->attributes;
    }

    public function setShowType($showType)
    {
        $this->showType = $showType;
    }

    public function getShowType()
    {
        return $this->showType;
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



    public function getPrimaryAttributes()
    {
        $ret = array();

        foreach($this->attributes as $value)
        {
            /* @var $value \Arte\Ober2doctrineBundle\Entity\OberAttribute */
            if($value->getPrimary() == true){
                $ret[] = $value;
            }

        }

        return $ret;
    }

}
