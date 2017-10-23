<?php
/**
 * The following variables are available in this template:
 * - $this: the CrudCode object
 */
?>
<?php echo "<?php\n"; ?>
/* @var $this <?php echo $this->getControllerClass(); ?> */
/* @var $model <?php echo $this->getModelClass(); ?> */
<?php echo "?>\n"; ?>
<div id="contentHeader">
	<h3>更新 <?php echo $this->modelClass; ?></h3>
	<div class="searchArea">
		<ul class="action left" >
			<li ><a href="<?php echo "<?php echo \$this->createUrl('index') ?>"?>" class="actionBtn"><span><?php echo Yii::t('admin', 'Go Back'); ?></span></a></li>
			<li class="current"><a href="<?php echo  "<?php echo \$this->createUrl('create') ?>"?>" class="actionBtn"><span><?php echo Yii::t('admin', 'Add'); ?></span></a></li>
		</ul>
		<div class="search right"> </div>
	</div>
</div>

<?php echo "<?php \$this->renderPartial('_form', array('model'=>\$model)); ?>"; ?>
