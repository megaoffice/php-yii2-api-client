<?php


namespace megaoffice\client\models;


class MOProducts
{
    public static function getAll($condition = null){
        $res = \Yii::$app->megaofficeClient->query('/catalog/products', $condition);
        return $res;
    }
}