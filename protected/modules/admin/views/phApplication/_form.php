<?php
/* @var $this PhApplicationController */
/* @var $model PhApplication */
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
<script type="text/javascript" src="<?php echo $this->_static_public ?>/js/jquery/jquery.ui.widget.js"></script>
<script type="text/javascript" src="<?php echo $this->_static_public ?>/js/jquery/jquery.fileupload.js"></script>
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
            <?php echo $form->labelEx($model,'sub_title'); ?>                </td>
    </tr>
    <tr>
        <td><?php echo $form->textField($model,'sub_title',array('size'=>60,'maxlength'=>100)); ?></td>
    </tr>
            <tr>
                <td class="tb_title">
                    <?php echo $form->labelEx($model,'url'); ?>                </td>
            </tr>
            <tr>
                <td><?php echo $form->textField($model,'url',array('size'=>60,'maxlength'=>100)); ?></td>
            </tr>
                        <tr>
                <td class="tb_title">
                    <?php echo $form->labelEx($model,'os_type'); ?>                </td>
            </tr>
            <tr>
                <td><?php echo $form->dropDownList($model, 'os_type',PhApplication::getOsTypeArray()); ?></td>
            </tr>
                        <tr>
                <td class="tb_title">
                    <?php echo $form->labelEx($model,'icon'); ?>                </td>
            </tr>
            <tr>
                <td>
                    <input name="PhApplication[icon]" type="hidden" id="attach_file_1" value="<?php echo $model->icon; ?>"/>
                    <input name="simple_file" id="fileupload_1" onclick="fileUpload(1)" type="file">
                    <div id="img_preview_1" style="padding:10px;">
                        <?php if ($model->icon): ?>
                            <a href="<?php echo $model->icon ?>" target="_blank">
                                <img style="max-width:600px; padding: 5px; border: 1px solid #cccccc;" src="<?php echo $model->icon ?>" align="absmiddle" />
                            </a>
                        <?php endif ?>
                    </div>
                </td>
            </tr>
                        <tr>
                <td class="tb_title">
                    <?php echo $form->labelEx($model,'animation'); ?>                </td>
            </tr>
            <tr>
                <td>
                    <input name="PhApplication[animation]" type="hidden" id="attach_file_2" value="<?php echo $model->animation; ?>"/>
                    <input name="simple_file" id="fileupload_2" onclick="fileUpload(2)" type="file">
                    <div id="img_preview_2" style="padding:10px;">
                        <?php if ($model->animation): ?>
                            <a href="<?php echo $model->animation ?>" target="_blank">
                                <img style="max-width:600px; padding: 5px; border: 1px solid #cccccc;" src="<?php echo $model->animation ?>" align="absmiddle" />
                            </a>
                        <?php endif ?>
                    </div>
                </td>
            </tr>
                        <tr>
                <td class="tb_title">
                    <?php echo $form->labelEx($model,'sort_order'); ?>                </td>
            </tr>
            <tr>
                <td><?php echo $form->textField($model,'sort_order'); ?></td>
            </tr>
                        <tr>
                <td class="tb_title">
                    <?php echo $form->labelEx($model,'status'); ?>                </td>
            </tr>
            <tr>
                <td><?php echo $form->dropDownList($model, 'status', array('1' =>'显示','0'=>'隐藏' )); ?></td>
            </tr>
            
    <tr class="submit">
        <td><input type="submit" name="editsubmit" value="提交" class="button"
                   tabindex="3"/></td>
    </tr>
</table>
<script type="text/javascript">
    //ajax上传图片
    function fileUpload(id) {
        $('#fileupload_'+id).fileupload({
            url: "<?php echo $this->createUrl('PhApplication/uploadSimple'); ?>",
            dataType: 'json',
            done: function (e, JsonData) {
                var data = JsonData.result;
                if (200 === data.code) {
                    var atta_file = '';
                    if (data.data.file_path) {
                        $('#attach_file_'+id).val(data.data.file_path);
                        atta_file = '<a href="' + data.data.file_path + '" target="_blank"><img  style="max-width:600px; padding: 5px; border: 1px solid #cccccc;"  src="' + data.data.file_path + '"  align="absmiddle" /></a>';
                    }
                    $('#img_preview_'+id).html(atta_file);
                } else {
                    alert(data.message);
                }
                return false;
            }
        });
    }
</script>
<?php $this->endWidget(); ?>

