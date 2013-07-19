<?php

namespace Arte\Ober2doctrineBundle\Entity;


/**
 * OberRelation
 *
 */
class OberRelation
{
    private $id;
    private $showType;
    private $parentEntity;
    private $childEntity;
    private $relationType;
    private $parentColumn;
    private $childColumn;

    public function setChildColumn($childColumn)
    {
        $this->childColumn = $childColumn;
    }

    public function getChildColumn()
    {
        return $this->childColumn;
    }

    public function setParentColumn($parentColumn)
    {
        $this->parentColumn = $parentColumn;
    }

    public function getParentColumn()
    {
        return $this->parentColumn;
    }

    public function setRelationType($relationType)
    {
        $this->relationType = $relationType;
    }

    public function getRelationType()
    {
        return $this->relationType;
    }

    public function setChildEntity($childEntity)
    {
        $this->childEntity = $childEntity;
    }

    public function getChildEntity()
    {
        return $this->childEntity;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setParentEntity($parentEntity)
    {
        $this->parentEntity = $parentEntity;
    }

    public function getParentEntity()
    {
        return $this->parentEntity;
    }

    public function setShowType($showType)
    {
        $this->showType = $showType;
    }

    public function getShowType()
    {
        return $this->showType;
    }
}
