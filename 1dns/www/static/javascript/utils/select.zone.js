/* *
 * SelectZone 类
 */
function SelectZone(){
  this.filters   = new Object();
  this.id        = arguments[0] ? arguments[0] : 1;     // 过滤条件
  this.sourceSel = arguments[1] ? arguments[1] : null;  // 源 
  this.targetSel = arguments[2] ? arguments[2] : null;  // 目标   select 对象
  this.maxlength = arguments[3] ? arguments[3] : 5;  // 目标   select 对象
  var _self = this;
  /**
   * 检查对象
   * @return boolean
   */
  this.check = function(){
    /* source select */
    if ( ! this.sourceSel){
      alert('source select undefined');
      return false;
    } else{
      if (this.sourceSel.nodeName != 'SELECT'){
        alert('source select is not SELECT');
        return false;
      }
    }
    /* target select */
    if ( ! this.targetSel){
      alert('target select undefined');
      return false;
    }else{
      if (this.targetSel.nodeName != 'SELECT'){
        alert('target select is not SELECT');
        return false;
      }
    }
    return true;
  }

  /**
   * 添加选中项
   * @param   boolean  all
   */
  this.addItem = function(all){
    if (!this.check()){
      return;
    }
    var selOpt  = new Array();
    for (var i = 0; i < this.sourceSel.length; i ++ ){
    	var _v = {"text":this.sourceSel.options[i].text,"value":this.sourceSel.options[i].value};
    	if(all == true){
    		selOpt.push(_v);
    	}else{
    		var exists = false;
    		if(this.sourceSel.options[i].selected){
    			exists = true;
    		}else{
    			for(var j = 0; j < this.targetSel.length; j ++ ){
    				if(this.targetSel.options[j].value == _v['value']){
    					exists = true;
    					break;
    				}
    			}
    		}
    		if(exists == true)selOpt.push(_v);
    	}
    }
    if (selOpt.length > 0){
      _self.createOptions(_self.targetSel,selOpt);
    }
  }
  /**
   * 删除选中项
   * @param   boolean    all
   */
  this.dropItem = function(all){
    if (!this.check()){
      return;
    }
    var arr = new Array();
    for (var i = this.targetSel.length - 1; i >= 0 ; i -- ){
      if(this.targetSel.options[i].selected || all){
		this.targetSel.remove(this.targetSel.options[i].index)
      }
    }
  }
  /**
   * 为select元素创建options
   */
  this.createOptions = function(obj, arr) {
    if(arr.length >= this.maxlength){
		alert("最多只能选择"+this.maxlength+"个");
		return false;
	}
	if(arr.length > 0){
		var _vv = [];
		//清空
		for (var i = this.targetSel.length - 1; i >= 0 ; i -- ){
			this.targetSel.remove(this.targetSel.options[i].index)
	    }
	    //重写
	    for (var i = 0;i<arr.length;i++){
	    	var opt   = document.createElement("OPTION");
		    opt.value = arr[i].value;
		    opt.text  = arr[i].text;
		    opt.id    = "sel_target"+arr[i].value;
		    _vv.push(opt.value);
		    obj.options.add(opt);
	    }
	    $("#"+this.id).val(_vv.join(","));
	}
  }
}
