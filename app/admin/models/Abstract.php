<?php
/**
 * User: tangyijun
 * Date: 2019-12-16
 * Time: 17:10
 */

class AbstractModel extends \Libs\Instance
{
    public function renderForm(string  $model) : array
    {
        $form = [];
        $html = '';
        foreach ($model::getInstance()->form['field'] as $key => $value){
            $form['form'][$value['key']] = $value['value'];
            $form['rules'][$value['key']] = $value['validate'];
            switch ($value['type']){
                case 'text':
                    $html .= $this->setInputText($value['title'],$value['key']);
                    break;
                case 'textarea':
                    $html .= $this->setInputTextArea($value['title'],$value['key']);
                    break;
                default:
                    break;
            }
        }
        $form['dialog'] = $model::getInstance()->form['dialog'];
        return [
            'form' => $form,
            'html' => $html,
            'label-width' => $model::getInstance()->form['label-width']
        ];
    }


    /**
     * @param $label
     * @param $key
     * @return string
     * 设置单行文本
     */
    public function setInputText(string $label,string  $key) : string
    {
        $html = '<el-form-item prop="'.$key.'" label="'.$label.'"><el-input v-model="form.'.$key.'"></el-input></el-form-item>';
        return $html;
    }

    /**
     * @param $label
     * @param $key
     * @return string
     * 设置多行文本
     */
    public function setInputTextArea(string  $label, string $key) : string
    {
        $html = '<el-form-item prop="'.$key.'" label="'.$label.'"><el-input type="textarea" v-model="form.'.$key.'"></el-input></el-form-item>';
        return $html;
    }
}