# tp5redis
tp5下的Redis操作类,对原Redis的二次封装,支持编辑器识别

## 使用
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