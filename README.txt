# 功能

* 快递状态查询，可支持顺丰、圆通、中通等快递公司
* 手机短信下发，可用于注册、找回密码等各种需要手机验证码的场景,有有效期处理防止机器人刷接口
* 企业核名，检查当前企业名称是否被占用

# 注意

请自行到下面两个网站注册 APPKEY,并修改 `Home/Conf/config.php` 的参数,格式如下

* http://www.juhe.cn (快递、短信)
* http://www.yjapi.com/ （企业信息）

数据库结构已经导出 sql 文件放在 `/Public/` 目录下，在服务器上导入即可

```php
<?php
return array(
	// 快递查询
	'EXPRESS_APP_KEY' => '你的快递 APPKEY',
	'EXPRESS_QUERY_URL' => 'http://v.juhe.cn/exp/index', //快递单号查询
	'EXPRESS_COM_URL' => 'http://v.juhe.cn/exp/com', //快递公司查询

	// 发短信
	'SEND_SMS_KEY' => '你的短信接口 APPKEY',
	'SEND_SMS_URL' => 'http://v.juhe.cn/sms/send',

	// 核名
	'COMPANY_KEY' => '核名 APPKEY',
	'COMPANY_URL' => 'http://eci.yjapi.com/ECIFast/Search',

	//数据库配置信息
	'DB_TYPE'   => 'mysql', // 数据库类型
	'DB_HOST'   => 'localhost', // 服务器地址
	'DB_NAME'   => '你的数据库名', // 数据库名
	'DB_USER'   => '你的数据库用户名', // 用户名
	'DB_PWD'    => '密码', // 密码
	'DB_PORT'   => 3306, // 端口
	'DB_PREFIX' => 'pre_', // 数据库表前缀 
	'DB_CHARSET'=> 'utf8', // 字符集
);
```

# 备注

请注意修改 APPKEY
请注意修改数据库连接的参数
