<?php
/* @var $this WuDayarpuController */
/* @var $model WuDayarpu */
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
                    <?php echo $form->labelEx($model,'weekday'); ?>                </td>
            </tr>
            <tr>
                <td><?php echo $form->textField($model,'weekday'); ?></td>
            </tr>
                        <tr>
                <td class="tb_title">
                    <?php echo $form->labelEx($model,'arpu'); ?>                </td>
            </tr>
            <tr>
                <td><?php echo $form->textField($model,'arpu'); ?></td>
            </tr>
                        <tr>
                <td class="tb_title">
                    <?php echo $form->labelEx($model,'dodate'); ?>                </td>
            </tr>
            <tr>
                <td><?php echo $form->textField($model,'dodate'); ?></td>
            </tr>
            
    <tr class="submit">
        <td><input type="submit" name="editsubmit" value="提交" class="button"
                   tabindex="3"/></td>
    </tr>
</table>
<?php $this->endWidget(); ?>

