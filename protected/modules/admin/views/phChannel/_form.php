<?php
/* @var $this PhchannelController */
/* @var $model PhChannel */
/* @var $form CActiveForm */
?>

<?php if (CHtml::errorSummary($model)):?><table id="tips">
    <tr>
        <td>
            <div class="erro_div">
                <span class="error_message"> <?php echo CHtml::errorSummary($model); ?>
 </span>
            </div>
        </td>
    </tr>
</table>
<?php endif;?>
<?php $form = $this->beginWidget('CActiveForm');?>
<table class="form_table">
                <tr>
                <td class="tb_title">
                    <?php echo $form->labelEx($model,'title'); ?>                </td>
            </tr>
            <tr>
                <td><?php echo $form->textField($model,'title',array('size'=>60,'maxlength'=>100)); ?></td>
            </tr>
                        <tr>
                <td class="tb_title">
                    <?php echo $form->labelEx($model,'sub_title'); ?>                </td>
            </tr>
            <tr>
                <td><?php echo $form->textField($model,'sub_title',array('size'=>30,'maxlength'=>30)); ?></td>
            </tr>
    <tr>
        <td class="tb_title">
            <?php echo $form->labelEx($model,'domain'); ?>                </td>
    </tr>
    <tr>
        <td><?php echo $form->textField($model,'domain',array('size'=>60,'maxlength'=>100)); ?></td>
    </tr>
                        <tr>
                <td class="tb_title">
                    <?php echo $form->labelEx($model,'status'); ?>                </td>
            </tr>
            <tr>
                <td><?php echo $form->dropDownList($model, 'status', array('1' =>'显示','0'=>'隐藏' )); ?></td>
            </tr>
                        <tr>
                <td class="tb_title">
                    <?php echo $form->labelEx($model,'sort_order'); ?>                </td>
            </tr>
            <tr>
                <td><?php echo $form->textField($model,'sort_order'); ?></td>
            </tr>
                        <tr>
                <td class="tb_title">
                    <?php echo $form->labelEx($model,'remark'); ?>                </td>
            </tr>
            <tr>
                <td><?php echo $form->textField($model,'remark',array('size'=>60,'maxlength'=>255)); ?></td>
            </tr>
            
    <tr class="submit">
        <td><input type="submit" name="editsubmit" value="提交" class="button"
                   tabindex="3"/></td>
    </tr>
</table>
<?php $this->endWidget(); ?>

