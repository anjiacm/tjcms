<?php
/* @var $this WuTjuseruphourController */
/* @var $model WuTjuseruphour */
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
                    <?php echo $form->labelEx($model,'userid'); ?>                </td>
            </tr>
            <tr>
                <td><?php echo $form->textField($model,'userid'); ?></td>
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
                    <?php echo $form->labelEx($model,'dayupdate'); ?>                </td>
            </tr>
            <tr>
                <td><?php echo $form->textField($model,'dayupdate',array('size'=>60,'maxlength'=>255)); ?></td>
            </tr>
                        <tr>
                <td class="tb_title">
                    <?php echo $form->labelEx($model,'quedao'); ?>                </td>
            </tr>
            <tr>
                <td><?php echo $form->textField($model,'quedao',array('size'=>60,'maxlength'=>255)); ?></td>
            </tr>
                        <tr>
                <td class="tb_title">
                    <?php echo $form->labelEx($model,'version_name'); ?>                </td>
            </tr>
            <tr>
                <td><?php echo $form->textField($model,'version_name',array('size'=>60,'maxlength'=>255)); ?></td>
            </tr>
            
    <tr class="submit">
        <td><input type="submit" name="editsubmit" value="提交" class="button"
                   tabindex="3"/></td>
    </tr>
</table>
<?php $this->endWidget(); ?>

