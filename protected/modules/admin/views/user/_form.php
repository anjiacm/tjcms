<?php if (CHtml::errorSummary($model)): ?>

    <table id="tips">
        <tr>
            <td><div class="erro_div"><span class="error_message"> <?php echo CHtml::errorSummary($model); ?> </span></div></td>
        </tr>
    </table>
<?php endif ?>
<?php $form = $this->beginWidget('CActiveForm', array('id' => 'xform', 'htmlOptions' => array('name' => 'xform'))); ?>
<table class="form_table">
    <tr>
        <td class="tb_title"><?php echo Yii::t('common', 'UserName'); ?>：</td>
    </tr>
    <tr>
        <td>
            <?php if ($model->isNewRecord): ?>
                <?php echo $form->textField($model, 'username', array('size' => 30, 'maxlength' => 128, 'class' => 'validate[required]')); ?>
            <?php else: ?>
                <?php echo $model->username; ?>
            <?php endif; ?>
        </td>
    </tr>
    <tr>
        <td class="tb_title"><?php echo Yii::t('common', 'UserAvatar'); ?>：</td>
    </tr>
    <tr>
        <td>
            <img src="<?php echo $model->avatar ? (strstr($model->avatar, 'http://') ? $model->avatar : $this->_baseUrl . '/' . $model->avatar) : Yii::app()->theme->baseUrl . '/styles/images/avatar-max-img.png'; ?>" width="100"/>
        </td>
    </tr>
    <tr>
        <td class="tb_title"><?php echo Yii::t('common', 'PassWord'); ?>：</td>
    </tr>
    <tr>
        <td><?php if ($model->isNewRecord): ?>
                <?php echo $form->passwordField($model, 'password', array('size' => 30, 'maxlength' => 50, 'value' => '', 'class' => 'validate[required]')); ?>
            <?php else: ?>
                <?php echo $form->passwordField($model, 'password', array('size' => 30, 'maxlength' => 50, 'value' => '')); ?>
            <?php endif ?></td>
    </tr>
    <tr>
        <td class="tb_title"><?php echo Yii::t('common', 'Email'); ?>：</td>
    </tr>
    <tr>
        <td><?php echo $form->textField($model, 'email', array('size' => 30, 'maxlength' => 50)); ?></td>
    </tr>
    <tr>
        <td class="tb_title"><?php echo $form->labelEx($model, 'qq'); ?>：</td>
    </tr>
    <tr>
        <td><?php echo $form->textField($model, 'qq', array('size' => 30, 'maxlength' => 50)); ?></td>
    </tr>
    <tr>
        <td class="tb_title"><?php echo $form->labelEx($model, 'mobile'); ?>：</td>
    </tr>
    <tr>
        <td><?php echo $form->textField($model, 'mobile', array('size' => 30, 'maxlength' => 50)); ?></td>
    </tr>

<?php if($this->resdo ==1){?>
    <tr>
        <td class="tb_title"><?php echo Yii::t('admin', 'User Group'); ?>：</td>
    </tr>
    <tr>
        <td>    	
            <select name="User[groupid]" id="User[groupid]" class="validate[required]" onchange="lmdo(this)">
                <option value="">=组=</option>
                <?php foreach ($this->group_list as $group): ?>
                    <option value="<?php echo $group['id']; ?>" <?php Helper::selected($group['id'], $model->groupid); ?>><?php echo $group['group_name']; ?></option>
                <?php endforeach; ?>
            </select>
        </td>
    </tr>
    <tr class="lmlist" style="display: block;">
        <td class="tb_title">联盟列表：</td>
    </tr>
    <tr class="lmlist" style="display: block;">
        <td>
            <select name="User[lmid]" id="lmid" class="validate[required]">
                <option value="0">=选择联盟=</option>
                <?php foreach ($this->lm_list as $lm): ?>
                    <option value="<?php echo $lm['id']; ?>" <?php Helper::selected($lm['id'], $model->lmid); ?>><?php echo $lm['lmname']; ?></option>
                <?php endforeach; ?>
            </select>
        </td>
    </tr>
    <?php }?>
    <tr>
        <td class="tb_title"><?php echo Yii::t('common', 'Status'); ?>：</td>
    </tr>
    <tr>
        <td><?php echo $form->dropDownList($model, 'status', array('1' => Yii::t('common', 'Normal'), '0' => Yii::t('common', 'Locked'), '-1' => Yii::t('common', 'Unpass'))); ?></td>
    </tr>
    <tr class="submit">
        <td><input type="submit" name="editsubmit" value="<?php echo Yii::t('common', 'Submit'); ?>" class="button" tabindex="3" /></td>
    </tr>
</table>
<?php $this->endWidget(); ?>
<script>
var groupid= <?php echo $model->groupid?>;

    if(groupid != 1 ){
        $('#lmid').val(0);
        $('.lmlist').css('display','none');
    }


function lmdo(abj) {
    var num = $(abj).val();
    if(num!=1){
        $('#lmid').val(0);
        $('.lmlist').css('display','none');
    }else{
        $('.lmlist').css('display','block');
    }
}
    </script>
