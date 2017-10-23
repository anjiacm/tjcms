<?php
//date_default_timezone_set('PRC');
class WuTjuserallController extends Backend
{

   //初始化函数
    public $_hoursall;
    public $_newupsum;
    public $_dayupsum;
    public $_ztupsum;
    public $_datelistall;
    public $_bbqdlist;
    public $_kzqdlist;
    public $_qdlist;
    public $_qdlist_s;
    public $_qdlist_t;
    public $_timetype;
    public $_lmid;
    public $_lmidall;
    public $_mrbb;
    public $weekArr=array("周日","周一","周二","周三","周四","周五","周六");
	public function init(){
		parent::init();
		$this->_hoursall=array(
		    '00:00','01:00','02:00','03:00','04:00','05:00','06:00','07:00','08:00','09:00',
            '10:00','11:00','12:00','13:00','14:00','15:00','16:00','17:00','18:00','19:00',
            '20:00','21:00','22:00','23:00',
        );
        $this->_qdlist=WuChanelname::listall();

        $this->_qdlist_s=WuChanelSmall::listalls();
        $lmlistall = WuLmlist::lmlistall();
        $lmfind=WuLmlist::model()->findByPk($_SESSION['adminlm']);
        $this->_lmid=empty($lmfind)?'':$lmfind['lm'].'|'.$lmlistall[$_SESSION['adminlm']];
        $this->_mrbb = '';
      //  exit();

    }

    public function actionindex(){
        /*基础配备 获取 渠道 版本 支付渠道等内容*/
        $index=1;
        $this->_bbqdlist=$this->listqdbb();
        $page = empty($_REQUEST['page'])?'6':intval($_REQUEST['page']);

        $timetype = empty($_REQUEST['timetype'])?'':intval($_REQUEST['timetype']);
        if(isset($_POST['ztqs'])){
            $inputfrom =$_POST['ztqs'];
            $starttime  = strtotime(date("Y-m-d")) ;
            $endtime= strtotime(date("Y-m-d",strtotime("+1 day")));
            $quedao=empty($_POST['ztqs']['qdone'])?'':intval($_POST['ztqs']['qdone']);
            $zquedao=empty($_POST['ztqs']['qdtwo'])?'':intval($_POST['ztqs']['qdtwo']);
            $bb=empty($_POST['ztqs']['bb'])?$this->_mrbb:intval($_POST['ztqs']['bb']);
            $bb=($bb==999)?'':$bb;
            $devnum=empty($_POST['ztqs']['devnum'])?'':intval($_POST['ztqs']['devnum']);

            $pay=empty($_POST['ztqs']['pay'])?'':intval($_POST['ztqs']['pay']);
            $timeall=empty($_POST['ztqs']['timeall'])?'1':intval($_POST['ztqs']['timeall']);
            $this->_timetype =$timeall;
            $this->_dayupsum=$this->newindextj($starttime,$endtime,$timetype,$quedao,$zquedao,$bb,$pay,$page,$devnum);

        }else{
            $inputfrom =array();
            $this->_timetype =1;
            $starttime  = strtotime(date("Y-m-d")) ;
            $endtime= strtotime(date("Y-m-d",strtotime("+1 day")));
            $this->_dayupsum=$this->newindextj($starttime,$endtime,$timetype,'','',$this->_mrbb,'',$page,'');

        }





//        $alltj=array();
//        $alltj['dayhours'] = $tulist['today'];
//        $alltj['todayhours'] = $tulist['zuodaiday'];
//        $alltj['sevenhours'] =$tulist['day_week'];
//        $alltj['yuehours'] =$tulist['day_month'];
        $this->render('index', array (
            'res'=>$index,
            'pages'=>$page,
            'starttime'=>$starttime,
            //'alltj'=>$alltj,
            'endtime'=>$endtime ,
            'searchfrom'=>$inputfrom,
            'timetype'=>$timetype
        ));

    }


    /*列出实时的统计*/
    private  function  newindextj($startday,$endday,$type,$qd_b='',$qd_s='',$bb='',$paytype='',$page,$dev)
    {
        /*今日*/
        $timetype=$type;//空 日期  1 小时
        $today = intval($startday);
        $theday = intval($endday);
        $listsize = ($theday - $today) / 3600 / 24;
        $channel = $qd_b;
        $channel_s = $qd_s;
        $app_version = $bb;
        $dem_num = $dev;
        $pay_type =empty($paytype)?'0':intval($paytype);
        $pages =empty($page)?'6':intval($page);

        $showdate = array();
        /*统计昨天*/
        $zuodate=  strtotime(date('Y-m-d',strtotime('-1 days')));
        $zuosql ='SELECT
                    DATE_FORMAT(FROM_UNIXTIME(dodate),"%Y-%m-%d %H:00") as time,
                    sum(newadduser) as adduser,
                    sum(openuser) as openuser,
                    sum(payuser) as payuser,
                    sum(newpayuser) as newpayuser,
                    sum(paymoney) as paymoney
                FROM
                    y_wu_tjselectall
                WHERE 
                    dodate 
                BETWEEN '.$zuodate.' and '.$theday.' 
                AND
                  (channel="'.$channel.'"  or "'.$channel.'"="") and 
                   (channel_s="'.$channel_s.'"  or "'.$channel_s.'"="") and 
                    (app_version="'.$app_version.'"  or "'.$app_version.'"="") and
                     (dem_num="'.$dem_num.'"  or "'.$dem_num.'"="") and 
                     (pay_type="'.$pay_type.'"  or "'.$pay_type.'"="")
                     group by
                time WITH ROLLUP
                
                ';
        $zuoall=Yii::app()->db->createCommand($zuosql)->queryAll();
        foreach ($zuoall as &$zlist){
            $zdayalls['zadduser'][strtotime($zlist['time'])] = $zlist['adduser'];
            $zdayalls['zopenuser'][strtotime($zlist['time'])] = $zlist['openuser'];
            $zdayalls['zpayuser'][strtotime($zlist['time'])] = $zlist['payuser'];
            $zdayalls['znewpayuser'][strtotime($zlist['time'])] = $zlist['newpayuser'];
            $zdayalls['zpaymoney'][strtotime($zlist['time'])] = intval($zlist['paymoney']);
        }
        /*统计7天前*/
        $sevendate=  strtotime(date('Y-m-d',strtotime('-7 days')));
        $sevendate2=  strtotime(date('Y-m-d',strtotime('-6 days')));
        $sevensql ='SELECT
                    DATE_FORMAT(FROM_UNIXTIME(dodate),"%Y-%m-%d %H:00") as time,
                    sum(newadduser) as adduser,
                    sum(openuser) as openuser,
                    sum(payuser) as payuser,
                    sum(newpayuser) as newpayuser,
                    sum(paymoney) as paymoney
                FROM
                    y_wu_tjselectall
                WHERE 
                    dodate 
                BETWEEN '.$sevendate.' and '.$sevendate2.' 
                AND
                  (channel="'.$channel.'"  or "'.$channel.'"="") and 
                   (channel_s="'.$channel_s.'"  or "'.$channel_s.'"="") and 
                    (app_version="'.$app_version.'"  or "'.$app_version.'"="") and 
                     (dem_num="'.$dem_num.'"  or "'.$dem_num.'"="") and 
                     (pay_type="'.$pay_type.'"  or "'.$pay_type.'"="")
                     group by
                time WITH ROLLUP
                
                ';
        $sevall=Yii::app()->db->createCommand($sevensql)->queryAll();
        foreach ($sevall as &$slist){
            $sdayalls['sadduser'][strtotime($slist['time'])] = $slist['adduser'];
            $sdayalls['sopenuser'][strtotime($slist['time'])] = $slist['openuser'];
            $sdayalls['spayuser'][strtotime($slist['time'])] = $slist['payuser'];
            $sdayalls['snewpayuser'][strtotime($slist['time'])] = $slist['newpayuser'];
            $sdayalls['spaymoney'][strtotime($slist['time'])] = intval($slist['paymoney']);
        }
        /*统计30天前*/
        $yuedate=  strtotime(date('Y-m-d',strtotime('-30 days')));
        $yuedate2=  strtotime(date('Y-m-d',strtotime('-29 days')));
        $mothsql ='SELECT
                    DATE_FORMAT(FROM_UNIXTIME(dodate),"%Y-%m-%d %H:00") as time,
                    sum(newadduser) as adduser,
                    sum(openuser) as openuser,
                    sum(payuser) as payuser,
                    sum(newpayuser) as newpayuser,
                    sum(paymoney) as paymoney
                FROM
                    y_wu_tjselectall
                WHERE 
                    dodate 
                BETWEEN '.$yuedate.' and '.$yuedate2.' 
                AND
                  (channel="'.$channel.'"  or "'.$channel.'"="") and 
                   (channel_s="'.$channel_s.'"  or "'.$channel_s.'"="") and 
                    (app_version="'.$app_version.'"  or "'.$app_version.'"="") and 
                     (dem_num="'.$dem_num.'"  or "'.$dem_num.'"="") and 
                     (pay_type="'.$pay_type.'"  or "'.$pay_type.'"="")
                     group by
                time WITH ROLLUP
                
                ';
        $moall=Yii::app()->db->createCommand($mothsql)->queryAll();
        foreach ($moall as &$mlist){
            $mdayalls['madduser'][strtotime($mlist['time'])] = $mlist['adduser'];
            $mdayalls['mopenuser'][strtotime($mlist['time'])] = $mlist['openuser'];
            $mdayalls['mpayuser'][strtotime($mlist['time'])] = $mlist['payuser'];
            $mdayalls['mnewpayuser'][strtotime($mlist['time'])] = $mlist['newpayuser'];
            $mdayalls['mpaymoney'][strtotime($mlist['time'])] = intval($mlist['paymoney']);
        }

        /*统计今日*/
            $sql ='SELECT
                    DATE_FORMAT(FROM_UNIXTIME(dodate),"%Y-%m-%d %H:00") as time,
                    sum(newadduser) as adduser,
                    sum(openuser) as openuser,
                    sum(payuser) as payuser,
                    sum(newpayuser) as newpayuser,
                    sum(paymoney) as paymoney
                FROM
                    y_wu_tjselectday
                WHERE 
                    dodate 
                BETWEEN '.$today.' and '.$theday.' 
                AND
                  (channel="'.$channel.'"  or "'.$channel.'"="") and 
                   (channel_s="'.$channel_s.'"  or "'.$channel_s.'"="") and 
                    (app_version="'.$app_version.'"  or "'.$app_version.'"="") and 
                     (dem_num="'.$dem_num.'"  or "'.$dem_num.'"="") and 
                     (pay_type="'.$pay_type.'"  or "'.$pay_type.'"="")
                     group by
                time WITH ROLLUP
                
                ';
            $ztqsall=Yii::app()->db->createCommand($sql)->queryAll();
            foreach ($ztqsall as &$ulist){
                $dayalls['adduser'][strtotime($ulist['time'])] = $ulist['adduser'];
                $dayalls['openuser'][strtotime($ulist['time'])] = $ulist['openuser'];
                $dayalls['payuser'][strtotime($ulist['time'])] = $ulist['payuser'];
                $dayalls['newpayuser'][strtotime($ulist['time'])] = $ulist['newpayuser'];
                $dayalls['paymoney'][strtotime($ulist['time'])] = intval($ulist['paymoney']);
            }

            for ($i=0;$i<$listsize*24;$i++){
                $lnum= $today+$i*60*60;
                $zlum= $zuodate+$i*60*60;
                $slum= $sevendate+$i*60*60;
                $mlum= $yuedate+$i*60*60;
                $listalls['tablelist'][$i]['dateall']=date('m-d H:00',$today+$i*60*60);
                if($this->_timetype ==1){
                    $listalls['tablelist'][$i]['adduser']=empty( $dayalls['adduser'][$lnum])?'0': $dayalls['adduser'][$lnum];
                    $listalls['tablelist'][$i]['openuser']=empty( $dayalls['openuser'][$lnum])?'0': $dayalls['openuser'][$lnum];
                    $listalls['tablelist'][$i]['payuser']=empty( $dayalls['payuser'][$lnum])?'0': $dayalls['payuser'][$lnum];
                    $listalls['tablelist'][$i]['newpayuser']=empty( $dayalls['newpayuser'][$lnum])?'0': $dayalls['newpayuser'][$lnum];
                    $listalls['tablelist'][$i]['paymoney']=empty( $dayalls['paymoney'][$lnum])?'0': $dayalls['paymoney'][$lnum];
                    $listalls['tablelist'][$i]['fflv']=empty($dayalls['adduser'][$lnum])?'0':sprintf("%.4f",$dayalls['newpayuser'][$lnum]/$dayalls['adduser'][$lnum])*100;
                    $listalls['tablelist'][$i]['arpu']=empty($dayalls['adduser'][$lnum])?'0':sprintf("%.2f",$dayalls['paymoney'][$lnum]/$dayalls['adduser'][$lnum]);
                }else{

                    $listalls['tablelist'][$i]['adduser']=empty( $zdayalls['zadduser'][$zlum])?'0': $zdayalls['zadduser'][$zlum];
                    $listalls['tablelist'][$i]['openuser']=empty( $zdayalls['zopenuser'][$zlum])?'0': $zdayalls['zopenuser'][$zlum];
                    $listalls['tablelist'][$i]['payuser']=empty( $zdayalls['zpayuser'][$zlum])?'0': $zdayalls['zpayuser'][$zlum];
                    $listalls['tablelist'][$i]['newpayuser']=empty( $zdayalls['znewpayuser'][$zlum])?'0': $zdayalls['znewpayuser'][$zlum];
                    $listalls['tablelist'][$i]['paymoney']=empty( $zdayalls['zpaymoney'][$zlum])?'0': $zdayalls['zpaymoney'][$zlum];
                    $listalls['tablelist'][$i]['fflv']=empty($zdayalls['zadduser'][$zlum])?'0':sprintf("%.4f",$zdayalls['znewpayuser'][$zlum]/$zdayalls['zadduser'][$zlum])*100;
                    $listalls['tablelist'][$i]['arpu']=empty($dayalls['adduser'][$lnum])?'0':sprintf("%.2f",$dayalls['paymoney'][$lnum]/$dayalls['adduser'][$lnum]);
                }

                /*昨天数据*/
                $listalls['tablelist'][$i]['zarpu']=empty($zdayalls['zadduser'][$zlum])?'0':sprintf("%.2f",$zdayalls['zpaymoney'][$zlum]/$zdayalls['zadduser'][$zlum]);

                /*7天前数据*/
                $listalls['tablelist'][$i]['sarpu']=empty($sdayalls['sadduser'][$slum])?'0':sprintf("%.2f",$sdayalls['spaymoney'][$slum]/$sdayalls['sadduser'][$slum]);

                /*30天前数据*/
                $listalls['tablelist'][$i]['marpu']=empty($mdayalls['madduser'][$mlum])?'0':sprintf("%.2f",$mdayalls['mpaymoney'][$mlum]/$mdayalls['madduser'][$mlum]);





                    if($pages ==1){
                        $listalls['zxtu']['theday'][$i]=empty( $dayalls['openuser'][$lnum])?'0':$dayalls['openuser'][$lnum];
                        $listalls['zxtu']['today'][$i]=empty( $zdayalls['zopenuser'][$zlum])?'0':$zdayalls['zopenuser'][$zlum];
                        $listalls['zxtu']['sevenday'][$i]=empty( $sdayalls['sopenuser'][$slum])?'0':$sdayalls['sopenuser'][$slum];
                        $listalls['zxtu']['month'][$i]=empty( $mdayalls['mopenuser'][$mlum])?'0':$mdayalls['mopenuser'][$mlum];
                    }else if($pages ==2){
                        $listalls['zxtu']['theday'][$i]=empty( $dayalls['payuser'][$lnum])?'0':$dayalls['payuser'][$lnum];
                        $listalls['zxtu']['today'][$i]=empty( $zdayalls['zpayuser'][$zlum])?'0':$zdayalls['zpayuser'][$zlum];
                        $listalls['zxtu']['sevenday'][$i]=empty( $sdayalls['spayuser'][$slum])?'0':$sdayalls['spayuser'][$slum];
                        $listalls['zxtu']['month'][$i]=empty( $mdayalls['mpayuser'][$mlum])?'0':$mdayalls['mpayuser'][$mlum];
                    }else if($pages ==3){
                        $listalls['zxtu']['theday'][$i]=empty( $dayalls['newpayuser'][$lnum])?'0':$dayalls['newpayuser'][$lnum];
                        $listalls['zxtu']['today'][$i]=empty( $zdayalls['znewpayuser'][$zlum])?'0':$zdayalls['znewpayuser'][$zlum];
                        $listalls['zxtu']['sevenday'][$i]=empty( $sdayalls['snewpayuser'][$slum])?'0':$sdayalls['snewpayuser'][$slum];
                        $listalls['zxtu']['month'][$i]=empty( $mdayalls['mnewpayuser'][$mlum])?'0':$mdayalls['mnewpayuser'][$mlum];
                    }else if($pages ==4){
                        $listalls['zxtu']['theday'][$i]=empty( $dayalls['paymoney'][$lnum])?'0':$dayalls['paymoney'][$lnum];
                        $listalls['zxtu']['today'][$i]=empty( $zdayalls['zpaymoney'][$zlum])?'0':$zdayalls['zpaymoney'][$zlum];
                        $listalls['zxtu']['sevenday'][$i]=empty( $sdayalls['spaymoney'][$slum])?'0':$sdayalls['spaymoney'][$slum];
                        $listalls['zxtu']['month'][$i]=empty( $mdayalls['mpaymoney'][$mlum])?'0':$mdayalls['mpaymoney'][$mlum];
                    }elseif($pages == 5){
                        $listalls['zxtu']['theday'][$i]=empty($dayalls['adduser'][$lnum])?'0':sprintf("%.4f",$dayalls['newpayuser'][$lnum]/$dayalls['adduser'][$lnum])*100;
                        $listalls['zxtu']['today'][$i]=empty($zdayalls['zadduser'][$zlum])?'0':sprintf("%.4f",$zdayalls['znewpayuser'][$zlum]/$zdayalls['zadduser'][$zlum])*100;
                        $listalls['zxtu']['sevenday'][$i]=empty($sdayalls['sadduser'][$slum])?'0':sprintf("%.4f",$sdayalls['snewpayuser'][$slum]/$sdayalls['sadduser'][$slum])*100;
                        $listalls['zxtu']['month'][$i]=empty($mdayalls['madduser'][$mlum])?'0':sprintf("%.4f",$mdayalls['mnewpayuser'][$mlum]/$mdayalls['madduser'][$mlum])*100;
                    }elseif($pages == 6){
                        $listalls['zxtu']['theday'][$i]=empty($dayalls['adduser'][$lnum])?'0':sprintf("%.2f",$dayalls['paymoney'][$lnum]/$dayalls['adduser'][$lnum]);
                        $listalls['zxtu']['today'][$i]=empty($zdayalls['zadduser'][$zlum])?'0':sprintf("%.2f",$zdayalls['zpaymoney'][$zlum]/$zdayalls['zadduser'][$zlum]);
                        $listalls['zxtu']['sevenday'][$i]=empty($sdayalls['sadduser'][$slum])?'0':sprintf("%.2f",$sdayalls['spaymoney'][$slum]/$sdayalls['sadduser'][$slum]);
                        $listalls['zxtu']['month'][$i]=empty($mdayalls['madduser'][$mlum])?'0':sprintf("%.2f",$mdayalls['mpaymoney'][$mlum]/$mdayalls['madduser'][$mlum]);
                    }else{
                        $listalls['zxtu']['theday'][$i]=empty( $dayalls['adduser'][$lnum])?'0':$dayalls['adduser'][$lnum];
                        $listalls['zxtu']['today'][$i]=empty( $zdayalls['zadduser'][$zlum])?'0':$zdayalls['zadduser'][$zlum];
                        $listalls['zxtu']['sevenday'][$i]=empty( $sdayalls['sadduser'][$slum])?'0':$sdayalls['sadduser'][$slum];
                        $listalls['zxtu']['month'][$i]=empty( $mdayalls['madduser'][$mlum])?'0':$mdayalls['madduser'][$mlum];
                    }







            }

        if($this->_timetype ==1) {
            $listalls['adduser'] = $dayalls['adduser'][0];
            $listalls['openuser'] = $dayalls['openuser'][0];
            $listalls['payuser'] = $dayalls['payuser'][0];
            $listalls['newpayuser'] = $dayalls['newpayuser'][0];
            $listalls['paymoney'] = $dayalls['paymoney'][0];
        }else{
            $listalls['adduser'] = $zdayalls['zadduser'][0];
            $listalls['openuser'] = $zdayalls['zopenuser'][0];
            $listalls['payuser'] = $zdayalls['zpayuser'][0];
            $listalls['newpayuser'] = $zdayalls['znewpayuser'][0];
            $listalls['paymoney'] = $zdayalls['zpaymoney'][0];
        }
        return $listalls;

    }















  /*&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&*/
    /*列出版本信息   渠道信息 */
    private  function  listqdbb(){
        $listqdbb=array();
        $qdsql='SELECT id as id,chanle AS qdlist FROM `y_wu_chanel_one` ;';
        $qdlist=Yii::app()->db->createCommand($qdsql)->queryAll();
        $quedao=array();
        $quedao_two=array();
        $bb=array();
        $str2='测试';
        $str3='%';
        foreach ($qdlist as &$list){
            if(strpos($list['qdlist'],$str2) === false and strpos($list['qdlist'],$str3) === false){     //使用绝对等于
                $quedao[$list['id']]=$list['qdlist'];
            }
        }
        $qdtwosql='SELECT id as id,chanle_web AS twolist FROM `y_wu_chanel_two` ;';
        $qdtwolist=Yii::app()->db->createCommand($qdtwosql)->queryAll();
        foreach ($qdtwolist as &$tlist){
            if(strpos($tlist['twolist'],$str2) === false and strpos($tlist['twolist'],$str3) === false){     //使用绝对等于
                $quedao_two[$tlist['id']]=$tlist['twolist'];
            }
        }
        $qdthreesql='SELECT id as id,chanle_movie AS threelist FROM `y_wu_chanel_three` ;';
        $qdthreelist=Yii::app()->db->createCommand($qdthreesql)->queryAll();
        foreach ($qdthreelist as &$slist){
            if(strpos($slist['threelist'],$str2) === false and strpos($slist['threelist'],$str3) === false){     //使用绝对等于
                $quedao_three[$slist['id']]=$slist['threelist'];
            }
        }
        $bbsql='SELECT id as id,version_name as banben FROM `y_wu_version`';
        $bblist=Yii::app()->db->createCommand($bbsql)->queryAll();
        foreach ($bblist as &$blist){

                $bb[$blist['id']]=$blist['banben'];

        }
        $listqdbb['qdone']=$quedao;
        $listqdbb['qdtwo']=$quedao_two;
        $listqdbb['qdthree']=$quedao_three;
        $listqdbb['bb']=$bb;

        return $listqdbb;
    }

    public function actionztqs(){
        /*基础配备 获取 渠道 版本 支付渠道等内容*/
        $index=2;
        $this->_bbqdlist=$this->listqdbb();
        $page = intval(Yii::app()->request->getParam('page'));

        $timetype = empty($_REQUEST['timetype'])?'':intval($_REQUEST['timetype']);
        if(isset($_POST['ztqs'])){
            $inputfrom =$_POST['ztqs'];

            $starttime  = empty($_POST['ztqs']['starttime'])?strtotime(date('Y-m-d',strtotime('-7 days'))):strtotime($_POST['ztqs']['starttime']);
            $endtime= empty($_POST['ztqs']['endtime'])?strtotime('today'):strtotime($_POST['ztqs']['endtime']);
            $quedao=empty($_POST['ztqs']['qdone'])?'':intval($_POST['ztqs']['qdone']);
            $zquedao=empty($_POST['ztqs']['qdtwo'])?'':intval($_POST['ztqs']['qdtwo']);
            $bb=empty($_POST['ztqs']['bb'])?$this->_mrbb:intval($_POST['ztqs']['bb']);
            $bb=($bb==999)?'':$bb;
            $devnum=empty($_POST['ztqs']['devnum'])?'':intval($_POST['ztqs']['devnum']);
            $pay=empty($_POST['ztqs']['pay'])?'':intval($_POST['ztqs']['pay']);
            $this->_dayupsum=$this->newztqstj($starttime,$endtime,$timetype,$quedao,$zquedao,$bb,$pay,$devnum);

        }else{
            $inputfrom =array();
            $starttime  = strtotime(date('Y-m-d',strtotime('-7 days')));
            $endtime= strtotime('today');
            $this->_dayupsum=$this->newztqstj($starttime,$endtime,$timetype,'','',$this->_mrbb,'','');

        }

        $this->render('ztqs', array (
            'res'=>$index,
            'pages'=>$page,
            'starttime'=>$starttime,
            'endtime'=>$endtime ,
            'searchfrom'=>$inputfrom,
            'timetype'=>$timetype
        ));

    }
    /*列出整体趋势的统计*/
    private  function  newztqstj($startday,$endday,$type,$qd_b='',$qd_s='',$bb='',$paytype='',$dev='')
    {
        /*今日*/
        $timetype=$type;//空 日期  1 小时
        $today = intval($startday);
        $theday = intval($endday);
        $listsize = ($theday - $today) / 3600 / 24;
        $channel = $qd_b;
        $channel_s = $qd_s;
        $app_version = $bb;
        $dem_num = $dev;
        $pay_type =empty($paytype)?'0':intval($paytype);
        $showdate = array();

        if($timetype ==1){
            for($i=0;$i<$listsize*24;$i++)
            {
                $showdate[]=date('m-d H:00',$today+$i*60*60);
            }
            $sql ='SELECT
                    DATE_FORMAT(FROM_UNIXTIME(dodate),"%Y-%m-%d %H:00") as time,
                    sum(newadduser) as adduser,
                    sum(openuser) as openuser,
                    sum(payuser) as payuser,
                    sum(newpayuser) as newpayuser,
                    sum(paymoney) as paymoney
                FROM
                    y_wu_tjselectall
                WHERE 
                    dodate 
                BETWEEN '.$today.' and '.$theday.'-1 
                AND
                  (channel="'.$channel.'"  or "'.$channel.'"="") and 
                   (channel_s="'.$channel_s.'"  or "'.$channel_s.'"="") and 
                    (app_version="'.$app_version.'"  or "'.$app_version.'"="") and 
                    (dem_num="'.$dem_num.'"  or "'.$dem_num.'"="") and 
                     (pay_type="'.$pay_type.'"  or "'.$pay_type.'"="")
                     group by
                time WITH ROLLUP
                
                ';
            $ztqsall=Yii::app()->db->createCommand($sql)->queryAll();
            foreach ($ztqsall as &$ulist){
                $dayalls['adduser'][strtotime($ulist['time'])] = $ulist['adduser'];
                $dayalls['openuser'][strtotime($ulist['time'])] = $ulist['openuser'];
                $dayalls['payuser'][strtotime($ulist['time'])] = $ulist['payuser'];
                $dayalls['newpayuser'][strtotime($ulist['time'])] = $ulist['newpayuser'];
                $dayalls['paymoney'][strtotime($ulist['time'])] = intval($ulist['paymoney']);
            }
            for ($i=0;$i<$listsize*24;$i++){
                $lnum= $today+$i*60*60;
                //$theweek=date('w',$today+$i*60*60);

                $listalls['tablelist'][$i]['dateall']=date('m-d H:00',$today+$i*60*60);
                $listalls['tablelist'][$i]['adduser']=empty( $dayalls['adduser'][$lnum])?'0': $dayalls['adduser'][$lnum];
                $listalls['tablelist'][$i]['openuser']=empty( $dayalls['openuser'][$lnum])?'0': $dayalls['openuser'][$lnum];
                $listalls['tablelist'][$i]['payuser']=empty( $dayalls['payuser'][$lnum])?'0': $dayalls['payuser'][$lnum];
                $listalls['tablelist'][$i]['newpayuser']=empty( $dayalls['newpayuser'][$lnum])?'0': $dayalls['newpayuser'][$lnum];
                $listalls['tablelist'][$i]['paymoney']=empty( $dayalls['paymoney'][$lnum])?'0': $dayalls['paymoney'][$lnum];
                $listalls['tablelist'][$i]['fflv']=empty($dayalls['adduser'][$lnum])?'0':sprintf("%.4f",$dayalls['newpayuser'][$lnum]/$dayalls['adduser'][$lnum])*100;
                $listalls['tablelist'][$i]['arpu']=empty($dayalls['adduser'][$lnum])?'0':sprintf("%.2f",$dayalls['paymoney'][$lnum]/$dayalls['adduser'][$lnum]);
                $listalls['zxianadduser'][$i]=empty( $dayalls['adduser'][$lnum])?'0':$dayalls['adduser'][$lnum];
                $listalls['zxianopenuser'][$i]=empty( $dayalls['openuser'][$lnum])?'0':$dayalls['openuser'][$lnum];
                $listalls['zxianpayuser'][$i]=empty( $dayalls['payuser'][$lnum])?'0':$dayalls['payuser'][$lnum];
                $listalls['zxiannewpayuser'][$i]=empty( $dayalls['newpayuser'][$lnum])?'0':$dayalls['newpayuser'][$lnum];
                $listalls['zxianpaymoney'][$i]=empty( $dayalls['paymoney'][$lnum])?'0':$dayalls['paymoney'][$lnum];
                $listalls['zxianfflv'][$i]=empty($dayalls['adduser'][$lnum])?'0':sprintf("%.4f",$dayalls['newpayuser'][$lnum]/$dayalls['adduser'][$lnum])*100;
                $listalls['zxianarpu'][$i]=empty($dayalls['adduser'][$lnum])?'0':sprintf("%.2f",$dayalls['paymoney'][$lnum]/$dayalls['adduser'][$lnum]);
            }
        }else{
            for ($i = 0; $i < $listsize; $i++) {
                $showdate[]=date('m-d',$today+$i*24*60*60);
            }
            $sql ='SELECT
                    DATE_FORMAT(FROM_UNIXTIME(dodate),"%Y-%m-%d") as time,
                    sum(newadduser) as adduser,
                    sum(openuser) as openuser,
                    sum(payuser) as payuser,
                    sum(newpayuser) as newpayuser,
                    sum(paymoney) as paymoney
                FROM
                    y_wu_tjselectall
                WHERE 
                    dodate 
                BETWEEN '.$today.' and '.$theday.'-1 
                AND
                  (channel="'.$channel.'"  or "'.$channel.'"="") and 
                   (channel_s="'.$channel_s.'"  or "'.$channel_s.'"="") and 
                    (app_version="'.$app_version.'"  or "'.$app_version.'"="") and 
                     (dem_num="'.$dem_num.'"  or "'.$dem_num.'"="") and 
                     (pay_type="'.$pay_type.'"  or "'.$pay_type.'"="")
                     group by
                time WITH ROLLUP
                
                ';
            $ztqsall=Yii::app()->db->createCommand($sql)->queryAll();
            foreach ($ztqsall as &$ulist){
                $dayalls['adduser'][strtotime($ulist['time'])] = $ulist['adduser'];
                $dayalls['openuser'][strtotime($ulist['time'])] = $ulist['openuser'];
                $dayalls['payuser'][strtotime($ulist['time'])] = $ulist['payuser'];
                $dayalls['newpayuser'][strtotime($ulist['time'])] = $ulist['newpayuser'];
                $dayalls['paymoney'][strtotime($ulist['time'])] = intval($ulist['paymoney']);
            }
            for ($i=0;$i<$listsize;$i++){
                $lnum= $today+$i*24*60*60;
                $theweek=date('w',$today+$i*24*60*60);

                $listalls['tablelist'][$i]['dateall']=date('m-d',$today+$i*24*60*60) .'('.$this->weekArr[$theweek].')';
                $listalls['tablelist'][$i]['adduser']=empty( $dayalls['adduser'][$lnum])?'0': $dayalls['adduser'][$lnum];
                $listalls['tablelist'][$i]['openuser']=empty( $dayalls['openuser'][$lnum])?'0': $dayalls['openuser'][$lnum];
                $listalls['tablelist'][$i]['payuser']=empty( $dayalls['payuser'][$lnum])?'0': $dayalls['payuser'][$lnum];
                $listalls['tablelist'][$i]['newpayuser']=empty( $dayalls['newpayuser'][$lnum])?'0': $dayalls['newpayuser'][$lnum];
                $listalls['tablelist'][$i]['paymoney']=empty( $dayalls['paymoney'][$lnum])?'0': $dayalls['paymoney'][$lnum];
                $listalls['tablelist'][$i]['fflv']=empty($dayalls['adduser'][$lnum])?'0':sprintf("%.4f",$dayalls['newpayuser'][$lnum]/$dayalls['adduser'][$lnum])*100;
                $listalls['tablelist'][$i]['arpu']=empty($dayalls['adduser'][$lnum])?'0':sprintf("%.2f",$dayalls['paymoney'][$lnum]/$dayalls['adduser'][$lnum]);
                $listalls['zxianadduser'][$i]=empty( $dayalls['adduser'][$lnum])?'0':$dayalls['adduser'][$lnum];
                $listalls['zxianopenuser'][$i]=empty( $dayalls['openuser'][$lnum])?'0':$dayalls['openuser'][$lnum];
                $listalls['zxianpayuser'][$i]=empty( $dayalls['payuser'][$lnum])?'0':$dayalls['payuser'][$lnum];
                $listalls['zxiannewpayuser'][$i]=empty( $dayalls['newpayuser'][$lnum])?'0':$dayalls['newpayuser'][$lnum];
                $listalls['zxianpaymoney'][$i]=empty( $dayalls['paymoney'][$lnum])?'0':$dayalls['paymoney'][$lnum];
                $listalls['zxianfflv'][$i]=empty($dayalls['adduser'][$lnum])?'0':sprintf("%.4f",$dayalls['newpayuser'][$lnum]/$dayalls['adduser'][$lnum])*100;
                $listalls['zxianarpu'][$i]=empty($dayalls['adduser'][$lnum])?'0':sprintf("%.2f",$dayalls['paymoney'][$lnum]/$dayalls['adduser'][$lnum]);

            }
        }
        $this->_datelistall = $showdate;
        $listalls['adduser']= $dayalls['adduser'][0];
        $listalls['openuser']= $dayalls['openuser'][0];
        $listalls['payuser']= $dayalls['payuser'][0];
        $listalls['newpayuser']= $dayalls['newpayuser'][0];
        $listalls['paymoney']= $dayalls['paymoney'][0];

        return $listalls;

    }




/*%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%渠道分析区域%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%*/


    public function actionqudao(){
        /*基础配备 获取 渠道 版本 支付渠道等内容*/
        $index=11;
        $this->_bbqdlist=$this->listqdbb();
        $page = intval(Yii::app()->request->getParam('page'));

        $timetype = empty($_REQUEST['timetype'])?'':intval($_REQUEST['timetype']);
        if(isset($_POST['ztqs'])){
            $inputfrom =$_POST['ztqs'];

            $starttime  = empty($_POST['ztqs']['starttime'])?strtotime(date('Y-m-d',strtotime('-7 days'))):strtotime($_POST['ztqs']['starttime']);
            $endtime= empty($_POST['ztqs']['endtime'])?strtotime('today'):strtotime($_POST['ztqs']['endtime']);
            $quedao=empty($_POST['ztqs']['qdone'])?'':intval($_POST['ztqs']['qdone']);
            $zquedao=empty($_POST['ztqs']['qdtwo'])?'':intval($_POST['ztqs']['qdtwo']);
            $bb=empty($_POST['ztqs']['bb'])?$this->_mrbb:intval($_POST['ztqs']['bb']);
            $bb=($bb==999)?'':$bb;
            $devnum=empty($_POST['ztqs']['devnum'])?'':intval($_POST['ztqs']['devnum']);
            $pay=empty($_POST['ztqs']['pay'])?'':intval($_POST['ztqs']['pay']);

            $timeall=empty($_POST['ztqs']['timeall'])?'0':intval($_POST['ztqs']['timeall']);
            $this->_timetype = $timeall;


            if($timeall==1){
                $starttime=  strtotime(date('Y-m-d',strtotime('-1 days')));
                $endtime=  strtotime(date('Y-m-d'));
                $this->_dayupsum=$this->newqdtj($starttime,$endtime,$timetype,$bb,$pay,$page,$devnum);
            }elseif($timeall==2){
                $sevendate=  strtotime(date('Y-m-d',strtotime('-7 days')));
                $sevendate2=  strtotime(date('Y-m-d',strtotime('-6 days')));
                $this->_dayupsum=$this->newqdtj($sevendate,$sevendate2,$timetype,$bb,$pay,$page,$devnum);
            }elseif($timeall==3){
                $starttime=  strtotime(date('Y-m-d'));
                $endtime=  strtotime(date('Y-m-d',strtotime('+1 days')));
                $this->_dayupsum=$this->newqdtj($starttime,$endtime,$timetype,$bb,$pay,$page,$devnum);
            }else{
                if($starttime == strtotime(date('Y-m-d'))){
                    $this->_timetype = 3;
                    $starttime=  strtotime(date('Y-m-d'));
                    $endtime=  strtotime(date('Y-m-d',strtotime('+1 days')));
                    $this->_dayupsum=$this->newqdtj($starttime,$endtime,$timetype,$bb,$pay,$page,$devnum);
                }else{
                    $this->_dayupsum=$this->newqdtj($starttime,$endtime,$timetype,$bb,$pay,$page,$devnum);
                }

            }
        }else{
            $inputfrom =array();
            $this->_timetype = 0;
            $starttime  = strtotime(date('Y-m-d',strtotime('-7 days')));
            $endtime= strtotime('today');
            $this->_dayupsum=$this->newqdtj($starttime,$endtime,$timetype,'',$this->_mrbb,$page,'');

        }

        $this->render('qudao', array (
            'res'=>$index,
            'pages'=>$page,
            'starttime'=>$starttime,
            'endtime'=>$endtime ,
            'searchfrom'=>$inputfrom,
            'timetype'=>$timetype
        ));

    }
    /*列出整体趋势的统计*/
    private  function  newqdtj($startday,$endday,$type,$bb='',$paytype='',$page,$dev)
    {
        /*今日*/
        $timetype=$type;//空 日期  1 小时
        $today = intval($startday);
        $theday = intval($endday);
        $listsize = ($theday - $today) / 3600 / 24;
        $app_version = $bb;
        $dem_num =$dev;
        $pay_type =empty($paytype)?'0':intval($paytype);
        $pages =empty($page)?'0':intval($page);
        $showdate = array();

            for($i=0;$i<$listsize;$i++)
            {
                $showdate[]=date('m-d',$today+$i*24*60*60);
            }

        if($this->_timetype ==3 ){
            /*所有渠道数据*/
            $qdsql = 'SELECT
                    channel,
                    sum(newadduser) as adduser,
                    sum(openuser) as openuser,
                    sum(payuser) as payuser,
                    sum(newpayuser) as newpayuser,
                    sum(paymoney) as paymoney
                FROM
                    y_wu_tjselectday
                WHERE 
                    dodate 
                BETWEEN ' . $today . ' and ' . $theday . ' 
                AND
                    (app_version="' . $app_version . '"  or "' . $app_version . '"="") and 
                    (dem_num="' . $dem_num . '"  or "' . $dem_num . '"="") and 
                     (pay_type="' . $pay_type . '"  or "' . $pay_type . '"="")
                     group by
                channel
                
                ';
            $qdlistall = Yii::app()->db->createCommand($qdsql)->queryAll();

            /*获取top10的渠道数据*/
            if ($pages) {

                if ($pages == 1) {
                    $sqltj = 'openuser';
                    $sqldo = 'sum(openuser)  as ';
                } else if ($pages == 2) {
                    $sqltj = 'payuser';
                    $sqldo = 'sum(payuser)  as ';
                } else if ($pages == 3) {
                    $sqltj = 'newpayuser';
                    $sqldo = 'sum(newpayuser)  as ';
                } else if ($pages == 4) {
                    $sqltj = 'paymoney';
                    $sqldo = 'sum(paymoney)  as ';
                } elseif ($pages == 5) {
                    $sqltj = 'fflv';
                    $sqldo = 'truncate(sum(newpayuser)/sum(newadduser)*100,2)  as ';
                } elseif ($pages == 6) {
                    $sqltj = 'arpu';
                    $sqldo = 'truncate(sum(paymoney)/sum(newadduser),2)  as ';

                } else {
                    $sqltj = 'adduser';
                    $sqldo = 'sum(newadduser) as ';
                }

            } else {
                $sqltj = 'adduser';
                $sqldo = 'sum(newadduser) as ';
            }
            /*新增用户*/
            $addusersql = 'SELECT 
                        channel ,
                        DATE_FORMAT(FROM_UNIXTIME(dodate),"%Y-%m-%d") as time,
                         ' . $sqldo . '  ' . $sqltj . '
                        from 
                        y_wu_tjselectday b 
                        where  	dodate 
                                    BETWEEN ' . $today . ' and ' . $theday . ' 
                                     and
                                  (app_version="' . $app_version . '"  or "' . $app_version . '"="") and 
                                    (pay_type="' . $pay_type . '"  or "' . $pay_type . '"="") and 
                        exists(SELECT 
                                            channel
                                    FROM 
                                    (
                                    SELECT
                                            channel,
                                            DATE_FORMAT(FROM_UNIXTIME(dodate),"%Y-%m-%d") as time,
                                            ' . $sqldo . '  ' . $sqltj . '
                                    FROM
                                    y_wu_tjselectday
                                    WHERE
                                            dodate 
                                     BETWEEN ' . $today . ' and ' . $theday . ' 
                                      AND
                    (app_version="' . $app_version . '"  or "' . $app_version . '"="") and 
                     (dem_num="' . $dem_num . '"  or "' . $dem_num . '"="") and 
                     (pay_type="' . $pay_type . '"  or "' . $pay_type . '"="")
                                    GROUP BY channel ORDER BY ' . $sqltj . ' desc limit 10
                                    )t where channel = b.channel)
                        GROUP BY channel,time ';


            $toptenall = Yii::app()->db->createCommand($addusersql)->queryAll();
        }else {
            /*所有渠道数据*/
            $qdsql = 'SELECT
                    channel,
                    sum(newadduser) as adduser,
                    sum(openuser) as openuser,
                    sum(payuser) as payuser,
                    sum(newpayuser) as newpayuser,
                    sum(paymoney) as paymoney
                FROM
                    y_wu_tjselectall
                WHERE 
                    dodate 
                BETWEEN ' . $today . ' and ' . $theday . ' 
                AND
                    (app_version="' . $app_version . '"  or "' . $app_version . '"="") and 
                     (dem_num="' . $dem_num . '"  or "' . $dem_num . '"="") and 
                     (pay_type="' . $pay_type . '"  or "' . $pay_type . '"="")
                     group by
                channel
                
                ';
            $qdlistall = Yii::app()->db->createCommand($qdsql)->queryAll();

            /*获取top10的渠道数据*/
            if ($pages) {

                if ($pages == 1) {
                    $sqltj = 'openuser';
                    $sqldo = 'sum(openuser)  as ';
                } else if ($pages == 2) {
                    $sqltj = 'payuser';
                    $sqldo = 'sum(payuser)  as ';
                } else if ($pages == 3) {
                    $sqltj = 'newpayuser';
                    $sqldo = 'sum(newpayuser)  as ';
                } else if ($pages == 4) {
                    $sqltj = 'paymoney';
                    $sqldo = 'sum(paymoney)  as ';
                } elseif ($pages == 5) {
                    $sqltj = 'fflv';
                    $sqldo = 'truncate(sum(newpayuser)/sum(newadduser)*100,2)  as ';
                } elseif ($pages == 6) {
                    $sqltj = 'arpu';
                    $sqldo = 'truncate(sum(paymoney)/sum(newadduser),2)  as ';

                } else {
                    $sqltj = 'adduser';
                    $sqldo = 'sum(newadduser) as ';
                }

            } else {
                $sqltj = 'adduser';
                $sqldo = 'sum(newadduser) as ';
            }
            /*新增用户*/
            $addusersql = 'SELECT 
                        channel ,
                        DATE_FORMAT(FROM_UNIXTIME(dodate),"%Y-%m-%d") as time,
                         ' . $sqldo . '  ' . $sqltj . '
                        from 
                        y_wu_tjselectall b 
                        where  	dodate 
                                    BETWEEN ' . $today . ' and ' . $theday . ' 
                                     and
                                  (app_version="' . $app_version . '"  or "' . $app_version . '"="") and 
                                    (pay_type="' . $pay_type . '"  or "' . $pay_type . '"="") and 
                        exists(SELECT 
                                            channel
                                    FROM 
                                    (
                                    SELECT
                                            channel,
                                            DATE_FORMAT(FROM_UNIXTIME(dodate),"%Y-%m-%d") as time,
                                            ' . $sqldo . '  ' . $sqltj . '
                                    FROM
                                    y_wu_tjselectall
                                    WHERE
                                            dodate 
                                     BETWEEN ' . $today . ' and ' . $theday . ' 
                                      AND
                    (app_version="' . $app_version . '"  or "' . $app_version . '"="") and 
                     (dem_num="' . $dem_num . '"  or "' . $dem_num . '"="") and 
                     (pay_type="' . $pay_type . '"  or "' . $pay_type . '"="")
                                    GROUP BY channel ORDER BY ' . $sqltj . ' desc limit 10
                                    )t where channel = b.channel)
                        GROUP BY channel,time ';


            $toptenall = Yii::app()->db->createCommand($addusersql)->queryAll();
        }
        foreach ($toptenall as $key=> $ulist){
            $dayalls[$ulist['channel']][strtotime($ulist['time'])] = $ulist[ $sqltj];
            $listqd[$ulist['channel']] = $ulist['channel'];
        }

        foreach ($listqd as $key=>$list){
            for ($i=0;$i<$listsize;$i++){
                $lnum= $today+$i*24*60*60;
                if($dayalls[$list][$lnum]){
                    $listalls[$list][$lnum]=$dayalls[$list][$lnum];
                }else{
                    $listalls[$list][$lnum]=0;
                }
            }
            $qdalls[]= $this->_qdlist[$key];
        }
        foreach($listalls as $key=>$llist){
           $cs[$key] = array_values($llist);
        }
        foreach($qdlistall as & $qlist){
            $qlist['channel'] =$this->_qdlist[$qlist['channel']];
            $qlist['fflv'] = empty($qlist['adduser'])?'0':sprintf("%.4f",$qlist['newpayuser']/$qlist['adduser'])*100;
            $qlist['arpu']=empty($qlist['adduser'])?'0':sprintf("%.2f",$qlist['paymoney']/$qlist['adduser']);

        }
        $qdlist['qd']=$qdalls;
        $qdlist['zxlist'] =  $cs;
        $qdlist['qdlist'] =  $qdlistall;
        $this->_datelistall = $showdate;


        return $qdlist;

    }

    /*%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%渠道详情界面%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%*/

    public function actionqdlist(){
        /*基础配备 获取 渠道 版本 支付渠道等内容*/
        $index=21;
        $this->_kzqdlist=$this->listkzqd();

        $this->_bbqdlist=$this->listqdbb();
        $page = intval(Yii::app()->request->getParam('page'));
        $quedao =empty($_REQUEST['channel'])?'':intval($_REQUEST['channel']);
        $timetype = empty($_REQUEST['timetype'])?'':intval($_REQUEST['timetype']);
        if(isset($_POST['ztqs'])){
            $inputfrom =$_POST['ztqs'];

            $starttime  = empty($_POST['ztqs']['starttime'])?strtotime(date('Y-m-d',strtotime('-7 days'))):strtotime($_POST['ztqs']['starttime']);
            $endtime= empty($_POST['ztqs']['endtime'])?strtotime('today'):strtotime($_POST['ztqs']['endtime']);
            $quedao=empty($_POST['ztqs']['qdone'])?'':intval($_POST['ztqs']['qdone']);
            $qdtwo=empty($_POST['ztqs']['qdtwo'])?'':intval($_POST['ztqs']['qdtwo']);
            $qdthree=empty($_POST['ztqs']['qdthree'])?'':intval($_POST['ztqs']['qdthree']);
            $bb=empty($_POST['ztqs']['bb'])?$this->_mrbb:intval($_POST['ztqs']['bb']);
            $bb=($bb==999)?'':$bb;
            $devnum=empty($_POST['ztqs']['devnum'])?'':intval($_POST['ztqs']['devnum']);
            $pay=empty($_POST['ztqs']['pay'])?'':intval($_POST['ztqs']['pay']);
            $timeall=empty($_POST['ztqs']['timeall'])?'0':intval($_POST['ztqs']['timeall']);
            $this->_timetype = $timeall;
            $excel=empty($_POST['ztqs']['excel'])?'':intval($_POST['ztqs']['excel']);
            if($excel){
                if ($timeall == 1) {
                    $starttime = strtotime(date('Y-m-d', strtotime('-1 days')));
                    $endtime = strtotime(date('Y-m-d'));
                    //$this->_dayupsum=$this->newqdxqtj($starttime,$endtime,$timetype,$quedao,$zquedao,$bb,$pay);
                    $this->_dayupsum = $this->newqdexcel($starttime, $endtime, $timetype, $quedao, $qdtwo, $qdthree, $bb, $pay, $devnum);
                } elseif ($timeall == 2) {
                    $starttime = strtotime(date('Y-m-d', strtotime('-7 days')));
                    $endtime = strtotime(date('Y-m-d', strtotime('-6 days')));
                    //  $this->_dayupsum=$this->newqdxqtj($sevendate,$sevendate2,$timetype,$quedao,$zquedao,$bb,$pay);
                    $this->_dayupsum = $this->newqdexcel($sevendate, $sevendate2, $timetype, $quedao, $qdtwo, $qdthree, $bb, $pay, $devnum);
                } elseif ($timeall == 3) {
                    $sevendate = strtotime(date('Y-m-d'));
                    $sevendate2 = strtotime(date('Y-m-d', strtotime('+1 days')));
                    //  $this->_dayupsum=$this->newqdxqtj($sevendate,$sevendate2,$timetype,$quedao,$zquedao,$bb,$pay);
                    $this->_dayupsum = $this->newqdexcel($sevendate, $sevendate2, $timetype, $quedao, $qdtwo, $qdthree, $bb, $pay, $devnum);
                } else {
                    if ($starttime == strtotime(date('Y-m-d'))) {
                        $this->_timetype = 3;
                        $sevendate = strtotime(date('Y-m-d'));
                        $sevendate2 = strtotime(date('Y-m-d', strtotime('+1 days')));
                        // $this->_dayupsum=$this->newqdxqtj($sevendate,$sevendate2,$timetype,$quedao,$zquedao,$bb,$pay);
                        $this->_dayupsum = $this->newqdexcel($sevendate, $sevendate2, $timetype, $quedao, $qdtwo, $qdthree, $bb, $pay, $devnum);
                    } else {
                        // $this->_dayupsum=$this->newqdxqtj($starttime,$endtime,$timetype,$quedao,$zquedao,$bb,$pay);
                        $this->_dayupsum = $this->newqdexcel($starttime, $endtime, $timetype, $quedao, $qdtwo, $qdthree, $bb, $pay, $devnum);
                    }
                }
            }else {

                if ($timeall == 1) {
                    $starttime = strtotime(date('Y-m-d', strtotime('-1 days')));
                    $endtime = strtotime(date('Y-m-d'));
                    //$this->_dayupsum=$this->newqdxqtj($starttime,$endtime,$timetype,$quedao,$zquedao,$bb,$pay);
                    $this->_dayupsum = $this->newqdxqtj($starttime, $endtime, $timetype, $quedao, $qdtwo, $qdthree, $bb, $pay, $devnum);
                } elseif ($timeall == 2) {
                    $sevendate = strtotime(date('Y-m-d', strtotime('-7 days')));
                    $sevendate2 = strtotime(date('Y-m-d', strtotime('-6 days')));
                    //  $this->_dayupsum=$this->newqdxqtj($sevendate,$sevendate2,$timetype,$quedao,$zquedao,$bb,$pay);
                    $this->_dayupsum = $this->newqdxqtj($sevendate, $sevendate2, $timetype, $quedao, $qdtwo, $qdthree, $bb, $pay, $devnum);
                } elseif ($timeall == 3) {
                    $sevendate = strtotime(date('Y-m-d'));
                    $sevendate2 = strtotime(date('Y-m-d', strtotime('+1 days')));
                    //  $this->_dayupsum=$this->newqdxqtj($sevendate,$sevendate2,$timetype,$quedao,$zquedao,$bb,$pay);
                    $this->_dayupsum = $this->newqdxqtj($sevendate, $sevendate2, $timetype, $quedao, $qdtwo, $qdthree, $bb, $pay, $devnum);
                } else {
                    if ($starttime == strtotime(date('Y-m-d'))) {
                        $this->_timetype = 3;
                        $sevendate = strtotime(date('Y-m-d'));
                        $sevendate2 = strtotime(date('Y-m-d', strtotime('+1 days')));
                        // $this->_dayupsum=$this->newqdxqtj($sevendate,$sevendate2,$timetype,$quedao,$zquedao,$bb,$pay);
                        $this->_dayupsum = $this->newqdxqtj($sevendate, $sevendate2, $timetype, $quedao, $qdtwo, $qdthree, $bb, $pay, $devnum);
                    } else {
                        // $this->_dayupsum=$this->newqdxqtj($starttime,$endtime,$timetype,$quedao,$zquedao,$bb,$pay);
                        $this->_dayupsum = $this->newqdxqtj($starttime, $endtime, $timetype, $quedao, $qdtwo, $qdthree, $bb, $pay, $devnum);
                    }
                }
            }

        }else{
            $this->_timetype =1;
            $inputfrom =array();
            $starttime=  strtotime(date('Y-m-d',strtotime('-1 days')));
            $endtime=  strtotime(date('Y-m-d'));
            $this->_dayupsum=$this->newqdxqtj($starttime,$endtime,$timetype,$quedao,'','',$this->_mrbb,'','');
            //$this->_dayupsum=$this->newqdxqtj($starttime,$endtime,$timetype,$quedao,'','','');

        }

        $this->render('qdlist', array (
            'res'=>$index,
            'pages'=>$page,
            'quedao'=>$quedao,
            'starttime'=>$starttime,
            'endtime'=>$endtime ,
            'searchfrom'=>$inputfrom,
            'timetype'=>$timetype
        ));

    }

    /*列出渠道列表的统计*/
    private  function  newqdxqtj($startday,$endday,$type,$qd_b='',$qd_t='',$qd_s='',$bb='',$paytype='',$dev)
    {
        /*今日*/
        $timetype=$type;//空 日期  1 小时
        $today = intval($startday);
        $theday = intval($endday);
        $listsize = ($theday - $today) / 3600 / 24;
        $channel = empty($qd_b)?'':$qd_b;
        $channel_t = $qd_t;
        $channel_s = $qd_s;
        $app_version = $bb;
        $dem_num = $dev;
        $pay_type =empty($paytype)?'0':intval($paytype);
        $showdate = array();

        if($this->_timetype ==3 ){
            $sql = 'SELECT
channel,
                    channel_s,
                      channel_t,
                    sum(newadduser) as adduser,
                    sum(openuser) as openuser,
                    sum(payuser) as payuser,
                    sum(newpayuser) as newpayuser,
                    sum(paymoney) as paymoney
                FROM
                    y_wu_tjselectday
                WHERE 
                    dodate 
                BETWEEN ' . $today . ' and ' . $theday . ' 
                AND
                (channel="' . $channel . '"  or "' . $channel . '"="") and 
                 (channel_t="' . $channel_t . '"  or "' . $channel_t . '"="") and 
                  (channel_s="' . $channel_s . '"  or "' . $channel_s . '"="") and 
                    (app_version="' . $app_version . '"  or "' . $app_version . '"="") and 
                     (dem_num="' . $dem_num . '"  or "' . $dem_num . '"="") and 
                     (pay_type="' . $pay_type . '"  or "' . $pay_type . '"="")
                     group by
                channel,channel_s,channel_t
                
                ';

            $ztqsall = Yii::app()->db->createCommand($sql)->queryAll();
            foreach ($ztqsall as &$ulist) {
                $ulist['channel_all'] = $this->_qdlist_t[$ulist['channel_t']] . '-' . $this->_qdlist_s[$ulist['channel_s']];
                $ulist['paymoney'] = intval($ulist['paymoney']);
                $ulist['fflv'] = empty($ulist['adduser']) ? '0' : sprintf("%.4f", ($ulist['newpayuser'] / $ulist['adduser']) * 100);
                $ulist['arpu'] = empty($ulist['adduser']) ? '0' : sprintf("%.2f", $ulist['paymoney'] / $ulist['adduser']);
            }
        }else {
            for ($i = 0; $i < $listsize; $i++) {
                $showdate[] = date('m-d', $today + $i * 24 * 60 * 60);
            }
            $sql = 'SELECT
channel,
                    channel_s,
                      channel_t,
                    sum(newadduser) as adduser,
                    sum(openuser) as openuser,
                    sum(payuser) as payuser,
                    sum(newpayuser) as newpayuser,
                    sum(paymoney) as paymoney
                FROM
                    y_wu_tjselectall
                WHERE 
                    dodate 
                BETWEEN ' . $today . ' and ' . $theday . ' 
                AND
                (channel="' . $channel . '"  or "' . $channel . '"="") and 
                 (channel_t="' . $channel_t . '"  or "' . $channel_t . '"="") and 
                  (channel_s="' . $channel_s . '"  or "' . $channel_s . '"="") and 
                    (app_version="' . $app_version . '"  or "' . $app_version . '"="") and 
                     (dem_num="' . $dem_num . '"  or "' . $dem_num . '"="") and 
                     (pay_type="' . $pay_type . '"  or "' . $pay_type . '"="")
                     group by
               channel, channel_s,channel_t
                
                ';
            $ztqsall = Yii::app()->db->createCommand($sql)->queryAll();

            foreach ($ztqsall as &$ulist) {
                $ulist['channel_all'] = $this->_qdlist_t[$ulist['channel_t']] . '-' . $this->_qdlist_s[$ulist['channel_s']];
                $ulist['paymoney'] = intval($ulist['paymoney']);
                $ulist['fflv'] = empty($ulist['adduser']) ? '0' : sprintf("%.4f", ($ulist['newpayuser'] / $ulist['adduser']) * 100);
                $ulist['arpu'] = empty($ulist['adduser']) ? '0' : sprintf("%.2f", $ulist['paymoney'] / $ulist['adduser']);
            }

        }

        $this->_datelistall = $showdate;
        $listalls['tablelist'] = $ztqsall;

        return $listalls;

    }

    /*列出渠道列表的统计*/
    private  function  newqdexcel($startday,$endday,$type,$qd_b='',$qd_t='',$qd_s='',$bb='',$paytype='',$dev)
    {
        /*今日*/
        $timetype=$type;//空 日期  1 小时
        $today = intval($startday);
        $theday = intval($endday);
        $listsize = ($theday - $today) / 3600 / 24;
        $channel = empty($qd_b)?'':$qd_b;
        $channel_t = $qd_t;
        $channel_s = $qd_s;
        $app_version = $bb;
        $dem_num = $dev;
        $pay_type =empty($paytype)?'0':intval($paytype);
        $showdate = array();

        if($this->_timetype ==3 ){
            $sql = 'SELECT
channel,
                    channel_s,
                      channel_t,
                    sum(newadduser) as adduser,
                    sum(openuser) as openuser,
                    sum(payuser) as payuser,
                    sum(newpayuser) as newpayuser,
                    sum(paymoney) as paymoney
                FROM
                    y_wu_tjselectday
                WHERE 
                    dodate 
                BETWEEN ' . $today . ' and ' . $theday . ' 
                AND
                (channel="' . $channel . '"  or "' . $channel . '"="") and 
                 (channel_t="' . $channel_t . '"  or "' . $channel_t . '"="") and 
                  (channel_s="' . $channel_s . '"  or "' . $channel_s . '"="") and 
                    (app_version="' . $app_version . '"  or "' . $app_version . '"="") and 
                     (dem_num="' . $dem_num . '"  or "' . $dem_num . '"="") and 
                     (pay_type="' . $pay_type . '"  or "' . $pay_type . '"="")
                     group by
                channel,channel_s,channel_t
                
                ';

            $ztqsall = Yii::app()->db->createCommand($sql)->queryAll();
            foreach ($ztqsall as &$ulist) {
                $ulist['channel_all'] = $this->_qdlist_t[$ulist['channel_t']] . '-' . $this->_qdlist_s[$ulist['channel_s']];
                $ulist['paymoney'] = intval($ulist['paymoney']);
                $ulist['fflv'] = empty($ulist['adduser']) ? '0' : sprintf("%.4f", ($ulist['newpayuser'] / $ulist['adduser']) * 100);
                $ulist['arpu'] = empty($ulist['adduser']) ? '0' : sprintf("%.2f", $ulist['paymoney'] / $ulist['adduser']);
            }
        }else {
            for ($i = 0; $i < $listsize; $i++) {
                $showdate[] = date('m-d', $today + $i * 24 * 60 * 60);
            }
            $sql = 'SELECT
channel,
                    channel_s,
                      channel_t,
                    sum(newadduser) as adduser,
                    sum(openuser) as openuser,
                    sum(payuser) as payuser,
                    sum(newpayuser) as newpayuser,
                    sum(paymoney) as paymoney
                FROM
                    y_wu_tjselectall
                WHERE 
                    dodate 
                BETWEEN ' . $today . ' and ' . $theday . ' 
                AND
                (channel="' . $channel . '"  or "' . $channel . '"="") and 
                 (channel_t="' . $channel_t . '"  or "' . $channel_t . '"="") and 
                  (channel_s="' . $channel_s . '"  or "' . $channel_s . '"="") and 
                    (app_version="' . $app_version . '"  or "' . $app_version . '"="") and 
                     (dem_num="' . $dem_num . '"  or "' . $dem_num . '"="") and 
                     (pay_type="' . $pay_type . '"  or "' . $pay_type . '"="")
                     group by
               channel, channel_s,channel_t
                
                ';
            $ztqsall = Yii::app()->db->createCommand($sql)->queryAll();

            foreach ($ztqsall as &$ulist) {
                $ulist['channel_all'] =  $this->_qdlist[$ulist['channel']].'-'.$this->_qdlist_t[$ulist['channel_t']] . '-' . $this->_qdlist_s[$ulist['channel_s']];
                $ulist['paymoney'] = intval($ulist['paymoney']);
                $ulist['fflv'] = empty($ulist['adduser']) ? '0' : sprintf("%.4f", ($ulist['newpayuser'] / $ulist['adduser']) * 100);
                $ulist['arpu'] = empty($ulist['adduser']) ? '0' : sprintf("%.2f", $ulist['paymoney'] / $ulist['adduser']);
            }

        }

//        $ztqsall=Yii::app()->db->createCommand($sql)->queryAll();
//
//        foreach ($ztqsall as &$ulist){
//            $ulist['channel_all'] = $this->_qdlist[$ulist['channel']].'-'.$this->_qdlist_t[$ulist['channel_t']].'-'.$this->_qdlist_s[$ulist['channel_s']];
//
//        }

//        $sql='SELECT * FROM y_wu_app as apps LEFT JOIN (SELECT y_wu_bigtype.typename as bigname,y_wu_bigtype.id as bigid FROM y_wu_bigtype) as big ON  apps.appbigtypeid = big.bigid LEFT JOIN (SELECT y_wu_type.id as smid, y_wu_type.s_typename as smname FROM y_wu_type)as sm ON apps.appsmtypeid = sm.smid';
//        $sresult=Yii::app()->db->createCommand($sql)->queryAll();

        $arr=array();

        $arr[] = array(
            array('val'=>'渠道名','align'=>'center','width'=>40),
            array('val'=>'新增用户','align'=>'center','width'=>5),
            array('val'=>'活跃用户','align'=>'center','width'=>10),
            array('val'=>'付费用户','align'=>'center','width'=>5),
            array('val'=>'新增付费用户','align'=>'center','width'=>10),
            array('val'=>'付费金额','align'=>'center','width'=>5),
            array('val'=>'付费率','align'=>'center','width'=>10),
            array('val'=>'ARPU','align'=>'center','width'=>10),
        );



        foreach($ztqsall as &$v){

            $arr[] = array(
                array('val'=>''.$v['channel_all'].''),
                array('val'=>''.$v['adduser'].''),
                array('val'=>''.$v['openuser'].''),
                array('val'=>''.$v['payuser'].''),
                array('val'=>''.$v['newpayuser'].''),
                array('val'=>''.$v['paymoney'].''),
                array('val'=>''.$v['fflv'].''),
                array('val'=>''.$v['arpu'].''),

            );
        }



        $objectPHPExcel = new ExcelExport('渠道数据');
        foreach($arr as $val){
            $objectPHPExcel->setCells($val);
        }
        $objectPHPExcel->save();

    }
//    private  function  newqdxqtj($startday,$endday,$type,$qd_b='',$qd_s='',$bb='',$paytype='')
//    {
//        /*今日*/
//        $timetype=$type;//空 日期  1 小时
//        $today = intval($startday);
//        $theday = intval($endday);
//        $channel = empty($qd_b)?'':$qd_b;
//        $channel_s = $qd_s;
//        $app_version = $bb;
//        $pay_type =empty($paytype)?'0':intval($paytype);
//        $showdate = array();
//
//        if($this->_timetype ==3 ){
//            $sql = 'SELECT
//                    channel_s,
//                    sum(newadduser) as adduser,
//                    sum(openuser) as openuser,
//                    sum(payuser) as payuser,
//                    sum(newpayuser) as newpayuser,
//                    sum(paymoney) as paymoney
//                FROM
//                    y_wu_tjselectday
//                WHERE
//                    dodate
//                BETWEEN ' . $today . ' and ' . $theday . '
//                AND
//                 (channel="' . $channel . '"  or "' . $channel . '"="") and
//                   (channel_s="' . $channel_s . '"  or "' . $channel_s . '"="") and
//                    (app_version="' . $app_version . '"  or "' . $app_version . '"="") and
//                     (pay_type="' . $pay_type . '"  or "' . $pay_type . '"="")
//                     group by
//                channel_s
//
//                ';
//            $ztqsall = Yii::app()->db->createCommand($sql)->queryAll();
//
//
//            foreach ($ztqsall as &$ulist) {
//                $ulist['channel_all'] = empty($this->_qdlist_s[$ulist['channel_s']]) ? '未知' . $ulist['channel_s'] : $this->_qdlist_s[$ulist['channel_s']];
//                $ulist['paymoney'] = intval($ulist['paymoney']);
//                $ulist['fflv'] = empty($ulist['adduser']) ? '0' : sprintf("%.4f", ($ulist['newpayuser'] / $ulist['adduser']) * 100);
//                $ulist['arpu'] = empty($ulist['adduser']) ? '0' : sprintf("%.2f", $ulist['paymoney'] / $ulist['adduser']);
//            }
//        }else {
//            $sql = 'SELECT
//                    channel_s,
//                    sum(newadduser) as adduser,
//                    sum(openuser) as openuser,
//                    sum(payuser) as payuser,
//                    sum(newpayuser) as newpayuser,
//                    sum(paymoney) as paymoney
//                FROM
//                    y_wu_tjselectall
//                WHERE
//                    dodate
//                BETWEEN ' . $today . ' and ' . $theday . '
//                AND
//                 (channel="' . $channel . '"  or "' . $channel . '"="") and
//                   (channel_s="' . $channel_s . '"  or "' . $channel_s . '"="") and
//                    (app_version="' . $app_version . '"  or "' . $app_version . '"="") and
//                     (pay_type="' . $pay_type . '"  or "' . $pay_type . '"="")
//                     group by
//                channel_s
//
//                ';
//            $ztqsall = Yii::app()->db->createCommand($sql)->queryAll();
//
//
//            foreach ($ztqsall as &$ulist) {
//                $ulist['channel_all'] = empty($this->_qdlist_s[$ulist['channel_s']]) ? '未知' . $ulist['channel_s'] : $this->_qdlist_s[$ulist['channel_s']];
//                $ulist['paymoney'] = intval($ulist['paymoney']);
//                $ulist['fflv'] = empty($ulist['adduser']) ? '0' : sprintf("%.4f", ($ulist['newpayuser'] / $ulist['adduser']) * 100);
//                $ulist['arpu'] = empty($ulist['adduser']) ? '0' : sprintf("%.2f", $ulist['paymoney'] / $ulist['adduser']);
//            }
//        }
//
//
//        $this->_datelistall = $showdate;
//        $listalls['tablelist'] = $ztqsall;
//
//        return $listalls;
//
//    }







/******************************************************付费***************************************************************/
    /************************************************************************************************************************/
    /************************************************************************************************************************/
    /****************************************************区域******************************************************************/
    public  function  actionpayindex(){
        /*基础配备 获取 渠道 版本 支付渠道等内容*/
        $index=5;
        $this->_bbqdlist=$this->listqdbb();
        $page = intval(Yii::app()->request->getParam('page'));

        $timetype = empty($_REQUEST['timetype'])?'':intval($_REQUEST['timetype']);
        if(isset($_POST['ztqs'])){
            $inputfrom =$_POST['ztqs'];

            $starttime  = empty($_POST['ztqs']['starttime'])?strtotime(date('Y-m-d',strtotime('-7 days'))):strtotime($_POST['ztqs']['starttime']);
            $endtime= empty($_POST['ztqs']['endtime'])?strtotime('today'):strtotime($_POST['ztqs']['endtime']);
            $quedao=empty($_POST['ztqs']['qdone'])?'':intval($_POST['ztqs']['qdone']);
            $zquedao=empty($_POST['ztqs']['qdtwo'])?'':intval($_POST['ztqs']['qdtwo']);
            $bb=empty($_POST['ztqs']['bb'])?$this->_mrbb:intval($_POST['ztqs']['bb']);
            $bb=($bb==999)?'':$bb;
            $devnum=empty($_POST['ztqs']['devnum'])?'':intval($_POST['ztqs']['devnum']);
            $pay=empty($_POST['ztqs']['pay'])?'':intval($_POST['ztqs']['pay']);
            $this->_dayupsum=$this->newpay($starttime,$endtime,$timetype,$quedao,$zquedao,$bb,$pay,$devnum);

        }else{
            $inputfrom =array();
            $starttime  = strtotime(date('Y-m-d',strtotime('-7 days')));
            $endtime= strtotime('today');
            $this->_dayupsum=$this->newpay($starttime,$endtime,$timetype,'','',$this->_mrbb,'','');

        }

        $this->render('payindex', array (
            'res'=>$index,
            'pages'=>$page,
            'starttime'=>$starttime,
            'endtime'=>$endtime ,
            'searchfrom'=>$inputfrom,
            'timetype'=>$timetype
        ));

    }

    /*列出新增用户折线图*/
    private  function  newpay($startday,$endday,$type,$qd_b='',$qd_s='',$bb='',$paytype='',$dev='')
    {
        /*今日*/
        $timetype=$type;//空 日期  1 小时
        $today = intval($startday);
        $theday = intval($endday);
        $listsize = ($theday - $today) / 3600 / 24;
        $channel = $qd_b;
        $channel_s = $qd_s;
        $app_version = $bb;
        $dem_num=$dev;
        $pay_type =empty($paytype)?'0':intval($paytype);
        $showdate = array();

        if($timetype ==1){
            for($i=0;$i<$listsize*24;$i++)
            {
                $showdate[]=date('m-d H:00',$today+$i*60*60);
            }
            $sql ='SELECT
                    DATE_FORMAT(FROM_UNIXTIME(dodate),"%Y-%m-%d %H:00") as time,
                    sum(newadduser) as adduser,
                    sum(payuser) as payuser, 
                    sum(paysum) as paysum,
                    sum(newpayuser) as newpayuser,
                    sum(paymoney) as paymoney
                FROM
                    y_wu_tjselectall
                WHERE 
                    dodate 
                BETWEEN '.$today.' and '.$theday.' 
                AND
                  (channel="'.$channel.'"  or "'.$channel.'"="") and 
                   (channel_s="'.$channel_s.'"  or "'.$channel_s.'"="") and 
                    (app_version="'.$app_version.'"  or "'.$app_version.'"="") and 
                     (dem_num="'.$dem_num.'"  or "'.$dem_num.'"="") and 
                     (pay_type="'.$pay_type.'"  or "'.$pay_type.'"="")
                     group by
                time WITH ROLLUP
                
                ';

            $ztqsall=Yii::app()->db->createCommand($sql)->queryAll();
            foreach ($ztqsall as &$ulist){
                $dayalls['adduser'][strtotime($ulist['time'])] = $ulist['adduser'];
                $dayalls['paysum'][strtotime($ulist['time'])] = $ulist['paysum'];
                $dayalls['payuser'][strtotime($ulist['time'])] = $ulist['payuser'];
                $dayalls['newpayuser'][strtotime($ulist['time'])] = $ulist['newpayuser'];
                $dayalls['paymoney'][strtotime($ulist['time'])] = intval($ulist['paymoney']);
            }
            for ($i=0;$i<$listsize*24;$i++){
                $lnum= $today+$i*60*60;
                $listalls['tablelist'][$i]['dateall']=date('m-d H:00',$today+$i*60*60);
                $listalls['tablelist'][$i]['adduser']=empty( $dayalls['adduser'][$lnum])?'0': $dayalls['adduser'][$lnum];
                $listalls['tablelist'][$i]['paysum']=empty( $dayalls['paysum'][$lnum])?'0': $dayalls['paysum'][$lnum];
                $listalls['tablelist'][$i]['payuser']=empty( $dayalls['payuser'][$lnum])?'0': $dayalls['payuser'][$lnum];
                $listalls['tablelist'][$i]['newpayuser']=empty( $dayalls['newpayuser'][$lnum])?'0': $dayalls['newpayuser'][$lnum];
                $listalls['tablelist'][$i]['paymoney']=empty( $dayalls['paymoney'][$lnum])?'0': $dayalls['paymoney'][$lnum];
               // $listalls['tablelist'][$i]['fflv']=empty($dayalls['adduser'][$lnum])?'0':sprintf("%.4f",$dayalls['newpayuser'][$lnum]/$dayalls['adduser'][$lnum])*100;
                //$listalls['tablelist'][$i]['arpu']=empty($dayalls['adduser'][$lnum])?'0':sprintf("%.2f",$dayalls['paymoney'][$lnum]/$dayalls['adduser'][$lnum]);
                $listalls['zxianadduser'][$i]=empty( $dayalls['adduser'][$lnum])?'0':$dayalls['adduser'][$lnum];
                $listalls['zxianpaysum'][$i]=empty( $dayalls['paysum'][$lnum])?'0':$dayalls['paysum'][$lnum];
                $listalls['zxianpayuser'][$i]=empty( $dayalls['payuser'][$lnum])?'0':$dayalls['payuser'][$lnum];
                $listalls['zxiannewpayuser'][$i]=empty( $dayalls['newpayuser'][$lnum])?'0':$dayalls['newpayuser'][$lnum];
                $listalls['zxianpaymoney'][$i]=empty( $dayalls['paymoney'][$lnum])?'0':$dayalls['paymoney'][$lnum];
               // $listalls['zxianfflv'][$i]=empty($dayalls['adduser'][$lnum])?'0':sprintf("%.4f",$dayalls['newpayuser'][$lnum]/$dayalls['adduser'][$lnum])*100;
               // $listalls['zxianarpu'][$i]=empty($dayalls['adduser'][$lnum])?'0':sprintf("%.2f",$dayalls['paymoney'][$lnum]/$dayalls['adduser'][$lnum]);
            }
        }else{
            for ($i = 0; $i < $listsize; $i++) {
                $showdate[]=date('m-d',$today+$i*24*60*60);
            }
            $sql ='SELECT
                    DATE_FORMAT(FROM_UNIXTIME(dodate),"%Y-%m-%d") as time,
                    sum(newadduser) as adduser,
                    sum(paysum) as paysum,
                    sum(payuser) as payuser,
                    sum(newpayuser) as newpayuser,
                    sum(paymoney) as paymoney
                FROM
                    y_wu_tjselectall
                WHERE 
                    dodate 
                BETWEEN '.$today.' and '.$theday.' 
                AND
                  (channel="'.$channel.'"  or "'.$channel.'"="") and 
                   (channel_s="'.$channel_s.'"  or "'.$channel_s.'"="") and 
                    (app_version="'.$app_version.'"  or "'.$app_version.'"="") and 
                     (dem_num="'.$dem_num.'"  or "'.$dem_num.'"="") and 
                     (pay_type="'.$pay_type.'"  or "'.$pay_type.'"="")
                     group by
                time WITH ROLLUP
                
                ';

            $ztqsall=Yii::app()->db->createCommand($sql)->queryAll();
            foreach ($ztqsall as &$ulist){
                $dayalls['adduser'][strtotime($ulist['time'])] = $ulist['adduser'];
                $dayalls['paysum'][strtotime($ulist['time'])] = $ulist['paysum'];
                $dayalls['payuser'][strtotime($ulist['time'])] = $ulist['payuser'];
                $dayalls['newpayuser'][strtotime($ulist['time'])] = $ulist['newpayuser'];
                $dayalls['paymoney'][strtotime($ulist['time'])] = intval($ulist['paymoney']);
            }
            for ($i=0;$i<$listsize;$i++){
                $lnum= $today+$i*24*60*60;
                $listalls['tablelist'][$i]['dateall']=date('m-d',$today+$i*24*60*60);
                $listalls['tablelist'][$i]['adduser']=empty( $dayalls['adduser'][$lnum])?'0': $dayalls['adduser'][$lnum];
                $listalls['tablelist'][$i]['paysum']=empty( $dayalls['paysum'][$lnum])?'0': $dayalls['paysum'][$lnum];
                $listalls['tablelist'][$i]['payuser']=empty( $dayalls['payuser'][$lnum])?'0': $dayalls['payuser'][$lnum];
                $listalls['tablelist'][$i]['newpayuser']=empty( $dayalls['newpayuser'][$lnum])?'0': $dayalls['newpayuser'][$lnum];
                $listalls['tablelist'][$i]['paymoney']=empty( $dayalls['paymoney'][$lnum])?'0': $dayalls['paymoney'][$lnum];
               // $listalls['tablelist'][$i]['fflv']=empty($dayalls['adduser'][$lnum])?'0':sprintf("%.4f",$dayalls['newpayuser'][$lnum]/$dayalls['adduser'][$lnum])*100;
               // $listalls['tablelist'][$i]['arpu']=empty($dayalls['adduser'][$lnum])?'0':sprintf("%.2f",$dayalls['paymoney'][$lnum]/$dayalls['adduser'][$lnum]);
                $listalls['zxianadduser'][$i]=empty( $dayalls['adduser'][$lnum])?'0':$dayalls['adduser'][$lnum];
                $listalls['zxianpaysum'][$i]=empty( $dayalls['paysum'][$lnum])?'0':$dayalls['paysum'][$lnum];
                $listalls['zxianpayuser'][$i]=empty( $dayalls['payuser'][$lnum])?'0':$dayalls['payuser'][$lnum];
                $listalls['zxiannewpayuser'][$i]=empty( $dayalls['newpayuser'][$lnum])?'0':$dayalls['newpayuser'][$lnum];
                $listalls['zxianpaymoney'][$i]=empty( $dayalls['paymoney'][$lnum])?'0':$dayalls['paymoney'][$lnum];
               // $listalls['zxianfflv'][$i]=empty($dayalls['adduser'][$lnum])?'0':sprintf("%.4f",$dayalls['newpayuser'][$lnum]/$dayalls['adduser'][$lnum])*100;
               // $listalls['zxianarpu'][$i]=empty($dayalls['adduser'][$lnum])?'0':sprintf("%.2f",$dayalls['paymoney'][$lnum]/$dayalls['adduser'][$lnum]);

            }
        }
        $this->_datelistall = $showdate;
        $listalls['adduser']= $dayalls['adduser'][0];
        $listalls['paysum']= $dayalls['paysum'][0];
        $listalls['payuser']= $dayalls['payuser'][0];
        $listalls['newpayuser']= $dayalls['newpayuser'][0];
        $listalls['paymoney']= $dayalls['paymoney'][0];

        return $listalls;

    }











/*&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&付费转化&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&*/
    public  function  actionpaychang(){
        /*基础配备 获取 渠道 版本 支付渠道等内容*/
        $index=6;
        $this->_bbqdlist=$this->listqdbb();
        $page = intval(Yii::app()->request->getParam('page'));

        $timetype = empty($_REQUEST['timetype'])?'':intval($_REQUEST['timetype']);
        if(isset($_POST['ztqs'])){
            $inputfrom =$_POST['ztqs'];

            $starttime  = empty($_POST['ztqs']['starttime'])?strtotime(date('Y-m-d',strtotime('-7 days'))):strtotime($_POST['ztqs']['starttime']);
            $endtime= empty($_POST['ztqs']['endtime'])?strtotime('today'):strtotime($_POST['ztqs']['endtime']);
            $quedao=empty($_POST['ztqs']['qdone'])?'':intval($_POST['ztqs']['qdone']);
            $zquedao=empty($_POST['ztqs']['qdtwo'])?'':intval($_POST['ztqs']['qdtwo']);
            $bb=empty($_POST['ztqs']['bb'])?$this->_mrbb:intval($_POST['ztqs']['bb']);
            $bb=($bb==999)?'':$bb;
            $devnum=empty($_POST['ztqs']['devnum'])?'':intval($_POST['ztqs']['devnum']);
            $pay=empty($_POST['ztqs']['pay'])?'':intval($_POST['ztqs']['pay']);
            $this->_dayupsum=$this->newchang($starttime,$endtime,$timetype,$quedao,$zquedao,$bb,$pay,$devnum);

        }else{
            $inputfrom =array();
            $starttime  = strtotime(date('Y-m-d',strtotime('-7 days')));
            $endtime= strtotime('today');
            $this->_dayupsum=$this->newchang($starttime,$endtime,$timetype,'','',$this->_mrbb,'','');

        }

        $this->render('paychang', array (
            'res'=>$index,
            'pages'=>$page,
            'starttime'=>$starttime,
            'endtime'=>$endtime ,
            'searchfrom'=>$inputfrom,
            'timetype'=>$timetype
        ));

    }
    /*列出新增用户折线图*/
    private  function  newchang($startday,$endday,$type,$qd_b='',$qd_s='',$bb='',$paytype='',$dev)
    {
        /*今日*/
        $timetype=$type;//空 日期  1 小时
        $today = intval($startday);
        $theday = intval($endday);
        $listsize = ($theday - $today) / 3600 / 24;
        $channel = $qd_b;
        $channel_s = $qd_s;
        $app_version = $bb;
        $dem_num = $dev;
        $pay_type =empty($paytype)?'0':intval($paytype);
        $showdate = array();

        if($timetype ==1){
            for($i=0;$i<$listsize*24;$i++)
            {
                $showdate[]=date('m-d H:00',$today+$i*60*60);
            }
            $sql ='SELECT
                    DATE_FORMAT(FROM_UNIXTIME(dodate),"%Y-%m-%d %H:00") as time,
                   sum(tenuser) as tenuser,
                    sum(houruser) as houruser, 
                    sum(onedayuser) as onedayuser,
                    sum(threeday) as threeday,
                    sum(sevenday) as sevenday,
                    sum(otherday) as otherday
                FROM
                    y_wu_tjselectall
                WHERE 
                    dodate 
                BETWEEN '.$today.' and '.$theday.' 
                AND
                  (channel="'.$channel.'"  or "'.$channel.'"="") and 
                   (channel_s="'.$channel_s.'"  or "'.$channel_s.'"="") and 
                    (app_version="'.$app_version.'"  or "'.$app_version.'"="") and 
                     (dem_num="'.$dem_num.'"  or "'.$dem_num.'"="") and 
                     (pay_type="'.$pay_type.'"  or "'.$pay_type.'"="")
                     group by
                time WITH ROLLUP
                
                ';
            $ztqsall=Yii::app()->db->createCommand($sql)->queryAll();
            foreach ($ztqsall as &$ulist){
                $dayalls['tenuser'][strtotime($ulist['time'])] = $ulist['tenuser'];
                $dayalls['houruser'][strtotime($ulist['time'])] = $ulist['houruser'];
                $dayalls['onedayuser'][strtotime($ulist['time'])] = $ulist['onedayuser'];
                $dayalls['threeday'][strtotime($ulist['time'])] = $ulist['threeday'];
                $dayalls['sevenday'][strtotime($ulist['time'])] = $ulist['sevenday'];
                $dayalls['otherday'][strtotime($ulist['time'])] = $ulist['otherday'];
            }
            for ($i=0;$i<$listsize*24;$i++){
                $lnum= $today+$i*60*60;
                $listalls['tablelist'][$i]['dateall']=date('m-d H:00',$today+$i*60*60);
                $listalls['tablelist'][$i]['tenuser']=empty( $dayalls['tenuser'][$lnum])?'0': $dayalls['tenuser'][$lnum];
                $listalls['tablelist'][$i]['houruser']=empty( $dayalls['houruser'][$lnum])?'0': $dayalls['houruser'][$lnum];
                $listalls['tablelist'][$i]['onedayuser']=empty( $dayalls['onedayuser'][$lnum])?'0': $dayalls['onedayuser'][$lnum];
                $listalls['tablelist'][$i]['threeday']=empty( $dayalls['threeday'][$lnum])?'0': $dayalls['threeday'][$lnum];
                $listalls['tablelist'][$i]['sevenday']=empty( $dayalls['sevenday'][$lnum])?'0': $dayalls['sevenday'][$lnum];
                $listalls['tablelist'][$i]['otherday']=empty( $dayalls['otherday'][$lnum])?'0': $dayalls['otherday'][$lnum];

                $listalls['zxiantenuser'][$i]=empty( $dayalls['tenuser'][$lnum])?'0':$dayalls['tenuser'][$lnum];
                $listalls['zxianhouruser'][$i]=empty( $dayalls['houruser'][$lnum])?'0':$dayalls['houruser'][$lnum];
                $listalls['zxianonedayuser'][$i]=empty( $dayalls['onedayuser'][$lnum])?'0':$dayalls['onedayuser'][$lnum];
                $listalls['zxianthreeday'][$i]=empty( $dayalls['threeday'][$lnum])?'0':$dayalls['threeday'][$lnum];
                $listalls['zxiansevenday'][$i]=empty( $dayalls['sevenday'][$lnum])?'0':$dayalls['sevenday'][$lnum];
                $listalls['zxianotherday'][$i]=empty( $dayalls['otherday'][$lnum])?'0':$dayalls['otherday'][$lnum];
                // $listalls['zxianfflv'][$i]=empty($dayalls['adduser'][$lnum])?'0':sprintf("%.4f",$dayalls['newpayuser'][$lnum]/$dayalls['adduser'][$lnum])*100;
                // $listalls['zxianarpu'][$i]=empty($dayalls['adduser'][$lnum])?'0':sprintf("%.2f",$dayalls['paymoney'][$lnum]/$dayalls['adduser'][$lnum]);
            }
        }else{
            for ($i = 0; $i < $listsize; $i++) {
                $showdate[]=date('m-d',$today+$i*24*60*60);
            }
            $sql ='SELECT
                    DATE_FORMAT(FROM_UNIXTIME(dodate),"%Y-%m-%d") as time,
                     sum(tenuser) as tenuser,
                    sum(houruser) as houruser, 
                    sum(onedayuser) as onedayuser,
                    sum(threeday) as threeday,
                    sum(sevenday) as sevenday,
                    sum(otherday) as otherday
                FROM
                    y_wu_tjselectall
                WHERE 
                    dodate 
                BETWEEN '.$today.' and '.$theday.' 
                AND
                  (channel="'.$channel.'"  or "'.$channel.'"="") and 
                   (channel_s="'.$channel_s.'"  or "'.$channel_s.'"="") and 
                    (app_version="'.$app_version.'"  or "'.$app_version.'"="") and 
                     (dem_num="'.$dem_num.'"  or "'.$dem_num.'"="") and 
                     (pay_type="'.$pay_type.'"  or "'.$pay_type.'"="")
                     group by
                time WITH ROLLUP
                
                ';
            $ztqsall=Yii::app()->db->createCommand($sql)->queryAll();
            foreach ($ztqsall as &$ulist){
                $dayalls['tenuser'][strtotime($ulist['time'])] = $ulist['tenuser'];
                $dayalls['houruser'][strtotime($ulist['time'])] = $ulist['houruser'];
                $dayalls['onedayuser'][strtotime($ulist['time'])] = $ulist['onedayuser'];
                $dayalls['threeday'][strtotime($ulist['time'])] = $ulist['threeday'];
                $dayalls['sevenday'][strtotime($ulist['time'])] = $ulist['sevenday'];
                $dayalls['otherday'][strtotime($ulist['time'])] = $ulist['otherday'];
            }
            for ($i=0;$i<$listsize;$i++){
                $lnum= $today+$i*24*60*60;
                $listalls['tablelist'][$i]['dateall']=date('m-d',$today+$i*24*60*60);
                $listalls['tablelist'][$i]['tenuser']=empty( $dayalls['tenuser'][$lnum])?'0': $dayalls['tenuser'][$lnum];
                $listalls['tablelist'][$i]['houruser']=empty( $dayalls['houruser'][$lnum])?'0': $dayalls['houruser'][$lnum];
                $listalls['tablelist'][$i]['onedayuser']=empty( $dayalls['onedayuser'][$lnum])?'0': $dayalls['onedayuser'][$lnum];
                $listalls['tablelist'][$i]['threeday']=empty( $dayalls['threeday'][$lnum])?'0': $dayalls['threeday'][$lnum];
                $listalls['tablelist'][$i]['sevenday']=empty( $dayalls['sevenday'][$lnum])?'0': $dayalls['sevenday'][$lnum];
                $listalls['tablelist'][$i]['otherday']=empty( $dayalls['otherday'][$lnum])?'0': $dayalls['otherday'][$lnum];

                $listalls['zxiantenuser'][$i]=empty( $dayalls['tenuser'][$lnum])?'0':$dayalls['tenuser'][$lnum];
                $listalls['zxianhouruser'][$i]=empty( $dayalls['houruser'][$lnum])?'0':$dayalls['houruser'][$lnum];
                $listalls['zxianonedayuser'][$i]=empty( $dayalls['onedayuser'][$lnum])?'0':$dayalls['onedayuser'][$lnum];
                $listalls['zxianthreeday'][$i]=empty( $dayalls['threeday'][$lnum])?'0':$dayalls['threeday'][$lnum];
                $listalls['zxiansevenday'][$i]=empty( $dayalls['sevenday'][$lnum])?'0':$dayalls['sevenday'][$lnum];
                $listalls['zxianotherday'][$i]=empty( $dayalls['otherday'][$lnum])?'0':$dayalls['otherday'][$lnum];
                // $listalls['zxianfflv'][$i]=empty($dayalls['adduser'][$lnum])?'0':sprintf("%.4f",$dayalls['newpayuser'][$lnum]/$dayalls['adduser'][$lnum])*100;
                // $listalls['zxianarpu'][$i]=empty($dayalls['adduser'][$lnum])?'0':sprintf("%.2f",$dayalls['paymoney'][$lnum]/$dayalls['adduser'][$lnum]);

            }
        }
        $this->_datelistall = $showdate;

        $listalls['tenuser']= $dayalls['tenuser'][0];
        $listalls['houruser']= $dayalls['houruser'][0];
        $listalls['onedayuser']= $dayalls['onedayuser'][0];
        $listalls['threeday']= $dayalls['threeday'][0];
        $listalls['sevenday']= $dayalls['sevenday'][0];
        $listalls['otherday']= $dayalls['otherday'][0];
        return $listalls;

    }

    /*&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&付费习惯&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&*/
    public  function  actionpaymoney(){
        /*基础配备 获取 渠道 版本 支付渠道等内容*/
        $index=7;
        $this->_bbqdlist=$this->listqdbb();
        $page = intval(Yii::app()->request->getParam('page'));

        $timetype = empty($_REQUEST['timetype'])?'':intval($_REQUEST['timetype']);
        if(isset($_POST['ztqs'])){
            $inputfrom =$_POST['ztqs'];

            $starttime  = empty($_POST['ztqs']['starttime'])?strtotime(date('Y-m-d',strtotime('-7 days'))):strtotime($_POST['ztqs']['starttime']);
            $endtime= empty($_POST['ztqs']['endtime'])?strtotime('today'):strtotime($_POST['ztqs']['endtime']);
            $quedao=empty($_POST['ztqs']['qdone'])?'':intval($_POST['ztqs']['qdone']);
            $zquedao=empty($_POST['ztqs']['qdtwo'])?'':intval($_POST['ztqs']['qdtwo']);
            $bb=empty($_POST['ztqs']['bb'])?$this->_mrbb:intval($_POST['ztqs']['bb']);
            $bb=($bb==999)?'':$bb;
            $devnum=empty($_POST['ztqs']['devnum'])?'':intval($_POST['ztqs']['devnum']);
            $alltj['shu']=$this->payshu($starttime,$endtime,$quedao,$zquedao,$bb,$devnum);
            $this->_dayupsum=$this->newpaytype($starttime,$endtime,$timetype,$quedao,$zquedao,$bb,$devnum);

        }else{
            $inputfrom =array();
            $starttime  = strtotime(date('Y-m-d',strtotime('-7 days')));
            $endtime= strtotime('today');
            $alltj['shu']=$this->payshu($starttime,$endtime,'','','','');
            $this->_dayupsum=$this->newpaytype($starttime,$endtime,$timetype,'','',$this->_mrbb,'');

        }

        $this->render('paymoney', array (
            'res'=>$index,
            'pages'=>$page,
            'starttime'=>$starttime,
            'endtime'=>$endtime ,
            'alltj'=>$alltj,
            'searchfrom'=>$inputfrom,
            'timetype'=>$timetype
        ));

    }

    /*列出新增用户折线图*/
    private  function  newpaytype($startday,$endday,$type,$qd_b='',$qd_s='',$bb='',$dev)
    {
        /*今日*/
        $timetype=$type;//空 日期  1 小时
        $today = intval($startday);
        $theday = intval($endday);
        $listsize = ($theday - $today) / 3600 / 24;
        $channel = $qd_b;
        $channel_s = $qd_s;
        $app_version = $bb;
        $dem_num=$dev;
        $showdate = array();

        if($timetype ==1){
            for($i=0;$i<$listsize*24;$i++)
            {
                $showdate[]=date('m-d H:00',$today+$i*60*60);
            }
            $zrfsql ='SELECT
                    DATE_FORMAT(FROM_UNIXTIME(dodate),"%Y-%m-%d %H:00") as time,
                    pay_type as paytype,
                      sum(paymoney) as paymoney,
                    sum(paysum) as paysum
                FROM
                    y_wu_tjselectall
                WHERE 
                    dodate 
                BETWEEN '.$today.' and '.$theday.' 
                AND
                  (channel="'.$channel.'"  or "'.$channel.'"="") and 
                   (channel_s="'.$channel_s.'"  or "'.$channel_s.'"="") and 
                    (app_version="'.$app_version.'"  or "'.$app_version.'"="") and 
                     (dem_num="'.$dem_num.'"  or "'.$dem_num.'"="") and 
                      pay_type!=0
                     group by
                time ,paytype
                
                ';
            $zrfall=Yii::app()->db->createCommand($zrfsql)->queryAll();
            foreach ($zrfall as &$zlist){
                $zrfalls['money'][$zlist['paytype']][strtotime($zlist['time'])] = intval($zlist['paymoney']);
                $zrfalls['paysum'][$zlist['paytype']][strtotime($zlist['time'])] = $zlist['paysum'];
            }

            for ($i=0;$i<$listsize;$i++){
                $lnum= $today+$i*24*60*60;
                $listalls['tablelist'][$i]['dateall']=date('m-d',$today+$i*24*60*60);
                $listalls['tablelist'][$i]['zrfmoney']=empty(  $zrfalls['money'][1][$lnum])?'0': $zrfalls['money'][1][$lnum];
                $listalls['tablelist'][$i]['zrfuser']=empty( $zrfalls['paysum'][1][$lnum])?'0':  $zrfalls['paysum'][1][$lnum];
                $listalls['tablelist'][$i]['wxmoney']=empty( $zrfalls['money'][2][$lnum])?'0': $zrfalls['money'][2][$lnum][$lnum];
                $listalls['tablelist'][$i]['wxuser']=empty( $zrfalls['paysum'][2][$lnum])?'0':  $zrfalls['paysum'][2][$lnum];
                $listalls['tablelist'][$i]['alimoney']=empty($zrfalls['money'][3][$lnum])?'0': $zrfalls['money'][3][$lnum];
                $listalls['tablelist'][$i]['aliuser']=empty( $zrfalls['paysum'][3][$lnum])?'0': $zrfalls['paysum'][3][$lnum];

                $listalls['zxianzrfmoney'][$i]=empty(  $zrfalls['money'][1][$lnum])?'0': $zrfalls['money'][1][$lnum];
                $listalls['zxianzrfuser'][$i]=empty( $zrfalls['paysum'][1][$lnum])?'0':  $zrfalls['paysum'][1][$lnum];
                $listalls['zxianwxmoney'][$i]=empty( $zrfalls['money'][2][$lnum])?'0': $zrfalls['money'][2][$lnum][$lnum];
                $listalls['zxianwxuser'][$i]=empty( $zrfalls['paysum'][2][$lnum])?'0':  $zrfalls['paysum'][2][$lnum];
                $listalls['zxianalimoney'][$i]=empty($zrfalls['money'][3][$lnum])?'0': $zrfalls['money'][3][$lnum];
                $listalls['zxianaliuser'][$i]=empty( $zrfalls['paysum'][3][$lnum])?'0': $zrfalls['paysum'][3][$lnum];
                // $listalls['zxianfflv'][$i]=empty($dayalls['adduser'][$lnum])?'0':sprintf("%.4f",$dayalls['newpayuser'][$lnum]/$dayalls['adduser'][$lnum])*100;
                // $listalls['zxianarpu'][$i]=empty($dayalls['adduser'][$lnum])?'0':sprintf("%.2f",$dayalls['paymoney'][$lnum]/$dayalls['adduser'][$lnum]);

            }
        }else{
            for ($i = 0; $i < $listsize; $i++) {
                $showdate[]=date('m-d',$today+$i*24*60*60);
            }
            $zrfsql ='SELECT
                    DATE_FORMAT(FROM_UNIXTIME(dodate),"%Y-%m-%d") as time,
                    pay_type as paytype,
                      sum(paymoney) as paymoney,
                    sum(paysum) as paysum
                FROM
                    y_wu_tjselectall
                WHERE 
                    dodate 
                BETWEEN '.$today.' and '.$theday.' 
                AND
                  (channel="'.$channel.'"  or "'.$channel.'"="") and 
                   (channel_s="'.$channel_s.'"  or "'.$channel_s.'"="") and 
                    (app_version="'.$app_version.'"  or "'.$app_version.'"="") and 
                    (dem_num="'.$dem_num.'"  or "'.$dem_num.'"="") and 
                      pay_type!=0
                     group by
                time ,paytype
                
                ';
            $zrfall=Yii::app()->db->createCommand($zrfsql)->queryAll();
            foreach ($zrfall as &$zlist){
                $zrfalls['money'][$zlist['paytype']][strtotime($zlist['time'])] = intval($zlist['paymoney']);
                $zrfalls['paysum'][$zlist['paytype']][strtotime($zlist['time'])] = $zlist['paysum'];
            }

            for ($i=0;$i<$listsize;$i++){
                $lnum= $today+$i*24*60*60;
                $listalls['tablelist'][$i]['dateall']=date('m-d',$today+$i*24*60*60);
                $listalls['tablelist'][$i]['zrfmoney']=empty(  $zrfalls['money'][1][$lnum])?'0': $zrfalls['money'][1][$lnum];
                $listalls['tablelist'][$i]['zrfuser']=empty( $zrfalls['paysum'][1][$lnum])?'0':  $zrfalls['paysum'][1][$lnum];
                $listalls['tablelist'][$i]['wxmoney']=empty( $zrfalls['money'][2][$lnum])?'0': $zrfalls['money'][2][$lnum][$lnum];
                $listalls['tablelist'][$i]['wxuser']=empty( $zrfalls['paysum'][2][$lnum])?'0':  $zrfalls['paysum'][2][$lnum];
                $listalls['tablelist'][$i]['alimoney']=empty($zrfalls['money'][3][$lnum])?'0': $zrfalls['money'][3][$lnum];
                $listalls['tablelist'][$i]['aliuser']=empty( $zrfalls['paysum'][3][$lnum])?'0': $zrfalls['paysum'][3][$lnum];

                $listalls['zxianzrfmoney'][$i]=empty(  $zrfalls['money'][1][$lnum])?'0': $zrfalls['money'][1][$lnum];
                $listalls['zxianzrfuser'][$i]=empty( $zrfalls['paysum'][1][$lnum])?'0':  $zrfalls['paysum'][1][$lnum];
                $listalls['zxianwxmoney'][$i]=empty( $zrfalls['money'][2][$lnum])?'0': $zrfalls['money'][2][$lnum][$lnum];
                $listalls['zxianwxuser'][$i]=empty( $zrfalls['paysum'][2][$lnum])?'0':  $zrfalls['paysum'][2][$lnum];
                $listalls['zxianalimoney'][$i]=empty($zrfalls['money'][3][$lnum])?'0': $zrfalls['money'][3][$lnum];
                $listalls['zxianaliuser'][$i]=empty( $zrfalls['paysum'][3][$lnum])?'0': $zrfalls['paysum'][3][$lnum];
                // $listalls['zxianfflv'][$i]=empty($dayalls['adduser'][$lnum])?'0':sprintf("%.4f",$dayalls['newpayuser'][$lnum]/$dayalls['adduser'][$lnum])*100;
                // $listalls['zxianarpu'][$i]=empty($dayalls['adduser'][$lnum])?'0':sprintf("%.2f",$dayalls['paymoney'][$lnum]/$dayalls['adduser'][$lnum]);

            }
        }
        $this->_datelistall = $showdate;

        return $listalls;

    }
    /*付费习惯树状图*/
    private  function payshu($startday,$endday,$qd='',$zqd='',$bb='',$dev=''){
        /*今日*/
        $today =intval($startday);
        $theday = intval($endday);
        $listsize = ($theday-$today)/3600/24;

        $quedao = $qd;
        $channel_s = $zqd;
        $banben = $bb;
       // $paydo = $pay;
        $dem_num =$dev;
/*付费类型统计*/
        $moneyslq='select  
                        y_vip_log.money as money,
                         count(y_vip_log.money) as moneysum
                        from y_vip_log 
                        where 
                    (chanel_bid="'.$quedao.'"  or "'.$quedao.'"="") and 
                   (chanel_bid="'.$channel_s.'"  or "'.$channel_s.'"="") and 
                    (versionid="'.$banben.'"  or "'.$banben.'"="") and 
                     (dem_num="'.$dem_num.'"  or "'.$dem_num.'"="") and 
                      pay_type!=0 and
                            y_vip_log.`status` = 1 AND 
                         y_vip_log.add_time 
                        BETWEEN '.$today.' and '.$theday.' 
                        group by money 
                  ';
        $moneylist=Yii::app()->db->createCommand($moneyslq)->queryAll();
        foreach ($moneylist as $key=>$mnlist){
            $mvalls['money'][$key] = ($mnlist['money']<2)?'测试金额':$mnlist['money'];
            $mvalls['moneysum'][$key] = $mnlist['moneysum'];
        }

        /*付费电影统计*/
        $movieslq='select  
                        y_vip_log.last_watching as movie,
                         count(y_vip_log.last_watching) as thesum
                        from y_vip_log 
                        where 
                  (chanel_bid="'.$quedao.'"  or "'.$quedao.'"="") and 
                   (chanel_bid="'.$channel_s.'"  or "'.$channel_s.'"="") and 
                    (versionid="'.$banben.'"  or "'.$banben.'"="") and 
                     (dem_num="'.$dem_num.'"  or "'.$dem_num.'"="") and 
                            y_vip_log.`status` = 1 AND 
                         y_vip_log.add_time 
                        BETWEEN '.$today.' and '.$theday.' 
                        group by movie 
                  ';
        $mvlist=Yii::app()->db->createCommand($movieslq)->queryAll();
        foreach ($mvlist as $key=>$mlist){
            $mvalls['movie'][$key] = empty($mlist['movie'])?'未知影片':$mlist['movie'];
            $mvalls['thesum'][$key] = $mlist['thesum'];
        }
        $listalls=array();
        $listalls['mvname'] = $mvalls['movie'];
        $listalls['thesum'] = $mvalls['thesum'];
        $listalls['money'] = $mvalls['money'];
        $listalls['moneysum'] = $mvalls['moneysum'];
        return $listalls;

    }

    /*######################################################编辑的页面展示#############################################################*/
    public function  actioneditindex(){
        /*基础配备 获取 渠道 版本 支付渠道等内容*/
        $index=77;
        $this->_bbqdlist=$this->listqdbb();
        $page = intval(Yii::app()->request->getParam('page'));

        $timetype = empty($_REQUEST['timetype'])?'':intval($_REQUEST['timetype']);
        if(isset($_POST['ztqs'])){
            $inputfrom =$_POST['ztqs'];

            $starttime  = empty($_POST['ztqs']['starttime'])?strtotime(date('Y-m-d',strtotime('-7 days'))):strtotime($_POST['ztqs']['starttime']);
            $endtime= empty($_POST['ztqs']['endtime'])?strtotime('today'):strtotime($_POST['ztqs']['endtime']);
            $quedao=empty($_POST['ztqs']['qdone'])?'':intval($_POST['ztqs']['qdone']);
            $zquedao=empty($_POST['ztqs']['qdtwo'])?'':intval($_POST['ztqs']['qdtwo']);
            $bb=empty($_POST['ztqs']['bb'])?$this->_mrbb:intval($_POST['ztqs']['bb']);
            $bb=($bb==999)?'':$bb;
            $devnum=empty($_POST['ztqs']['devnum'])?'':intval($_POST['ztqs']['devnum']);
            $excel=empty($_POST['ztqs']['excel'])?'':intval($_POST['ztqs']['excel']);
            if($excel){
                $alltj=$this->editmvexcel($starttime,$endtime,$quedao,$zquedao,$bb,$devnum);
            }else{
                $alltj=$this->editmv($starttime,$endtime,$quedao,$zquedao,$bb,$devnum);
            }



        }else{
            $inputfrom =array();
            $starttime  = strtotime(date('Y-m-d',strtotime('-7 days')));
            $endtime= strtotime('today');
            $alltj=$this->editmv($starttime,$endtime,'','','','');


        }

        $this->render('editindex', array (
            'res'=>$index,
            'pages'=>$page,
            'starttime'=>$starttime,
            'endtime'=>$endtime ,
            'alltj'=>$alltj,
            'searchfrom'=>$inputfrom,
            'timetype'=>$timetype
        ));

    }
    /*付费习惯树状图*/
    private  function editmv($startday,$endday,$qd='',$zqd='',$bb='',$dev=''){
        /*今日*/
        $today =intval($startday);
        $theday = intval($endday);
        $listsize = ($theday-$today)/3600/24;

        $quedao = $qd;
        $banben = $bb;
        $channel_s = $zqd;
        $dem_num =$dev;
        /*付费电影统计*/
        $movieslq='select  
                        y_vip_log.last_watching as movie,
                         count(y_vip_log.last_watching) as thesum
                        from y_vip_log 
                        where 
                   (chanel_bid="'.$quedao.'"  or "'.$quedao.'"="") and 
                   (chanel_bid="'.$channel_s.'"  or "'.$channel_s.'"="") and 
                    (versionid="'.$banben.'"  or "'.$banben.'"="") and 
                     (dem_num="'.$dem_num.'"  or "'.$dem_num.'"="") and 
                       
                            y_vip_log.`status` = 1 AND 
                         y_vip_log.add_time 
                        BETWEEN '.$today.' and '.$theday.' 
                        group by movie  order by thesum desc
                  ';

        $mvlist=Yii::app()->db->createCommand($movieslq)->queryAll();
        foreach ($mvlist as $key=>$mlist){

            if($key<=50){
                $mvalls['movie'][$key] = empty($mlist['movie'])?'未知影片':$mlist['movie'];
                $mvalls['thesum'][$key] = $mlist['thesum'];
            }
        }
        $id=0;
        foreach ($mvlist as &$mlist){
            $id++;
            $mlist['id'] = $id;
            $mlist['movie']=empty($mlist['movie'])?'未知影片':$mlist['movie'];
        }
        $listalls=array();
        $listalls['mvname'] = $mvalls['movie'];
        $listalls['thesum'] = $mvalls['thesum'];
        $listalls['tablelist']=$mvlist;
        return $listalls;

    }
    /*付费影片导出excel*/
    private  function editmvexcel($startday,$endday,$qd='',$zqd='',$bb='',$dev=''){
        /*今日*/
        $today =intval($startday);
        $theday = intval($endday);
        $listsize = ($theday-$today)/3600/24;

        $quedao = $qd;
        $banben = $bb;
        $channel_s = $zqd;
        $dem_num =$dev;
        /*付费电影统计*/
        $movieslq='select  
                        y_vip_log.last_watching as movie,
                         count(y_vip_log.last_watching) as thesum
                        from y_vip_log 
                        where 
                   (chanel_bid="'.$quedao.'"  or "'.$quedao.'"="") and 
                   (chanel_bid="'.$channel_s.'"  or "'.$channel_s.'"="") and 
                    (versionid="'.$banben.'"  or "'.$banben.'"="") and 
                     (dem_num="'.$dem_num.'"  or "'.$dem_num.'"="") and 
                       
                            y_vip_log.`status` = 1 AND 
                         y_vip_log.add_time 
                        BETWEEN '.$today.' and '.$theday.' 
                        group by movie  order by thesum desc
                  ';
        $mvlist=Yii::app()->db->createCommand($movieslq)->queryAll();
        foreach ($mvlist as &$mlist){
            $mlist['movie']=empty($mlist['movie'])?'未知影片':$mlist['movie'];
        }
        $arr=array();

        $arr[] = array(
            array('val'=>'影片名','align'=>'center','width'=>40),
            array('val'=>'影片付费量','align'=>'center','width'=>5),
        );



        foreach($mvlist as &$v){

            $arr[] = array(
                array('val'=>''.$v['movie'].''),
                array('val'=>''.$v['thesum'].''),
            );
        }



        $objectPHPExcel = new ExcelExport('影片数据');
        foreach($arr as $val){
            $objectPHPExcel->setCells($val);
        }
        $objectPHPExcel->save();

    }
    /*按时间*/
    /*/##############################################################################################################*/
    /*渠道统计*/
    /*列出版本信息   渠道信息 */
    private  function  listkzqd(){
        $this->_qdlist_t=WuChanelTwo::listall();
        $listqdbb=array();
        $qdsql='SELECT id as id,chanle AS qdlist FROM `y_wu_chanel_one` ;';
        $qdlist=Yii::app()->db->createCommand($qdsql)->queryAll();
        $quedao=array();
        $quedao_two=array();
        $bb=array();
        $str2=$this->_lmid;
      //  $str3='%';
        foreach ($qdlist as &$list){
            $stemyes=preg_match("/$str2/", $list['qdlist'])? true: false;
            if($stemyes === true || !$str2){     //使用绝对等于
                $quedao[$list['id']]=$list['qdlist'];
                $this->_lmidall[] = $list['id'];
            }
        }

        $qdtwosql='SELECT  DISTINCT webid FROM `y_wu_tjchannellist` WHERE  bid in('.implode(',',$this->_lmidall).')';
        $qdtwolist=Yii::app()->db->createCommand($qdtwosql)->queryAll();

        //$qdtwosql='SELECT id as id,chanle_web AS twolist FROM `y_wu_chanel_two`';
        //$qdtwolist=Yii::app()->db->createCommand($qdtwosql)->queryAll();
        foreach ($qdtwolist as &$tlist){
            //if(strpos($tlist['twolist'],$str2) === false and strpos($tlist['twolist'],$str3) === false){     //使用绝对等于
                $quedao_two[$tlist['webid']]=$this->_qdlist_t[$tlist['webid']];
           // }
        }

        $qdthreesql='SELECT  DISTINCT mvid FROM `y_wu_tjchannellist` WHERE  bid in('.implode(',',$this->_lmidall).')';
        $qdthreelist=Yii::app()->db->createCommand($qdthreesql)->queryAll();
        foreach ($qdthreelist as &$slist){
            //if(strpos($slist['threelist'],$str2) === false and strpos($slist['threelist'],$str3) === false){     //使用绝对等于
                $quedao_three[$slist['mvid']]=$this->_qdlist_s[$slist['mvid']];
           // }
        }
        $bbsql='SELECT id as id,version_name as banben FROM `y_wu_version`';
        $bblist=Yii::app()->db->createCommand($bbsql)->queryAll();
        foreach ($bblist as &$blist){

            $bb[$blist['id']]=$blist['banben'];

        }
        $listqdbb['qdone']=$quedao;
        $listqdbb['qdtwo']=$quedao_two;
        $listqdbb['qdthree']=$quedao_three;
        $listqdbb['bb']=$bb;

        return $listqdbb;
    }

    public function actionkzqd(){
        /*基础配备 获取 渠道 版本 支付渠道等内容*/
        $index=20;
        $this->_kzqdlist=$this->listkzqd();
        //$this->_qdlist_t=WuChanelTwo::listall();
        $page = intval(Yii::app()->request->getParam('page'));
        $quedao =empty($_REQUEST['channel'])?'':intval($_REQUEST['channel']);
        $timetype = empty($_REQUEST['timetype'])?'':intval($_REQUEST['timetype']);

        if(isset($_POST['ztqs'])){
            $inputfrom =$_POST['ztqs'];

            $starttime  = empty($_POST['ztqs']['starttime'])?strtotime(date('Y-m-d')):strtotime($_POST['ztqs']['starttime']);
            $endtime=strtotime ("+1 day", $starttime);
            $quedao=empty($_POST['ztqs']['qdone'])?'':intval($_POST['ztqs']['qdone']);
            $qdtwo=empty($_POST['ztqs']['qdtwo'])?'':intval($_POST['ztqs']['qdtwo']);
            $qdthree=empty($_POST['ztqs']['qdthree'])?'':intval($_POST['ztqs']['qdthree']);
            $bb=empty($_POST['ztqs']['bb'])?$this->_mrbb:intval($_POST['ztqs']['bb']);
            $bb=($bb==999)?'':$bb;
            $devnum=empty($_POST['ztqs']['devnum'])?'':intval($_POST['ztqs']['devnum']);
            $pay=empty($_POST['ztqs']['pay'])?'':intval($_POST['ztqs']['pay']);
            $timeall=empty($_POST['ztqs']['timeall'])?'0':intval($_POST['ztqs']['timeall']);
            $this->_timetype = $timeall;
            $excel=empty($_POST['ztqs']['excel'])?'':intval($_POST['ztqs']['excel']);

            if($excel){
                if($starttime == strtotime(date('Y-m-d'))){
                    $this->_timetype = 3;
                    $starttime=  strtotime(date('Y-m-d'));
                    $endtime=  strtotime(date('Y-m-d',strtotime('+1 days')));
                    $this->_dayupsum=$this->Excel($starttime,$endtime,$timetype,$quedao,$qdtwo,$qdthree,$bb,$pay,$devnum);
                }else {
                    $this->_dayupsum=$this->Excel($starttime,$endtime,$timetype,$quedao,$qdtwo,$qdthree,$bb,$pay,$devnum);
                }

            }else{


                if($timeall==1){
                    $starttime=  strtotime(date('Y-m-d',strtotime('-1 days')));
                    $endtime=  strtotime(date('Y-m-d'));
                    $this->_dayupsum=$this->kzqdtj($starttime,$endtime,$timetype,$quedao,$qdtwo,$qdthree,$bb,$pay,$devnum);
                }elseif($timeall==2){
                    $starttime=  strtotime(date('Y-m-d',strtotime('-7 days')));
                    $endtime=  strtotime(date('Y-m-d',strtotime('-6 days')));
                    $this->_dayupsum=$this->kzqdtj($starttime,$endtime,$timetype,$quedao,$qdtwo,$qdthree,$bb,$pay,$devnum);
                }elseif($timeall==3) {
                    $starttime=  strtotime(date('Y-m-d'));
                    $endtime=  strtotime(date('Y-m-d',strtotime('+1 days')));
                    $this->_dayupsum=$this->kzqdtj($starttime,$endtime,$timetype,$quedao,$qdtwo,$qdthree,$bb,$pay,$devnum);
                }else{
                    if($starttime == strtotime(date('Y-m-d'))){
                        $this->_timetype = 3;
                        $starttime=  strtotime(date('Y-m-d'));
                        $endtime=  strtotime(date('Y-m-d',strtotime('+1 days')));
                        $this->_dayupsum=$this->kzqdtj($starttime,$endtime,$timetype,$quedao,$qdtwo,$qdthree,$bb,$pay,$devnum);
                    }else {
                        $this->_dayupsum=$this->kzqdtj($starttime,$endtime,$timetype,$quedao,$qdtwo,$qdthree,$bb,$pay,$devnum);
                    }

                }
            }

        }else{
            $this->_timetype =3;
            $inputfrom =array();
            $starttime=  strtotime(date('Y-m-d'));
            $endtime=  strtotime(date('Y-m-d',strtotime('+1 days')));
            $this->_dayupsum=$this->kzqdtj($starttime,$endtime,$timetype,$quedao,'',$this->_mrbb,'','');

        }
        $this->render('kzqd', array (
            'res'=>$index,
            'pages'=>$page,
            'quedao'=>$quedao,
            'starttime'=>$starttime,
            'endtime'=>$endtime ,
            'searchfrom'=>$inputfrom,
            'timetype'=>$timetype
        ));

    }
    /*列出整体趋势的统计*/
    private  function  kzqdtj($startday,$endday,$type,$qd_b='',$qd_t='',$qd_s='',$bb='',$paytype='',$dev)
    {
        /*今日*/
        $timetype=$type;//空 日期  1 小时
        $today = intval($startday);
        $theday = intval($endday);
        $listsize = ($theday - $today) / 3600 / 24;
        $channel = empty($qd_b)?'':$qd_b;
        $channel_t = $qd_t;
        $channel_s = $qd_s;
        $app_version = $bb;
        $dem_num = $dev;
        $pay_type =empty($paytype)?'0':intval($paytype);
        $showdate = array();

        if($this->_timetype ==3 ){
            $sql = 'SELECT
channel,
                    channel_s,
                      channel_t,
                    sum(newadduser) as adduser,
                     sum(adduser) as oldadduser,
                    sum(openuser) as openuser,
                    sum(newopenuser) as newopenuser,
                    sum(payuser) as payuser,
                    sum(newpayuser) as newpayuser,
                    sum(paymoney) as paymoney
                FROM
                    y_wu_tjselectday
                WHERE 
                    dodate 
                BETWEEN ' . $today . ' and ' . $theday . ' 
                AND
                (channel="' . $channel . '"  or "' . $channel . '"="") and 
                 (channel_t="' . $channel_t . '"  or "' . $channel_t . '"="") and 
                  (channel_s="' . $channel_s . '"  or "' . $channel_s . '"="") and 
                    (app_version="' . $app_version . '"  or "' . $app_version . '"="") and 
                     (dem_num="' . $dem_num . '"  or "' . $dem_num . '"="") and 
                     (pay_type="' . $pay_type . '"  or "' . $pay_type . '"="")
                     group by
                channel,channel_s,channel_t
                
                ';

            $ztqsall = Yii::app()->db->createCommand($sql)->queryAll();
            $qdlist_all=array();
            $i = 0;
            foreach ($ztqsall as $key=>$ulist) {

                //var_dump(in_array($ulist['channel'],$this->_lmidall));
                if(in_array($ulist['channel'],$this->_lmidall)){

                    //var_dump($this->_qdlist[$ulist['channel']]);
                   // var_dump();

                    $qdlist_all[$i]['channel_all'] = $this->_qdlist[$ulist['channel']] . '-' .$this->_qdlist_t[$ulist['channel_t']] . '-' . $this->_qdlist_s[$ulist['channel_s']];
                    $qdlist_all[$i]['adduser'] = $ulist['adduser'];
                    $qdlist_all[$i]['oldadduser'] = $ulist['oldadduser'];
                    $qdlist_all[$i]['openuser'] = $ulist['openuser'];
                    $qdlist_all[$i]['newopenuser'] = $ulist['newopenuser'];
                    $qdlist_all[$i]['payuser'] = $ulist['payuser'];
                    $qdlist_all[$i]['newpayuser'] = $ulist['newpayuser'];

                    $qdlist_all[$i]['paymoney'] = intval($ulist[$key]['paymoney']);
                    $qdlist_all[$i]['fflv'] = empty($ulist['adduser']) ? '0' : sprintf("%.4f", ($ulist['newpayuser'] / $ulist['adduser']) * 100);
                    $qdlist_all[$i]['arpu'] = empty($ulist['adduser']) ? '0' : sprintf("%.2f", $ulist['paymoney'] / $ulist['adduser']);
                    $i++;
                }

            }


        }else {
            for ($i = 0; $i < $listsize; $i++) {
                $showdate[] = date('m-d', $today + $i * 24 * 60 * 60);
            }
            $sql = 'SELECT
channel,
                    channel_s,
                      channel_t,
                    sum(newadduser) as adduser,
                    sum(openuser) as openuser,
                     sum(adduser) as oldadduser,
                    sum(newopenuser) as newopenuser,
                    sum(payuser) as payuser,
                    sum(newpayuser) as newpayuser,
                    sum(paymoney) as paymoney
                FROM
                    y_wu_tjselectall
                WHERE 
                    dodate 
                BETWEEN ' . $today . ' and ' . $theday . '-1 
                AND
                (channel="' . $channel . '"  or "' . $channel . '"="") and 
                 (channel_t="' . $channel_t . '"  or "' . $channel_t . '"="") and 
                  (channel_s="' . $channel_s . '"  or "' . $channel_s . '"="") and 
                    (app_version="' . $app_version . '"  or "' . $app_version . '"="") and 
                      (dem_num="' . $dem_num . '"  or "' . $dem_num . '"="") and 
                     (pay_type="' . $pay_type . '"  or "' . $pay_type . '"="")
                     group by
               channel, channel_s,channel_t
                
                ';
            $ztqsall = Yii::app()->db->createCommand($sql)->queryAll();
            $qdlist_all=array();
            $i = 0;
            foreach ($ztqsall as $key=>$ulist) {

                //var_dump(in_array($ulist['channel'],$this->_lmidall));
                if(in_array($ulist['channel'],$this->_lmidall)){

                    //var_dump($this->_qdlist[$ulist['channel']]);
                    // var_dump();

                    $qdlist_all[$i]['channel_all'] = $this->_qdlist[$ulist['channel']] . '-' .$this->_qdlist_t[$ulist['channel_t']] . '-' . $this->_qdlist_s[$ulist['channel_s']];
                    $qdlist_all[$i]['adduser'] = $ulist['adduser'];
                    $qdlist_all[$i]['oldadduser'] = $ulist['oldadduser'];
                    $qdlist_all[$i]['openuser'] = $ulist['openuser'];
                    $qdlist_all[$i]['newopenuser'] = $ulist['newopenuser'];
                    $qdlist_all[$i]['payuser'] = $ulist['payuser'];
                    $qdlist_all[$i]['newpayuser'] = $ulist['newpayuser'];

                    $qdlist_all[$i]['paymoney'] = intval($ulist[$key]['paymoney']);
                    $qdlist_all[$i]['fflv'] = empty($ulist['adduser']) ? '0' : sprintf("%.4f", ($ulist['newpayuser'] / $ulist['adduser']) * 100);
                    $qdlist_all[$i]['arpu'] = empty($ulist['adduser']) ? '0' : sprintf("%.2f", $ulist['paymoney'] / $ulist['adduser']);
                    $i++;
                }

            }

        }


        $this->_datelistall = $showdate;
        $listalls['tablelist'] = $qdlist_all;

        return $listalls;

    }


    /*%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%拓展汇总渠道数据%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%*/
    public function actionkzz(){
        /*基础配备 获取 渠道 版本 支付渠道等内容*/
        $index=111;
        $this->_kzqdlist=$this->listkzqd();
        $page = intval(Yii::app()->request->getParam('page'));

        $timetype = empty($_REQUEST['timetype'])?'':intval($_REQUEST['timetype']);
        if(isset($_POST['ztqs'])){
            $inputfrom =$_POST['ztqs'];

            $starttime  = empty($_POST['ztqs']['starttime'])?strtotime(date('Y-m-d',strtotime('-7 days'))):strtotime($_POST['ztqs']['starttime']);
            $endtime= empty($_POST['ztqs']['endtime'])?strtotime('today'):strtotime($_POST['ztqs']['endtime']);
            $quedao=empty($_POST['ztqs']['qdone'])?'':intval($_POST['ztqs']['qdone']);
            $zquedao=empty($_POST['ztqs']['qdtwo'])?'':intval($_POST['ztqs']['qdtwo']);
            $bb=empty($_POST['ztqs']['bb'])?$this->_mrbb:intval($_POST['ztqs']['bb']);
            $bb=($bb==999)?'':$bb;
            $devnum=empty($_POST['ztqs']['devnum'])?'':intval($_POST['ztqs']['devnum']);
            $pay=empty($_POST['ztqs']['pay'])?'':intval($_POST['ztqs']['pay']);

            $timeall=empty($_POST['ztqs']['timeall'])?'0':intval($_POST['ztqs']['timeall']);
            $this->_timetype = $timeall;


            if($timeall==1){
                $starttime=  strtotime(date('Y-m-d',strtotime('-1 days')));
                $endtime=  strtotime(date('Y-m-d'));
                $this->_dayupsum=$this->newkzz($starttime,$endtime,$timetype,$bb,$pay,$page,$devnum);
            }elseif($timeall==2){
                $sevendate=  strtotime(date('Y-m-d',strtotime('-7 days')));
                $sevendate2=  strtotime(date('Y-m-d',strtotime('-6 days')));
                $this->_dayupsum=$this->newkzz($sevendate,$sevendate2,$timetype,$bb,$pay,$page,$devnum);
            }elseif($timeall==3){
                $starttime=  strtotime(date('Y-m-d'));
                $endtime=  strtotime(date('Y-m-d',strtotime('+1 days')));
                $this->_dayupsum=$this->newkzz($starttime,$endtime,$timetype,$bb,$pay,$page,$devnum);
            }else{
                if($starttime == strtotime(date('Y-m-d'))){
                    $this->_timetype = 3;
                    $starttime=  strtotime(date('Y-m-d'));
                    $endtime=  strtotime(date('Y-m-d',strtotime('+1 days')));
                    $this->_dayupsum = $this->newkzz($starttime, $endtime, $timetype, $bb, $pay, $page,$devnum);
                }else {
                    $this->_dayupsum = $this->newkzz($starttime, $endtime, $timetype, $bb, $pay, $page,$devnum);
                }
            }
        }else{
            $inputfrom =array();
            $this->_timetype = 0;
            $starttime  = strtotime(date('Y-m-d',strtotime('-7 days')));
            $endtime= strtotime('today');
            $this->_dayupsum=$this->newkzz($starttime,$endtime,$timetype,$this->_mrbb,'',$page,'');

        }

        $this->render('kzz', array (
            'res'=>$index,
            'pages'=>$page,
            'starttime'=>$starttime,
            'endtime'=>$endtime ,
            'searchfrom'=>$inputfrom,
            'timetype'=>$timetype
        ));

    }
    /*列出整体趋势的统计*/
    private  function  newkzz($startday,$endday,$type,$bb='',$paytype='',$page,$dev)
    {
        /*今日*/
        $timetype=$type;//空 日期  1 小时
        $today = intval($startday);
        $theday = intval($endday);
        $listsize = ($theday - $today) / 3600 / 24;
        $app_version = $bb;
        $dem_num =$dev;
        $pay_type =empty($paytype)?'0':intval($paytype);
        $pages =empty($page)?'0':intval($page);
        $showdate = array();

        for($i=0;$i<$listsize;$i++)
        {
            $showdate[]=date('m-d',$today+$i*24*60*60);
        }

        if($this->_timetype ==3 ){
            /*所有渠道数据*/
            $qdsql = 'SELECT
                    channel,
                    sum(newadduser) as adduser,
                     sum(adduser) as oldadduser,
                    sum(openuser) as openuser,
                    sum(newopenuser) as newopenuser,
                    sum(payuser) as payuser,
                    sum(newpayuser) as newpayuser,
                    sum(paymoney) as paymoney
                FROM
                    y_wu_tjselectday
                WHERE 
                    dodate 
                BETWEEN ' . $today . ' and ' . $theday . ' 
                AND
                    (app_version="' . $app_version . '"  or "' . $app_version . '"="") and 
                     (dem_num="' . $dem_num . '"  or "' . $dem_num . '"="") and 
                     (pay_type="' . $pay_type . '"  or "' . $pay_type . '"="")
                     group by
                channel
                
                ';
            $qdlistall = Yii::app()->db->createCommand($qdsql)->queryAll();

            /*获取top10的渠道数据*/
            if ($pages) {

                if ($pages == 1) {
                    $sqltj = 'openuser';
                    $sqldo = 'sum(openuser)  as ';
                } else if ($pages == 2) {
                    $sqltj = 'payuser';
                    $sqldo = 'sum(payuser)  as ';
                } else if ($pages == 3) {
                    $sqltj = 'newpayuser';
                    $sqldo = 'sum(newpayuser)  as ';
                } else if ($pages == 4) {
                    $sqltj = 'paymoney';
                    $sqldo = 'sum(paymoney)  as ';
                } elseif ($pages == 5) {
                    $sqltj = 'fflv';
                    $sqldo = 'truncate(sum(newpayuser)/sum(newadduser)*100,2)  as ';
                } elseif ($pages == 6) {
                    $sqltj = 'arpu';
                    $sqldo = 'truncate(sum(paymoney)/sum(newadduser),2)  as ';

                }elseif($pages == 7){
                    $sqltj = 'oldadduser';
                    $sqldo = 'sum(adduser) as ';
                }elseif($pages == 8){
                    $sqltj = 'newopenuser';
                    $sqldo = 'sum(newopenuser)  as ';
                }else {
                    $sqltj = 'adduser';
                    $sqldo = 'sum(newadduser) as ';
                }

            } else {
                $sqltj = 'adduser';
                $sqldo = 'sum(newadduser) as ';
            }
            /*新增用户*/
            $addusersql = 'SELECT 
                        channel ,
                        DATE_FORMAT(FROM_UNIXTIME(dodate),"%Y-%m-%d") as time,
                         ' . $sqldo . '  ' . $sqltj . '
                        from 
                        y_wu_tjselectday b 
                        where  	dodate 
                                    BETWEEN ' . $today . ' and ' . $theday . ' 
                                     and
                                  (app_version="' . $app_version . '"  or "' . $app_version . '"="") and 
                                    (pay_type="' . $pay_type . '"  or "' . $pay_type . '"="") and 
                        exists(SELECT 
                                            channel
                                    FROM 
                                    (
                                    SELECT
                                            channel,
                                            DATE_FORMAT(FROM_UNIXTIME(dodate),"%Y-%m-%d") as time,
                                            ' . $sqldo . '  ' . $sqltj . '
                                    FROM
                                    y_wu_tjselectday
                                    WHERE
                                            dodate 
                                     BETWEEN ' . $today . ' and ' . $theday . ' 
                                      AND
                    (app_version="' . $app_version . '"  or "' . $app_version . '"="") and 
                     (dem_num="' . $dem_num . '"  or "' . $dem_num . '"="") and 
                     (pay_type="' . $pay_type . '"  or "' . $pay_type . '"="")
                                    GROUP BY channel ORDER BY ' . $sqltj . ' desc limit 10
                                    )t where channel = b.channel)
                        GROUP BY channel';


            $toptenall = Yii::app()->db->createCommand($addusersql)->queryAll();
        }else {
            /*所有渠道数据*/
            $qdsql = 'SELECT
                    channel,
                    sum(newadduser) as adduser,
                    sum(adduser) as oldadduser,
                    sum(openuser) as openuser,
                    sum(newopenuser) as newopenuser,
                    sum(payuser) as payuser,
                    sum(newpayuser) as newpayuser,
                    sum(paymoney) as paymoney
                FROM
                    y_wu_tjselectall
                WHERE 
                    dodate 
                BETWEEN ' . $today . ' and ' . $theday . '-1 
                AND
                    (app_version="' . $app_version . '"  or "' . $app_version . '"="") and 
                     (dem_num="' . $dem_num . '"  or "' . $dem_num . '"="") and 
                     (pay_type="' . $pay_type . '"  or "' . $pay_type . '"="")
                     group by
                channel
                
                ';
            $qdlistall = Yii::app()->db->createCommand($qdsql)->queryAll();

            /*获取top10的渠道数据*/
            if ($pages) {

                if ($pages == 1) {
                    $sqltj = 'openuser';
                    $sqldo = 'sum(openuser)  as ';
                } else if ($pages == 2) {
                    $sqltj = 'payuser';
                    $sqldo = 'sum(payuser)  as ';
                } else if ($pages == 3) {
                    $sqltj = 'newpayuser';
                    $sqldo = 'sum(newpayuser)  as ';
                } else if ($pages == 4) {
                    $sqltj = 'paymoney';
                    $sqldo = 'sum(paymoney)  as ';
                } elseif ($pages == 5) {
                    $sqltj = 'fflv';
                    $sqldo = 'truncate(sum(newpayuser)/sum(newadduser)*100,2)  as ';
                } elseif ($pages == 6) {
                    $sqltj = 'arpu';
                    $sqldo = 'truncate(sum(paymoney)/sum(newadduser),2)  as ';

                }elseif($pages == 7){
                    $sqltj = 'oldadduser';
                    $sqldo = 'sum(adduser) as ';
                }elseif($pages == 8){
                    $sqltj = 'newopenuser';
                    $sqldo = 'sum(newopenuser)  as ';
                } else {
                    $sqltj = 'adduser';
                    $sqldo = 'sum(newadduser) as ';
                }

            } else {
                $sqltj = 'adduser';
                $sqldo = 'sum(newadduser) as ';
            }
            /*新增用户*/
            $addusersql = 'SELECT 
                        channel ,
                        DATE_FORMAT(FROM_UNIXTIME(dodate),"%Y-%m-%d") as time,
                         ' . $sqldo . '  ' . $sqltj . '
                        from 
                        y_wu_tjselectall b 
                        where  	dodate 
                                    BETWEEN ' . $today . ' and ' . $theday . ' 
                                     and
                                  (app_version="' . $app_version . '"  or "' . $app_version . '"="") and 
                                    (pay_type="' . $pay_type . '"  or "' . $pay_type . '"="") and 
                        exists(SELECT 
                                            channel
                                    FROM 
                                    (
                                    SELECT
                                            channel,
                                            DATE_FORMAT(FROM_UNIXTIME(dodate),"%Y-%m-%d") as time,
                                            ' . $sqldo . '  ' . $sqltj . '
                                    FROM
                                    y_wu_tjselectall
                                    WHERE
                                            dodate 
                                     BETWEEN ' . $today . ' and ' . $theday . ' 
                                      AND
                    (app_version="' . $app_version . '"  or "' . $app_version . '"="") and 
                     (dem_num="' . $dem_num . '"  or "' . $dem_num . '"="") and 
                     (pay_type="' . $pay_type . '"  or "' . $pay_type . '"="")
                                    GROUP BY channel ORDER BY ' . $sqltj . ' desc limit 10
                                    )t where channel = b.channel)
                        GROUP BY channel,time ';


            $toptenall = Yii::app()->db->createCommand($addusersql)->queryAll();
        }
        foreach ($toptenall as $key=> $ulist){
           // var_dump(in_array($ulist['channel'],$this->_lmidall);
            if(in_array($ulist['channel'],$this->_lmidall)) {
                //var_dump($ulist['channel']);
                $dayalls[$ulist['channel']][strtotime($ulist['time'])] = $ulist[$sqltj];
                $listqd[$ulist['channel']] = $ulist['channel'];
            }
        }

        foreach ($listqd as $key=>$list){

            for ($i=0;$i<$listsize;$i++){
                $lnum= $today+$i*24*60*60;
                if($dayalls[$list][$lnum]){
                    $listalls[$list][$lnum]=$dayalls[$list][$lnum];
                }else{
                    $listalls[$list][$lnum]=0;
                }
            }
            $qdalls[]= $this->_qdlist[$key];
        }
        foreach($listalls as $key=>$llist){
            $cs[$key] = array_values($llist);
        }
        $qdlist_all=array();
        $i = 0;
        foreach($qdlistall as & $qlist){
            if(in_array($qlist['channel'],$this->_lmidall)) {
                $qdlist_all[$i]['channel'] = $this->_qdlist[$qlist['channel']];
                $qdlist_all[$i]['adduser'] = $qlist['adduser'];
                $qdlist_all[$i]['oldadduser'] = $qlist['oldadduser'];
                $qdlist_all[$i]['openuser'] = $qlist['openuser'];
                $qdlist_all[$i]['newopenuser'] = $qlist['newopenuser'];
                $i++;
            }
        }
        $qdlist['qd']=$qdalls;
        $qdlist['zxlist'] =  $cs;
        $qdlist['qdlist'] =  $qdlist_all;
        $this->_datelistall = $showdate;


        return $qdlist;

    }



    /**
	* create a particular model.
	* If create is successful, the browser will be redirected to the 'index' page.
	* @param integer $id the ID of the model to be created
	*/
	public function actionCreate()
	{
		$model=new WuTjuserall;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['WuTjuserall']))
		{
			$model->attributes=$_POST['WuTjuserall'];
			$model->createtime=time();
			$model->updatetime=time();
			if($model->save())
				$this->redirect(array('index'));
		}

		$this->render('create',array(
			'model'=>$model,
		));
	}

    private function  Excel($startday,$endday,$type,$qd_b='',$qd_t='',$qd_s='',$bb='',$paytype='',$dev='')
    {
        /*今日*/
        $timetype=$type;//空 日期  1 小时
        $today = intval($startday);
        $theday = intval($endday);
        $listsize = ($theday - $today) / 3600 / 24;
        $channel = empty($qd_b)?'':$qd_b;
        $channel_t = $qd_t;
        $channel_s = $qd_s;
        $app_version = $bb;
        $dem_num = $dev;
        $pay_type =empty($paytype)?'0':intval($paytype);
        $showdate = array();


        for ($i = 0; $i < $listsize; $i++) {
            $showdate[]=date('m-d',$today+$i*24*60*60);
        }
        if($this->_timetype ==3 ){
            $sql ='SELECT
                    channel,
                    channel_s,
                      channel_t,
                    sum(newadduser) as adduser,
                    sum(newopenuser) as newopenuser,
                    sum(adduser) as oldadduser,
                    sum(openuser) as openuser
                   
                FROM
                    y_wu_tjselectday
                WHERE 
                    dodate 
                BETWEEN '.$today.' and '.$theday.' 
                AND
                (channel="'.$channel.'"  or "'.$channel.'"="") and 
                 (channel_t="'.$channel_t.'"  or "'.$channel_t.'"="") and 
                  (channel_s="'.$channel_s.'"  or "'.$channel_s.'"="") and 
                    (app_version="'.$app_version.'"  or "'.$app_version.'"="") and 
                     (dem_num="'.$dem_num.'"  or "'.$dem_num.'"="") and 
                     (pay_type="'.$pay_type.'"  or "'.$pay_type.'"="")
                     group by
                channel,channel_s,channel_t
                
                ';
        }else{
            $sql ='SELECT
                    channel,
                    channel_s,
                      channel_t,
                    sum(newadduser) as adduser,
                    sum(newopenuser) as newopenuser,
                    sum(adduser) as oldadduser,
                    sum(openuser) as openuser
                   
                FROM
                    y_wu_tjselectall
                WHERE 
                    dodate 
                BETWEEN '.$today.' and '.$theday.'-1 
                AND
                (channel="'.$channel.'"  or "'.$channel.'"="") and 
                 (channel_t="'.$channel_t.'"  or "'.$channel_t.'"="") and 
                  (channel_s="'.$channel_s.'"  or "'.$channel_s.'"="") and 
                    (app_version="'.$app_version.'"  or "'.$app_version.'"="") and 
                      (dem_num="'.$dem_num.'"  or "'.$dem_num.'"="") and 
                     (pay_type="'.$pay_type.'"  or "'.$pay_type.'"="")
                     group by
               channel, channel_s,channel_t
                
                ';
        }

        $ztqsall=Yii::app()->db->createCommand($sql)->queryAll();
        $qdlist_all=array();
        $i = 0;
        foreach ($ztqsall as $key=>$ulist) {
            if(in_array($ulist['channel'],$this->_lmidall)){
                $qdlist_all[$i]['channel_all'] = $this->_qdlist[$ulist['channel']] . '-' .$this->_qdlist_t[$ulist['channel_t']] . '-' . $this->_qdlist_s[$ulist['channel_s']];
                $qdlist_all[$i]['adduser'] = $ulist['adduser'];
                $qdlist_all[$i]['oldadduser'] = $ulist['oldadduser'];
                $qdlist_all[$i]['openuser'] = $ulist['openuser'];
                $qdlist_all[$i]['newopenuser'] = $ulist['newopenuser'];

                $i++;
            }

        }
//        foreach ($ztqsall as &$ulist){
//            $ulist['channel_all'] = $this->_qdlist[$ulist['channel']].'-'.$this->_qdlist_t[$ulist['channel_t']].'-'.$this->_qdlist_s[$ulist['channel_s']];
//
//        }

//        $sql='SELECT * FROM y_wu_app as apps LEFT JOIN (SELECT y_wu_bigtype.typename as bigname,y_wu_bigtype.id as bigid FROM y_wu_bigtype) as big ON  apps.appbigtypeid = big.bigid LEFT JOIN (SELECT y_wu_type.id as smid, y_wu_type.s_typename as smname FROM y_wu_type)as sm ON apps.appsmtypeid = sm.smid';
//        $sresult=Yii::app()->db->createCommand($sql)->queryAll();

        $arr=array();

        $arr[] = array(
            array('val'=>'渠道名','align'=>'center','width'=>40),
            array('val'=>'新增用户','align'=>'center','width'=>5),
            array('val'=>'活跃用户','align'=>'center','width'=>10),
            array('val'=>'老版新增用户','align'=>'center','width'=>5),
            array('val'=>'老版活跃用户','align'=>'center','width'=>10),
        );



        foreach($qdlist_all as &$v){

            $arr[] = array(
                array('val'=>''.$v['channel_all'].''),
                array('val'=>''.$v['adduser'].''),
                array('val'=>''.$v['newopenuser'].''),
                array('val'=>''.$v['oldadduser'].''),
                array('val'=>''.$v['openuser'].''),
            );
        }



        $objectPHPExcel = new ExcelExport('渠道数据');
        foreach($arr as $val){
            $objectPHPExcel->setCells($val);
        }
        $objectPHPExcel->save();
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

		if(isset($_POST['WuTjuserall']))
		{
			$model->attributes=$_POST['WuTjuserall'];
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
				WuTjuserall::model()->deleteAll($criteria);
				break;
			case 'show':
			//显示
				WuTjuserall::model()->updateAll(array('status' => 1), $criteria);
				break;
			case 'hide':
			//隐藏
				WuTjuserall::model()->updateAll(array('status' => 0), $criteria);
				break;
			case 'sortOrder':
				$sortOrder = $_POST['order'];
				foreach((array)$ids as $id){
					$catalogModel = WuTjuserall::model()->findByPk($id);
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
	 * @return WuTjuserall the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=WuTjuserall::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param WuTjuserall $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='wu-tjuserall-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
