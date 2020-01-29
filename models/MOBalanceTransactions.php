<?php


namespace megaoffice\client\models;

/**
 * Class MOClients
 * @package megaoffice\client\models
 *
 * @property integer $id;
 * @property array $options;
 */
class MOBalanceTransactions extends MOActiveRecord
{
    public static $tableName = 'balance/transactions';

    public static $attrList = [
        'id', 'balance_id', 'status', 'debt_id', 'credit_id',
        'value', 'doc_id', 'options', 'created', 'executed',
    ];


//    public function __construct($config = [])
//    {
//
//        parent::__construct($config);
//    }

    public static function tableName(){
        return static::$tableName;
    }
}