<?php
/**
* 后台脚本控制类
*/
class tCli{ 
    protected static $pid_file      = "";
    protected static $terminate     = false;   //是否中断
    protected static $workers_count = 0;
    protected static $gc_enabled    = null;
    protected static $workers_max   = 8;       //最多运行8个进程
    protected static $tasks         = array(); //执行的工作任务
    protected static $user          = "root";
    protected static $output        = "/dev/null";
    protected static $name          = "";
 
    public static function init($user='root',$output="/dev/null"){ 
        global $argv;

        self::$user       = $user;              //设置运行的用户 默认情况下nobody
        self::$output     = $output;            //设置输出的地方
        self::$name       = basename($argv[0]);
        self::$pid_file   = __ROOT__. "cache/" .__CLASS__ . "_" . self::$name . ".pid";            
    }

    //开始
    public static function start($workers_num = 2){
        //初始
        self::init();
        //检查环境
        self::check_pcntl();
        //初始化进程
        self::daemonize();
        //初始化任务
        self::init_task();
        //开始执行
        self::run($workers_num);
    }

    //整个进程退出
    public static function stop(){
        //初始化
        self::init();
        //检查PId存在
        self::check_pidfile();
        //
        self::log(self::$name ." stoped");
        //posix_kill(0, SIGKILL);
    }

    //检查环境是否支持pcntl支持
    public static function check_pcntl(){
        if ( ! function_exists('pcntl_signal_dispatch')) {
            // PHP < 5.3 uses ticks to handle signals instead of pcntl_signal_dispatch
            // call sighandler only every 10 ticks
            declare(ticks = 10);
        } 
        // Make sure PHP has support for pcntl
        if ( ! function_exists('pcntl_signal')) {
            $message = 'PHP does not appear to be compiled with the PCNTL extension.  This is neccesary for daemonization';
            self::log($message);
            throw new Exception($message);
        }

        //信号处理
        pcntl_signal(SIGTERM, array(__CLASS__, "signal_handler"),false);
        pcntl_signal(SIGINT,  array(__CLASS__, "signal_handler"),false);
        pcntl_signal(SIGQUIT, array(__CLASS__, "signal_handler"),false);
        pcntl_signal(SIGALRM, array(__CLASS__, "signal_handler"));
        //pcntl_signal(SIGCHLD, array(__CLASS__, "signal_handler"),false); // if worker die, minus children num
 
        // Enable PHP 5.3 garbage collection
        if (function_exists('gc_enable')){
            gc_enable();
            self::$gc_enabled = gc_enabled();
        }
    }
 
    // daemon化程序
    public static function daemonize(){ 
        global $stdin, $stdout, $stderr;        
        set_time_limit(0); 
        // 只允许在cli下面运行
        if (php_sapi_name() != "cli"){
            die("only run in command line mode\n");
        } 
        // 只能单例运行
        self::check_pidfile(); 
        umask(0); //把文件掩码清0
 
        if (pcntl_fork() != 0){ //是父进程，父进程退出
            exit();
        }
 
        posix_setsid();//设置新会话组长，脱离终端
 
        if (pcntl_fork() != 0){ //是第一子进程，结束第一子进程   
            exit();
        }
 
        chdir(__ROOT__); //改变工作目录
 
        self::set_user(self::$user) or die("cannot change owner");
 
        //关闭打开的文件描述符
        if(App::get_conf("cfg.debug")) {   
            self::log("debug on");
        }else{
            fclose(STDIN);
            fclose(STDOUT);
            fclose(STDERR);
     
            $stdin  = fopen(self::$output, 'r');
            $stdout = fopen(self::$output, 'a');
            $stderr = fopen(self::$output, 'a');
        } 
        self::create_pidfile();
    }

    //--检测pid是否已经存在
    public static function check_pidfile($force = true){
        if ($force)   
            $signo = SIGKILL; //kill -9  
        else    
            $signo = SIGTERM; //kill
        
        if (file_exists(self::$pid_file)){
            $pid = @file_get_contents(self::$pid_file);
            $pid = intval($pid);
            if($pid > 0){                
                if (posix_kill($pid, $signo)) {
                    @unlink(self::$pid_file);
                }else{
                    self::log("KILL pid ".$pid ." fail");
                }  
                posix_kill($pid, SIGTERM);                
            }            
        }
    }

    //----创建pid
    public static function create_pidfile(){
        $fp = fopen(self::$pid_file, 'w') or die("cannot create pid file");
        fwrite($fp, posix_getpid());
        fclose($fp);
        chmod(self::$pid_file, 0644);
        //self::log("create pid file " . self::$pid_file);
    }
 
    //设置运行的用户
    public static function set_user($name){ 
        $result = false;
        if (empty($name)){
            return true;
        }
        $user = posix_getpwnam($name);
        if ($user) {
            $uid = $user['uid'];
            $gid = $user['gid'];
            $result = posix_setuid($uid);
            posix_setgid($gid);
        }
        return $result; 
    }

    //信号处理函数
    public static function signal_handler($signo){ 
        switch($signo){ 
            //用户自定义信号
            case SIGUSR1: //busy
                if (self::$workers_count <self::$workers_max){
                    $pid = pcntl_fork();
                    if ($pid > 0){
                        self::$workers_count ++;
                    }
                }
                break;
            //子进程结束信号
            case SIGCHLD:
                while(($pid=pcntl_waitpid(-1, $status, WNOHANG)) > 0){
                    self::$workers_count --;
                }
                break;
            //中断进程
            case SIGTERM:
            case SIGHUP:
            case SIGQUIT: 
                self::$terminate = true;
                break;
            //闹钟信号处理
            case SIGALRM:
                self::tick_task();
                pcntl_alarm(1);
                break;
            default:
                return false;
        }
 
    }
    /**
    *开始开启进程
    *$count 准备开启的进程数
    */
    public static function run($count=1){
        self::log(self::$name . " start");        
        $siginfo = array();
        pcntl_alarm(1);
        while (true) {
            if (self::$terminate){
                break;
            }
            sleep(1);
            //pcntl_sigtimedwait(array(SIGALRM),$siginfo,1);
            pcntl_signal_dispatch();
        }
        exit(0); 
    }
 
    //初始化任务
    public static function init_task() {
        foreach(App::get_conf("task") as $key => $worker) {
            if(isset($worker['status']) && $worker['status']) {
                self::add_task($worker['time_long'],$worker['controller'], $worker['action'], $worker['parm'], $worker['persistent']);
             }
        }
    }

    /**
     * 
     * 添加一个任务
     * 
     * @param int $time_long 多长时间运行一次 单位秒
     * @param callback $func 任务运行的函数或方法
     * @param mix $args 任务运行的函数或方法使用的参数
     * @return void
     */
    public static function add_task($time_long, $controller,$action, $args = array(), $persistent = true){
        if($time_long <= 0){
            return false;
        }
        $time_now = time();
        $run_time = $time_now + $time_long;
        if(!isset(self::$tasks[$run_time])){
            self::$tasks[$run_time] = array();
        }
        self::$tasks[$run_time][] = array($controller,$action,$args,$persistent,$time_long);
        return true;
    }  

    /**
     * 
     * 定时被调用，用于触发定时任务
     * 
     * @return void
     */
    public static function tick_task(){
        if(empty(self::$tasks)){
            return;
        }        
        $time_now = time();
        foreach (self::$tasks as $run_time=>$task_data){
            // 时间到了就运行一下
            if($time_now >= $run_time){
                foreach($task_data as $index=>$one_task){
                    $task_ctrl   = $one_task[0];
                    $task_action = $one_task[1];
                    $task_args   = $one_task[2];
                    $persistent  = $one_task[3];
                    $time_long   = $one_task[4];
                    //执行任务
                    App::exec($task_ctrl, $task_action);
                    //call_user_func_array($task_func, $task_args);
                    // 持久的放入下一个任务队列
                    if($persistent){
                        self::add_task($time_long, $task_ctrl, $task_action, $task_args);
                    }
                }
                unset(self::$tasks[$run_time]);
            }
        }
    }    
    /**
     * 删除所有的任务
     */
    public static function del_task(){
        self::$tasks = array();
    }
 
    //日志处理
    public static function log($message){
        printf("%s\t%d\t%d\t%s\n", date("Y-m-d H:i:s"), posix_getpid(), posix_getppid(), $message);
        //X("tLog")->write("error",array(posix_getpid(), posix_getppid(), $message));
    }
}
?>