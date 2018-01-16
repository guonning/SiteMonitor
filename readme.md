# 网站监控程序
定时监控URL

## 功能，特性

这是一个全能的，类似监控宝的程序

- 使用自定义的HTTP请求信息来检查HTTP服务器
- 记录响应数据，包括响应体，和响应IP、响应时间、路由trace等
- 自定义报警条件：HTTP状态码、响应内容、响应时间
- 多渠道报警通知：邮件、短信、HTTP请求

## 安装

!!! 如果你在安装过程中遇到任何问题，请不要犹豫，立即提出Issue。

1. 拉取代码，使用 
```bash
git clone https://github.com/shellus/SiteMonitor.git
```

2. 为储存目录增加写入权限
```bash
chmod +w -R SiteMonitor/storage
```

3. 执行composer install, 这一过程可能在1分钟到30分钟之间
```bash
composer install
```

如果提示`composer: command not found`,你可能需要先使用下面的命令安装composer
```bash
php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
php -r "if (hash_file('SHA384', 'composer-setup.php') === '544e09ee996cdf60ece3804abc52599c22b1f40f4323403c44d44fdfdd586475ca9813a858088ffbc1f233e9b180f061') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;"
php composer-setup.php
php -r "unlink('composer-setup.php');"
mv composer.phar /usr/bin/composer
```

4. 配置数据库连接信息
```bash
cp .env.example .env
```
编辑.env文件：修改数据库连接信息，程序不会自动创建数据库，所以请确认数据库存在，你可以使用`CREATE DATABASE site_monitor;`来创建数据库

5. 配置邮件发送设置
编辑.env文件：如果你和我一样使用QQ域名邮箱，那么只需要修改`MAIL_USERNAME`和`MAIL_FROM_ADDRESS`为你的邮箱，`MAIL_PASSWORD`为你的密码即可。
如果使用其他邮箱，你需要配置`MAIL_HOST` `MAIL_PORT` `MAIL_ENCRYPTION` 等选项，其中前两项可以从你邮箱的帮助中找到，

> 注意: tls和ssl是不同的选项

6. 准备数据
```bash
php artisan key:generate
php artisan migrate
php artisan db:seed
``` 

7. 添加cron条目 (注意，修改/path/to/SiteMonitor为你自己的路径)
```bash
* * * * * php /path/to/SiteMonitor/artisan schedule:run
```

8. 添加开机启动，你可以使用`/etc/rc.local`或`Supervisor`来实现这一点
```bash
php /path/to/SiteMonitor/artisan queue:work --queue=monitor
```

9. 最后，配置你的nginx配置文件，将root目录绑定到`/path/to/SiteMonitor/public`吧。
推荐的nginx配置像这个样子
```nginxconf
server {

        listen 80;

        root /path/to/SiteMonitor/artisan;
        index index.html index.php;

        server_name _;

        location / {
                try_files $uri $uri/ /index.php?$args;
        }

        location ~ \.php {
                fastcgi_split_path_info ^(.+\.php)(/.+)$;
                fastcgi_pass unix:/run/php/php7.2-fpm.sock;
                fastcgi_index index.php;
                include fastcgi.conf;
        }

}
```



## 授权协议

目前开发阶段，开源及授权方式未定，未来变更授权不会制约变更前的代码
