<?php


namespace megaoffice\client\models;


use megaoffice\client\traits\NestedStructuresTrait;

class MODocuments extends MOActiveRecord
{
    public static $tableName = 'documents';

    public static $attrList = [
        'id', 'type_id', 'status_id', 'deleted', 'uuid', 'options', 'created',
    ];

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