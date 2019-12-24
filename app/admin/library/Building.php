<?php
/**
 * User: tangyijun
 * Date: 2019-12-23
 * Time: 14:26
 */
class Building extends \Libs\Instance
{

    /**
     * @param string $formName
     * @param array $fields
     * @param array $validate
     * @param array $basic
     * @param array $extra
     * @return string
     * 渲染form
     */
    public function renderForm($formName = 'form',array $fields = [],array $validate = [],array $extra = [],array $basic = ['labelWidth' => '80px','dialog' => false,'table' => [],'search' => '','total' => 0]) :string
    {
        $form = [];
        foreach ($fields as $key => $value){
            $form[$formName][$value['key']] = $value['value'];     //表单字段
        }
        if(!empty($extra)){
            $basic = array_merge($basic,$extra);
        }
        foreach ($validate as $key => $value){
            $form[$key] = $value;
        }
        foreach ($basic as  $key => $value) $form[$key] = $value;

        return json_encode($form,JSON_UNESCAPED_UNICODE);
    }


    /**
     * @param array $validates
     * @return string
     * 表单验证
     */
    public function renderValidate(array $validates) : string
    {
        $rules = [];
        foreach ($validates as $key => $value){
            $rules[$key] = $value;
        }
        return json_encode($rules);
    }

    /**
     * @param array $fields
     * @return string
     * 渲染table
     */
    public function renderTable(array $fields) : string
    {
        $tableString = '';
        foreach ($fields as $key => $value){
            if(empty($value['alias'])){
                $tableString .= '<el-table-column prop="'.$value['key'].'" label="'.$value['title'].'"></el-table-column>';
            }else{
                $tableString .= '<el-table-column   label="'.$value['title'].'"><template scope="scope">';
                foreach ($value['alias'] as $idx => $item){
                    $tableString .= '<span v-if="scope.row.'.$value['key'].'=='.$idx.'">'.$item.'</span>';
                }
                $tableString .= '</template></el-table-column>';
            }
        }
        return $tableString;
    }

    /**
     * @param array $fields
     * @return string
     */
    public function renderHtml(array $fields) : string
    {
        $html = '';
        foreach ($fields as $key => $value) {
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
        return $html;
    }

    public function renderSubmit(string $name,string $url,string $form = 'form') : string
    {
        $string = $name.':function(formName) {
                this.$refs[formName].validate((valid) => {
                    if (valid) {
                        $.post("'.$url.'", vm.'.$form.').done(function(response) {
                            if(200 == response.code){
                                vm.$message.success(response.msg);
                                vm.init();
                                vm.dialog = false
                            }else{
                                vm.$message.error(response.msg);
                            }
                        })
                    } else {
                        return false;
                    }
                });
            },';
        return $string;
    }

    /**
     * @param array $search
     * @return string
     * 渲染搜索
     */
    public function renderSearch(array $search) :string
    {
        $searchString = 'table.filter(data => !search';
        foreach ($search as $key => $value){
            $searchString .= ' || data.'.$value.'.toLowerCase().includes(search.toLowerCase())';
        }
        $searchString .= ')';
        return $searchString;
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