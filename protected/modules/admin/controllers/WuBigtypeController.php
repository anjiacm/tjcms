<?php

class WuBigtypeController extends Backend
{

    protected $_typelist;
   //初始化函数
	public function init(){
		parent::init();
		$this->_typelist=WuBigtype::getList();
    }


	public function actionIndex(){

		$model=new WuBigtype;
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
		$model=new WuBigtype;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['WuBigtype']))
		{
			$model->attributes=$_POST['WuBigtype'];
			//$model->power=time();
			$model->dodate=time();
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

		if(isset($_POST['WuBigtype']))
		{
			$model->attributes=$_POST['WuBigtype'];
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
				WuBigtype::model()->deleteAll($criteria);
				break;
			case 'show':
			//显示
				WuBigtype::model()->updateAll(array('power' => 0), $criteria);
				break;
			case 'hide':
			//隐藏
				WuBigtype::model()->updateAll(array('power' => 1), $criteria);
				break;
			case 'sortOrder':
				$sortOrder = $_POST['id'];
				foreach((array)$ids as $id){
					$catalogModel = WuBigtype::model()->findByPk($id);
					if($catalogModel){
						$catalogModel->id = $sortOrder[$id];
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
	 * @return WuBigtype the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=WuBigtype::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param WuBigtype $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='wu-bigtype-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
