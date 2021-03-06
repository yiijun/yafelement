<?php
/**
 * User: tangyijun
 * Date: 2019-12-27
 * Time: 10:57
 */
class ConfigModel extends AbstractModel
{
    public $table = 'yaf_config';

    public $fields = [
        [
            'type' => 'text',
            'value' => '',
            'title' => '标题',
            'key' => 'title',
            'show' => []
        ],
        [
            'type' => 'upload',
            'value' => '',
            'title' => 'logo',
            'key' => 'log',
            'show' => []
        ],
        [
            'type' => 'text',
            'value' => '',
            'title' => '关键字',
            'key' => 'keyword',
            'show' => []
        ],
        [
            'type' => 'textarea',
            'value' => '',
            'title' => '描述',
            'key' => 'desc',
            'show' => []
        ],
        [
            'type' => 'text',
            'value' => '',
            'title' => '系统名称',
            'key' => 'sys_title',
            'show' => []
        ],
    ];

    public function add(array $data = [])
    {
        return \libs\db\pdo::getInstance()->insert("INSERT INTO `{$this->table}` (`key`,`value`) VALUES (?,?) ON DUPLICATE KEY UPDATE `value`= ?",$data);
    }

    public function read()
    {
        return \libs\db\pdo::getInstance()->fetchAll("SELECT * FROM `{$this->table}`",[]);
    }

    public function conf()
    {
        $config = $this->read();
        $res = [];
        foreach ($config as $k => $v){
            $res[$v['key']] = $v['value'];
        }
        return $res;
    }
}