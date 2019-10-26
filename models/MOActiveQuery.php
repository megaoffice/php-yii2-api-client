<?php


namespace megaoffice\client\models;


use yii\db\ActiveQueryInterface;
use yii\db\ActiveQueryTrait;
use yii\db\ActiveRecordInterface;
use yii\db\ActiveRelationTrait;

class MOActiveQuery extends MOQuery implements ActiveQueryInterface
{
    use ActiveQueryTrait;
    //use ActiveRelationTrait;

    /**
     * Executes query and returns all results as an array.
     * @param Connection $db the DB connection used to create the DB command.
     * If null, the DB connection returned by [[modelClass]] will be used.
     * @return array|MOActiveRecord[] the query results. If the query results in nothing, an empty array will be returned.
     */
    public function all($db = null)
    {
        return parent::all($db);
    }


    /**
     * Constructor.
     * @param string $modelClass the model class associated with this query
     * @param array $config configurations to be applied to the newly created query object
     */
    public function __construct($modelClass, $config = [])
    {
        $this->modelClass = $modelClass;
        parent::__construct($config);
    }

    /**
     * Specifies the relation associated with the junction table for use in relational query.
     * @param string $relationName the relation name. This refers to a relation declared in the [[ActiveRelationTrait::primaryModel|primaryModel]] of the relation.
     * @param callable $callable a PHP callback for customizing the relation associated with the junction table.
     * Its signature should be `function($query)`, where `$query` is the query to be customized.
     * @return $this the relation object itself.
     */
    public function via($relationName, callable $callable = null)
    {
        // TODO: Implement via() method.
    }

    /**
     * Finds the related records for the specified primary record.
     * This method is invoked when a relation of an ActiveRecord is being accessed in a lazy fashion.
     * @param string $name the relation name
     * @param ActiveRecordInterface $model the primary model
     * @return mixed the related record(s)
     */
    public function findFor($name, $model)
    {
        // TODO: Implement findFor() method.
    }
}