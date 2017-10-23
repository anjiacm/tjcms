<?php
/* @var $this PhChannelPageController */
/* @var $model PhChannelPage */
/* @var $form CActiveForm */
?>

<?php if (CHtml::errorSummary($model)): ?>
    <table id="tips">
    <tr>
        <td>
            <div class="erro_div">
                <span class="error_message"> <?php echo CHtml::errorSummary($model); ?>
 </span>
            </div>
        </td>
    </tr>
    </table>
<?php endif; ?>
<?php $form = $this->beginWidget('CActiveForm'); ?>
<table class="form_table">
    <tr>
        <td class="tb_title">
            <?php echo $form->labelEx($model, 'channel_title'); ?>                </td>
    </tr>
    <tr>
        <td><?php echo $form->textField($model, 'channel_title', array('size' => 60, 'maxlength' => 100)); ?></td>
    </tr>
    <tr>
    <tr>
        <td class="tb_title">
            <?php echo $form->labelEx($model, 'channel_id'); ?>                </td>
    </tr>
    <tr>
        <td><?php echo $form->dropDownList($model, 'channel_id', Helper::arrayMap($this->_channel_list, 'id', 'title')); ?></td>
    </tr>
    <tr>
        <td class="tb_title">
            <?php echo $form->labelEx($model, 'content_title'); ?>                </td>
    </tr>
    <tr>
        <td><?php echo $form->textField($model, 'content_title', array('size' => 60, 'maxlength' => 100)); ?></td>
    </tr>
    <tr>
        <td class="tb_title">
            <?php echo $form->labelEx($model, 'content_sub_title'); ?>                </td>
    </tr>
    <tr>
        <td><?php echo $form->textField($model, 'content_sub_title', array('size' => 60, 'maxlength' => 100)); ?></td>
    </tr>
    <tr>
        <td class="tb_title">
            <?php echo $form->labelEx($model, 'page_id'); ?>                </td>
    </tr>
    <tr>
        <td><?php echo $form->textField($model, 'page_id'); ?></td>
    </tr>
    <tr>
        <td class="tb_title">
            <?php echo $form->labelEx($model, 'page_url'); ?>                </td>
    </tr>
    <tr>
        <td><?php echo $form->textField($model, 'page_url', array('size' => 60, 'maxlength' => 255)); ?></td>
    </tr>
    <tr>
        <td class="tb_title">
            <?php echo $form->labelEx($model, 'android_id'); ?>                </td>
    </tr>
    <tr>
        <td><?php echo $form->checkBoxList($model, 'android_id', PhApplication::getAppSelectList(1),array('separator'=>'','labelOptions'=>array('style'=>'display:inline'))); ?></td>
    </tr>
    <tr>
        <td class="tb_title">
            <?php echo $form->labelEx($model, 'ios_id'); ?>                </td>
    </tr>
    <tr>
        <td><?php echo $form->checkBoxList($model, 'ios_id', PhApplication::getAppSelectList(2),array('separator'=>'','labelOptions'=>array('style'=>'display:inline'))); ?></td>
    </tr>
    <tr>
        <td class="tb_title">
            <?php echo $form->labelEx($model, 'theme'); ?>                </td>
    </tr>
    <tr>
        <td><?php echo $form->dropDownList($model, 'theme', Helper::arrayMap(PhTheme::getThemeSelectList(), 'id', 'title')); ?></td>
    </tr>
    <tr>
        <td class="tb_title">
            <?php echo $form->labelEx($model, 'sort_order'); ?>                </td>
    </tr>
    <tr>
        <td><?php echo $form->textField($model, 'sort_order'); ?></td>
    </tr>
    <tr>
        <td class="tb_title">
            <?php echo $form->labelEx($model, 'playtime'); ?>                </td>
    </tr>
    <tr>
        <td><?php echo $form->textField($model, 'playtime'); ?></td>
    </tr>
    <tr>
        <td class="tb_title">
            <?php echo $form->labelEx($model, 'showtime'); ?>                </td>
    </tr>
    <tr>
        <td><?php echo $form->textField($model, 'showtime'); ?></td>
    </tr>
    <tr>
        <td class="tb_title">
            <?php echo $form->labelEx($model, 'tv_num'); ?>                </td>
    </tr>
    <tr>
        <td><?php echo $form->textField($model, 'tv_num'); ?></td>
    </tr>

    <tr>
        <td class="tb_title">
            <?php echo $form->labelEx($model,'speciallist'); ?>                </td>
    </tr>
    <tr>
        <td><?php echo $form->textField($model,'speciallist',array('size'=>60,'maxlength'=>100,'class'=>'input-text-selected')); ?></td>
    </tr>

    <tr>
        <td class="tb_title">
            <?php echo $form->labelEx($model, 'statistics'); ?>                </td>
    </tr>
    <tr>
        <td><?php echo $form->checkBoxList($model, 'statistics', Helper::arrayMap(PhStatistics::getStatisticsSelectList(),'id','title'),array('separator'=>'','labelOptions'=>array('style'=>'display:inline'))); ?></td>
    </tr>

    <tr>
        <td class="tb_title">
            <?php echo $form->labelEx($model, 'playactorlist'); ?>                </td>
    </tr>
    <tr>
        <td><?php echo $form->checkBoxList($model, 'playactorlist', Helper::arrayMap(PhPlayactor::getPlayactorSelectList(),'id','title'),array('separator'=>'','labelOptions'=>array('style'=>'display:inline'))); ?></td>
    </tr>

    <tr class="submit">
        <td><input type="submit" name="editsubmit" value="提交" class="button"
                   tabindex="3"/></td>
    </tr>
</table>
<div class="ph-popup">
    <div class="ph-title">专题-文章管理</div>
    <ul class="ph-left"></ul>
    <ul class="ph-right"></ul>
    <div class="ph-btn">提交</div>
</div>
<script type="text/javascript" src="<?php echo $this->_static_public ?>/js/plugins/input-text-selected.js"></script>
<script type="text/javascript">
    <?php if($model->speciallist){?>
    var arr=[<?php echo $model->speciallist?>];
    <?php }else{?>
    var arr=[];
    <?php }?>


    var obj=<?php echo CJSON::encode($this->speciallist);?>
</script>
<?php $this->endWidget(); ?>

