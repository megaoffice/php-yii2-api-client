<?php


namespace megaoffice\client\models;


use megaoffice\client\traits\NestedStructuresTrait;

class MOCatalogs extends MOActiveRecord
{
    public static $tableName = 'catalogs';

//    public function __construct($config = [])
//    {
//
//        parent::__construct($config);
//    }

    use NestedStructuresTrait;
//    public static function getAll($condition = null){
//        $res = \Yii::$app->megaofficeClient->query('/catalogs', $condition);
//        return $res;
//    }
    public static function tableName(){
        return static::$tableName;
    }
}