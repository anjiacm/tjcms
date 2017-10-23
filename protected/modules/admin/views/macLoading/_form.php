<?php
/* @var $this MacLoadingController */
/* @var $model MacLoading */
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
                    <?php echo $form->labelEx($model,'name'); ?>                </td>
            </tr>
            <tr>
                <td><?php echo $form->textField($model,'name',array('size'=>60,'maxlength'=>100)); ?></td>
            </tr>
                        <tr>
                <td class="tb_title">
                    <?php echo $form->labelEx($model,'des'); ?>                </td>
            </tr>
            <tr>
                <td><?php echo $form->textField($model,'des',array('size'=>60,'maxlength'=>100)); ?></td>
            </tr>
                        <tr>
                <td class="tb_title">
                    <?php echo $form->labelEx($model,'type'); ?>                </td>
            </tr>
            <tr>
                <td><?php echo $form->textField($model,'type'); ?></td>
            </tr>
                        <tr>
                <td class="tb_title">
                    <?php echo $form->labelEx($model,'hide'); ?>                </td>
            </tr>
            <tr>
                <td><?php echo $form->textField($model,'hide'); ?></td>
            </tr>
            
    <tr class="submit">
        <td><input type="submit" name="editsubmit" value="提交" class="button"
                   tabindex="3"/></td>
    </tr>
</table>
<?php $this->endWidget(); ?>

