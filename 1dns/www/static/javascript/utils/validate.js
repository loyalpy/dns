//验证规则用法
// class="required email"或者 class="{required:true,minlength:5}",使用class验证，必须有规则才行
//参考地址：http://blog.sina.com.cn/s/blog_8b892aa90101e8lh.html

//验证是否全是中文
$.validator.addMethod("realname", function (value, element) {
    var cn = /^[\u4E00-\u9FA5]{1,20}$/i;
    return this.optional(element) || (cn.test(value));
}, "请输入中文");

//验证是否为座机号码
$.validator.addMethod("tel", function (value, element) {
    var tel = /^(((\+|)\d{2})|)(-| |)\d{3,4}(-| |)\d{3,4}(-| |)\d{3,4}(((-| |)\d{3,4})|)$/;
    return this.optional(element) || (tel.test(value));
}, "请输入正确的电话号码");

//验证是否为手机号码
$.validator.addMethod("mobile", function (value, element) {
    var length = value.length;
    var mobile = /^0?(17[0-9]|13[0-9]|15[012356789]|18[0236789]|14[57])[0-9]{8}$/;
    return this.optional(element) || (length == 11 && mobile.test(value));
}, "手机号码格式错误");

// 验证是否为QQ号码
$.validator.addMethod("qq", function(value, element) {
    var tel = /^\d{5,10}$/;
	return this.optional(element) || (tel.test(value));
}, "qq号码格式错误");

// 验证是否为字母和数字
jQuery.validator.addMethod("chrnum", function(value, element) {
	var chrnum = /^([a-zA-Z0-9]+)$/;
	return this.optional(element) || (chrnum.test(value));
}, "只能输入数字和字母(字符A-Z, a-z, 0-9)");
