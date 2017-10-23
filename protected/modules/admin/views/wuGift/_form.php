<?php
/* @var $this WuGiftController */
/* @var $model WuGift */
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
                    <?php echo $form->labelEx($model,'giftname'); ?>                </td>
            </tr>
            <tr>
                <td><?php echo $form->textField($model,'giftname',array('size'=>60,'maxlength'=>255)); ?></td>
            </tr>
                        <tr>
                <td class="tb_title">
                    <?php echo $form->labelEx($model,'gifturl'); ?>                </td>
            </tr>
            <tr>
                <td><?php echo $form->textArea($model,'gifturl',array('rows'=>6, 'cols'=>50)); ?></td>
            </tr>
    <tr>
        <td class="tb_title">
            <?php echo $form->labelEx($model,'giftcontent'); ?>                </td>
    </tr>
    <tr>
        <td><?php echo $form->textArea($model,'giftcontent',array('rows'=>6, 'cols'=>50)); ?></td>
    </tr>
                        <tr>
                <td class="tb_title">
                    <?php echo $form->labelEx($model,'giftimg'); ?>                </td>
            </tr>
            <tr>
                <td>
                    <img src="<?php echo $model->giftimg ?>" id="giftimglook" style="width:100px;height: 100px;overflow: hidden;">
                    <input type="hidden"  id="giftimgfmt"  name="WuGift[giftimg]" value="<?php echo $model->giftimg ?>">
                </td>
            </tr>
                        <tr>
                <td class="tb_title">
                    <?php echo $form->labelEx($model,'gifttype'); ?>                </td>
            </tr>
            <tr>
                <td><?php echo $form->dropDownList($model, 'gifttype',$this->_goodstypelist); ?></td>
            </tr>

                        <tr>
                <td class="tb_title">
                    <?php echo $form->labelEx($model,'giftmoney'); ?>                </td>
            </tr>
            <tr>
                <td><?php echo $form->textField($model,'giftmoney',array('size'=>60,'maxlength'=>255)); ?></td>
            </tr>
                        <tr>
                <td class="tb_title">
                    <?php echo $form->labelEx($model,'giftnewmoney'); ?>                </td>
            </tr>
            <tr>
                <td><?php echo $form->textField($model,'giftnewmoney',array('size'=>60,'maxlength'=>255)); ?></td>
            </tr>
                        <tr>
                <td class="tb_title">
                    <?php echo $form->labelEx($model,'giftnum'); ?>                </td>
            </tr>
            <tr>
                <td><?php echo $form->textField($model,'giftnum'); ?></td>
            </tr>


    <tr>
        <td class="tb_title">
            <?php echo $form->labelEx($model,'giftkd'); ?>                </td>
    </tr>
    <tr>
        <td><?php echo $form->radioButtonList($model, 'giftkd',  $this->_goodskdlist['s_goodsname']); ?></td>
    </tr>
    <tr>
        <td class="tb_title">
            <?php echo $form->labelEx($model,'giftqd'); ?>                </td>
    </tr>
    <tr>
        <td><?php echo $form->radioButtonList($model, 'giftqd',  $this->_goodsqdlist['s_goodsname']); ?></td>
    </tr>
    <tr>
        <td class="tb_title">
            <?php echo $form->labelEx($model,'giftbq'); ?>                </td>
    </tr>
    <tr>
        <td><?php echo $form->radioButtonList($model, 'giftbq',  $this->_goodsbqlist['s_goodsname']); ?></td>
    </tr>




                        <tr>
                <td class="tb_title">
                    <?php echo $form->labelEx($model,'giftpower'); ?>                </td>
            </tr>
            <tr>
                <td><?php echo $form->dropDownList($model, 'giftpower', array('0' =>'正常','1'=>'禁用' )); ?></td>
            </tr>
            
    <tr class="submit">
        <td><input type="submit" name="editsubmit" value="提交" class="button"
                   tabindex="3"/></td>
    </tr>
</table>
<?php $this->endWidget(); ?>
<script>
    $(function () {
        $('#WuGift_gifturl').blur(function () {
            var url = $(this).val();
            $.post("<?php echo $this->createUrl('WuGift/getiteminfo') ?>", {'url': url}, function (result) {
                if (result.errno == 0) {
                    var obj = result.obj;
                    if ($("#WuGift_giftname").val() == '') {
                        $("#WuGift_giftname").val(obj.title);
                    }
                   // console.log(obj);
//                    $('#TbkGoods_num_iid').val(obj.num_iid);
                    $('#giftimglook').attr('src',obj.pic_url);
                    $('#giftimgfmt').val(obj.pic_url);
                    $('#WuGift_giftnewmoney').val(obj.price);
                    $('#WuGift_giftmoney').val(obj.market_price);
//                    $('#TbkGoods_volume').val(obj.volume);
//                    $('#TbkGoods_nick').val(obj.nick);
//                    $('#TbkGoods_provcity').val(obj.provcity);

                } else {
                    alert(result.errno);
                }
            }, 'json');
        });
    })
</script>
