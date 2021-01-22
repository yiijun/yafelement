<?php

/**
 * User: tangyijun
 * Date: 2019-12-23
 * Time: 14:26
 */
use libs\instance;
class Building extends Instance
{
    /**
     * @param string $formName
     * @param array $fields
     * @param array $validate
     * @param array $basic
     * @param array $extra 扩展字段需要在视图中定义返回页面再渲染
     * @return string
     * 渲染form
     */
    public function renderForm($formName = 'form', array $fields = [], array $validate = [], array $extra = [], array $basic = ['labelWidth' => '80px', 'dialog' => false, 'table' => [], 'search' => '', 'total' => 0]): string
    {
        $form = [];
        foreach ($fields as $key => $value) {
            $form[$formName][$value['key']] = $value['value'];     //表单字段
        }
        if(!empty($extra)) {
            $basic = $basic + $extra;
        }
        foreach ($validate as $key => $value) {
            $form[$key] = $value;
        }
        foreach ($basic as $key => $value) $form[$key] = $value;

        return json_encode($form, JSON_UNESCAPED_UNICODE);
    }

    public function renderClearForm($fields = [])
    {
        $form = [
            'id' => 0
        ];
        foreach ($fields as $key => $value) {
            $form[$value['key']] = $value['value'];
        }
        return json_encode($form, JSON_UNESCAPED_UNICODE);
    }


    /**
     * @param array $validates
     * @return string
     * 表单验证
     */
    public function renderValidate(array $validates): string
    {
        $rules = [];
        foreach ($validates as $key => $value) {
            $rules[$key] = $value;
        }
        return json_encode($rules);
    }

    public function renderPageHtml()
    {

    }

    /**
     * @param array $fields
     * @param string $pri
     * @return string
     * 渲染table
     */
    public function renderTable(array $fields, string $pri): string
    {
        $tableString = '<el-table-column  prop="' . $pri . '" label="' . $pri . '编号"></el-table-column>';
        foreach ($fields as $key => $value) {
            if(isset($value['isTable']) && $value['isTable'] === true) continue;
            switch ($value['type']) {
                case 'upload':
                    $tableString .= '<el-table-column label="' . $value['title'] . '"><template scope="scope"><img :src="scope.row.' . $value['key'] . '" width="' . $value['width'] . '"></template></el-table-column>';
                    break;
                default:
                    if(empty($value['alias'])) {
                        $tableString .= '<el-table-column  prop="' . $value['key'] . '" label="' . $value['title'] . '"></el-table-column>';
                    } else {
                        //获取回调函数
                        $option = call_user_func_array($value['alias']['callback'],[])->{$value['alias']['function']}();
                        $option = json_decode($option,true);
                        $tableString .= '<el-table-column   label="' . $value['title'] . '"><template scope="scope">';
                        foreach ($option as $idx => $item) {
                            $tableString .= '<el-tag v-if="scope.row.' . $value['key'] . '==' . $item['id'] . '">' . $item['name'] . '</el-tag>';
                        }
                        $tableString .= '</template></el-table-column>';
                    }
                    break;
            }

        }
        $tableString .= '<el-table-column prop="create_time" label="时间"></el-table-column>';
        return $tableString;
    }

    /**
     * @return string
     * 加载菜单
     */
    public function renderAside(): string
    {
        $data = RouteModel::getInstance()->getAll();
        $menus = RouteModel::getInstance()->treeRoute($data, 0, 0);
        $aside = $this->treeAsideData($menus);
        return $aside;
    }

    /**
     * @param string $controller
     * @param string $action
     * @return int
     * 获取当前选中菜单
     */
    public function renderCurrentAside(string $controller, string $action): int
    {
        return RouteModel::getInstance()->getRowByRoute('/' . strtolower($controller) . '/' . strtolower($action))['id'] ?? 0;
    }


    /**
     * @param string $controller
     * @param string $action
     * @return string
     */
    public function renderBreadcrumb(string $controller, string $action): string
    {
        $route = '/' . strtolower($controller) . '/' . strtolower($action);
        $row = RouteModel::getInstance()->getRowByRoute($route);
        $parents = function($pid) use (&$parents) {
            static $data = [];
            if($pid != 0) $row = RouteModel::getInstance()->getRowById($pid);
            if(!empty($row)) {
                $data[] = $row;
                $parents($row['pid']);
            }
            return $data;
        };
        $data = isset($row['pid']) ? $parents($row['pid']) : [];
        array_unshift($data, $row);
        $breadcrumb = '';
        foreach (array_reverse($data) as $key => $value) {
            $breadcrumb .= isset($value['route']) ? ' <el-breadcrumb-item><a href="' . $value['route'] . '">' . $value['name'] . '</a></el-breadcrumb-item>' : '';
        }
        return $breadcrumb;
    }

    /**
     * @param array $data
     * @return string
     * 递归菜单
     */
    private function treeAsideData(array $data)
    {
        $html = '';
        if(is_array($data)) {
            foreach ($data as $row) {
                if(empty($row['children'])) {
                    $html .= '<a href="' . $row['route'] . '"><el-menu-item index="' . $row['id'] . '"><template slot="title"><i class="' . $row['icon'] . '"></i><span>' . $row['name'] . '</span></template></el-menu-item></a>';
                } else {
                    $html .= '<el-submenu index="' . $row['id'] . '"><template slot="title"><i class="' . $row['icon'] . '"></i> <span>' . $row['name'] . '</span></template>';
                    $html .= $this->treeAsideData($row['children']);
                    $html .= '</el-submenu>';
                }
            }
        }
        return $html;
    }

    /**
     * @param array $search
     * @return string
     * 渲染搜索
     */
    public function renderSearch(array $search): string
    {
        $searchString = 'table.filter(data => !search';
        foreach ($search as $key => $value) {
            $searchString .= ' || data.' . $value . '.toLowerCase().includes(search.toLowerCase())';
        }
        $searchString .= ')';
        return $searchString;
    }

    /**
     * @param array $button
     * @return string
     * 获取表格操作事件
     */
    public function renderTableButton(array $button = ['edit' => ['func' => 'saveForm', 'txt' => 'Edit', 'type' => 'primary'], 'del' => ['func' => 'deleteForm', 'txt' => 'Delete', 'type' => 'danger']]): string
    {
        $html = '';
        foreach ($button as $key => $value) {
            $html .= '<el-button type="' . $value['type'] . '" size="mini" @click="' . $value['func'] . '(scope.$index, scope.row)">' . $value['txt'] . '</el-button>';
        }
        return $html;
    }

    /**
     * @param array $fields
     * @param string $controller
     * @param string $name
     * @return string
     */
    public function renderHtml(array $fields, string $controller = '', string $name = 'form'): string
    {
        $html = '';
        foreach ($fields as $key => $value) {
            switch ($value['type']) {
                case 'text':
                    $html .= $this->setInputText($value, $name);
                    break;
                case 'textarea':
                    $html .= $this->setInputTextArea($value, $name);
                    break;
                case 'cascader':
                    $html .= $this->setCascader($value, $name);
                    break;
                case 'upload':
                    $html .= $this->setUpload($value, $name, $controller);
                    break;
                case 'number':
                    $html .= $this->setInputNumber($value, $name);
                    break;
                case 'select':
                    $html .= $this->setSelect($value, $name);
                    break;
                case 'radio':
                    $html .= $this->setRadio($value, $name);
                    break;
                case 'editor':
                    $html .= $this->setEditor($value,$name);
                default:
                    break;
            }
        }
        return $html;
    }

    /**
     * @param array $value
     * @param string $name
     * @return string
     * 文本域
     */
    public function setInputText(array $value, string $name): string
    {
        $html = '<el-form-item prop="' . $value['key'] . '" label="' . $value['title'] . '"><el-input v-model="' . $name . '.' . $value['key'] . '"></el-input></el-form-item>';
        return $html;
    }

    /**
     * @param array $value
     * @param string $name
     * @return string
     * 整形输入框
     */
    public function setInputNumber(array $value, string $name): string
    {
        $html = '<el-form-item prop="' . $value['key'] . '" label="' . $value['title'] . '"><el-input-number v-model="' . $name . '.' . $value['key'] . '"></el-input-number></el-form-item>';
        return $html;
    }


    /**
     * @param array $value
     * @param string $name
     * @return string
     * 文本域
     */
    public function setInputTextArea(array $value, string $name): string
    {
        $html = '<el-form-item  prop="' . $value['key'] . '" label="' . $value['title'] . '"><el-input type="textarea" :rows="4" v-model="' . $name . '.' . $value['key'] . '"></el-input></el-form-item>';
        return $html;
    }


    /**
     * @param $value
     * @param $name
     * @return string
     * 级联
     */
    public function setCascader($value, $name): string
    {
        $html = '<el-form-item label="' . $value['title'] . '"><el-cascader :props=' . json_encode($value['prop']['props']) . ' v-model="' . $name . '.' . $value['key'] . '" placeholder="输入关键字搜索" :options=' . call_user_func_array($value['prop']['callback'], [])->{$value['prop']['function']}() . ' filterable></el-cascader></el-form-item>';
        return $html;
    }

    /**
     * @param $value
     * @param $name
     * @return string
     * select 选择器
     */
    public function setSelect($value, $name): string
    {
        $html = '<el-form-item label="' . $value['title'] . '"><el-select v-model="' . $name . '.' . $value['key'] . '" placeholder="请选择' . $value['title'] . '"><el-option v-for=\'(item,index) in '.call_user_func_array($value['prop']['callback'],[])->{$value['prop']['function']}().'\' :label="item.name" :value="item.id"></el-option></el-select></el-form-item>';
        return $html;
    }

    /**
     * @param $value
     * @param $name
     * @param $controller
     * @return string
     * 上传
     */
    public function setUpload($value, $name, $controller): string
    {
        $oss_onoff = \Yaf\Application::app()->getConfig()->application->oss_onoff;

        //如果开启oss,文件将自动上传到oss，否则上传到本地
        if($oss_onoff == true){
            $html = '<el-form-item label="' . $value['title'] . '"><el-upload class="avatar-uploader" action="/upload/post/field/' . $value['key'] . '/name/' . $name . '" :show-file-list="false":on-success="handleSuccess"><img v-if="' . $name . '.' . $value['key'] . '" :src="' . $name . '.' . $value['key'] . '" class="avatar"><i v-else class="el-icon-plus avatar-uploader-icon"></i></el-upload></el-form-item>';
        }else{
            $html = '<el-form-item label="' . $value['title'] . '"><el-upload class="avatar-uploader" action="/upload/form/field/' . $value['key'] . '/name/' . $name . '" :show-file-list="false":on-success="handleSuccess"><img v-if="' . $name . '.' . $value['key'] . '" :src="' . $name . '.' . $value['key'] . '" class="avatar"><i v-else class="el-icon-plus avatar-uploader-icon"></i></el-upload></el-form-item>';
        }

        return $html;
    }

    /**
     * @param $value
     * @param $name
     * @return string
     * 单选
     */
    public function setRadio($value,$name) :string
    {
        $html = '<el-form-item label="'.$value['title'].'"><el-radio-group v-model="' . $name . '.' . $value['key'] . '"><el-radio v-for=\'(item,index) in '.call_user_func_array($value['prop']['callback'],[])->{$value['prop']['function']}().'\' :label="item.id.toString()">{{item.name}}</el-radio></el-radio-group></el-form-item>';
        return $html;
    }

    /**
     * @param $value
     * @param $name
     * @return string
     * 设置markdown 编辑器
     */
    public function setEditor($value,$name)
    {
        $html = '<el-form-item name="editor_content" prop="' . $value['key'] . '" label="' . $value['title'] . '"><div id="editor_content"><textarea v-model="' . $name . '.' . $value['key'] . '" style="display:none;"></textarea></div></el-form-item>';
        return $html;
    }
}