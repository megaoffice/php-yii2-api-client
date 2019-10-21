<?php


namespace megaoffice\client\models;


class MOCatalogs
{
    public static function getAll($condition = null){
        $res = \Yii::$app->megaofficeClient->query('/catalogs', $condition);
        return $res;
    }
}