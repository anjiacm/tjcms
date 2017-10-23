
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="zh_cn" lang="zh_cn">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no" />
    <meta name="apple-mobile-web-app-status-bar-style" content=" black "/>
    <meta name="format-detection" content="telephone=no"/>
    <meta name="full-screen" content="yes">
    <meta name="x5-fullscreen" content="true">
    <meta name="language" content="zh_cn" />
    <link rel="stylesheet" type="text/css" href="<?php echo $this->_static_public ?>/tjm/css/common.css" />
    <script type="text/javascript" src="<?php echo $this->_static_public ?>/tjm/js/jquery.min.js"></script>

    <link rel="stylesheet" type="text/css" href="<?php echo $this->_static_public ?>/tjm/css/bootstrap.min.css" />
    <link rel="stylesheet" href="<?php echo $this->_static_public ?>/tjm/css/bootstrap-table.min.css">
    <link rel="stylesheet" href="<?php echo $this->_static_public ?>/tjm/css/manhead.css">
    <script src="<?php echo $this->_static_public ?>/tjm/js/echarts.min.js"></script>
    <title>yiifcms - Payindex WuTjuserall</title>
</head>

<body>


        <div class="onepage layout_two">
            <div class="head">
                <div class="headleft">arpu</div>
                <div class="headright" onclick="go()">刷新数据</div>
            </div>
            <div id="zhexian" style="width: 100%;height:400px;margin-top: 10px;"></div>
        </div>

        <div class="onepage layout_two">
            <div class="head">
                <div class="headleft">今日实时数据</div>
            </div>
            <table id="table"></table>
        </div>
        <script src="<?php echo $this->_static_public ?>/tjm/js/bootstrap.min.js"></script>
        <script src="<?php echo $this->_static_public ?>/tjm/js/bootstrap-table.min.js"></script>
        <script src="<?php echo $this->_static_public ?>/tjm/js/bootstrap-table-zh-CN.min.js"></script>
        <script>

            $('#table').bootstrapTable({
                data:<?php echo json_encode($this->_dayupsum['tablelist']) ?>,
                columns: [{
                   // sortable: true,
                    field: 'dateall',
                    title: '时段'
                }, {
                  //  sortable: true,
                    field: 'arpu',
                    title: 'arpu'
                }, {
                   // sortable: true,
                    field: 'zarpu',
                    title: '昨日'
                }, {
                  //  sortable: true,
                    field: 'sarpu',
                    title: '上周同日'
                }, {
                  //  sortable: true,
                    field: 'ffanduser',
                    title: '付费用户%'
                }, {
                   // sortable: true,
                    field: 'paymoney',
                    title: '金额'
                }, {
                   // sortable: true,
                    field: 'adduser',
                    title: '新增用户'
                }],
                pagination: true,
                pageSize: 25,
                pageList: [25]
//                showRefresh:true,
//                search:true,
            });

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
                    data:['今日','昨日','上周同日'],

                },
                grid: {
                    left: '1%',
                    containLabel: true
                },
                toolbox: {
                    show : false,
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
                        data:alltj['theday'],
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
                        name:'昨日',
                        type:'line',
                        //stack: '总量',
                        data:alltj['today'],
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
                        name:'上周同日',
                        type:'line',
                        //stack: '总量',
                        data:alltj['sevenday'],
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

    <!-- 内容main结束 -->
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
        function  go() {
            window.location.reload();

        }
    </script>

</body>
</html>
