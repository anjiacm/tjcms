<?php
/* @var $this PhStatisticsController */
/* @var $model PhStatistics */
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
                    <?php echo $form->labelEx($model,'content'); ?>                </td>
            </tr>
            <tr>
                <td><?php echo $form->textArea($model,'content',array('rows'=>6, 'cols'=>50)); ?></td>
            </tr>


            
    <tr class="submit">
        <td><input type="submit" name="editsubmit" value="提交" class="button"
                   tabindex="3"/></td>
    </tr>
</table>
<?php $this->endWidget(); ?>

