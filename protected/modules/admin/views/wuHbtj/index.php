<?php $t_statsArray = array(
    '0' => '<span class="color_show">正常</span>',
    '1' => '<span class="color_hide" style="color:red">停用</span>'
);
?>
<?php

    /* @var $this WuHbtjController */
    ?>
    <div id="contentHeader">
        <h3>Wu Hbtjs</h3>
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
                                <th width=\"10%\"><?php echo $form->label($model, 'hbimg');?></th>
                                <th width=\"10%\"><?php echo $form->label($model, 'hburl');?></th>
                                <th width=\"10%\">
                                    <select  id="bigtype" onchange="typedo(this);">
                                        <?php foreach ($this->_goodstypelist as $key=>$typerow): ?>
                                            <option value="<?php echo $key ?>"> <?php echo $typerow?></option>
                                        <?php endforeach; ?>

                                    </select>
                                </th>
                                <th width=\"10%\"><?php echo $form->label($model, 'hborder');?></th>
                                <th width=\"10%\"><?php echo $form->label($model, 'hbdodate');?></th>

                <th width=\"10%\"><?php echo $form->label($model, 'hbtext');?></th>
                                <th width=\"10%\"><?php echo $form->label($model, 'hbpower');?></th>

            <th>操作</th>
        </tr>
        <?php foreach ($datalist as $row): ?>        <tr class="tb_list">

                                <td><input type="checkbox" name="id[]"
                               value="<?php echo $row->id?>"/><?php echo $row->id?>                    </td>
                                    <td><img src="<?php echo $row->hbimg?>" style="width: 190px;height: 60px;overflow: hidden;"></td>
            <td><a href="<?php echo $row->hburl?>">点击查看</a></td>
                                <td><?php echo $this->_goodstypelist[$row->hbtypeid]?></td>
                                    <td>
                                        <input onchange="orderdo(this,'<?php echo $row->id ?>','<?php echo $row->hbtypeid ?>');" value="<?php echo $row->hborder ?>">
                                    </td>
                                    <td><?php echo date('Y-m-d H:i:s',$row->hbdodate)?></td>

                <td><?php echo $row->hbtext?></td>
            <td><?php echo $t_statsArray[$row->hbpower]?></td>


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

                        <option value="delete">删除</option>

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
    function  typedo(abj) {
        $thetype = $(abj).val();
        location.href='?r=admin/WuHbtj/index/bigtypeid/'+$thetype;
    }
    function  orderdo(abj,appid,bigtypeid) {
        var theordernum= $(abj).val();
        //alert(theordernum+'//'+typeid+'//'+bigtypeid);
        $.post("<?php echo $this->createUrl('WuHbtj/orderdo'); ?>",{neworder:theordernum,hbid:appid,hbtypeid:bigtypeid},
            function(data){

                alert(data.msg);
                location.reload()
            },'json')
    }
    </script>