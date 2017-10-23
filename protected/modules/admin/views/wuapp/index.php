<?php $t_statsArray = array(
    '0' => '<span class="color_show">正常</span>',
    '1' => '<span class="color_hide" style="color:red">停用</span>'
);
?>
<?php

    /* @var $this WuappController */
    ?>
<style>
   tr td {
        text-overflow: ellipsis; /* for IE */
        -moz-text-overflow: ellipsis; /* for Firefox,mozilla */
        overflow: hidden;
        white-space: nowrap;
        border: 0;
        text-align: left
    }
</style>
    <div id="contentHeader">
        <h3>Wu Apps</h3>
        <div class="searchArea">
            <ul class="action left">
                <li><a href="<?php echo $this->createUrl('create') ?>"
                       class="actionBtn"><span>添加</span></a></li>
            </ul>
            <div class="search right">
                <?php $this->beginWidget('CActiveForm', array('id' => 'searchForm', 'method' => 'get', 'htmlOptions' => array('name' => 'xform')));?>                <input name="searchsubmit" type="submit" class="button"
                       value="查询"/>
                <?php $this->endWidget(); ?>
            </div>
        </div>
    </div>

<?php $form = $this->beginWidget('CActiveForm', array('action' => $this->createUrl('batch'), 'htmlOptions' => array('name' => 'cpform')));?>
    <table class="content_list">
        <tr class="tb_header">
                            <th width=\"10%\"><?php echo $form->label($model, 'id');?></th>
                                <th width=\"10%\"><?php echo $form->label($model, 'appname');?></th>
                                <th width=\"10%\">
                                    <select  id="bigtype" onchange="typedo(this);">
                                        <?php foreach ($this->_bigtypelist as $key=>$typerow): ?>
                                            <option value="<?php echo $key ?>"> <?php echo $typerow?></option>
                                        <?php endforeach; ?>

                                    </select>

                                </th>
                                <th width=\"10%\">
                                    <select  id="stype" onchange="stypedo(this);">
                                        <option value="0">全部</option>
                                        <?php foreach ($styperesult as $key=>$styperow): ?>

                                            <option value="<?php echo $styperow['id'] ?>"> <?php echo $styperow['s_typename']?></option>
                                        <?php endforeach; ?>

                                    </select>
                                </th>
                                <th width=\"10%\"><?php echo $form->label($model, 'apptitle');?></th>
                                <th width=\"10%\"><?php echo $form->label($model, 'appdonum');?></th>



                                <th width=\"10%\"><?php echo $form->label($model, 'apporder');?></th>
                                <th width=\"10%\"><?php echo $form->label($model, 'appdodate');?></th>
                                <th width=\"10%\"><?php echo $form->label($model, 'apppower');?></th>

            <th>操作</th>
        </tr>
        <?php foreach ($datalist as $row): ?>        <tr class="tb_list">

                                <td><input type="checkbox" name="id[]"
                               value="<?php echo $row->id?>"/><?php echo $row->id?>                    </td>
                                    <td ><?php echo $row->appname?></td>
                                <td><?php echo  $this->_typelist['bigtype'][$row->appbigtypeid]?></td>
                                <td><?php echo $this->_typelist['stype'][$row->appsmtypeid]?></td>
                                <td><?php echo $row->apptitle?></td>
                                <td><?php echo $row->appdonum?></td>



                                    <td>
                                        <input onchange="orderdo(this,'<?php echo $row->id ?>','<?php echo $row->appbigtypeid ?>','<?php echo $row->appsmtypeid ?>');" value="<?php echo $row->apporder ?>">
                                    </td>
                                    <td><?php echo date('Y-m-d H:i:s',$row->appdodate)?></td>
                                <td><?php echo $t_statsArray[$row->apppower]?></td>


            <td>
                <a href="<?php echo $this->createUrl('update', array('id' => $row->id)) ?>">
                    <img src="<?php echo $this->module->assetsUrl; ?>/images/update.png"
                         align="absmiddle"/></a>&nbsp;&nbsp;
                <a href="<?php echo $this->createUrl('batch', array('command' => 'delete', 'id' => $row->id)) ?>"
                   class="confirmSubmit">
                    <img src="<?php echo $this->module->assetsUrl; ?>/images/delete.png"
                         align="absmiddle"/></a>
            </td>
        </tr>
        <?php endforeach; ?>        <tr class="submit">
            <td colspan="5">
                <div class="cuspages right">
                    <?php  $this->widget('CLinkPager', array('pages' => $pagebar));?>                </div>
                <div class="fixsel">
                    <input type="checkbox" name="chkall" id="chkall" onclick="checkAll(this.form, 'id')"/>
                    <label for="chkall">全选</label>
                    <select name="command">
                        <option value="">选择操作</option>
                        <option value="sortOrder">排序</option>
                        <option value="delete">删除</option>
                        <option value="show">显示</option>
                        <option value="hide">隐藏</option>
                    </select>
                    <input id="submit_maskall" class="button confirmSubmit" type="submit"
                           value="提交" name="maskall"/>
                </div>
            </td>
        </tr>
    </table>

<?php $this->endWidget(); ?>
<script>
    $thepagetype = '<?php echo $bigresult->id?>'
    $('#bigtype').val($thepagetype);
    $stypeid = '<?php echo $stypeid?>'
    $('#stype').val($stypeid);
    function  typedo(abj) {
        $thetype = $(abj).val();
        $sthetype = $('#stype').val();
        location.href='?r=admin/Wuapp/index/bigtypeid/'+$thetype;
    }
    function  stypedo(abj) {
        $thetype = $('#bigtype').val();
        $sthetype = $(abj).val();
        location.href='?r=admin/Wuapp/index/bigtypeid/'+$thetype+'/stype/'+$sthetype;
    }
    function  orderdo(abj,appid,bigtypeid,smtypeid) {
        var theordernum= $(abj).val();
        //alert(theordernum+'//'+typeid+'//'+bigtypeid);
        $.post("<?php echo $this->createUrl('Wuapp/orderdo'); ?>",{neworder:theordernum,appid:appid,bigtypeid:bigtypeid,smtypeid:smtypeid},
            function(data){

                alert(data.msg);
                location.reload()
            },'json')
    }
</script>