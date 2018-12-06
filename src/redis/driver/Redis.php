<?php
/**
 * Created by PhpStorm.
 * User: xiucai(封装者)
 * User: xuwei(原作者)
 * Date: 2018-09-04
 * Time: 11:11
 */
namespace tp5redis\redis\driver;
class Redis{
    /**
     * 驱动句柄
     * @var object
     */
    protected $handler = null;
    protected $options = [
        'host'       => '127.0.0.1',
        'port'       => 6379,
        'password'   => '',
        'select'     => 0,
        'timeout'    => 0,
        'expire'     => 0,
        'persistent' => false,
        'prefix'     => '',
    ];
    /**
     * 架构函数
     * @access public
     * @param  array $options 缓存参数
     */
    public function __construct($options = [])
    {
        if (!extension_loaded('redis')) {
            throw new \BadFunctionCallException('not support: redis');
        }
        if (!empty($options)) {
            $this->options = array_merge($this->options, $options);
        }
        $func = $this->options['persistent'] ? 'pconnect' : 'connect';
        $this->handler = new \Redis;
        $this->handler->$func($this->options['host'], $this->options['port'], $this->options['timeout']);
        if ('' != $this->options['password']) {
            $this->handler->auth($this->options['password']);
        }
        if (0 != $this->options['select']) {
            $this->handler->select($this->options['select']);
        }
    }

    /*****************hash表操作函数*******************/

    /**
     * 得到hash表中一个字段的值
     * @param string $key
     * @param string $field
     * @return string
     */
    public function hGet($key,$field)
    {
        return $this->handler->hGet($key,$field);
    }

    /**
     * 为hash表设定一个字段的值
     * @param string $key
     * @param string $field
     * @param string $value
     * @return bool|int
     */
    public function hSet($key,$field,$value)
    {
        return $this->handler->hSet($key,$field,$value);
    }

    /**
     * 判断hash表中，指定field是不是存在
     * @param string $key
     * @param string $field
     * @return bool
     */
    public function hExists($key,$field)
    {
        return $this->handler->hExists($key,$field);
    }

    /**
     * 删除hash表中指定字段 ,支持批量删除
     * @param string $key
     * @param string $field
     * @return bool|int
     */
    public function hDel($key,$field)
    {
        $fieldArr=explode(',',$field);
        $delNum=0;
        foreach($fieldArr as $row)
        {
            $row=trim($row);
            $delNum+=$this->handler->hDel($key,$row);
        }

        return $delNum;
    }

    /**
     * 返回hash表元素个数
     * @param string $key
     * @return int
     */
    public function hLen($key)
    {
        return $this->handler->hLen($key);
    }

    /**
     * 为hash表设定一个字段的值,如果字段存在，返回false
     * @param string $key
     * @param string $field
     * @param string $value
     * @return bool
     */
    public function hSetNx($key,$field,$value)
    {
        return $this->handler->hSetNx($key,$field,$value);
    }

    /**
     * 为hash表多个字段设定值
     * @param string $key
     * @param array $value
     * @return bool
     */
    public function hMset($key,$value)
    {
        if(!is_array($value)) return false;
        return $this->handler->hMset($key,$value);
    }

    /**
     * 取出hash表多个字段的值
     * @param string $key
     * @param array|string $value string以','号分隔字段
     * @return array
     */
    public function hMget($key,$field)
    {
        if(!is_array($field)) $field=explode(',', $field);
        return $this->handler->hMget($key,$field);
    }

    /**
     * 为hash表设值累加，可以负数
     * @param string $key
     * @param string $field
     * @param string $value
     * @return int
     */
    public function hIncrBy($key,$field,$value)
    {
        $value=intval($value);
        return $this->handler->hIncrBy($key,$field,$value);
    }

    /**
     * 返回所有hash表的所有字段
     * @param string $key
     * @return array
     */
    public function hKeys($key)
    {
        return $this->handler->hKeys($key);
    }

    /**
     * 返回所有hash表的字段值，为一个索引数组
     * @param string $key
     * @return array
     */
    public function hVals($key)
    {
        return $this->handler->hVals($key);
    }

    /**
     * 返回所有hash表的字段值，为一个关联数组
     * @param string $key
     * @return array
     */
    public function hGetAll($key)
    {
        return $this->handler->hGetAll($key);
    }

    /*********************有序集合操作*********************/

    /**
     * 给当前集合添加一个元素
     * 如果value已经存在，会更新order的值。
     * @param string $key
     * @param string $order 序号
     * @param string $value 值
     * @return int
     */
    public function zAdd($key,$order,$value)
    {
        return $this->handler->zAdd($key,$order,$value);
    }

    /**
     * 给$value成员的order值，增加$num,可以为负数
     * @param string $key
     * @param string $num 序号
     * @param string $value 值
     * @return mixed
     */
    public function zinCry($key,$num,$value)
    {
        return $this->handler->zinCry($key,$num,$value);
    }

    /**
     * 删除值为value的元素
     * @param string $key
     * @param string $value
     * @return bool
     */
    public function zRem($key,$value)
    {
        return $this->handler->zRem($key,$value);
    }

    /**
     * 集合以order递增排列后，0表示第一个元素，-1表示最后一个元素
     * @param string $key
     * @param int $start
     * @param int $end
     * @return array
     */
    public function zRange($key,$start,$end)
    {
        return $this->handler->zRange($key,$start,$end);
    }

    /**
     * 集合以order递减排列后，0表示第一个元素，-1表示最后一个元素
     * @param string $key
     * @param int $start
     * @param int $end
     * @return array
     */
    public function zRevRange($key,$start,$end)
    {
        return $this->handler->zRevRange($key,$start,$end);
    }

    /**
     * 集合以order递增排列后，返回指定order之间的元素。
     * min和max可以是-inf和+inf　表示最大值，最小值
     * @param string $key
     * @param string $start
     * @param string $end
     * @package array $option 参数
     *     withscores=>true，表示数组下标为Order值，默认返回索引数组
     *     limit=>array(0,1) 表示从0开始，取一条记录。
     * @return array
     */
    public function zRangeByScore($key,$start='-inf',$end="+inf",$option=array())
    {
        return $this->handler->zRangeByScore($key,$start,$end,$option);
    }

    /**
     * 集合以order递减排列后，返回指定order之间的元素。
     * min和max可以是-inf和+inf　表示最大值，最小值
     * @param string $key
     * @param int $start
     * @param int $end
     * @package array $option 参数
     *     withscores=>true，表示数组下标为Order值，默认返回索引数组
     *     limit=>array(0,1) 表示从0开始，取一条记录。
     * @return array
     */
    public function zRevRangeByScore($key,$start='-inf',$end="+inf",$option=array())
    {
        return $this->handler->zRevRangeByScore($key,$start,$end,$option);
    }

    /**
     * 返回order值在start end之间的数量
     * @param string $key
     * @param int $start
     * @param int $end
     * @return int
     */
    public function zCount($key,$start,$end)
    {
        return $this->handler->zCount($key,$start,$end);
    }

    /**
     * 返回值为value的order值
     * @param string $key
     * @param string $value
     * @return float
     */
    public function zScore($key,$value)
    {
        return $this->handler->zScore($key,$value);
    }

    /**
     * 返回集合以score递增加排序后，指定成员的排序号，从0开始。
     * @param string $key
     * @param string $value
     * @return int
     */
    public function zRank($key,$value)
    {
        return $this->handler->zRank($key,$value);
    }

    /**
     * 返回集合以score递增加排序后，指定成员的排序号，从0开始。
     * @param string $key
     * @param string $value
     * @return int
     */
    public function zRevRank($key,$value)
    {
        return $this->handler->zRevRank($key,$value);
    }

    /**
     * 删除集合中，score值在start end之间的元素　包括start end
     * min和max可以是-inf和+inf　表示最大值，最小值
     * @param string $key
     * @param int $start
     * @param int $end
     * @return int
     */
    public function zRemRangeByScore($key,$start,$end)
    {
        return $this->handler->zRemRangeByScore($key,$start,$end);
    }

    /**
     * 返回集合元素个数。
     * @param string $key
     * @return int
     */
    public function zCard($key)
    {
        return $this->handler->zCard($key);
    }
    /*********************队列操作命令************************/

    /**
     * 在队列尾部插入一个元素
     * @param string $key
     * @param string $value
     * @return bool|int
     */
    public function rPush($key,$value)
    {
        return $this->handler->rPush($key,$value);
    }

    /**
     * 在队列尾部插入一个元素 如果key不存在，什么也不做
     * @param string $key
     * @param string $value
     * @return int
     */
    public function rPushx($key,$value)
    {
        return $this->handler->rPushx($key,$value);
    }

    /**
     * 在队列头部插入一个元素
     * @param string $key
     * @param string $value
     * @return bool|int
     */
    public function lPush($key,$value)
    {
        return $this->handler->lPush($key,$value);
    }

    /**
     * 在队列头插入一个元素 如果key不存在，什么也不做
     * @param string $key
     * @param string $value
     * @return int
     */
    public function lPushx($key,$value)
    {
        return $this->handler->lPushx($key,$value);
    }

    /**
     * 返回队列长度
     * @param string $key
     * @return int
     */
    public function lLen($key)
    {
        return $this->handler->lLen($key);
    }

    /**
     * 返回队列指定区间的元素
     * @param string $key
     * @param int $start
     * @param int $end
     * @return array
     */
    public function lRange($key,$start,$end)
    {
        return $this->handler->lrange($key,$start,$end);
    }

    /**
     * 返回队列中指定索引的元素
     * @param string $key
     * @param int $index
     * @return String
     */
    public function lIndex($key,$index)
    {
        return $this->handler->lIndex($key,$index);
    }

    /**
     * 设定队列中指定index的值
     * @param string $key
     * @param int $index
     * @param string $value
     * @return bool
     */
    public function lSet($key,$index,$value)
    {
        return $this->handler->lSet($key,$index,$value);
    }

    /**
     * @see 同 lIndex()
     * @param string $key
     * @param int $index
     * @link  http://redis.io/commands/lindex
     */
    public function lGet( $key, $index ) {
        return $this->handler->lGet($key,$index);
    }

    /**
     * 删除值为vaule的count个元素
     * PHP-REDIS扩展的数据顺序与命令的顺序不太一样，不知道是不是bug
     * count>0 从尾部开始
     *  >0　从头部开始
     *  =0　删除全部
     * @param   string  $key
     * @param   string  $value
     * @param   int     $count
     * @return int
     */
    public function lRem($key,$count,$value)
    {
        return $this->handler->lRem($key,$value,$count);
    }

    /**
     * 删除并返回队列中的头元素。
     * @param string $key
     * @return string|bool
     */
    public function lPop($key)
    {
        return $this->handler->lPop($key);
    }

    /**
     * 删除并返回队列中的尾元素
     * @param string $key
     * @return string|bool
     */
    public function rPop($key)
    {
        return $this->handler->rPop($key);
    }

    /*************redis字符串操作命令*****************/

    /**
     * 设置一个key
     * @param string $key
     * @param string $value
     * @return bool
     */
    public function set($key,$value)
    {
        return $this->handler->set($key,$value);
    }

    /**
     * 得到一个key
     * @param string $key
     * @return bool|string
     */
    public function get($key)
    {
        return $this->handler->get($key);
    }

    /**
     * 设置一个有过期时间的key
     * @param   string  $key
     * @param   int     $expire
     * @param   string  $value
     * @return  bool
     */
    public function setex($key,$expire,$value)
    {
        return $this->handler->setex($key,$expire,$value);
    }


    /**
     * 设置一个key,如果key存在,不做任何操作.
     * @param string $key
     * @param string $value
     * @return bool
     */
    public function setnx($key,$value)
    {
        return $this->handler->setnx($key,$value);
    }

    /**
     * 批量设置key
     * @param  array $arr Pairs: array(key => value, ...)
     * @return bool
     */
    public function mset($arr)
    {
        return $this->handler->mset($arr);
    }

    /*************redis　无序集合操作命令*****************/

    /**
     * 返回集合中所有元素
     * @param string $key
     * @return array
     */
    public function sMembers($key)
    {
        return $this->handler->sMembers($key);
    }

    /**
     * 求2个集合的差集
     * @param string $key1
     * @param string $key2
     * @return array
     */
    public function sDiff($key1,$key2)
    {
        return $this->handler->sDiff($key1,$key2);
    }

    /**
     * 添加集合。由于版本问题，扩展不支持批量添加。这里做了封装
     * @param $key
     * @param string|array $value
     */
    public function sAdd($key,$value)
    {
        if(!is_array($value)) {
            $arr=array($value);
        }else{
            $arr=$value;
        }
        foreach($arr as $row) $this->handler->sAdd($key,$row);
    }

    /**
     * 返回无序集合的元素个数
     * @param string $key
     * @return int
     */
    public function sCard($key)
    {
        return $this->handler->sCard($key);
    }

    /**
     * 从集合中删除一个元素
     * @param string $key
     * @param string $value
     * @return int
     */
    public function sRem($key,$value)
    {
        return $this->handler->sRem($key,$value);
    }

    /*************redis管理操作命令*****************/

    /**
     * 选择数据库
     * @param int $dbId 数据库ID号
     * @return bool
     */
    public function select($dbId)
    {
        return $this->handler->select($dbId);
    }

    /**
     * 清空当前数据库
     * @return bool
     */
    public function flushDB()
    {
        return $this->handler->flushDB();
    }

    /**
     * 返回当前库信息
     * @param string $option
     * @return string
     */
    public function info( $option = null )
    {
        return $this->handler->info( $option );
    }

    /**
     * 同步保存数据到磁盘
     * @return bool
     */
    public function save()
    {
        return $this->handler->save();
    }

    /**
     * 异步保存数据到磁盘
     * @return bool
     */
    public function bgsave()
    {
        return $this->handler->bgsave();
    }

    /**
     * 返回最后保存到磁盘的时间戳
     * @return int
     */
    public function lastSave()
    {
        return $this->handler->lastSave();
    }

    /**
     * 返回key,支持*多个字符，?一个字符
     * 只有*　表示全部
     * @param string $key 键，*代表全部
     * @return array
     */
    public function keys($key)
    {
        return $this->handler->keys($key);
    }

    /**
     * 删除指定key，可以多个
     * @param string $key1
     * @param null|string $key2
     * @param null|string $key3
     * @return int
     */
    public function del($key1, $key2 = null, $key3 = null)
    {
        return $this->handler->del($key1, $key2, $key3);
    }

    /**
     * 判断一个key值是不是存在
     * @param string $key
     * @return bool
     */
    public function exists($key)
    {
        return $this->handler->exists($key);
    }

    /**
     * 为一个key设定过期时间 单位为秒
     * @param string $key
     * @param int $expire
     * @return bool
     */
    public function expire($key,$expire)
    {
        return $this->handler->expire($key,$expire);
    }

    /**
     * 返回一个key还有多久过期，单位秒
     * @param string $key
     * @return int
     */
    public function ttl($key)
    {
        return $this->handler->ttl($key);
    }

    /**
     * 设定一个key什么时候过期，time为一个时间戳
     * @param string $key
     * @param int $time
     * @return bool
     */
    public function exprieAt($key,$time)
    {
        return $this->handler->expireAt($key,$time);
    }

    /**
     * 关闭服务器链接
     */
    public function close()
    {
        return $this->handler->close();
    }

    /**
     * 返回当前数据库key数量
     * @return int
     */
    public function dbSize()
    {
        return $this->handler->dbSize();
    }

    /**
     * 返回一个随机key
     * @return string
     */
    public function randomKey()
    {
        return $this->handler->randomKey();
    }

    /**
     * 得到当前数据库ID
     * @return mixed
     */
    public function getDbId()
    {
        return $this->options['select'];
    }

    /**
     * 返回当前密码
     * @return mixed
     */
    public function getAuth()
    {
        return $this->options['password'];
    }

    /**
     * 返回当前host
     * @return mixed
     */
    public function getHost()
    {
        return $this->options['host'];
    }

    /**
     * 返回当前port
     * @return mixed
     */
    public function getPort()
    {
        return $this->options['port'];
    }

    /**
     * 返回当前连接
     * @return array
     */
    public function getConnInfo()
    {
        return array(
            'host'=>$this->options['host'],
            'port'=>$this->options['port'],
            'auth'=>$this->options['password']
        );
    }
    /*********************事务的相关方法************************/

    /**
     * 监控key,就是一个或多个key添加一个乐观锁
     * 在此期间如果key的值如果发生的改变，刚不能为key设定值
     * 可以重新取得Key的值。
     * @param string $key
     * @return void
     */
    public function watch($key)
    {
        return $this->handler->watch($key);
    }

    /**
     * 取消当前链接对所有key的watch
     *  EXEC 命令或 DISCARD 命令先被执行了的话，那么就不需要再执行 UNWATCH 了
     */
    public function unwatch()
    {
        return $this->handler->unwatch();
    }

    /**
     * 开启一个事务
     * 事务的调用有两种模式Redis::MULTI和Redis::PIPELINE，
     * 默认是Redis::MULTI模式，
     * Redis::PIPELINE管道模式速度更快，但没有任何保证原子性有可能造成数据的丢失
     * @param int $type
     * @return \Redis
     */
    public function multi($type=\Redis::MULTI)
    {
        return $this->handler->multi($type);
    }

    /**
     * 执行一个事务
     * 收到 EXEC 命令后进入事务执行，事务中任意命令执行失败，其余的命令依然被执行
     * @return array
     */
    public function exec()
    {
        return $this->handler->exec();
    }

    /**
     * 回滚一个事务
     */
    public function discard()
    {
        return $this->handler->discard();
    }

    /**
     * 测试当前链接是不是已经失效
     * 没有失效返回+PONG
     * 失效返回false
     * @return string|bool
     */
    public function ping()
    {
        return $this->handler->ping();
    }

    /**
     * 验证密码
     * @param string $auth
     * @return bool
     */
    public function auth($auth)
    {
        return $this->handler->auth($auth);
    }
    /*********************自定义的方法,用于简化操作************************/

    /**
     * 得到一组的ID号
     * @param string $prefix
     * @param string $ids
     * @return array|bool
     */
    public function hashAll($prefix,$ids)
    {
        if($ids==false)
            return false;
        if(is_string($ids))
            $ids=explode(',', $ids);
        $arr=array();
        foreach($ids as $id)
        {
            $key=$prefix.'.'.$id;
            $res=self::hGetAll($key);
            if($res!=false)
                $arr[]=$res;
        }

        return $arr;
    }

    /**
     * 生成一条消息，放在redis数据库中。使用0号库。
     * @param string $lkey
     * @param string|array $msg
     * @return string
     */
    public function pushMessage($lkey,$msg)
    {
        if(is_array($msg)){
            $msg    =    json_encode($msg);
        }
        $key    =    md5($msg);

        //如果消息已经存在，删除旧消息，已当前消息为准
        //echo $n=$this->lRem($lkey, 0, $key)."\n";
        //重新设置新消息
        self::lPush($lkey, $key);
        self::setex($key, 3600, $msg);
        return $key;
    }

    /**
     * 得到条批量删除key的命令
     * @param string $keys
     * @param string $dbId
     * @return string
     */
    public function delKeys($keys,$dbId)
    {
        $redisInfo = self::getConnInfo();
        $cmdArr = array(
            'redis-cli',
            '-a',
            $redisInfo['auth'],
            '-h',
            $redisInfo['host'],
            '-p',
            $redisInfo['port'],
            '-n',
            $dbId,
        );
        $redisStr = implode(' ', $cmdArr);
        $cmd = "{$redisStr} KEYS \"{$keys}\" | xargs {$redisStr} del";
        return $cmd;
    }
}