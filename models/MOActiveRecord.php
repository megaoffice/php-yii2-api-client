<?php


namespace megaoffice\client\models;

use Yii;
use yii\db\ActiveQueryInterface;
use yii\db\BaseActiveRecord;

class MOActiveRecord extends BaseActiveRecord
{

    public function attributes()
    {
        return static::$attrList;
    }

    /**
     * Returns the primary key **name(s)** for this AR class.
     *
     * Note that an array should be returned even when the record only has a single primary key.
     *
     * For the primary key **value** see [[getPrimaryKey()]] instead.
     *
     * @return string[] the primary key name(s) for this AR class.
     */
    public static function primaryKey()
    {
        return ['id'];
    }

    /**
     * Creates an [[ActiveQueryInterface]] instance for query purpose.
     *
     * The returned [[ActiveQueryInterface]] instance can be further customized by calling
     * methods defined in [[ActiveQueryInterface]] before `one()` or `all()` is called to return
     * populated ActiveRecord instances. For example,
     *
     * ```php
     * // find the customer whose ID is 1
     * $customer = Customer::find()->where(['id' => 1])->one();
     *
     * // find all active customers and order them by their age:
     * $customers = Customer::find()
     *     ->where(['status' => 1])
     *     ->orderBy('age')
     *     ->all();
     * ```
     *
     * This method is also called by [[BaseActiveRecord::hasOne()]] and [[BaseActiveRecord::hasMany()]] to
     * create a relational query.
     *
     * You may override this method to return a customized query. For example,
     *
     * ```php
     * class Customer extends ActiveRecord
     * {
     *     public static function find()
     *     {
     *         // use CustomerQuery instead of the default ActiveQuery
     *         return new CustomerQuery(get_called_class());
     *     }
     * }
     * ```
     *
     * The following code shows how to apply a default condition for all queries:
     *
     * ```php
     * class Customer extends ActiveRecord
     * {
     *     public static function find()
     *     {
     *         return parent::find()->where(['deleted' => false]);
     *     }
     * }
     *
     * // Use andWhere()/orWhere() to apply the default condition
     * // SELECT FROM customer WHERE `deleted`=:deleted AND age>30
     * $customers = Customer::find()->andWhere('age>30')->all();
     *
     * // Use where() to ignore the default condition
     * // SELECT FROM customer WHERE age>30
     * $customers = Customer::find()->where('age>30')->all();
     *
     * @return MOActiveQuery|object
     */
    public static function find()
    {
        return Yii::createObject(MOActiveQuery::class, [get_called_class()]);
    }

    /**
     * Inserts the record into the database using the attribute values of this record.
     *
     * Usage example:
     *
     * ```php
     * $customer = new Customer;
     * $customer->name = $name;
     * $customer->email = $email;
     * $customer->insert();
     * ```
     *
     * @param bool $runValidation whether to perform validation (calling [[\yii\base\Model::validate()|validate()]])
     * before saving the record. Defaults to `true`. If the validation fails, the record
     * will not be saved to the database and this method will return `false`.
     * @param array $attributes list of attributes that need to be saved. Defaults to `null`,
     * meaning all attributes that are loaded from DB will be saved.
     * @return bool whether the attributes are valid and the record is inserted successfully.
     */
    public function insert($runValidation = true, $attributes = null)
    {
        if ($runValidation && !$this->validate($attributes)) {
            Yii::info('Model not inserted due to validation error.', __METHOD__);
            return false;
        }

        if (!$this->beforeSave(true)) {
            return false;
        }

        $values = $this->getDirtyAttributes($attributes);

        $result = Yii::$app->megaofficeClient->insert(static::tableName(), $values);

        if($result['status'] == 'ok'){

            $record = $result['response'];

            $this->setAttribute('id', $record['id']);

            $changedAttributes = array_fill_keys(array_keys($values), null);
            $this->setOldAttributes($values);
            $this->afterSave(true, $changedAttributes);
            return true;

        }else{
            $errors = $result['response'];
            if(is_array($errors)){
                foreach ($errors as $error){
                    $this->addError($error['field'] ?? 'unknown', $error['message'] ?? '');
                }
            }else{
                $this->addError('megaoffice', $this->errors);
            }
            return false;
        }
//        if (($primaryKeys = static::getDb()->schema->insert(static::tableName(), $values)) === false) {
//            return false;
//        }
//        foreach ($primaryKeys as $name => $value) {
//            $id = static::getTableSchema()->columns[$name]->phpTypecast($value);
//            $this->setAttribute($name, $id);
//            $values[$name] = $id;
//        }


    }

    /**
     * Updates the whole table using the provided attribute values and conditions.
     *
     * For example, to change the status to be 1 for all customers whose status is 2:
     *
     * ```php
     * Customer::updateAll(['status' => 1], 'status = 2');
     * ```
     *
     * > Warning: If you do not specify any condition, this method will update **all** rows in the table.
     *
     * Note that this method will not trigger any events. If you need [[EVENT_BEFORE_UPDATE]] or
     * [[EVENT_AFTER_UPDATE]] to be triggered, you need to [[find()|find]] the models first and then
     * call [[update()]] on each of them. For example an equivalent of the example above would be:
     *
     * ```php
     * $models = Customer::find()->where('status = 2')->all();
     * foreach ($models as $model) {
     *     $model->status = 1;
     *     $model->update(false); // skipping validation as no user input is involved
     * }
     * ```
     *
     * For a large set of models you might consider using [[ActiveQuery::each()]] to keep memory usage within limits.
     *
     * @param array $attributes attribute values (name-value pairs) to be saved into the table
     * @param string|array $condition the conditions that will be put in the WHERE part of the UPDATE SQL.
     * Please refer to [[Query::where()]] on how to specify this parameter.
     * @param array $params the parameters (name => value) to be bound to the query.
     * @return int the number of rows updated
     */
    public static function updateAll($attributes, $condition = null, $params = [])
    {

        $result = Yii::$app->megaofficeClient->update($condition['id'], static::tableName(), $attributes);

        if($result['status'] == 'ok'){

            $record = $result['response'];
//
//            $this->setAttribute('id', $record['id']);
//
//            $changedAttributes = array_fill_keys(array_keys($values), null);
//            $this->setOldAttributes($values);
//            $this->afterSave(true, $changedAttributes);
            return $record;
//
        }else{
//            $errors = $result['response'];
//            if(is_array($errors)){
//                foreach ($errors as $error){
//                    $this->addError($error['field'] ?? 'unknown', $error['message'] ?? '');
//                }
//            }else{
//                $this->addError('megaoffice', $this->errors);
//            }
            return false;
        }



        $command = static::getDb()->createCommand();
        $command->update(static::tableName(), $attributes, $condition, $params);

        return $command->execute();
    }


    public function getPrimaryKey($asArray = false)
    {
        return $this->id;
    }

    /**
     * Returns the connection used by this AR class.
     * @return mixed the database connection used by this AR class.
     */
    public static function getDb()
    {
        // TODO: Implement getDb() method.
    }

    /**
     * {@inheritdoc}
     */
    public static function populateRecord($record, $row)
    {
//        $columns = static::$attributes;
//        foreach ($row as $name => $value) {
//
//
//            if (isset($columns[$name])) {
//                $row[$name] = $columns[$name]->phpTypecast($value);
//            }
//        }
        parent::populateRecord($record, $row);
    }

}