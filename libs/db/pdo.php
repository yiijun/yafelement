<?php
/**
 * User: tangyijun
 * Date: 2019-02-13
 * Time: 11:08
 */
namespace libs\db;
use libs\instance;

class Pdo extends Instance
{
    public $pdo;

    /**
     * Pdo constructor.
     * @param string $name
     */
    public function __construct($name = 'default')
    {
        $conf = \Yaf\Application::app()->getConfig()->db[$name]; //获取对应数据库配置
        $dsn = 'mysql:host='.$conf['host'].';port='.$conf['port'].';dbname='.$conf['dbname'].';charset='.$conf['charset'];
        $this->pdo = new \PDO(
            $dsn,
            $conf['username'],
            $conf['passwd'],
            $conf['options']
        );
        $this->pdo->setAttribute(\PDO::ATTR_ERRMODE,\PDO::ERRMODE_EXCEPTION);
    }

    /**
     * @param $statement
     * @param array $parameter
     * @return bool|PDOStatement
     * 使用预处理语句
     */
    public function query($statement, array $parameter = [])
    {
        $rs = $this->pdo->prepare($statement);
        $flag = isset($parameter[0]);
        foreach ($parameter as $key => $value) {
            switch (1) {
                case is_int($value):
                    $type = \PDO::PARAM_INT;
                    break;
                case is_bool($value):
                    $type = \PDO::PARAM_BOOL;
                    break;
                case is_null($value):
                    $type = \PDO::PARAM_NULL;
                    break;
                default:
                    $type = \PDO::PARAM_STR;
                    break;
            }
            $rs->bindValue($flag ? ($key + 1) : $key, $value, $type);
        }
        $rs->execute();
        return $rs;
    }

    /**
     * @param $statement
     * @param array $parameter
     * @param int $type
     * @return array
     */
    public function fetchAll($statement, array $parameter = [],$type = \PDO::FETCH_ASSOC) : array
    {
        $rs = $this->query($statement, $parameter);
        $results = $rs->fetchAll($type) ?: [];
        return $results;
    }

    /**
     * @param $statement
     * @param array $parameter
     * @param int $type
     * @return mixed
     */
    public function fetch($statement, array $parameter = [],  $type = \PDO::FETCH_ASSOC) : array
    {
        $rs = $this->query($statement, $parameter);
        $results = $rs->fetch($type) ?: [];

        return $results;
    }

    /**
     * @param $statement
     * @param array $parameter
     * @param int $column
     * @return mixed
     */
    public function fetchColumn($statement, array $parameter = [],  $column = 0)
    {
        $rs = $this->query($statement, $parameter);
        $results = $rs->fetchColumn($column);

        return $results;
    }

    /**
     * @param $statement
     * @param array $parameter
     * @return array
     */
    public function insert($statement, array $parameter = []) : array
    {
        return [
            'rowCount' => $this->query($statement, $parameter)->rowCount(),
            'lastInsertId' => $this->pdo->lastInsertId()
        ];
    }

    /**
     * @param $statement
     * @param array $parameter
     * @return int ,返回受影响的行数
     */
    public function update($statement, array $parameter = []) : int
    {

        return $this->query($statement, $parameter)->rowCount();
    }

    /**
     * @param $statement
     * @param array $parameter
     * @return int
     */
    public function delete($statement, array $parameter = []) :int
    {
        return $this->query($statement, $parameter)->rowCount();
    }
}