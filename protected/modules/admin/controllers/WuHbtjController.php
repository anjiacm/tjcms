<?php

class WuHbtjController extends Backend
{

    protected $_goodstypelist;
    //初始化函数
    public function init(){
        parent::init();
        $this->_goodstypelist=WuBigtype::getList();
    }


	public function actionIndex(){

		$model=new WuHbtj;
        $bigmodel=new WuBigType;
		//条件
		$criteria = new CDbCriteria();

        $bigcriteria = new CDbCriteria();
        $bigtypeid = intval(Yii::app()->request->getParam('bigtypeid'));
        if($bigtypeid){
            $bigtypeid = $bigtypeid;

        }else{
            $bigtypeid = 1 ;
        }
        $bigcriteria->addCondition('id='.$bigtypeid);
        $criteria->addCondition('hbtypeid='.$bigtypeid);
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
        $bigresult = $bigmodel->find($bigcriteria);
		$this->render('index', array ('model' => $model,'bigresult'=>$bigresult, 'datalist' => $result , 'pagebar' => $pages));
	}

    /*排序操作*/
    public function  actionorderdo(){
        $model=new WuHbtj;
        //$bigmodel=new WuBigType;
        $criteria = new CDbCriteria();
        $savecriteria = new CDbCriteria();
        $countcriteria = new CDbCriteria();
        $docountcriteria = new CDbCriteria();

        $neworder = intval(Yii::app()->request->getParam('neworder'));
        $hbid = intval(Yii::app()->request->getParam('hbid'));
        $hbtypeid = intval(Yii::app()->request->getParam('hbtypeid'));

        $countcriteria->addCondition('hbtypeid='.$hbtypeid);
        $thecount = $model->count($countcriteria);
        if($thecount>=$neworder){
            $criteria->addCondition('id='.$hbid);
            $theresult = $model->find($criteria);
            if($theresult){
                $editorder = $theresult['hborder'];
                $savecriteria->addCondition('hbtypeid='. $hbtypeid);
                if($editorder > $neworder ){
                    $savecriteria->addBetweenCondition('hborder', $neworder, $editorder);
                }else{
                    $savecriteria->addBetweenCondition('hborder', $editorder, $neworder);
                }
                $saveresult = $model->findAll($savecriteria);
                if($saveresult){
                    foreach ($saveresult as &$list){
                        if($list['id']== $hbid ){
                            $saveorder = $neworder;
                        }elseif ($neworder > $editorder){
                            $saveorder = $list['hborder'] -1;
                        }else{
                            $saveorder = $list['hborder'] +1;
                        }
                        //$docountcriteria->addCondition('id='.$list['id']);
                        //$model->s_order=$saveorder;
                        $model->updateBypk($list['id'],array('hborder'=>$saveorder));
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
		$model=new WuHbtj;
        $countcriteria = new CDbCriteria();
		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['WuHbtj']))
		{
			$model->attributes=$_POST['WuHbtj'];
            $countcriteria->addCondition('hbtypeid='.$model['hbtypeid']);
            $count = $model->count($countcriteria);
			$model->hbdodate=time();
			$model->hborder=$count+1;
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

		if(isset($_POST['WuHbtj']))
		{
			$model->attributes=$_POST['WuHbtj'];
			$model->hbdodate=time();
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
				WuHbtj::model()->deleteAll($criteria);
				break;
			case 'show':
			//显示
				WuHbtj::model()->updateAll(array('status' => 0), $criteria);
				break;
			case 'hide':
			//隐藏
				WuHbtj::model()->updateAll(array('status' => 1), $criteria);
				break;
			case 'sortOrder':
				$sortOrder = $_POST['order'];
				foreach((array)$ids as $id){
					$catalogModel = WuHbtj::model()->findByPk($id);
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
	 * @return WuHbtj the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=WuHbtj::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param WuHbtj $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='wu-hbtj-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
