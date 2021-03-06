<?php
/**
 * 用户管理控制器
 * 
 * @author        Sim Zhao <326196998@qq.com>
 * @copyright     Copyright (c) 2014-2015. All rights reserved.
 * 
 */
class UserController extends Backend
{
    protected $group_list;
    protected $lm_list;
    public $resdo;
    public function init(){
    	parent::init();
    	//用户组
        $typeone=md5($_SESSION['admingroupid'].'update');//权限进入
        $typetwo=md5($_SESSION['admingroupid'].'admin');//只为修改密码
        $type = empty($_REQUEST['user'])?'':trim($_REQUEST['user']);
        if($type ==$typeone ){
            $this->resdo = 1;
        }elseif($type ==$typetwo){
            $this->resdo = 2;
        }
    	$group_list = UserGroup::model()->findAll();    	
    	if($group_list){
            //var_dump($group_list);
    		foreach($group_list as $g){
    		  //  var_dump($g->attributes);
    			$this->group_list[$g['id']] = $g->attributes;
    		}
    	}
    	/*联盟列表*/
        $lm_list = WuLmlist::model()->findAll();
        if($lm_list){
            //var_dump($lm_list);
            foreach($lm_list as $l){
               //var_dump($l->attributes);
                $this->lm_list[$l['id']] = $l->attributes;
            }
        }
    }
    
    /**
     * 所有动作
     */
    public function actions()
    {
        $extra_actions = array();
        $actions = $this->actionMapping(array(
            'index'        => 'Index',             //普通用户列表
            'admin'        => 'Admin',             //管理员用户列表 
            'create'       => 'Create',            //用户添加
            'update'       => 'Update',            //用户编辑           
            'batch'        => 'Batch',             //批量操作
        ), 'application.modules.admin.controllers.user');
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
                $this->model = User::model()->findbyPk($_GET['id']);
            }
            if ($this->model === null) {
                throw new CHttpException(404, Yii::t('common', 'The requested page does not exist.'));
            }
        }
        return $this->model;
    }  
}
