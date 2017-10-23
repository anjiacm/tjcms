<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title><?php echo $bigresult['typename'] ?></title>
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
        <a href="<?php echo $this->createUrl('applist',array('typeid'=>$bigresult['id'])) ?>" class="tabs0">全部</a>
        <?php foreach ($styperesult as $styperow): ?>
            <a class="tabs<?php echo $styperow->id ?>" href="<?php echo $this->createUrl('applist',array('typeid'=>$bigresult['id'],'thesmtypeid'=>$styperow->id)) ?>"><?php echo $styperow->s_typename ?></a>
        <?php endforeach; ?>

    </div>
    <div class="mask_nav"></div>
</nav>
<ul class="ying">
    <?php foreach ($yyresult as $yyrow): ?>
        <li>
            <img src="<?php echo $yyrow->appicon?>" class="app_img" onclick="gourl(<?php echo $yyrow->id?>);"/>
            <div class="des">
                <div class="name"><?php echo $yyrow->appname?></div>
                <div class="txt"><span><?php echo $this->_typelist['stype'][$yyrow->appsmtypeid] ?></span><span><?php echo $yyrow->appdonum + $yyrow->appnumyes?>下载</span><span><?php echo $yyrow->appsize ?></span></div>
                <div class="t_title"><?php echo $yyrow->apptitle?></div>
            </div>
            <a href="javascript:void(0)"   onclick="downnum('<?php echo $yyrow->id ?>');" class="btn">
                <?php if($bigresult['id'] == 1){ ?>试玩<?php }else{?>安装<?php }?>
            </a>
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
<div id="qb-sougou-search" style="display: none; opacity: 0;"><iframe src=""></iframe></div>
<script src="<?php echo $this->_static_public ?>/home/default/js/common.min.js"></script>
<script src="<?php echo $this->_static_public ?>/home/default/js/main.js"></script>
</body>
</html>
<script>
    var thetype = <?php echo $bigresult['id'] ?>;
    var thesmtype = <?php echo $thesmtypeid ?>;
    $('.tabs'+<?php echo $thesmtypeid ?>).addClass('active');
    var page =2;
    var lx = 0;
    var dumpurl = window.location.host;
    var isload = 0;
    $("#J_loading").hide();
    $(window).scroll(function () {    //下拉加载
        var scrollTop = $(this).scrollTop();
        var scrollHeight = $(document).height();
        var windowHeight = $(this).height();


        if (scrollTop + windowHeight == scrollHeight && isload == 0) {
            isload =1;
            $("#J_loading ").show();
            $("#J_loading .txt").html("正在加载……");
            // $(".jzgd").text("正在加载...")

            $.post("<?php echo $this->createUrl('Default/appscolldo'); ?>",{page:page,typeid:thetype,thesmtypeid:thesmtype},
                function(data){
                    if(data.code ==0){
                        page++;

                        var htmllist = data.yylist;
                        var typelist = data.typelist;
                        for(i=0;i<htmllist.length;i++){
                            var nums = htmllist[i]['appdonum']-htmllist[i]['appnumyes'];
                            var html= '<li>'+
                                '<img src="'+htmllist[i]['appicon']+'" class="app_img"/>'+
                               '<div class="des">' +
                                '<div class="name">'+htmllist[i]['appname']+'</div>'+
                                '<div class="txt"><span>'+typelist['stype'][htmllist[i]['appsmtypeid']]+' </span><span>'+nums+'下载</span><span>'+htmllist[i]['appsize']+'</span></div>'+
                                '<div class="t_title">'+htmllist[i]['apptitle']+'</div>'+
                               '</div>' +
                                '<a href="/?r=home/Default/appres/appid/'+htmllist[i]['id']+'" class="btn">安装</a>'+
                                '</li>'
                            $(".ying").append(html);
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
    function gourl(id) {

        window.location.href='/?r=home/Default/appres/appid/'+id

    }
    function downnum(id) {
        $.post("<?php echo $this->createUrl('Default/downum'); ?>",{id:id},
            function(data){
                if(data.code == 0){
                    downloadFile(data.dourl);
                }else{

                }

                //alert(data.msg);

            },'json')
    }
    function downloadFile(url) {
        try{
            var elemIF =document.createElement("iframe");
            elemIF.src =url;elemIF.style.display ="none";
            document.body.appendChild(elemIF);
        }catch(e){}
    }
    </script>
