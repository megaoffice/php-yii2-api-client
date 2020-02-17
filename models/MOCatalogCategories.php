<?php


namespace megaoffice\client\models;



use megaoffice\client\traits\NestedStructuresTrait;

/**
 *
 * @property int $id
 * @property int $catalog_id
 * @property int $parent_id
 * @property string $name
 * @property string $descr
 * @property string $options
 * @property bool $disabled
 * @property string $created
 *
 * @property  $items
 * @property  $products
 *
 */
class MOCatalogCategories extends MOActiveRecord
{
    use NestedStructuresTrait;

    public static function getAll($condition = null){
        $res = \Yii::$app->megaofficeClient->query('/catalog/categories', $condition);
        return $res;
    }

    public static $tableName = 'catalog/categories';

    public static $attrList = [
        'id', 'catalog_id', 'parent_id', 'name','descr','disabled',  'options', 'items', 'products'
    ];

    public static function tableName(){
        return static::$tableName;
    }
}
