<?php

class WuDayarpuController extends Backend
{
    public $_hoursall;
   //初始化函数
	public function init(){
		parent::init();
        $this->_hoursall=array(
            '00:00','01:00','02:00','03:00','04:00','05:00','06:00','07:00','08:00','09:00',
            '10:00','11:00','12:00','13:00','14:00','15:00','16:00','17:00','18:00','19:00',
            '20:00','21:00','22:00','23:00',
        );
    }


	public function actionIndex(){

		//$model=new WuDayarpu;
		//条件
         $listallsql='SELECT 
                        *
                   FROM 
                      `y_wu_dayarpu`';
        $listalls=Yii::app()->db->createCommand($listallsql)->queryAll();
        foreach ($listalls as &$ylist){
            $yuehours[$ylist['weekday']][$ylist['dodate']] = $ylist['arpu'];
        }

        for ($w=1;$w<8;$w++) {
            for ($i = 0; $i < 24; $i++) {

                if ($yuehours[$w][$i]) {
                    $listshow[$w][$i] = $yuehours[$w][$i];
                } else {
                    $listshow[$w][$i] = 0;
                }
            }
        }
        for ($h = 0; $h < 24; $h++) {
            for ($f=1;$f<8;$f++) {
                if ($listshow[$f][$h]) {
                    $cs[$h][$f] = $yuehours[$f][$h];
                } else {
                    $cs[$h][$f] = 0;
                }
            }
        }

        $model =new WuEmailpower();
        $criteria = new CDbCriteria();
        $criteria->addCondition('id=1');
        $findtime=$model->find($criteria);



		$this->render('index', array ('model' => $model, 'cs'=>$cs,'power'=>$findtime));
	}

	/**
	* create a particular model.
	* If create is successful, the browser will be redirected to the 'index' page.
	* @param integer $id the ID of the model to be created
	*/
	public function actionCreate()
	{
		$model=new WuDayarpu;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['WuDayarpu']))
		{
			$model->attributes=$_POST['WuDayarpu'];
			$model->createtime=time();
			$model->updatetime=time();
			if($model->save())
				$this->redirect(array('index'));
		}

		$this->render('create',array(
			'model'=>$model,
		));
	}
    public function actionedit()
    {
        $model =new WuDayarpu();
        $criteria = new CDbCriteria();
        $week = empty($_REQUEST['week'])?'':intval($_REQUEST['week']);
        $time = empty($_REQUEST['time'])?'':intval($_REQUEST['time']);
        $arpu = empty($_REQUEST['arpu'])?'':intval($_REQUEST['arpu']);
        if( $week and $time>=0 ){
            $criteria->addCondition('weekday='.$week);
            $criteria->addCondition('dodate="'.$time.'"');
            $findtime=$model->find($criteria);

            if($findtime){
                $findtime->arpu=$arpu;
                $editdo=$findtime->save();
                if($editdo){
                    $art['code'] = 0;
                    $art['msg']='更新成功';
                }else{
                    $art['code'] = 2;
                    $art['msg']='更新失败';
                }
            }else{
                $model->weekday=$week;
                $model->dodate=$time;
                $model->arpu=$arpu;
                $editdo=$model->save();
                if($editdo){
                    $art['code'] = 0;
                    $art['msg']='添加成功';
                }else{
                    $art['code'] = 2;
                    $art['msg']='添加失败';
                }
            }
        }else{
            $art['code'] = 7;
            $art['msg']='缺少重要参数';
        }
        echo json_encode($art);
    }

    public function actionpower()
    {
        $model =new WuEmailpower();
        $criteria = new CDbCriteria();
        $power = empty($_REQUEST['power'])?'':intval($_REQUEST['power']);

            $criteria->addCondition('id=1');
            $findtime=$model->find($criteria);
            if($findtime){
                $findtime->power=$power;
                $editdo=$findtime->save();
                if($editdo){
                    $art['code'] = 0;
                    $art['msg']='更新成功';
                }else{
                    $art['code'] = 2;
                    $art['msg']='更新失败';
                }
            }else {

                $art['code'] = 2;
                $art['msg'] = '添加失败';
            }

        echo json_encode($art);
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

		if(isset($_POST['WuDayarpu']))
		{
			$model->attributes=$_POST['WuDayarpu'];
			$model->updatetime=time();
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
				WuDayarpu::model()->deleteAll($criteria);
				break;
			case 'show':
			//显示
				WuDayarpu::model()->updateAll(array('status' => 1), $criteria);
				break;
			case 'hide':
			//隐藏
				WuDayarpu::model()->updateAll(array('status' => 0), $criteria);
				break;
			case 'sortOrder':
				$sortOrder = $_POST['order'];
				foreach((array)$ids as $id){
					$catalogModel = WuDayarpu::model()->findByPk($id);
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
	 * @return WuDayarpu the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=WuDayarpu::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param WuDayarpu $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='wu-dayarpu-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
