<?php

class WuappController extends Backend
{
    protected $_typelist;
    protected $_bigtypelist;
    protected $_appreslist;
   //初始化函数
	public function init(){
		parent::init();
        $this->_typelist= WuApp::typealllist();
        $this->_bigtypelist=WuBigtype::getList();

    }


	public function actionIndex(){

		$model=new WuApp;
        $bigmodel=new WuBigType;
        $stypemodel=new WuType;
		//条件
		$criteria = new CDbCriteria();
        $bigcriteria = new CDbCriteria();
        $stypecriteria = new CDbCriteria();

        $bigtypeid = intval(Yii::app()->request->getParam('bigtypeid'));
        $stypeid = intval(Yii::app()->request->getParam('stype'));
        if($bigtypeid){
            $bigtypeid = $bigtypeid;

        }else{
            $bigtypeid = 1 ;
        }
        if($stypeid){
            $stypeid = $stypeid;
            $criteria->addCondition('appsmtypeid='.$stypeid);
        }else{
            $stypeid = 0;
        }
        $bigcriteria->addCondition('id='.$bigtypeid);
        $stypecriteria->addCondition('s_bigtypeid='.$bigtypeid);
        $criteria->addCondition('appbigtypeid='.$bigtypeid);
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
        $bigresult = $bigmodel->find($bigcriteria);
        $styperesult = $stypemodel->findAll($stypecriteria);
		$result = $model->findAll($criteria);
        $this->render('index', array ('model' => $model,'bigmodel' => $bigmodel,'bigresult'=>$bigresult,'stypeid'=>$stypeid,'styperesult'=>$styperesult, 'datalist' => $result , 'pagebar' => $pages));
	}
    public  function actiontext(){
        $model=new WuType;
        $criteria = new CDbCriteria();
        $criteria->with = "stype";
        $saveresult = $model->findAll($criteria);
        var_dump($saveresult);
    }
    /*排序操作*/
    public function  actionorderdo(){
        $model=new WuApp;
        //$bigmodel=new WuBigType;
        $criteria = new CDbCriteria();
        $savecriteria = new CDbCriteria();
        $countcriteria = new CDbCriteria();
        //$docountcriteria = new CDbCriteria();

        $neworder = intval(Yii::app()->request->getParam('neworder'));
        $appid = intval(Yii::app()->request->getParam('appid'));
        $bigtypeid = intval(Yii::app()->request->getParam('bigtypeid'));
        $smtypeid = intval(Yii::app()->request->getParam('smtypeid'));
        $countcriteria->addCondition('appbigtypeid='.$bigtypeid);
        $countcriteria->addCondition('appsmtypeid='.$smtypeid);
        $thecount = $model->count($countcriteria);
        if($thecount>=$neworder){
            $criteria->addCondition('id='.$appid);
            $theresult = $model->find($criteria);
            if($theresult){
                $editorder = $theresult['apporder'];
                $savecriteria->addCondition('appbigtypeid='. $bigtypeid);
                $savecriteria->addCondition('appsmtypeid='. $smtypeid);
                if($editorder > $neworder ){
                    $savecriteria->addBetweenCondition('apporder', $neworder, $editorder);
                }else{
                    $savecriteria->addBetweenCondition('apporder', $editorder, $neworder);
                }
                $saveresult = $model->findAll($savecriteria);
                if($saveresult){
                    foreach ($saveresult as &$list){
                        if($list['id']== $appid ){
                            $saveorder = $neworder;
                        }elseif ($neworder > $editorder){
                            $saveorder = $list['apporder'] -1;
                        }else{
                            $saveorder = $list['apporder'] +1;
                        }
                        //$docountcriteria->addCondition('id='.$list['id']);
                        //$model->s_order=$saveorder;
                        $model->updateBypk($list['id'],array('apporder'=>$saveorder));
                        if($model){
                            $art['code'] = 0;
                            $art['msg'] = '修改成功';
                        }else{
                            $art['code'] = 1;
                            $art['msg'] = '修改失败';
                        }
                    }

                }else{
                    $art['code'] = 5;
                    $art['msg'] = '序列查询失败';
                }
            }else{
                $art['code'] = 6;
                $art['msg'] = '数据不存在';
            }
        }else{
            $art['code'] = 7;
            $art['msg'] = '排序不允许超过该类最大值';
        }

        Helper::ajaxReturn($art);
    }
	/**
	* create a particular model.
	* If create is successful, the browser will be redirected to the 'index' page.
	* @param integer $id the ID of the model to be created
	*/
	public function actionCreate()
	{
		$model=new WuApp;
        $listimg = array();

       // $stmodel=new WuType;
       // $criteria = new CDbCriteria();
       // $criteria->with = "stype";
        //$syresult = $stmodel->findAll($criteria);
        $countcriteria = new CDbCriteria();
		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['WuApp']))
		{
			$model->attributes=$_POST['WuApp'];
            $countcriteria->addCondition('appbigtypeid='.$model['appbigtypeid']);
            $countcriteria->addCondition('appsmtypeid='.$model['appsmtypeid']);
            $count = $model->count($countcriteria);
			$model->appdodate=time();
            $model->apporder=$count+1;
			//$model->updatetime=time();
			if($model->save())
				$this->redirect(array('index'));
		}

		$this->render('create',array(
			'model'=>$model,'listimg'=>$listimg
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

        $listimg = explode(',',$model['appimg']);


		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['WuApp']))
		{
			$model->attributes=$_POST['WuApp'];
			$model->appdodate=time();
			if($model->save())
			    $this->redirect(array('index'));
		}

		$this->render('update',array(
			'model'=>$model,'listimg'=>$listimg
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
				WuApp::model()->deleteAll($criteria);
				break;
			case 'show':
			//显示
				WuApp::model()->updateAll(array('status' => 0), $criteria);
				break;
			case 'hide':
			//隐藏
				WuApp::model()->updateAll(array('status' => 1), $criteria);
				break;
			case 'sortOrder':
				$sortOrder = $_POST['order'];
				foreach((array)$ids as $id){
					$catalogModel = WuApp::model()->findByPk($id);
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

    /*查询APP类型所属分类*/
    public function  actionapplist(){
        $model=new WuApp;
        $stmodel=new WuType;

        $criteria = new CDbCriteria();
        $bigtypeid = intval(Yii::app()->request->getParam('bigtypeid'));
        $criteria->addCondition('s_bigtypeid='.$bigtypeid);
        $criteria->addCondition('s_power=0');
        $styperesult = $stmodel->findAll($criteria);
        if($styperesult){

            $art['result'] = json_decode(CJSON::encode($styperesult),TRUE);
            $art['code'] = 0;
            $art['msg'] = '查找成功';
        }else{
            $art['code'] = 1;
            $art['msg'] = '查找失败';
        }
        Helper::ajaxReturn($art);
    }
	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return WuApp the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=WuApp::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param WuApp $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='wu-app-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}



}
