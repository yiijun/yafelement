<?php
// +----------------------------------------------------------------------
// | Created by [ PhpStorm ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016.
// +----------------------------------------------------------------------
// | Create Time (2021-01-18 14:16)
// +----------------------------------------------------------------------
// | Author: 唐轶俊 <tangyijun@021.com>
// +----------------------------------------------------------------------
use libs\comm\common;
use libs\server\tx\oss;
use libs\file\upload;
class UploadController extends \Base\BaseController
{

    /**
     * markdown编辑器文件上传
     */
    public function editorAction()
    {
        $file = $_FILES['editormd-image-file'];
        if(empty($file)) {
            return Common::getInstance()->error('上传参数错误');
        }

        $res = Oss::getInstance()->upload($file); //cdn

        $data = [
            'success' => 0,
            'message' => '上传失败',
            'url' => ''
        ];

        if($res['code'] == 200) {
            $data = [
                'success' => 1,
                'url' => $res['path'],
                'message' => 'success',
            ];
        }
        header("content-Type: application/json; charset=utf-8");
        die(json_encode($data,JSON_UNESCAPED_UNICODE));
    }

    /**
     * cdn 编辑器文件上传
     */
    public function postAction()
    {
        if($this->_request->isPost()){
            $get = $this->_request->getParams();
            if(empty($get['field']) || empty($get['name'])) {
                return Common::getInstance()->error('上传参数错误');
            }

            $file = isset($_FILES) ? $_FILES['file'] : []; //获取上传文件

            if(empty($file)){
                return Common::getInstance()->error('缺少上传文件');
            }
            $res = Oss::getInstance()->upload($file); //上传至cdn

            if($res['code'] == 200){
                return Common::getInstance()->success([
                    'path' => "vm.{$get['name']}.{$get['field']} = '{$res['path']}'"
                ]);
            }
            return Common::getInstance()->error('上传文件失败');
        }
    }

    /**
     * 表单文件上传
     */
    public function formAction()
    {
        if($this->_request->isPost()){
            $get = $this->_request->getParams();
            if(empty($get['field']) || empty($get['name'])) {
                return Common::getInstance()->error('上传参数错误');
            }
            $path = Upload::getInstance()->post('upload/',['ext' => ['jpg','png'],'size' => 2000000]);
            return Common::getInstance()->success([
                'path' => "vm.{$get['name']}.{$get['field']} = '{$path}'"
            ]);
        }
        return Common::getInstance()->error(\libs\file\upload::getInstance()->error);
    }
}