<?php
/* @var $this VipLogController */
/* @var $model VipLog */
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
                    <?php echo $form->labelEx($model,'uid'); ?>                </td>
            </tr>
            <tr>
                <td><?php echo $form->textField($model,'uid'); ?></td>
            </tr>
                        <tr>
                <td class="tb_title">
                    <?php echo $form->labelEx($model,'device_id'); ?>                </td>
            </tr>
            <tr>
                <td><?php echo $form->textField($model,'device_id',array('size'=>50,'maxlength'=>50)); ?></td>
            </tr>
                        <tr>
                <td class="tb_title">
                    <?php echo $form->labelEx($model,'money'); ?>                </td>
            </tr>
            <tr>
                <td><?php echo $form->textField($model,'money',array('size'=>11,'maxlength'=>11)); ?></td>
            </tr>
                        <tr>
                <td class="tb_title">
                    <?php echo $form->labelEx($model,'pay_type'); ?>                </td>
            </tr>
            <tr>
                <td><?php echo $form->textField($model,'pay_type'); ?></td>
            </tr>
                        <tr>
                <td class="tb_title">
                    <?php echo $form->labelEx($model,'order_id'); ?>                </td>
            </tr>
            <tr>
                <td><?php echo $form->textField($model,'order_id',array('size'=>60,'maxlength'=>100)); ?></td>
            </tr>
                        <tr>
                <td class="tb_title">
                    <?php echo $form->labelEx($model,'trade_no'); ?>                </td>
            </tr>
            <tr>
                <td><?php echo $form->textField($model,'trade_no',array('size'=>60,'maxlength'=>100)); ?></td>
            </tr>
                        <tr>
                <td class="tb_title">
                    <?php echo $form->labelEx($model,'type_id'); ?>                </td>
            </tr>
            <tr>
                <td><?php echo $form->textField($model,'type_id'); ?></td>
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
                    <?php echo $form->labelEx($model,'recharge_type'); ?>                </td>
            </tr>
            <tr>
                <td><?php echo $form->textField($model,'recharge_type'); ?></td>
            </tr>
                        <tr>
                <td class="tb_title">
                    <?php echo $form->labelEx($model,'channel'); ?>                </td>
            </tr>
            <tr>
                <td><?php echo $form->textField($model,'channel',array('size'=>50,'maxlength'=>50)); ?></td>
            </tr>
                        <tr>
                <td class="tb_title">
                    <?php echo $form->labelEx($model,'version_name'); ?>                </td>
            </tr>
            <tr>
                <td><?php echo $form->textField($model,'version_name',array('size'=>50,'maxlength'=>50)); ?></td>
            </tr>
                        <tr>
                <td class="tb_title">
                    <?php echo $form->labelEx($model,'last_watching'); ?>                </td>
            </tr>
            <tr>
                <td><?php echo $form->textField($model,'last_watching',array('size'=>60,'maxlength'=>100)); ?></td>
            </tr>
            
    <tr class="submit">
        <td><input type="submit" name="editsubmit" value="提交" class="button"
                   tabindex="3"/></td>
    </tr>
</table>
<?php $this->endWidget(); ?>

