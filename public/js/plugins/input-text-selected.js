$(function(){
	var isAdd=true;
	var right_str="",left_str="",input_str="";
	$.each(arr, function(i,o) {
		if(i==arr.length-1){
			input_str+=o;
		}else{
			input_str+=o+",";
		};
	});
	$(".input-text-selected").val(input_str);
	$.each(obj, function(i,o) {
		right_str+="<div class='ph-add'>+</div><li data-id="+o.id+"><span>"+o.id+"</span>"+o.title+"</li>"
	});
	$(".ph-right").append(right_str);
	$.each(arr,function(n,m){
		$.each(obj,function(i,o){
			if(m==o.id){
				left_str+="<li data-id="+o.id+"><span>"+o.id+"</span>"+o.title+"</li>"
			};
		});
	});
	$(".ph-left").append(left_str);
	$(".ph-right li").click(function(){
		var _id=$(this).attr('data-id');
		$.each(arr, function(i,o) {
			if(_id==o){
				isAdd=false;
				return false;
			}else{
				isAdd=true;
			};
		});
		if(isAdd){
			var left_str="<li data-id="+_id+">"+$(this).html()+"</li>";
			$(".ph-left").append(left_str);
			arr.push($(this).attr('data-id'));
		};
	});
	$(document).on("click",".ph-left li",function(){
		var _id=$(this).attr('data-id');
		$.each(arr,function(i,o){
			if(o==_id){
				arr.splice(i,1);
			}
		});
		$(this).remove();
	})
	$(".input-text-selected").focus(function(){
		$(".ph-popup").show();
	})
	$(".ph-btn").click(function(){
		input_str="";
		$.each(arr, function(i,o) {
		if(i==arr.length-1){
			input_str+=o;
		}else{
			input_str+=o+",";
		};
		});
		$(".input-text-selected").val(input_str);
		$(".ph-popup").hide();
	})
})
