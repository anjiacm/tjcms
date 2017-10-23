<?php
/**
 * The following variables are available in this template:
 * - $this: the CrudCode object
 */
echo  "<?php \$t_statsArray = array(
                '1' => '<span class=\"color_show\">√</span>',
                '0' => '<span class=\"color_hide\">×</span>'
            );?>\n";
?>
<?php echo "<?php\n"; ?>

    /* @var $this <?php echo $this->getControllerClass(); ?> */
    ?>
    <div id="contentHeader">
        <h3><?php echo $this->pluralize($this->class2name($this->modelClass)); ?></h3>
        <div class="searchArea">
            <ul class="action left">
                <li><a href="<?php echo "<?php echo \$this->createUrl('create') ?>" ?>"
                       class="actionBtn"><span><?php echo Yii::t('admin', 'add'); ?></span></a></li>
            </ul>
            <div class="search right">
                <?php echo "<?php \$this->beginWidget('CActiveForm', array('id' => 'searchForm', 'method' => 'get', 'htmlOptions' => array('name' => 'xform')));?>" ?>
                <input name="searchsubmit" type="submit" class="button"
                       value="<?php echo Yii::t('admin', 'Query'); ?>"/>
                <?php echo "<?php \$this->endWidget(); ?>\n"; ?>
            </div>
        </div>
    </div>

<?php echo "<?php \$form = \$this->beginWidget('CActiveForm', array('action' => \$this->createUrl('batch'), 'htmlOptions' => array('name' => 'cpform')));?>\n" ?>
    <table class="content_list">
        <tr class="tb_header">
            <?
            $count = 0;
            foreach ($this->tableSchema->columns as $column) {
                if (++$count == 7)
                    echo "\t\t/*\n";
                ?>
                <th width=\"10%\"><?php echo "<?php echo \$form->label(\$model, '{$column->name}');?>" ?></th>
                <?php
            }
            if ($count >= 7)
                echo "\t\t*/\n";
            ?>
            <th><?php echo Yii::t('admin', 'Operate'); ?></th>
        </tr>
        <?php echo "<?php foreach (\$datalist as \$row): ?>" ?>
        <tr class="tb_list">

            <?
            $count = 0;

            foreach ($this->tableSchema->columns as $column) {
                if ($count == 0) {
                    ?>
                    <td><input type="checkbox" name="id[]"
                               value="<?php echo "<?php echo \$row->id?>" ?>"/><?php echo "<?php echo \$row->id?>" ?>
                    </td>
                    <?php
                    $count += 1;
                    continue;
                }
                if (strpos($column->name, 'order') !== false) {
                    ?>
                    <td><?php echo "<?php echo \$form->textField(\$row, '{$column->name}',array('name'=>'order['.\$row->id.']'));?>" ?></td>
                    <?php $count += 1;
                    continue;
                }
                if (strpos($column->name, 'time') !== false) {
                    ?>
                    <td><?php echo "<?php echo date('Y-m-d H:i',\$row->" . $column->name . ")?>"; ?></td>
                    <?php $count += 1;
                    continue;
                }
                if (strpos($column->name, 'status') !== false) {
                  ?>
                    <td><?php echo "<?php echo \$t_statsArray[\$row->" . $column->name . "]?>"; ?></td>
                    <?php
                    $count += 1;
                    continue;
                }
                if (++$count == 7)
                    echo "\t\t/*\n";
                ?>
                <td><?php echo "<?php echo \$row->" . $column->name . "?>"; ?></td>
                <?php
            }
            if ($count >= 7)
                echo "\t\t*/\n";
            ?>

            <td>
                <a href="<?php echo "<?php echo \$this->createUrl('update', array('id' => \$row->id)) ?>" ?>">
                    <img src="<?php echo "<?php echo \$this->module->assetsUrl; ?>/images/update.png" ?>"
                         align="absmiddle"/></a>&nbsp;&nbsp;
                <a href="<?php echo "<?php echo \$this->createUrl('batch', array('command' => 'delete', 'id' => \$row->id)) ?>" ?>"
                   class="confirmSubmit">
                    <img src="<?php echo "<?php echo \$this->module->assetsUrl; ?>/images/delete.png" ?>"
                         align="absmiddle"/></a>
            </td>
        </tr>
        <?php echo "<?php endforeach; ?>" ?>
        <tr class="submit">
            <td colspan="5">
                <div class="cuspages right">
                    <?php echo "<?php  \$this->widget('CLinkPager', array('pages' => \$pagebar));?>" ?>
                </div>
                <div class="fixsel">
                    <input type="checkbox" name="chkall" id="chkall" onclick="checkAll(this.form, 'id')"/>
                    <label for="chkall"><?php echo Yii::t('admin', 'Check All'); ?></label>
                    <select name="command">
                        <option value=""><?php echo Yii::t('admin', 'Select Operate'); ?></option>
                        <option value="sortOrder"><?php echo Yii::t('admin', 'Sort Order'); ?></option>
                        <option value="delete"><?php echo Yii::t('admin', 'Delete'); ?></option>
                        <option value="show"><?php echo Yii::t('admin', 'Show'); ?></option>
                        <option value="hide"><?php echo Yii::t('admin', 'Hidden'); ?></option>
                    </select>
                    <input id="submit_maskall" class="button confirmSubmit" type="submit"
                           value="<?php echo Yii::t('common', 'Submit'); ?>" name="maskall"/>
                </div>
            </td>
        </tr>
    </table>
<?php echo "<?php \$this->endWidget(); ?>\n"; ?>