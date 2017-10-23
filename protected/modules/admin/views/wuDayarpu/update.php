<?php
/* @var $this WuDayarpuController */
/* @var $model WuDayarpu */
?>
<div id="contentHeader">
	<h3>更新 WuDayarpu</h3>
	<div class="searchArea">
		<ul class="action left" >
			<li ><a href="<?php echo $this->createUrl('index') ?>" class="actionBtn"><span>返回</span></a></li>
			<li class="current"><a href="<?php echo $this->createUrl('create') ?>" class="actionBtn"><span>添加</span></a></li>
		</ul>
		<div class="search right"> </div>
	</div>
</div>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>