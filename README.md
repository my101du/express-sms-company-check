# 功能

* 快递状态查询，可支持顺丰、圆通、中通等快递公司
* 手机短信下发，可用于注册、找回密码等各种需要手机验证码的场景,有效期内处理防止短信炸弹
* 企业核名，检查当前企业名称是否被占用

# 前后端代码，以及具体实现方式详细说明文档

[使用聚合数据API实现“快递查询-短信验证-企业核名”](http://www.itjiaoshou.com/express-sms-company-check-use-api)

# APPKEY 申请

请自行到下面两个网站注册 APPKEY,稍后需要将它们复制到 `Home/Conf/config.php` 中

* http://www.juhe.cn (快递、短信 共两个)
* http://www.yjapi.com/ （企业信息 共一个）

# 短信发送记录数据库（主要用于记录短信验证码下发记录，限制有效期，以及核对用户输入的验证码）

已经导出 sql 文件放在 `/Public/` 目录下，在服务器上导入即可


# 配置文件范例（因信息敏感，代码库里已经删除这个文件，需自行添加）

`Home/Conf/config.php` 内容如下

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
