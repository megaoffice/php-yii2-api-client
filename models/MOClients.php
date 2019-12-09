<?php


namespace megaoffice\client\models;

/**
 * Class MOClients
 * @package megaoffice\client\models
 *
 * @property integer $id;
 * @property array $options;
 */
class MOClients extends MOActiveRecord
{
    public static $tableName = 'clients';

    public static $attrList = [
        'id', 'type', 'status', 'source',  'options', 'email', 'phone',
    ];


//    public function __construct($config = [])
//    {
//
//        parent::__construct($config);
//    }

    public static function tableName(){
        return static::$tableName;
    }
}