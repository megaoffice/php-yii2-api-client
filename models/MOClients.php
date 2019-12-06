<?php


namespace megaoffice\client\models;

class MOClients extends MOActiveRecord
{
    public static $tableName = 'clients';


    public function attributes()
    {
        return [
            'id', 'type', 'status', 'source',  'options', 'email', 'phone',
        ];
    }

//    public function __construct($config = [])
//    {
//
//        parent::__construct($config);
//    }

    public static function tableName(){
        return static::$tableName;
    }
}