<?php
/**
 * 推荐管理
 * 
 * @author        Sim Zhao <326196998@qq.com>
 * @copyright     Copyright (c) 2014-2015. All rights reserved.
 * 
 */
class RecommendController extends Backend
{	
    //所有动作
    public function actions()
    {
        $extra_actions = array();
        $actions = $this->actionMapping(array(
            'index'  => 'Index',    //列表页
            'create' => 'Create',   //添加
            'update' => 'Update',   //编辑           
            'batch'  => 'Batch',    //批量操作            
        ), 'application.modules.admin.controllers.recommend');
        return array_merge($actions, $extra_actions);
    }
    
    /**
     * 判断数据是否存在
     * 
     * return \$this->model
     */
    public function loadModel()
    {
    	if ($this->model === null) {
            if (isset($_GET['id'])) {
                $this->model = Recommend::model()->findbyPk($_GET['id']);
            }
            if ($this->model === null) {
                throw new CHttpException(404, Yii::t('common', 'The requested page does not exist.'));
            }
        }
        return $this->model;
    }      
}