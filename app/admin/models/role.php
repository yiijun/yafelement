<?php
/**
 * User: tangyijun
 * Date: 2019-12-25
 * Time: 14:03
 */
use \libs\db\pdo;
class RoleModel extends AbstractModel
{
    public $table = 'yaf_role';
    /**
     * @var array
     * 模型字段
     */
    public $fields = [
        [
            'type' => 'text',
            'value' => '',
            'title' => '角色名称',
            'key' => 'name',
        ],
        [
            'type' => 'textarea',
            'value' => '',
            'title' => '角色说明',
            'key' => 'desc',
        ],
        [
            'type' => 'cascader',
            'value' => [],
            'title' => '选择权限',
            'key' => 'routes',
            'prop' => [
                'callback' => ['RouteModel','getInstance'], //回调方法拉取级联数据
                'function' => 'getParentRoute',
                'props' => [
                    'label' => 'name',
                    'value' => 'id',
                    'multiple' => true,
                    'checkStrictly' => true
                ]
            ],
            'isTable' => true,
        ],
    ];

    /**
     * @var array
     * 字段验证
     */
    public $validate = [
        'rules' => [
            'name' => [
                ["required" => true, "message" => '请输入菜单名称', "trigger" => 'blur']
            ],
        ]
    ];

    public $search = ['name'];

    public function getRoleAll()
    {
        $data = Pdo::getInstance()->fetchAll("SELECT `id`,`name` FROM `{$this->table}`",[]);
        return json_encode($data,JSON_UNESCAPED_UNICODE);
    }

    public function getRoleByUid($role_id)
    {
        $data = Pdo::getInstance()->fetch("SELECT `id`,`name`,`selected` FROM `{$this->table}` WHERE  `id` = ?",[$role_id]);
        return $data;
    }

    public function renderPost(): int
    {
        try {
            $this->field = filter_input_array(INPUT_POST);
            $routes = [];
            foreach ($this->field['routes'] as $key => $value){
                foreach ($value as $k => $v){
                    $routes[] = $v;
                }
            }
            $this->field['selected'] = implode(',',array_unique($routes)); //角色查询
            $this->field['routes'] = json_encode($this->field['routes'],JSON_UNESCAPED_UNICODE); //ui选中
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
}