<?php
/**
 * User: tangyijun
 * Date: 2019-12-16
 * Time: 17:10
 */

use \Libs\Db\Pdo;

class AbstractModel extends \Libs\Instance
{
    public $model;

    public function __construct($model)
    {
        $this->model = $model;
    }

    /**
     * @param $page
     * @param int $num
     * @return array
     * 加载分页
     */
    public function renderPage($page,$num = 20)
    {
        $start = $page ? ($page - 1) * $num : 0;
        $table = $this->model::getInstance()->table;
        $list = Pdo::getInstance()->fetchAll('SELECT * FROM `'.$table.'` ORDER BY `id` DESC LIMIT ?,'.$num, [$start]);
        $count= Pdo::getInstance()->fetch('SELECT count(*) as `total` FROM `'.$table.'`',[]);
        return [
            'list' => $list,
            'total' => intval($count['total'] )
        ];
    }

    /**
     * @param array $data
     * @return int
     * 添加数据
     */
    public function renderAdd(array  $data) : int
    {
        try {
            Pdo::getInstance()->pdo->beginTransaction(); //事务开启
            $table = $this->model::getInstance()->table;
            $keys = array_keys($data);
            $result = Pdo::getInstance()->insert('INSERT INTO `'.$table.'`(`'.join('`,`', $keys).'`) VALUES (:'.join(',:', $keys).');', $data);
            return  Pdo::getInstance()->pdo->commit() ? $result['lastInsertId'] : 0; //事务提交
        } catch (\PDOException $e) {
            Pdo::getInstance()->pdo->rollBack(); //回滚事务
        }
        return 0;
    }
}