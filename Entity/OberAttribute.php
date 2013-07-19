<?php

namespace Arte\Ober2doctrineBundle\Entity;


/**
 * OberAttribute
 *
 */
class OberAttribute
{
    private $id;
    private $autoIncrementFlug;
    private $logicalName;
    private $physicalName;
    private $dataType;
    private $length;
    private $notNull;
    private $primary;
    private $showType;
    private $default;

    public function setDefault($default)
    {
        $this->default = $default;
    }

    public function getDefault()
    {
        return $this->default;
    }

    public function setAutoIncrementFlug($autoIncrementFlug)
    {
        $this->autoIncrementFlug = $autoIncrementFlug;
    }

    public function getAutoIncrementFlug()
    {
        return $this->autoIncrementFlug;
    }

    public function setShowType($showType)
    {
        $this->showType = $showType;
    }

    public function getShowType()
    {
        return $this->showType;
    }

    public function setPrimary($primary)
    {
        $this->primary = $primary;
    }

    public function getPrimary()
    {
        return $this->primary;
    }

    public function setDataType($dataType)
    {
        $this->dataType = $dataType;
    }

    public function getDataType()
    {
        return $this->dataType;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setLength($length)
    {
        $this->length = $length;
    }

    public function getLength()
    {
        return $this->length;
    }

    public function setLogicalName($logicalName)
    {
        $this->logicalName = $logicalName;
    }

    public function getLogicalName()
    {
        return $this->logicalName;
    }

    public function setNotNull($notNull)
    {
        $this->notNull = $notNull;
    }

    public function getNotNull()
    {
        return $this->notNull;
    }

    public function setPhysicalName($physicalName)
    {
        $this->physicalName = $physicalName;
    }

    public function getPhysicalName()
    {
        return $this->physicalName;
    }


}