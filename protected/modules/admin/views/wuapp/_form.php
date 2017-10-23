<?php
/* @var $this WuappController */
/* @var $model WuApp */
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
                    <?php echo $form->labelEx($model,'appname'); ?>                </td>
            </tr>
            <tr>
                <td><?php echo $form->textField($model,'appname',array('size'=>60,'maxlength'=>255)); ?></td>
            </tr>
                        <tr>
                <td class="tb_title">
                    <?php echo $form->labelEx($model,'appbigtypeid'); ?>                </td>
            </tr>
            <tr>
<!--                -->
                <td>
                    <select  name="WuApp[appbigtypeid]" id="WuApp_appbigtypeid"  onchange="typedo(this);">
                        <?php foreach ($this->_bigtypelist as $key=>$typerow): ?>
                            <option value="<?php echo $key ?>"> <?php echo $typerow?></option>
                        <?php endforeach; ?>

                    </select>
                </td>
            </tr>
                        <tr>
                <td class="tb_title">

                    <?php echo $form->labelEx($model,'appsmtypeid'); ?>
                </td>
            </tr>
            <tr>
                <td>
                    <select  name="WuApp[appsmtypeid]" id="WuApp_appsmtypeid" >


                    </select>
                </td>

            </tr>
                        <tr>
                <td class="tb_title">
                    <?php echo $form->labelEx($model,'apptitle'); ?>                </td>
            </tr>
            <tr>
                <td><?php echo $form->textField($model,'apptitle',array('size'=>60,'maxlength'=>255)); ?></td>
            </tr>
    <tr>
        <td class="tb_title">
            <?php echo $form->labelEx($model,'appurl'); ?>                </td>
    </tr>
    <tr>
        <td><?php echo $form->textField($model,'appurl',array('size'=>60,'maxlength'=>255)); ?></td>
    </tr>
                        <tr>
                <td class="tb_title">
                    <?php echo $form->labelEx($model,'appdonum'); ?>                </td>
            </tr>
            <tr>
                <td><?php echo $form->textField($model,'appdonum'); ?></td>
            </tr>
            <tr>
                <td class="tb_title">
                    <?php echo $form->labelEx($model,'appsize'); ?>                </td>
            </tr>
            <tr>
                <td><?php echo $form->textField($model,'appsize'); ?></td>
            </tr>
            <tr>
                <td class="tb_title">
                    <?php echo $form->labelEx($model,'appicon'); ?>                </td>
            </tr>
            <tr>
                <td class="" style="position:relative;">
                    <!--                    <h2 class="add-article-box-title"><span>海报图<span class="fmtcc">(750*360)</span></span></h2>-->
                    <!-- <span class="prompt-text">尺寸可以不需要太严格</span> -->
                    <div class="add-article-box-content" style="width: 150px;height: 150px;">
                        <div class="imgview" style="height: 120px">
                            <img id="lximghead" style="width: 120px;height: 120px; overflow: hidden" src="<?php echo $model->appicon ?>" alt="">
                        </div>
                    </div>
                    <div class="" style="margin-left: 25px;">
                        <div class="btn btn-warning" id="iconupImage"  >应用icon</div>
                        <input id="iconimgfmt"  name="WuApp[appicon]" type="hidden" name="iconimgfmt" value="<?php echo $model->appicon ?>"/>
                    </div>
                </td>

                        <tr>
                <td class="tb_title">
                    <?php echo $form->labelEx($model,'appimg'); ?>                </td>
            </tr>


            <tr>

                <td class="add-article-box" style="position:relative;">
<!--                    <h2 class="add-article-box-title"><span>海报图<span class="fmtcc">(750*360)</span></span></h2>-->
                    <!-- <span class="prompt-text">尺寸可以不需要太严格</span> -->
                    <div class="add-article-box-content">
                        <div class="imgview tgimg" >

                            <?php

                                foreach ($listimg as  $imgs):
                                    ?>
                                    <img id="lximghead" style="width:90px;height: 160px;" src="<?php echo $imgs ?>" alt="">
                            <?php endforeach; ?>

                        </div>
                    </div>
                    <div class="add-article-box-footer">
                        <div class="btn btn-warning" id="upImage">选择推广图</div>
                        <input id="imgfmt" type="hidden"  name="WuApp[appimg]" name="imgfmt" value="<?php echo $model->appimg ?>">
                    </div>
                </td>
            </tr>
                        <tr>
                <td class="tb_title">
                    <?php echo $form->labelEx($model,'appcontent'); ?>                </td>
            </tr>
            <tr>
                <td><?php echo $form->textArea($model,'appcontent',array('rows'=>6, 'cols'=>50)); ?></td>
            </tr>


                        <tr>
                <td class="tb_title">
                    <?php echo $form->labelEx($model,'apppower'); ?>                </td>
            </tr>
            <tr>
                <td><?php echo $form->dropDownList($model, 'apppower', array('0' =>'正常','1'=>'禁用' )); ?></td>
            </tr>

    <tr class="submit">
        <td><input type="submit" name="editsubmit" value="提交" class="button"
                   tabindex="3"/></td>
    </tr>
</table>
<script>
    var actiontype = '0';
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
                if(actiontype ==1){
                    for(i=0;i<arg.length;i++){
                        //$(".boximageall").append('<div class="imgeach" onmouseenter="ccshow(this)" onmouseleave="cchide(this)"><div class="chgcc" onclick="chgccfun(this)"><span class="fa fa-trash-o"></span></div><img src="'+arg[i].src+'" alt=""></div>');
                        imgall.push(arg[i].src);
                        $('.tgimg').append(' <img id="tglximghead" src="'+arg[i].src+'" alt="">');
                        if(i==(arg.length-1)){
                            $("#imgfmt").val(imgall);
                        }
                    }
                }else{
                    $("#lximghead").attr("src", arg[0].src);
                    $("#iconimgfmt").val(arg[0].src);
                }

                console.log(arg);
                //alert("封面图："+$("#imgfmt").val()+"广告小图："+$("#imgggt").val()+"素材图："+$("#imgallid").val())
            })
            //侦听文件上传，取上传文件列表中第一个上传的文件的路径

        });
    });
$('#upImage').click(function () {	//推广图
    actiontype= 1;
    var myImage = _uploadEditor.getDialog("insertimage");
    myImage.open();
});
$('#iconupImage').click(function () {	//icon
    actiontype = 0;
    var myImage = _uploadEditor.getDialog("insertimage");
    myImage.open();
});
var resdo = '<?php echo $model->appbigtypeid ?>';
if(resdo){
    $("#WuApp_appbigtypeid").val('<?php echo $model->appbigtypeid ?>');
    typedo('#WuApp_appbigtypeid');

}else{
    typedo('#WuApp_appbigtypeid');
}

var  thedo = 0
function  typedo(abj) {
    var thetype = $(abj).val();

    $.post("<?php echo $this->createUrl('Wuapp/applist'); ?>",{bigtypeid:thetype},
        function(data){
            if(data.code == 0){
               var thestype = data.result;
                $("#WuApp_appsmtypeid").html('');
                for(i=0;i<thestype.length;i++){
                    $("#WuApp_appsmtypeid").append('<option value='+thestype[i]['id']+'>'+thestype[i]['s_typename']+'</option>');
                }
                if(resdo && thedo == 0){
                    thedo =1;
                    $("#WuApp_appsmtypeid").val('<?php echo $model->appsmtypeid ?>');
                }
            }else{
                $("#WuApp_appsmtypeid").html('');

            }



        },'json')
}
    </script>

<?php $this->endWidget(); ?>

