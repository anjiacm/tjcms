<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>领券中心</title>
    <meta name="description" content="XXX">
    <meta name="keywords" content="XXX">
    <meta name="apple-mobile-web-app-status-bar-style" content=" black "/>
    <meta name="format-detection" content="telephone=no"/>
    <meta name="full-screen" content="yes">
    <meta name="x5-fullscreen" content="true">
    <meta name="applicable-device" content="mobile">
    <script src="<?php echo $this->_static_public ?>/home/default/js/flexible.js"></script>
    <link rel="stylesheet" href="<?php echo $this->_static_public ?>/home/default/css/swiper.min.css" />
    <link rel="stylesheet" href="<?php echo $this->_static_public ?>/home/default/css/main.debug.css" />
</head>
<body>
<header class="swiper-container">
    <div class="swiper-wrapper">
        <?php foreach ($hbresult as $hbrow): ?>
            <div class="swiper-slide">
                <a href="<?php echo $hbrow->hburl?>"><img src="<?php echo $hbrow->hbimg?>" alt="" title=""/></a>
            </div>
        <?php endforeach; ?>
    </div>
    <!-- Add Pagination -->
    <div class="swiper-pagination"></div>
</header>
<nav>
    <div id="js_top_menu">
        <a href="<?php echo $this->createUrl('fulilist',array('typeid'=>$bigresult['id'])) ?>" class="tabs0">全部</a>
        <?php foreach ($styperesult as $styperow): ?>
            <a class="tabs<?php echo $styperow->id ?>" href="<?php echo $this->createUrl('fulilist',array('typeid'=>$bigresult['id'],'gifttype'=>$styperow->id)) ?>"><?php echo $styperow->s_typename ?></a>
        <?php endforeach; ?>
    </div>
    <div class="mask_nav"></div>
</nav>
<ul class="quan">
    <?php foreach ($yyresult as $yyrow): ?>
    <li>
        <img src="<?php echo $yyrow->giftimg?>" class="shop" />
        <div class="des">
            <div class="title"><img src="<?php echo $this->_goodsqdlist['s_goodsimg'][$yyrow->giftqd] ?>"/><img src="<?php echo $this->_goodsbqlist['s_goodsimg'][$yyrow->giftbq] ?>"/><?php echo $yyrow->giftname?></div>
            <div class="price"><span>￥</span><?php echo $yyrow->giftnewmoney?><img src="<?php echo $this->_goodskdlist['s_goodsimg'][$yyrow->giftkd] ?>"/></div>
            <div class="old_price">原价：<del>￥<?php echo $yyrow->giftmoney?></del></div>
        </div>
        <div class="right" onclick="giftdo('<?php echo $yyrow->id?>')">
            <img src="<?php echo $this->_static_public ?>/home/default/images/541@3x.png"/>
            <div class="num"><span><?php echo floor($yyrow->giftmoney - $yyrow->giftnewmoney) ?></span></div>
            <div class="txt">已抢<?php echo $yyrow->giftnum + $yyrow->giftnewdo ?>件</div>
        </div>
    </li>
    <?php endforeach; ?>


</ul>
<div id="J_loading" class="loading">
    <div class="spinner">
        <div class="bounce1"></div>
        <div class="bounce2"></div>
        <div class="bounce3"></div>
    </div>
    <p class="txt">数据加载中</p>
</div>
<script src="<?php echo $this->_static_public ?>/home/default/js/common.min.js"></script>
<script src="<?php echo $this->_static_public ?>/home/default/js/main.js"></script>
</body>
</html>
<script>
    $('.tabs'+<?php echo $gifttype ?>).addClass('active');
    var gfittype = <?php echo $gifttype ?>;
    var page =2;
    var lx = 0;
    $("#J_loading").hide();
    $(window).scroll(function () {    //下拉加载
        var scrollTop = $(this).scrollTop();
        var scrollHeight = $(document).height();
        var windowHeight = $(this).height();
        var isload = 0;

        if (scrollTop + windowHeight == scrollHeight && isload == 0) {
           isload =1;
           $("#J_loading ").show();
           $("#J_loading .txt").html("正在加载……");
          // $(".jzgd").text("正在加载...")
            console.log(page);
            $.post("<?php echo $this->createUrl('Default/giftscolldo'); ?>",{page:page,gfittype:gfittype},
                function(data){
                    if(data.code ==0){
                        page++;

                        var htmllist = data.yylist;
                        var qdlist = data.qdlist;
                        var kdlist = data.kdlist;
                        var bqlist = data.bqlist;
                        for(i=0;i<htmllist.length;i++){
                            var money = htmllist[i]['giftmoney']-htmllist[i]['giftnewmoney'];
                            var nums = htmllist[i]['giftnum']+htmllist[i]['giftnewdo'];
                            var html='<li>'+
                            '<img src="'+htmllist[i]['giftimg']+'" class="shop" />'+
                            '<div class="des">'+
                                '<div class="title"><img src="'+qdlist['s_goodsimg'][htmllist[i]['giftqd']]+'"/><img src="'+bqlist['s_goodsimg'][htmllist[i]['giftbq']]+'"/>饭盒袋保温手提袋防水便当包零食饭盒袋保温手提袋防水便当包零食</div>'+
                                '<div class="price"><span>￥</span>'+htmllist[i]['giftnewmoney']+'<img src="'+kdlist['s_goodsimg'][htmllist[i]['giftkd']]+'"/></div>'+
                                '<div class="old_price">原价：<del>￥'+htmllist[i]['giftmoney']+'</del></div>'+
                            '</div>'+
                            '<div class="right" onclick="giftdo('+htmllist[i]['id']+')">'+
                                '<img src="<?php echo $this->_static_public ?>/home/default/images/541@3x.png"/>'+
                                '<div class="num"><span>'+Math.floor(money)+'</span></div>'+
                                '<div class="txt">已抢'+Math.floor(nums)+'件</div>'+
                            '</div>'+
                        '</li>';
                            $(".quan").append(html);
                        }
                        $("#J_loading").hide();
                        isload =0;
                    }else{
                        $("#J_loading .spinner").hide();
                        $("#J_loading .txt").html("已全部加载");


                    }
                },"json")


            //此处是滚动条到底部时候触发的事件，在这里写要加载的数据，或者是拉动滚动条的操作

        }
    });

        function giftdo(id) {
            $.post("<?php echo $this->createUrl('Default/giftdownum'); ?>",{id:id},
                function(data){
                    if(data.code == 0){
                        window.location.href=data.dourl
                    }else{

                    }

                    //alert(data.msg);

                },'json')
        }

</script>