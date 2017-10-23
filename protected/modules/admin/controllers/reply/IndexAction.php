<?php
/**
 *  列表
 * 
 * @author        Sim Zhao <326196998@qq.com>
 * @copyright     Copyright (c) 2015. All rights reserved.
 */

class IndexAction extends CAction
{	
	public function run(){
        
        $model = new Reply();        
        //条件
        $criteria = new CDbCriteria();        
        $status = trim(Yii::app()->request->getParam('status'));        
        $status && $criteria->addColumnCondition(array('status' => $status)); 
        $title = trim(Yii::app()->request->getParam('content'));        
        $title && $criteria->addSearchCondition('content', $title);        
        $criteria->order = 't.id DESC';        
        $count = $model->count($criteria);
        
        //分页
        $pages = new CPagination($count);
        $pages->pageSize = 20;
        $pages->applyLimit($criteria);
        
        //查询
        $result = $model->findAll($criteria);

        $this->controller->render('index', array ('model' => $model, 'datalist' => $result , 'pagebar' => $pages ));
	}
}