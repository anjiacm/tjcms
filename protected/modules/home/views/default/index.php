<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>发现</title>
    <!--<meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no" />-->
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
<!--<div class="mask">dsadfassd</div>-->
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
<div class="nav">
    <!--<a href="#">
        <img src="images/icon_Check-in@3x.png" />
        <span>签到红包</span>
    </a>-->
    <a href="<?php echo $this->createUrl('applist',array('typeid'=>1)) ?>">
        <img src="<?php echo $this->_static_public ?>/home/default/images/icon_game@3x.png" />
        <span>游戏中心</span>
    </a>
    <a href="<?php echo $this->createUrl('fulilist',array('typeid'=>3)) ?>">
        <img src="<?php echo $this->_static_public ?>/home/default/images/icon_Lottery@3x.png" />
        <span>福利领券</span>
    </a>
    <a href="<?php echo $this->createUrl('applist',array('typeid'=>2)) ?>">
        <img src="<?php echo $this->_static_public ?>/home/default/images/icon_application@3x.png" />
        <span>应用中心</span>
    </a>
</div>
<div class="cont">
    <div class="title">游戏中心</div>
    <div class="list" id="list_1">
        <?php foreach ($gameresult as $row): ?>
            <div class="one" >
                <img src="<?php echo $row->appicon?>" onclick="gourl(<?php echo $row->id?>);"/>
                <div class="name"><?php echo $row->appname?></div>
                <div class="des">
                    <span><?php echo $this->_typelist['stype'][$row->appsmtypeid] ?></span>
                    <span class="size"><?php echo $row->appsize?></span>
                </div>
                <a href="javascript:void(0)"   onclick="downnum('<?php echo $row->id ?>');" class="btn">试玩</a>
            </div>
        <?php endforeach; ?>


    </div>
    <div class="more">
        <a href="<?php echo $this->createUrl('applist',array('typeid'=>1)) ?>"><span>更多好玩游戏</span></a>
        <div class="change" data-index="1" data-num="2" ><span>换一换看看</span></div>
    </div>
</div>
<div class="cont">
    <div class="title">福利领券</div>
    <div class="list_quan">
        <?php foreach ($fuliresult as $fulirow): ?>
            <div class="one" onclick="giftdo('<?php echo $fulirow->id?>')">
                <img src="<?php echo $fulirow->giftimg?>" />
                <div class="des"><?php echo $fulirow->giftcontent?></div>
                <a href="<?php echo $fulirow->gifturl?>">
                    <img src="<?php echo $this->_static_public ?>/home/default/images/57@3x.png"/>
                    <span class="num">￥<?php echo floor($fulirow->giftmoney - $fulirow->giftnewmoney) ?></span>
                    <span class="chai"></span>
                </a>
            </div>
        <?php endforeach; ?>


    </div>
    <div class="more">
        <a href="<?php echo $this->createUrl('fulilist',array('typeid'=>3)) ?>"><span>领取更多福利</span></a>
        <div class="change" data-num="6" data-index="1"><span>换一换看看</span></div>
    </div>
</div>
<div class="cont">
    <div class="title">应用中心</div>
    <div class="list" id="list_2">
        <?php foreach ($yyresult as $row): ?>
            <div class="one">
                <img src="<?php echo $row->appicon?>" onclick="gourl(<?php echo $row->id?>);" />
                <div class="name"><?php echo $row->appname?></div>
                <div class="des">
                    <span><?php echo $this->_typelist['stype'][$row->appsmtypeid] ?></span>
                    <span class="size"><?php echo $row->appsize?></span>
                </div>
                <a  href="javascript:void(0)"   onclick="downnum('<?php echo $row->id ?>');" class="btn">试玩</a>
            </div>
        <?php endforeach; ?>

    </div>
    <div class="more">
        <a href="<?php echo $this->createUrl('applist',array('typeid'=>2)) ?>"><span>更多好玩应用</span></a>
        <div class="change" data-index="1" data-num="8" ><span>换一换看看</span></div>
    </div>
</div>
<div id="qb-sougou-search" style="display: none; opacity: 0;"><iframe src=""></iframe></div>
<script src="<?php echo $this->_static_public ?>/home/default/js/common.min.js"></script>

<script src="<?php echo $this->_static_public ?>/home/default/js/main.js"></script>
</body>
</html>
<script>
    var dumpurl = window.location.host;
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