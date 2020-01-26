<?php


namespace megaoffice\client\models;


use megaoffice\client\traits\NestedStructuresTrait;

class MODocuments extends MOActiveRecord
{
    public static $tableName = 'documents';

    public static $attrList = [
        'id', 'type_id', 'status_id', 'deleted', 'uuid', 'options', 'created',
    ];

    public function rules()
    {
        return [
            ['type_id', 'in', 'range' => [1, 2]],
            ['options', 'safe'],
            ['status_id', 'safe'],
            ['deleted', 'boolean'],
        ];
    }

    use NestedStructuresTrait;

    public static function tableName(){
        return static::$tableName;
    }
}