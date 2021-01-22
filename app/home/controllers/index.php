<?php
// +----------------------------------------------------------------------
// | Created by [ PhpStorm ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016.
// +----------------------------------------------------------------------
// | Create Time (2020-07-23 15:24)
// +----------------------------------------------------------------------
// | Author: 唐轶俊 <tangyijun@021.com>
// +----------------------------------------------------------------------
class IndexController extends \Yaf\Controller_Abstract{
    public function indexAction()
    {
        echo APP_PATH;
        $arr = [
            [
                'name' => '子夜华',
                'bqp' => 149,
            ],
            [
                'name' => '老朱',
                'bqp' => 337,
            ],
            [
                'name' => '碎梦',
                'bqp' => 140,
            ],
            [
                'name' => '剑帝',
                'bqp' => 240,
            ],
            [
                'name' => '心手',
                'bqp' => 145,
            ],
            [
                'name' => '若水',
                'bqp' => 240,
            ],
            [
                'name' => '蝶|对拜画眉',
                'bqp' => 210,
            ],
            [
                'name' => '对方正在输出',
                'bqp' => 134,
            ],
            [
                'name' => '无影司机',
                'bqp' => 129,
            ],
            [
                'name' => '可恨苍进已成空',
                'bqp' => 77,
            ],
            [
                'name' => '叶新',
                'bqp' => 115,
            ],
            [
                'name' => '夜千殇',
                'bqp' => 103,
            ],
            [
                'name' => '青山独归远',
                'bqp' => 129,
            ],
            [
                'name' => '律风提莫点江山',
                'bqp' => 60,
            ],
            [
                'name' => '被酒莫惊春睡重',
                'bqp' => 56,
            ],
            [
                'name' => '屠夫状元',
                'bqp' => 123,
            ],
            [
                'name' => '蔬星、孤狼',
                'bqp' => 32,
            ],
            [
                'name' => '费希丁',
                'bqp' => 166,
            ],
            [
                'name' => '雨梦娴',
                'bqp' => 125,
            ],
            [
                'name' => '星繁、',
                'bqp' => 88,
            ],
            [
                'name' => '有刀一寸斩仙客',
                'bqp' => 145,
            ],
            [
                'name' => '神煞',
                'bqp' => 60,
            ],
            [
                'name' => '老饕',
                'bqp' => 160,
            ],
            [
                'name' => '幻龙',
                'bqp' => 143,
            ],
            [
                'name' => '灵魂',
                'bqp' => 78,
            ],
            [
                'name' => '花落莫相离',
                'bqp' => 164,
            ],
            [
                'name' => '洛神洛',
                'bqp' => 380,
            ],
            [
                'name' => '但卿眉上风止',
                'bqp' => 114,
            ],
            [
                'name' => '花心、小爷',
                'bqp' => 130,
            ],
            [
                'name' => '昔我往矣杨柳依',
                'bqp' => 43,
            ],
            [
                'name' => '赌书消得泼香茶',
                'bqp' => 75,
            ],
            [
                'name' => '灭绝师太周芷若',
                'bqp' => 140,
            ],
        ];

        $person_num = count($arr); //参赛人数

        $group = ceil($person_num / 2); //分组

        //生成参赛id

        $ids = [];

        foreach ($arr as $key => $value){
            $ids[] = $key;
        }

        //将数组打乱
        shuffle($ids);

        //进行分组
        $groups = [];
        for($i = 0;$i< $group;$i++){
            $groups[] = [
                $ids[$i*2],
                $ids[($i*2) + 1],
            ];
        }

        $result = [];
        $str = '<h3>洛神殿第四届比武大会</h3>';
        $str .= '<p>本届参赛人数：'.$person_num.'人</p>';
        $str .= '<p>本届参赛分组：'.$group.'组</p>';
        foreach ($groups as $key => $value) {
            $str .= 'NO.'.($key +1).' :'.$arr[$value[0]]['name'] .'（兵器谱：'.$arr[$value[0]]['bqp'].'星） VS '. $arr[$value[1]]['name'].'（兵器谱:'.$arr[$value[1]]['bqp'].'星）<br>';
            if(($arr[$value[0]]['bqp'] - $arr[$value[1]]['bqp']) >= 60) {
                $str .= $arr[$value[0]]['name'] .' 需要交出一颗爆气丹';
            } elseif(($arr[$value[1]]['bqp'] - $arr[$value[0]]['bqp']) >= 60) {
                $str .= $arr[$value[1]]['name'] .' 需要交出一颗爆气丹';
            }else{
                $str .= '兵器谱差距小于60，双方无需交爆气丹';
            }
            $str .= '<br>-----------------------------------------------------<br>';
        }
        echo  $str;
        return false;
    }

    public function inputAction()
    {

    }
}
