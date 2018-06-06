<?php
class cls_mail{
	private $config = array();//邮件配置信息
	private $smtp   = null;   //邮件发送对象
	private $error  = '';     //错误信息
	private $tpl    = array(
		"findpass"  => "八戒DNS密码找回",
		"rz"        => "八戒DNS邮箱认证",
	);
	//构造函数
	public function __construct($config = null){
		if($config == null){
			$config = App::get_conf("db.smtp");
		}
		$this->config  = $config;
		if($this->check_conf($config)){
			//使用系统mail函数发送
			if(isset($config['email_type']) && $config['email_type']=='2'){
				$this->smtp = new tSmtp();
			}else{
				//使用外部SMTP服务器发送
				$server     = $config['smtp'];
				$port       = $config['smtp_port'];
				$account    = $config['smtp_user'];
				$password   = $config['smtp_pwd'];
				$this->smtp = new tSmtp($server,$port,$account,$password);
			}
			if(!$this->smtp){
				$this->error = '无法创建smtp类';
			}
		}else{
			$this->error = '配置参数填写不完整';
		}
	}

	//获取错误信息
	public function get_error(){
		return $this->error;
	}

	/**
	 * @brief 检查邮件配置信息的合法性
	 * @parms $site_config array 配置信息
	 * @return bool true:成功;false:失败;
	 */
	public function check_conf($config){
		if(isset($config['email_type']) && isset($config['mail_address'])){
			if($config['email_type'] == 1){
				$must_config = array('smtp','smtp_user','smtp_pwd','smtp_port');
				foreach($must_config as $val){
					if(!isset($config[$val]) || $config[$val] == ''){
						return false;
					}
				}
				return true;
			}else{
				return true;
			}
		}else{
			return false;
		}
	}

	/**
	 * @brief 邮件发送
	 * @parms  $to      string 收件人
	 * @parms  $title   string 标题
	 * @parms  $content string 内容
	 * @parms  $bcc     string 抄送人(";"分号间隔的email地址)
	 * @return bool true:成功;false:失败;
	 */
	public function send($to,$title,$content,$bcc = ''){
		if(is_object($this->smtp)){
			$from = $this->config['mail_address'];
			$ret = $this->smtp->send($to,$from,$title,$content , "" , "HTML" , "" , $bcc );
			return $ret;
		}else{
			return false;
		}
	}
	//同一邮箱发送间隔
	public function chk_email_send($email,$tpl,$distime = 120){
		global $timestamp;
		$row = M("sys_email")->get_row("email='{$email}' AND tpl='{$tpl}'","id,email,tpl,dateline","dateline DESC");
		if(isset($row['id'])){
			return ($timestamp - $row['dateline']) < $distime;
		}
		return false;
	}
	//根据模板发送
	public function send_tpl($to,$tpl,$data){
		global $timestamp;
		if(in_array($tpl,array_keys($this->tpl))){
			$from = $this->config['mail_address'];
			App::$controller->assign("data",$data);
			
			$title   = $this->tpl[$tpl];
			$content = App::$controller->fetch("tpl/email_$tpl");
			$ret = $this->send($to,$title,$content);
			if($ret){
				M("sys_email")->set_data(array(
					"title"    => $title,
					"tpl"      => $tpl,
					"content"  => $content,
					"email"    => $to,
					"status"   => 1,
					"dateline" => $timestamp,
				))->add();
			}
			return $ret;
		}else{
			return false;
		}
	}
	//获取配置信息
	public function get_configitem($key){
		return isset($this->config[$key]) ? $this->config[$key] : null;
	}
	//邮件添加
	public function email_add($to,$tpl,$title,$content){
		global $timestamp;
		if (empty($to)) {
			return 0;
		}
		if (strlen($title) > 100) {
			if (empty($content)) {
				$content = $title;
				$title = '';
			}else{
				$title = "sendCloud模板发送";
			}
		}
		$res = M("sys_email")->set_data(array(
			"title"    => $title?:'',
			"tpl"      => $tpl?:'',
			"content"  => $content?:'',
			"email"    => $to,
			"status"   => 1,
			"dateline" => $timestamp,
		))->add();
		if ($res) {
			return $res;
		}else{
			return 0;
		}
	}
}