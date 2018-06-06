<?php
//调用类
class HTTPStatus{
	public function query($question,$method = "get", $timeout=10){
		$return = array("error"=>0,"message"=>"","status_code"=>0,"reason"=>"","headers"=>array());
		$http_request = new HTTPRequest($question,$method,$timeout);
		$http_respons = $http_request->get_response();
		
		if($http_request->errmsg){
			$return["error"] = 1;
			$return["message"] = $http_request->errmsg;
		}else{
			$return["status_code"] = $http_respons->status_code;
			$return["reason"] = $http_respons->reason;
			$return["headers"] = $http_respons->get_headers();
		}
		return $return;
	}
}
//请求类
class HTTPRequest {
    var $url        = '';
    var $host       = '';
    var $port       = 80;
    var $path       = '/';
    var $method     = '';
    var $postdata   = '';
    var $cookies    = array();

    var $accept             = 'text/xml,application/xml,application/xhtml+xml,text/html,text/plain,image/png,image/jpeg,image/gif,*/*';
    var $accept_language    = 'zh-cn';
    var $accept_encoding    = 'gzip';
    var $user_agent         = 'Client/1.0';
    var $use_gzip           = false;
    var $username;
    var $password;
    var $timeout = 10;
    var $errmsg = "";
    
    public function HTTPRequest ($url, $method = 'get', $timeout = 10,$postData = '') {
        $this->url = $url;
        $this->method = $method;
        $this->postdata = $postData;
        $this->timeout = $timeout;
        
        $urlPattern = "/^http:\/\/([^\/]+)(\/.*)?/i";
        if (preg_match($urlPattern, $url, $urlArr)) {
            $hostStr = $urlArr[1];
            $hosts = preg_split("/:/i", $hostStr);
            $this->host = $hosts[0];
            if (count($hosts) > 1) {
                $this->port = $hosts[1];
            }
            if(count($urlArr) > 2) {
                $this->path = $urlArr[2];
            }
        }
    }
    public function get_response () {        
        try{
            $fp = @fsockopen($this->host, $this->port, $errno, $errstr, $this->timeout);
        }catch(Exception $e){
            $fp     = 0;
            $errno  = 0;
            $errstr = "";
        }
        if (!$fp) {
            switch($errno) {
                case -3:
                    $this->errmsg = 'Socket连接创建失败 (-3)';
                    break;
                case -4:
                    $this->errmsg = 'DNS定位失败 (-4)';
                    break;
                case -5:
                    $this->errmsg = '连接超时或被拒绝 (-5)';
                    break;
                default:
                    $this->errmsg = '连接失败 ('.$errno.')';
                    break;
                $this->errmsg .= ' '.$errstr;
            }
            return false;
        }
        socket_set_timeout($fp, $this->timeout);
        $request = $this->_request_header();
        //echo $request;

        fwrite($fp, $request);
        $content = '';
        $readState = 'start';
        $response = new HTTPResponse();
        while (!feof($fp)) {
            $line = fgets($fp, 4096);
            if ($readState == 'start') {
                $readState = 'header';
                if (!preg_match('/HTTP\/(\\d\\.\\d)\\s*(\\d+)\\s*(.*)/', $line, $m)) {
                    $this->errmsg = "非法的请求状态: " . htmlentities($line);
                    return false;
                }
                $response->http_version = $m[1]; //http版本
                $response->status_code = $m[2]; //状态码
                $response->reason = $m[3]; //理由
            }else if ($readState == 'header') {
                if (trim($line) == '') {
                    $readState = 'content';
                }
                if (!preg_match('/([^:]+):\\s*(.*)/', $line, $m)) {
                    continue;
                }
                $key = strtolower(trim($m[1]));
                $val = trim($m[2]);
                $response->append_headers($key, $val);
            } else {
                $content .= $line;
            }
        }
        fclose($fp);
        $response->content = $content;
        return $response;
    }
    public function set_cookies($array) {
        $this->cookies = $array;
    }
    private function _request_header() {
        $headers = array();
        $method = strtoupper($this->method);
        if ($method != 'POST') $method = 'GET';

        $headers[] = "{$method} {$this->path} HTTP/1.0";
        $headers[] = "Host: {$this->host}";
        $headers[] = "User-Agent: {$this->user_agent}";
        $headers[] = "Accept: {$this->accept}";
        $headers[] = "Connection: close";
        if ($this->use_gzip) {
            $headers[] = "Accept-Encoding: {$this->accept_encoding}";
        }
        $headers[] = "Accept-Language: {$this->accept_language}";
        // Cookies
        if ($this->cookies) {
            $cookie = 'Cookie: ';
            foreach ($this->cookies as $key => $value) {
                $cookie .= "$key=$value; ";
            }
            $headers[] = $cookie;
        }
        // authentication
        if ($this->username && $this->password) {
            $headers[] = 'Authorization: BASIC ' . base64_encode($this->username.':'.$this->password);
        }
        // 如果是POST方式, 设置content type和length头
        if ($method == 'POST') {
            $headers[] = 'Content-Type: application/x-www-form-urlencoded';
            $headers[] = 'Content-Length: ' . strlen($this->postdata);
        }
        $request = implode("\r\n", $headers) . "\r\n\r\n" . $this->postdata;
        return $request;
    } 
}
// http响应类
class HTTPResponse {
    private $data;
    private $headers = array();
    function HTTPResponse () {}
    //魔法设置
	public function __set($name,$value){
		$this->data[$name] = $value;
	}
	//魔法获取
	public function __get($name){
		if(isset($this->data[$name]))return $this->data[$name];
	}
    public function append_headers($k, $value) {
        if(isset($this->headers[$k])) {
            if(is_array($this->headers[$k])) {
                $this->headers[$k][] = $value;
            }else{
                $this->headers[$k] = array($this->headers[$k], $value);
            }
        }else{
            $this->headers[$k] = $value;
        }
    }
    public function get_headers () {
        return $this->headers;
    }
}

