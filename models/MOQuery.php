<?php


namespace megaoffice\client\models;

use Yii;
use yii\base\Component;
use yii\db\Command;
use yii\db\Connection;
use yii\db\ExpressionInterface;
use yii\db\QueryInterface;
use yii\db\QueryTrait;
use yii\helpers\ArrayHelper;

class MOQuery extends Component implements QueryInterface
{
    use QueryTrait;


    public $select;


    /**
     * @var
     */
    public $from;

    public $groupBy;
    public $join;
    public $having;
    public $union;

    /**
     * @var array list of query parameter values indexed by parameter placeholders.
     * For example, `[':name' => 'Dan', ':age' => 31]`.
     */
    public $params = [];


    /**
     * Creates a DB command that can be used to execute this query.
     * @param Connection $db the database connection used to generate the SQL statement.
     * If this parameter is not given, the `db` application component will be used.
     * @return Command the created DB command instance.
     */
    public function createCommand($db = null)
    {
        $config = ['class' => 'megaoffice\client\models\MOCommand', 'moQuery' => $this];

        $command = Yii::createObject($config);
//
//        if ($db === null) {
//            $db = Yii::$app->getDb();
//        }
//        list($sql, $params) = $db->getQueryBuilder()->build($this);
//
//        $command = $db->createCommand($sql, $params);
//        $this->setCommandCache($command);

        return $command;
    }


    /**
     * Executes the query and returns all results as an array.
     * @param Connection $db the database connection used to generate the SQL statement.
     * If this parameter is not given, the `db` application component will be used.
     * @return array the query results. If the query results in nothing, an empty array will be returned.
     */
    public function all($db = null)
    {
        if ($this->emulateExecution) {
            return [];
        }
        $rows = $this->createCommand($db)->queryAll();
        return $this->populate($rows);
    }

    /**
     * Converts the raw query results into the format as specified by this query.
     * This method is internally used to convert the data fetched from database
     * into the format as required by this query.
     * @param array $rows the raw query result from database
     * @return array the converted query result
     */
    public function populate($rows)
    {
        if ($this->indexBy === null) {
            return $rows;
        }
        $result = [];
        foreach ($rows as $row) {
            $result[ArrayHelper::getValue($row, $this->indexBy)] = $row;
        }

        return $result;
    }

    /**
     * Executes the query and returns a single row of result.
     * @param Connection $db the database connection used to execute the query.
     * If this parameter is not given, the `db` application component will be used.
     * @return array|bool the first row (in terms of an array) of the query result. False is returned if the query
     * results in nothing.
     */
    public function one($db = null)
    {
        return $this->createCommand($db)->queryOne();
    }

    /**
     * Returns the number of records.
     * @param string $q the COUNT expression. Defaults to '*'.
     * @param Connection $db the database connection used to execute the query.
     * If this parameter is not given, the `db` application component will be used.
     * @return int number of records.
     */
    public function count($q = '*', $db = null)
    {
        // TODO: Implement count() method.
    }

    /**
     * Returns a value indicating whether the query result contains any row of data.
     * @param Connection $db the database connection used to execute the query.
     * If this parameter is not given, the `db` application component will be used.
     * @return bool whether the query result contains any row of data.
     */
    public function exists($db = null)
    {
        // TODO: Implement exists() method.
    }

    /**
     * Sets the [[indexBy]] property.
     * @param string|callable $column the name of the column by which the query results should be indexed by.
     * This can also be a callable (e.g. anonymous function) that returns the index value based on the given
     * row data. The signature of the callable should be:
     *
     * ```php
     * function ($row)
     * {
     *     // return the index value corresponding to $row
     * }
     * ```
     *
     * @return $this the query object itself
     */
    public function indexBy($column)
    {
        // TODO: Implement indexBy() method.
    }

    /**
     * Sets the WHERE part of the query.
     *
     * The `$condition` specified as an array can be in one of the following two formats:
     *
     * - hash format: `['column1' => value1, 'column2' => value2, ...]`
     * - operator format: `[operator, operand1, operand2, ...]`
     *
     * A condition in hash format represents the following SQL expression in general:
     * `column1=value1 AND column2=value2 AND ...`. In case when a value is an array,
     * an `IN` expression will be generated. And if a value is `null`, `IS NULL` will be used
     * in the generated expression. Below are some examples:
     *
     * - `['type' => 1, 'status' => 2]` generates `(type = 1) AND (status = 2)`.
     * - `['id' => [1, 2, 3], 'status' => 2]` generates `(id IN (1, 2, 3)) AND (status = 2)`.
     * - `['status' => null]` generates `status IS NULL`.
     *
     * A condition in operator format generates the SQL expression according to the specified operator, which
     * can be one of the following:
     *
     * - **and**: the operands should be concatenated together using `AND`. For example,
     *   `['and', 'id=1', 'id=2']` will generate `id=1 AND id=2`. If an operand is an array,
     *   it will be converted into a string using the rules described here. For example,
     *   `['and', 'type=1', ['or', 'id=1', 'id=2']]` will generate `type=1 AND (id=1 OR id=2)`.
     *   The method will *not* do any quoting or escaping.
     *
     * - **or**: similar to the `and` operator except that the operands are concatenated using `OR`. For example,
     *   `['or', ['type' => [7, 8, 9]], ['id' => [1, 2, 3]]]` will generate `(type IN (7, 8, 9) OR (id IN (1, 2, 3)))`.
     *
     * - **not**: this will take only one operand and build the negation of it by prefixing the query string with `NOT`.
     *   For example `['not', ['attribute' => null]]` will result in the condition `NOT (attribute IS NULL)`.
     *
     * - **between**: operand 1 should be the column name, and operand 2 and 3 should be the
     *   starting and ending values of the range that the column is in.
     *   For example, `['between', 'id', 1, 10]` will generate `id BETWEEN 1 AND 10`.
     *
     * - **not between**: similar to `between` except the `BETWEEN` is replaced with `NOT BETWEEN`
     *   in the generated condition.
     *
     * - **in**: operand 1 should be a column or DB expression, and operand 2 be an array representing
     *   the range of the values that the column or DB expression should be in. For example,
     *   `['in', 'id', [1, 2, 3]]` will generate `id IN (1, 2, 3)`.
     *   The method will properly quote the column name and escape values in the range.
     *
     *   To create a composite `IN` condition you can use and array for the column name and value, where the values are indexed by the column name:
     *   `['in', ['id', 'name'], [['id' => 1, 'name' => 'foo'], ['id' => 2, 'name' => 'bar']] ]`.
     *
     *   You may also specify a sub-query that is used to get the values for the `IN`-condition:
     *   `['in', 'user_id', (new Query())->select('id')->from('users')->where(['active' => 1])]`
     *
     * - **not in**: similar to the `in` operator except that `IN` is replaced with `NOT IN` in the generated condition.
     *
     * - **like**: operand 1 should be a column or DB expression, and operand 2 be a string or an array representing
     *   the values that the column or DB expression should be like.
     *   For example, `['like', 'name', 'tester']` will generate `name LIKE '%tester%'`.
     *   When the value range is given as an array, multiple `LIKE` predicates will be generated and concatenated
     *   using `AND`. For example, `['like', 'name', ['test', 'sample']]` will generate
     *   `name LIKE '%test%' AND name LIKE '%sample%'`.
     *   The method will properly quote the column name and escape special characters in the values.
     *   Sometimes, you may want to add the percentage characters to the matching value by yourself, you may supply
     *   a third operand `false` to do so. For example, `['like', 'name', '%tester', false]` will generate `name LIKE '%tester'`.
     *
     * - **or like**: similar to the `like` operator except that `OR` is used to concatenate the `LIKE`
     *   predicates when operand 2 is an array.
     *
     * - **not like**: similar to the `like` operator except that `LIKE` is replaced with `NOT LIKE`
     *   in the generated condition.
     *
     * - **or not like**: similar to the `not like` operator except that `OR` is used to concatenate
     *   the `NOT LIKE` predicates.
     *
     * - **exists**: operand 1 is a query object that used to build an `EXISTS` condition. For example
     *   `['exists', (new Query())->select('id')->from('users')->where(['active' => 1])]` will result in the following SQL expression:
     *   `EXISTS (SELECT "id" FROM "users" WHERE "active"=1)`.
     *
     * - **not exists**: similar to the `exists` operator except that `EXISTS` is replaced with `NOT EXISTS` in the generated condition.
     *
     * - Additionally you can specify arbitrary operators as follows: A condition of `['>=', 'id', 10]` will result in the
     *   following SQL expression: `id >= 10`.
     *
     * **Note that this method will override any existing WHERE condition. You might want to use [[andWhere()]] or [[orWhere()]] instead.**
     *
     * @param array $condition the conditions that should be put in the WHERE part.
     * @return $this the query object itself
     * @see andWhere()
     * @see orWhere()
     */
    public function where($condition)
    {
        $this->where = $condition;
        return $this;
    }
    /**
     * Adds an additional WHERE condition to the existing one.
     * The new condition and the existing one will be joined using the `AND` operator.
     * @param string|array|ExpressionInterface $condition the new WHERE condition. Please refer to [[where()]]
     * on how to specify this parameter.
     * @param array $params the parameters (name => value) to be bound to the query.
     * @return $this the query object itself
     * @see where()
     * @see orWhere()
     */

    public function andWhere($condition, $params = [])
    {
        if ($this->where === null) {
            $this->where = $condition;
        } elseif (is_array($this->where) && isset($this->where[0]) && strcasecmp($this->where[0], 'and') === 0) {
            $this->where[] = $condition;
        } else {
            $this->where = ['and', $this->where, $condition];
        }
        $this->addParams($params);
        return $this;
    }


    /**
     * Adds an additional WHERE condition to the existing one.
     * The new condition and the existing one will be joined using the `OR` operator.
     * @param string|array|ExpressionInterface $condition the new WHERE condition. Please refer to [[where()]]
     * on how to specify this parameter.
     * @param array $params the parameters (name => value) to be bound to the query.
     * @return $this the query object itself
     * @see where()
     * @see andWhere()
     */
    public function orWhere($condition, $params = [])
    {
        if ($this->where === null) {
            $this->where = $condition;
        } else {
            $this->where = ['or', $this->where, $condition];
        }
        $this->addParams($params);
        return $this;
    }

    /**
     * Sets the WHERE part of the query ignoring empty parameters.
     *
     * @param array $condition the conditions that should be put in the WHERE part. Please refer to [[where()]]
     * on how to specify this parameter.
     * @return $this the query object itself
     * @see andFilterWhere()
     * @see orFilterWhere()
     */
    public function filterWhere(array $condition)
    {
        // TODO: Implement filterWhere() method.
    }

    /**
     * Adds an additional WHERE condition to the existing one ignoring empty parameters.
     * The new condition and the existing one will be joined using the 'AND' operator.
     * @param array $condition the new WHERE condition. Please refer to [[where()]]
     * on how to specify this parameter.
     * @return $this the query object itself
     * @see filterWhere()
     * @see orFilterWhere()
     */
    public function andFilterWhere(array $condition)
    {
        // TODO: Implement andFilterWhere() method.
    }

    /**
     * Adds an additional WHERE condition to the existing one ignoring empty parameters.
     * The new condition and the existing one will be joined using the 'OR' operator.
     * @param array $condition the new WHERE condition. Please refer to [[where()]]
     * on how to specify this parameter.
     * @return $this the query object itself
     * @see filterWhere()
     * @see andFilterWhere()
     */
    public function orFilterWhere(array $condition)
    {
        // TODO: Implement orFilterWhere() method.
    }

    /**
     * Sets the ORDER BY part of the query.
     * @param string|array $columns the columns (and the directions) to be ordered by.
     * Columns can be specified in either a string (e.g. "id ASC, name DESC") or an array
     * (e.g. `['id' => SORT_ASC, 'name' => SORT_DESC]`).
     * The method will automatically quote the column names unless a column contains some parenthesis
     * (which means the column contains a DB expression).
     * @return $this the query object itself
     * @see addOrderBy()
     */
    public function orderBy($columns)
    {
        // TODO: Implement orderBy() method.
    }

    /**
     * Adds additional ORDER BY columns to the query.
     * @param string|array $columns the columns (and the directions) to be ordered by.
     * Columns can be specified in either a string (e.g. "id ASC, name DESC") or an array
     * (e.g. `['id' => SORT_ASC, 'name' => SORT_DESC]`).
     * The method will automatically quote the column names unless a column contains some parenthesis
     * (which means the column contains a DB expression).
     * @return $this the query object itself
     * @see orderBy()
     */
    public function addOrderBy($columns)
    {
        // TODO: Implement addOrderBy() method.
    }

    /**
     * Sets the LIMIT part of the query.
     * @param int|ExpressionInterface|null $limit the limit. Use null or negative value to disable limit.
     * @return $this the query object itself
     */
    public function limit($limit)
    {
        $this->limit = $limit;
        return $this;
    }


    /**
     * Sets the OFFSET part of the query.
     * @param int|null $offset the offset. Use null or negative value to disable offset.
     * @return $this the query object itself
     */
    public function offset($offset)
    {
        // TODO: Implement offset() method.
    }

    /**
     * Sets whether to emulate query execution, preventing any interaction with data storage.
     * After this mode is enabled, methods, returning query results like [[one()]], [[all()]], [[exists()]]
     * and so on, will return empty or false values.
     * You should use this method in case your program logic indicates query should not return any results, like
     * in case you set false where condition like `0=1`.
     * @param bool $value whether to prevent query execution.
     * @return $this the query object itself.
     * @since 2.0.11
     */
    public function emulateExecution($value = true)
    {
        // TODO: Implement emulateExecution() method.
    }

    /**
     * Adds additional parameters to be bound to the query.
     * @param array $params list of query parameter values indexed by parameter placeholders.
     * For example, `[':name' => 'Dan', ':age' => 31]`.
     * @return $this the query object itself
     * @see params()
     */
    public function addParams($params)
    {
        if (!empty($params)) {
            if (empty($this->params)) {
                $this->params = $params;
            } else {
                foreach ($params as $name => $value) {
                    if (is_int($name)) {
                        $this->params[] = $value;
                    } else {
                        $this->params[$name] = $value;
                    }
                }
            }
        }

        return $this;
    }

}