<?php
/* @var $this WuGoodstypeController */
/* @var $model WuGoodstype */
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
                    <?php echo $form->labelEx($model,'goodstype'); ?>                </td>
            </tr>
            <tr>
                <td><?php echo $form->textField($model,'goodstype',array('size'=>60,'maxlength'=>255)); ?></td>
            </tr>

                        <tr>
                <td class="tb_title">
                    <?php echo $form->labelEx($model,'goodspower'); ?>                </td>
            </tr>
            <tr>
                <td><?php echo $form->dropDownList($model, 'goodspower', array('0' =>'正常','1'=>'禁用' )); ?></td>
            </tr>
            
    <tr class="submit">
        <td><input type="submit" name="editsubmit" value="提交" class="button"
                   tabindex="3"/></td>
    </tr>
</table>
<?php $this->endWidget(); ?>

