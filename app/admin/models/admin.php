<?php
/**
 * User: tangyijun
 * Date: 2020-01-02
 * Time: 18:07
 */
use \libs\db\pdo;
class AdminModel extends AbstractModel
{
    public $table = 'yaf_admin';

    public $fields = [
        [
            'type' => 'text',
            'value' => '',
            'title' => '用户名',
            'key' => 'username',
        ],
        [
            'type' => 'text',
            'value' => '',
            'title' => '密码',
            'key' => 'pwd',
            'isTable' => true
        ],
    ];

    public $validate = [
        'rules' => [
            'username' => [
                ["required" => true, "message" => '用户名必须填写', "trigger" => 'blur']
            ],
            'pwd' => [
                ["required" => true, "message" => '用户密码必须填写', "trigger" => 'blur'],
                ['min' => 6,'max' => 20,'message' => '长度在 6 到 20 个字符','trigger' => 'blur']
            ],
        ]
    ];

    public $tableButton = [
        'del' => ['func' => 'deleteForm','txt' => 'Delete','type' => 'danger']
    ];

    public $search = ['username'];


    public function renderPage($page,$num = 15) : array
    {
        $start = $page ? ($page - 1) * $num : 0;
        $list = Pdo::getInstance()->fetchAll('SELECT `id`,`username`,`create_time` FROM `'.$this->table.'` ORDER BY `id` DESC LIMIT ?,'.$num, [$start]);
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
            $keys = array_keys($this->field);
            $this->field['pwd'] = password_hash($this->field['pwd'],true);
            $result = Pdo::getInstance()->insert('INSERT INTO `'.$this->table.'`(`'.join('`,`', $keys).'`) VALUES (:'.join(',:', $keys).');', $this->field);
            return  Pdo::getInstance()->pdo->commit() ? $result['lastInsertId'] : 0; //事务提交
        } catch (\PDOException $e) {
            Pdo::getInstance()->pdo->rollBack(); //回滚事务
        }
        return 0;
    }
}