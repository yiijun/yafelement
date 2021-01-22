<?php
/**
 * User: tangyijun
 * Date: 2019-11-28
 * Time: 15:44
 */
namespace libs\file;
use libs\instance;

class Upload extends Instance {
    public $error;
    public $file;
    public $filename;

    private function checkPath($path)
    {
        if (is_dir($path) || mkdir($path, 0755, true)) {
            return true;
        }
        $this->error = '没有文件夹权限';
        return false;
    }

    private function checkSize($size)
    {
        return$this->file["size"] < $size;
    }

    private function checkExt($ext)
    {
        if (is_string($ext)) {
            $ext = explode(',', $ext);
        }
        $extension = strtolower(pathinfo($this->file["name"], PATHINFO_EXTENSION));
        return in_array($extension, $ext);
    }


    private function check($rule = [])
    {
        /* 检查文件大小 */
        if (isset($rule['size']) && !$this->checkSize($rule['size'])) {
            $this->error = '上传文件超出限制';
            return false;
        }

        /* 检查文件后缀 */
        if (isset($rule['ext']) && !$this->checkExt($rule['ext'])) {
            $this->error = '上传后缀不允许';
            return false;
        }
        return true;
    }

    /**
     * @param $path          文件上传的路径
     * @param $key string    文件上传的key
     * @param array $rule    规则['ext' => ['jpg','png'],'size' => 2000]
     * @param bool $replace  是否覆盖重名文件
     * @return bool|string
     * 文件上传
     */
    public function post($path,$rule = [],$key = 'file',$replace = true)
    {
        if (empty($this->file)) {
            $this->file = isset($_FILES) ? $_FILES[$key] : [];
        }
        if (!$this->check($rule)) {
            return false;
        }
        $path = rtrim($path, '/') . '/';
        // 文件保存命名规则
        $saveName  = date('Ymd') . '/' . md5(microtime(true));
        $extension = strtolower(pathinfo($this->file["name"], PATHINFO_EXTENSION));
        $filename = $path . $saveName.'.'.$extension;
        $this->filename = $this->file['tmp_name'];
        // 检测目录
        if (false === $this->checkPath(dirname($filename))) {
            return false;
        }

        if (!$replace && is_file($filename)) {
            $this->error = "has the same filename: {:filename}  -> filename:".$filename;
        }
        if( !move_uploaded_file($this->filename, $filename)){
            $this->error = 'upload write error';
            return false;
        }
        return '/'.$filename;
    }

    public function getError()
    {
        return $this->error;
    }
}