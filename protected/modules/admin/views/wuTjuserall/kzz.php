<?php
$t_statsArray = array(
    '1' => '<span class="color_show">√</span>',
    '0' => '<span class="color_hide">×</span>'
);
?>
<link rel="stylesheet" type="text/css" href="<?php echo $this->_static_public ?>/fenye/css/bootstrap.min.css" />
<link rel="stylesheet" href="<?php echo $this->_static_public ?>/fenye/css/bootstrap-table.min.css">
<link rel="stylesheet" type="text/css" href="<?php echo $this->_static_public ?>/timejs/jquery.datetimepicker.css"/>
<link rel="stylesheet" type="text/css" href="<?php echo $this->_static_public ?>/Css/manhead.css" />
<script src="<?php echo $this->_static_public ?>/echarts.min.js"></script>

<?php $this->renderPartial('searchhead', array(
    'page'=>$pages,'res'=>$res,'starttime'=>$starttime, 'endtime'=>$endtime ,
)); ?>
<?php $this->renderPartial('head', array('timetype'=>$timetype,'page'=>$pages,'res'=>$res )); ?>
<table id="table">
</table>

<script src="<?php echo $this->_static_public ?>/timejs/build/jquery.datetimepicker.full.js"></script>
<script src="<?php echo $this->_static_public ?>/fenye/js/bootstrap.min.js"></script>
<script src="<?php echo $this->_static_public ?>/fenye/js/bootstrap-table.min.js"></script>
<script src="<?php echo $this->_static_public ?>/fenye/js/bootstrap-table-zh-CN.min.js"></script>
<script>

    $('#table').bootstrapTable({
        data: <?php echo json_encode($this->_dayupsum['qdlist']) ?>,
        columns: [{
            sortable: true,
            field: 'channel',
            title: '渠道名',
        }, {
            sortable: true,
            field: 'adduser',
            title: '新增用户'
        }, {
            sortable: true,
            field: 'newopenuser',
            title: '活跃用户'
        }, {
            sortable: true,
            field: 'oldadduser',
            title: '老版新增用户'
        }, {
            sortable: true,
            field: 'openuser',
            title: '老版活跃用户'
        }],
        pagination: true,
        pageSize: 24,
        pageList: [24, 50, 100],
//                showRefresh:true,
//                search:true,
    });

</script>
<script>
    $.datetimepicker.setLocale('ch');

    $('.timeall').datetimepicker({
        yearOffset:222,
        lang:'ch',
        timepicker:false,
        format:'Y-m-d',
        formatDate:'Y-m-d'


    });
    $('#devnum').val('<?php echo empty($searchfrom['devnum'])?'':$searchfrom['devnum'] ?>');
    $('#qdone').val('<?php echo empty($searchfrom['qdone'])?'':$searchfrom['qdone'] ?>');
    $('#qdtwo').val('<?php echo empty($searchfrom['qdtwo'])?'':$searchfrom['qdtwo'] ?>');
    $('#bbid').val('<?php echo empty($searchfrom['bb'])?'':$searchfrom['bb'] ?>');
    $('#paydo').val('<?php echo empty($searchfrom['pay'])?'':$searchfrom['pay'] ?>');
    $('#datetimepicker4').val('<?php echo date('Y-m-d',$starttime)?>');
    $('#datetimepickerend').val('<?php echo date('Y-m-d',$endtime)?>');

    function restime(){
        // $('.timeall').datetimepicker('reset');
        $('#qdone').val('');
        $('#qdtwo').val('');
        $('#bbid').val('');
        $('#paydo').val('');
        $('#datetimepicker4').val('<?php echo date('Y-m-d H:i',$starttime)?>');
        $('#datetimepickerend').val('<?php echo date('Y-m-d H:i',$endtime)?>');
    };
    document.onkeydown=function(event){
        var e = event || window.event || arguments.callee.caller.arguments[0];
        if(e && e.keyCode==13){ // enter 键
            $('#loading').show();
            document.getElementById("searchForm").submit();

        }
    };

</script>
<script>

    var hoursall =<?php echo json_encode($this->_datelistall) ?>;
    var allsums=0;
    var qdnameall =<?php echo json_encode($this->_qdlist) ?>


        $('.mannewnums').html(allsums);
    var myChart = echarts.init(document.getElementById('zhexian'));
    var zxlist = <?php echo json_encode($this->_dayupsum['zxlist'])?>;
    var arr =Array();
    var qdname= Array();
    for ( var i in zxlist ){
        var str = zxlist[ i ]// i 就代表 data 里面的 user pass 等等 而data[ i ] 就代表 userName    12121 就是 i 所对应的值；
        var id = i;
        qdname.push(id);
        arr.push( str );
    }
    var zx=Array();

    for(i=0;i<arr.length;i++){
        zx.push( {
            name: qdnameall[qdname[i]],
            type:'line',
           // stack: '总量',
            data:arr[i]
        });
    }

    //=============================================  里面直接复制
    option = {
        tooltip : {
            trigger: 'axis'
        },
        legend: {
            data:<?php echo json_encode($this->_dayupsum['qd'])?>,
            bottom:'0px'
        },
        toolbox: {
            show : true,
            feature : {
                mark : {show: true},
                //dataView : {show: true, readOnly: false},
                magicType : {show: true, type: ['line', 'bar']},
                restore : {show: true},
                // saveAsImage : {show: true}
            }
        },
        calculable : true,
        xAxis : [
            {
                type : 'category',
                boundaryGap : false,
                data : hoursall
            }
        ],
        yAxis : [
            {
                type : 'value'
            }
        ],
        series : zx
    };

    //=============================================  上面直接复制

    myChart.setOption(option);


</script>
<script type="text/javascript">
    $(function(){
        try{
            if (typeof(eval(prettyPrint)) == "function") {
                //代码着色
                prettyPrint();
            }
        }catch(e){}
        $(window).resize(function(){
            myChart.resize();
        })
    });
</script>