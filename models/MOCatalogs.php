<?php


namespace megaoffice\client\models;


use megaoffice\client\traits\NestedStructuresTrait;

class MOCatalogs
{
    use NestedStructuresTrait;
    public static function getAll($condition = null){
        $res = \Yii::$app->megaofficeClient->query('/catalogs', $condition);
        return $res;
    }
}