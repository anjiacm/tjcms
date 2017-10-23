<div class="onepage">
    <div class="head">

       <div class="headleft">
           <?php if($res==1){?>  今日实时数据  <?php }?>
           <?php if($res==11){?>  TOP10渠道  <?php }?>
           <?php if($res==111){?> 渠道 IN TOP10  <?php }?>
       </div>

        <?php if($res!=1 && $res!=11 && $res!=111){?>
        <div class="headright">
            <span class="<?php echo ($timetype==1)?'listtime':'' ?>" onclick="tmtype(<?php echo $page?>,1,<?php echo $res?>)">小时</span>
            <span class="<?php echo empty($timetype)?'listtime':''?>"  onclick="tmtype(<?php echo $page?>,'',<?php echo $res?>)">日</span>
<!--            <span>周</span>-->
<!--            <span>月</span>-->
        </div>
        <?php }?>

    </div>
    <?php if($res==6){?>
    <div class="list">
        <div class="list_any <?php echo empty($page)?'u_do':'' ?>" onclick="theurl('',<?php echo $res?>)">
            <span class="any_test">10分钟内</span>
            <span class="any_test "><?php echo empty($this->_dayupsum['tenuser'])?'0':$this->_dayupsum['tenuser'] ?></span>
        </div>


        <div class="list_any <?php echo ($page==1)?'u_do':'' ?>" onclick="theurl(1,<?php echo $res?>)">
            <span class="any_test">1小时内</span>
            <span class="any_test "><?php echo empty($this->_dayupsum['houruser'])?0:$this->_dayupsum['houruser'] ?></span>
        </div>
        <div class="list_any <?php echo ($page==2)?'u_do':'' ?>" onclick="theurl(2,<?php echo $res?>)">
            <span class="any_test">1天内</span>
            <span class="any_test"><?php echo empty($this->_dayupsum['onedayuser'])?0:$this->_dayupsum['onedayuser'] ?></span>
        </div>
        <div class="list_any <?php echo ($page==3)?'u_do':'' ?>" onclick="theurl(3,<?php echo $res?>)">
            <span class="any_test">3天内</span>
            <span class="any_test"><?php echo empty($this->_dayupsum['threeday'])?0:$this->_dayupsum['threeday'] ?></span>
        </div>
        <div class="list_any <?php echo ($page==4)?'u_do':'' ?>" onclick="theurl(4,<?php echo $res?>)">
            <span class="any_test">7天内</span>
            <span class="any_test"><?php echo empty($this->_dayupsum['sevenday'])?0:$this->_dayupsum['sevenday'] ?></span>
        </div>
        <div class="list_any <?php echo ($page==5)?'u_do':'' ?>" onclick="theurl(5,<?php echo $res?>)">
            <span class="any_test">7天以上</span>
            <span class="any_test"><?php echo empty($this->_dayupsum['otherday'])?0:$this->_dayupsum['otherday'] ?></span>
        </div>
    </div>
    <?php }elseif($res==7){?>
    <div class="leftlist">
        <div class="list_any_s <?php echo empty($page)?'u_do':'' ?>" onclick="theurl('',<?php echo $res?>)">
            <span class="any_test">金额</span>
        </div>
        <div class="list_any_s <?php echo ($page==1)?'u_do':'' ?>" onclick="theurl(1,<?php echo $res?>)">
            <span class="any_test">人数</span>
        </div>
    </div>
    <?php }elseif($res==11 || $res ==111){?>
    <div class="leftlist">
        <?php if($res ==11){?>
        <div class="list_any_s <?php echo empty($page)?'u_do':'' ?>" onclick="theurl('',<?php echo $res?>)">
            <span class="any_test">新增用户</span>
        </div>
        <div class="list_any_s <?php echo ($page==1)?'u_do':'' ?>" onclick="theurl(1,<?php echo $res?>)">
            <span class="any_test">活跃用户</span>
        </div>
        <?php }else{?>
            <div class="list_any_s <?php echo empty($page)?'u_do':'' ?>" onclick="theurl('',<?php echo $res?>)">
                <span class="any_test">新增用户</span>
            </div>
            <div class="list_any_s <?php echo ($page==8)?'u_do':'' ?>" onclick="theurl(8,<?php echo $res?>)">
                <span class="any_test">活跃用户</span>
            </div>
            <div class="list_any_s <?php echo ($page==7)?'u_do':'' ?>" onclick="theurl(7,<?php echo $res?>)">
                <span class="any_test">老版新增用户</span>
            </div>
            <div class="list_any_s <?php echo ($page==1)?'u_do':'' ?>" onclick="theurl(1,<?php echo $res?>)">
                <span class="any_test">老版活跃用户</span>
            </div>
        <?php }?>
        <?php if($res !=111){?>
        <div class="list_any_s <?php echo ($page==2)?'u_do':'' ?>" onclick="theurl(2,<?php echo $res?>)">
            <span class="any_test">付费用户</span>
        </div>
        <div class="list_any_s <?php echo ($page==3)?'u_do':'' ?>" onclick="theurl(3,<?php echo $res?>)">
            <span class="any_test">新增用户付费量</span>
        </div>

        <div class="list_any_s <?php echo ($page==4)?'u_do':'' ?>" onclick="theurl(4,<?php echo $res?>)">
            <span class="any_test">付费金额</span>
        </div>
        <div class="list_any_s <?php echo ($page==5)?'u_do':'' ?>" onclick="theurl(5,<?php echo $res?>)">
            <span class="any_test">付费率</span>
        </div>
        <div class="list_any_s <?php echo ($page==6)?'u_do':'' ?>" onclick="theurl(6,<?php echo $res?>)">
            <span class="any_test">ARPU</span>
        </div>
        <?php }?>
    </div>
    <?php }else{?>
    <div class="list">
        <?php if($res==5){?>
            <div class="list_any <?php echo empty($page)?'u_do':'' ?>" onclick="theurl('',<?php echo $res?>)">
                <span class="any_test">付费金额</span>
                <span class="any_test "><?php echo empty($this->_dayupsum['paymoney'])?'0':$this->_dayupsum['paymoney'] ?></span>
            </div>


            <div class="list_any <?php echo ($page==1)?'u_do':'' ?>" onclick="theurl(1,<?php echo $res?>)">
                <span class="any_test">付费用户</span>
                <span class="any_test "><?php echo empty($this->_dayupsum['payuser'])?0:$this->_dayupsum['payuser'] ?></span>
            </div>

            <div class="list_any <?php echo ($page==2)?'u_do':'' ?>" onclick="theurl(2,<?php echo $res?>)">
                <span class="any_test">付费次数</span>
                <span class="any_test"><?php echo empty($this->_dayupsum['paysum'])?0:$this->_dayupsum['paysum'] ?></span>
            </div>
        <?php }else{?>
            <?php if($_SESSION['admingroupid']==10){?>
                <div class="list_any <?php echo ($page==8)?'u_do':'' ?>" onclick="theurl(8,<?php echo $res?>)">
                    <span class="any_test">新增用户</span>
                    <span class="any_test "><?php echo empty($this->_dayupsum['adduser'])?"0":$this->_dayupsum['adduser'] ?></span>
                </div>


                <div class="list_any <?php echo ($page==1)?'u_do':'' ?>" onclick="theurl(1,<?php echo $res?>)">
                    <span class="any_test">活跃用户</span>
                    <span class="any_test "><?php echo empty($this->_dayupsum['openuser'])?0:$this->_dayupsum['openuser'] ?></span>
                </div>

                <div class="list_any <?php echo ($page==2)?'u_do':'' ?>" onclick="theurl(2,<?php echo $res?>)">
                    <span class="any_test">付费用户</span>
                    <span class="any_test"><?php echo empty($this->_dayupsum['payuser'])?0:$this->_dayupsum['payuser'] ?></span>
                </div>
                <div class="list_any <?php echo ($page==3)?'u_do':'' ?>" onclick="theurl(3,<?php echo $res?>)">
                    <span class="any_test">新增用户付费量</span>
                    <span class="any_test"><?php echo empty($this->_dayupsum['newpayuser'])?0:$this->_dayupsum['newpayuser'] ?></span>
                </div>
                <div class="list_any <?php echo ($page==4)?'u_do':'' ?>" onclick="theurl(4,<?php echo $res?>)">
                    <span class="any_test">付费金额</span>
                    <span class="any_test"><?php echo empty($this->_dayupsum['paymoney'])?'0':$this->_dayupsum['paymoney'] ?></span>
                </div>
                <div class="list_any <?php echo ($page==5)?'u_do':'' ?>" onclick="theurl(5,<?php echo $res?>)">
                    <span class="any_test">付费率</span>
                    <span class="any_test"><?php echo empty($this->_dayupsum['adduser'])?'0':sprintf("%.4f",$this->_dayupsum['newpayuser']/$this->_dayupsum['adduser'])*100 ?>%</span>
                </div>
                <div class="list_any <?php echo ($page==6)?'u_do':'' ?>" onclick="theurl(6,<?php echo $res?>)">
                    <span class="any_test">ARPU</span>
                    <span class="any_test"><?php echo empty($this->_dayupsum['adduser'])?'0':sprintf("%.2f",$this->_dayupsum['paymoney']/$this->_dayupsum['adduser']) ?></span>
                </div>
            <?php }elseif($_SESSION['admingroupid']==9){?>
                <div class="list_any <?php echo ($page==5)?'u_do':'' ?>" onclick="theurl(5,<?php echo $res?>)">
                    <span class="any_test">付费率</span>
                    <span class="any_test"><?php echo empty($this->_dayupsum['adduser'])?'0':sprintf("%.4f",$this->_dayupsum['newpayuser']/$this->_dayupsum['adduser'])*100 ?>%</span>
                </div>
                <div class="list_any <?php echo ($page==6)?'u_do':'' ?>" onclick="theurl(6,<?php echo $res?>)">
                    <span class="any_test">ARPU</span>
                    <span class="any_test"><?php echo empty($this->_dayupsum['adduser'])?'0':sprintf("%.2f",$this->_dayupsum['paymoney']/$this->_dayupsum['adduser']) ?></span>
                </div>
            <?php }?>
        <?php }?>
    </div>
    <?php }?>
    <?php  if($res!=7){?>
    <div id="zhexian" style="width: 100%;height:300px;margin-top: 0px;">

    </div>
    <?php  }?>
    <?php  if($res==7 ){?>
    <?php  if($page==1 ){?>
            <div id="zhexianman" style="width: 100%;height:300px;margin-top: 0px;">

            </div>
            <div id="zhexian" style="width: 100%;height:300px;margin-top: 0px;display:none">

            </div>
        <?php }else{?>
            <div id="zhexianman" style="width: 100%;height:300px;margin-top: 0px;display:none">

            </div>
            <div id="zhexian" style="width: 100%;height:300px;margin-top: 0px;">

            </div>

        <?php }?>
    <?php }?>
</div>

<script>
    function  theurl(page,res) {
        if(res==1){
            location.href='?r=admin/wuTjuserall/index&page='+page;
        }else if(res == 5){
            location.href='?r=admin/wuTjuserall/payindex&page='+page;
        }else if(res == 6){
            location.href='?r=admin/wuTjuserall/paychang&page='+page;
        }else if(res == 7){
            location.href='?r=admin/wuTjuserall/paymoney&page='+page;
        }else if(res == 11){
            location.href='?r=admin/wuTjuserall/qudao&page='+page;
        }else if(res == 111){
            location.href='?r=admin/wuTjuserall/kzz&page='+page;
        }else{
            location.href='?r=admin/wuTjuserall/ztqs&page='+page;
        }

    }
    function tmtype(page,type,res) {
        if(res == 2){
            location.href='?r=admin/wuTjuserall/ztqs&page='+page+'&timetype='+type;
        }else if(res == 5){
            location.href='?r=admin/wuTjuserall/payindex&page='+page+'&timetype='+type;
        }else if(res == 6){
            location.href='?r=admin/wuTjuserall/paychang&page='+page+'&timetype='+type;
        }else if(res == 7){
            location.href='?r=admin/wuTjuserall/paymoney&page='+page+'&timetype='+type;
        }else if(res == 11){
            location.href='?r=admin/wuTjuserall/qudao&page='+page+'&timetype='+type;
        }else{
            location.href='?r=admin/wuTjuserall/paymoney&page='+page;
        }

    }
    </script>