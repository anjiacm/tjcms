<?php

class DefaultController extends ApiBase implements ErrorCode
{
	/*public function filters() {
		return array (
			array (
				'COutputCache + index, category, content',
				'duration' => 3600,
				'varyByParam' => array('id','page'),
			)
		);
	}*/

	public function actionIndex()
	{
		$content = $this->render('index','',true);
		Helper::writeHtml($content,"aaa");
	}

	public function actionGetLoading()
	{
		$timestamp = empty($_REQUEST['timestamp'])?'':intval($_REQUEST['timestamp']);
		$id = empty($_REQUEST['id'])?'':intval($_REQUEST['id']);
		$sign = isset($_REQUEST['sign'])? trim($_REQUEST['sign']):'';
		$_sign = md5($id.$timestamp. Yii::app()->params['apikey']);//md5(id+timestamp+apikey)
		if($sign!=$_sign){
			$result['msg'] = "error sign";
			$result['code'] = self::ERROR_SIGN;
			$this->ajaxReturn($result);
		}
		if(empty($id)){
			$result['msg']='error id';
			$result['code']= self::ERROR_ID;
			$this->ajaxReturn($result);
		}
		$model = new MacLoadingRelation();
		$data = $model->getApiData($id);
		if($data){
			$data = CJSON::decode($data,true);
			return $this->ajaxReturn($data);
		}
	}

	public function actionGetChannelPageInfo()
	{
		$timestamp = empty($_REQUEST['timestamp'])?'':intval($_REQUEST['timestamp']);
		$channel_name = isset($_REQUEST['channel_name'])?$_REQUEST['channel_name']:'';
		$channel_encode = strtoupper(urlencode($channel_name));
		$channel_decode = strtoupper(urldecode($channel_name));
		$sign = isset($_REQUEST['sign'])? trim($_REQUEST['sign']):'';
		$_sign = md5($channel_encode.$timestamp. Yii::app()->params['apikey']);//md5(id+timestamp+apikey)
		if($sign!=$_sign){
			$result['msg'] = "error sign";
			$result['code'] = self::ERROR_SIGN;
			$this->ajaxReturn($result);
		}
		if(isset($channel_decode) && $channel_decode==""){
			$result['msg']='error channel';
			$result['code']= self::ERROR_ID;
			$this->ajaxReturn($result);
		}
		$cdb = new CDbCriteria();
		$model = new PhChannelPage();
		$cdb->compare('channel_title',$channel_decode);
		$data  = $model->find($cdb);
		$json = array();
		if($data){
			$page_id =empty($data->page_id)?1:$data->page_id;//飘花后台默认栏目ID为1
			$json = Yii::app()->curl->get('http://api.qizhouqi.com/loading/?id='.$page_id);
			$json = CJSON::decode($json,true);
			$result['channel']=array(
				'title'=>$data->channel_title,
			);
			$result['code']=1;
		}else{
			$page_id =1;//飘花后台默认栏目ID为1
			$json = Yii::app()->curl->get('http://api.qizhouqi.com/loading/?id='.$page_id);
			$json = CJSON::decode($json,true);
			$result['channel']=array(
				'title'=>$channel_decode,
			);
			$result['code']=1;
		}

		$result['videos']=$json;

		return $this->ajaxReturn($result);
	}
	/*获取渠道号  版本号id*/
	public function actionchannelnum(){
        $Chanelmodel=new WuChanelOne();
        $Chanel_tmodel=new WuChanelTwo();
        $Chanel_smodel=new WuChanelThree();
        $Versionmodel=new WuVersion();
        $version_name = empty($_REQUEST['version_name'])?'':trim($_REQUEST['version_name']);
        $quedao = empty($_REQUEST['channel'])?'':urldecode(trim($_REQUEST['channel']));

        $chanelcriteria = new CDbCriteria();
        $t_chanelcriteria = new CDbCriteria();
        $s_chanelcriteria = new CDbCriteria();
        $versioncriteria = new CDbCriteria();
        /*渠道查询*///$arr = preg_split('/[-+\*]+/is', $str);
        $chanel = preg_split('/[-_\*]+/is', $quedao);
        //$chanel=explode('-',$quedao);
        if (preg_match("/[\x7f-\xff]/", $quedao)) {
            $chanel_big=empty($chanel[0])?'未知':urldecode($chanel[0]);
            $chanel_two='未知';
            $chanel_small=empty($chanel[1])?'未知':urldecode($chanel[1]);
        }else{
            if(preg_match('/[a-zA-Z]/',$chanel[1])){
                if($chanel[1]=="ALM"){
                    $chanel_big=empty($chanel[1])?'未知':urldecode($chanel[1]);
                    $chanel_two=empty($chanel[2])?'未知':urldecode($chanel[2]);
                    $chanel_small=empty($chanel[0])?'未知':urldecode($chanel[0]);
                }else{
                    $chanel_big=empty($chanel[0])?'未知':urldecode($chanel[0]);
                    $chanel_small=empty($chanel[1])?'未知':urldecode($chanel[1]);
                    $chanel_two=empty($chanel[2])?'未知':urldecode($chanel[2]);
                }

            }else{

                $chanel_big=empty($chanel[0])?'未知':urldecode($chanel[0]);
                $chanel_two=empty($chanel[1])?'未知':urldecode($chanel[1]);
                $chanel_small=empty($chanel[2])?'未知':urldecode($chanel[2]);


            }

        }

        /*渠道一*/
        $chanelcriteria->addCondition('chanle="'.$chanel_big.'"');
        $Chanelfinddo = $Chanelmodel->find($chanelcriteria);
        if(!$Chanelfinddo){
            $Chanelmodel->chanle=$chanel_big;
            $Chanelmodel->dodate=time();
            $Chanelmodel->save();
            $bigchanelid=$Chanelmodel->id;
        }else{
            $bigchanelid=$Chanelfinddo->id;
        }
        /*渠道二*/
        $t_chanelcriteria->addCondition('chanle_web="'.$chanel_two.'"');
        $Chanel_tmall_finddo = $Chanel_tmodel->find($t_chanelcriteria);
        if(!$Chanel_tmall_finddo){
            $Chanel_tmodel->chanle_web=$chanel_two;
            $Chanel_tmodel->dodate=time();
            $Chanel_tmodel->save();
            $tmallchanelid=$Chanel_tmodel->id;
        }else{
            $tmallchanelid=$Chanel_tmall_finddo->id;
        }
        /*渠道三*/
        $s_chanelcriteria->addCondition('chanle_movie="'.$chanel_small.'"');
        $Chanel_small_finddo = $Chanel_smodel->find($s_chanelcriteria);
        if(!$Chanel_small_finddo){
            $Chanel_smodel->chanle_movie=$chanel_small;
            $Chanel_smodel->dodate=time();
            $Chanel_smodel->save();
            $smallchanelid=$Chanel_smodel->id;
        }else{
            $smallchanelid=$Chanel_small_finddo->id;
        }


        /*渠道查询结束*/

        /*版本查询*/
        $versioncriteria->addCondition('version_name="'.$version_name.'"');
        $Versionfinddo = $Versionmodel->find($versioncriteria);
        if(!$Versionfinddo){
            $Versionmodel->version_name=$version_name;
            $Versionmodel->dodate=time();
            $Versionmodel->save();
            $versionid=$Versionmodel->id;
        }else{
            $versionid=$Versionfinddo->id;
        }
        $art['chanel_bid']=$bigchanelid;
        $art['chanel_sid']=$smallchanelid;
        $art['chanel_web']=$tmallchanelid;
        $art['versionid']=$versionid;
        return $this->ajaxReturn($art);
    }
/*&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&统计信息&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&*/
    public function actionusertj(){
        $tjmodel=new WuTjuserall();
        $tjerrmodel=new WuTjusererror();
        $daymodel=new WuTjdayuser();
        $hourmodel=new WuTjuseruphour();


        $Chanelmodel=new WuChanelOne();
        $Chanel_tmodel=new WuChanelTwo();
        $Chanel_smodel=new WuChanelThree();
        $Versionmodel=new WuVersion();
        /*安全验证*/
        $openkey= empty($_REQUEST['openkey'])?'':$_REQUEST['openkey'];
        $theday= empty($_REQUEST['theday'])?'':$_REQUEST['theday'];
        $key = md5('yr2017'.$theday);
        /*安全验证结束*/

        if($openkey){//key验证
            if($key == $openkey ) {
                $device_id = empty($_REQUEST['device_id'])?'':trim($_REQUEST['device_id']);
                $version_name = empty($_REQUEST['version_name'])?'':trim($_REQUEST['version_name']);
                $quedao = empty($_REQUEST['channel'])?'':trim($_REQUEST['channel']);

                $chanelcriteria = new CDbCriteria();
                $t_chanelcriteria = new CDbCriteria();
                $s_chanelcriteria = new CDbCriteria();
                $versioncriteria = new CDbCriteria();
                /*渠道查询*///$arr = preg_split('/[-+\*]+/is', $str);
                $chanel = preg_split('/[-_\*]+/is', $quedao);
                //$chanel=explode('-',$quedao);
                if (preg_match("/[\x7f-\xff]/", $quedao)) {
                    $chanel_big=empty($chanel[0])?'未知':$chanel[0];
                    $chanel_two='未知';
                    $chanel_small=empty($chanel[1])?'未知':urldecode($chanel[1]);
                }else{
                    if(preg_match('/[a-zA-Z]/',$chanel[1])){
                        if($chanel[1]=="ALM"){
                            $chanel_big=empty($chanel[1])?'未知':$chanel[1];
                            $chanel_two=empty($chanel[2])?'未知':$chanel[2];
                            $chanel_small=empty($chanel[0])?'未知':urldecode($chanel[0]);
                        }else{
                            $chanel_big=empty($chanel[0])?'未知':$chanel[0];
                            $chanel_small=empty($chanel[1])?'未知':urldecode($chanel[1]);
                            $chanel_two=empty($chanel[2])?'未知':$chanel[2];
                        }

                    }else{

                        $chanel_big=empty($chanel[0])?'未知':$chanel[0];
                        $chanel_two=empty($chanel[1])?'未知':$chanel[1];
                        $chanel_small=empty($chanel[2])?'未知':urldecode($chanel[2]);


                    }

                }
                /*渠道一*/
                $chanelcriteria->addCondition('chanle="'.$chanel_big.'"');
                $Chanelfinddo = $Chanelmodel->find($chanelcriteria);
                if(!$Chanelfinddo){
                    $Chanelmodel->chanle=$chanel_big;
                    $Chanelmodel->dodate=time();
                    $Chanelmodel->save();
                    $bigchanelid=$Chanelmodel->id;
                }else{
                    $bigchanelid=$Chanelfinddo->id;
                }
                /*渠道二*/
                $t_chanelcriteria->addCondition('chanle_web="'.$chanel_two.'"');
                $Chanel_tmall_finddo = $Chanel_tmodel->find($t_chanelcriteria);
                if(!$Chanel_tmall_finddo){
                    $Chanel_tmodel->chanle_web=$chanel_two;
                    $Chanel_tmodel->dodate=time();
                    $Chanel_tmodel->save();
                    $tmallchanelid=$Chanel_tmodel->id;
                }else{
                    $tmallchanelid=$Chanel_tmall_finddo->id;
                }
                /*渠道三*/
                $s_chanelcriteria->addCondition('chanle_movie="'.$chanel_small.'"');
                $Chanel_small_finddo = $Chanel_smodel->find($s_chanelcriteria);
                if(!$Chanel_small_finddo){
                    $Chanel_smodel->chanle_movie=$chanel_small;
                    $Chanel_smodel->dodate=time();
                    $Chanel_smodel->save();
                    $smallchanelid=$Chanel_smodel->id;
                }else{
                    $smallchanelid=$Chanel_small_finddo->id;
                }


                /*渠道查询结束*/

                /*版本查询*/
                $version_name=empty($version_name)?'未知':$version_name;
                $versioncriteria->addCondition('version_name="'.$version_name.'"');
                $Versionfinddo = $Versionmodel->find($versioncriteria);
                if(!$Versionfinddo){
                    $Versionmodel->version_name=$version_name;
                    $Versionmodel->dodate=time();
                    $Versionmodel->save();
                    $versionid=$Versionmodel->id;
                }else{
                    $versionid=$Versionfinddo->id;
                }
                /*版本查询结束*/

                if($device_id){
                    $criteria = new CDbCriteria();
                    $daycriteria = new CDbCriteria();
                    $criteria->addCondition('device_id="'.$device_id.'"');
                    $userfinddo = $tjmodel->find($criteria);

                    if(!$userfinddo){
                        $transaction=$tjmodel->dbConnection->beginTransaction();
                        $tjmodel->device_id=$device_id;
                        $tjmodel->version_name=$version_name;
                        $tjmodel->quedao=urldecode($quedao);
                        $tjmodel->update=time();
                        $tjmodel->usersum='1';
                        $tjmodel->chanel_bid=$bigchanelid;
                        $tjmodel->chanel_web=$tmallchanelid;
                        $tjmodel->chanel_sid=$smallchanelid;
                        $tjmodel->versionid=$versionid;
                        $tjmodel->save();

                        $onlyOne = $tjmodel->attributes['id'];

                        $daymodel->userid=$onlyOne;
                        $daymodel->quedao=urldecode($quedao);
                        $daymodel->device_id=$device_id;
                        $daymodel->version_name=$version_name;
                        $daymodel->dayupdate=strtotime(date('Y-m-d')) ;
                        $daymodel->upsum='1';
                        $daymodel->chanel_bid=$bigchanelid;
                        $daymodel->chanel_web=$tmallchanelid;
                        $daymodel->chanel_sid=$smallchanelid;
                        $daymodel->versionid=$versionid;
                        $daymodel->save();

                        $hourmodel->userid=$onlyOne;
                        $hourmodel->quedao=urldecode($quedao);
                        $hourmodel->device_id=$device_id;
                        $hourmodel->version_name=$version_name;
                        $hourmodel->dayupdate=time() ;
                        $hourmodel->chanel_bid=$bigchanelid;
                        $hourmodel->chanel_web=$tmallchanelid;
                        $hourmodel->chanel_sid=$smallchanelid;
                        $hourmodel->versionid=$versionid;
                        $hourmodel->save();
                        //file_put_contents('./add.log',$daymodel->attributes['id'].'//'.$tjmodel->attributes['id'].'//'.$hourmodel->attributes['id'],FILE_APPEND); //测试用
                        if($daymodel and $tjmodel and $hourmodel){
                            $transaction->commit();
                            $art['msg'] ='添加成功'.$onlyOne;
                            $art['code'] = 0;
                        }else{
                            $transaction->rollBack();
                            $art['msg'] ='添加失败';
                            $art['code'] = 1;
                        }
                    }else{

                        $transaction=$tjmodel->dbConnection->beginTransaction();
                        $userfinddo->usersum+=1 ;
                        // $userfinddo->chanel_bid=$bigchanelid;
                        // $userfinddo->chanel_sid=$smallchanelid;
                        // $userfinddo->versionid=$versionid;
                        $userfinddo->save();
                        $theday = strtotime(date('Y-m-d'));
                        $daycriteria->addCondition('device_id="'.$device_id.'"');
                        $daycriteria->addCondition('userid='.$userfinddo['id']);
                        $daycriteria->addCondition('quedao="'.$quedao.'"');
                        $daycriteria->addCondition('dayupdate='.$theday);

                        $dayuserfinddo = $daymodel->find($daycriteria);

                        if($dayuserfinddo){
                            $dayuserfinddo->upsum+=1 ;
                            $hourmodel->userid=$userfinddo['id'];;
                            $hourmodel->quedao=urldecode($quedao);
                            $hourmodel->device_id=$device_id;
                            $hourmodel->version_name=$version_name;
                            $hourmodel->dayupdate=time() ;
                            $hourmodel->chanel_bid=$bigchanelid;
                            $hourmodel->chanel_web=$tmallchanelid;
                            $hourmodel->chanel_sid=$smallchanelid;
                            $hourmodel->versionid=$versionid;
                            $hourmodel->save();
                            $dores=$dayuserfinddo->save();
                        }else{
                            $daymodel->userid=$userfinddo['id'];
                            $daymodel->quedao=urldecode($quedao);
                            $daymodel->device_id=$device_id;
                            $daymodel->version_name=$version_name;
                            $daymodel->dayupdate=strtotime(date('Y-m-d')) ;
                            $daymodel->upsum=1;
                            $daymodel->chanel_bid=$bigchanelid;
                            $daymodel->chanel_web=$tmallchanelid;
                            $daymodel->chanel_sid=$smallchanelid;
                            $daymodel->versionid=$versionid;
                            $dores=$daymodel->save();
                            $hourmodel->userid=$userfinddo['id'];;
                            $hourmodel->quedao=urldecode($quedao);
                            $hourmodel->device_id=$device_id;
                            $hourmodel->version_name=$version_name;
                            $hourmodel->dayupdate=time() ;
                            $hourmodel->chanel_bid=$bigchanelid;
                            $hourmodel->chanel_web=$tmallchanelid;
                            $hourmodel->chanel_sid=$smallchanelid;
                            $hourmodel->versionid=$versionid;
                            $hourmodel->save();

                        }
                        //file_put_contents('./save.log',$dayuserfinddo->attributes['id'].'//'.$userfinddo->attributes['id'].'//'.$hourmodel->attributes['id'],FILE_APPEND); //测试用
                        if($dores and $userfinddo and $hourmodel){
                            $transaction->commit();
                            $art['msg'] ='更新成功';
                            $art['code'] = 2;
                        }else{
                            $transaction->rollBack();
                            $art['msg'] ='更新失败';
                            $art['code'] = 3;
                        }
                    }
                    if($art['code'] == 1){
                        $tjerrmodel->device_id=$device_id;
                        $tjerrmodel->version_name=$version_name;
                        $tjerrmodel->quedao=$quedao;
                        $tjerrmodel->update=time();
                        $tjerrmodel->usersum='1';
                        $tjerrmodel->save();
                    }
                }else{
                    $art['msg'] ='缺少必要参数';
                    $art['code'] = 119;
                }

            }else{
                $art['msg'] ='参数错误';
                $art['code'] = 211;
            }
        }else{
            $art['msg'] ='非法访问';
            $art['code'] = 110;
        }
        return $this->ajaxReturn($art);
    }

    /*新版统计接口*/
    public function actionusertjnew(){
        $tjmodel=new WuTjuserall();
        $tjerrmodel=new WuTjusererror();
        $daymodel=new WuTjdayuser();
        $hourmodel=new WuTjuseruphour();
        $alldaymodel=new WuTjselectall();
        $alltimemodel=new WuTjselectallTime();
        $Chanelmodel=new WuChanelname();
        $Chanel_smodel=new WuChanelSmall();
        $Versionmodel=new WuVersion();
        /*安全验证*/
        $openkey= empty($_REQUEST['openkey'])?'':$_REQUEST['openkey'];
        $theday= empty($_REQUEST['theday'])?'':$_REQUEST['theday'];
        $key = md5('yr2017'.$theday);
        /*安全验证结束*/

        //if($openkey){//key验证
            //if($key == $openkey ) {
                $device_id = empty($_REQUEST['device_id'])?'':trim($_REQUEST['device_id']);
                $version_name = empty($_REQUEST['version_name'])?'未知':trim($_REQUEST['version_name']);
                $quedao = empty($_REQUEST['quedao'])?'':trim($_REQUEST['quedao']);

                //if($device_id){
                    $criteria = new CDbCriteria();
                    $daycriteria = new CDbCriteria();
                    $chanelcriteria = new CDbCriteria();
                    $s_chanelcriteria = new CDbCriteria();
                    $versioncriteria = new CDbCriteria();
                    /*渠道查询*/
                    $chanel=explode('-',$quedao);
                    $chanel_big=empty($chanel[0])?'未知':$chanel[0];
                    $chanel_small=empty($chanel[1])?'未知':$chanel[1];
                    $chanelcriteria->addCondition('chanlename="'.$chanel_big.'"');
                    $Chanelfinddo = $Chanelmodel->find($chanelcriteria);
                    if(!$Chanelfinddo){
                        $Chanelmodel->chanlename=$chanel_big;
                        $Chanelmodel->dodate=time();
                        $Chanelmodel->save();
                        $bigchanelid=$Chanelmodel->id;
                    }else{
                        $bigchanelid=$Chanelfinddo->id;
                    }
                    $s_chanelcriteria->addCondition('chanel_s_name="'.$chanel_small.'"');
                    $Chanel_small_finddo = $Chanel_smodel->find($s_chanelcriteria);
                    if(!$Chanel_small_finddo){
                        $Chanel_smodel->chanel_s_name=$chanel_small;
                        $Chanelmodel->dodate=time();
                        $Chanelmodel->save();
                        $smallchanelid=$Chanel_smodel->id;
                    }else{
                        $smallchanelid=$Chanel_small_finddo->id;
                    }
                    /*渠道查询结束*/

                    /*版本查询*/
                    $versioncriteria->addCondition('version_name="'.$version_name.'"');
                    $Versionfinddo = $Versionmodel->find($versioncriteria);
                    if(!$Versionfinddo){
                        $Versionmodel->version_name=$version_name;
                        $Versionmodel->dodate=time();
                        $Versionmodel->save();
                        $versionid=$Versionmodel->id;
                    }else{
                        $versionid=$Versionfinddo->id;
                    }
                    /*版本查询结束*/

                    $criteria->addCondition('device_id="'.$device_id.'"');
                    $userfinddo = $tjmodel->find($criteria);
                    if(!$userfinddo){
                        $transaction=$tjmodel->dbConnection->beginTransaction();
                        $tjmodel->device_id=$device_id;
                        $tjmodel->version_name=$version_name;
                        $tjmodel->quedao=$quedao;
                        $tjmodel->update=time();
                        $tjmodel->usersum='1';
                        $tjmodel->chanel_bid=$bigchanelid;
                        $tjmodel->chanel_sid=$smallchanelid;
                        $tjmodel->versionid=$versionid;
                        $tjmodel->save();

                        $onlyOne = $tjmodel->attributes['id'];

                        $daymodel->userid=$onlyOne;
                        $daymodel->quedao=$quedao;
                        $daymodel->device_id=$device_id;
                        $daymodel->version_name=$version_name;
                        $daymodel->dayupdate=strtotime(date('Y-m-d')) ;
                        $daymodel->upsum='1';
                        $daymodel->save();

                        $hourmodel->userid=$onlyOne;
                        $hourmodel->quedao=$quedao;
                        $hourmodel->device_id=$device_id;
                        $hourmodel->version_name=$version_name;
                        $hourmodel->dayupdate=time() ;
                        $hourmodel->save();

                        /*写入查询表*/
                        $alldaycriteria = new CDbCriteria();//时间查询
                        $allchanelcriteria = new CDbCriteria();//渠道查询
                        $alls_chanelcriteria = new CDbCriteria();//支付类型查询
                        $allversioncriteria = new CDbCriteria();
                        $thedaydate=strtotime(date('Y-m-d')) ;
                        $thetimedate=strtotime(date('Y-m-d H:00:00')) ;
                        $alldaycriteria->addCondition('dodate='.$thedaydate);
                        $alldaycriteria->addCondition('channel=0');
                        $alldaycriteria->addCondition('channel_s=0');
                        $alldaycriteria->addCondition('pay_type=0');
                        $alldaycriteria->addCondition('app_version=0');
                        $alldaycriteria->addCondition('app_movie=0');
                        $alldaycriteria->addCondition('app_version=0');
                        $alldayfinddo = $alldaymodel->find($alldaycriteria);
                        if($alldayfinddo){
                            $alldayfinddo->adduser+=1;
                            $alldayfinddo->openuser+=1;
                        }else{
                            $alldaymodel->dodate=$thedaydate;
                            $alldaymodel->adduser=1;
                            $alldaymodel->openuser=1;
                            $alldaymodel->save();
                        }
                        //$allmodel
                        /*写入查询表结束*/
                        if($daymodel and $tjmodel and $hourmodel){
                            $transaction->commit();
                            $art['msg'] ='添加成功';
                            $art['code'] = 0;
                        }else{
                            $transaction->rollBack();
                            $art['msg'] ='添加失败';
                            $art['code'] = 1;
                        }
                    }else{

                        $transaction=$tjmodel->dbConnection->beginTransaction();
                        $userfinddo->usersum+=1 ;
                        $userfinddo->save();
                        $theday = strtotime(date('Y-m-d'));
                        $daycriteria->addCondition('device_id="'.$device_id.'"');
                        $daycriteria->addCondition('userid='.$userfinddo['id']);
                        $daycriteria->addCondition('quedao="'.$quedao.'"');
                        $daycriteria->addCondition('dayupdate='.$theday);

                        $dayuserfinddo = $daymodel->find($daycriteria);

                        if($dayuserfinddo){
                            $dayuserfinddo->upsum+=1 ;
                            $hourmodel->userid=$userfinddo['id'];;
                            $hourmodel->quedao=$quedao;
                            $hourmodel->device_id=$device_id;
                            $hourmodel->version_name=$version_name;
                            $hourmodel->dayupdate=time() ;
                            $hourmodel->save();
                            $dores=$dayuserfinddo->save();
                        }else{
                            $daymodel->userid=$userfinddo['id'];
                            $daymodel->quedao=$quedao;
                            $daymodel->device_id=$device_id;
                            $daymodel->version_name=$version_name;
                            $daymodel->dayupdate=strtotime(date('Y-m-d')) ;
                            $daymodel->upsum=1;
                            $dores=$daymodel->save();
                            $hourmodel->userid=$userfinddo['id'];;
                            $hourmodel->quedao=$quedao;
                            $hourmodel->device_id=$device_id;
                            $hourmodel->version_name=$version_name;
                            $hourmodel->dayupdate=time() ;
                            $hourmodel->save();

                        }
                        if($dores and $userfinddo and $hourmodel){
                            $transaction->commit();
                            $art['msg'] ='更新成功';
                            $art['code'] = 2;
                        }else{
                            $transaction->rollBack();
                            $art['msg'] ='更新失败';
                            $art['code'] = 3;
                        }
                    }
                    if($art['code'] == 1){
                        $tjerrmodel->device_id=$device_id;
                        $tjerrmodel->version_name=$version_name;
                        $tjerrmodel->quedao=$quedao;
                        $tjerrmodel->update=time();
                        $tjerrmodel->usersum='1';
                        $tjerrmodel->save();
                    }
              //  }else{
                  //  $art['msg'] ='缺少必要参数';
                  //  $art['code'] = 119;
               // }
           // }else{
                //$art['msg'] ='参数错误';
               // $art['code'] = 211;
            //}
       // }else{
           // $art['msg'] ='非法访问';
          //  $art['code'] = 110;
        //}
        //return $this->ajaxReturn($art);
    }
    public function  actiontext(){
        $chanel=array(null,1,2,3,4,5,6,7);
        $chanel_sub=array(null,1,2,3,4,5,6,7);;
        $versname=array(null,1,2,3,4,5,6,7);;
        $paytype=array(null,1,2,3,4,5,6,7);;
        $movie=array(null,1,2,3,4,5,6,7);;
        $starttime=time();
        $endtime=date('Y-m-d');
        foreach ($chanel as $keyone=>$chlist){
            foreach ($chanel_sub as $keytwo=>$sublist){
                foreach ($versname as $key3=>$vlist){
                    foreach ($paytype as $key4=>$plist){
                        foreach ($movie as $key5=>$mlist){
                            $sql=<<<EOF
SELECT
    *
    FROM
    TABLE_NAME 
WHERE 
    1
EOF;
                            IF($chlist){
                                $sql .=" AND  chanel= $chlist" ;
                            }
                            if($sublist){
                                $sql .=" AND  chanel_sub= $sublist" ;
                            }
                            if($vlist){
                                $sql .=" AND  versname= $vlist" ;
                            }
                            if($plist){
                                $sql .=" AND  paytype= $plist" ;
                            }
                            if($mlist){
                                $sql .=" AND  movie= $mlist" ;
                            }
                            $sql .=" AND date bettwen  $starttime and $endtime";
                            echo $sql . '<br>';
                        }
                    }
                }
            }
        }
    }

    public function actionlistall(){

    }



    public function actiontesttwo(){

        $json = Yii::app()->curl->get('http://tj.pingoula.net/?r=api/default/channelnum&version_name=1.0.1&channel=聚目测试1-3D朋友测试new');
        echo $json;
    }
    public  function actionapppost(){
        $time=time();
        $openkey=md5('yr2017'.$time);

        $json = Yii::app()->curl->get('http://local.mantj.com/?r=api/default/usertj&openkey='.$openkey.'&theday='.$time.'&device_id=1178881111999792cs&version_name=1.0.1&channel=墨禾测试9-晚娘测试下');
        //$result = $this->httppost($dourl);5293-小姨子-固定位
        // $result = json_decode($json,true);
        echo $json;
    }
}