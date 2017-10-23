<?php
    $indextitle = array(
        'WuTjuserall' => '统计页面',
        'WuDayarpu' => 'ARPU页面',
        'User' => '用户权限'
    );
    $pagetitle = array(
    'WuDayarpu|index' => 'Arpu首页',
    'WuTjuserall|index' => '概况-实时统计',
    'WuTjuserall|ztqs' => '概况-整体趋势',
    'WuTjuserall|qudao' => '渠道分析-渠道列表',
    'WuTjuserall|qdlist' => '渠道分析-渠道详情',
    'WuTjuserall|payindex' => '付费分析-付费趋势',
    'WuTjuserall|paymoney' => '付费分析-付费转化',
    'WuTjuserall|paychang' => '付费分析-付费习惯',
    'WuTjuserall|kzz' => '扩展-渠道首页',
    'WuTjuserall|kzqd' => '扩展-渠道详情',
    'WuTjuserall|editindex' => '扩展-影片分析',
    'User|update' => '权限-修改密码',
    );
?>


<?php if (CHtml::errorSummary($model)): ?>
    <table  id="tips">
        <tr>
            <td><div class="erro_div"><span class="error_message"> <?php echo CHtml::errorSummary($model); ?> </span></div></td>
        </tr>
    </table>
<?php endif ?>
<?php $form = $this->beginWidget('CActiveForm', array('id' => 'xform', 'htmlOptions' => array('name' => 'xform'))); ?>
<table class="form_table">
    <tr>
        <td class="tb_title"><?php echo Yii::t('admin', 'Group Name'); ?>：</td>
    </tr>
    <tr >
        <td >
            <?php if ($model->id == User::AdminGroupID): ?>
                <?php echo $model->group_name; ?>
            <?php else: ?>
                <?php echo $form->textField($model, 'group_name', array('size' => 30, 'maxlength' => 128, 'class' => 'validate[required]')); ?>
            <?php endif; ?>
        </td>
    </tr>
    <tr>
        <td  colspan="2"><?php echo Yii::t('admin', 'Acl'); ?>：</td>
    </tr>
    <tr >
        <td width="90%">
            <?php if ($model->id != User::AdminGroupID): ?>
                <table>
                    <script type="text/javascript">
                        function selectAll(id, checked) {
                            $('#' + id + ' td').each(function () {
                                $(this).children('input').attr('checked', checked);
                            });
                        }
                    </script>
                    <?php foreach ((array) $acls as $ak => $acl): ?>
                        <tr id="<?php echo $ak . '_id'; ?>">	  	 		
                            <td style="width:8%; border:1px solid #CCCCCC;"><input type="checkbox" name="acls[]" value=""  style="display: none"  checked="checked"  onclick="selectAll('<?php echo $ak . '_id'; ?>', this.checked)"/><strong style="color:#000000;"><?php echo $indextitle[Yii::t('acl', $ak)]; ?></strong></td>
                            <?php foreach ((array) $acl as $value): ?>
                                <td style="width:8%; border:1px solid #CCCCCC;"><input type="checkbox" name="acls[]" value="<?php echo $ak . '|' . $value ?>" <?php Helper::selected($ak . '|' . $value, $has_acls, 'checked'); ?>/><?php echo $pagetitle[Yii::t('acl', $ak . '|' . $value)]; ?></td>
                            <?php endforeach; ?>	  	 		
                        </tr>
                    <?php endforeach; ?>	  	 	
                </table>  
            <?php else: ?>
                <?php echo ' ( 最高权限 ) ' . $model->acl; ?>
            <?php endif; ?>		
        </td>
    </tr>
    <?php if ($model->id != User::AdminGroupID): ?>
        <tr class="submit">
            <td><input type="submit" name="editsubmit" value="<?php echo Yii::t('common', 'Submit'); ?>" class="button" tabindex="3" /></td>
        </tr>
    <?php endif; ?>
</table>
<?php
$this->endWidget();