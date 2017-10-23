<?php
/**
 *  编辑
 * 
 * @author        Sim Zhao <326196998@qq.com>
 * @copyright     Copyright (c) 2015. All rights reserved.
 */

class UpdateAction extends CAction
{

	public function run(){
	   // $urldo =Yii::app()->request->urlReferrer;

        $model = $this->controller->loadModel();

        if (isset($_POST['User'])) {
            if(empty($_POST['User']['password'])){
            	$_POST['User']['password'] = $model->password;
            }else{
            	$_POST['User']['password'] = User::createPassword($_POST['User']['password']);
            }     
            $model->attributes = $_POST['User'];
            if ($model->save()) {               
                $this->controller->message('script',Yii::t('admin','Update Success'),'http://local.mantj.com/?r=admin');
            }
        }        
        $this->controller->render('update', array ('model' => $model ));
	}
}