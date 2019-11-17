<?php


namespace megaoffice\client\models;


use megaoffice\client\traits\NestedStructuresTrait;

class MOCatalogs extends MOActiveRecord
{
    public static $tableName = 'documents';

//    public function __construct($config = [])
//    {
//
//        parent::__construct($config);
//    }

    use NestedStructuresTrait;

    public static function tableName(){
        return static::$tableName;
    }
}