<?php

class MtjController extends FrontBase
{
    //初始化函数
    public $_hoursall;
    public $_newupsum;
    public $_dayupsum;
    public $_ztupsum;
    public $_datelistall;
    public $_bbqdlist;
    public $_qdlist;
    public function init()
    {
        parent::init();
        if (!Helper::isMobile())
        {

            $this->redirect('http://tjnew.pingoula.net/?r=admin');
        }else{
            if (!$_SESSION['admin__id']) {
                $this->redirect('http://tjnew.pingoula.net/?r=admin');
            }

        }
        if ($_SESSION['adminnickname'] =="lmadmin") {

            echo '统计后台无手机端！请使用电脑查看';
            exit();
        }
        $this->_hoursall=array(
            '00:00','01:00','02:00','03:00','04:00','05:00','06:00','07:00','08:00','09:00',
            '10:00','11:00','12:00','13:00','14:00','15:00','16:00','17:00','18:00','19:00',
            '20:00','21:00','22:00','23:00',
        );

        $this->layout = 'shop.views.layouts.main';



    }


    public function actionmtj(){
        /*基础配备 获取 渠道 版本 支付渠道等内容*/
        $index=1;
        $page = intval(Yii::app()->request->getParam('page'));

        $timetype = empty($_REQUEST['timetype'])?'':intval($_REQUEST['timetype']);
        if(isset($_POST['ztqs'])){
            $inputfrom =$_POST['ztqs'];

            $starttime  = empty($_POST['ztqs']['starttime'])?strtotime(date('Y-m-d',strtotime('-7 days'))):strtotime($_POST['ztqs']['starttime']);
            $endtime= empty($_POST['ztqs']['endtime'])?strtotime('today'):strtotime($_POST['ztqs']['endtime']);
            $quedao=empty($_POST['ztqs']['qdone'])?'':intval($_POST['ztqs']['qdone']);
            $zquedao=empty($_POST['ztqs']['qdtwo'])?'':intval($_POST['ztqs']['qdtwo']);
            $bb=empty($_POST['ztqs']['bb'])?'':intval($_POST['ztqs']['bb']);
            $pay=empty($_POST['ztqs']['pay'])?'':intval($_POST['ztqs']['pay']);
            $this->_dayupsum=$this->newindextj($starttime,$endtime,$timetype,$quedao,$zquedao,$bb,$pay,$page);

        }else{
            $inputfrom =array();
            $starttime  = strtotime(date("Y-m-d")) ;
            $endtime= strtotime(date("Y-m-d",strtotime("+1 day")));
            $this->_dayupsum=$this->newindextj($starttime,$endtime,$timetype,'','','','',$page);

        }

        $this->render('mtj', array (
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
    private  function  newindextj($startday,$endday,$type,$qd_b='',$qd_s='',$bb='',$paytype='',$page)
    {
        /*今日*/
        $timetype=$type;//空 日期  1 小时
        $today = intval($startday);
        $theday = intval($endday);
        $listsize = ($theday - $today) / 3600 / 24;
        $channel = $qd_b;
        $channel_s = $qd_s;
        $app_version = $bb;
        $pay_type =empty($paytype)?'0':intval($paytype);
        $pages =empty($page)?'0':intval($page);

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
        $listalls['tablelist'][0]['fflv']=empty($dayalls['adduser'][0])?'0':sprintf("%.4f",$dayalls['newpayuser'][0]/$dayalls['adduser'][0])*100;
        $listalls['tablelist'][0]['arpu']=empty($dayalls['adduser'][0])?'0':sprintf("%.2f",$dayalls['paymoney'][0]/$dayalls['adduser'][0]);
        /*昨天数据*/
        $listalls['tablelist'][0]['zarpu']=empty($zdayalls['zadduser'][0])?'0':sprintf("%.2f",$zdayalls['zpaymoney'][0]/$zdayalls['zadduser'][0]);

        /*7天前数据*/
        $listalls['tablelist'][0]['sarpu']=empty($sdayalls['sadduser'][0])?'0':sprintf("%.2f",$sdayalls['spaymoney'][0]/$sdayalls['sadduser'][0]);
        $listalls['tablelist'][0]['adduser']= $dayalls['adduser'][0];
        $listalls['tablelist'][0]['openuser']= $dayalls['openuser'][0];
        $listalls['tablelist'][0]['payuser']= $dayalls['payuser'][0];
        $listalls['tablelist'][0]['newpayuser']= $dayalls['newpayuser'][0];
        $listalls['tablelist'][0]['paymoney']= $dayalls['paymoney'][0];
        $listalls['tablelist'][0]['dateall']='总计';
        $listalls['tablelist'][0]['ffanduser']= $listalls['tablelist'][0]['payuser'].'('.$listalls['tablelist'][0]['fflv'].'%)';
        for ($i=0;$i<$listsize*24;$i++){
            $lnum= $today+$i*60*60;
            $zlum= $zuodate+$i*60*60;
            $slum= $sevendate+$i*60*60;

            $listalls['tablelist'][$i+1]['dateall']=date('H',$today+$i*60*60);
            $listalls['tablelist'][$i+1]['adduser']=empty( $dayalls['adduser'][$lnum])?'0': $dayalls['adduser'][$lnum];
            $listalls['tablelist'][$i+1]['openuser']=empty( $dayalls['openuser'][$lnum])?'0': $dayalls['openuser'][$lnum];
            $listalls['tablelist'][$i+1]['payuser']=empty( $dayalls['payuser'][$lnum])?'0': $dayalls['payuser'][$lnum];
            $listalls['tablelist'][$i+1]['newpayuser']=empty( $dayalls['newpayuser'][$lnum])?'0': $dayalls['newpayuser'][$lnum];
            $listalls['tablelist'][$i+1]['paymoney']=empty( $dayalls['paymoney'][$lnum])?'0': $dayalls['paymoney'][$lnum];
            $listalls['tablelist'][$i+1]['fflv']=empty($dayalls['adduser'][$lnum])?'0':sprintf("%.4f",$dayalls['newpayuser'][$lnum]/$dayalls['adduser'][$lnum])*100;
            $listalls['tablelist'][$i+1]['arpu']=empty($dayalls['adduser'][$lnum])?'0':sprintf("%.2f",$dayalls['paymoney'][$lnum]/$dayalls['adduser'][$lnum]);
            /*昨天数据*/
            $listalls['tablelist'][$i+1]['zarpu']=empty($zdayalls['zadduser'][$zlum])?'0':sprintf("%.2f",$zdayalls['zpaymoney'][$zlum]/$zdayalls['zadduser'][$zlum]);

            /*7天前数据*/
            $listalls['tablelist'][$i+1]['sarpu']=empty($sdayalls['sadduser'][$slum])?'0':sprintf("%.2f",$sdayalls['spaymoney'][$slum]/$sdayalls['sadduser'][$slum]);

            $listalls['tablelist'][$i+1]['ffanduser']= $listalls['tablelist'][$i+1]['payuser'].'('.$listalls['tablelist'][$i+1]['fflv'].'%)';


            if($pages){

                if($pages ==1){
                    $listalls['zxtu']['theday'][$i]=empty( $dayalls['openuser'][$lnum])?'0':$dayalls['openuser'][$lnum];
                    $listalls['zxtu']['today'][$i]=empty( $zdayalls['zopenuser'][$zlum])?'0':$zdayalls['zopenuser'][$zlum];
                    $listalls['zxtu']['sevenday'][$i]=empty( $sdayalls['sopenuser'][$slum])?'0':$sdayalls['sopenuser'][$slum];
                }else if($pages ==2){
                    $listalls['zxtu']['theday'][$i]=empty( $dayalls['payuser'][$lnum])?'0':$dayalls['payuser'][$lnum];
                    $listalls['zxtu']['today'][$i]=empty( $zdayalls['zpayuser'][$zlum])?'0':$zdayalls['zpayuser'][$zlum];
                    $listalls['zxtu']['sevenday'][$i]=empty( $sdayalls['spayuser'][$slum])?'0':$sdayalls['spayuser'][$slum];
                }else if($pages ==3){
                    $listalls['zxtu']['theday'][$i]=empty( $dayalls['newpayuser'][$lnum])?'0':$dayalls['newpayuser'][$lnum];
                    $listalls['zxtu']['today'][$i]=empty( $zdayalls['znewpayuser'][$zlum])?'0':$zdayalls['znewpayuser'][$zlum];
                    $listalls['zxtu']['sevenday'][$i]=empty( $sdayalls['snewpayuser'][$slum])?'0':$sdayalls['snewpayuser'][$slum];
                }else if($pages ==4){
                    $listalls['zxtu']['theday'][$i]=empty( $dayalls['paymoney'][$lnum])?'0':$dayalls['paymoney'][$lnum];
                    $listalls['zxtu']['today'][$i]=empty( $zdayalls['zpaymoney'][$zlum])?'0':$zdayalls['zpaymoney'][$zlum];
                    $listalls['zxtu']['sevenday'][$i]=empty( $sdayalls['spaymoney'][$slum])?'0':$sdayalls['spaymoney'][$slum];
                }elseif($pages == 5){
                    $listalls['zxtu']['theday'][$i]=empty($dayalls['adduser'][$lnum])?'0':sprintf("%.4f",$dayalls['newpayuser'][$lnum]/$dayalls['adduser'][$lnum])*100;
                    $listalls['zxtu']['today'][$i]=empty($zdayalls['zadduser'][$zlum])?'0':sprintf("%.4f",$zdayalls['znewpayuser'][$zlum]/$zdayalls['zadduser'][$zlum])*100;
                    $listalls['zxtu']['sevenday'][$i]=empty($sdayalls['sadduser'][$slum])?'0':sprintf("%.4f",$sdayalls['snewpayuser'][$slum]/$sdayalls['sadduser'][$slum])*100;
                }elseif($pages == 6){
                    $listalls['zxtu']['theday'][$i]=empty($dayalls['adduser'][$lnum])?'0':sprintf("%.2f",$dayalls['paymoney'][$lnum]/$dayalls['adduser'][$lnum]);
                    $listalls['zxtu']['today'][$i]=empty($zdayalls['zadduser'][$zlum])?'0':sprintf("%.2f",$zdayalls['zpaymoney'][$zlum]/$zdayalls['zadduser'][$zlum]);
                    $listalls['zxtu']['sevenday'][$i]=empty($sdayalls['sadduser'][$slum])?'0':sprintf("%.2f",$sdayalls['spaymoney'][$slum]/$sdayalls['sadduser'][$slum]);
                }else{
                    $listalls['zxtu']['theday'][$i]=empty( $dayalls['adduser'][$lnum])?'0':$dayalls['adduser'][$lnum];
                    $listalls['zxtu']['today'][$i]=empty( $zdayalls['zadduser'][$zlum])?'0':$zdayalls['zadduser'][$zlum];
                    $listalls['zxtu']['sevenday'][$i]=empty( $sdayalls['sadduser'][$slum])?'0':$sdayalls['sadduser'][$slum];
                }

            }else{
                $listalls['zxtu']['theday'][$i]=empty($dayalls['adduser'][$lnum])?'0':sprintf("%.2f",$dayalls['paymoney'][$lnum]/$dayalls['adduser'][$lnum]);
                $listalls['zxtu']['today'][$i]=empty($zdayalls['zadduser'][$zlum])?'0':sprintf("%.2f",$zdayalls['zpaymoney'][$zlum]/$zdayalls['zadduser'][$zlum]);
                $listalls['zxtu']['sevenday'][$i]=empty($sdayalls['sadduser'][$slum])?'0':sprintf("%.2f",$sdayalls['spaymoney'][$slum]/$sdayalls['sadduser'][$slum]);

            }





        }



        return $listalls;

    }
}