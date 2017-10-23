<?php

class WuLmlistController extends Backend
{

   //初始化函数
	public function init(){
		parent::init();
    }


	public function actionIndex(){

		$model=new WuLmlist;
		//条件
		$criteria = new CDbCriteria();
		//$title = trim(Yii::app()->request->getParam('title'));
		//$position_id = intval(Yii::app()->request->getParam('position_id'));
		//$title && $criteria->addColumnCondition(array('title' =>$title));
		//$position_id && $criteria->addColumnCondition(array('position_id', $position_id));

        $criteria->order = 't.id ASC';
		$count = $model->count($criteria);

		//分页
		$pages = new CPagination($count);
		$pages->pageSize = 20;
		$pages->applyLimit($criteria);

		//查询
		$result = $model->findAll($criteria);
		$this->render('index', array ('model' => $model, 'datalist' => $result , 'pagebar' => $pages));
	}

	/**
	* create a particular model.
	* If create is successful, the browser will be redirected to the 'index' page.
	* @param integer $id the ID of the model to be created
	*/
	public function actionCreate()
	{
		$model=new WuLmlist;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['WuLmlist']))
		{
			$model->attributes=$_POST['WuLmlist'];
			$model->dodate=time();
			//$model->updatetime=time();
			if($model->save())
				$this->redirect(array('index'));
		}

		$this->render('create',array(
			'model'=>$model,
		));
	}

	/**
	* Updates a particular model.
	* If update is successful, the browser will be redirected to the 'view' page.
	* @param integer $id the ID of the model to be updated
	*/
	public function actionUpdate($id)
	{
		$model=$this->loadModel($id);

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['WuLmlist']))
		{
			$model->attributes=$_POST['WuLmlist'];
            $model->dodate=time();
			if($model->save())
			    $this->redirect(array('index'));
		}

		$this->render('update',array(
			'model'=>$model,
		));
	}

	public function actionBatch(){
		$ids = Yii::app()->request->getParam('id');
		$command = Yii::app()->request->getParam('command');
		empty( $ids ) && $this->message( 'error', Yii::t('admin','No Select') );
		if(!is_array($ids)) {
			$ids = array($ids);
		}
		$criteria = new CDbCriteria();
		$criteria->addInCondition('id', $ids);
		switch ( $command ) {
			case 'delete':
			//删除
				WuLmlist::model()->deleteAll($criteria);
				break;
			case 'show':
			//显示
				WuLmlist::model()->updateAll(array('status' => 1), $criteria);
				break;
			case 'hide':
			//隐藏
				WuLmlist::model()->updateAll(array('status' => 0), $criteria);
				break;
			case 'sortOrder':
				$sortOrder = $_POST['order'];
				foreach((array)$ids as $id){
					$catalogModel = WuLmlist::model()->findByPk($id);
					if($catalogModel){
						$catalogModel->order = $sortOrder[$id];
						$catalogModel->save();
					}
				}
				break;
			default:
				$this->message('error', Yii::t('admin','Error Operation'));
		}
		$this->message('success', Yii::t('admin','Batch Operate Success'));
	}


	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return WuLmlist the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=WuLmlist::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param WuLmlist $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='wu-lmlist-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
