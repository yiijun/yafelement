# yafelement
在yaf的基础上做了一点优化，支持多域名、多平台、多环境（生产、测试、开发）独立配置、自动加载、常驻内存、分布式代码、已构建核心类库、一个非常灵活的api架构。

**解决问题**

- 解决了使用yaf多模块无法继承BaseController的问题

- 解决了yaf使用多域名配置只能使用多模块、并且只能通过转发设置模块的方法的问题

- 灵活的配置规则

- 核心类库分离使用多模块所有核心类库都放在library文件混乱

- 组件共享，多域名组件共享

改进一下yaf,除了具备yaf的功能之外，更加拥有更加强大的能力，可以用于大型项目的基础架构；

**安装**

安装yaf扩展即可

**域名配置(host 文件)**

> 127.0.0.1      admin.yaf-element.com

> 127.0.0.1      home.yaf-element.com


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

