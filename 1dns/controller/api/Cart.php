<?php
/**
 * 登录
 * by Thinkhu 2014
 */
class Cart extends API{
    public function __construct(){
        parent::__construct('Cart');
    }
    //加入购物车
    public function Add(){
        global $uid,$timestamp;
        
        //验证企业用户是否已经审核成功
        $u = C("user")->get_cache_userinfo($uid);
        if ($u['utype'] == 2) {
            if ($u['ulevel'] == 0) {
                $this->respons_error("您未完成资料认证审核，请认证后操作!");
            }
        }

        $type  = R("type","int");
        if(!in_array($type,array(0,1,2))){
            $this->respons_error("加入购物车类型出错!");
        }
        $data = array(
            'uid'						=> $uid,
            'type'					=> $type,
            'goods_no'			=> R("service_group","string"),//服务套餐等级,短信套餐
            'goods_name' 		=> R("domain","string"),//域名
            'price'	   				=> R("price","string"),	//套餐价格
            'dateline'	   			=> $timestamp,	//添加时间
            'status'	   				=> 0,//购物车状态，0添加，1完成
            'num'	   				=> R("num","int"),	//套餐价格
        );

        if($type == 0){//购买域名套餐
                if (!tValidate::is_domain($data['goods_name'])) {
                    $this->respons_error("请选择域名!");
                }
                if (empty($data['goods_no'])) {
                    $this->respons_error("请选择服务套餐!");
                }
                //判断企业个人选择套餐的合法性
                $service_groups = M("@domain_service")->get_cache_list($this->userinfo['utype'] );

               if(empty($service_groups) || !in_array($data['goods_no'],array_keys($service_groups)) ){
                   $this->respons_error("请选择相应会员类型的服务套餐!");
               }

                if (empty($data['num'])) {
                    $this->respons_error("服务套餐时间最少一个月!");
                }
               $row = M("@cart")->get_row("indel=0 AND status=0 AND goods_name='{$data['goods_name']}' AND uid='{$uid}'");
                if(isset($row['cart_id'])){
                    if($row['goods_no'] ==$data['goods_no']){
                        $p_num = $row['num']+$data['num'];

                        if ($this->userinfo['utype'] == 1) {//个人最多1年
                            if ($p_num > 10) {
                                $this->respons_error("个人用户套餐时间最多1年!");
                            }
                        }elseif ($this->userinfo['utype'] == 2) {//企业最多五年
                            if ($p_num > 50) {
                                $this->respons_error("企业用户套餐时间最多五年!");
                            }
                        }

                        $updata = array(
                            "num" => $p_num,
                            "dateline"=>$timestamp,
                        );
                        $ret = M("cart")->set_data($updata)->update("cart_id='{$row['cart_id']}'");
                    }else{
                        $updata = array(
                            "num" => $data['num'],
                            "price" => $data['price'],
                            "goods_no" => $data['goods_no'],
                            "dateline"=>$timestamp,
                        );
                        $ret = M("cart")->set_data($updata)->update("cart_id='{$row['cart_id']}'");
                    }
                }else{
                    $ret = M("cart")->set_data($data)->add();
                }

        }elseif ($type == 1) { // 购买短信服务套餐
            if (empty($data['goods_no'])) {
                $this->respons_error("请选择增值服务套餐!");
            }
            if (empty($data['num'])) {
                $this->respons_error("请选择服务套餐数量!");
            }
            $row = M("@cart")->get_row("indel=0 AND status=0 AND goods_no='{$data['goods_no']}' AND uid='{$uid}'");
            if(isset($row['cart_id'])){ //已存在，仅数量+1
                $p_num = $row['num']+$data['num'];
                if ($p_num > 10) {
                    $this->respons_error("购买数量超出，请付款后重新购买!");
                }
                $updata = array(
                    "num" => $p_num,
                    "dateline"=>$timestamp,
                );
                $ret = M("cart")->set_data($updata)->update("cart_id='{$row['cart_id']}'");
            }else{ //不存在，直接添加购物车
                $ret = M("cart")->set_data($data)->add();
            }
        }
        if ($ret) {
            $this->respons_success("加入购物车成功");
        }else{
            $this->respons_error("加入购物车失败");
        }
    }
    //修改购物车
    public function Edit(){
        $cart_id = R("cart_id","int");
        $num = R("num","int");
        $cart_row = M("@cart")->get($cart_id);
        if(!isset($cart_row['cart_id']) || $cart_row['indel'] == 1 || $cart_row['status'] == 1) {
            $this->respons_error("购物车数据未找到!");
        }
        if($cart_row['type'] == 0){
            if($num <= 0){
                $this->respons_error("购买时长必须选择");
            }
            $ret = M("cart")->set_data(array("num"=>$num))->update("cart_id='{$cart_id}'");
        }elseif ($cart_row['type'] == 1) {
            if($num <= 0){
                $this->respons_error("购买数量必须选择");
            }
            $ret = M("cart")->set_data(array("num"=>$num))->update("cart_id='{$cart_id}'");
        }
        if ($ret) {
            $this->respons_success("修改购物车成功");
        }else{
            $this->respons_error("修改购物车失败");
        }
    }
    //删除订单
    public function Del(){
        global $uid;

        $goods_name  = R("goods_name","string");
        $type               = R("type","int");
        $cart_id            = R("cart_id","int");
        if (!in_array($type,array(0,1,2))) {
            $this->respons_error("删除失败，类型不存在");
        }
        if ($type == 0) { //域名套餐删除
            if (empty($goods_name)) {
                $this->respons_error("请选择域名");
            }
            $rst = M("cart")->set_data(array("indel"=>1))->update("goods_name ='{$goods_name}' and uid={$uid} and type={$type} and indel=0");
        }elseif ($type == 1){ //增值服务删除
            if (empty($cart_id)) {
                $this->respons_error("请选择增值服务");
            }
            $rst = M("cart")->set_data(array("indel"=>1))->update("cart_id ='{$cart_id}'");
        }

        if ($rst) {
            $this->respons_success("删除成功");
        } else {
            $this->respons_error("删除失败");
        }
    }
}