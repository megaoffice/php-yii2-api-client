<?php


namespace megaoffice\client\models;

/**
 * Class MOPromocodes
 * @package megaoffice\client\models
 *
 * @property integer $id;
 * @property integer $type_id;
 * @property bool $disabled;
 * @property string $created;
 * @property string $updated;
 * @property array $options;
 */
class MOPromo extends MOActiveRecord
{
    public static $tableName = 'promo/codes';

    public static $attrList = [
        'id', 'type_id', 'disabled', 'created', 'updated', 'options',
    ];

    public static function tableName(){
        return static::$tableName;
    }



}