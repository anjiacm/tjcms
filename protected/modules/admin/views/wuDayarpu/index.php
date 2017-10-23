<?php $t_statsArray = array(
                '1' => '<span class="color_show">√</span>',
                '0' => '<span class="color_hide">×</span>'
            );?>
<?php

    /* @var $this WuDayarpuController */
    ?>
<div id="contentHeader">

    <div class="searchArea">

        <div class="search right">
            警告设置：
            <select  id="apprespowers" onchange="power(this);">
                <option value="0">关闭警告</option>
                <option value="1">开启警告</option>
            </select>
        </div>
    </div>
</div>
<table class="content_list" style="margin-top: 20px;">
    <tr class="tb_header">
        <th width=\"10%\">时间</th>
        <th width=\"10%\">周一</th>
        <th width=\"10%\">周二</th>
        <th width=\"10%\">周三</th>
        <th width=\"10%\">周四</th>
        <th width=\"10%\">周五</th>
        <th width=\"10%\">周六</th>
        <th width=\"10%\">周日</th>
    </tr>
    <?php foreach ($this->_hoursall as $key=> $row): ?>
        <tr class="tb_list">

            <td>
              <?php echo $row?>
            </td>
            <td> <input value="<?php echo $cs[$key][1]?>" size="10"  onchange="resdo(this,1,<?php echo $key?>)"><span id="edit1<?php echo $key?>"></span></td>
            <td> <input value="<?php echo $cs[$key][2]?>" size="10"  onchange="resdo(this,2,<?php echo $key?>)"><span id="edit2<?php echo $key?>"></span></td>
            <td> <input value="<?php echo $cs[$key][3]?>" size="10"  onchange="resdo(this,3,<?php echo $key?>)"><span id="edit3<?php echo $key?>"></span></td>
            <td> <input value="<?php echo $cs[$key][4]?>" size="10"  onchange="resdo(this,4,<?php echo $key?>)"><span id="edit4<?php echo $key?>"></span></td>
            <td> <input value="<?php echo $cs[$key][5]?>" size="10"  onchange="resdo(this,5,<?php echo $key?>)"><span id="edit5<?php echo $key?>"></span></td>
            <td> <input value="<?php echo $cs[$key][6]?>" size="10"  onchange="resdo(this,6,<?php echo $key?>)"><span id="edit6<?php echo $key?>"></span></td>
            <td> <input value="<?php echo $cs[$key][7]?>" size="10"  onchange="resdo(this,7,<?php echo $key?>)"><span id="edit7<?php echo $key?>"></span></td>

        </tr>
    <?php endforeach; ?>

</table>

<script>
    $('#apprespowers').val(<?php echo $power['power']?>)
    function resdo(obj,week,time){
        var arpu = $(obj).val();
        $.post("<?php echo $this->createUrl('WuDayarpu/edit'); ?>",{week:week,time:time,arpu:arpu},
            function(data){
                if(data.code==0){
                    $('#edit'+week+time).html('<?php echo $t_statsArray[1]?>');
                }else{
                    $('#edit'+week+time).html('<?php echo $t_statsArray[0]?>');
                }

            },'json')
    }
    function power(obj){
        var power = $(obj).val();
        $.post("<?php echo $this->createUrl('WuDayarpu/power'); ?>",{power:power},
            function(data){
                if(data.code==0){

                }else{
                    alert(data.msg);
                }

            },'json')
    }
    </script>