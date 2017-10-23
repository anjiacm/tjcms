<?php $t_statsArray = array(
    '1' => '<span class="color_show">√</span>',
    '0' => '<span class="color_hide">×</span>'
);?>
<?php

/* @var $this WuTjuserallController */
?>
<link rel="stylesheet" type="text/css" href="<?php echo $this->_static_public ?>/Css/manhead.css" />
<script src="<?php echo $this->_static_public ?>/echarts.min.js"></script>
<?php $this->renderPartial('head'); ?>


<script>


    var listall =<?php echo json_encode($datalist) ?>;
    var hoursall =<?php echo json_encode($this->_hoursall) ?>;
    var alltj =<?php echo json_encode($alltj) ?>;
    console.log(zuolistall);
    var apptime =[],appnum =[],zuoapptime =[],zuoappnum =[],allsums=0;
    for(var i =1;i<listall.length;i++){
        apptime.push(listall[i-1]['time']);
        appnum.push(listall[i-1]['mannum']);
        allsums=listall[i]['mannum'];
    }

    for(var zi =0;zi<zuolistall.length;zi++){
        zuoapptime.push(zuolistall[zi]['time']);
        zuoappnum.push(zuolistall[zi]['mannum']);
        // allsums=listall[i]['mannum'];
    }

    $('.mannewnums').html(allsums);
    var myChart = echarts.init(document.getElementById('zhexian'));

    //=============================================  里面直接复制
    option = {
        tooltip : {
            trigger: 'axis'
        },
        legend: {
            data:['今日','昨日','7天前','30天前']
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
                name:'今日',
                type:'line',
                //stack: '总量',
                data:alltj['dayhours']
            },
            {
                name:'昨日',
                type:'line',
               // stack: '总量',
                data:alltj['todayhours']
            },
            {
                name:'7天前',
                type:'line',
              //  stack: '总量',
                data:alltj['sevenhours']
            },
            {
                name:'30天前',
                type:'line',
             //   stack: '总量',
                data:alltj['yuehours']
            },

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