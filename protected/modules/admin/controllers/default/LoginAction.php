<?php
/**
 *  后台登录
 * 
 * @author        Sim Zhao <326196998@qq.com>
 * @copyright     Copyright (c) 2015. All rights reserved.
 */

class LoginAction extends CAction
{
	public function run(){
        $this->controller->layout = false;
		$this->controller->pageTitle = Yii::t('admin','admin login').' - '.$this->controller->setting_base['site_name'];
        
        $returnUrl = Yii::app()->user->__returnUrl;        
		if(!Yii::app()->user->getIsGuest()){			
			$this->controller->rediretParentUrl($returnUrl);
		}		
		
		$model = new LoginForm();
        if (Helper::isMobile())
        {

            $mbl = 1;
        }else{
            $mbl = 2;
        }
		// if it is ajax validation request
		if(isset($_POST['ajax']) && $_POST['ajax']==='login-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
		
		// collect user input data
		if(isset($_POST['LoginForm']))
		{
			$model->attributes=$_POST['LoginForm'];
			// validate user input and redirect to the previous page if valid
			if($model->validate() && $model->login()) {
			    if($mbl ==2){
                    $this->controller->message('success', Yii::t('common', 'Login Success'), $returnUrl, 2);
                }else{
                    $this->controller->redirect('http://local.mantj.com/?r=tjall/mtj/mtj');
                }

            }
		}				
		$this->controller->render('login', array('model'=>$model,'mbl'=>$mbl));
	}
}