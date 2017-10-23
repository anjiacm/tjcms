<?php
    $t_statsArray = array(
        '1' => '<span class="color_show">√</span>',
        '0' => '<span class="color_hide">×</span>'
    );
?>
<link rel="stylesheet" type="text/css" href="<?php echo $this->_static_public ?>/timejs/jquery.datetimepicker.css"/>
<link rel="stylesheet" type="text/css" href="<?php echo $this->_static_public ?>/Css/manhead.css" />
<link rel="stylesheet" href="<?php echo $this->_static_public ?>/search/select2.css" />
<link rel="stylesheet" href="<?php echo $this->_static_public ?>/search/select2-bootstrap.css" />
<script src="<?php echo $this->_static_public ?>/echarts.min.js"></script>
<?php $this->renderPartial('searchhead', array(
    'page'=>$pages,'res'=>$res,'starttime'=>$starttime, 'endtime'=>$endtime ,
)); ?>
    <?php $this->renderPartial('head', array('listalls'=>$listalls,'page'=>$pages,'res'=>$res )); ?>
    <?php $this->renderPartial('table', array('res'=>$res)); ?>
<script>
    var oneres,twores,threeres;
    oneres =<?php echo empty($searchfrom['qdone'])?'0':$searchfrom['qdone'] ?>;
    twores =<?php echo empty($searchfrom['qdtwo'])?'0':$searchfrom['qdtwo'] ?>;
</script>
<script src="<?php echo $this->_static_public ?>/search/angular.min.js"></script>
<script src="<?php echo $this->_static_public ?>/search/select2.min.js"></script>
<script src="<?php echo $this->_static_public ?>/search/index.js"></script>
<script src="<?php echo $this->_static_public ?>/timejs/build/jquery.datetimepicker.full.js"></script>
<script>
    $.datetimepicker.setLocale('ch');

    $('.timeall').datetimepicker({
        format:"Y-m-d H:00"   //格式化日期

    });

    $('#bbid').val('<?php echo empty($searchfrom['bb'])?'':$searchfrom['bb'] ?>');
    $('#devnum').val('<?php echo empty($searchfrom['devnum'])?'':$searchfrom['devnum'] ?>');
    $('#paydo').val(<?php echo empty($searchfrom['pay'])?'':$searchfrom['pay'] ?>);
    $('#datetimepicker4').val('<?php echo date('Y-m-d H:i',$starttime)?>');
    $('#datetimepickerend').val('<?php echo date('Y-m-d H:i',$endtime)?>');

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

    var hoursall =<?php echo json_encode($this->_hoursall) ?>;
    var alltj =<?php echo json_encode($this->_dayupsum['zxtu']) ?>;
    var allsums=0;




    $('.mannewnums').html(allsums);
    var myChart = echarts.init(document.getElementById('zhexian'));

    //=============================================  里面直接复制
    option = {
        tooltip : {
            trigger: 'axis'
        },
        legend: {
            data:['今日','昨日','7天前','30天前'],

        },
        toolbox: {
            show : true,
            feature : {
                mark : {show: true},
                //dataView : {show: true, readOnly: false},
                magicType : {show: true, type: ['line', 'bar']},
                restore : {show: true},
                //saveAsImage : {show: true}
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
        series : [
            {
                name:'今日',
                type:'line',
                //stack: '总量',
                data:alltj['theday']
            },
            {
                name:'昨日',
                type:'line',
              //  stack: '总量',
                data:alltj['today']
            },
            {
                name:'7天前',
                type:'line',
              //  stack: '总量',
                data:alltj['sevenday']
            },
            {
                name:'30天前',
                type:'line',
               // stack: '总量',
                data:alltj['month']
            },

        ]
    };

    //=============================================  上面直接复制

    myChart.setOption(option);

    function updatesql() {
        $('#loading').show();
        $('#searchtext').html('数据更新中……请不要做任何操作');
        $.post("?r=tjall/taday/index",{},
            function(data){
                if(data.code ===0){
                  // alert(data.msg);
                    $('#searchtext').html('数据更新完成……正在刷新页面');
                   location.reload();
                }else{
                    $('#loading').hide();

                    alert(data.msg);
                }
            },'json')
    }

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