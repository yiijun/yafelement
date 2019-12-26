<?php
/**
 * User: tangyijun
 * Date: 2019-12-16
 * Time: 10:55
 */
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
            'key' => 'role_name',
            'show' => [] //1=>'正常',2=>'禁用'
        ],
        [
            'type' => 'cascader',
            'value' => '',
            'title' => '选择权限',
            'key' => 'pid',
            'show' => [],
            'prop' => [
                'callback' => ['RouteModel','getInstance'], //回调方法拉取级联数据
                'function' => 'getParentRoute',
                'props' => [
                    'levels' => false,        //定义是否显示完整的路径
                    'checkStrictly' => false, //开启之后可以选择任意一级
                    'label' => 'name',
                    'value' => 'id',
                    'multiple' => true
                ]
            ],
            'isTable' => true,
        ],
        [
            'type' => 'textarea',
            'value' => '',
            'title' => '角色描述',
            'key' => 'desc',
            'show' => []
        ],
    ];

    /**
     * @var array
     * 字段验证
     */
    public $validate = [
        'rules' => [
            'role_name' => [
                ["required" => true, "message" => '请输入角色名称', "trigger" => 'blur']
            ],
            'desc' => [
                ["required" => true, "message" => '请输入角色描述', "trigger" => 'blur']
            ]
        ]
    ];

    public $search = ['role_name'];
}