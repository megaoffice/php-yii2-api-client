<?php


namespace megaoffice\client\models;


class MOCatalogCategories
{
    public static function getAll($condition = null){
        $res = \Yii::$app->megaofficeClient->query('/catalog/categories', $condition);
        return $res;
    }
}