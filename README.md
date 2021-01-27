## Yaf-element

基于Yaf element-ui vue，带无限极分类、多级分销、权限管理后台系统，element组件集成，模块自动生成，并支持多域名、多平台、多环境（生产、测试、开发）独立配置、自动加载、常驻内存、分布式代码、已构建核心类库、一个非常灵活的api架构。

## 解决问题

- 解决了使用yaf多模块无法继承BaseController的问题

- 解决了yaf使用多域名配置只能使用多模块、并且只能通过转发设置模块的方法的问题

- 灵活的配置规则

- 核心类库分离使用多模块所有核心类库都放在library文件混乱

- 组件共享，多域名组件共享

- 项目解耦，可将项目模块化，业务单独部署

改进一下yaf,除了具备yaf的功能之外，更加拥有更加强大的能力，可以用于大型项目的基础架构；

## 后台

element ui + vue 


## 功能

- 设置模型字段，自动生成表单、表格、渲染视图、表单验证等（增删改查操作），简而言之，项目中一个模块只需要设置模型，增删改查将自动生成，支持自定义和重写，总之如果你要搭建一个后台管理系统，那么它将是你不错得选择；

- 图表功能、柱形图、折线图...

- 支持本地上传、oss上传，只要开启上传将会自动上传到oss

- markdown

- 网站配置

- 菜单设置（支持无限极菜单，自动渲染，后台只需要添加即可）

- 管理员设置

- 权限设置

- 多级分销


## 组件

- composer

- pdo

- file

- api

- http curl

- session

- log(日志处理)

- server
    
    - 腾讯oss

- redis

- apcu
    
- common



## 安装

- 安装yaf扩展

    - 开启：use_namespace
    - 开启：lowcase_path
    - 开启：use_spl_autoload
    - 设置：environ

php.ini 增加一下配置

```$xslt
[Yaf]
yaf.use_namespace = 1
yaf.environ  = "develop"
yaf.lowcase_path =1
yaf.use_spl_autoload=1
```

- git clone https://gitee.com/phpbloger/yafelement.git

- 配置数据库:conf//开发环境//对应模块.ini


```$xslt
[db]
db.default.host = "127.0.0.1"
db.default.port = 3306
db.default.dbname = "yafelement"
db.default.charset = "utf8"
db.default.username = "root"
db.default.passwd = "root"

```
- 配置nginx 或者apache,配置在下方

- 运行即可


##注意
如果实在wamp环境下只需要开启命名空间即可。

如果实在lnmp环境上运行该框架必须确保一下配置开启：

```
#确保命名空间开启
yaf.use_namespace=1
#确保文件以小写开头加载
yaf.lowcase_path=1
#确保可以使用其他文件加载方式，我们采用psr-4加载核心类库
yaf.use_spl_autoload=1
```

##host

> 127.0.0.1      admin.yaf-element.com

> 127.0.0.1      home.yaf-element.com


##虚拟主机配置

> apache 

后台

```
<VirtualHost *:80>
    DocumentRoot "D:\phpStudy\WWW\yafelement\public\admin"
    ServerName admin.yaf-element.com
    ServerAlias 
  <Directory "D:\phpStudy\WWW\yafelement\public\admin">
      Options FollowSymLinks ExecCGI
      AllowOverride All
      Order allow,deny
      Allow from all
      Require all granted
  </Directory>
</VirtualHost>
```

home

```
<VirtualHost *:80>
    DocumentRoot "D:\phpStudy\WWW\yafelement\public\admin"
    ServerName home.yaf-element.com
    ServerAlias 
  <Directory "D:\phpStudy\WWW\yafelement\public\admin">
      Options FollowSymLinks ExecCGI
      AllowOverride All
      Order allow,deny
      Allow from all
      Require all granted
  </Directory>
</VirtualHost>
```

>nginx

后台

```php
server
{
    listen 80;
	listen 443 ssl http2;
    server_name admin.yaf-element.com;
    index index.php index.html index.htm default.php default.htm default.html;
    root /www/wwwroot/admin.yaf-element.com/public/home;
    
    #SSL-START SSL相关配置，请勿删除或修改下一行带注释的404规则
    #error_page 404/404.html;
    ssl_certificate    /www/server/panel/vhost/cert/admin.yaf-element.com/fullchain.pem;
    ssl_certificate_key    /www/server/panel/vhost/cert/admin.yaf-element.com/privkey.pem;
    ssl_protocols TLSv1.1 TLSv1.2 TLSv1.3;
    ssl_ciphers EECDH+CHACHA20:EECDH+CHACHA20-draft:EECDH+AES128:RSA+AES128:EECDH+AES256:RSA+AES256:EECDH+3DES:RSA+3DES:!MD5;
    ssl_prefer_server_ciphers on;
    ssl_session_cache shared:SSL:10m;
    ssl_session_timeout 10m;
    add_header Strict-Transport-Security "max-age=31536000";
    error_page 497  https://$host$request_uri;
    #SSL-END
	
    
    #ERROR-PAGE-START  错误页配置，可以注释、删除或修改
    #error_page 404 /404.html;
    #error_page 502 /502.html;
    #ERROR-PAGE-END
    
    #PHP-INFO-START  PHP引用配置，可以注释或修改
    include enable-php-74.conf;
    #PHP-INFO-END
    
    #REWRITE-START URL重写规则引用,修改后将导致面板设置的伪静态规则失效
    include /www/server/panel/vhost/rewrite/admin.yaf-element.com.conf;
    #REWRITE-END
    
    #禁止访问的文件或目录
    location ~ ^/(\.user.ini|\.htaccess|\.git|\.svn|\.project|LICENSE|README.md)
    {
        return 404;
    }
    
    #一键申请SSL证书验证目录相关设置
    location ~ \.well-known{
        allow all;
    }
    
    location ~ .*\.(gif|jpg|jpeg|png|bmp|swf)$
    {
        expires      30d;
        error_log /dev/null;
        access_log off;
    }
    
    location ~ .*\.(js|css)?$
    {
        expires      12h;
        error_log /dev/null;
        access_log off; 
    }
    access_log  /dev/null;
    error_log  /www/wwwlogs/admin.yaf-element.com.error.log;
}

```

home

```
server
{
    listen 80;
	listen 443 ssl http2;
    server_name home.yaf-element.com;
    index index.php index.html index.htm default.php default.htm default.html;
    root /www/wwwroot/home.yaf-element.com/public/home;
    
    #SSL-START SSL相关配置，请勿删除或修改下一行带注释的404规则
    #error_page 404/404.html;
    ssl_certificate    /www/server/panel/vhost/cert/home.yaf-element.com/fullchain.pem;
    ssl_certificate_key    /www/server/panel/vhost/cert/home.yaf-element.com/privkey.pem;
    ssl_protocols TLSv1.1 TLSv1.2 TLSv1.3;
    ssl_ciphers EECDH+CHACHA20:EECDH+CHACHA20-draft:EECDH+AES128:RSA+AES128:EECDH+AES256:RSA+AES256:EECDH+3DES:RSA+3DES:!MD5;
    ssl_prefer_server_ciphers on;
    ssl_session_cache shared:SSL:10m;
    ssl_session_timeout 10m;
    add_header Strict-Transport-Security "max-age=31536000";
    error_page 497  https://$host$request_uri;

    #SSL-END

    
    #ERROR-PAGE-START  错误页配置，可以注释、删除或修改
    #error_page 404 /404.html;
    #error_page 502 /502.html;
    #ERROR-PAGE-END
    
    #PHP-INFO-START  PHP引用配置，可以注释或修改
    include enable-php-74.conf;
    #PHP-INFO-END
    
    #REWRITE-START URL重写规则引用,修改后将导致面板设置的伪静态规则失效
    include /www/server/panel/vhost/rewrite/home.yaf-element.com.cn.conf;
    #REWRITE-END
    
    #禁止访问的文件或目录
    location ~ ^/(\.user.ini|\.htaccess|\.git|\.svn|\.project|LICENSE|README.md)
    {
        return 404;
    }
    
    #一键申请SSL证书验证目录相关设置
    location ~ \.well-known{
        allow all;
    }
    
    location ~ .*\.(gif|jpg|jpeg|png|bmp|swf)$
    {
        expires      30d;
        error_log /dev/null;
        access_log off;
    }
    
    location ~ .*\.(js|css)?$
    {
        expires      12h;
        error_log /dev/null;
        access_log off; 
    }
    access_log  /dev/null;
    error_log  /www/wwwlogs/home.yaf-element.com.error.log;
}

```

以上配置根据自身情况修改即可；


该版本已经集成了markdown并且组件化，增加腾讯云上传

