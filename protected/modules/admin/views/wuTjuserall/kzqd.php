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

    $('#table').bootstrapTable({
        data: <?php echo json_encode($this->_dayupsum['tablelist']) ?>,
        showFooter:true,
        columns: [{
            sortable: true,
            field: 'channel_all',
            title: '渠道名',
            footerFormatter:'汇总'
        }, {
            sortable: true,
            field: 'adduser',
            title: '新增用户',
            footerFormatter: function (value) {
                var count = 0;

                for (var i in value) {
                    count += parseInt(value[i].adduser);
                }
                return count;
            }
        }, {
            sortable: true,
            field: 'newopenuser',
            title: '活跃用户',
            footerFormatter: function (value) {
                var count = 0;

                for (var i in value) {
                    count += parseInt(value[i].newopenuser);
                }
                return count;
            }
        }, {
            sortable: true,
            field: 'oldadduser',
            title: '老版新增用户',
            footerFormatter: function (value) {
                var count = 0;

                for (var i in value) {
                    count += parseInt(value[i].oldadduser);
                }
                return count;
            }
        }, {
            sortable: true,
            field: 'openuser',
            title: '老版活跃用户',
            footerFormatter: function (value) {
                var count = 0;

                for (var i in value) {
                    count += parseInt(value[i].openuser);
                }
                return count;
            }
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
//        yearOffset:222,
//        lang:'ch',
//        timepicker:false,
//        format:'Y-m-d',
//        formatDate:'Y-m-d'
        format:"Y-m-d H:00"   //格式化日期

    });
    $('#bbid').val('<?php echo empty($searchfrom['bb'])?'':$searchfrom['bb'] ?>');
    $('#paydo').val('<?php echo empty($searchfrom['pay'])?'':$searchfrom['pay'] ?>');
    $('#datetimepicker4').val('<?php echo date('Y-m-d H:i',$starttime)?>');
    $('#datetimepickerend').val('<?php echo date('Y-m-d H:i',$endtime)?>');
    $('#devnum').val('<?php echo empty($searchfrom['devnum'])?'':$searchfrom['devnum'] ?>');

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
