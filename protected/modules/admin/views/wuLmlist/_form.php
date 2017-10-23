<?php
/* @var $this WuLmlistController */
/* @var $model WuLmlist */
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
                    <?php echo $form->labelEx($model,'lmname'); ?>                </td>
            </tr>
            <tr>
                <td><?php echo $form->textField($model,'lmname',array('size'=>60,'maxlength'=>255)); ?></td>
            </tr>
                        <tr>
                <td class="tb_title">
                    <?php echo $form->labelEx($model,'lm'); ?>                </td>
            </tr>
            <tr>
                <td><?php echo $form->textField($model,'lm',array('size'=>60,'maxlength'=>255)); ?></td>
            </tr>
                        <tr>
                <td class="tb_title">
                    <?php echo $form->labelEx($model,'dodate'); ?>                </td>
            </tr>
            <tr>
                <td><?php echo $form->textField($model,'dodate'); ?></td>
            </tr>
                        <tr>
                <td class="tb_title">
                    <?php echo $form->labelEx($model,'power'); ?>                </td>
            </tr>
            <tr>
                <td><?php echo $form->textField($model,'power'); ?></td>
            </tr>
            
    <tr class="submit">
        <td><input type="submit" name="editsubmit" value="提交" class="button"
                   tabindex="3"/></td>
    </tr>
</table>
<?php $this->endWidget(); ?>

