<?php


namespace megaoffice\client\models;

/**
 * Class MOClients
 * @package megaoffice\client\models
 *
 * @property integer $id;
 * @property array $options;
 */
class MOBalanceLevels extends MOActiveRecord
{
    public static $tableName = 'balance/levels';

    public static $attrList = [
        'id', 'balance_id', 'parent_id', 'name', 'code',
        'debt', 'credit', 'debt_turnover', 'credit_turnover',
        'email', 'phone',
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