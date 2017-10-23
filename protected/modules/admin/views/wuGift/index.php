<?php $t_statsArray = array(
    '0' => '<span class="color_show">正常</span>',
    '1' => '<span class="color_hide" style="color:red">停用</span>'
);?>
<?php

    /* @var $this WuGiftController */
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
        <h3>Wu Gifts</h3>
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
                                <th width=\"10%\"><?php echo $form->label($model, 'giftname');?></th>
                                <th width=\"10%\"><?php echo $form->label($model, 'gifturl');?></th>
                                <th width=\"10%\"><?php echo $form->label($model, 'giftimg');?></th>
            <th width=\"10%\"><?php echo $form->label($model, 'giftqd');?></th>
                                <th width=\"10%\">
                                    <select  id="bigtype" onchange="typedo(this);">
                                        <?php foreach ($this->_goodstypelist as $key=>$typerow): ?>
                                            <option value="<?php echo $key ?>"> <?php echo $typerow?></option>
                                        <?php endforeach; ?>

                                    </select>
                                </th>
                                <th width=\"10%\"><?php echo $form->label($model, 'giftorder');?></th>

                <th width=\"10%\"><?php echo $form->label($model, 'giftmoney');?></th>
                                <th width=\"10%\"><?php echo $form->label($model, 'giftnewmoney');?></th>
                                <th width=\"10%\"><?php echo $form->label($model, 'giftnum');?></th>
                                <th width=\"10%\"><?php echo $form->label($model, 'giftdate');?></th>
                                <th width=\"10%\"><?php echo $form->label($model, 'giftpower');?></th>

            <th>操作</th>
        </tr>
        <?php foreach ($datalist as $row): ?>        <tr class="tb_list">

                                <td><input type="checkbox" name="id[]"
                               value="<?php echo $row->id?>"/><?php echo $row->id?>                    </td>
                                    <td><?php echo $row->giftname?></td>
            <td><a href="<?php echo $row->gifturl?>">点击查看</a></td>

                                <td><img src="<?php echo $row->giftimg?>" style="width:100px;height: 100px;overflow: hidden;"></td>
            <td><img src="<?php echo $this->_goodsqdlist['s_goodsimg'][$row->giftqd]?>" style="width: 100px;height: 100px;overflow: hidden;"></td>
                                <td><?php echo $this->_goodstypelist[$row->gifttype]?></td>
                                    <td>
                                        <input onchange="orderdo(this,'<?php echo $row->id ?>','<?php echo $row->gifttype ?>');" value="<?php echo $row->giftorder ?>">
                                    </td>

                <td><?php echo $row->giftmoney?></td>
                                <td><?php echo $row->giftnewmoney?></td>
                                <td><?php echo $row->giftnum?></td>
                                <td><?php echo date('Y-m-d H:i:s',$row->giftdate)?></td>
                                <td><?php echo $t_statsArray[$row->giftpower]?></td>


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
        location.href='?r=admin/WuGift/index/bigtypeid/'+$thetype;
    }
    function  orderdo(abj,appid,smtypeid) {
        var theordernum= $(abj).val();
        //alert(theordernum+'//'+typeid+'//'+bigtypeid);
        $.post("<?php echo $this->createUrl('WuGift/orderdo'); ?>",{neworder:theordernum,giftid:appid,gifttype:smtypeid},
            function(data){

                alert(data.msg);
                location.reload()
            },'json')
    }
    </script>
