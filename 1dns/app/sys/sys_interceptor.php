<?php
/**
 * 内核拦截器
 * 
 * 在app中使用这个类，需要在config.php里配置interceptor
 * 'interceptor'=>array(
 *		'classname', //将classname类注册到所有位置
 *		'classname1@onFinishApp', //将classname1类注册到onFinishApp这个位置
 * );
 * 
 * <ul>
 *	<li>onPhpShutDown一旦注册，便肯定会执行，即使程序中调用了die和exit</li>
 * </ul>
 *
 * 在使用拦截器时，建议一个拦截器只完成一方面的工作，比如tBlog@onFinishApp,tUser@onCreateApp
 * 虽然tBlog和tUser的逻辑可以写在一类里，但为了以后维护的方便，建议拆分开
 *
 */
class tInterceptor{
	/**
	 * @brief 系统中预定的位置
	 */
	private static $valid_position = array(
		'onCreateApp' , 'onFinishApp' ,
		'onCreateController' , 'onFinishController',
		'onCreateAction' , 'onFinishAction',
		'onCreateView' , 'onFinishView',
		'onPhpShutDown','onError','onException'
	);
	private static $obj = array();
	/**
	 * 向系统中的拦截位置注册类
	 * @param string|array $value 可以为 "iclass_name","class_name@position",也可以是由他们组成的数组
	 */
	public static function reg($value){
		if( is_array($value) ){
			foreach($value as $v){
				self::reg($v);
			}
		}else{
			$tmp = explode("@",trim($value));
			if(count($tmp) == 2){
				self::reg_position($tmp[0] , $tmp[1]);
			}else{
				foreach(self::$valid_position as $value){
					self::reg_position($tmp[0] , $value);
				}
			}
		}
	}	
	/**
	 * 直接像某位置注册类
	 * @param string $className
	 * @param string $position
	 */
	public static function reg_position($class_name,$position){
		$valid_pos = in_array( $position,self::$valid_position);
		$have_done = isset(self::$obj[$position]) &&  in_array($class_name,self::$obj[$position]);
		if($valid_pos && !$have_done  ){
			self::$obj[$position][] = $class_name;
		}
	}

	/**
	 * 调用注册到某个位置的拦截器
	 * @param string $position 位置
	 */
	public static function run($position){
		if( !isset(self::$obj[$position]) || !in_array($position , self::$valid_position)){
			return;
		}
		foreach( self::$obj[$position] as $value){
			call_user_func(array($value,$position));
		}
	}

	/**
	 * 删除某个位置的所有拦截器，如果$class_name!=null,则只删除它一个
	 * @param string $position
	 * @param string|null $className
	 */
	public static function del($position,$class_name = null){
		if(!isset(self::$obj[$position])){
			if($className!==null){
				foreach(self::$obj[$position] as $key=>$value){
					if( $class_name==$value ){
						unset(self::$obj[$position][$key]);
						break;
					}
				}
			}else{
				unset(self::$obj[$position]);
			}
		}
	}
	
	/**
	 * 清空所有拦截器
	 */
	public static function del_all(){
		self::$obj = array();
	}
	
	/**
	 * 调用所有的onFinishApp拦截器
	 */
	public static function shut_down(){
		self::run("onPhpShutDown");
	}
}

