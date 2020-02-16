<?php


namespace megaoffice\client\models;


class MOCatalogCategories extends MOActiveRecord
{
    public static function getAll($condition = null){
        $res = \Yii::$app->megaofficeClient->query('/catalog/categories', $condition);
        return $res;
    }

    public static $tableName = 'catalog/categories';

    public static $attrList = [
        'id', 'catalog_id', 'parent_id', 'name','descr','disabled',  'options', 'products'
    ];

    public static function tableName(){
        return static::$tableName;
    }
}
