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
        [
            'type' => 'cascader',
            'value' => '',
            'title' => '所属上级',
            'key' => 'pid',
            'show' => [],
            'prop' => [
                'callback' => ['AdminModel','getInstance'], //回调方法拉取级联数据
                'function' => 'getParentAdmin',
                'props' => [
                    'levels' => false,   //定义是否显示完整的路径
                    'emitPath' => false, //定义是否返回最后一级得数据，true 返回一个数组
                    'checkStrictly' => true, //开启之后可以选择任意一级
                    'label' => 'name',
                    'value' => 'id',
                ]
            ],
            'isTable' => true,
        ],
        [
            'type' => 'select',
            'value' => '',
            'title' => '所属角色',
            'key' => 'role_id',
            'show' => [],
            'prop' => [
                'callback' => ['RoleModel','getInstance'], //回调方法拉取级联数据
                'function' => 'getRoleAll',
                'props' => [
                    'label' => 'name',
                    'value' => 'id',
                ]
            ],
            'alias' => [
                'callback' => ['RoleModel', 'getInstance'],
                'function' => 'getRoleAll',
                'props' => [
                    'label' => 'name',
                    'value' => 'id',
                ]
            ],
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

    /**
     * @param $id
     * @return array|mixed
     * 获取一条
     */
    public function getRowById($id)
    {
        return pdo::getInstance()->fetch("SELECT `id`,`role_id` FROM `{$this->table}` WHERE `id`=?",[$id]);
    }


    /**
     * @return string
     * 级联选择回调函数
     */
    public function getParentAdmin() : string
    {
        $data = Pdo::getInstance()->fetchAll("SELECT `id`,`username` as `name`,`pid`,`role_id` FROM `{$this->table}`",[]);
        $tree = $this->treeRoute($data,0,0);
        return json_encode($tree,JSON_UNESCAPED_UNICODE);
    }

    /**
     * @param array $data
     * @param int $pid
     * @param int $deep
     * @return array
     * 无限极
     */
    public function  treeRoute(array $data,int $pid,int $deep=0) : array
    {
        $tree=[];
        foreach ($data as $row) {
            if($row['pid'] == $pid){
                $row['deep'] = $deep;
                $row['children'] = $this->treeRoute($data,$row['id'],$deep+1);
                $tree[] = $row;
            }
        }
        return $tree;
    }


    /**
     * @param $page
     * @param int $num
     * @return array
     * 重写列表
     */
    public function renderPage($page,$num = 15) : array
    {
        $start = $page ? ($page - 1) * $num : 0;
        $list = Pdo::getInstance()->fetchAll('SELECT `id`,`username`,`create_time`,`role_id`,`login_time` FROM `'.$this->table.'` WHERE `id` > 1 ORDER BY `id` DESC LIMIT ?,'.$num, [$start]);
        $count= Pdo::getInstance()->fetch('SELECT count(*) as `total` FROM `'.$this->table.'` WHERE `id` > 1',[]);
        return [
            'list' => $list,
            'total' => intval($count['total'] )
        ];
    }

    /**
     * @return int
     * 重写post表单提交
     */
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