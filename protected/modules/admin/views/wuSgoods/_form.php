<?php
/* @var $this WuSgoodsController */
/* @var $model WuSgoods */
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
                    <?php echo $form->labelEx($model,'goodsid'); ?>                </td>
            </tr>
            <tr>

                <td><?php echo $form->dropDownList($model, 'goodsid', $this->_goodstypelist); ?></td>
            </tr>
                        <tr>
                <td class="tb_title">
                    <?php echo $form->labelEx($model,'s_goodsname'); ?>                </td>
            </tr>
            <tr>
                <td><?php echo $form->textField($model,'s_goodsname',array('size'=>60,'maxlength'=>255)); ?></td>
            </tr>
                        <tr>
                <td class="tb_title">
                    <?php echo $form->labelEx($model,'s_goodsimg'); ?>                </td>
            </tr>
            <tr>
                <td class="add-article-box" style="position:relative;">
                    <!--                    <h2 class="add-article-box-title"><span>海报图<span class="fmtcc">(750*360)</span></span></h2>-->
                    <!-- <span class="prompt-text">尺寸可以不需要太严格</span> -->
                    <div class="add-article-box-content">
                        <div class="imgview">
                            <img id="lximghead" src="<?php echo $model->s_goodsimg ?>" alt="">
                        </div>
                    </div>
                    <div class="add-article-box-footer">
                        <div class="btn btn-warning" id="iconupImage">类型图标</div>
                        <input id="iconimgfmt"  name="WuSgoods[s_goodsimg]" type="hidden"  value="<?php echo $model->s_goodsimg ?>"/>
                    </div>
                </td>
            </tr>

            
    <tr class="submit">
        <td><input type="submit" name="editsubmit" value="提交" class="button"
                   tabindex="3"/></td>
    </tr>
</table>
<?php $this->endWidget(); ?>
<script>
    var actiontype = '2';
</script>

<script type="text/javascript" charset="utf-8" src="./ueedit/ueditor.config.js"></script>
<script type="text/javascript" charset="utf-8" src="./ueedit/ueditor.all.js"> </script>
<script id="uploadEditor" type="text/plain" style="display:none;"></script>
<script>
var imgall = Array();
var _uploadEditor;

$(function () {
    //重新实例化一个编辑器，防止在上面的editor编辑器中显示上传的图片或者文件
    _uploadEditor = UE.getEditor('uploadEditor');
    _uploadEditor.ready(function () {
        //设置编辑器不可用
        //_uploadEditor.setDisabled();
        //隐藏编辑器，因为不会用到这个编辑器实例，所以要隐藏
        _uploadEditor.hide();
        //侦听图片上传
        _uploadEditor.addListener('beforeInsertImage', function (t, arg) {
            //将地址赋值给相应的input,只去第一张图片的路径
            //图片预览

            $("#lximghead").attr("src", arg[0].src);
            $("#iconimgfmt").val(arg[0].src);


            console.log(arg);
            //alert("封面图："+$("#imgfmt").val()+"广告小图："+$("#imgggt").val()+"素材图："+$("#imgallid").val())
        })
        //侦听文件上传，取上传文件列表中第一个上传的文件的路径

    });
});

$('#iconupImage').click(function () {	//icon
    actiontype = 2;
    var myImage = _uploadEditor.getDialog("insertimage");
    myImage.open();
});
</script>
