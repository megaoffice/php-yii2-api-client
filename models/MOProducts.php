<?php


namespace megaoffice\client\models;

/**
 * Class MOProducts
 * @package megaoffice\client\models
 *
 * @property integer $id;
 * @property integer $category_id;
 * @property integer $type_id;
 * @property array $options;
 */
class MOProducts extends MOActiveRecord
{
    public static function getAll($condition = null){
        $res = \Yii::$app->megaofficeClient->query('/catalog/products', $condition);
        return $res;
    }

    public static $tableName = 'catalog/products';

    public static $attrList = [
        'id', 'category_id', 'type_id', 'options',
    ];

    public static function tableName(){
        return static::$tableName;
    }



}