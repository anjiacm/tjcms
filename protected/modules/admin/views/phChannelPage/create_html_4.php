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
<div class="ph_layout p_add">
    <div class="ph_playBox">
        <div class="play_video top_video">
            <div class="play_topBar top_play">
                <span class="t_logo"></span>
                <p>海量热播大片 无广告 免费看</p>
                <a href="javascript:;" id="js_down" class="js_btn">下载app</a>
            </div>
            <a href="javascript:;" class="play_place">
                <img src="<?php echo $data[0]['h_img_url'] ?>"/>
                <span class="v_icon"></span>
            </a>

            <div class="play_video_box view_de">
                <img class="loading" src="<?php echo $this->_static_public; ?>/channelpage/<?php echo $theme; ?>/images/loading.gif"/>
                <div class="down_box" id="js_downBox">
                    <img src="<?php echo $this->_static_public; ?>/channelpage/<?php echo $theme; ?>/images/logo2.png" class="down_logo">
                    <h2>请先下载 <span><span class="app_name">聚看影院</span>APP</span></h2>
                    <p>安装完成后即可免费在线观看该影片</p>
                    <img src="<?php echo $this->_static_public; ?>/channelpage/<?php echo $theme; ?>/images/btn_down.png" class="down_btn">
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
                    <p class="v_tit"><span><?php echo $data[0]['title'] ?></span></p>
                    <p class="v_info">
                        <!--<span class="v_l">全<label>12</label>集&nbsp;&nbspnbsp;&nbsp;第<label>1</label>集;</span>-->
                        <span class="v_c"><label><?php echo $data[0]['hits'] ?></label>&nbsp;次播放</span>
                    </p>
                </div>
            </div>

            <div class="cross_down" href="#">
                <a href="javascript:;" id="js_crossDowm" class="js_btn">
                    <i></i>
                    <p><label>下载</label><span class="app_name">聚看影院</span>&nbsp;&nbsp;最新大片立即看</p>
                    <span><em></em></span>
                </a>
            </div>

            <div class="v_antho">
                <p class="ho_tit">本片主演</p>
                <!--滑动导航-->
                <nav class="nav">
                    <div class="scroll_nav scr_nav" id="v_scrollBox">
                        <ul class="scroll_wrap v_adver">
                            <?php
                            if(!empty($playactors)){
                                foreach($playactors as $key=> $item){
                                    if($key>5) break;
                                    ?>
                                    <li>
                                        <a href="javascript:;">
                                        <span class="v_pic">
                                                <img src="<?php echo Helper::getFullUrl($item['avater']) ?>">
                                           </span>
                                            <span class="v_in"><?php echo $item['title'] ?></span>

                                        </a>
                                    </li>
                                    <?php
                                }
                            }
                            ?>
                        </ul>
                        <!--<div class="cover-scroll"></div>-->
                    </div>
                </nav>

            </div>

        </div>
    </div>
    <div class="ph_list">
        <div class="list_hot">
            <div class="line">猜你喜欢</div>
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
        <?php
        if (!empty($special)) {
            foreach ($special as $item) {
                if ($item['showstyle'] == 1) {//横图
                    ?>
                    <div class="list_hot">
                        <div class="tit"><?php echo $item['title'];?></div>
                        <div class="list_item clearfix list_any">
                            <?php
                            if(!empty($item['postlist'])){
                                foreach($item['postlist'] as $key=> $post){
                                    if($key>5) break;
                                    ?>
                                    <a href="javascript:;">
                                        <span class="pic">
                                            <img src="<?php echo Helper::getFullUrl($post['img_h']) ?>">
                                        </span>
                                        <span class="info"><?php echo $post['title'];?></span>
                                        <span class="info des"><label><?php echo $post['hits'];?></label>次播放</span>
                                        <span class="v_watch">app观看</span>
                                    </a>
                                    <?php
                                }
                            }
                            ?>
                        </div>
                    </div>
                    <?php
                } elseif ($item['showstyle'] == 2) {//竖图
                    ?>
                    <div class="list_hot">
                        <div class="tit"><?php echo $item['title']; ?></div>
                        <div class="list_item clearfix add_beau">
                            <?php
                            if (!empty($item['postlist'])) {
                                foreach ($item['postlist'] as $key => $post) {
                                    if ($key > 2) break;
                                    ?>
                                    <a href="javascript:;">
                                            <span class="pic">
                                                <em>
                                                    <img src="<?php echo Helper::getFullUrl($post['img_l']) ?>">
                                                    <span class="v_wfoot">高清</span>
                                                </em>
                                                <span class="info"><?php echo $post['title']; ?></span>
                                                <span class="info des"><label><?php echo $post['hits']; ?></label>次播放</span>
                                            </span>
                                    </a>
                                    <?php
                                }
                            }
                            ?>
                        </div>
                    </div>
                    <?php
                } elseif ($item['showstyle'] == 3) {//大图
                    ?>
                    <div class="list_hot">
                        <div class="tit"><?php echo $item['title'];?></div>
                        <div class="list_item clearfix add_item">
                            <?php
                            if(!empty($item['postlist'])){
                                foreach($item['postlist'] as $key=> $post){
                                    if($key>2) break;
                                    ?>
                                    <a href="javascript:;">
                                        <span class="pic">
                                            <img src="<?php echo Helper::getFullUrl($post['img_l']) ?>">
                                        </span>
                                        <span class="info"><?php echo $post['title'];?></span>
                                    </a>
                                    <?php
                                }
                            }
                            ?>
                        </div>
                    </div>
                    <?php
                }
            }
        }
        ?>
    </div>


    <a class="ph_footer">
        <div class="fo_left">
            <img src="<?php echo $this->_static_public; ?>/channelpage/<?php echo $theme; ?>/images/footer_logo.png">
            <p class="left_info">
                <span class="app_name">聚看影院</span>
            </p>
        </div>
        <div class="fo_right">
            <span href="javascript:;" id="js_an" class="IOS"><span><i></i>IOS</span></span>
        </div>
    </a>
</div>
<script src="<?php echo $this->_static_public; ?>/channelpage/<?php echo $theme; ?>/js/jquery-1.12.2.min.js"></script>
<script src="<?php echo $this->_static_public; ?>/channelpage/<?php echo $theme; ?>/js/jquery.lazyload.min.js"></script>
<script src="<?php echo $this->_static_public; ?>/channelpage/<?php echo $theme; ?>/js/main.js"></script>
<script>
    var siteData =<?php echo CJSON::encode($app);?>
</script>
<div style="display:none">
    <?php if(!empty($statistics)){
        foreach($statistics as $item){
            echo $item->content."\r\n";
        }
    }?>
</div>
</body>
</html>