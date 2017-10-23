<?php
/**
 * The following variables are available in this template:
 * - $this: the CrudCode object
 */
?>
<?php echo "<?php\n"; ?>
/* @var $this <?php echo $this->getControllerClass(); ?> */
/* @var $model <?php echo $this->getModelClass(); ?> */
/* @var $form CActiveForm */
?>

<?php echo "<?php if (CHtml::errorSummary(\$model)):?>" ?>
<table id="tips">
    <tr>
        <td>
            <div class="erro_div">
                <span class="error_message"> <?php echo "<?php echo CHtml::errorSummary(\$model); ?>\n" ?> </span>
            </div>
        </td>
    </tr>
</table>
<?php echo "<?php endif;?>\n" ?>
<?php echo "<?php \$form = \$this->beginWidget('CActiveForm');?>\n" ?>
<table class="form_table">
    <?php
    foreach ($this->tableSchema->columns as $column) {
        if ($column->autoIncrement || strpos($column->name, "time") !== false) {
            continue;
        }
        if (strpos($column->name, "status") !== false) {
            ?>
            <tr>
                <td class="tb_title">
                    <?php echo "<?php echo " . $this->generateActiveLabel($this->modelClass, $column) . "; ?>"; ?>
                </td>
            </tr>
            <tr>
                <td><?php echo "<?php echo \$form->dropDownList(\$model, '{$column->name}', array('1' =>'显示','0'=>'隐藏' )); ?>"?></td>
            </tr>
            <?php
        } else {
            ?>
            <tr>
                <td class="tb_title">
                    <?php echo "<?php echo " . $this->generateActiveLabel($this->modelClass, $column) . "; ?>"; ?>
                </td>
            </tr>
            <tr>
                <td><?php echo "<?php echo " . $this->generateActiveField($this->modelClass, $column) . "; ?>"; ?></td>
            </tr>
            <?php
        }
    }
    ?>

    <tr class="submit">
        <td><input type="submit" name="editsubmit" value="<?php echo Yii::t('common', 'Submit'); ?>" class="button"
                   tabindex="3"/></td>
    </tr>
</table>
<?php echo "<?php \$this->endWidget(); ?>\n"; ?>

