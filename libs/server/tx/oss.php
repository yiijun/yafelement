<?php
// +----------------------------------------------------------------------
// | Created by [ PhpStorm ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016.
// +----------------------------------------------------------------------
// | Create Time (2021-01-18 13:57)
// +----------------------------------------------------------------------
// | Author: 唐轶俊 <tangyijun@021.com>
// +----------------------------------------------------------------------
namespace libs\server\tx;
use libs\instance;

class Oss extends Instance
{
    public $client;

    public $bucket;

    public $file;

    public $conf = [];

    function __construct()
    {
        $this->conf = \Yaf\Application::app()->getConfig()->tx;
        $region = "ap-shanghai"; //设置一个默认的存储桶地域
        $this->client = new \Qcloud\Cos\Client([
            'region' => $region,
            'schema' => 'https', //协议头部，默认为http
            'credentials' => [
                'secretId' => $this->conf->secret_id,
                'secretKey' => $this->conf->secret_key
            ]
        ]);
        $this->bucket = $this->conf->bucket;
    }


    public function upload($file)
    {
        $this->file = $file;
        try {
            $saveName = date('Ymd') . '/' . md5(microtime(true));
            $extension = strtolower(pathinfo($this->file["name"], PATHINFO_EXTENSION));
            $filename = $saveName . '.' . $extension;
            $file = fopen($this->file['tmp_name'], 'rb');
            if($file) {
                $result = $this->client->Upload(
                    $bucket = $this->bucket,
                    $key = $filename,
                    $body = $file
                );
                if($result) {
                    return [
                        'msg' => '上传成功',
                        'code' => 200,
                        'path' => $this->conf->oss_url . $filename
                    ];
                }
            }
            return [
                'code' => -1,
                'path' => '',
                'msg' => '上传失败'
            ];

        } catch (\Exception $e) {
            return [
                'code' => $e->getCode(),
                'path' => '',
                'msg' => $e->getMessage()
            ];
        }
    }
}