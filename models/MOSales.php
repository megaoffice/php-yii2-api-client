<?php


namespace megaoffice\client\models;

/**
 * Class MOSales
 * @package megaoffice\client\models
 *
 * @property integer $id;
 * @property integer $type_id;
 * @property integer $status_id;
 * @property integer $seller_id;
 * @property integer $buyer_type;
 * @property integer $buyer_id;
 * @property integer $source;
 * @property integer $location;
 * @property array   $address;
 * @property array   $options;
 * @property array   $items;
 * @property array   $deliveries;
 * @property string  $actual;
 * @property string  $region;
 * @property string  $currency;
 * @property string  $promoId;
 * @property string  $promoError;
 *
 */
class MOSales extends MOActiveRecord
{
    public $region;
    public $currency;
    public $promoId;
    public $promoError;


    public static $tableName = 'sales';

    public static $attrList = [
        'id', 'type_id', 'status_id','seller_id', 'buyer_type', 'address', 'location_id',
        'time_from', 'time_to', 'create_deliveries',
        'buyer_id', 'source',  'options', 'items', 'deliveries', 'actual',
    ];

    public function applyPromocode($promocode){
        $result = MOPromocodes::applyToSale($this, $promocode);
        $this->promoError = $result['promoError'] ?? -1;
        if($this->promoError == 0){
            $this->items = $result['items'] ?? [];
        }
    }

    public static function tableName(){
        return static::$tableName;
    }
    public function init()
    {
        if(empty($this->type_id)){
            $this->type_id = 1;
        }
        if(empty($this->items)){
            $this->items = [];
        }
        if(empty($this->options)){
            $this->options = [];
        }
        if(empty($this->status_id)){
            $this->status_id = 0;
        }
       if(empty($this->buyer_type)){
            $this->buyer_type = 0;
        }
        parent::init();
    }
}