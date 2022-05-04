程序说明：

***只是程序源码，暂无自动化引导安装，一点不懂TP5.1框架的慎用。

1、开发环境：PHP(7.4)+MYSQL

2、使用ThinkPHP5.1框架，后台模板使用x-admin，大部分内容都可以通过后台操控，少数页面内容直接写在代码内。

配置写法不同，为避免未知错误，建议百度找一个适合自己的。

Nginxα参考

if (!-e $request_filename) {

		rewrite /adms.php(.*)$ /adms.php?s=/$1 last; 
		
		rewrite /index.php(.*)$ /index.php?s=/$1 last; 
		
	rewrite  ^(.*)$  /index.php?s=/$1  last;
	
	break;
	
}

配置步骤：

1、建议使用phpstudy集成开发环境，安装方便，配置简单。

2、手动新建库，并导入。

3、根目录指到当前文件夹。

4、修改配置文件库链接信息。

5、前台访问地址：http://localhost/index.php

6、后台访问地址：http://localhost/adms.php

7、初始账号：admin，123456

