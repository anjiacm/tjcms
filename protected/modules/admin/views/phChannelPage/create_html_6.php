<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="description" content="聚看影院海量影视免费看-<?php echo $data[0]['title'] ?>免费观看">
    <meta name="keywords" content="聚看影院,<?php echo $data[0]['title'] ?>">
    <meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=no"/>
    <meta name="apple-mobile-web-app-status-bar-style" content=" black "/>
    <meta name="format-detection" content="telephone=no"/>
    <meta name="full-screen" content="yes">
    <meta name="x5-fullscreen" content="true">
    <meta name="applicable-device" content="mobile">
    <title><?php echo $data[0]['title'] ?>-聚看影院-首页</title>
    <script src="<?php echo $this->_static_public; ?>/channelpage/<?php echo $theme; ?>/js/rem.js"></script>
    <link rel="stylesheet" href="<?php echo $this->_static_public; ?>/channelpage/<?php echo $theme; ?>/css/styles.css">
</head>
<body>
<div class="ph_layout p_add p_pink">
    <div class="ph_playBox">
        <div class="play_video top_video">
            <div class="play_topBar top_play">
                <span class="t_logo"></span>
                <p>海量热播韩剧 无广告 免费看</p>
                <a href="javascript:;" id="js_down" class="js_btn">下载app</a>
            </div>
            <a href="javascript:;" class="play_place">
                <!--<a href="jg.html">-->
                <img src="<?php echo $data[0]['h_img_url'] ?>"/>
                <span class="v_icon"></span>
            </a>

            <div class="play_video_box view_de">
                <img class="loading" src="<?php echo $this->_static_public; ?>/channelpage/<?php echo $theme; ?>/images/loading.gif"/>
                <div class="down_box" id="js_downBox">
                    <img src="<?php echo $this->_static_public; ?>/channelpage/<?php echo $theme; ?>/images/logo2.png" class="down_logo">
                    <h2>请先下载 <span><span class="app_name">聚看影院</span>APP</span></h2>
                    <p>安装完成后即可免费在线观看该影片</p>
                    <img src="<?php echo $this->_static_public; ?>/channelpage/<?php echo $theme; ?>/images/btn_down_hj.png" class="down_btn">
                    <p class="c">友情提示：</p>
                    <p><span class="app_name">聚看影院</span>内资源全部免费观看，无任何收费信息</p>
                </div>
                <div class="play_but">
                    <div class="play_time">
                        <span>00:00</span>&nbsp;/&nbsp;<span><?php echo sprintf("%02d", intval($channelpage->playtime / 60)) . ":" . sprintf("%02d", ($channelpage->playtime % 60)) . ":00" ?></span>
                    </div>
                    <div class="play_pro">
                    </div>
                </div>
            </div>


            <div class="play_info clearfix">
                <div class="info_left">
                    <p class="v_tit"><span><?php echo $data[0]['title'] ?></span>&nbsp;&nbsp;第<label>1</label>集</p>
                    <p class="v_info">
                        <span class="v_l">全<label><?php echo $channelpage['tv_num'] ?></label>集&nbsp;&nbsp;</span>
                        <span class="v_c"><label><?php echo $data[0]['hits'] ?></label>&nbsp;&nbsp;次播放</span>
                    </p>
                </div>
            </div>
            <div class="v_antho">
                <span class="antho_l">↓下方手指右滑-选集</span>
                <span class="antho_r"><?php echo $channelpage['tv_num'] ?>集全</span>

            </div>

            <div class="cross_down" href="#">
                <a href="javascript:;" id="js_crossDowm" class="js_btn">
                    <i></i>
                    <p><label>下载</label><span class="app_name">聚看影院</span>&nbsp;&nbsp;最新韩剧立即看</p>
                    <span><em></em></span>
                </a>
            </div>

            <!--滑动导航-->
            <nav class="nav">
                <div class="scroll_nav scr_nav" id="v_scrollBox">
                    <ul class="scroll_wrap">
                        <?php
                        $n = $channelpage['tv_num'];
                        if($n<=3){
                            $n=1;
                        }elseif($n>3 && $n<=5){
                            $n=3;
                        }elseif($n>5 && $n<=10){
                            $n=5;
                        }elseif($n>10){
                            $n=10;
                        }
                        for($i=1;$i<=$n;$i++){
                            if($i==1){
                                echo '<li  class="active"><a href="javascript:;" data-num="'.$i.'">'.$i.'</a></li>';
                            }else{
                                echo '<li><a href="javascript:;"  data-num="'.$i.'">'.$i.'</a></li>';
                            }
                        }
                        ?>
                    </ul>
                    <div class="cover-scroll"></div>
                </div>
            </nav>


        </div>
    </div>
    <div class="ph_list">
        <div class="list_hot">
            <div class="tit">猜你喜欢</div>
            <div class="list_item clearfix list_any">
                <?php
                if ($data) {
                    foreach ($data as $key => $item) {
                        ?>
                        <?php  if ($key == 0) continue; ?>
                        <a href="javascript:;">
                            <span class="pic">
                                <img class="lazy" data-src="<?php echo $item['h_img_url'] ?>">
                            </span>
                            <span class="info"><?php echo $item['title'] ?></span>
                            <span class="info des"><label><?php echo $item['hits'] ?></label>次播放</span>
                            <span class="v_watch">app观看</span>
                        </a>
                    <?php }
                }
                ?>
            </div>
        </div>

        <!--新增:lvman 2017-6-1-->
        <?php
        if (!empty($special)) {
            foreach ($special as $item) {
                if ($item['showstyle'] == 1) {
                    ?>
                    <div class="list_hot">
                        <div class="tit"><?php echo $item['title']; ?></div>
                        <div class="list_item clearfix add_item">
                            <?php
                            if (!empty($item['postlist'])) {
                                foreach ($item['postlist'] as $key => $post) {
                                    if ($key > 5) break;
                                    ?>
                                    <a href="javascript:;">
                            <span class="pic">
                                <img class="lazy" data-src="<?php echo Helper::getFullUrl($post['img_h']) ?>">
                                <div class="play_vi"></div>
                            </span>
                                        <span class="info"><?php echo $post['title']; ?></span>
                                        <span class="des1"><?php echo $post['sub_title']; ?></span>
                                    </a>
                                    <?php
                                }
                            }
                            ?>

                        </div>
                    </div>
                    <?php
                } else {//竖图
                    ?>

                    <div class="list_hot">
                        <div class="tit"><?php echo $item['title']; ?></div>
                        <div class="list_item clearfix add_beau">

                            <a href="javascript:;">
                                <?php
                                if (!empty($item['postlist'])) {
                                    foreach ($item['postlist'] as $key => $post) {
                                        if ($key > 2) break;
                                        ?>
                                        <span class="pic">
                            <em>
                                <img class="lazy" data-src="<?php echo Helper::getFullUrl($post['img_l']) ?>">
                                <span class="v_wfoot">高清</span>
                            </em>
                            <span class="info"><?php echo $post['title']; ?></span>
                            <span class="info des"><label><?php echo $post['hits']; ?></label>次播放</span>

                        </span>
                                        <?php
                                    }
                                }
                                ?>
                            </a>

                        </div>
                    </div>
                    <?php

                }
            }
        }
        ?>
    </div>

    <div class="v_more">
        <a href="javascript:;">打开APP看看更多&nbsp;</a>
    </div>


    <a class="ph_footer">
        <div class="fo_left">
            <img src="<?php echo $this->_static_public;?>/channelpage/<?php echo $theme; ?>/images/footer_logo.png">
            <p class="left_info">
                <span class="app_name">聚看影院</span>
            </p>
        </div>
        <div class="fo_right">
                <span href="javascript:;" id="js_an" class="Android">
                    <span><i></i>安卓秒速下载</span>
                </span>
        </div>
    </a>
</div>
<script src="<?php echo $this->_static_public;?>/channelpage/<?php echo $theme; ?>/js/jquery-1.12.2.min.js"></script>
<script src="<?php echo $this->_static_public;?>/channelpage/<?php echo $theme; ?>/js/jquery.lazyload.min.js"></script>
<script src="<?php echo $this->_static_public;?>/channelpage/<?php echo $theme; ?>/js/main.js"></script>
<script>
    var siteData = <?php echo CJSON::encode($app); ?>
</script>
<div style="display:none">
    <?php if(!empty($statistics)){
        foreach($statistics as $item){
            echo $item->content."\r\n";
        }
    } ?>
</div>
<script>
    //选集设置  传参是总共有多少集
    num_select(<?php echo $channelpage['tv_num'] ?>)

</script>
</body>
</html>