!function () {
    function e() {
        this.pullUpFlag = !0,this.pulldown_pgNum = 0;
    }
    var tmpUrl='tmp.html',
        _ = $("body"), T = [],
        L = $("#js_news_list"),
        j = $("#js_top_menu"),
        D = !0, N = 0, G = 0, S = !0, O = !1, B = !1, A = null, P = 150,C = new WebStorageCache, F = null,J = !1;
    e.prototype={
        init:function(){
            var a=this;
            a.index();
            j.children("a").each(function () {
                var e = $(this);
                if (e.hasClass('active'))return setTimeout(function () {
                    a.scrollTo(e, !1)
                }, 50), !1
            }), j.on("click", "a", function () {
                var e = $(this);
                e.hasClass("active") || a.scrollTo(e, !1);
            }),
            //     $(window).on("scroll", function () {
            // 	if($("#J_loading").length<1) return false;
            //     var e = GLOBAL.Util.getScrollTop(), t = Number($("#J_loading").offset().top) - 100, s = GLOBAL.Util.getClientHeight(), i = null;
            //     i && clearTimeout(i), i = setTimeout(function () {
            //         C.set("news_pos_" + a.newsType, e, {exp: 2400})
            //     }, 200), t >= s && e + s >= t && a.pullUpFlag && a.pullUpLoadData(), L.find("video").each(function () {
            //         var e = $(this), a = e.offset().top, t = _.scrollTop();
            //         (t >= a || a - t >= $(window).height() - e.height()) && (this.paused || this.pause())
            //     })
            // });
            //懒加载调用
            $('.lazy').lazyload({
		        effect:"fadeIn"
		    });
            //幻灯调用
            new Swiper('.swiper-container', {
                pagination: '.swiper-pagination',
                paginationClickable: true,
                autoplay: 5e3,
                loop:true
            });
        },index:function(){
        	$("#list_2 .one").each(function(i,o){
        		if(i<8) $(o).show();
        	});
        	$("#list_1 .one").each(function(i,o){
        		if(i<2) $(o).show();
        	});
        	$(".list_quan .one").each(function(i,o){
        		if(i<6) $(o).show();
        	});
        	var thepages,index=1 ;
        	$(".change").click(function(){
                $(this).addClass("change_active");

                var num=$(this).attr("data-num");
                //var index=$(this).attr("data-index");
                $(this).parents().siblings().children(".one").hide();
                var max=$(this).parents().siblings().children(".one").size();
                thepages = Math.ceil(max/num);
                console.log(index);
                console.log(thepages);
                if(index<thepages){

                    index++;



                }else{
                   index = 1
                }


                $($(this).parents().siblings().children(".one")).each(function(i,o){
                    if(i<(index*num)&&i>(index*num)-1-num) $(o).show();
                });
                $(this).attr("data-index",index);
                setTimeout(function(){
                  $(".change").removeClass("change_active");
                },1000)
              })
        	$("#more_btn").click(function(){
        		if($(this).html()=="更多"){
        			$(this).siblings("p").removeClass("not_all");
	        		$(this).html("收起");
        		}else{
        			$(this).siblings("p").addClass("not_all");
	        		$(this).html("更多");
        		}
        	})
        	
        },scrollTo: function (e, a) {
            var t = j.children("a"), s = $(window).width(), i = e[0].offsetLeft, n = e.width();
            t.removeClass("active"), e.addClass("active"), j.scrollLeft(i + n / 2 - s / 2)
        }, pullDownLoadData: function (e) {
          //下拉加载数据
        }, pullUpLoadData: function () {
            //上拉加载更多
            var _str="";
//          var type_num="";
            $.each(list_arr, function(i,o) {
            	_str+='<li>'+
				'<img src="'+o.img_src+'" class="app_img"/>'+
				'<div class="des">'+
					'<div class="name">'+o.name+'</div>'+
					'<div class="txt"><span>'+o.label+'</span><span>'+o.down_num+'下载</span><span>'+o.size+'</span></div>'+
					'<div class="t_title">'+o.title+'</div>'+
				'</div>'+
				'<a href="#" class="btn">安装</a>'+
			'</li>'
//			领券中心
//			_str+='<li>'+
//				'<img src="images/pic/a.png" class="shop" />'+
//				'<div class="des">'+
//				    '<div class="title"><img src="images/571@3x.png"/><img src="images/58@3x.png"/>饭盒袋保温手提袋防水便当包零食饭盒袋保温手提袋防水便当包零食</div>'+
//					'<div class="price"><span>￥</span>20.9<img src="images/59@3x.png"/></div>'+
//					'<div class="old_price">原价：<del>￥245</del></div>'+
//				'</div>'+
//				'<div class="right">'+
//					'<img src="images/541@3x.png"/>'+
//					'<div class="num"><span>20</span></div>'+
//					'<div class="txt">以抢2554件</div>'+
//				'</div>'+
//			'</li>'
            });
            $(".ying").append(_str);
            //加载完毕
            $("#J_loading .spinner").hide();
            $("#J_loading .txt").html("已加载完全");
        }},$(function () {
        var a = new e;
        a.init();
    })
}();
var list_arr=[{"img_src":"images/pic/ic.png","name":"aaa","label":"小游戏用","down_num":"1212","size":"345M","title":"sdfdsfdsfsdfsdfsdf"},
{"img_src":"images/pic/ic.png","name":"a11aaa","label":"小游戏用","down_num":"1212","size":"345M","title":"sdfdsfdsfsdfsdfsdf"}]
