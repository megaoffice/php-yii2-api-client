<?php


namespace megaoffice\client\models;

/**
 * Class MOSales
 * @package megaoffice\client\models
 *
 * @property integer $id;
 * @property array $options;
 */
class MOSales extends MOActiveRecord
{
    public static $tableName = 'sales';

    public static $attrList = [
        'id', 'type_id', 'status_id','seller_id', 'buyer_type', 'buyer_id', 'source',  'options', 'items', 'actual',
    ];


    public static function tableName(){
        return static::$tableName;
    }
}