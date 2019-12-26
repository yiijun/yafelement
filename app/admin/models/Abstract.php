<?php
/**
 * User: tangyijun
 * Date: 2019-12-16
 * Time: 17:10
 */

use \Libs\Db\Pdo;

class AbstractModel extends \Libs\Instance
{
    public $create_time = true;

    public $table;

    /**
     * @param $page
     * @param int $num
     * @return array
     */
    public function renderPage($page,$num = 15) : array
    {
        $start = $page ? ($page - 1) * $num : 0;
        $list = Pdo::getInstance()->fetchAll('SELECT * FROM `'.$this->table.'` ORDER BY `id` DESC LIMIT ?,'.$num, [$start]);
        $count= Pdo::getInstance()->fetch('SELECT count(*) as `total` FROM `'.$this->table.'`',[]);
        return [
            'list' => $list,
            'total' => intval($count['total'] )
        ];
    }

    /**
     * @param array $data
     * @return int
     */
    public function renderPost(array  $data) : int
    {
        try {
            if($this->create_time == true) $data['create_time'] = date('Y-m-d H:i:s');
            Pdo::getInstance()->pdo->beginTransaction(); //事务开启
            if(empty($data['id'])){
                $keys = array_keys($data);
                $result = Pdo::getInstance()->insert('INSERT INTO `'.$this->table.'`(`'.join('`,`', $keys).'`) VALUES (:'.join(',:', $keys).');', $data);
                return  Pdo::getInstance()->pdo->commit() ? $result['lastInsertId'] : 0; //事务提交
            }else{
                $keys = '';
                foreach ($data as $key => $value) $keys .= '`'.$key.'`=:'.$key.',';
                $result = Pdo::getInstance()->update('UPDATE `'.$this->table.'` SET '.substr($keys, 0, -1).' WHERE `id`='.$data['id'], $data);
                return  Pdo::getInstance()->pdo->commit() ? $result : 0;
            }
        } catch (\PDOException $e) {
            Pdo::getInstance()->pdo->rollBack(); //回滚事务
        }
        return 0;
    }

    /**
     * @param int $id
     * @return int
     */
    public function renderDelete(int $id) : int
    {
        return Pdo::getInstance()->delete('DELETE FROM `'.$this->table.'` WHERE `id`=?', [$id]);
    }
}