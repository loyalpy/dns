var dataConfig ={},dataConfig_A = {};
dataConfig['isp'] = {"1":"\u7535\u4fe1","2":"\u8054\u901a","3":"\u4e16\u5bfc","4":"\u7535\u4fe1\/\u8054\u901a\/\u53cc\u7ebf"};
dataConfig_A['isp'] = [];
dataConfig_A['isp'].push({v:'电信',key:'1'});
dataConfig_A['isp'].push({v:'联通',key:'2'});
dataConfig_A['isp'].push({v:'世导',key:'3'});
dataConfig_A['isp'].push({v:'电信/联通/双线',key:'4'});

dataConfig['depart'] = {"1":"\u529e\u516c\u5ba4","2":"\u4e1a\u52a1\u90e8","3":"\u5ba2\u670d\u90e8","5":"\u6280\u672f\u90e8","6":"\u8d22\u52a1\u90e8","9":"\u4ee3\u7406\u90e8","11":"\u652f\u6491\u90e8"};
dataConfig_A['depart'] = [];
dataConfig_A['depart'].push({v:'办公室',key:'1'});
dataConfig_A['depart'].push({v:'业务部',key:'2'});
dataConfig_A['depart'].push({v:'客服部',key:'3'});
dataConfig_A['depart'].push({v:'技术部',key:'5'});
dataConfig_A['depart'].push({v:'财务部',key:'6'});
dataConfig_A['depart'].push({v:'代理部',key:'9'});
dataConfig_A['depart'].push({v:'支撑部',key:'11'});

dataConfig['post'] = {"1":"\u603b\u7ecf\u7406","2":"\u7ecf\u7406","3":"\u4e3b\u7ba1","4":"\u4e1a\u52a1","5":"\u8d22\u52a1","6":"\u8fd0\u7ef4","7":"\u5ba2\u670d"};
dataConfig_A['post'] = [];
dataConfig_A['post'].push({v:'总经理',key:'1'});
dataConfig_A['post'].push({v:'经理',key:'2'});
dataConfig_A['post'].push({v:'主管',key:'3'});
dataConfig_A['post'].push({v:'业务',key:'4'});
dataConfig_A['post'].push({v:'财务',key:'5'});
dataConfig_A['post'].push({v:'运维',key:'6'});
dataConfig_A['post'].push({v:'客服',key:'7'});

dataConfig['work_tp'] = {"up":"\u4e0a\u67b6\u5355","wh":"\u7ef4\u62a4\u5355","re":"\u56de\u6536\u5355","dw":"\u4e0b\u67b6\u5355"};
dataConfig_A['work_tp'] = [];
dataConfig_A['work_tp'].push({v:'上架单',key:'up'});
dataConfig_A['work_tp'].push({v:'维护单',key:'wh'});
dataConfig_A['work_tp'].push({v:'回收单',key:'re'});
dataConfig_A['work_tp'].push({v:'下架单',key:'dw'});

dataConfig['work_st'] = {"1":"\u5f85\u5904\u7406","2":"\u5904\u7406\u4e2d","3":"\u5f85\u56de\u8bbf","4":"\u5df2\u7ed3\u675f"};
dataConfig_A['work_st'] = [];
dataConfig_A['work_st'].push({v:'待处理',key:'1'});
dataConfig_A['work_st'].push({v:'处理中',key:'2'});
dataConfig_A['work_st'].push({v:'待回访',key:'3'});
dataConfig_A['work_st'].push({v:'已结束',key:'4'});

dataConfig['work_lv'] = {"1":"\u666e\u901a","8":"\u91cd\u8981","9":"\u7d27\u6025"};
dataConfig_A['work_lv'] = [];
dataConfig_A['work_lv'].push({v:'普通',key:'1'});
dataConfig_A['work_lv'].push({v:'重要',key:'8'});
dataConfig_A['work_lv'].push({v:'紧急',key:'9'});

dataConfig['finance_kp'] = {"1":"\u5f00\u53d1\u7968\uff08\u672a\u5f00\uff09","2":"\u5f00\u53d1\u7968\uff08\u5df2\u5f00\uff09","3":"\u4e0d\u5f00\u7968"};
dataConfig_A['finance_kp'] = [];
dataConfig_A['finance_kp'].push({v:'开发票（未开）',key:'1'});
dataConfig_A['finance_kp'].push({v:'开发票（已开）',key:'2'});
dataConfig_A['finance_kp'].push({v:'不开票',key:'3'});

dataConfig['finance_js'] = {"1":"\u6302\u53f7","2":"\u5feb\u9012","3":"\u5176\u4ed6\u65b9\u5f0f"};
dataConfig_A['finance_js'] = [];
dataConfig_A['finance_js'].push({v:'挂号',key:'1'});
dataConfig_A['finance_js'].push({v:'快递',key:'2'});
dataConfig_A['finance_js'].push({v:'其他方式',key:'3'});

dataConfig['idc'] = {"1":{"name":"\u6e56\u5dde\u7535\u4fe1\u673a\u623f","code":1,"sort":0,"isp":4,"address":"\u9752\u6751\u8def23\u53f7","workaddr":"http:\/\/61.153.104.133:6099\/","fireaddr":"http:\/\/122.225.102.125:2007","jfaddr":"","unlockaddr":"","dbhost":"117.27.227.100:3306","dbuser":"ddoslog","dbpwd":"slwjp888","dbname":"qy","dbtablepre":"t_","dbtype":"mysql","area":"33050300"},"2":{"name":"\u798f\u5dde\u8054\u901a","code":2,"sort":0,"isp":4,"address":"\u5357\u4eac\u8def93\u53f7","workaddr":"http:\/\/61.153.104.133:6099\/","fireaddr":"","jfaddr":"","unlockaddr":"","dbhost":"","dbuser":"","dbpwd":"","dbname":"","dbtablepre":"","dbtype":"mysql","area":"33050000"},"5":{"name":"\u5609\u5174\u673a\u623f","code":5,"sort":0,"isp":1,"address":"","workaddr":"http:\/\/61.153.104.133:6099\/","fireaddr":"http:\/\/122.225.102.125:2007","jfaddr":"","unlockaddr":"","dbhost":"117.27.227.100:3306","dbuser":"ddoslog","dbpwd":"slwjp888","dbname":"qy","dbtablepre":"t_","dbtype":"mysql","area":"33010000"}};
dataConfig_A['idc'] = [];
dataConfig_A['idc'].push({v:'湖州电信机房',a:{"name":"\u6e56\u5dde\u7535\u4fe1\u673a\u623f","code":1,"sort":0,"isp":4,"address":"\u9752\u6751\u8def23\u53f7","workaddr":"http:\/\/61.153.104.133:6099\/","fireaddr":"http:\/\/122.225.102.125:2007","jfaddr":"","unlockaddr":"","dbhost":"117.27.227.100:3306","dbuser":"ddoslog","dbpwd":"slwjp888","dbname":"qy","dbtablepre":"t_","dbtype":"mysql","area":"33050300"},key:'1'});
dataConfig_A['idc'].push({v:'福州联通',a:{"name":"\u798f\u5dde\u8054\u901a","code":2,"sort":0,"isp":4,"address":"\u5357\u4eac\u8def93\u53f7","workaddr":"http:\/\/61.153.104.133:6099\/","fireaddr":"","jfaddr":"","unlockaddr":"","dbhost":"","dbuser":"","dbpwd":"","dbname":"","dbtablepre":"","dbtype":"mysql","area":"33050000"},key:'2'});
dataConfig_A['idc'].push({v:'嘉兴机房',a:{"name":"\u5609\u5174\u673a\u623f","code":5,"sort":0,"isp":1,"address":"","workaddr":"http:\/\/61.153.104.133:6099\/","fireaddr":"http:\/\/122.225.102.125:2007","jfaddr":"","unlockaddr":"","dbhost":"117.27.227.100:3306","dbuser":"ddoslog","dbpwd":"slwjp888","dbname":"qy","dbtablepre":"t_","dbtype":"mysql","area":"33010000"},key:'5'});

dataConfig['stock_outin'] = {"1":"\u670d\u52a1\u5668\u65b0(\u51fa\u5e93)","2":"\u670d\u52a1\u5668\u66ff\u6362(\u51fa\u5e93)","3":"\u7f51\u7edc\u8bbe\u5907(\u51fa\u5e93)","4":"\u56de\u5bc4\u5ba2\u6237(\u51fa\u5e93)","11":"\u9000\u56de(\u5165\u5e93)","12":"\u8ffd\u52a0(\u5165\u5e93)","13":"\u670d\u52a1\u5668\u66ff\u6362(\u5165\u5e93)","19":"\u65b0\u5f55\u5165(\u5165\u5e93)"};
dataConfig_A['stock_outin'] = [];
dataConfig_A['stock_outin'].push({v:'服务器新(出库)',key:'1'});
dataConfig_A['stock_outin'].push({v:'服务器替换(出库)',key:'2'});
dataConfig_A['stock_outin'].push({v:'网络设备(出库)',key:'3'});
dataConfig_A['stock_outin'].push({v:'回寄客户(出库)',key:'4'});
dataConfig_A['stock_outin'].push({v:'退回(入库)',key:'11'});
dataConfig_A['stock_outin'].push({v:'追加(入库)',key:'12'});
dataConfig_A['stock_outin'].push({v:'服务器替换(入库)',key:'13'});
dataConfig_A['stock_outin'].push({v:'新录入(入库)',key:'19'});

dataConfig['goods_cat'] = {"1":"\u670d\u52a1\u5668\u79df\u7528","2":"\u670d\u52a1\u5668\u6258\u7ba1","3":"\u673a\u67dc\u79df\u7528","4":"\u5927\u5e26\u5bbd","5":"\u5b89\u5168\u9632\u62a4"};
dataConfig_A['goods_cat'] = [];
dataConfig_A['goods_cat'].push({v:'服务器租用',key:'1'});
dataConfig_A['goods_cat'].push({v:'服务器托管',key:'2'});
dataConfig_A['goods_cat'].push({v:'机柜租用',key:'3'});
dataConfig_A['goods_cat'].push({v:'大带宽',key:'4'});
dataConfig_A['goods_cat'].push({v:'安全防护',key:'5'});

dataConfig['paytype'] = {"1":"\u6708\u4ed8","3":"\u5b63\u4ed8","6":"\u534a\u5e74\u4ed8","12":"\u5e74\u4ed8"};
dataConfig_A['paytype'] = [];
dataConfig_A['paytype'].push({v:'月付',key:'1'});
dataConfig_A['paytype'].push({v:'季付',key:'3'});
dataConfig_A['paytype'].push({v:'半年付',key:'6'});
dataConfig_A['paytype'].push({v:'年付',key:'12'});

dataConfig['finance_bk'] = {"1":"\u90ae\u653f\u6c47\u6b3e","2":"\u519c\u4e1a\u94f6\u884c","3":"\u516c\u53f8\u8d26\u6237","4":"\u62db\u5546\u94f6\u884c","5":"\u5efa\u8bbe\u94f6\u884c","6":"\u5de5\u5546\u94f6\u884c","7":"\u652f\u4ed8\u5b9d","8":"\u8d22\u4ed8\u901a","9":"\u652f\u7968","10":"\u73b0\u91d1"};
dataConfig_A['finance_bk'] = [];
dataConfig_A['finance_bk'].push({v:'邮政汇款',key:'1'});
dataConfig_A['finance_bk'].push({v:'农业银行',key:'2'});
dataConfig_A['finance_bk'].push({v:'公司账户',key:'3'});
dataConfig_A['finance_bk'].push({v:'招商银行',key:'4'});
dataConfig_A['finance_bk'].push({v:'建设银行',key:'5'});
dataConfig_A['finance_bk'].push({v:'工商银行',key:'6'});
dataConfig_A['finance_bk'].push({v:'支付宝',key:'7'});
dataConfig_A['finance_bk'].push({v:'财付通',key:'8'});
dataConfig_A['finance_bk'].push({v:'支票',key:'9'});
dataConfig_A['finance_bk'].push({v:'现金',key:'10'});

dataConfig['finance_cat'] = {"1":"\u9884\u4ed8\u6b3e","2":"\u5e94\u6536\u6b3e","9":"\u4f59\u6b3e"};
dataConfig_A['finance_cat'] = [];
dataConfig_A['finance_cat'].push({v:'预付款',key:'1'});
dataConfig_A['finance_cat'].push({v:'应收款',key:'2'});
dataConfig_A['finance_cat'].push({v:'余款',key:'9'});

dataConfig['rpurview'] = {"1":"\u670d\u52a1\u5668\u4e1a\u52a1\u4fee\u6539","2":"\u670d\u52a1\u5668\u7528\u6237\u540d\u4fee\u6539"};
dataConfig_A['rpurview'] = [];
dataConfig_A['rpurview'].push({v:'服务器业务修改',key:'1'});
dataConfig_A['rpurview'].push({v:'服务器用户名修改',key:'2'});

dataConfig['pay_type'] = {"1":"\u6708\u4ed8","3":"\u5b63\u4ed8","6":"\u534a\u5e74\u4ed8","12":"\u5e74\u4ed8"};
dataConfig_A['pay_type'] = [];
dataConfig_A['pay_type'].push({v:'月付',key:'1'});
dataConfig_A['pay_type'].push({v:'季付',key:'3'});
dataConfig_A['pay_type'].push({v:'半年付',key:'6'});
dataConfig_A['pay_type'].push({v:'年付',key:'12'});

dataConfig['pay_bank'] = {"1":"\u4e2d\u56fd\u94f6\u884c","2":"\u4e2d\u56fd\u5de5\u5546\u94f6\u884c","3":"\u4e2d\u56fd\u519c\u4e1a\u94f6\u884c","4":"\u4e2d\u56fd\u5efa\u8bbe\u94f6\u884c"};
dataConfig_A['pay_bank'] = [];
dataConfig_A['pay_bank'].push({v:'中国银行',key:'1'});
dataConfig_A['pay_bank'].push({v:'中国工商银行',key:'2'});
dataConfig_A['pay_bank'].push({v:'中国农业银行',key:'3'});
dataConfig_A['pay_bank'].push({v:'中国建设银行',key:'4'});

dataConfig['adlist_cate'] = {"1":{"name":"\u9996\u9875\u901a\u680f\u8f6e\u64ad\u52a8\u753b","code":"1"},"2":{"name":"\u767b\u5f55\u5de6\u4fa7","code":"2"},"4":{"name":"\u4f01\u4e1a\u5feb\u8baf\u53f3\u8fb94\u56fe  184x228","code":"4"},"5":{"name":"\u9996\u9875\u5de5\u7a0b\u5b9a\u5236 180x120, 650x390, 396x390, 228x390","code":"5"},"6":{"name":"\u9996\u9875\u6c11\u7528\u6c99\u53d1 228x390 650x390  296x390","code":"6"},"7":{"name":"\u5de5\u7a0b\u6848\u4f8b\u5217\u8868-\u8f6e\u64ad\u56fe\u7247 940 x 705","code":"7"},"8":{"name":"\u5de5\u7a0b\u6848\u4f8b\u5217\u8868-\u6587\u5b57\u5e7f\u544a","code":"8"},"9":{"name":"\u7ecf\u9500\u5546\u7f51\u7edc\u8f6e\u64ad 1210 x 400","code":"9"}};
dataConfig_A['adlist_cate'] = [];
dataConfig_A['adlist_cate'].push({v:'首页通栏轮播动画',a:{"name":"\u9996\u9875\u901a\u680f\u8f6e\u64ad\u52a8\u753b","code":"1"},key:'1'});
dataConfig_A['adlist_cate'].push({v:'登录左侧',a:{"name":"\u767b\u5f55\u5de6\u4fa7","code":"2"},key:'2'});
dataConfig_A['adlist_cate'].push({v:'企业快讯右边4图  184x228',a:{"name":"\u4f01\u4e1a\u5feb\u8baf\u53f3\u8fb94\u56fe  184x228","code":"4"},key:'4'});
dataConfig_A['adlist_cate'].push({v:'首页工程定制 180x120, 650x390, 396x390, 228x390',a:{"name":"\u9996\u9875\u5de5\u7a0b\u5b9a\u5236 180x120, 650x390, 396x390, 228x390","code":"5"},key:'5'});
dataConfig_A['adlist_cate'].push({v:'首页民用沙发 228x390 650x390  296x390',a:{"name":"\u9996\u9875\u6c11\u7528\u6c99\u53d1 228x390 650x390  296x390","code":"6"},key:'6'});
dataConfig_A['adlist_cate'].push({v:'工程案例列表-轮播图片 940 x 705',a:{"name":"\u5de5\u7a0b\u6848\u4f8b\u5217\u8868-\u8f6e\u64ad\u56fe\u7247 940 x 705","code":"7"},key:'7'});
dataConfig_A['adlist_cate'].push({v:'工程案例列表-文字广告',a:{"name":"\u5de5\u7a0b\u6848\u4f8b\u5217\u8868-\u6587\u5b57\u5e7f\u544a","code":"8"},key:'8'});
dataConfig_A['adlist_cate'].push({v:'经销商网络轮播 1210 x 400',a:{"name":"\u7ecf\u9500\u5546\u7f51\u7edc\u8f6e\u64ad 1210 x 400","code":"9"},key:'9'});

dataConfig['upurview'] = ["\u8bbe\u7f6e\u7279\u6b8a\u6743\u9650","\u8de8\u516c\u53f8"];
dataConfig_A['upurview'] = [];
dataConfig_A['upurview'].push({v:'设置特殊权限',key:'0'});
dataConfig_A['upurview'].push({v:'跨公司',key:'1'});

dataConfig['finance_out_cat'] = {"1":"\u8d22\u52a1\u6536\u5165\u9000\u6b3e","2":"\u8bbe\u5907\u8d2d\u4e70","3":"\u8d39\u7528\u62a5\u9500","9":"\u5176\u4ed6"};
dataConfig_A['finance_out_cat'] = [];
dataConfig_A['finance_out_cat'].push({v:'财务收入退款',key:'1'});
dataConfig_A['finance_out_cat'].push({v:'设备购买',key:'2'});
dataConfig_A['finance_out_cat'].push({v:'费用报销',key:'3'});
dataConfig_A['finance_out_cat'].push({v:'其他',key:'9'});

dataConfig['server_tp'] = {"1":"\u79df\u7528","2":"\u6258\u7ba1","3":"\u6d4b\u8bd5","4":"\u8d5e\u52a9","5":"\u5176\u4ed6"};
dataConfig_A['server_tp'] = [];
dataConfig_A['server_tp'].push({v:'租用',key:'1'});
dataConfig_A['server_tp'].push({v:'托管',key:'2'});
dataConfig_A['server_tp'].push({v:'测试',key:'3'});
dataConfig_A['server_tp'].push({v:'赞助',key:'4'});
dataConfig_A['server_tp'].push({v:'其他',key:'5'});

dataConfig['server_us'] = {"1":"\u4e00\u4f53\u673a","2":"1U","3":"2U","4":"3U","5":"4U"};
dataConfig_A['server_us'] = [];
dataConfig_A['server_us'].push({v:'一体机',key:'1'});
dataConfig_A['server_us'].push({v:'1U',key:'2'});
dataConfig_A['server_us'].push({v:'2U',key:'3'});
dataConfig_A['server_us'].push({v:'3U',key:'4'});
dataConfig_A['server_us'].push({v:'4U',key:'5'});

dataConfig['server_os'] = {"1":"WIN2003","2":"WIN2008","3":"CENTOS6.3","4":"CENTOS6.4","5":"UNIX","6":"REDHAT","7":"UBUNTU","8":"LINUX","9":"ios","10":"other"};
dataConfig_A['server_os'] = [];
dataConfig_A['server_os'].push({v:'WIN2003',key:'1'});
dataConfig_A['server_os'].push({v:'WIN2008',key:'2'});
dataConfig_A['server_os'].push({v:'CENTOS6.3',key:'3'});
dataConfig_A['server_os'].push({v:'CENTOS6.4',key:'4'});
dataConfig_A['server_os'].push({v:'UNIX',key:'5'});
dataConfig_A['server_os'].push({v:'REDHAT',key:'6'});
dataConfig_A['server_os'].push({v:'UBUNTU',key:'7'});
dataConfig_A['server_os'].push({v:'LINUX',key:'8'});
dataConfig_A['server_os'].push({v:'ios',key:'9'});
dataConfig_A['server_os'].push({v:'other',key:'10'});

dataConfig['server_cfg'] = {"1":"I3\/8G\/500G","2":"I5\/8G\/500G","3":"Q8\/8G\/120G","7":"dell r720","8":"dell r410","9":"e31230 v2\/16"};
dataConfig_A['server_cfg'] = [];
dataConfig_A['server_cfg'].push({v:'I3/8G/500G',key:'1'});
dataConfig_A['server_cfg'].push({v:'I5/8G/500G',key:'2'});
dataConfig_A['server_cfg'].push({v:'Q8/8G/120G',key:'3'});
dataConfig_A['server_cfg'].push({v:'dell r720',key:'7'});
dataConfig_A['server_cfg'].push({v:'dell r410',key:'8'});
dataConfig_A['server_cfg'].push({v:'e31230 v2/16',key:'9'});

dataConfig['bandwidth'] = {"1":"100M\u5171\u4eab","2":"100M\u72ec\u4eab","3":"10M\u72ec\u4eab"};
dataConfig_A['bandwidth'] = [];
dataConfig_A['bandwidth'].push({v:'100M共享',key:'1'});
dataConfig_A['bandwidth'].push({v:'100M独享',key:'2'});
dataConfig_A['bandwidth'].push({v:'10M独享',key:'3'});

dataConfig['work_tag'] = {"1":"\u91cd\u88c5\u7cfb\u7edf","2":"\u683cC\u76d8\u88c5\u7cfb\u7edf","3":"\u7834\u5bc6\u7801"};
dataConfig_A['work_tag'] = [];
dataConfig_A['work_tag'].push({v:'重装系统',key:'1'});
dataConfig_A['work_tag'].push({v:'格C盘装系统',key:'2'});
dataConfig_A['work_tag'].push({v:'破密码',key:'3'});

dataConfig['work_pj'] = {"1":"\u975e\u5e38\u6ee1\u610f","2":"\u6ee1\u610f","3":"\u4e00\u822c","4":"\u4e0d\u6ee1\u610f"};
dataConfig_A['work_pj'] = [];
dataConfig_A['work_pj'].push({v:'非常满意',key:'1'});
dataConfig_A['work_pj'].push({v:'满意',key:'2'});
dataConfig_A['work_pj'].push({v:'一般',key:'3'});
dataConfig_A['work_pj'].push({v:'不满意',key:'4'});

dataConfig['server_st'] = {"1":"\u56de\u6536\u7a7a\u95f2","9":"\u6b63\u5e38\u4f7f\u7528"};
dataConfig_A['server_st'] = [];
dataConfig_A['server_st'].push({v:'回收空闲',key:'1'});
dataConfig_A['server_st'].push({v:'正常使用',key:'9'});

dataConfig['utype'] = {"1":{"name":"\u4e2a\u4eba","code":1},"2":{"name":"\u4f01\u4e1a","code":2},"3":{"name":"\u804c\u4ecb","code":3}};
dataConfig_A['utype'] = [];
dataConfig_A['utype'].push({v:'个人',a:{"name":"\u4e2a\u4eba","code":1},key:'1'});
dataConfig_A['utype'].push({v:'企业',a:{"name":"\u4f01\u4e1a","code":2},key:'2'});
dataConfig_A['utype'].push({v:'职介',a:{"name":"\u804c\u4ecb","code":3},key:'3'});

dataConfig['onepage'] = {"common":{"name":"\u5bfc\u822a\u680f\u5355\u9875","code":"common"},"dingzhi":{"name":"\u6211\u8981\u5b9a\u5236","code":"dingzhi"},"jiameng":{"name":"\u6211\u8981\u52a0\u76df","code":"jiameng"},"xiangqing":{"name":"\u8be6\u60c5\u9875\u56fa\u5b9a","code":"xiangqing"},"yalisha":{"name":"\u5370\u8c61\u96c5\u8389\u838e","code":"yalisha"},"zidingyi":{"name":"\u5176\u4ed6\u81ea\u5b9a\u4e49\u9875","code":"zidingyi"}};
dataConfig_A['onepage'] = [];
dataConfig_A['onepage'].push({v:'导航栏单页',a:{"name":"\u5bfc\u822a\u680f\u5355\u9875","code":"common"},key:'common'});
dataConfig_A['onepage'].push({v:'我要定制',a:{"name":"\u6211\u8981\u5b9a\u5236","code":"dingzhi"},key:'dingzhi'});
dataConfig_A['onepage'].push({v:'我要加盟',a:{"name":"\u6211\u8981\u52a0\u76df","code":"jiameng"},key:'jiameng'});
dataConfig_A['onepage'].push({v:'详情页固定',a:{"name":"\u8be6\u60c5\u9875\u56fa\u5b9a","code":"xiangqing"},key:'xiangqing'});
dataConfig_A['onepage'].push({v:'印象雅莉莎',a:{"name":"\u5370\u8c61\u96c5\u8389\u838e","code":"yalisha"},key:'yalisha'});
dataConfig_A['onepage'].push({v:'其他自定义页',a:{"name":"\u5176\u4ed6\u81ea\u5b9a\u4e49\u9875","code":"zidingyi"},key:'zidingyi'});

dataConfig['friendlink'] = {"1":{"name":"\u5bfc\u822a\u6761\u94fe\u63a5","code":"1"},"2":{"name":"\u641c\u7d22\u5173\u952e\u8bcd","code":"2"},"3":{"name":"\u5e95\u90e8\u94fe\u63a5","code":"3"}};
dataConfig_A['friendlink'] = [];
dataConfig_A['friendlink'].push({v:'导航条链接',a:{"name":"\u5bfc\u822a\u6761\u94fe\u63a5","code":"1"},key:'1'});
dataConfig_A['friendlink'].push({v:'搜索关键词',a:{"name":"\u641c\u7d22\u5173\u952e\u8bcd","code":"2"},key:'2'});
dataConfig_A['friendlink'].push({v:'底部链接',a:{"name":"\u5e95\u90e8\u94fe\u63a5","code":"3"},key:'3'});

dataConfig['modeltype'] = {"1":"\u5355\u9009","2":"\u591a\u9009","3":"\u4e0b\u62c9","4":"\u8f93\u5165","9":"\u6b65\u957f"};
dataConfig_A['modeltype'] = [];
dataConfig_A['modeltype'].push({v:'单选',key:'1'});
dataConfig_A['modeltype'].push({v:'多选',key:'2'});
dataConfig_A['modeltype'].push({v:'下拉',key:'3'});
dataConfig_A['modeltype'].push({v:'输入',key:'4'});
dataConfig_A['modeltype'].push({v:'步长',key:'9'});

dataConfig['goodsrel'] = {"content":"\u8be6\u60c5"};
dataConfig_A['goodsrel'] = [];
dataConfig_A['goodsrel'].push({v:'详情',key:'content'});

