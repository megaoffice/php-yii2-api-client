<?php


namespace megaoffice\client\models;

/**
 * Class MOPromocodes
 * @package megaoffice\client\models
 *
 * @property integer $id;
 * @property integer $promo_id;
 * @property string $code;
 * @property bool $active;
 * @property string $valid_to;
 * @property integer $uses;
 * @property integer $max_uses;
 * @property array $options;
 */
class MOProducts extends MOActiveRecord
{
    public static $tableName = 'promo/codes';

    public static $attrList = [
        'id', 'promo_id', 'code', 'active', 'valid_to', 'uses', 'max_uses', 'options',
    ];

    public static function tableName(){
        return static::$tableName;
    }



}