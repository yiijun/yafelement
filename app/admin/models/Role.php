<?php
/**
 * User: tangyijun
 * Date: 2019-12-16
 * Time: 10:55
 */
class RoleModel extends AbstractModel
{
    public $table = 'yaf_role';

    public $form = [
        'field' => [
            [
                'type' => 'text',
                'validate' => [
                    ["required" => true, "message" => '请输入角色名称', "trigger" => 'blur']
                ],
                'value' => '',
                'title' => '角色名称',
                'key' => 'role_name',
                'show' => [] //1=>'正常',2=>'禁用'
            ],
            [
                'type' => 'textarea',
                'validate' => [
                    ["required" => true, "message" => '请输入角色描述', "trigger" => 'blur']
                ],
                'value' => '',
                'title' => '角色描述',
                'key' => 'desc',
                'show' => []
            ]
        ],
        'dialog' => false,
        'label-width' => '80px',
        'search' => ['role_name'],//用于搜索
    ];
}