<?php
/**
 * User: tangyijun
 * Date: 2019-12-25
 * Time: 14:03
 */
use  \Libs\Db\Pdo;
class RouteModel extends AbstractModel
{
    public $table = 'yaf_route';

    /**
     * @var array
     * 模型字段
     */
    public $fields = [
        [
            'type' => 'text',
            'value' => '',
            'title' => '菜单名称',
            'key' => 'name',
            'show' => []
        ],
        [
            'type' => 'cascader',
            'value' => '',
            'title' => '上级菜单',
            'key' => 'pid',
            'show' => [],
            'prop' => [
                'callback' => ['RouteModel','getInstance'], //回调方法拉取级联数据
                'function' => 'getParentRoute',
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
            'type' => 'text',
            'value' => '',
            'title' => 'icon',
            'key' => 'icon',
            'show' => []
        ],
        [
            'type' => 'number',
            'value' => 0,
            'title' => '排序值',
            'key' => 'sorts',
            'show' => []
        ],
        [
            'type' => 'text',
            'value' => '',
            'title' => '路由',
            'key' => 'route',
            'show' => []
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

    public $reload = true;//是否重载页面

    /**
     * @return string
     * 级联选择回调函数
     */
    public function getParentRoute() : string
    {
        $data = Pdo::getInstance()->fetchAll("SELECT `id`,`name`,`pid` FROM `{$this->table}`",[]);
        $tree = $this->treeRoute($data,0,0);
        return json_encode($tree,JSON_UNESCAPED_UNICODE);
    }

    /**
     * @param array $data
     * @param int $pid
     * @param int $deep
     * @return array
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
     * 重写render
     */
    public function renderPage($page,$num = 15) : array
    {
        $start = $page ? ($page - 1) * $num : 0;
        $list = Pdo::getInstance()->fetchAll('SELECT * FROM `'.$this->table.'` ORDER BY `id` DESC LIMIT ?,'.$num, [$start]); //递归
        $tree = $this->treeRoute($list,0,0);
        $count= Pdo::getInstance()->fetch('SELECT count(*) as `total` FROM `'.$this->table.'`',[]);
        return [
            'list' => $tree,
            'total' => intval($count['total'] )
        ];
    }

    public function getRowByRoute(string $route) :array
    {
        return Pdo::getInstance()->fetch('SELECT `id` FROM `'.$this->table.'` WHERE `route` = ?',[$route]);
    }

    public function getAll()
    {
        return Pdo::getInstance()->fetchAll('SELECT * FROM `'.$this->table.'`');
    }
}