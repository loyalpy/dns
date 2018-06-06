<?php
return array (
  67 => 
  array (
    'id' => '67',
    'pid' => '0',
    'type' => 'top',
    'module' => 'domain_manager',
    'action' => 'index',
    'name' => '域名解析',
    'enname' => 'record',
    'url' => '/domain_manager/index',
    'extaction' => '',
    'status' => '1',
    'isopen' => '1',
    'description' => '',
    'sort' => '1',
    'has_children' => '6',
    'level' => 0,
    'space' => '',
  ),
  88 => 
  array (
    'id' => '88',
    'pid' => '67',
    'type' => 'top',
    'module' => 'domain_manager',
    'action' => 'domain',
    'name' => '域名管理',
    'enname' => '',
    'url' => '/domain_manager/domain',
    'extaction' => 'qy,域名牵引
qy_lock,域名牵引锁定
qy_unlock,域名牵引解锁
log,域名操作日志
log_switch,域名切换日志
black,域名黑白名单
blackdel,黑白名单删除
deleted,已删除域名
find,域名找回
diyline,自定义线路
expserch,域名到期搜索
checkall,检测NS
checkns,查询域名NS
edit,域名编辑权限
refreshall,批量刷新纪录
batchdel,批量删除
records,查看域名解析
refresh,刷新域名记录
del,域名删除
change_status,域名暂停启用
change,域名更多操作
sysdel,域名锁定
record_check,域名URL审核
record_check_batch,批量域名URL审核
get,获取用户操作
diyline_del,自定义线路删除
diyline_sh,自定义线路审核
order,域名续费历史
index_find_ask,域名解析统计
bind,别名绑定',
    'status' => '1',
    'isopen' => '0',
    'description' => '',
    'sort' => '0',
    'has_children' => '0',
    'level' => 1,
    'space' => '&nbsp;&nbsp;&nbsp;&nbsp;',
  ),
  90 => 
  array (
    'id' => '90',
    'pid' => '67',
    'type' => 'top',
    'module' => 'domain_monitor',
    'action' => 'monitor',
    'name' => '域名监控',
    'enname' => '',
    'url' => '/domain_monitor/monitor',
    'extaction' => 'record,查看监控详情
edit,监控编辑
del,删除监控',
    'status' => '1',
    'isopen' => '0',
    'description' => '',
    'sort' => '2',
    'has_children' => '0',
    'level' => 1,
    'space' => '&nbsp;&nbsp;&nbsp;&nbsp;',
  ),
  95 => 
  array (
    'id' => '95',
    'pid' => '67',
    'type' => 'top',
    'module' => 'domain_manager',
    'action' => 'records',
    'name' => '域名解析记录',
    'enname' => '',
    'url' => '/domain_manager/records',
    'extaction' => 'check,记录审核',
    'status' => '1',
    'isopen' => '0',
    'description' => '',
    'sort' => '2',
    'has_children' => '0',
    'level' => 1,
    'space' => '&nbsp;&nbsp;&nbsp;&nbsp;',
  ),
  91 => 
  array (
    'id' => '91',
    'pid' => '67',
    'type' => 'top',
    'module' => 'domain_service',
    'action' => 'service_group',
    'name' => '域名服务套餐',
    'enname' => '',
    'url' => '/domain_service/service_group',
    'extaction' => 'refresh,刷新缓存
edit,新增编辑套餐
del,删除套餐
item,套餐设置项
line,套餐线路配置
showdetail,查看参数详情',
    'status' => '1',
    'isopen' => '0',
    'description' => '',
    'sort' => '5',
    'has_children' => '0',
    'level' => 1,
    'space' => '&nbsp;&nbsp;&nbsp;&nbsp;',
  ),
  98 => 
  array (
    'id' => '98',
    'pid' => '67',
    'type' => 'top',
    'module' => 'domain_manager',
    'action' => 'line',
    'name' => '域名服务线路',
    'enname' => '',
    'url' => '/domain_manager/line',
    'extaction' => 'aclip,IP添加查看导入
edit,域名线路修改
del,域名线路删除
refresh,线路刷新',
    'status' => '1',
    'isopen' => '0',
    'description' => '',
    'sort' => '6',
    'has_children' => '0',
    'level' => 1,
    'space' => '&nbsp;&nbsp;&nbsp;&nbsp;',
  ),
  92 => 
  array (
    'id' => '92',
    'pid' => '67',
    'type' => 'top',
    'module' => 'domain_service',
    'action' => 'ns_group',
    'name' => '域名服务器组',
    'enname' => '',
    'url' => '/domain_service/ns_group',
    'extaction' => 'btnopra,域名服务器刷新状态
monitor,监控服务器组
web,WEB服务器组
database,DB服务器组
edit,新增编辑NS服务器组
refresh,刷新NS服务器组
del,删除NS服务器组
query_log,查询日志
black_log,牵引日志
start_log,启动日志
update_dns,更新NS版本
reloadzone,重载ZONE
reloadisp,重载ISP
reloadcustom,重载CUST
restartline,重载线路
status,启动状态
start,启动
stop,停止
restart,重启
restartallline,重载所有线路',
    'status' => '1',
    'isopen' => '0',
    'description' => '',
    'sort' => '9',
    'has_children' => '0',
    'level' => 1,
    'space' => '&nbsp;&nbsp;&nbsp;&nbsp;',
  ),
  102 => 
  array (
    'id' => '102',
    'pid' => '0',
    'type' => 'top',
    'module' => 'domain_register',
    'action' => 'domain',
    'name' => '域名注册',
    'enname' => 'globe',
    'url' => '/domain_register/domain',
    'extaction' => '',
    'status' => '1',
    'isopen' => '0',
    'description' => '',
    'sort' => '2',
    'has_children' => '3',
    'level' => 0,
    'space' => '',
  ),
  103 => 
  array (
    'id' => '103',
    'pid' => '102',
    'type' => 'top',
    'module' => 'domain_register',
    'action' => 'domain',
    'name' => '注册域名',
    'enname' => '',
    'url' => '/domain_register/domain',
    'extaction' => 'info,域名注册详细信息
order,域名续费历史
ns_edit,域名NS修改
update_online,域名线上信息同步
renew,域名续费
log,操作日志所有
batch,注册域名批量操作
self_admin,域名自助管理平台
log_get,操作日志单个
rz_status,实名认证查询',
    'status' => '1',
    'isopen' => '0',
    'description' => '',
    'sort' => '1',
    'has_children' => '0',
    'level' => 1,
    'space' => '&nbsp;&nbsp;&nbsp;&nbsp;',
  ),
  104 => 
  array (
    'id' => '104',
    'pid' => '102',
    'type' => 'top',
    'module' => 'domain_register',
    'action' => 'price',
    'name' => '域名价格',
    'enname' => '',
    'url' => '/domain_register/price',
    'extaction' => 'cache_set,域名价格刷新缓存
edit,域名价格新增修改
del,域名价格删除
show,域名价格展示',
    'status' => '1',
    'isopen' => '0',
    'description' => '',
    'sort' => '2',
    'has_children' => '0',
    'level' => 1,
    'space' => '&nbsp;&nbsp;&nbsp;&nbsp;',
  ),
  107 => 
  array (
    'id' => '107',
    'pid' => '102',
    'type' => 'top',
    'module' => 'domain_register',
    'action' => 'template',
    'name' => '信息模板管理',
    'enname' => '',
    'url' => '/domain_register/template',
    'extaction' => 'sh,信息模板审核',
    'status' => '1',
    'isopen' => '0',
    'description' => '',
    'sort' => '3',
    'has_children' => '0',
    'level' => 1,
    'space' => '&nbsp;&nbsp;&nbsp;&nbsp;',
  ),
  109 => 
  array (
    'id' => '109',
    'pid' => '0',
    'type' => 'top',
    'module' => 'coupon_manager',
    'action' => 'coupon',
    'name' => '促销活动',
    'enname' => 'tag',
    'url' => '/coupon_manager/coupon',
    'extaction' => '',
    'status' => '1',
    'isopen' => '0',
    'description' => '',
    'sort' => '5',
    'has_children' => '4',
    'level' => 0,
    'space' => '',
  ),
  110 => 
  array (
    'id' => '110',
    'pid' => '109',
    'type' => 'top',
    'module' => 'coupon_manager',
    'action' => 'coupon',
    'name' => '代金劵',
    'enname' => '',
    'url' => '/coupon_manager/coupon',
    'extaction' => '',
    'status' => '1',
    'isopen' => '0',
    'description' => '',
    'sort' => '0',
    'has_children' => '0',
    'level' => 1,
    'space' => '&nbsp;&nbsp;&nbsp;&nbsp;',
  ),
  55 => 
  array (
    'id' => '55',
    'pid' => '109',
    'type' => 'top',
    'module' => 'user_manager',
    'action' => 'account_active',
    'name' => '活动配置',
    'enname' => '',
    'url' => '/user_manager/account_active',
    'extaction' => 'del,删除会员账户
edit,编辑会员账户
add,增加会员账户
makecache,会员账户生成缓存',
    'status' => '1',
    'isopen' => '0',
    'description' => '',
    'sort' => '1',
    'has_children' => '0',
    'level' => 1,
    'space' => '&nbsp;&nbsp;&nbsp;&nbsp;',
  ),
  113 => 
  array (
    'id' => '113',
    'pid' => '109',
    'type' => 'top',
    'module' => 'coupon_manager',
    'action' => 'email',
    'name' => '邮件模板',
    'enname' => '',
    'url' => '/coupon_manager/email',
    'extaction' => '',
    'status' => '1',
    'isopen' => '0',
    'description' => '',
    'sort' => '2',
    'has_children' => '0',
    'level' => 1,
    'space' => '&nbsp;&nbsp;&nbsp;&nbsp;',
  ),
  114 => 
  array (
    'id' => '114',
    'pid' => '109',
    'type' => 'top',
    'module' => 'coupon_manager',
    'action' => 'email_set',
    'name' => '发送配置',
    'enname' => '',
    'url' => '/coupon_manager/email_set',
    'extaction' => '',
    'status' => '1',
    'isopen' => '0',
    'description' => '',
    'sort' => '3',
    'has_children' => '0',
    'level' => 1,
    'space' => '&nbsp;&nbsp;&nbsp;&nbsp;',
  ),
  115 => 
  array (
    'id' => '115',
    'pid' => '0',
    'type' => 'top',
    'module' => 'tg_manager',
    'action' => 'index',
    'name' => '推广中心',
    'enname' => 'th-list',
    'url' => '/tg_manager/index',
    'extaction' => '',
    'status' => '1',
    'isopen' => '0',
    'description' => '',
    'sort' => '6',
    'has_children' => '2',
    'level' => 0,
    'space' => '',
  ),
  116 => 
  array (
    'id' => '116',
    'pid' => '115',
    'type' => 'top',
    'module' => 'tg_manager',
    'action' => 'tg_user',
    'name' => '推广员',
    'enname' => '',
    'url' => '/tg_manager/tg_user',
    'extaction' => 'check,审核',
    'status' => '1',
    'isopen' => '0',
    'description' => '',
    'sort' => '1',
    'has_children' => '0',
    'level' => 1,
    'space' => '&nbsp;&nbsp;&nbsp;&nbsp;',
  ),
  117 => 
  array (
    'id' => '117',
    'pid' => '115',
    'type' => 'top',
    'module' => 'tg_manager',
    'action' => 'tg',
    'name' => '下级推广员',
    'enname' => '',
    'url' => '/tg_manager/tg',
    'extaction' => '',
    'status' => '1',
    'isopen' => '0',
    'description' => '',
    'sort' => '2',
    'has_children' => '0',
    'level' => 1,
    'space' => '&nbsp;&nbsp;&nbsp;&nbsp;',
  ),
  76 => 
  array (
    'id' => '76',
    'pid' => '0',
    'type' => 'top',
    'module' => 'order_manager',
    'action' => 'index',
    'name' => '订单/资金',
    'enname' => 'shopping-cart',
    'url' => '/order_manager/index',
    'extaction' => '',
    'status' => '1',
    'isopen' => '0',
    'description' => '',
    'sort' => '9',
    'has_children' => '6',
    'level' => 0,
    'space' => '',
  ),
  77 => 
  array (
    'id' => '77',
    'pid' => '76',
    'type' => 'top',
    'module' => 'order_manager',
    'action' => 'recharge',
    'name' => '充值订单',
    'enname' => '',
    'url' => '/order_manager/recharge',
    'extaction' => 'add,新增充值
sh,审核
kaipiao,开票
action,充值订单',
    'status' => '1',
    'isopen' => '0',
    'description' => '',
    'sort' => '1',
    'has_children' => '0',
    'level' => 1,
    'space' => '&nbsp;&nbsp;&nbsp;&nbsp;',
  ),
  81 => 
  array (
    'id' => '81',
    'pid' => '76',
    'type' => 'top',
    'module' => 'order_manager',
    'action' => 'resume',
    'name' => '提现订单',
    'enname' => '',
    'url' => '/order_manager/withdraw',
    'extaction' => '',
    'status' => '0',
    'isopen' => '0',
    'description' => '',
    'sort' => '2',
    'has_children' => '0',
    'level' => 1,
    'space' => '&nbsp;&nbsp;&nbsp;&nbsp;',
  ),
  84 => 
  array (
    'id' => '84',
    'pid' => '76',
    'type' => 'top',
    'module' => 'finance_manager',
    'action' => 'dongjie',
    'name' => '冻结资金',
    'enname' => '',
    'url' => '/finance_manager/dongjie',
    'extaction' => '',
    'status' => '0',
    'isopen' => '0',
    'description' => '',
    'sort' => '91',
    'has_children' => '0',
    'level' => 1,
    'space' => '&nbsp;&nbsp;&nbsp;&nbsp;',
  ),
  111 => 
  array (
    'id' => '111',
    'pid' => '76',
    'type' => 'top',
    'module' => 'order_manager',
    'action' => 'parser',
    'name' => '域名解析订单',
    'enname' => '',
    'url' => '/order_manager/parser',
    'extaction' => '',
    'status' => '1',
    'isopen' => '0',
    'description' => '',
    'sort' => '91',
    'has_children' => '0',
    'level' => 1,
    'space' => '&nbsp;&nbsp;&nbsp;&nbsp;',
  ),
  112 => 
  array (
    'id' => '112',
    'pid' => '76',
    'type' => 'top',
    'module' => 'order_manager',
    'action' => 'register',
    'name' => '域名注册订单',
    'enname' => '',
    'url' => '/order_manager/register',
    'extaction' => '',
    'status' => '1',
    'isopen' => '0',
    'description' => '',
    'sort' => '91',
    'has_children' => '0',
    'level' => 1,
    'space' => '&nbsp;&nbsp;&nbsp;&nbsp;',
  ),
  58 => 
  array (
    'id' => '58',
    'pid' => '76',
    'type' => 'top',
    'module' => 'user_manager',
    'action' => 'user_accountlog',
    'name' => '资金流水',
    'enname' => '',
    'url' => '/user_manager/user_accountlog',
    'extaction' => '',
    'status' => '1',
    'isopen' => '0',
    'description' => '',
    'sort' => '99',
    'has_children' => '0',
    'level' => 1,
    'space' => '&nbsp;&nbsp;&nbsp;&nbsp;',
  ),
  2 => 
  array (
    'id' => '2',
    'pid' => '0',
    'type' => 'top',
    'module' => 'user_manager',
    'action' => 'userlist',
    'name' => '会员管理',
    'enname' => 'user',
    'url' => '/user_manager/userlist',
    'extaction' => '',
    'status' => '1',
    'isopen' => '0',
    'description' => '',
    'sort' => '70',
    'has_children' => '7',
    'level' => 0,
    'space' => '',
  ),
  35 => 
  array (
    'id' => '35',
    'pid' => '2',
    'type' => 'top',
    'module' => 'user_manager',
    'action' => 'userlist',
    'name' => '会员管理',
    'enname' => '',
    'url' => '/user_manager/userlist',
    'extaction' => 'quicklogin,快速登录
get,选择用户操作
edit,会员添加修改
refresh,刷新会员缓存
del,删除会员
recharge,会员账户管理
setting,会员基础设置
send_email,自定义发送邮件',
    'status' => '1',
    'isopen' => '0',
    'description' => '',
    'sort' => '0',
    'has_children' => '0',
    'level' => 1,
    'space' => '&nbsp;&nbsp;&nbsp;&nbsp;',
  ),
  105 => 
  array (
    'id' => '105',
    'pid' => '2',
    'type' => 'top',
    'module' => 'user_manager',
    'action' => 'userlist_com',
    'name' => '企业管理',
    'enname' => '',
    'url' => '/user_manager/userlist_com',
    'extaction' => 'edit,企业会员认证审核',
    'status' => '1',
    'isopen' => '0',
    'description' => '',
    'sort' => '0',
    'has_children' => '0',
    'level' => 1,
    'space' => '&nbsp;&nbsp;&nbsp;&nbsp;',
  ),
  8 => 
  array (
    'id' => '8',
    'pid' => '2',
    'type' => 'top',
    'module' => 'user_manager',
    'action' => 'userrole',
    'name' => '会员角色',
    'enname' => '',
    'url' => '/user_manager/userrole',
    'extaction' => 'edit,会员角色新增修改
del,会员角色删除',
    'status' => '1',
    'isopen' => '0',
    'description' => '',
    'sort' => '9',
    'has_children' => '0',
    'level' => 1,
    'space' => '&nbsp;&nbsp;&nbsp;&nbsp;',
  ),
  54 => 
  array (
    'id' => '54',
    'pid' => '2',
    'type' => 'top',
    'module' => 'user_manager',
    'action' => 'utype_set',
    'name' => '会员类型',
    'enname' => '',
    'url' => '/user_manager/utype_set',
    'extaction' => 'add,新增用户类型
edit,编辑用户类型
del,删除用户类型',
    'status' => '1',
    'isopen' => '0',
    'description' => '',
    'sort' => '10',
    'has_children' => '0',
    'level' => 1,
    'space' => '&nbsp;&nbsp;&nbsp;&nbsp;',
  ),
  57 => 
  array (
    'id' => '57',
    'pid' => '2',
    'type' => 'top',
    'module' => 'user_manager',
    'action' => 'ulevel_set',
    'name' => '会员等级配置',
    'enname' => '',
    'url' => '/user_manager/ulevel_set',
    'extaction' => 'del,删除会员等级
edit,编辑会员等级
add,增加会员等级
edit_data,会员等级设置项
makecache,会员等级生成缓存',
    'status' => '1',
    'isopen' => '0',
    'description' => '',
    'sort' => '11',
    'has_children' => '0',
    'level' => 1,
    'space' => '&nbsp;&nbsp;&nbsp;&nbsp;',
  ),
  10 => 
  array (
    'id' => '10',
    'pid' => '2',
    'type' => 'top',
    'module' => 'user_manager',
    'action' => 'loginlog',
    'name' => '会员登录日志',
    'enname' => '',
    'url' => '/user_manager/loginlog',
    'extaction' => 'refresh,会员日志刷新',
    'status' => '1',
    'isopen' => '0',
    'description' => '',
    'sort' => '98',
    'has_children' => '0',
    'level' => 1,
    'space' => '&nbsp;&nbsp;&nbsp;&nbsp;',
  ),
  9 => 
  array (
    'id' => '9',
    'pid' => '2',
    'type' => 'top',
    'module' => 'user_manager',
    'action' => 'userlog',
    'name' => '会员操作日志',
    'enname' => '',
    'url' => '/user_manager/userlog',
    'extaction' => 'refresh,会员登陆日志刷新',
    'status' => '1',
    'isopen' => '0',
    'description' => '',
    'sort' => '99',
    'has_children' => '0',
    'level' => 1,
    'space' => '&nbsp;&nbsp;&nbsp;&nbsp;',
  ),
  27 => 
  array (
    'id' => '27',
    'pid' => '0',
    'type' => 'top',
    'module' => 'goods_manager',
    'action' => 'goodslist',
    'name' => '图文管理',
    'enname' => 'phone',
    'url' => '/goods_manager/goodslist',
    'extaction' => '',
    'status' => '0',
    'isopen' => '0',
    'description' => '',
    'sort' => '70',
    'has_children' => '3',
    'level' => 0,
    'space' => '',
  ),
  28 => 
  array (
    'id' => '28',
    'pid' => '27',
    'type' => 'top',
    'module' => 'goods_manager',
    'action' => 'goodslist',
    'name' => '图文管理',
    'enname' => '',
    'url' => '/goods_manager/goodslist',
    'extaction' => '',
    'status' => '1',
    'isopen' => '0',
    'description' => '',
    'sort' => '1',
    'has_children' => '0',
    'level' => 1,
    'space' => '&nbsp;&nbsp;&nbsp;&nbsp;',
  ),
  29 => 
  array (
    'id' => '29',
    'pid' => '27',
    'type' => 'top',
    'module' => 'goods_manager',
    'action' => 'goodslist_cate',
    'name' => '图文分类',
    'enname' => '',
    'url' => '/goods_manager/goodslist_cate',
    'extaction' => '',
    'status' => '1',
    'isopen' => '0',
    'description' => '',
    'sort' => '6',
    'has_children' => '0',
    'level' => 1,
    'space' => '&nbsp;&nbsp;&nbsp;&nbsp;',
  ),
  30 => 
  array (
    'id' => '30',
    'pid' => '27',
    'type' => 'top',
    'module' => 'goods_manager',
    'action' => 'goodslist_model',
    'name' => '图文模型',
    'enname' => '',
    'url' => '/goods_manager/goodslist_model',
    'extaction' => 'edit,编辑
del,删除
attrspec,规格与属性',
    'status' => '1',
    'isopen' => '0',
    'description' => '',
    'sort' => '7',
    'has_children' => '0',
    'level' => 1,
    'space' => '&nbsp;&nbsp;&nbsp;&nbsp;',
  ),
  50 => 
  array (
    'id' => '50',
    'pid' => '0',
    'type' => 'top',
    'module' => 'photos_manager',
    'action' => 'index',
    'name' => '图片管理',
    'enname' => 'picture',
    'url' => '/photos_manager/index',
    'extaction' => '',
    'status' => '0',
    'isopen' => '0',
    'description' => '',
    'sort' => '76',
    'has_children' => '3',
    'level' => 0,
    'space' => '',
  ),
  52 => 
  array (
    'id' => '52',
    'pid' => '50',
    'type' => 'top',
    'module' => 'photos_manager',
    'action' => 'photoslist',
    'name' => '图片集',
    'enname' => 'attach',
    'url' => '/photos_manager/photoslist',
    'extaction' => '',
    'status' => '1',
    'isopen' => '0',
    'description' => '',
    'sort' => '0',
    'has_children' => '0',
    'level' => 1,
    'space' => '&nbsp;&nbsp;&nbsp;&nbsp;',
  ),
  53 => 
  array (
    'id' => '53',
    'pid' => '50',
    'type' => 'top',
    'module' => 'photos_manager',
    'action' => 'photoslist_attach',
    'name' => '图片管理',
    'enname' => '',
    'url' => '/photos_manager/photoslist_attach',
    'extaction' => '',
    'status' => '1',
    'isopen' => '0',
    'description' => '',
    'sort' => '1',
    'has_children' => '0',
    'level' => 1,
    'space' => '&nbsp;&nbsp;&nbsp;&nbsp;',
  ),
  51 => 
  array (
    'id' => '51',
    'pid' => '50',
    'type' => 'top',
    'module' => 'photos_manager',
    'action' => 'photoslist_cate',
    'name' => '图片集分类',
    'enname' => '',
    'url' => '/photos_manager/photoslist_cate',
    'extaction' => '',
    'status' => '1',
    'isopen' => '0',
    'description' => '',
    'sort' => '81',
    'has_children' => '0',
    'level' => 1,
    'space' => '&nbsp;&nbsp;&nbsp;&nbsp;',
  ),
  4 => 
  array (
    'id' => '4',
    'pid' => '0',
    'type' => 'top',
    'module' => 'cms_manager',
    'action' => 'threads',
    'name' => '内容管理',
    'enname' => 'list',
    'url' => '/cms_manager/threads',
    'extaction' => '',
    'status' => '1',
    'isopen' => '0',
    'description' => '',
    'sort' => '78',
    'has_children' => '4',
    'level' => 0,
    'space' => '',
  ),
  5 => 
  array (
    'id' => '5',
    'pid' => '4',
    'type' => 'top',
    'module' => 'cms_manager',
    'action' => 'threads',
    'name' => '内容列表',
    'enname' => '',
    'url' => '/cms_manager/threads',
    'extaction' => 'forums,内容分类
edit,内容新增编辑
del,内容删除
forums_refresh,内容分类刷新
forums_edit,内容分类编辑添加
forums_copy,内容分类拷贝
forums_del,内容分类删除',
    'status' => '1',
    'isopen' => '0',
    'description' => '',
    'sort' => '0',
    'has_children' => '0',
    'level' => 1,
    'space' => '&nbsp;&nbsp;&nbsp;&nbsp;',
  ),
  11 => 
  array (
    'id' => '11',
    'pid' => '4',
    'type' => 'top',
    'module' => 'cms_manager',
    'action' => 'onepage',
    'name' => '单页列表',
    'enname' => '',
    'url' => '/cms_manager/onepage',
    'extaction' => 'cate,单页分类
edit,单页新增编辑
del,单页删除
cate_edit,单页分类编辑添加
cate_del,单页分类删除',
    'status' => '1',
    'isopen' => '0',
    'description' => '',
    'sort' => '1',
    'has_children' => '0',
    'level' => 1,
    'space' => '&nbsp;&nbsp;&nbsp;&nbsp;',
  ),
  12 => 
  array (
    'id' => '12',
    'pid' => '4',
    'type' => 'top',
    'module' => 'cms_manager',
    'action' => 'adlist',
    'name' => '广告列表',
    'enname' => '',
    'url' => '/cms_manager/adlist',
    'extaction' => 'cate,广告分类
edit,广告新增编辑
del,广告删除
cate_refresh,广告分类刷新
cate_edit,广告分类编辑添加
cate_del,广告分类删除',
    'status' => '1',
    'isopen' => '0',
    'description' => '',
    'sort' => '2',
    'has_children' => '0',
    'level' => 1,
    'space' => '&nbsp;&nbsp;&nbsp;&nbsp;',
  ),
  13 => 
  array (
    'id' => '13',
    'pid' => '4',
    'type' => 'top',
    'module' => 'cms_manager',
    'action' => 'friendlink',
    'name' => '链接管理',
    'enname' => '',
    'url' => '/cms_manager/friendlink',
    'extaction' => 'cate,链接管理分类
edit,链接管理新增编辑
del,链接管理删除
cate_refresh,链接管理分类刷新
cate_edit,链接管理分类编辑添加
cate_del,链接管理分类删除',
    'status' => '1',
    'isopen' => '0',
    'description' => '',
    'sort' => '3',
    'has_children' => '0',
    'level' => 1,
    'space' => '&nbsp;&nbsp;&nbsp;&nbsp;',
  ),
  6 => 
  array (
    'id' => '6',
    'pid' => '0',
    'type' => 'top',
    'module' => 'sys_manager',
    'action' => 'index',
    'name' => '系统管理',
    'enname' => 'cog',
    'url' => '/sys_manager/index',
    'extaction' => '',
    'status' => '1',
    'isopen' => '0',
    'description' => '',
    'sort' => '99',
    'has_children' => '8',
    'level' => 0,
    'space' => '',
  ),
  106 => 
  array (
    'id' => '106',
    'pid' => '6',
    'type' => 'top',
    'module' => 'sys_manager',
    'action' => 'index',
    'name' => '系统首页',
    'enname' => '',
    'url' => '/sys_manager/index',
    'extaction' => 'count,查看统计信息
serverstatus,查看服务器状态信息
beanstalk,查看消息队列
memcache,查看memcache
import,老版本数据导入
online,查看在线会员
batchrash,批量刷新域名
weixinport,微信菜单生成
mac_query,服务器状态统计查询
find_ask,DNS服务器请求统计图
sys_count_image,后台首页会员统计',
    'status' => '1',
    'isopen' => '0',
    'description' => '',
    'sort' => '0',
    'has_children' => '0',
    'level' => 1,
    'space' => '&nbsp;&nbsp;&nbsp;&nbsp;',
  ),
  14 => 
  array (
    'id' => '14',
    'pid' => '6',
    'type' => 'top',
    'module' => 'sys_manager',
    'action' => 'sys_config',
    'name' => '系统配置',
    'enname' => '',
    'url' => '/sys_manager/sys_config',
    'extaction' => 'save,系统配置保存',
    'status' => '1',
    'isopen' => '0',
    'description' => '',
    'sort' => '1',
    'has_children' => '0',
    'level' => 1,
    'space' => '&nbsp;&nbsp;&nbsp;&nbsp;',
  ),
  15 => 
  array (
    'id' => '15',
    'pid' => '6',
    'type' => 'top',
    'module' => 'sys_manager',
    'action' => 'data_config',
    'name' => '基础数据配置',
    'enname' => '',
    'url' => '/sys_manager/data_config',
    'extaction' => 'dns_version,DNS版本
suport_domain,支持域名
RRtype,记录类型
domain_group,域名固定分组
service_num,套餐购买时间
scan_host,扫描主机
register_domain,域名注册类型
domain_agent,域名注册代理商',
    'status' => '1',
    'isopen' => '0',
    'description' => '',
    'sort' => '1',
    'has_children' => '0',
    'level' => 1,
    'space' => '&nbsp;&nbsp;&nbsp;&nbsp;',
  ),
  64 => 
  array (
    'id' => '64',
    'pid' => '6',
    'type' => 'top',
    'module' => 'sys_manager',
    'action' => 'area_config',
    'name' => '地区数据',
    'enname' => 'area',
    'url' => '/sys_manager/area_config',
    'extaction' => '',
    'status' => '0',
    'isopen' => '0',
    'description' => '',
    'sort' => '2',
    'has_children' => '0',
    'level' => 1,
    'space' => '&nbsp;&nbsp;&nbsp;&nbsp;',
  ),
  63 => 
  array (
    'id' => '63',
    'pid' => '6',
    'type' => 'top',
    'module' => 'sys_manager',
    'action' => 'city_config',
    'name' => '城市站点数据',
    'enname' => 'city',
    'url' => '/sys_manager/city_config',
    'extaction' => '',
    'status' => '0',
    'isopen' => '0',
    'description' => '',
    'sort' => '3',
    'has_children' => '0',
    'level' => 1,
    'space' => '&nbsp;&nbsp;&nbsp;&nbsp;',
  ),
  59 => 
  array (
    'id' => '59',
    'pid' => '6',
    'type' => 'top',
    'module' => 'sys_manager',
    'action' => 'sys_payment',
    'name' => '支付工具',
    'enname' => 'pay',
    'url' => '/sys_manager/sys_payment',
    'extaction' => 'edit,支付方式编辑
del,支付方式删除',
    'status' => '1',
    'isopen' => '0',
    'description' => '',
    'sort' => '9',
    'has_children' => '0',
    'level' => 1,
    'space' => '&nbsp;&nbsp;&nbsp;&nbsp;',
  ),
  85 => 
  array (
    'id' => '85',
    'pid' => '6',
    'type' => 'top',
    'module' => 'sys_manager',
    'action' => 'sys_sms',
    'name' => '系统短信',
    'enname' => '',
    'url' => '/sys_manager/sys_sms',
    'extaction' => 'refresh,系统短信刷新',
    'status' => '1',
    'isopen' => '0',
    'description' => '',
    'sort' => '80',
    'has_children' => '0',
    'level' => 1,
    'space' => '&nbsp;&nbsp;&nbsp;&nbsp;',
  ),
  86 => 
  array (
    'id' => '86',
    'pid' => '6',
    'type' => 'top',
    'module' => 'sys_manager',
    'action' => 'sys_email',
    'name' => '系统邮件',
    'enname' => '',
    'url' => '/sys_manager/sys_email',
    'extaction' => '',
    'status' => '1',
    'isopen' => '0',
    'description' => '',
    'sort' => '82',
    'has_children' => '0',
    'level' => 1,
    'space' => '&nbsp;&nbsp;&nbsp;&nbsp;',
  ),
);
?>