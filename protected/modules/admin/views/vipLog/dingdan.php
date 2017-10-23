<?php $t_statsArray = array(
    '1' => '<span class="color_show">√</span>',
    '0' => '<span class="color_hide">×</span>'
);?>
<?php

/* @var $this VipLogController */
?>
<link rel="stylesheet" type="text/css" href="<?php echo $this->_static_public ?>/timejs/jquery.datetimepicker.css"/>
<script src="<?php echo $this->_static_public ?>/search/search.min.js"></script>
<link rel="stylesheet" href="<?php echo $this->_static_public ?>/search/search.min.css">
<div id="contentHeader">
    <h3>Vip Logs</h3>
    <div class="searchArea">
        <ul class="action left">
            <div   class="actionBtn">
                <input type="text" value="" style="width: 150px;" placeholder="开始时间" class="timeall" id="datetimepicker4"/>
                <input type="text" value="" class="timeall" style="width: 150px;" placeholder="结束时间" id="datetimepickerend"/>
                <input id="reset" type="button" onclick="restime(this)" value="重置"/>
                <input id="timeall" type="button" onclick="search(this,4)" value="查询时间段"/>

                <span style="margin-left: 20px;">渠道查询</span>
                <input type="text"  style="width: 150px;" placeholder="输入内容查询" id="qdtextid"/>
                <input id="qdbutton" onclick="search(this,2)" type="button"  value="点击查询"/>
            </div>
        </ul>
        <div class="search right">

            <span>时段查询</span>
            <select id="tmdayid" style="margin-left: 10px;" onchange="search(this,1);">
                <option value="7">7天内数据</option>
                <option value="15">15天内数据</option>
                <option value="30">30天内数据</option>
            </select>
            <span>版本查询</span>
            <select id="bbid" style="margin-left: 10px;" onchange="search(this,3);">
                <option value="1.0.1">1.0.1</option>
            </select>
        </div>
    </div>
</div>

<?php $form = $this->beginWidget('CActiveForm', array('action' => $this->createUrl('batch'), 'htmlOptions' => array('name' => 'cpform')));?>
<table class="content_list">
    <tr class="tb_header">
        <th width=\"10%\">
            <select id="ttsle" onchange="daytime(this);">
                <option value="1">按天</option>
                <option value="2">按时</option>
            </select>
        </th>
        <th width=\"10%\">新增</th>
        <th width=\"10%\">收入</th>
        <th width=\"10%\">arpu</th>

        <!--            <th>操作</th>-->
    </tr>
    <?php foreach ($datalist as $row): ?>
        <tr class="tb_list">


        <td>
            <?php if($timetype ==1){ ?>
                <?php echo $row['day']?>
            <?php }else{ ?>
                <?php echo $row['time']?>
            <?php } ?>
        </td>
        <td>暂未统计</td>
        <td><?php echo $row['money']?></td>

            <td>暂未统计</td>


    </tr>
    <?php endforeach; ?>
    <tr class="submit">
        <td colspan="5">
            <div class="cuspages right">
                <?php if($timetype ==2){ ?>
                    <?php  $this->widget('CLinkPager', array('pages' => $pagebar));?>
                <?php } ?>
            </div>


        </td>
    </tr>
</table>
<?php $this->endWidget(); ?>
<script src="<?php echo $this->_static_public ?>/timejs/build/jquery.datetimepicker.full.js"></script>
<script>
    var thedotype=<?php echo $thetype?>;

    if(thedotype==1){
        $('#tmdayid').val('<?php echo $thetime?>');
    }else if(thedotype==2){
        $('#qdtextid').val('<?php echo $qdtext?>');

    }else if(thedotype==3){
        $('#bbid').val('<?php echo $version?>');
    }else if(thedotype==4){
        $('#datetimepicker4').val('<?php echo date('Y-m-d H:i',$starttime)?>');
        $('#datetimepickerend').val('<?php echo date('Y-m-d H:i',$endtime)?>');
    }
    $('#ttsle').val(<?php echo $timetype ?>);
    document.onkeydown=function(event){
        var e = event || window.event || arguments.callee.caller.arguments[0];
        if(e && e.keyCode==13){ // enter 键
            var search=$('#kuid').val();
            if(search){
                searchdo()
            }

        }
    };
    $.datetimepicker.setLocale('ch');

    $('.timeall').datetimepicker({
        format:"Y-m-d H:00"   //格式化日期

    });
    function edittime(obj,type,kuid){
        // console.log( $(obj).parents('td').find('.timeall').val());
        var thetime=$(obj).parents('td').find('.timeall').val();

        $(obj).parents('td').find('.timeall').datetimepicker('hide');
    };

    function restime(){
        $('.timeall').datetimepicker('reset');
    };


    function daytime(obj){
        var timetype = $(obj).val();
        if(thedotype==1){
            var searchall = $('#tmdayid').val();
            location.href='?r=admin/vipLog/dingdan/searchall/'+searchall+'/thetype/'+thedotype+'/timetype/'+timetype;
        }else if(thedotype==2){
            var searchall = $('#qdtextid').val();
            location.href='?r=admin/vipLog/dingdan/searchall/'+searchall+'/thetype/'+thedotype+'/timetype/'+timetype;
        }else if(thedotype==3){
            var searchall = $('#bbid').val();
            location.href='?r=admin/vipLog/dingdan/searchall/'+searchall+'/thetype/'+thedotype+'/timetype/'+timetype;
        }else if(thedotype==4){
            var startime = $('#datetimepicker4').val();
            var endtime = $('#datetimepickerend').val();
            location.href='?r=admin/vipLog/dingdan/starttime/'+startime+'/endtime/'+endtime+'/thetype/'+thedotype+'/timetype/'+timetype;
        }else{
            location.href='?r=admin/vipLog/dingdan/timetype/'+timetype;
        }

    };
    function search(obj,thetype){
         if(thetype==1){
             var searchall = $(obj).val();
             location.href='?r=admin/vipLog/dingdan/searchall/'+searchall+'/thetype/'+thetype;
         }else if(thetype==2){
             var searchall = $('#qdtextid').val();
             location.href='?r=admin/vipLog/dingdan/searchall/'+searchall+'/thetype/'+thetype;
         }else if(thetype==3){
             var searchall = $(obj).val();
             location.href='?r=admin/vipLog/dingdan/searchall/'+searchall+'/thetype/'+thetype;
         }else if(thetype==4){
            var startime = $('#datetimepicker4').val();
             var endtime = $('#datetimepickerend').val();
             location.href='?r=admin/vipLog/dingdan/starttime/'+startime+'/endtime/'+endtime+'/thetype/'+thetype;
         }

    };

</script>

<script>
    $(function() {
        var availableTags = <?php echo json_encode($listall)?>;
        $( "#qdtextid" ).autocomplete({
            source: availableTags
        });
    });
</script>
