<?php

class WuGiftController extends Backend
{

    protected $_goodstypelist;
    protected $_goodskdlist;
    protected $_goodsqdlist;
    protected $_goodsbqlist;
    //初始化函数
    public function init(){
        parent::init();
        $this->_goodstypelist=WuGift::getgoodslist();
        $this->_goodskdlist=WuGift::getgoodstypelist(1);
        $this->_goodsqdlist=WuGift::getgoodstypelist(2);
        $this->_goodsbqlist=WuGift::getgoodstypelist(3);
    }


	public function actionIndex(){

		$model=new WuGift;
        $smodel=new WuType;
		//条件
		$criteria = new CDbCriteria();
        $bigcriteria = new CDbCriteria();
        $bigtypeid = intval(Yii::app()->request->getParam('bigtypeid'));
        if($bigtypeid){
            $bigtypeid = $bigtypeid;
            $bigcriteria->addCondition('id='.$bigtypeid);
            $criteria->addCondition('gifttype='.$bigtypeid);
        }


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
        $bigresult = $smodel->find($bigcriteria);
		$this->render('index', array ('model' => $model, 'datalist' => $result,'bigresult'=>$bigresult, 'pagebar' => $pages));
	}

    /*排序操作*/
    public function  actionorderdo(){
        $model=new WuGift;
        //$bigmodel=new WuBigType;
        $criteria = new CDbCriteria();
        $savecriteria = new CDbCriteria();
        $countcriteria = new CDbCriteria();
        //$docountcriteria = new CDbCriteria();

        $neworder = intval(Yii::app()->request->getParam('neworder'));
        $giftid = intval(Yii::app()->request->getParam('giftid'));
        $gifttype = intval(Yii::app()->request->getParam('gifttype'));

        $countcriteria->addCondition('gifttype='.$gifttype);
        $thecount = $model->count($countcriteria);
        if($thecount>=$neworder){
            $criteria->addCondition('id='.$giftid);
            $theresult = $model->find($criteria);
            if($theresult){
                $editorder = $theresult['giftorder'];
                $savecriteria->addCondition('gifttype='.$gifttype);
                //$savecriteria->addCondition('appsmtypeid='. $smtypeid);
                if($editorder > $neworder ){
                    $savecriteria->addBetweenCondition('giftorder', $neworder, $editorder);
                }else{
                    $savecriteria->addBetweenCondition('giftorder', $editorder, $neworder);
                }
                $saveresult = $model->findAll($savecriteria);
                if($saveresult){
                    foreach ($saveresult as &$list){
                        if($list['id']== $giftid ){
                            $saveorder = $neworder;
                        }elseif ($neworder > $editorder){
                            $saveorder = $list['giftorder'] -1;
                        }else{
                            $saveorder = $list['giftorder'] +1;
                        }
                        //$docountcriteria->addCondition('id='.$list['id']);
                        //$model->s_order=$saveorder;
                        $model->updateBypk($list['id'],array('giftorder'=>$saveorder));
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
		$model=new WuGift;
        $countcriteria = new CDbCriteria();
		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['WuGift']))
		{
			$model->attributes=$_POST['WuGift'];
            $countcriteria->addCondition('gifttype='.$model['gifttype']);
            $thecount = $model->count($countcriteria);
			$model->giftdate=time();
			$model->giftorder= $thecount+1;
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

		if(isset($_POST['WuGift']))
		{
			$model->attributes=$_POST['WuGift'];
			$model->giftdate=time();
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
				WuGift::model()->deleteAll($criteria);
				break;
			case 'show':
			//显示
				WuGift::model()->updateAll(array('status' => 0), $criteria);
				break;
			case 'hide':
			//隐藏
				WuGift::model()->updateAll(array('status' => 1), $criteria);
				break;
			case 'sortOrder':
				$sortOrder = $_POST['order'];
				foreach((array)$ids as $id){
					$catalogModel = WuGift::model()->findByPk($id);
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
	 * @return WuGift the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=WuGift::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param WuGift $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='wu-gift-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}

    public function actionGetItemInfo()
    {
        $url = $_POST['url'];
        if ($url) {
            preg_match('/taobao.com/', $url, $t);
            preg_match('/tmall.com/', $url, $tm);
            if (isset($t['0']) || isset($tm['0'])) {
                preg_match('/id=\d*/', $url, $data);
                $numIid = trim($data[0], 'id=');
                require_once(Yii::getPathOfAlias('ext') . "/TopSDK/config.php");
                require_once(Yii::getPathOfAlias('ext') . "/TopSDK/Api/TopApi.class.php");
                $taobao = new TopApi(APP_KEY, APP_SECRET);
                $item = $taobao->getItemInfo($numIid);

                $result = array(
                    'errno' => 0,
                    'obj' => array()
                );
                if (is_array($item)) {
                    $result['obj'] = $item[0];
                } else {
                    $result['errno'] = $taobao->error();
                }
                Helper::ajaxReturn($result);
            } else {
                Helper::ajaxReturn(array('errno' => '商品链接不正确'));
            }
        } else {
            Helper::ajaxReturn(array('errno' => '商品链接未填写'));
        }
    }
}
