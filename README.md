# php tcp 连接 redis

## 使用

```
$redis = new Redis('127.0.0.1');

// set get
$redis->set('abc','1993');
echo $redis->get('abc');

// 扩展方法
$redis->execute('SET bool bool');
echo $redis->query('GET bool');

```
