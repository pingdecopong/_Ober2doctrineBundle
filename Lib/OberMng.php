<?php

namespace Arte\Ober2doctrineBundle\Lib;

use Arte\Ober2doctrineBundle\Entity;
use Arte\Ober2doctrineBundle\Entity\OberRelation;
use Arte\Ober2doctrineBundle\Entity\OberIndex;
use Arte\Ober2doctrineBundle\Entity\OberAttribute;
use Arte\Ober2doctrineBundle\Entity\OberEntity;

class OberMng
{
    private $entitys;
    private $relations;

    public function setEntitys($entitys)
    {
        $this->entitys = $entitys;
    }

    public function getEntitys()
    {
        return $this->entitys;
    }

    public function setRelations($relations)
    {
        $this->relations = $relations;
    }

    public function getRelations()
    {
        return $this->relations;
    }

    public function __construct()
    {
        $this->entitys = array();
        $this->relations = array();
    }

    public function loadFile($filepath)
    {

        $xml = simplexml_load_file($filepath);

        //テーブル分
        foreach($xml->ENTITY as $entity)
        {

            $oberEntity = new OberEntity();

//            echo "--Entity--\n";

            //showtype
            $showtype = (int)$entity['SHOWTYPE'];
            if($showtype == 2){
                continue;
            }

            //ID
            $oberEntity->setId((string)$entity['ID']);

            //Name
            $oberEntity->setPhysicalName((string)$entity['P-NAME']);
//            echo $oberEntity->getPhysicalName() . "\n";

            $oberAttributes = array();
            //属性分
            foreach($entity->ATTR as $attribute)
            {
                $oberAttribute = new OberAttribute();

//                echo (string)$attribute['P-NAME']."\n";

                //ID
                $oberAttribute->setId((string)$attribute['ID']);

                //型
                $oberAttribute->setDataType(strtolower((string)$attribute['DATATYPE']));

                //length
                $oberAttribute->setLength((string)$attribute['LENGTH']);

                //PK
                if($attribute['PK'] == 1){
                    $oberAttribute->setPrimary(true);
                }else{
                    $oberAttribute->setPrimary(false);
                }

                //autoincrement
                $rule_id = $attribute['RULEID'];
                $rule_name = "";
                $ret = $entity->xpath("/ERD/RULE[@ID=$rule_id]");
                if(isset($ret[0]['L-NAME'])){
                    $rule_name = $ret[0]['L-NAME'];
                }
                if(($oberAttribute->getDataType() == 'integer' && $attribute['RULE'] == 'AUTO_INCREMENT') || ($oberAttribute->getDataType() == 'integer' && $rule_name == 'AUTO_INCREMENT')){
                    $oberAttribute->setAutoIncrementFlug(true);
                }

                //デフォルト値
                if($attribute['DEF'] != "")
                {
                    if($oberAttribute->getDataType() == 'boolean')
                    {
                        if($attribute['DEF'] == 'true'){
                            $oberAttribute->setDefault(true);
                        }
                        if($attribute['DEF'] == 'false'){
                            $oberAttribute->setDefault(false);
                        }

                    }else if($oberAttribute->getDataType() == 'integer')
                    {
                        $oberAttribute->setDefault((int)$attribute['DEF']);
                    }else
                    {
                        $oberAttribute->setDefault((string)$attribute['DEF']);
                    }
                }

                //NULL
                if($attribute['NULL'] == 0){
                    $oberAttribute->setNotNull(false);
                }else if($attribute['NULL'] == 1){
                    $oberAttribute->setNotNull(true);
                }

                //カラム名
                $columnName = (string)$attribute['P-NAME'];
                $oberAttribute->setPhysicalName($columnName);

                //
                $oberAttributes[(string)$oberAttribute->getId()] = $oberAttribute;

            }
            $oberEntity->setAttributes($oberAttributes);

            //index分
            $oberIndexes = array();
            foreach($entity->INDEX as $index)
            {
//                echo "--index--\n";

                $oberIndex = new OberIndex();

                //id
                $oberIndex->setId((string)$index['ID']);

                //name
                $index_name = (string)$index['P-NAME'];
                $oberIndex->setPhysicalName($index_name);

                //インデックスタイプ
                $index_type = (string)$index['I-TYPE'];
                $oberIndex->setType($index_type);
                if($index_type == 0){
                    //主キー
//                    continue;
                }else if($index_type == 1){
                    //UNIQUE
//                    $columns["$index_name"]['type'] = 'unique';
                }else if($index_type == 3){
                    //INDEX
//                    $columns["$index_name"]['type'] = 'index';
                }

                //
                foreach($index->COLUMN as $index_column)
                {
//                    echo "--index--column--\n";
                    $index_entity_id = $entity['ID'];
                    $index_column_id = $index_column['ID'];
                    $index_column_object = $entity->xpath("/ERD/ENTITY[@ID=$index_entity_id]/ATTR[@ID=$index_column_id]");

                    foreach($index_column_object as $value)
                    {
//                        echo "--index--column--foreach--\n";

                        $index_columns = array();
                        $index_columns = $oberIndex->getColumns();
                        $index_columns[] = $oberAttributes[(string)$value['ID']];
                        $oberIndex->setColumns($index_columns);
                    }
                }

                $oberIndexes[(string)$oberIndex->getId()] = $oberIndex;
            }
            $oberEntity->setIndexes($oberIndexes);
            //
            $this->entitys[(string)$oberEntity->getId()] = $oberEntity;
        }

        //リレーションタイプ（多対多以外）
        $ret = $entity->xpath("/ERD/RELATION[@R-TYPE=0 or @R-TYPE=1 or @R-TYPE=2]");
    	foreach($ret as $value)
        {
//            echo "--relation--\n";

            //showtype
            $showtype = (int)$value['SHOWTYPE'];
            if($showtype == 2){
                continue;
            }

            $oberRelation = new OberRelation();

            $p_entity_id = $value['P-ENTITY'];
            $c_entity_id = $value['C-ENTITY'];
            $relation_id = $value['ID'];
            $relation_type = $value['R-TYPE'];

            $oberRelation->setId($relation_id);

//            echo $p_entity_id."\n";
//            echo $c_entity_id."\n";
//            echo $relation_id."\n";
//            echo $relation_type."\n";

            $p_entity = $this->entitys[(string)$p_entity_id];
            $oberRelation->setParentEntity($p_entity);
//            echo "ParentEntityName\n";
//            echo $p_entity->getPhysicalName();
//            echo "\n";

            $c_entity = $this->entitys[(string)$c_entity_id];
            $oberRelation->setChildEntity($c_entity);
//            echo "ChildrenEntityName\n";
//            echo $c_entity->getPhysicalName();
//            echo "\n";

            //リレーションタイプ
            $c_relation_type = "";
            if($value['R-TYPE'] == 0 || $value['R-TYPE'] == 1)
            {
                if(($value['CARDINALITY'] == 3 && $value['CARDINALITYC'] == 1) || $value['CARDINALITY'] == 2)
                {
                    $c_relation_type = 'OneToMany';

                }else if($value['CARDINALITY'] == 0 || $value['CARDINALITY'] == 1)
                {
                    $c_relation_type = 'OneToMany';
                }
            }else{
                //多対多
                $c_relation_type = 'ManyToMany';
            }
            $oberRelation->setRelationType($c_relation_type);
//            echo "RelationType\n";
//            echo $c_relation_type;
//            echo "\n";

            //多対多以外の場合はリレーションカラムを取得する
            if($value['R-TYPE'] != 2)
            {
                //親
                $ret = $entity->xpath("/ERD/ENTITY/ATTR/FK[@RELATION=$relation_id]");
                $attr = $ret[0]['ATTR'];
//                echo $attr;
//                echo "\n";
                //
                $p_entity_attributes = $p_entity->getAttributes();
                $oberRelation->setParentColumn($p_entity_attributes[(string)$attr]);
//                echo $p_entity_attributes[(string)$attr]->getPhysicalName();
//                echo "\n";

                //子
                $ret = $entity->xpath("/ERD/ENTITY/ATTR/FK[@RELATION=$relation_id]/..");
                $c_column_name = $ret[0]['ID'];
//                echo $c_column_name;
//                echo "\n";
                //
                $c_entity_attributes = $c_entity->getAttributes();
                $oberRelation->setChildColumn($c_entity_attributes[(string)$c_column_name]);
//                echo $c_entity_attributes[(string)$c_column_name]->getPhysicalName();
//                echo "\n";
            }

            $this->relations[(string)$oberRelation->getId()] = $oberRelation;

//            print_r($oberRelation);
        }

    }
}
