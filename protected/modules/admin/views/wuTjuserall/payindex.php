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
<link rel="stylesheet" href="<?php echo $this->_static_public ?>/search/select2.css" />
<link rel="stylesheet" href="<?php echo $this->_static_public ?>/search/select2-bootstrap.css" />
<script src="<?php echo $this->_static_public ?>/echarts.min.js"></script>
<?php $this->renderPartial('searchhead', array(
    'page'=>$pages,'res'=>$res,'starttime'=>$starttime, 'endtime'=>$endtime ,
)); ?>

<?php $this->renderPartial('head', array('timetype'=>$timetype,'page'=>$pages,'res'=>$res )); ?>
<table id="table">
</table>
<script>
    var oneres,twores,threeres;
    oneres =<?php echo empty($searchfrom['qdone'])?'0':$searchfrom['qdone'] ?>;
    twores =<?php echo empty($searchfrom['qdtwo'])?'0':$searchfrom['qdtwo'] ?>;
    threeres =<?php echo empty($searchfrom['qdthree'])?'0':$searchfrom['qdthree'] ?>;
</script>
<script src="<?php echo $this->_static_public ?>/search/angular.min.js"></script>
<script src="<?php echo $this->_static_public ?>/search/select2.min.js"></script>
<script src="<?php echo $this->_static_public ?>/search/index.js"></script>
<script src="<?php echo $this->_static_public ?>/timejs/build/jquery.datetimepicker.full.js"></script>
<script src="<?php echo $this->_static_public ?>/fenye/js/bootstrap.min.js"></script>
<script src="<?php echo $this->_static_public ?>/fenye/js/bootstrap-table.min.js"></script>
<script src="<?php echo $this->_static_public ?>/fenye/js/bootstrap-table-zh-CN.min.js"></script>
<script>
    $.datetimepicker.setLocale('ch');

    $('.timeall').datetimepicker({
        format:"Y-m-d H:00"   //格式化日期

    });

    $('#devnum').val('<?php echo empty($searchfrom['devnum'])?'':$searchfrom['devnum'] ?>');
    $('#bbid').val('<?php echo empty($searchfrom['bb'])?'':$searchfrom['bb'] ?>');
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

    $('#table').bootstrapTable({
        data:<?php echo json_encode($this->_dayupsum['tablelist']) ?>,
        columns: [{
            sortable: true,
            field: 'dateall',
            title: '日期'
        }, {
            sortable: true,
            field: 'adduser',
            title: '新增用户'
        }, {
            sortable: true,
            field: 'paymoney',
            title: '付费金额'
        }, {
            sortable: true,
            field: 'payuser',
            title: '付费用户'
        }, {
            sortable: true,
            field: 'paysum',
            title: '付费次数'
        }],
        pagination: true,
        pageSize: 24,
        pageList: [24, 50, 100],
//                showRefresh:true,
//                search:true,
    });

</script>

<script>

    var hoursall =<?php echo json_encode($this->_datelistall) ?>;
    var allsums=0;




    $('.mannewnums').html(allsums);
    var myChart = echarts.init(document.getElementById('zhexian'));

    //=============================================  里面直接复制
    option = {
        tooltip : {
            trigger: 'axis'
        },
        legend: {
            data:['付费金额','付费用户','付费次数'],

        },
        toolbox: {
            show : true,
            feature : {
                mark : {show: true},
                dataView : {show: true, readOnly: false},
                magicType : {show: true, type: ['line', 'bar', 'stack', 'tiled']},
                restore : {show: true},
                saveAsImage : {show: true}
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
                name:'付费金额',
                type:'line',
                //stack: '总量',
                data:<?php echo json_encode($this->_dayupsum['zxianpaymoney'])?>,
                itemStyle: {
                    normal: {

                        label: {
                            show: true,//是否展示
                            position:'top'
                        }
                    }
                }
            },
            {
                name:'付费用户',
                type:'line',
                //stack: '总量',
                data:<?php echo json_encode($this->_dayupsum['zxianpayuser'])?>,
                itemStyle: {
                    normal: {

                        label: {
                            show: true,//是否展示
                            position:'top'
                        }
                    }
                }
            },
            {
                name:'付费次数',
                type:'line',
                //stack: '总量',
                data:<?php echo json_encode($this->_dayupsum['zxianpaysum'])?>,
                itemStyle: {
                    normal: {

                        label: {
                            show: true,//是否展示
                            position:'top'
                        }
                    }
                }
            }


        ]
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