<?php
/**
 * User: tangyijun
 * Date: 2019-12-16
 * Time: 17:10
 */

use \Libs\Db\Pdo;

class AbstractModel extends \Libs\Instance
{
    public $model;

    public function __construct($model)
    {
        $this->model = $model;
    }

    /**
     * @param array $form_extra
     * @return array
     * 加载表单
     */
    public function renderForm(array  $form_extra = ['table' => [],'search' => '','total' => 0]) : array
    {
        $form = [];
        $html = '';
        $table = '';
        foreach ($this->model::getInstance()->form['field'] as $key => $value){
            $form['form'][$value['key']] = $value['value'];
            $form['rules'][$value['key']] = $value['validate'];
            $table .= $this->renderTable($value['key'],$value['title'],$value['show']);
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
        $form['dialog'] = $this->model::getInstance()->form['dialog'];
        foreach ($form_extra as  $key => $value) $form[$key] = $value;

        return [
            'form' => $form,
            'html' => $html,
            'label-width' => $this->model::getInstance()->form['label-width'],
            'table' => $table,
            'search' => $this->renderSearch($this->model::getInstance()->form['search'])
        ];
    }

    /**
     * @param array $search
     * @return string
     * 加载搜索
     */
    public function renderSearch(array $search)
    {
        $searchString = 'table.filter(data => !search';
        foreach ($search as $key => $value){
            $searchString .= ' || data.'.$value.'.toLowerCase().includes(search.toLowerCase())';
        }
        $searchString .= ')';
        return $searchString;
    }

    /**
     * @param string $field
     * @param string $title
     * @param array $show
     * @return string
     * 加载表格
     */
    public function renderTable(string $field = '',string $title = '',array $show = []) : string
    {
        if(empty($show)){
            $string =  '<el-table-column prop="'.$field.'" label="'.$title.'"></el-table-column>';
        }else{
            $string = '<el-table-column   label="'.$title.'"><template scope="scope">';
            foreach ($show as $key => $value){
                $string.= '<span v-if="scope.row.'.$title.'=='.$key.'">'.$value.'</span>';
            }
            $string.='</template></el-table-column>';
        }
        return $string;
    }

    /**
     * @param $page
     * @param int $num
     * @return array
     * 加载分页
     */
    public function renderPage($page,$num = 20)
    {
        $start = $page ? ($page - 1) * $num : 0;
        $table = $this->model::getInstance()->table;
        $list = Pdo::getInstance()->fetchAll('SELECT * FROM `'.$table.'` ORDER BY `id` DESC LIMIT ?,'.$num, [$start]);
        $count= Pdo::getInstance()->fetch('SELECT count(*) as `total` FROM `'.$table.'`',[]);
        return [
            'list' => $list,
            'total' => intval($count['total'] )
        ];
    }

    /**
     * @param array $data
     * @return int
     * 添加数据
     */
    public function renderAdd(array  $data) : int
    {
        try {
            Pdo::getInstance()->pdo->beginTransaction(); //事务开启
            $table = $this->model::getInstance()->table;
            $keys = array_keys($data);
            $result = Pdo::getInstance()->insert('INSERT INTO `'.$table.'`(`'.join('`,`', $keys).'`) VALUES (:'.join(',:', $keys).');', $data);
            return  Pdo::getInstance()->pdo->commit() ? $result['lastInsertId'] : 0; //事务提交
        } catch (\PDOException $e) {
            Pdo::getInstance()->pdo->rollBack(); //回滚事务
        }
        return 0;
    }

    /**
     * @return array
     * 清理表单
     */
    public function renderClearForm() : array
    {
        $form = [];
        foreach ($this->model::getInstance()->form['field'] as $key => $value){
            $form['form'][$value['key']] = $value['value'];
        }
        return $form;
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