<?php

class VipLogController extends Backend
{

   //初始化函数
	public function init(){
		parent::init();
    }


	public function actionIndex(){

		$model=new VipLog;
		//条件
		$criteria = new CDbCriteria();
		//$title = trim(Yii::app()->request->getParam('title'));
		//$position_id = intval(Yii::app()->request->getParam('position_id'));
		//$title && $criteria->addColumnCondition(array('title' =>$title));
		//$position_id && $criteria->addColumnCondition(array('position_id', $position_id));

        $criteria->order = 't.id ASC';
        $criteria->addCondition('status=1');
		$count = $model->count($criteria);

		//分页
		$pages = new CPagination($count);
		$pages->pageSize = 20;
		$pages->applyLimit($criteria);

		//查询

		$result = $model->findAll($criteria);
		$this->render('index', array ('model' => $model, 'datalist' => $result , 'pagebar' => $pages));
	}


    public function actiondingdan(){
        $model=new VipLog;
        $timetype = empty($_REQUEST['timetype'])?1:intval($_REQUEST['timetype']);//1按天查询  2 按小时查询
        $thetype=empty($_REQUEST['thetype'])?1:intval($_REQUEST['thetype']);//1 时段查询（7天 15 30） 2 按渠道查询（模糊查询）  3按版本查询 4 时间段查询
        $thepages = empty($_REQUEST['page'])?1:intval($_REQUEST['page']);
        /*渠道信息*/
        $qdtext = trim(Yii::app()->request->getParam('searchall'));
        /*时间段查询*/

        $starttime = empty($_REQUEST['starttime'])?strtotime(date('Y-m-d 00:00')):strtotime($_REQUEST['starttime']);
        $endtime = empty($_REQUEST['endtime'])?strtotime(date('Y-m-d 23:00')):strtotime($_REQUEST['endtime']);
        /*时段查询*/
        $thetime=empty($_REQUEST['searchall'])?7:intval($_REQUEST['searchall']);
        /*版本查询*/
        $version=empty($_REQUEST['searchall'])?7:trim($_REQUEST['searchall']);
        /*默认七天查询*/

        $listsql ='select p_vip_log.channel as list from p_vip_log where p_vip_log.`status` = 1 and p_vip_log.channel <> ""  GROUP BY list';
        $listall=Yii::app()->db_zbdindan->createCommand($listsql)->queryAll();
        $listall=json_decode(CJSON::encode($listall),TRUE);

        $listallresult = array();
        foreach ($listall as $key=>$value) {
            $listallresult[$key]=$value['list'];
        }
//        $list = array_unique(array_column($listall, 'list'));
//        var_dump($list);
//        exit();
        if($timetype ==2){
            if($thetype ==1){// 时段查询

                $countsql='select count(*) as total FROM (select  DATE_FORMAT(FROM_UNIXTIME(p_vip_log.add_time),"%Y-%m-%d %H:00:00") as time from p_vip_log where p_vip_log.`status` = 1 and DATE_SUB(curdate(),INTERVAL '.$thetime.' day) <= DATE_FORMAT(FROM_UNIXTIME(p_vip_log.add_time),"%Y-%m-%d") group by time) as a';


            }elseif($thetype ==2){//按渠道查询

                $countsql='select count(*) as total FROM (select  DATE_FORMAT(FROM_UNIXTIME(p_vip_log.add_time),"%Y-%m-%d %H:00:00") as time from p_vip_log where p_vip_log.`status` = 1 and locate('."'".$qdtext."'".',p_vip_log.channel )  group by time) as a';

            }elseif($thetype ==3){//按版本查询

                $countsql='select count(*) as total FROM (select DATE_FORMAT(FROM_UNIXTIME(p_vip_log.add_time),"%Y-%m-%d %H:00:00") as time from p_vip_log where p_vip_log.`status` = 1 and p_vip_log.version_name= '."$version".' group by time) as a';

            }elseif($thetype ==4){//时间段查询

                $countsql='select count(*) as total FROM (select  DATE_FORMAT(FROM_UNIXTIME(p_vip_log.add_time),"%Y-%m-%d %H:00:00") as time from p_vip_log where p_vip_log.`status` = 1 and p_vip_log.add_time BETWEEN '.$starttime.' and  '.$endtime.' group by time ) as a ';

            }else{

                $countsql='select count(*) as total FROM (select  DATE_FORMAT(FROM_UNIXTIME(p_vip_log.add_time),"%Y-%m-%d %H:00:00") as time from p_vip_log where p_vip_log.`status` = 1 and DATE_SUB(curdate(),INTERVAL '.$thetime.' day) <= DATE_FORMAT(FROM_UNIXTIME(p_vip_log.add_time),"%Y-%m-%d") group by time) as a';


            }
            $criteria = new CDbCriteria();
            $count=Yii::app()->db_zbdindan->createCommand($countsql)->queryAll();

            $count =$count[0]['total'];
            $pages = new CPagination($count);
            $pages->pageSize = 20;
            $pages->applyLimit($criteria);

            $limit =20;
            $offset = ($thepages-1)*$limit;
        }else{
            $criteria = new CDbCriteria();
            $count=0;
            $pages = new CPagination($count);
            $pages->pageSize = 20;
            $pages->applyLimit($criteria);
        }
        if($thetype){
            if($thetype ==1){// 时段查询
                if($timetype ==1){
                    $sql=' select DATE_FORMAT(FROM_UNIXTIME(p_vip_log.add_time),"%Y-%m-%d") as day,sum(p_vip_log.money) as  money from p_vip_log where p_vip_log.`status` = 1 and DATE_SUB(curdate(),INTERVAL '.$thetime.' day) <= DATE_FORMAT(FROM_UNIXTIME(p_vip_log.add_time),"%Y-%m-%d") group by day';
                }else{
                    $sql='select  DATE_FORMAT(FROM_UNIXTIME(p_vip_log.add_time),"%Y-%m-%d %H:00:00") as time,sum(p_vip_log.money) as  money from p_vip_log where p_vip_log.`status` = 1 and DATE_SUB(curdate(),INTERVAL '.$thetime.' day) <= DATE_FORMAT(FROM_UNIXTIME(p_vip_log.add_time),"%Y-%m-%d") group by time  limit '.$offset.','.$limit;
                }

            }elseif($thetype ==2){//按渠道查询
                if($timetype ==1){
                    $sql='select p_vip_log.channel ,DATE_FORMAT(FROM_UNIXTIME(p_vip_log.add_time),"%Y-%m-%d") as day,sum(p_vip_log.money) as  money from p_vip_log where p_vip_log.`status` = 1 and locate('."'".$qdtext."'".' ,p_vip_log.channel ) group by day';

                }else{
                    $sql='select p_vip_log.channel , DATE_FORMAT(FROM_UNIXTIME(p_vip_log.add_time),"%Y-%m-%d %H:00:00") as time, sum(p_vip_log.money) as  money from p_vip_log where p_vip_log.`status` = 1 and locate('."'".$qdtext."'".' ,p_vip_log.channel ) group by time limit '.$offset.','.$limit;
                }
            }elseif($thetype ==3){//按版本查询
                if($timetype ==1){
                    $sql='select p_vip_log.version_name ,DATE_FORMAT(FROM_UNIXTIME(p_vip_log.add_time),"%Y-%m-%d") as day,sum(p_vip_log.money) as  money from p_vip_log where p_vip_log.`status` = 1 and p_vip_log.version_name= '."$version".' group by day';
                }else{
                    $sql='select p_vip_log.version_name , DATE_FORMAT(FROM_UNIXTIME(p_vip_log.add_time),"%Y-%m-%d %H:00:00") as time,sum(p_vip_log.money) as  money from p_vip_log where p_vip_log.`status` = 1 and p_vip_log.version_name= '."$version".' group by time  limit '.$offset.','.$limit;
                }
            }elseif($thetype ==4){//时间段查询
                if($timetype ==1){
                    $sql='select DATE_FORMAT(FROM_UNIXTIME(p_vip_log.add_time),"%Y-%m-%d") as day,sum(p_vip_log.money) as  money from p_vip_log where p_vip_log.`status` = 1 and p_vip_log.add_time BETWEEN '.$starttime.' and  '.$endtime.' group by day';
                }else{
                    $sql='select DATE_FORMAT(FROM_UNIXTIME(p_vip_log.add_time),"%Y-%m-%d %H:00:00") as time,sum(p_vip_log.money) as  money from p_vip_log where p_vip_log.`status` = 1 and p_vip_log.add_time BETWEEN '.$starttime.' and  '.$endtime.' group by time  limit '.$offset.','.$limit;
                }
            }else{
                if($timetype ==1){
                    $sql=' select DATE_FORMAT(FROM_UNIXTIME(p_vip_log.add_time),"%Y-%m-%d") as day,sum(p_vip_log.money) as  money from p_vip_log where p_vip_log.`status` = 1 and DATE_SUB(curdate(),INTERVAL '.$thetime.' day) <= DATE_FORMAT(FROM_UNIXTIME(p_vip_log.add_time),"%Y-%m-%d") group by day';

                }else{
                    $sql='select  DATE_FORMAT(FROM_UNIXTIME(p_vip_log.add_time),"%Y-%m-%d %H:00:00") as time,sum(p_vip_log.money) as  money from p_vip_log where p_vip_log.`status` = 1 and DATE_SUB(curdate(),INTERVAL '.$thetime.' day) <= DATE_FORMAT(FROM_UNIXTIME(p_vip_log.add_time),"%Y-%m-%d") group by time limit '.$offset.','.$limit;

                }
            }
        }else{

            if($timetype ==1){
                $sql=' select DATE_FORMAT(FROM_UNIXTIME(p_vip_log.add_time),"%Y-%m-%d") as day,sum(p_vip_log.money) as  money from p_vip_log where p_vip_log.`status` = 1 and DATE_SUB(curdate(),INTERVAL '.$thetime.' day) <= DATE_FORMAT(FROM_UNIXTIME(p_vip_log.add_time),"%Y-%m-%d") group by day';

            }else{
                $sql='select  DATE_FORMAT(FROM_UNIXTIME(p_vip_log.add_time),"%Y-%m-%d %H:00:00") as time,sum(p_vip_log.money) as  money from p_vip_log where p_vip_log.`status` = 1 and DATE_SUB(curdate(),INTERVAL '.$thetime.' day) <= DATE_FORMAT(FROM_UNIXTIME(p_vip_log.add_time),"%Y-%m-%d") group by time limit '.$offset.','.$limit;

            }
        }
       // file_put_contents('./11.txt',$sql,FILE_APPEND); //测试用
        $result=Yii::app()->db_zbdindan->createCommand($sql)->queryAll();

        //查询
        //       // $result = $model->findAll($criteria);
        $this->render('dingdan', array ('model' => $model, 'listall'=>$listallresult,'datalist' => $result,'qdtext'=> $qdtext, 'starttime'=> $starttime, 'endtime'=> $endtime,'thetime'=> $thetime,'version'=> $version,'thetype'=>$thetype,'timetype' => $timetype ,'pagebar'=>$pages));
    }
	/**
	* create a particular model.
	* If create is successful, the browser will be redirected to the 'index' page.
	* @param integer $id the ID of the model to be created
	*/
	public function actionCreate()
	{
		$model=new VipLog;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['VipLog']))
		{
			$model->attributes=$_POST['VipLog'];
			$model->createtime=time();
			$model->updatetime=time();
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

		if(isset($_POST['VipLog']))
		{
			$model->attributes=$_POST['VipLog'];
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
				VipLog::model()->deleteAll($criteria);
				break;
			case 'show':
			//显示
				VipLog::model()->updateAll(array('status' => 1), $criteria);
				break;
			case 'hide':
			//隐藏
				VipLog::model()->updateAll(array('status' => 0), $criteria);
				break;
			case 'sortOrder':
				$sortOrder = $_POST['order'];
				foreach((array)$ids as $id){
					$catalogModel = VipLog::model()->findByPk($id);
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
	 * @return VipLog the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=VipLog::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param VipLog $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='vip-log-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
