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
<div id="mvshuzhuan" style="width: 100%;height:300px;margin-top: 20px;border: 1px solid #C9C9C9">

</div>
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
    $('#qdone').val('<?php echo empty($searchfrom['qdone'])?'':$searchfrom['qdone'] ?>');
    $('#qdtwo').val('<?php echo empty($searchfrom['qdtwo'])?'':$searchfrom['qdtwo'] ?>');
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
        data: <?php echo json_encode($alltj['tablelist']) ?>,
        columns: [{
            sortable: true,
            field: 'id',
            title: '排名'
        },{
            sortable: true,
            field: 'movie',
            title: '影片名'
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
    var alltj =<?php echo json_encode($alltj) ?>;
    var allsums=0;




    $('.mannewnums').html(allsums);



    var getRandomColor = function(){
        return  '#' +
            (function(color){
                return (color +=  '0123456789abcdef'[Math.floor(Math.random()*16)])
                && (color.length == 6) ?  color : arguments.callee(color);
            })('');
    }
    var listcolor =Array();
    for(i=0;i<alltj['mvname'].length;i++){

        listcolor.push(getRandomColor());
    }
    var myChart3 = echarts.init(document.getElementById('mvshuzhuan'));
    option3 = {
        title : {
            text: '付费影片分布图',
            subtext: ''
        },
        tooltip : {
            trigger: 'axis',
            show:false,
        },
        legend: {
            data:['<?php echo date('Y-m-d H:i',$starttime)?>至<?php echo date('Y-m-d H:i',$endtime)?>']
        },
        toolbox: {
            show : true,

            feature : {
                mark : {show: true},
                dataView : {show: true, readOnly: false},
                magicType : {show: true, type: ['line', 'bar']},
                restore : {show: true},
                saveAsImage : {show: true}
            }
        },
        calculable : true,
        xAxis : [
            {
                type : 'category',
                data : alltj['mvname'],
                axisLabel:{
                    interval:0,//横轴信息全部显示
                    rotate:-30,//-30度角倾斜显示
                }

            }
        ],
        yAxis : [
            {

                axisLabel:{

                    formatter: function (value, index) {
                        var texts = [];
                        if (index === 0) {
                            texts.push();
                        }
                        if (index === 2) {
                            texts.push();
                        }
                        if(index==4){
                            texts.push();
                        }

                        return texts;
                    }
                }
            }

        ],
        series : [
            {
                name:'数据',
                type:'bar',
                data:alltj['thesum'],
                itemStyle: {
                    normal: {
                        color: function(params) {
                            // build a color map as your need.
                            var colorList = listcolor;
                            return colorList[params.dataIndex]
                        }

                    }
                }

            }





        ]
    };
    myChart3.setOption(option3);
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
            myChart3.resize();
        })
    });
</script>