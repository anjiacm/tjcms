<?php
/* @var $this WutypeController */
/* @var $model WuType */
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
<link rel="stylesheet" type="text/css" href="<?php echo $this->_static_public ?>/bootstrap.min.css" />
<link rel="stylesheet" type="text/css" href="<?php echo $this->_static_public ?>/style.css" />
<?php $form = $this->beginWidget('CActiveForm');?>
<table class="form_table">
                <tr>
                <td class="tb_title">
                    <?php echo $form->labelEx($model,'s_typename'); ?>                </td>
            </tr>
            <tr>
                <td><?php echo $form->textField($model,'s_typename',array('size'=>60,'maxlength'=>255)); ?></td>
            </tr>
                        <tr>
                <td class="tb_title">
                    <?php echo $form->labelEx($model,'s_bigtypeid'); ?>                </td>
            </tr>
            <tr>

                <td><?php echo $form->dropDownList($model, 's_bigtypeid', $this->_typelist); ?></td>
            </tr>


                        <tr>
                <td class="tb_title">
                    <?php echo $form->labelEx($model,'s_power'); ?>                </td>
            </tr>
            <tr>
                <td><?php echo $form->dropDownList($model, 's_power', array('0' =>'正常','1'=>'禁用' )); ?></td>
            </tr>

            
    <tr class="submit">
        <td><input type="submit" name="editsubmit" value="提交" class="button"
                   tabindex="3"/></td>
    </tr>
</table>

<?php $this->endWidget(); ?>

