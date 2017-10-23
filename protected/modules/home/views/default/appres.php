<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title><?php echo $gameresult['appname']?></title>
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
<div class="detail_title">
    <img src="<?php echo $gameresult['appicon']?>" />
    <div class="des">
        <div class="name"><?php echo $gameresult['appname']?></div>
        <div class="txt"><span><?PHP echo $this->_typelist['stype'][$gameresult['appsmtypeid']] ?></span><span><?php echo $gameresult['appdonum'] + $gameresult['appnumyes']?>下载</span><span><?php echo $gameresult['appsize'] ?></span></div>
    </div>
</div>
<div class="img_scroll">
    <div class="in">
        <?php

        foreach ($listimg as  $imgs):
            ?>

            <img src="<?php echo $imgs ?>" />
        <?php endforeach; ?>

    </div>
</div>
<div class="detail_txt">
    <div class="title"><?php echo $gameresult['apptitle']?></div>
    <p class="not_all"><?php echo $gameresult['appcontent']?></p>
    <div class="btn" id="more_btn">更多</div>
</div>
<div class="cont" style="padding-bottom:80px ">
    <div class="title">相关推荐</div>
    <div class="list tab1">
        <?php foreach ($gameorresult as $row): ?>
            <div class="one">
                <img src="<?php echo $row->appicon?>" onclick="gourl(<?php echo $row->id?>);"/>
                <div class="name"><?php echo $row->appname?></div>
                <div class="des">
                    <span><?php echo $row->apptitle?></span>
                    <span class="size"><?php echo $row->appsize?></span>
                </div>
                <a href="javascript:void(0)"   onclick="downnum('<?php echo $row->id ?>');" class="btn">试玩</a>
            </div>
        <?php endforeach; ?>
    </div>
</div>
<a href="javascript:void(0)" class="btn_down" onclick="downnum('<?php echo $gameresult['id']?>');" style="position:fixed;bottom: 20px;"><img src="<?php echo $this->_static_public ?>/home/default/images/icon_record@3x.png"/>试玩</a>
<div id="qb-sougou-search" style="display: none; opacity: 0;"><iframe src=""></iframe></div>
    <script src="<?php echo $this->_static_public ?>/home/default/js/common.min.js"></script>
    <script src="<?php echo $this->_static_public ?>/home/default/js/main.js"></script>
</body>
</html>
<script>
    var dumpurl = window.location.host;
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
    function gourl(id) {

        window.location.href=dumpurl+'/?r=home/Default/appres/appid/'+id

    }
</script>