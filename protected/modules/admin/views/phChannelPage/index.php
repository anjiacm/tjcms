<?php $t_statsArray = array(
    '1' => '<span class="color_show">√</span>',
    '0' => '<span class="color_hide">×</span>'
);
$t_channel = Helper::arrayMap($this->_channel_list, 'id', 'title');
?>
<?php

/* @var $this PhChannelPageController */
?>
<div id="contentHeader">
    <h3>Ph Channel Pages</h3>
    <div class="searchArea">
        <ul class="action left">
            <li><a href="<?php echo $this->createUrl('create') ?>" class="actionBtn"><span>添加</span></a></li>
            <li><a href="<?php echo $this->createUrl('CreateUrls') ?>" class="actionBtn"><span>批量生成连接</span></a></li>
            <li><a href="<?php echo $this->createUrl('CreateHtmls') ?>" class="actionBtn"><span>批量生成页面</span></a></li>
        </ul>
        <div class="search right">
            <?php $this->beginWidget('CActiveForm', array('id' => 'searchForm', 'method' => 'get', 'htmlOptions' => array('name' => 'xform'))); ?>
            <input name="searchsubmit" type="submit" class="button"
                   value="查询"/>
            <?php $this->endWidget(); ?>
        </div>
    </div>
</div>

<?php $form = $this->beginWidget('CActiveForm', array('action' => $this->createUrl('batch'), 'htmlOptions' => array('name' => 'cpform'))); ?>
<table class="content_list">
    <tr class="tb_header">
        <th width=\"10%\"><?php echo $form->label($model, 'id'); ?></th>
        <th width=\"10%\"><?php echo $form->label($model, 'sort_order'); ?></th>
        <th width=\"10%\"><?php echo $form->label($model, 'channel_title'); ?></th>
        <th width=\"10%\"><?php echo $form->label($model, 'channel_id'); ?></th>
        <th width=\"10%\"><?php echo $form->label($model, 'content_title'); ?></th>
        <th width=\"10%\"><?php echo $form->label($model, 'page_id'); ?></th>
        <th width=\"10%\"><?php echo $form->label($model, 'page_url'); ?></th>
        <th width=\"10%\"><?php echo $form->label($model, 'playtime'); ?></th>
        <th width=\"10%\"><?php echo $form->label($model, 'theme'); ?></th>
        <th width=\"10%\"><?php echo $form->label($model, 'createtime'); ?></th>
        <th width=\"10%\"><?php echo $form->label($model, 'updatetime'); ?></th>
        <th>操作</th>
    </tr>
    <?php foreach ($datalist as $row): ?>
        <tr class="tb_list">

            <td><input type="checkbox" name="id[]"
                       value="<?php echo $row->id ?>"/><?php echo $row->id ?>                    </td>
            <td><?php echo $form->textField($row, 'sort_order', array('name' => 'order[' . $row->id . ']', 'size' => '3')); ?></td>
            <td><?php echo $row->channel_title ?></td>
            <td><?php echo $t_channel[$row->channel_id] ?></td>
            <td><?php echo $row->content_title ?></td>
            <td><?php echo $row->page_id ?></td>
            <td><a href="<?php echo $row->page_url ?>" target="_blank"><?php echo $row->page_url ?></a></td>
            <td><?php echo $row->playtime ?></td>
            <td><?php echo $row->theme ?></td>
            <td><?php echo date('Y-m-d H:i', $row->createtime) ?></td>
            <td><?php echo date('Y-m-d H:i', $row->updatetime) ?></td>
            <td>
                <a href="<?php echo $this->createUrl('update', array('id' => $row->id)) ?>">
                    <img src="<?php echo $this->module->assetsUrl; ?>/images/update.png"
                         align="absmiddle"/></a>&nbsp;&nbsp;
                <a href="<?php echo $this->createUrl('batch', array('command' => 'delete', 'id' => $row->id)) ?>"
                   class="confirmSubmit">
                    <img src="<?php echo $this->module->assetsUrl; ?>/images/delete.png"
                         align="absmiddle"/></a>
                <a href="<?php echo $this->createUrl('createHtml', array('id' => $row->id)) ?>" class="button">生成页面</a>
                <a href="<?php echo $this->createUrl('createUrl', array('id' => $row->id)) ?>" class="button">生成内容连接</a>
            </td>
        </tr>
    <?php endforeach; ?>
    <tr class="submit">
        <td colspan="5">
            <div class="cuspages right">
                <?php $this->widget('CLinkPager', array('pages' => $pagebar)); ?>                </div>
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
