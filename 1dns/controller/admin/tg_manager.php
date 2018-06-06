<?php
/**
 * 推广管理
 * by py 2017
 */
class tg_manager extends UCAdmin{
	function __construct(){
		parent::__construct('tg_manager');
	}
	//推广管理
	public function tg_user(){
		//管理员登录检查
		$do = R("do");
		switch ($do) {
			case "get_url":
			case "get":
				$return = array();
				$page = R("page", "int");
				$keyword = R("keyword", "string");
				$uid = R("uid", "int");
				$status = R("status", "int");
				$size = R("size", "int");
				$size = $size ? $size : 30;
				$pageurl = U("/tg_manager/tg_user?do=get&status={$status}&uid={$uid}&keyword").$keyword;
				if ($do == "get_url") {
					$result = array("keyword" => $keyword, "uid" => $uid, "status" => $status);
					$result['pageurl'] = $pageurl;
					$result['error'] = 0;
					$result['message'] = "处理成功！";
					tAjax::json($result);
				}
				$where = "1"  . ($uid ? " AND user_id = '{$uid}'" : "");
				$where .= $keyword?" AND (name like '%{$keyword}%' OR mobile like '%{$keyword}%' OR email like '%{$keyword}%') ":"";
				$where .= $status?" AND status = '{$status}'":"";
				$query = new tQuery('tg_user');
				$query->where = $where;
				$query->pagesize = $size;
				$query->page = $page;
				$query->fields = "*";
				$query->order = "id desc";
				$return['list'] = $query->find();
				$return['pagebar'] = $query->get_pagebar($pageurl);
				$return['pagebar'] = tFun::pagebar_js($return['pagebar'], $pageurl, "load_list", array());
				foreach ($return['list'] as $k => $v) {
				}
				tAjax::json($return);
				break;
			default:
				$this->assign("pageurl", U("/tg_manager/tg_user?do=get&keyword="));
				$this->display();
				break;
		}
	}
	//ip审核
	public  function tg_user_check(){
		$status     = R("status","int");
		$inlock     = R("inlock","int");
		$up_mail     = R("up_mail","int");
		$id  = R("id","int");

		$record_row = M("tg_user")->get_row("id = '{$id}'");
		if (!isset($record_row['id'])) {
			tAjax::json_error("用户不存在！");
		}
		if (tUtil::check_hash()) {
			//更改记录状态
			$data = array(
				"status"=>$status,
				"inlock"=>$inlock,
				"urate"=>R("urate","int"),
				"urate1"=>R("urate1","int"),
			);
			M("tg_user")->set_data($data)->update("id = '$id'");
			//发送邮件
			if ($up_mail == 1) {
				if ($record_row['email'] && $status == 3) {
					C("user")->send_meail_usual($record_row['email'],"推广用户审核成功","感谢您成为八戒DNS的推广员,您提交的资料已经通过我们的审核！");
				}
			}
			tAjax::json(array("error"=>0,"message"=>"操作成功！","callback"=>"close"),"json");
		}else{
			$record_row['status'] = 3;
			$is_hide = ($record_row['pay_type'] == 1)?"style='display:none;'":"";
			$arr = array("1"=>"支付宝(alipay)","2"=>"农业银行(ABC)","3"=>"工商银行(ICBC)","4"=>"招商银行","5"=>"建设银行");
			$html = "<ul style='line-height: 20px;'>
								<li><span style='color: gray'>联系人</span>：{$record_row['name']}</li>
								<li><span style='color: gray'>联系电话</span>：{$record_row['mobile']}</li>
								<li><span style='color: gray'>邮箱</span>：{$record_row['email']}</li>
								<li><span style='color: gray'>结算方式</span>：{$arr[$record_row['pay_type']]}</li>
								<li><span style='color: gray'>结算账号</span>：{$record_row['pay_no']}</li>
								<li {$is_hide}><span style='color: gray'>开户行</span>：{$record_row['pay_bank']}</li>
							</ul>";
			$record_row['info'] = $html;
			tAjax::json($record_row);
		}
	}
	//下级推广员
	public function tg(){
		//管理员登录检查
		$do = R("do");
		switch ($do) {
			case "get_url":
			case "get":
				$return = array();
				$page = R("page", "int");
				$keyword = R("keyword", "string");
				$uid = R("uid", "int");
				$size = R("size", "int");
				$size = $size ? $size : 30;
				$pageurl = U("/tg_manager/tg?do=get&uid={$uid}&keyword=").$keyword;
				if ($do == "get_url") {
					$result = array("keyword" => $keyword, "uid" => $uid);
					$result['pageurl'] = $pageurl;
					$result['error'] = 0;
					$result['message'] = "处理成功！";
					tAjax::json($result);
				}
				$where = "1"  . ($uid ? " AND myid = {$uid}" : "");
				if ($keyword) {
					$myid = M("user")->get_one("uname like '%{$keyword}%' OR mobile like '%{$keyword}%' OR email like '%{$keyword}%'","uid");
					$where .= " AND myid = {$myid}";
				}
				$query = new tQuery('tg');
				$query->where = $where;
				$query->pagesize = $size;
				$query->page = $page;
				$query->fields = "*";
				$query->order = "id desc";
				$return['list'] = $query->find();
				$return['pagebar'] = $query->get_pagebar($pageurl);
				$return['pagebar'] = tFun::pagebar_js($return['pagebar'], $pageurl, "load_list", array());
				foreach ($return['list'] as $k => $v) {
					//用户
					$tmp = C("user")->get_cache_userinfo($v['myid']);
					$from_tmp = C("user")->get_cache_userinfo($v['fromid']);
					$return['list'][$k]['up_name'] = $from_tmp['uname'] ? $from_tmp['uname'] : '-';
					$return['list'][$k]['name'] = $tmp['uname'] ? $tmp['uname'] : '-';
					$return['list'][$k]['email'] = $tmp['email'] ? $tmp['email'] : '-';
					$return['list'][$k]['mobile'] = $tmp['mobile'] ? $tmp['mobile'] : '-';
				}
				tAjax::json($return);
				break;
			default:
				$this->assign("pageurl", U("/tg_manager/tg?do=get"));
				$this->display();
				break;
		}
	}
}
?>