# tp5redis
tp5下的Redis操作类,对原Redis的二次封装,支持编辑器识别

## 安装
> composer require "xiucaiwu/tp5redis:1.0.*"

## 配置
tp5.0
```
config.php
'redis'                  => [
	'host' => '127.0.0.1', // redis主机
	'port' => '', // redis端口
	'password' => '', // 密码
	'select' => 0, // 操作库
	'expire' => 0, // 有效期(秒)
	'timeout' => 0, // 超时时间(秒)
	'persistent' => true, // 是否长连接
	'prefix' => '', //前缀
]
```

tp5.1
```
config/redis.php
return [
	'host' => '127.0.0.1', // redis主机
	'port' => '', // redis端口
	'password' => '', // 密码
	'select' => 0, // 操作库
	'expire' => 0, // 有效期(秒)
	'timeout' => 0, // 超时时间(秒)
	'persistent' => true, // 是否长连接
	'prefix' => '', //前缀
];
```

## 例子
```
<?php
	namespace app\index\controller;
	use tp5redis\Redis;

	class index
	{
		public function test()
		{
			  Redis::set('abc',111);
			  $res = Redis::get('abc');
			  var_dump($res);  //输出111代表成功
		}
	｝
```
