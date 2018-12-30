<?php
namespace tp5redis;

/**
 * Class Redis
 * @package org
 *
 * ----------------hash表操作命令----------------
 * @method string hGet(string $key,string $field) static 得到hash表中一个字段的值
 * @method bool|int hSet(string $key,string $field,string $value) static 为hash表设定一个字段的值
 * @method bool hExists(string $key,string $field) static 判断hash表中，指定field是不是存在
 * @method bool|int hDel(string $key,string $field) static 删除hash表中指定字段
 * @method int hLen(string $key) static 返回hash表元素个数
 * @method bool hSetNx(string $key,string $field,string $value) static 为hash表设定一个字段的值,如果字段存在，返回false
 * @method bool hMset(string $key,string $value) static 为hash表多个字段设定值
 * @method array hMget(string $key,array $fields) static 取出hash表多个字段的值
 * @method int hIncrBy(string $key,string $field,string $value) static 为hash表值累加，可以负数
 * @method array hKeys(string $key) static 返回所有hash表的所有字段
 * @method array hVals(string $key) static 返回所有hash表的字段值，为一个索引数组
 * @method array hGetAll(string $key) static 返回所有hash表的字段值，为一个关联数组
 *
 * ---------------有序集合操作命令---------------
 * @method int zAdd(string $key,string $order,string $value) static 给当前集合添加一个元素
 * @method mixed zinCry(string $key,string $num,string $value) static 给$value成员的order值，增加$num,可以为负数
 * @method bool zRem(string $key,string $value) static 删除值为value的元素
 * @method array zRange(string $key,int $start,int $end) static 集合以order递增排列后，0表示第一个元素，-1表示最后一个元素
 * @method array zRevRange(string $key,int $start,int $end) static 集合以order递减排列后，0表示第一个元素，-1表示最后一个元素
 * @method array zRangeByScore(string $key,string $start='-inf',string $end="+inf",array $option=array()) static 集合以order递增排列后，返回指定order之间的元素
 * @method array zRevRangeByScore(string $key,string $start='-inf',string $end="+inf",array $option=array()) static 集合以order递减排列后，返回指定order之间的元素
 * @method int zCount(string $key,int $start,int $end) static 返回order值在start end之间的数量
 * @method float zScore(string $key,string $value) static 返回值为value的order值
 * @method int zRank(string $key,string $value) static 返回集合以score递增加排序后，指定成员的排序号，从0开始
 * @method int zRevRank(string $key,string $value) static 返回集合以score递增加排序后，指定成员的排序号，从0开始
 * @method int zRemRangeByScore(string $key,int $start,int $end) static 返回集合以score递增加排序后，指定成员的排序号，从0开始
 * @method int zCard(string $key) static 返回集合元素个数
 *
 * -----------------队列操作命令-----------------
 * @method bool|int rPush(string $key,string $value) static 在队列尾部插入一个元素
 * @method int rPushx(string $key,string $value) static 在队列尾部插入一个元素 如果key不存在，什么也不做
 * @method bool|int lPush(string $key,string $value) static 在队列头部插入一个元素
 * @method int lPushx(string $key,string $value) static 在队列头插入一个元素 如果key不存在，什么也不做
 * @method int lLen(string $key) static 返回队列长度
 * @method array lRange(string $key,int $start,int $end) static 返回队列指定区间的元素
 * @method String lIndex(string $key,int $index) static 返回队列中指定索引的元素
 * @method bool lSet(string $key,int $index,string $value) static 设定队列中指定index的值
 * @method lGet(string $key,int $index) static 同lIndex
 * @method int lRem(string $key,int $count,string $value) static 删除值为vaule的count个元素
 * @method string|bool lPop(string $key) static 删除并返回队列中的头元素
 * @method string|bool rPop(string $key) static 删除并返回队列中的尾元素
 *
 * ----------------字符串操作命令----------------
 * @method bool set(string $key,string $value) static 设置一个key
 * @method string|bool get(string $key) static 得到一个key的值
 * @method bool setex(string $key,int $expire,string $value) static 设置一个有过期时间的key
 * @method bool setnx(string $key,string $value) static 设置一个key,如果key存在,不做任何操作
 * @method bool mset(array $arr) static 批量设置key
 *
 * ---------------无序集合操作命令---------------
 * @method array sMembers(string $key) static 返回集合中所有元素
 * @method array sDiff(string $key1,string $key2) static 求2个集合的差集
 * @method sAdd(string $key,string $value) static 添加集合，由于版本问题，扩展不支持批量添加。这里做了封装
 * @method int sCard(string $key) static 返回无序集合的元素个数
 * @method int sRem(string $key,string $value) static 从集合中删除一个元素
 *
 * -----------------管理操作命令-----------------
 * @method bool select(int $dbId) static 选择数据库
 * @method bool flushDB() static 清空当前数据库
 * @method string|array info(string $option) static 返回当前库信息
 * @method bool save() static 同步保存数据到磁盘
 * @method bool bgsave() static 异步保存数据到磁盘
 * @method int lastSave() static 返回最后保存到磁盘的时间戳
 * @method array keys(string $key) static 返回key,支持*多个字符，?一个字符
 * @method int del(string $key1, $key2 = null, $key3 = null) static 删除指定key，可以多个
 * @method bool exists(string $key) static 判断一个key值是不是存在
 * @method bool expire(string $key,int $expire) static 为一个key设定过期时间 单位为秒
 * @method int ttl(string $key) static 返回一个key还有多久过期，单位秒
 * @method bool exprieAt(string $key,int $time) static 设定一个key什么时候过期，time为一个时间戳
 * @method close() static 关闭服务器链接
 * @method int dbSize() static 返回当前数据库key数量
 * @method string randomKey() static 返回当前数据库key数量
 * @method mixed getDbId() static 得到当前数据库ID
 * @method mixed getAuth() static 返回当前密码
 * @method mixed getHost() static 返回当前host
 * @method mixed getPort() static 返回当前port
 * @method array getConnInfo() static 返回当前连接
 *
 * -----------------事务相关方法-----------------
 * @method void watch(string $key) static 监控key,就是一个或多个key添加一个乐观锁
 * @method unwatch() static 取消当前链接对所有key的watch
 * @method \Redis multi(int $type=\Redis::MULTI) static 开启一个事务
 * @method array exec() static 执行一个事务
 * @method discard() static 回滚一个事务
 * @method string|bool ping() static 测试当前链接是不是已经失效
 * @method bool auth(string $auth) static 验证密码
 *
 * -----------------自定义的方法-----------------
 * @method array|bool hashAll(string $prefix,string $ids) static 得到一组的ID号
 * @method string pushMessage(string $lkey,string|array $msg) static 生成一条消息，放在redis数据库中。使用0号库
 * @method string delKeys(string $keys,string $dbId) static 得到条批量删除key的命令
 */
class Redis
{
    private static $handler = null;

    /**
     * Redis配置
     * @var array
     */
    protected static $config = [
        'host'          => '127.0.0.1', // redis主机
        'port'          => '',          // redis端口
        'password'      => '',          // 密码
        'select'        => 0,           // 操作库
        'expire'        => 0,           // 有效期(秒)
        'timeout'       => 0,           // 超时时间(秒)
        'persistent'    => true,        // 是否长连接
        'prefix'        => '',          //前缀
    ];

    /**
     * 初始化
     * @param array $config
     */
    private static function init(){
        if(defined('THINK_VERSION')) {
            self::$config = array_merge(self::$config, \think\Config::get('redis'));
        } else {
            self::$config = array_merge(self::$config, \think\facade\Config::pull('redis'));
        }
        if( is_null(self::$handler) ) {
            $class = '\\tp5redis\\redis\\driver\\Redis';  //此处部署Redis驱动所在位置，本例为org/redis/driver/Redis，实际根据位置修改
            self::$handler = new $class(self::$config);
        }
    }

    /**
     * 静态调用
     * @param $method
     * @param $args
     * @return mixed
     */
    public static function __callStatic($method, $args)
    {
        self::init();
        return call_user_func_array([self::$handler, $method], $args);
    }
}
