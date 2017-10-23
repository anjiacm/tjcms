<?php
/* @var $this WuTjuserallController */
/* @var $model WuTjuserall */
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
                    <?php echo $form->labelEx($model,'quedao'); ?>                </td>
            </tr>
            <tr>
                <td><?php echo $form->textField($model,'quedao',array('size'=>60,'maxlength'=>255)); ?></td>
            </tr>
                        <tr>
                <td class="tb_title">
                    <?php echo $form->labelEx($model,'update'); ?>                </td>
            </tr>
            <tr>
                <td><?php echo $form->textField($model,'update',array('size'=>60,'maxlength'=>255)); ?></td>
            </tr>
                        <tr>
                <td class="tb_title">
                    <?php echo $form->labelEx($model,'version_name'); ?>                </td>
            </tr>
            <tr>
                <td><?php echo $form->textField($model,'version_name',array('size'=>60,'maxlength'=>255)); ?></td>
            </tr>
                        <tr>
                <td class="tb_title">
                    <?php echo $form->labelEx($model,'device_id'); ?>                </td>
            </tr>
            <tr>
                <td><?php echo $form->textField($model,'device_id',array('size'=>60,'maxlength'=>255)); ?></td>
            </tr>
                        <tr>
                <td class="tb_title">
                    <?php echo $form->labelEx($model,'usersum'); ?>                </td>
            </tr>
            <tr>
                <td><?php echo $form->textField($model,'usersum'); ?></td>
            </tr>
            
    <tr class="submit">
        <td><input type="submit" name="editsubmit" value="提交" class="button"
                   tabindex="3"/></td>
    </tr>
</table>
<?php $this->endWidget(); ?>

