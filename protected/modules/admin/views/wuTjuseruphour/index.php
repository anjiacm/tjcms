<?php $t_statsArray = array(
                '1' => '<span class="color_show">√</span>',
                '0' => '<span class="color_hide">×</span>'
            );?>
<?php

    /* @var $this WuTjuseruphourController */
    ?>
    <div id="contentHeader">
        <h3>Wu Tjuseruphours</h3>
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
                                <th width=\"10%\"><?php echo $form->label($model, 'userid');?></th>
                                <th width=\"10%\"><?php echo $form->label($model, 'device_id');?></th>
                                <th width=\"10%\"><?php echo $form->label($model, 'dayupdate');?></th>
                                <th width=\"10%\"><?php echo $form->label($model, 'quedao');?></th>
                                <th width=\"10%\"><?php echo $form->label($model, 'version_name');?></th>
                            <th>操作</th>
        </tr>
        <?php foreach ($datalist as $row): ?>        <tr class="tb_list">

                                <td><input type="checkbox" name="id[]"
                               value="<?php echo $row->id?>"/><?php echo $row->id?>                    </td>
                                    <td><?php echo $row->userid?></td>
                                <td><?php echo $row->device_id?></td>
                                <td><?php echo $row->dayupdate?></td>
                                <td><?php echo $row->quedao?></td>
                                <td><?php echo $row->version_name?></td>
                
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
