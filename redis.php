<?php
/**
* Redis 操作类
*/
class Redis
{
	public $host;
	public $port;
	public $sock;

	function __construct($host, $port=6379)
	{
        $this->host = $host;
        $this->port = $port;
        $this->connect();
	}

	// 连接
	public function connect()
	{
        if ($sock = pfsockopen($this->host, $this->port, $errno, $errstr)) {
            $this->sock = $sock;
            return;
        }
    	$msg = "Cannot open socket to {$this->host}:{$this->port}";
    	trigger_error("$msg.", E_USER_ERROR);     
	}

	// 设置 set value
	public function set($key,$value){
		$this->send( 'SET '.$key.' '.$value );
		return true;
	}

	// 获取 get value
	public function get($args){
		$this->send('GET '.$args);
		return $this->read();
	}

	// 执行
	public function execute($args){
		$this->send($args);
		return true;
	}

	// 执行查询
	public function query($args){
		$this->send($args);
		return $this->read();
	}	

	// 发送指令
	protected function send($arg){
		fputs($this->sock, $arg."\r\n");
	}

	// 读取
	protected function read(){
		$error = $this->getError();
		if( $error == '$-1' ){
			return null;
			exit;
		}
		fgets($this->sock);
		return trim( fgets($this->sock) );
		exit;
	}

	// 获取操作信息
	protected function getError(){
		return trim(fgets($this->sock));
	}

	// 关闭
	protected function close(){
		@fclose($this->sock );
	}

	public function __destruct ()
	{
		$this->close();
	}

}

// 使用
$redis = new Redis('127.0.0.1');

// set get
$redis->set('abc','1993');
echo $redis->get('abc');

// 扩展方法
$redis->execute('SET bool bool');
echo $redis->query('GET bool');
