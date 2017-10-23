<?php $this->beginWidget('CActiveForm', array('id' => 'searchForm', 'method' => 'post', 'htmlOptions' => array('name' => 'xform')));?>

<div class="ztqs">

    <div class="leftpage">
        <?php if($res!=11 and $res!=111 and  $res!=20 and  $res!=21){?>
            <select select2    class="quedaoone form-control "  id="qdone"  name="ztqs[qdone]" placeholder="请选择主渠道" onchange="formgo()">
                <option value="">置空</option>
                <?php foreach ( $this->_bbqdlist['qdone'] as $key=>$row): ?>
                    <option value="<?php echo $key?>"><?php echo $row?></option>
                <?php endforeach; ?>

            </select>

        <?php if($res!=21){?>
                <select select2 class="quedaoone form-control "  name="ztqs[qdtwo]"   id="qdtwo" placeholder="请选择子渠道" onchange="formgo()">
                    <option value="">置空</option>
                    <?php foreach ( $this->_bbqdlist['qdthree'] as $key=>$threerow): ?>
                        <option value="<?php echo $key?>"><?php echo $threerow?></option>
                    <?php endforeach; ?>
                </select>
            <?php }?>
        <!--            <input class="qdbutton" onclick="search(this,2)" type="submit"  value="点击查询"/>-->
        <?php }?>
        <?php if($res==20 || $res==21){?>
            <select select2    class="quedaoone form-control "  id="qdone"  name="ztqs[qdone]" placeholder="请选择主渠道" onchange="formgo()">
                <option value="">置空</option>
                <?php foreach ( $this->_kzqdlist['qdone'] as $key=>$onerow): ?>
                    <option   value="<?php echo $key?>"><?php echo $onerow?></option>
                <?php endforeach; ?>

            </select>
            <select select2 class="quedaoone form-control "  name="ztqs[qdtwo]"   id="qdtwo" placeholder="请选择网站" onchange="formgo()">
                <option value="">置空</option>
                <?php foreach ( $this->_kzqdlist['qdtwo'] as $key=>$row): ?>
                    <option value="<?php echo $key?>"><?php echo $row?></option>
                <?php endforeach; ?>
            </select>
            <select select2 class="quedaoone form-control  " name="ztqs[qdthree]"  id="qdthree"  placeholder="请选择影片" onchange="formgo()">
                <option value="">置空</option>
                <?php foreach ( $this->_kzqdlist['qdthree'] as $key=>$threerow): ?>
                    <option value="<?php echo $key?>"><?php echo $threerow?></option>
                <?php endforeach; ?>
            </select>
        <?php }?>
    </div>
    <div class="rightpage">
        <select class="paydo" id="devnum" name="ztqs[devnum]"  onchange="formgo()">
            <option value="">选择系统</option>
            <option value="1">安卓</option>
            <option value="2">IOS</option>
        </select>
        <?php if($res !=20 ){?>
            <select class="banben" id="bbid" name="ztqs[bb]" onchange="formgo()">
                <option value="">全部</option>

                <?php foreach ( $this->_bbqdlist['bb'] as $key=>$bbrow): ?>
                    <option value="<?php echo $key?>"><?php echo $bbrow?></option>
                <?php endforeach; ?>
            </select>
        <?php }?>
        <?php if($res ==20 ){?>
            <select class="banben" id="bbid" name="ztqs[bb]" onchange="formgo()">
                <option value="">全部</option>

                <?php foreach ( $this->_kzqdlist['bb'] as $key=>$bbrow): ?>
                    <option value="<?php echo $key?>"><?php echo $bbrow?></option>
                <?php endforeach; ?>
            </select>
        <?php }?>
        <?php if($res == 7 || $res == 77){?>
        <?php }elseif($res == 20){?>
        <?php }else{?>
        <select class="paydo" id="paydo" name="ztqs[pay]" onchange="formgo()">
            <option value="">支付渠道</option>
            <option value="1">中润付</option>
            <option value="2">微信</option>
            <option value="3">支付宝</option>
        </select>
        <?php }?>
        <?php if($res == 1 || $res == 21 || $res == 20  || $res ==11 ){?>
            <select class="banben" id="timtypeid" name="ztqs[timeall]" onchange="todayform()">
                <?php if($res == 1 ){?>
                <option value="1">今天</option>
                <option value="2">昨天</option>
                <?php }?>
                <?php if($res == 21  || $res ==11 || $res ==20){?>
                    <option value="0">选择日期</option>
                    <option value="3">今天</option>
                    <option value="1">昨天</option>
                    <option value="2">7天前</option>
                <?php }?>
            </select>
        <?php }?>
<!--        --><?php //if($res == 20){?>
<!---->
<!--                <div class="timepage"    >-->
<!---->
<!--                    <input type="text" value=""   placeholder="开始时间" class="timeall timeinput"  name="ztqs[starttime]" id="datetimepicker4"/>-->
<!---->
<!--                    <input id="timeall"  class="timebut" type="button"   onclick="search()" value="点击查询"/>-->
<!---->
<!--                </div>-->
<!--                <input id="excelid"  class="timebut" type="hidden"  name="ztqs[excel]"  value=""/>-->
<!--                <input id="timeall" style="border: none;width: 80px;height: 30px;" class="timebut" type="button"   onclick="excel()" value="导出excel"/>-->
<!---->
<!--        --><?php //}?>
        <?php if($res != 1 ){?>
            <?php if($res==11 || $res==21 || $res==20 ){?>
                    <div class="timepage"  style="display: none;">

                        <input type="text" value=""   placeholder="开始时间" class="timeall timeinput"  name="ztqs[starttime]" id="datetimepicker4"/>
                        <input type="text" value="" class="timeall timeinput" placeholder="结束时间"  name="ztqs[endtime]" id="datetimepickerend"/>

                        <!--            <input id="reset"  class="sitebut"type="button" onclick="restime(this)" value="重置查询"/>-->
                        <input id="timeall"  class="timebut" type="button"   onclick="search()" value="点击查询"/>

                    </div>
                <?php if($res==21 || $res==20){?>
                    <input id="excelid"  class="timebut" type="hidden"  name="ztqs[excel]"  value=""/>
                    <input id="timeall" style="border: none;width: 80px;height: 30px;" class="timebut" type="button"   onclick="excel()" value="导出excel"/>
                <?php }?>
            <?php }else{?>
                <div class="timepage"  >

                    <input type="text" value=""   placeholder="开始时间" class="timeall timeinput"  name="ztqs[starttime]" id="datetimepicker4"/>
                    <input type="text" value="" class="timeall timeinput" placeholder="结束时间"  name="ztqs[endtime]" id="datetimepickerend"/>

                    <!--            <input id="reset"  class="sitebut"type="button" onclick="restime(this)" value="重置查询"/>-->
                    <input id="timeall"  class="timebut" type="button"   onclick="search()" value="点击查询"/>
                    <?php if($res==77){?>
                        <input id="excelid"  class="timebut" type="hidden"  name="ztqs[excel]"  value=""/>
                        <input id="timeall" style="border: none;width: 80px;height: 30px;" class="timebut" type="button"   onclick="excel()" value="导出excel"/>
                    <?php }?>
                </div>
            <?php }?>
        <?php }?>
    </div>
</div>

<div id="loading">
    <div id="loading-center">
        <div id="loading-center-absolute">
            <div class="object" id="object_one"></div>
            <div class="object" id="object_two" style="left:20px;"></div>
            <div class="object" id="object_three" style="left:40px;"></div>
            <div class="object" id="object_four" style="left:60px;"></div>
            <div class="object" id="object_five" style="left:80px;"></div>
            <div style="margin-top:30px;width:200%;color:#ffffff;font-size:18px;text-align:left" id="searchtext">正在查询中，请稍后……</div>
        </div>

    </div>
</div>
<?php $this->endWidget(); ?>
<script>

    $('#timtypeid').val('<?php echo $this->_timetype ?>');
    if($('#timtypeid').val()==0){
        $('.timepage').show()
    }
    function  search() {
        $('#excelid').val('');
        $('#loading').show();
        document.getElementById("searchForm").submit();
    }
    function  excel() {
        $('#excelid').val('1');
        document.getElementById("searchForm").submit();
    }
    function  formgo() {
       // $('#loading').show();
        $('#excelid').val('');
            document.getElementById("searchForm").submit();


    }
    function  todayform() {
        $('#excelid').val('');
        // $('#loading').show();
        if($('#timtypeid').val() == 0){
            $('.timepage').show()
        }else{
            document.getElementById("searchForm").submit();
        }

    }

</script>

