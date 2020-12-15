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

    public $fields = [];

    public $field = [];

    public $reload = false;

    public $validate = ['rules' => ['null' => null]];

    public $search = [];

    public $pri = 'id';

    public $tableButton = [
        'edit' => ['func' => 'saveForm','txt' => 'Edit','type' => 'primary'],
        'del' => ['func' => 'deleteForm','txt' => 'Delete','type' => 'danger']
    ];

    public function __construct()
    {
        $fields = Pdo::getInstance()->fetchAll('SHOW FULL COLUMNS FROM ' . $this->table);
        foreach ($fields as $option => $field){
            if($field['Key'] == 'PRI'){
                $this->pri = $field['Field']; //主键字段
            }
        }
        foreach ($fields as $field => $option){
            $filter = filter_input(INPUT_POST,$option['Field']);
            if($filter) $this->field[$option['Field']] = $filter;
        }
    }

    public $table;

    public function renderPage($page,$num = 15) : array
    {
        $start = $page ? ($page - 1) * $num : 0;
        $list = Pdo::getInstance()->fetchAll('SELECT * FROM `'.$this->table.'` ORDER BY `'.$this->pri.'` DESC LIMIT ?,'.$num, [$start]);
        $count= Pdo::getInstance()->fetch('SELECT count(*) as `total` FROM `'.$this->table.'`',[]);
        return [
            'list' => $list,
            'total' => intval($count['total'] )
        ];
    }

    public function renderPost() : int
    {
        try {
            if($this->create_time == true) $this->field['create_time'] = date('Y-m-d H:i:s');
            Pdo::getInstance()->pdo->beginTransaction(); //事务开启
            if(empty($this->field[$this->pri])){
                $keys = array_keys($this->field);
                $result = Pdo::getInstance()->insert('INSERT INTO `'.$this->table.'`(`'.join('`,`', $keys).'`) VALUES (:'.join(',:', $keys).');', $this->field);
                return  Pdo::getInstance()->pdo->commit() ? $result['lastInsertId'] : 0; //事务提交
            }else{
                $keys = '';
                foreach ($this->field as $key => $value) $keys .= '`'.$key.'`=:'.$key.',';
                $result = Pdo::getInstance()->update('UPDATE `'.$this->table.'` SET '.substr($keys, 0, -1).' WHERE `id`='.$this->field[$this->pri], $this->field);
                return  Pdo::getInstance()->pdo->commit() ? $result : 0;
            }
        } catch (\PDOException $e) {
            Pdo::getInstance()->pdo->rollBack(); //回滚事务
        }
        return 0;
    }

    public function renderDelete(int $id) : int
    {
        return Pdo::getInstance()->delete('DELETE FROM `'.$this->table.'` WHERE `'.$this->pri.'`=?', [$id]);
    }
}