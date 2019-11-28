# yafelement
支持多域名、多平台、多环境（生产、测试、开发）独立配置、自动加载、常驻内存、分布式代码、已构建核心类库、一个非常灵活的api架构

**安装**
安装yaf扩展即可

**域名配置(host 文件)**
127.0.0.1      admin.yaf-element.com
127.0.0.1      home.yaf-element.com


**虚拟主机配置**

> apache 

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

