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
class MOPromocodes extends MOActiveRecord
{
    public static $tableName = 'promo/codes';

    protected static $errorCodes = [
        0 => 'No Errors',
        2 => 'No products fiund for this Promo code',
        3 => 'Promo code has already been used',
        4 => 'Too early to use this promo code',
        5 => 'Too late to use this promo code',
    ];

    public static $attrList = [
        'id', 'promo_id', 'code', 'promo',  'active', 'valid_to', 'uses', 'max_uses', 'options',
    ];

    public static function tableName(){
        return static::$tableName;
    }
    public static function applyToSale($sale, $promocode){
        $app = \Yii::$app->megaofficeClient->applyPromocode($sale, $promocode);
        if($app['status'] == 'ok'){
            return $app['response']['sale'] ?? [];
        }else{
            return $sale;
        }
    }

    public static function getErrorCodeLabel($errCode){
        return static::$errorCodes[$errCode] ?? 'Unknown error';
    }

}