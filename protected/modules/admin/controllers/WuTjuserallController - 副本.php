<?php

class WuTjuserallController extends Backend
{

   //初始化函数
    public $_hoursall;
    public $_newupsum;
    public $_dayupsum;
    public $_ztupsum;
    public $_datelistall;
    public $_bbqdlist;
	public function init(){
		parent::init();
		$this->_hoursall=array(
		    '00:00','01:00','02:00','03:00','04:00','05:00','06:00','07:00','08:00','09:00',
            '10:00','11:00','12:00','13:00','14:00','15:00','16:00','17:00','18:00','19:00',
            '20:00','21:00','22:00','23:00',
        );



    }
    private function  listall(){
        $today=strtotime('today');
        $theday= strtotime(date('Y-m-d',strtotime('+1 days')));
        /*统计新增*/
        $usernewupsql='select  
                        count(*) as daysums 
                        from y_wu_tjuserall 
                        where 
                          y_wu_tjuserall.`update` 
                        BETWEEN '.$today.' and '.$theday;
        $findnum=Yii::app()->db->createCommand($usernewupsql)->queryAll();
        /*统计日活*/
        $userupsql='select  
                        count(*) as daysums 
                        from 
                        y_wu_tjuseruphour 
                        where 
                        y_wu_tjuseruphour.`dayupdate` 
                        BETWEEN '.$today.' and '.$theday;
        $finddaynum=Yii::app()->db->createCommand($userupsql)->queryAll();
        /*付费用户（触发付费接口）*/
        $userffsql='select  
                        count(*) as daysums 
                        from p_vip_log 
                        where 
                        p_vip_log.`status` = 1 AND 
                        p_vip_log.add_time 
                        BETWEEN '.$today.' and '.$theday;
        $ffdaynum=Yii::app()->db_zbdindan->createCommand($userffsql)->queryAll();
        /*新增付费用户*/
        $addffsql='select 
                        COUNT(*) as daysums 
                   from 
                        (select  
                          p_vip_log.device_id as p_userid  
                        from p_vip_log 
                        where 
                              p_vip_log.`status` = 1 AND 
                              p_vip_log.device_id in 
                            (select 
                              man_tjdb.y_wu_tjuserall.device_id 
                            from  man_tjdb.y_wu_tjuserall 
                            where 
                              y_wu_tjuserall.`update`   
                            BETWEEN '.$today.' and '.$theday.'
                            ) 
                            and  pingoula_ad.p_vip_log.add_time 
                        BETWEEN '.$today.' and '.$theday.' 
                        GROUP BY p_vip_log.device_id ) t ';
        $addffnum=Yii::app()->db_zbdindan->createCommand($addffsql)->queryAll();
        /*付费金额统计*/
        $daysumsql='select  
                        sum(p_vip_log.money) as money 
                    from p_vip_log 
                    where  
                        p_vip_log.`status` = 1 AND  
                        p_vip_log.add_time   
                        BETWEEN '.$today.' and '.$theday;
        $daymoneysum=Yii::app()->db_zbdindan->createCommand($daysumsql)->queryAll();
        $lists['findnum']=$findnum[0]['daysums'];
        $lists['finddaynum']=$finddaynum[0]['daysums'];
        $lists['ffdaynum']=$ffdaynum[0]['daysums'];
        $lists['addffnum']=$addffnum[0]['daysums'];
        $lists['daymoneysum']=$daymoneysum[0]['money'];
        return $lists;
    }

    /*列出表格信息*/
    private function tabellist(){
        /*今日*/
        $theday= strtotime(date('Y-m-d',strtotime('+1 days')));
        $today=strtotime('today');
        $days =array(0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0);
        $huos =array(0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0);
        $ffu =array(0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0);
        $addff =array(0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0);
        $ff =array(0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0);

        $daysql='select 
                  DATE_FORMAT(FROM_UNIXTIME(y_wu_tjuserall.`update`),"%H:00") as time,
                  DATE_FORMAT(FROM_UNIXTIME(y_wu_tjuserall.`update`),"%H") as times,
                  count(*) as mannum 
                from y_wu_tjuserall 
                where y_wu_tjuserall.`update` BETWEEN '.$today.' and '.$theday.'  
                group by time';
        $daylist=Yii::app()->db->createCommand($daysql)->queryAll();
        foreach ($daylist as &$list){
            $days[intval($list['times'])] = $list['mannum'];
        }

        /*日活实时*/
        $huosql='select 
                    DATE_FORMAT(FROM_UNIXTIME(y_wu_tjuseruphour.`dayupdate`),"%H:00") as time,
                    DATE_FORMAT(FROM_UNIXTIME(y_wu_tjuseruphour.`dayupdate`),"%H") as times,
                    count(*) as mannum 
                from y_wu_tjuseruphour 
                where y_wu_tjuseruphour.`dayupdate` BETWEEN '.$today.' and '.$theday.' 
                group by time ';
        $huolist=Yii::app()->db->createCommand($huosql)->queryAll();
        foreach ($huolist as &$hlist){
            $huos[intval($hlist['times'])] = $hlist['mannum'];
        }
        /*付费用户实时*/
        $userffsql='select  
                        DATE_FORMAT(FROM_UNIXTIME(p_vip_log.add_time),"%H:00") as time,
                        count(*) as daysums ,
                        DATE_FORMAT(FROM_UNIXTIME(p_vip_log.add_time),"%H") as times 
                    from p_vip_log 
                    where  p_vip_log.`status` = 1 AND p_vip_log.add_time BETWEEN '.$today.' and '.$theday.'
                    GROUP BY time';
        $userffnum=Yii::app()->db_zbdindan->createCommand($userffsql)->queryAll();
        foreach ($userffnum as &$ulist){
            $ffu[intval($ulist['times'])] = $ulist['daysums'];
        }
        /*新增付费用户实时*/
        $addffsql='select 
                        DATE_FORMAT(FROM_UNIXTIME(p_vip_log.add_time),"%H:00") as time, 
                        DATE_FORMAT(FROM_UNIXTIME(p_vip_log.`add_time`),"%H") as times,
                        count(*) as sums  
                    from p_vip_log 
                    where p_vip_log.`status` = 1 AND p_vip_log.device_id in 
                            (select man_tjdb.y_wu_tjuserall.device_id 
                              from  man_tjdb.y_wu_tjuserall 
                              where y_wu_tjuserall.`update`  BETWEEN '.$today.' and '.$theday.' ) 
                        and  p_vip_log.add_time BETWEEN '.$today.' and '.$theday.' 
                        GROUP BY time';
        $addfflist=Yii::app()->db_zbdindan->createCommand($addffsql)->queryAll();
        foreach ($addfflist as &$alist){
            $addff[intval($alist['times'])] = $alist['sums'];
        }
        /*付费实时*/
        $ffsql='select  
                    DATE_FORMAT(FROM_UNIXTIME(p_vip_log.add_time),"%H:00") as time,
                    DATE_FORMAT(FROM_UNIXTIME(p_vip_log.`add_time`),"%H") as times,
                    sum(p_vip_log.money) as  money 
                from p_vip_log 
                where p_vip_log.`status` = 1 AND  p_vip_log.add_time  BETWEEN '.$today.' and '.$theday.' 
                     group by time ';
        $fflist=Yii::app()->db_zbdindan->createCommand($ffsql)->queryAll();
        foreach ($fflist as &$flist){
            $ff[intval($flist['times'])] = $flist['money'];
        }
        $listalls=array();
        for ($i=0;$i<24;$i++){
            $listalls[$i]['mannum']=empty($days[$i])?'0':$days[$i];
            $listalls[$i]['huo']=empty($huos[$i])?'0':$huos[$i];
            $listalls[$i]['ffday']=empty($ffu[$i])?'0':$ffu[$i];
            $listalls[$i]['ffsums']=empty($addff[$i])?'0':$addff[$i];
            $listalls[$i]['money']=empty($ff[$i])?'0':$ff[$i];
        }
        return $listalls;
    }


	public function actionIndex(){
		$model=new WuTjuserall;
        $index=1;
		//条件
		$criteria = new CDbCriteria();
        $page = intval(Yii::app()->request->getParam('page'));
		if($page){
		    if($page ==1){
                $tulist=$this->huoyueuser();
            }else if($page ==2){
                $tulist=$this->fufeiuser();
            }else if($page ==3){
                $tulist=$this->addffuser();
            }else if($page ==4){
                $tulist=$this->ffuser();
            }elseif($page == 5){
                $tulist=$this->fflvuser();
            }elseif($page == 6){
                $tulist=$this->Arpuuser();
            }else{
                $tulist=$this->newadduser();
            }

        }else{
            $tulist=$this->newadduser();
        }




        $alltj=array();
        $alltj['dayhours'] = $tulist['today'];
        $alltj['todayhours'] = $tulist['zuodaiday'];
        $alltj['sevenhours'] =$tulist['day_week'];
        $alltj['yuehours'] =$tulist['day_month'];
        $listalls=$this->tabellist();
        $this->_dayupsum = $this->listall();
		//查询
		//$result = $model->findAll($criteria);
		$this->render('index', array ('model' => $model,'res'=>$index,'listalls'=>$listalls,'alltj'=>$alltj,'pages'=>$page ));
	}
    /*列出新增用户折线图*/
    private  function  newadduser(){
        $theday= strtotime(date('Y-m-d',strtotime('+1 days')));
        $hours =array(0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0);
        $todayhours =array(0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0);
        $sevenhours =array(0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0);
        $yuehours =array(0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0);
        /*今日新增*/
        $today=strtotime('today');
        $daysql='select 
                  DATE_FORMAT(FROM_UNIXTIME(y_wu_tjuserall.`update`),"%H:00") as time,
                  DATE_FORMAT(FROM_UNIXTIME(y_wu_tjuserall.`update`),"%H") as times,
                  count(*) as mannum 
                from y_wu_tjuserall 
                where y_wu_tjuserall.`update` BETWEEN '.$today.' and '.$theday.'  
                group by time';
        $daylist=Yii::app()->db->createCommand($daysql)->queryAll();
        foreach ($daylist as &$list){
            $hours[intval($list['times'])] = $list['mannum'];
        }
        /*各个时间*/
        //昨日
        $zuodate=  strtotime(date('Y-m-d',strtotime('-1 days')));
        $zuolistsql='select 
                        DATE_FORMAT(FROM_UNIXTIME(y_wu_tjuserall.`update`),"%H:00") as time,
                        DATE_FORMAT(FROM_UNIXTIME(y_wu_tjuserall.`update`),"%H") as times,
                        count(*) as mannum 
                    from 
                        y_wu_tjuserall 
                    where 
                        y_wu_tjuserall.`update` 
                        BETWEEN '.$zuodate.' and '.$today.' 
                         group by time';
        $zuoalllist=Yii::app()->db->createCommand($zuolistsql)->queryAll();
        foreach ($zuoalllist as &$tlist){
            $todayhours[intval($tlist['times'])] = $tlist['mannum'];
        }
        //七天前
        $sevendate=  strtotime(date('Y-m-d',strtotime('-7 days')));
        $sevendate2=  strtotime(date('Y-m-d',strtotime('-6 days')));
        $sevenlistsql='select 
                            DATE_FORMAT(FROM_UNIXTIME(y_wu_tjuserall.`update`),"%H:00") as time,
                            DATE_FORMAT(FROM_UNIXTIME(y_wu_tjuserall.`update`),"%H") as times,
                            count(*) as mannum 
                      from 
                            y_wu_tjuserall 
                      where 
                            y_wu_tjuserall.`update` 
                            BETWEEN '.$sevendate.' and '.$sevendate2.'  
                            group by time';
        $sevenlllist=Yii::app()->db->createCommand($sevenlistsql)->queryAll();
        foreach ($sevenlllist as &$qilist){
            $sevenhours[intval($qilist['times'])] = $qilist['mannum'];
        }
        //七天前
        $yuedate=  strtotime(date('Y-m-d',strtotime('-30 days')));
        $yuedate2=  strtotime(date('Y-m-d',strtotime('-29 days')));
        $yuelistsql='select 
                        DATE_FORMAT(FROM_UNIXTIME(y_wu_tjuserall.`update`),"%H:00") as time,
                        DATE_FORMAT(FROM_UNIXTIME(y_wu_tjuserall.`update`),"%H") as times,
                        count(*) as mannum 
                    from 
                        y_wu_tjuserall 
                    where 
                        y_wu_tjuserall.`update` 
                        BETWEEN '.$yuedate.' and '.$yuedate2.' 
                         group by time';
        $yuealllist=Yii::app()->db->createCommand($yuelistsql)->queryAll();
        foreach ($yuealllist as &$ylist){
            $yuehours[intval($ylist['times'])] = $ylist['mannum'];
        }
        $newuseradd['today'] =$hours;
        $newuseradd['zuodaiday'] =$todayhours;
        $newuseradd['day_week'] =$sevenhours;
        $newuseradd['day_month'] =$yuehours;
        return $newuseradd;
    }

    /*列出活跃用户折线图*/
    private  function  huoyueuser(){
        /*今日*/
        $theday= strtotime(date('Y-m-d',strtotime('+1 days')));
        $hours =array(0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0);
        $todayhours =array(0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0);
        $sevenhours =array(0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0);
        $yuehours =array(0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0);
        $today=strtotime('today');
        $daysql='select 
                    DATE_FORMAT(FROM_UNIXTIME(y_wu_tjuseruphour.`dayupdate`),"%H:00") as time,
                    DATE_FORMAT(FROM_UNIXTIME(y_wu_tjuseruphour.`dayupdate`),"%H") as times,
                    count(*) as mannum 
                from 
                    y_wu_tjuseruphour 
                where 
                    y_wu_tjuseruphour.`dayupdate` 
                    BETWEEN '.$today.' and '.$theday.'  
                    group by time ';
        $daylist=Yii::app()->db->createCommand($daysql)->queryAll();
        foreach ($daylist as &$list){
            $hours[intval($list['times'])] = $list['mannum'];
        }
        /*各个时间*/
        //昨日
        $zuodate=  strtotime(date('Y-m-d',strtotime('-1 days')));
        $zuolistsql='select 
                        DATE_FORMAT(FROM_UNIXTIME(y_wu_tjuseruphour.`dayupdate`),"%H:00") as time,
                        DATE_FORMAT(FROM_UNIXTIME(y_wu_tjuseruphour.`dayupdate`),"%H") as times,
                        count(*) as mannum 
                    from 
                        y_wu_tjuseruphour 
                    where 
                        y_wu_tjuseruphour.`dayupdate` 
                        BETWEEN '.$zuodate.' and '.$today.'  
                        group by time';
        $zuoalllist=Yii::app()->db->createCommand($zuolistsql)->queryAll();
        foreach ($zuoalllist as &$tlist){
            $todayhours[intval($tlist['times'])] = $tlist['mannum'];
        }
        //七天前
        $sevendate=  strtotime(date('Y-m-d',strtotime('-7 days')));
        $sevendate2=  strtotime(date('Y-m-d',strtotime('-6 days')));
        $sevenlistsql='select 
                            DATE_FORMAT(FROM_UNIXTIME(y_wu_tjuseruphour.`dayupdate`),"%H:00") as time,
                            DATE_FORMAT(FROM_UNIXTIME(y_wu_tjuseruphour.`dayupdate`),"%H") as times,
                            count(*) as mannum 
                        from 
                            y_wu_tjuseruphour 
                        where 
                            y_wu_tjuseruphour.`dayupdate` 
                            BETWEEN '.$sevendate.' and '.$sevendate2.'  
                            group by time';
        $sevenlllist=Yii::app()->db->createCommand($sevenlistsql)->queryAll();
        foreach ($sevenlllist as &$qilist){
            $sevenhours[intval($qilist['times'])] = $qilist['mannum'];
        }
        //七天前
        $yuedate=  strtotime(date('Y-m-d',strtotime('-30 days')));
        $yuedate2=  strtotime(date('Y-m-d',strtotime('-29 days')));
        $yuelistsql='select 
                        DATE_FORMAT(FROM_UNIXTIME(y_wu_tjuseruphour.`dayupdate`),"%H:00") as time,
                        DATE_FORMAT(FROM_UNIXTIME(y_wu_tjuseruphour.`dayupdate`),"%H") as times,
                        count(*) as mannum 
                    from 
                        y_wu_tjuseruphour 
                    where 
                        y_wu_tjuseruphour.`dayupdate` 
                        BETWEEN '.$yuedate.' and '.$yuedate2.'  
                        group by time';
        $yuealllist=Yii::app()->db->createCommand($yuelistsql)->queryAll();
        foreach ($yuealllist as &$ylist){
            $yuehours[intval($ylist['times'])] = $ylist['mannum'];
        }
        $newuseradd['today'] =$hours;
        $newuseradd['zuodaiday'] =$todayhours;
        $newuseradd['day_week'] =$sevenhours;
        $newuseradd['day_month'] =$yuehours;
        return $newuseradd;
    }


    /*列出付费用户折线图*/
    private  function  fufeiuser(){
        /*今日*/
        $theday= strtotime(date('Y-m-d',strtotime('+1 days')));
        $hours =array(0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0);
        $todayhours =array(0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0);
        $sevenhours =array(0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0);
        $yuehours =array(0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0);
        $today=strtotime('today');
        $daysql='select DATE_FORMAT(FROM_UNIXTIME(p_vip_log.add_time),"%H:00") as time,
                    DATE_FORMAT(FROM_UNIXTIME(p_vip_log.add_time),"%H") as times,
                    count(*) as mannum 
                from p_vip_log 
                where p_vip_log.`status` = 1 AND  p_vip_log.add_time BETWEEN '.$today.' and '.$theday.' 
                    group by time ';
        $daylist=Yii::app()->db_zbdindan->createCommand($daysql)->queryAll();
        foreach ($daylist as &$list){
            $hours[intval($list['times'])] = $list['mannum'];
        }
        /*各个时间*/
        //昨日
        $zuodate=  strtotime(date('Y-m-d',strtotime('-1 days')));
        $zuolistsql='select DATE_FORMAT(FROM_UNIXTIME(p_vip_log.add_time),"%H:00") as time,
                          DATE_FORMAT(FROM_UNIXTIME(p_vip_log.add_time),"%H") as times,
                          count(*) as mannum 
                      from p_vip_log 
                      where p_vip_log.`status` = 1 AND p_vip_log.add_time BETWEEN '.$zuodate.' and '.$today.'  
                            group by time ';
        $zuoalllist=Yii::app()->db_zbdindan->createCommand($zuolistsql)->queryAll();
        foreach ($zuoalllist as &$tlist){
            $todayhours[intval($tlist['times'])] = $tlist['mannum'];
        }
        //七天前
        $sevendate=  strtotime(date('Y-m-d',strtotime('-7 days')));
        $sevendate2=  strtotime(date('Y-m-d',strtotime('-6 days')));
        $sevenlistsql='select 
                            DATE_FORMAT(FROM_UNIXTIME(p_vip_log.add_time),"%H:00") as time,
                            DATE_FORMAT(FROM_UNIXTIME(p_vip_log.add_time),"%H") as times,
                            count(*) as mannum 
                        from p_vip_log 
                        where  p_vip_log.`status` = 1 AND p_vip_log.add_time BETWEEN '.$sevendate.' and '.$sevendate2.'  
                            group by time ';
        $sevenlllist=Yii::app()->db_zbdindan->createCommand($sevenlistsql)->queryAll();
        foreach ($sevenlllist as &$qilist){
            $sevenhours[intval($qilist['times'])] = $qilist['mannum'];
        }
        //30天前
        $yuedate=  strtotime(date('Y-m-d',strtotime('-30 days')));
        $yuedate2=  strtotime(date('Y-m-d',strtotime('-29 days')));
        $yuelistsql='select 
                        DATE_FORMAT(FROM_UNIXTIME(p_vip_log.add_time),"%H:00") as time,
                        DATE_FORMAT(FROM_UNIXTIME(p_vip_log.add_time),"%H") as times,
                        count(*) as mannum 
                    from p_vip_log 
                    where p_vip_log.`status` = 1 AND  p_vip_log.add_time BETWEEN '.$yuedate.' and '.$yuedate2.'  
                        group by time ';
        $yuealllist=Yii::app()->db_zbdindan->createCommand($yuelistsql)->queryAll();
        foreach ($yuealllist as &$ylist){
            $yuehours[intval($ylist['times'])] = $ylist['mannum'];
        }
        $newuseradd['today'] =$hours;
        $newuseradd['zuodaiday'] =$todayhours;
        $newuseradd['day_week'] =$sevenhours;
        $newuseradd['day_month'] =$yuehours;
        return $newuseradd;
    }

    /*列出新增付费用户折线图*/
    private  function  addffuser(){
        /*今日*/
        $theday= strtotime(date('Y-m-d',strtotime('+1 days')));
        $hours =array(0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0);
        $todayhours =array(0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0);
        $sevenhours =array(0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0);
        $yuehours =array(0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0);
        $today=strtotime('today');
        $daysql='select 
                        DATE_FORMAT(FROM_UNIXTIME(p_vip_log.add_time),"%H:00") as time, 
                        DATE_FORMAT(FROM_UNIXTIME(p_vip_log.`add_time`),"%H") as times,
                        count(*) as sums  
                    from p_vip_log 
                    where p_vip_log.`status` = 1 AND p_vip_log.device_id in 
                            (select man_tjdb.y_wu_tjuserall.device_id 
                              from  man_tjdb.y_wu_tjuserall 
                              where y_wu_tjuserall.`update`  BETWEEN '.$today.' and '.$theday.' ) 
                        and  p_vip_log.add_time BETWEEN '.$today.' and '.$theday.' 
                        GROUP BY time';
        $daylist=Yii::app()->db_zbdindan->createCommand($daysql)->queryAll();
        foreach ($daylist as &$list){
            $hours[intval($list['times'])] = $list['sums'];
        }
        /*各个时间*/
        //昨日
        $zuodate=  strtotime(date('Y-m-d',strtotime('-1 days')));
        $zuolistsql='select 
                        DATE_FORMAT(FROM_UNIXTIME(p_vip_log.add_time),"%H:00") as time, 
                        DATE_FORMAT(FROM_UNIXTIME(p_vip_log.`add_time`),"%H") as times,
                        count(*) as sums  
                    from p_vip_log 
                    where p_vip_log.`status` = 1 AND p_vip_log.device_id in 
                            (select man_tjdb.y_wu_tjuserall.device_id 
                              from  man_tjdb.y_wu_tjuserall 
                              where y_wu_tjuserall.`update`  BETWEEN '.$zuodate.' and '.$today.' ) 
                        and  p_vip_log.add_time BETWEEN '.$zuodate.' and '.$today.' 
                        GROUP BY time';
        $zuoalllist=Yii::app()->db_zbdindan->createCommand($zuolistsql)->queryAll();
        foreach ($zuoalllist as &$tlist){
            $todayhours[intval($tlist['times'])] = $tlist['sums'];
        }
        //七天前
        $sevendate=  strtotime(date('Y-m-d',strtotime('-7 days')));
        $sevendate2=  strtotime(date('Y-m-d',strtotime('-6 days')));
        $sevenlistsql='select 
                        DATE_FORMAT(FROM_UNIXTIME(p_vip_log.add_time),"%H:00") as time, 
                        DATE_FORMAT(FROM_UNIXTIME(p_vip_log.`add_time`),"%H") as times,
                        count(*) as sums  
                    from p_vip_log 
                    where  p_vip_log.`status` = 1 AND p_vip_log.device_id in 
                            (select man_tjdb.y_wu_tjuserall.device_id 
                              from  man_tjdb.y_wu_tjuserall 
                              where y_wu_tjuserall.`update`  BETWEEN '.$sevendate.' and '.$sevendate2.' ) 
                        and  p_vip_log.add_time BETWEEN '.$sevendate.' and '.$sevendate2.' 
                        GROUP BY time';
        $sevenlllist=Yii::app()->db_zbdindan->createCommand($sevenlistsql)->queryAll();
        foreach ($sevenlllist as &$qilist){
            $sevenhours[intval($qilist['times'])] = $qilist['sums'];
        }
        //30天前
        $yuedate=  strtotime(date('Y-m-d',strtotime('-30 days')));
        $yuedate2=  strtotime(date('Y-m-d',strtotime('-29 days')));
        $yuelistsql='select 
                        DATE_FORMAT(FROM_UNIXTIME(p_vip_log.add_time),"%H:00") as time, 
                        DATE_FORMAT(FROM_UNIXTIME(p_vip_log.`add_time`),"%H") as times,
                        count(*) as sums  
                    from p_vip_log 
                    where p_vip_log.`status` = 1 AND p_vip_log.device_id in 
                            (select man_tjdb.y_wu_tjuserall.device_id 
                              from  man_tjdb.y_wu_tjuserall 
                              where y_wu_tjuserall.`update`  BETWEEN '.$yuedate.' and '.$yuedate2.' ) 
                        and  p_vip_log.add_time BETWEEN '.$yuedate.' and '.$yuedate2.' 
                        GROUP BY time ';
        $yuealllist=Yii::app()->db_zbdindan->createCommand($yuelistsql)->queryAll();
        foreach ($yuealllist as &$ylist){
            $yuehours[intval($ylist['times'])] = $ylist['sums'];
        }
        $newuseradd['today'] =$hours;
        $newuseradd['zuodaiday'] =$todayhours;
        $newuseradd['day_week'] =$sevenhours;
        $newuseradd['day_month'] =$yuehours;
        return $newuseradd;
    }
    /*列出付费金额折线图*/
    private  function  ffuser(){
        /*今日*/
        $theday= strtotime(date('Y-m-d',strtotime('+1 days')));
        $hours =array(0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0);
        $todayhours =array(0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0);
        $sevenhours =array(0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0);
        $yuehours =array(0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0);
        $today=strtotime('today');
        $daysql='select  
                    DATE_FORMAT(FROM_UNIXTIME(p_vip_log.add_time),"%H:00") as time,
                    DATE_FORMAT(FROM_UNIXTIME(p_vip_log.`add_time`),"%H") as times,
                    sum(p_vip_log.money) as  money 
                from p_vip_log 
                where p_vip_log.`status` = 1 AND  p_vip_log.add_time  BETWEEN '.$today.' and '.$theday.' 
                     group by time';
        $daylist=Yii::app()->db_zbdindan->createCommand($daysql)->queryAll();
        foreach ($daylist as &$list){
            $hours[intval($list['times'])] = $list['money'];
        }
        /*各个时间*/
        //昨日
        $zuodate=  strtotime(date('Y-m-d',strtotime('-1 days')));
        $zuolistsql='select  
                    DATE_FORMAT(FROM_UNIXTIME(p_vip_log.add_time),"%H:00") as time,
                    DATE_FORMAT(FROM_UNIXTIME(p_vip_log.`add_time`),"%H") as times,
                    sum(p_vip_log.money) as  money 
                from p_vip_log 
                where p_vip_log.`status` = 1 AND  p_vip_log.add_time  BETWEEN '.$zuodate.' and '.$today.' 
                     group by time';
        $zuoalllist=Yii::app()->db_zbdindan->createCommand($zuolistsql)->queryAll();
        foreach ($zuoalllist as &$tlist){
            $todayhours[intval($tlist['times'])] = $tlist['money'];
        }
        //七天前
        $sevendate=  strtotime(date('Y-m-d',strtotime('-7 days')));
        $sevendate2=  strtotime(date('Y-m-d',strtotime('-6 days')));
        $sevenlistsql='select  
                    DATE_FORMAT(FROM_UNIXTIME(p_vip_log.add_time),"%H:00") as time,
                    DATE_FORMAT(FROM_UNIXTIME(p_vip_log.`add_time`),"%H") as times,
                    sum(p_vip_log.money) as  money 
                from p_vip_log 
                where p_vip_log.`status` = 1 AND  p_vip_log.add_time  BETWEEN '.$sevendate.' and '.$sevendate2.' 
                     group by time';
        $sevenlllist=Yii::app()->db_zbdindan->createCommand($sevenlistsql)->queryAll();
        foreach ($sevenlllist as &$qilist){
            $sevenhours[intval($qilist['times'])] = $qilist['money'];
        }
        //30天前
        $yuedate=  strtotime(date('Y-m-d',strtotime('-30 days')));
        $yuedate2=  strtotime(date('Y-m-d',strtotime('-29 days')));
        $yuelistsql='select  
                    DATE_FORMAT(FROM_UNIXTIME(p_vip_log.add_time),"%H:00") as time,
                    DATE_FORMAT(FROM_UNIXTIME(p_vip_log.`add_time`),"%H") as times,
                    sum(p_vip_log.money) as  money 
                from p_vip_log 
                where p_vip_log.`status` = 1 AND  p_vip_log.add_time  BETWEEN '.$yuedate.' and '.$yuedate2.' 
                     group by time';
        $yuealllist=Yii::app()->db_zbdindan->createCommand($yuelistsql)->queryAll();
        foreach ($yuealllist as &$ylist){
            $yuehours[intval($ylist['times'])] = $ylist['money'];
        }
        $newuseradd['today'] =$hours;
        $newuseradd['zuodaiday'] =$todayhours;
        $newuseradd['day_week'] =$sevenhours;
        $newuseradd['day_month'] =$yuehours;
        return $newuseradd;
    }
    /*付费率*/
    private  function  fflvuser(){
        $hours =array(0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0);
        $todayhours =array(0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0);
        $sevenhours =array(0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0);
        $yuehours =array(0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0);
        $ffuser=$this->addffuser();
        $newuser=$this->newadduser();
        $listalls=array();
        for ($i=0;$i<24;$i++){
            $listalls['today'][$i]=empty($newuser['today'][$i])?'0':sprintf("%.2f",$ffuser['today'][$i]/$newuser['today'][$i]);
            $listalls['zuodaiday'][$i]=empty($newuser['zuodaiday'][$i])?'0':sprintf("%.2f",$ffuser['zuodaiday'][$i]/$newuser['zuodaiday'][$i]);
            $listalls['day_week'][$i]=empty($newuser['day_week'][$i])?'0':sprintf("%.2f",$ffuser['day_week'][$i]/$newuser['day_week'][$i]);
            $listalls['day_month'][$i]=empty($newuser['day_month'][$i])?'0':sprintf("%.2f",$ffuser['day_month'][$i]/$newuser['day_month'][$i]);

        }
        return $listalls;
    }
    /*Arpu*/
    private  function  Arpuuser(){
        $hours =array(0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0);
        $todayhours =array(0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0);
        $sevenhours =array(0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0);
        $yuehours =array(0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0);
        $ffuser=$this->ffuser();
        $newuser=$this->newadduser();
        $listalls=array();
        for ($i=0;$i<24;$i++){
            $listalls['today'][$i]=empty($newuser['today'][$i])?'0':sprintf("%.2f",$ffuser['today'][$i]/$newuser['today'][$i]);
            $listalls['zuodaiday'][$i]=empty($newuser['zuodaiday'][$i])?'0':sprintf("%.2f",$ffuser['zuodaiday'][$i]/$newuser['zuodaiday'][$i]);
            $listalls['day_week'][$i]=empty($newuser['day_week'][$i])?'0':sprintf("%.2f",$ffuser['day_week'][$i]/$newuser['day_week'][$i]);
            $listalls['day_month'][$i]=empty($newuser['day_month'][$i])?'0':sprintf("%.2f",$ffuser['day_month'][$i]/$newuser['day_month'][$i]);

        }
        return $listalls;
    }


















  /*&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&*/
    /*列出版本信息   渠道信息 */
    private  function  listqdbb(){
        $listqdbb=array();
        $qdsql='SELECT substring_index(quedao, "-", 1) AS qdlist FROM `y_wu_tjuserall` GROUP BY qdlist ;';
        $qdlist=Yii::app()->db->createCommand($qdsql)->queryAll();
        $quedao=array();
        $quedao_two=array();
        $str2='测试';
        foreach ($qdlist as &$list){
            if(strpos($list['qdlist'],$str2) === false and $list['qdlist']!=''){     //使用绝对等于
                $quedao[]=$list['qdlist'];
            }
        }
        $qdtwosql='SELECT substring_index(substring_index(quedao,\'-\', 2),\'-\',-1) AS twolist FROM `y_wu_tjuserall` GROUP BY twolist';
        $qdtwolist=Yii::app()->db->createCommand($qdtwosql)->queryAll();
        foreach ($qdtwolist as &$tlist){
            if(strpos($tlist['twolist'],$str2) === false and $tlist['twolist']!=''){     //使用绝对等于
                $quedao_two[]=$tlist['twolist'];
            }
        }
        $bbsql='SELECT version_name as banben FROM `y_wu_tjuserall` GROUP BY version_name';
        $bblist=Yii::app()->db->createCommand($bbsql)->queryAll();
        $listqdbb['qdone']=$quedao;
        $listqdbb['qdtwo']=$quedao_two;
        $listqdbb['bb']=$bblist;
        return $listqdbb;
    }



    /*整体趋势统计*/
    private function  listdayall($startday,$endday,$qd='',$bb='',$pay=''){
        //$theday= strtotime(date('Y-m-d',strtotime('+1 days')));
        $today =intval($startday);
        $theday = intval($endday);
        $quedao = $qd;
        $banben = $bb;
        $paydo = $pay;
        /*统计新增*/
        $usernewupsql='select  
                            count(*) as daysums 
                            from y_wu_tjuserall
                             where 
                             locate("'.$quedao.'",y_wu_tjuserall.quedao ) 
                             and locate("'.$banben.'",y_wu_tjuserall.version_name ) 
                             and  y_wu_tjuserall.`update` 
                      BETWEEN '.$today.' and '.$theday;

        $findnum=Yii::app()->db->createCommand($usernewupsql)->queryAll();
        /*统计日活*/
        $userupsql='select  
                        count(*) as daysums from y_wu_tjuseruphour where  
                        locate("'.$quedao.'",y_wu_tjuseruphour.quedao ) 
                        and locate("'.$banben.'",y_wu_tjuseruphour.version_name ) 
                        and  y_wu_tjuseruphour.`dayupdate` 
                        BETWEEN '.$today.' and '.$theday;
        $finddaynum=Yii::app()->db->createCommand($userupsql)->queryAll();

        /*付费用户（触发付费接口）*/
        $userffsql='select  
                        count(*) as daysums from p_vip_log where  
                       (p_vip_log.pay_channel="'.$paydo.'"  or "'.$paydo.'"="") and 
                        locate("'.$quedao.'",p_vip_log.channel )  and 
                        locate("'.$banben.'",p_vip_log.version_name )  and
                         p_vip_log.`status` = 1 AND p_vip_log.add_time 
                        BETWEEN '.$today.' and '.$theday;

        $ffdaynum=Yii::app()->db_zbdindan->createCommand($userffsql)->queryAll();

        /*新增付费用户*/
        $addffsql='select 
                        COUNT(*) as daysums 
                        from 
                        (select 
                         p_vip_log.device_id as p_userid  
                        from p_vip_log 
                        where
                          (p_vip_log.pay_channel="'.$paydo.'"  or "'.$paydo.'"="") and 
                         locate("'.$quedao.'",p_vip_log.channel ) and 
                        locate("'.$banben.'",p_vip_log.version_name ) and 
                        p_vip_log.`status` = 1 AND 
                        p_vip_log.device_id in 
                            (select 
                            man_tjdb.y_wu_tjuserall.device_id 
                            from  man_tjdb.y_wu_tjuserall 
                            where  locate("'.$quedao.'",y_wu_tjuserall.quedao ) 
                            and locate("'.$banben.'",y_wu_tjuserall.version_name ) 
                            and y_wu_tjuserall.`update`   BETWEEN '.$today.' and '.$theday.') 
                        and  p_vip_log.add_time BETWEEN '.$today.' and '.$theday.' 
                    GROUP BY p_vip_log.device_id ) t ';
        $addffnum=Yii::app()->db_zbdindan->createCommand($addffsql)->queryAll();
        /*付费金额统计*/
        $daysumsql='select  
                        sum(p_vip_log.money) as money 
                        from p_vip_log 
                        where   
                        (p_vip_log.pay_channel="'.$paydo.'"  or "'.$paydo.'"="") and 
                        locate("'.$quedao.'",p_vip_log.channel ) and 
                        locate("'.$banben.'",p_vip_log.version_name ) and 
                         p_vip_log.`status` = 1 AND  p_vip_log.add_time   
                        BETWEEN '.$today.' and '.$theday;
        $daymoneysum=Yii::app()->db_zbdindan->createCommand($daysumsql)->queryAll();
        $lists['findnum']=$findnum[0]['daysums'];
        $lists['finddaynum']=$finddaynum[0]['daysums'];
        $lists['ffdaynum']=$ffdaynum[0]['daysums'];
        $lists['addffnum']=$addffnum[0]['daysums'];
        $lists['daymoneysum']=$daymoneysum[0]['money'];
        return $lists;
    }
    /*列出表格信息*/
    private function tabeldaylist($startday,$endday,$qd='',$bb='',$pay=''){
        /*今日*/
        $today =intval($startday);
        $theday = intval($endday);
        $listsize = ($theday-$today)/3600/24;

        $quedao = $qd;
        $banben = $bb;
        $paydo = $pay;

        $dayalls =array();
        $huos =array();
        $ffu =array();
        $addff =array();
        $ff =array();
        $showdate =array();
        for($i=0;$i<$listsize;$i++)
        {
            $dayalls[$today+$i*24*60*60]=0;
            $huos[$today+$i*24*60*60]=0;
            $ffu[$today+$i*24*60*60]=0;
            $addff[$today+$i*24*60*60]=0;
            $ff[$today+$i*24*60*60]=0;
            $showdate[]=date('m-d',$today+$i*24*60*60);
        }
         $this->_datelistall = $showdate;
        $daysql='select 
                  DATE_FORMAT(FROM_UNIXTIME(y_wu_tjuserall.`update`),"%Y-%m-%d") as day,
                  count(*) as mannum 
                from y_wu_tjuserall 
                where  
                locate("'.$quedao.'",y_wu_tjuserall.quedao )  and 
                locate("'.$banben.'",y_wu_tjuserall.version_name )  and 
                y_wu_tjuserall.`update` BETWEEN '.$today.' and '.$theday.'  
                group by day';
        $daylist=Yii::app()->db->createCommand($daysql)->queryAll();
        foreach ($daylist as &$list){
            $dayalls[strtotime($list['day'])] = $list['mannum'];

        }

        /*日活实时*/
        $huosql='select 
                    DATE_FORMAT(FROM_UNIXTIME(y_wu_tjuseruphour.`dayupdate`),"%Y-%m-%d") as day,
                    count(*) as mannum 
                from y_wu_tjuseruphour 
                where 
                 locate("'.$quedao.'",y_wu_tjuseruphour.quedao )  and 
                locate("'.$banben.'",y_wu_tjuseruphour.version_name )  and 
                y_wu_tjuseruphour.`dayupdate` 
                BETWEEN '.$today.' and '.$theday.' 
                group by day ';
        $huolist=Yii::app()->db->createCommand($huosql)->queryAll();
        foreach ($huolist as &$hlist){
            $huos[strtotime($hlist['day'])] = $hlist['mannum'];
        }

        /*付费用户实时*/
        $userffsql='select  
                        DATE_FORMAT(FROM_UNIXTIME(p_vip_log.add_time),"%Y-%m-%d") as day,
                        count(*) as daysums 
                    from p_vip_log 
                    where  
                    
  (p_vip_log.pay_channel="'.$paydo.'"  or "'.$paydo.'"="") and 
                        locate("'.$quedao.'",p_vip_log.channel )  and 
                        locate("'.$banben.'",p_vip_log.version_name )  and
                    p_vip_log.`status` = 1 AND
                     p_vip_log.add_time BETWEEN '.$today.' and '.$theday.'
                    GROUP BY day';
        $userffnum=Yii::app()->db_zbdindan->createCommand($userffsql)->queryAll();
        foreach ($userffnum as &$ulist){
            $ffu[strtotime($ulist['day'])] = $ulist['daysums'];
        }

        /*新增付费用户实时*/
        $addffsql='select 
                        DATE_FORMAT(FROM_UNIXTIME(p_vip_log.add_time),"%Y-%m-%d") as day, 
                        count(*) as sums  
                    from p_vip_log 
                    where
                        (p_vip_log.pay_channel="'.$paydo.'"  or "'.$paydo.'"="") and 
                        locate("'.$quedao.'",p_vip_log.channel )  and 
                        locate("'.$banben.'",p_vip_log.version_name )  and
                         p_vip_log.`status` = 1 AND
                          p_vip_log.device_id in 
                                (select man_tjdb.y_wu_tjuserall.device_id 
                                  from  man_tjdb.y_wu_tjuserall 
                                  where 
                                    locate("'.$quedao.'",y_wu_tjuserall.quedao )  and 
                                    locate("'.$banben.'",y_wu_tjuserall.version_name )  and 
                                    y_wu_tjuserall.`update`  BETWEEN '.$today.' and '.$theday.' ) 
                            and  p_vip_log.add_time BETWEEN '.$today.' and '.$theday.' 
                   GROUP BY day';
        $addfflist=Yii::app()->db_zbdindan->createCommand($addffsql)->queryAll();
        foreach ($addfflist as &$alist){
            $addff[strtotime($alist['day'])] = $alist['sums'];
        }
        /*付费实时*/
        $ffsql='select  
                    DATE_FORMAT(FROM_UNIXTIME(p_vip_log.add_time),"%Y-%m-%d") as day,
                    sum(p_vip_log.money) as  money 
                from p_vip_log 
                where 
                (p_vip_log.pay_channel="'.$paydo.'"  or "'.$paydo.'"="") and 
                locate("'.$quedao.'",p_vip_log.channel )  and 
                locate("'.$banben.'",p_vip_log.version_name )  and
                p_vip_log.`status` = 1 AND  
                p_vip_log.add_time  
                BETWEEN '.$today.' and '.$theday.' 
                     group by day ';
        $fflist=Yii::app()->db_zbdindan->createCommand($ffsql)->queryAll();
        foreach ($fflist as &$flist){
            $ff[strtotime($flist['day'])] = $flist['money'];
        }
        $listalls=array();
        for ($i=0;$i<$listsize;$i++){
            $lnum= $today+$i*24*60*60;
            $listalls[$i]['dateall']=date('m-d',$today+$i*24*60*60);
            $listalls[$i]['mannum']=empty($dayalls[$lnum])?'0':$dayalls[$lnum];
            $listalls[$i]['huo']=empty($huos[$lnum])?'0':$huos[$lnum];
            $listalls[$i]['ffday']=empty($ffu[$lnum])?'0':$ffu[$lnum];
            $listalls[$i]['ffsums']=empty($addff[$lnum])?'0':$addff[$lnum];
            $listalls[$i]['money']=empty($ff[$lnum])?'0':$ff[$lnum];
            $listalls[$i]['fflv']=empty($ff[$lnum])?'0':sprintf("%.4f",$addff[$lnum]/$dayalls[$lnum])*100;
            $listalls[$i]['arpu']=empty($ff[$lnum])?'0':sprintf("%.2f",$ff[$lnum]/$dayalls[$lnum]);
        }

        return $listalls;
    }
    /*列出表格信息*/
    private function tabeltimelist($startday,$endday,$qd='',$bb='',$pay=''){
        /*今日*/
        $today =intval($startday);
        $theday = intval($endday);
        $listsize = ($theday-$today)/3600/24;

        $quedao = $qd;
        $banben = $bb;
        $paydo = $pay;


        $dayalls =array();
        $huos =array();
        $ffu =array();
        $addff =array();
        $ff =array();
        for($i=0;$i<$listsize*24;$i++)
        {
            $dayalls[$today+$i*60*60]=0;
            $huos[$today+$i*60*60]=0;
            $ffu[$today+$i*60*60]=0;
            $addff[$today+$i*60*60]=0;
            $ff[$today+$i*60*60]=0;
            $showdate[]=date('m-d H',$today+$i*60*60);
        }
        //var_dump($showdate);
       // exit();
        $daysql='select 
                  DATE_FORMAT(FROM_UNIXTIME(y_wu_tjuserall.`update`),"%Y-%m-%d %H:00") as time,
                  count(*) as mannum 
                from y_wu_tjuserall 
                where 
                    locate("'.$quedao.'",y_wu_tjuserall.quedao )  and 
                    locate("'.$banben.'",y_wu_tjuserall.version_name )  and 
                    y_wu_tjuserall.`update` 
                    BETWEEN '.$today.' and '.$theday.'  
                group by time';
        $daylist=Yii::app()->db->createCommand($daysql)->queryAll();
        foreach ($daylist as &$list){
            $dayalls[strtotime($list['time'])] = $list['mannum'];
        }

        /*日活实时*/
        $huosql='select 
                    DATE_FORMAT(FROM_UNIXTIME(y_wu_tjuseruphour.`dayupdate`),"%Y-%m-%d %H:00") as time,
                    count(*) as mannum 
                from y_wu_tjuseruphour 
                where 
                 locate("'.$quedao.'",y_wu_tjuseruphour.quedao )  and 
                locate("'.$banben.'",y_wu_tjuseruphour.version_name )  and 
                y_wu_tjuseruphour.`dayupdate`
                 BETWEEN '.$today.' and '.$theday.' 
                group by time ';
        $huolist=Yii::app()->db->createCommand($huosql)->queryAll();
        foreach ($huolist as &$hlist){
            $huos[strtotime($hlist['time'])] = $hlist['mannum'];
        }
        /*付费用户实时*/
        $userffsql='select  
                        DATE_FORMAT(FROM_UNIXTIME(p_vip_log.add_time),"%Y-%m-%d %H:00") as time,
                        count(*) as daysums 
                    from p_vip_log 
                    where  
                         (p_vip_log.pay_channel="'.$paydo.'"  or "'.$paydo.'"="") and 
                        locate("'.$quedao.'",p_vip_log.channel )  and 
                        locate("'.$banben.'",p_vip_log.version_name )  and
                        p_vip_log.`status` = 1 AND 
                        p_vip_log.add_time 
                        BETWEEN '.$today.' and '.$theday.'
                    GROUP BY time';
        $userffnum=Yii::app()->db_zbdindan->createCommand($userffsql)->queryAll();
        foreach ($userffnum as &$ulist){
            $ffu[strtotime($ulist['time'])] = $ulist['daysums'];
        }
        /*新增付费用户实时*/
        $addffsql='select 
                        DATE_FORMAT(FROM_UNIXTIME(p_vip_log.add_time),"%Y-%m-%d %H:00") as time, 
                        count(*) as sums  
                    from p_vip_log 
                    where 
                       (p_vip_log.pay_channel="'.$paydo.'"  or "'.$paydo.'"="") and 
                        locate("'.$quedao.'",p_vip_log.channel )  and 
                        locate("'.$banben.'",p_vip_log.version_name )  and
                        p_vip_log.`status` = 1 
                        AND p_vip_log.device_id in 
                                (select man_tjdb.y_wu_tjuserall.device_id 
                                  from  man_tjdb.y_wu_tjuserall 
                                  where y_wu_tjuserall.`update`  BETWEEN '.$today.' and '.$theday.' ) 
                            and  p_vip_log.add_time BETWEEN '.$today.' and '.$theday.' 
                            GROUP BY time';
        $addfflist=Yii::app()->db_zbdindan->createCommand($addffsql)->queryAll();
        foreach ($addfflist as &$alist){
            $addff[strtotime($alist['time'])] = $alist['sums'];
        }
        /*付费实时*/
        $ffsql='select  
                    DATE_FORMAT(FROM_UNIXTIME(p_vip_log.add_time),"%Y-%m-%d %H:00") as time,
                    sum(p_vip_log.money) as  money 
                from p_vip_log 
                where 
                     (p_vip_log.pay_channel="'.$paydo.'"  or "'.$paydo.'"="") and 
                    locate("'.$quedao.'",p_vip_log.channel )  and 
                    locate("'.$banben.'",p_vip_log.version_name )  and
                    p_vip_log.`status` = 1 AND  
                    p_vip_log.add_time  
                    BETWEEN '.$today.' and '.$theday.' 
                group by time ';
        $fflist=Yii::app()->db_zbdindan->createCommand($ffsql)->queryAll();
        foreach ($fflist as &$flist){
            $ff[strtotime($flist['time'])] = $flist['money'];
        }
        $listalls=array();
        for($i=0;$i<$listsize*24;$i++){
            $lnum= $today+$i*60*60;
            $listalls[$i]['dateall']=date('m-d H:00',$today+$i*60*60);
            $listalls[$i]['mannum']=empty($dayalls[$lnum])?'0':$dayalls[$lnum];
            $listalls[$i]['huo']=empty($huos[$lnum])?'0':$huos[$lnum];
            $listalls[$i]['ffday']=empty($ffu[$lnum])?'0':$ffu[$lnum];
            $listalls[$i]['ffsums']=empty($addff[$lnum])?'0':$addff[$lnum];
            $listalls[$i]['money']=empty($ff[$lnum])?'0':$ff[$lnum];
            $listalls[$i]['fflv']=empty($dayalls[$lnum])?'0':sprintf("%.4f",$addff[$lnum]/$dayalls[$lnum])*100;
            $listalls[$i]['arpu']=empty($dayalls[$lnum])?'0':sprintf("%.2f",$ff[$lnum]/$dayalls[$lnum]);
        }
        return $listalls;
    }


    public function  actionztqs(){
        $index=2;
        $page = intval(Yii::app()->request->getParam('page'));

        $timetype = intval(Yii::app()->request->getParam('timetype'));
        if(isset($_POST['ztqs'])){
            $inputfrom =$_POST['ztqs'];

            $starttime  = empty($_POST['ztqs']['starttime'])?strtotime(date('Y-m-d',strtotime('-7 days'))):strtotime($_POST['ztqs']['starttime']);
            $endtime= empty($_POST['ztqs']['endtime'])?strtotime('today'):strtotime($_POST['ztqs']['endtime']);
            $qdone=empty($_POST['ztqs']['qdone'])?'':trim($_POST['ztqs']['qdone']);
            $qdtwo=empty($_POST['ztqs']['qdtwo'])?'':trim($_POST['ztqs']['qdtwo']);
            if($qdone){
                $quedao=$qdone.'-'.$qdtwo;
            }else{
                $quedao='';
            }
           //
            $bb=trim($_POST['ztqs']['bb']);
            $pay=empty($_POST['ztqs']['pay'])?'':intval($_POST['ztqs']['pay']);
            $this->_dayupsum=$this->listdayall($starttime,$endtime,$quedao,$bb,$pay);
            if($timetype){
                $listtimealls=$this->tabeltimelist($starttime,$endtime,$quedao,$bb,$pay);
                $listalls=$this->tabeldaylist($starttime,$endtime,$quedao,$bb,$pay);
            }else{
                $listalls=$this->tabeldaylist($starttime,$endtime,$quedao,$bb,$pay);
                $listtimealls=$listalls;
            }

        }else{
            $inputfrom =array();
            $starttime  = strtotime(date('Y-m-d',strtotime('-7 days')));
            $endtime= strtotime('today');
            $this->_dayupsum=$this->listdayall($starttime,$endtime,'','','');
            if($timetype){
                $listtimealls=$this->tabeltimelist($starttime,$endtime,'','','');
                $listalls=$this->tabeldaylist($starttime,$endtime,'','','');
            }else{
                $listalls=$this->tabeldaylist($starttime,$endtime,'','','');
                $listtimealls=$listalls;
            }
        }



        $alltj=array();
        if($page){
            if($page ==1){
                $alltj['names'] ='活跃用户';
            }else if($page ==2){
                $alltj['names'] ='付费用户';
            }else if($page ==3){
                $alltj['names'] ='新增付费用户';
            }else if($page ==4){
                $alltj['names'] ='付费金额';
            }elseif($page == 5){
                $alltj['names'] ='付费率';
            }elseif($page == 6){
                $alltj['names'] ='ARPU';
            }else{
                $alltj['names'] ='新增用户';
            }

        }else{
            $alltj['names'] ='新增用户';
        }
        $tulist=$this->newadduser_day($starttime,$endtime,$page);

        $this->_bbqdlist=$this->listqdbb();

        $alltj['dayhours'] = $tulist['zxian'];


        $this->render('ztqs', array (
            'listalls'=>$listalls,
            'listtimealls'=>$listtimealls,
            'alltj'=>$alltj,
            'res'=>$index,
            'pages'=>$page,
            'starttime'=>$starttime,
            'endtime'=>$endtime ,
            'searchfrom'=>$inputfrom,
            'timetype'=>$timetype
        ));
    }

    /*列出新增用户折线图*/
    private  function  newadduser_day($startday,$endday,$type,$qd='',$bb='',$pay=''){

            /*今日*/
            $today =intval($startday);
            $theday = intval($endday);
            $listsize = ($theday-$today)/3600/24;
            $quedao = $qd;
            $banben = $bb;
            $paydo = $pay;
            $dayalls =array();
            for($i=0;$i<$listsize;$i++)
            {
                $dayalls[$today+$i*24*60*60]=0;
            }
            if($type){
                if($type ==1){
                    /*日活实时*/
                    $huosql='select 
                                DATE_FORMAT(FROM_UNIXTIME(y_wu_tjdayuser.`dayupdate`),"%Y-%m-%d") as day,
                                count(*) as mannum 
                            from y_wu_tjdayuser 
                            where 
                            locate("'.$quedao.'",y_wu_tjdayuser.quedao )  and 
                            locate("'.$banben.'",y_wu_tjdayuser.version_name )  and
                            y_wu_tjdayuser.`dayupdate`  
                            BETWEEN '.$today.' and '.$theday.' 
                            group by day ';
                    $huolist=Yii::app()->db->createCommand($huosql)->queryAll();
                    foreach ($huolist as &$hlist){
                        $dayalls[strtotime($hlist['day'])] = $hlist['mannum'];
                    }
                }elseif($type ==2){
                    /*付费用户实时*/
                    $userffsql='select  
                                    DATE_FORMAT(FROM_UNIXTIME(p_vip_log.add_time),"%Y-%m-%d") as day,
                                    count(*) as daysums 
                                from p_vip_log 
                                where  
                                 (p_vip_log.pay_channel="'.$paydo.'"  or "'.$paydo.'"="") and 
                                 locate("'.$quedao.'",p_vip_log.channel )  and 
                                locate("'.$banben.'",p_vip_log.version_name )  and
                                p_vip_log.`status` = 1 AND 
                                p_vip_log.add_time 
                                BETWEEN '.$today.' and '.$theday.'
                                GROUP BY day';
                    $userffnum=Yii::app()->db_zbdindan->createCommand($userffsql)->queryAll();
                    foreach ($userffnum as &$ulist){
                        $dayalls[strtotime($ulist['day'])] = $ulist['daysums'];
                    }
                }elseif($type ==3){
                    /*新增付费用户实时*/
                    $addffsql='select 
                                    DATE_FORMAT(FROM_UNIXTIME(p_vip_log.add_time),"%Y-%m-%d") as day, 
                                    count(*) as sums  
                                from p_vip_log 
                                where 
                                 (p_vip_log.pay_channel="'.$paydo.'"  or "'.$paydo.'"="") and 
                                 locate("'.$quedao.'",p_vip_log.channel )  and 
                                locate("'.$banben.'",p_vip_log.version_name )  and
                                p_vip_log.`status` = 1 AND 
                                p_vip_log.device_id in 
                                        (select man_tjdb.y_wu_tjuserall.device_id 
                                          from  man_tjdb.y_wu_tjuserall 
                                          where 
                                             locate("'.$quedao.'",y_wu_tjuserall.quedao )  and 
                                            locate("'.$banben.'",y_wu_tjuserall.version_name )  and
                                          y_wu_tjuserall.`update`  
                                          BETWEEN '.$today.' and '.$theday.' ) 
                                    and  p_vip_log.add_time BETWEEN '.$today.' and '.$theday.' 
                                    GROUP BY day';
                    $addfflist=Yii::app()->db_zbdindan->createCommand($addffsql)->queryAll();
                    foreach ($addfflist as &$alist){
                        $dayalls[strtotime($alist['day'])] = $alist['sums'];
                    }
                }elseif($type ==4){
                    /*付费实时*/
                    $ffsql='select  
                                DATE_FORMAT(FROM_UNIXTIME(p_vip_log.add_time),"%Y-%m-%d") as day,
                                sum(p_vip_log.money) as  money 
                            from p_vip_log 
                            where 
                             (p_vip_log.pay_channel="'.$paydo.'"  or "'.$paydo.'"="") and 
                                 locate("'.$quedao.'",p_vip_log.channel )  and 
                                locate("'.$banben.'",p_vip_log.version_name )  and
                            p_vip_log.`status` = 1 AND  
                            p_vip_log.add_time  
                            BETWEEN '.$today.' and '.$theday.' 
                                 group by day ';
                    $fflist=Yii::app()->db_zbdindan->createCommand($ffsql)->queryAll();
                    foreach ($fflist as &$flist){
                        $dayalls[strtotime($flist['day'])] = $flist['money'];
                    }
                }else{
                    $daysql='select 
                                  DATE_FORMAT(FROM_UNIXTIME(y_wu_tjuserall.`update`),"%Y-%m-%d") as day,
                                  count(*) as mannum 
                                from y_wu_tjuserall 
                                where 
                                 
                                 locate("'.$quedao.'",y_wu_tjuserall.quedao )  and 
                                locate("'.$banben.'",y_wu_tjuserall.version_name )  and
                                y_wu_tjuserall.`update` 
                                BETWEEN '.$today.' and '.$theday.'  
                                group by day';
                    $daylist=Yii::app()->db->createCommand($daysql)->queryAll();
                    foreach ($daylist as &$list){
                        $dayalls[strtotime($list['day'])] = $list['mannum'];

                    }
                }
            }else{
                $daysql='select 
                              DATE_FORMAT(FROM_UNIXTIME(y_wu_tjuserall.`update`),"%Y-%m-%d") as day,
                              count(*) as mannum 
                            from y_wu_tjuserall 
                            where 
                               locate("'.$quedao.'",y_wu_tjuserall.quedao )  and 
                                locate("'.$banben.'",y_wu_tjuserall.version_name )  and
                            y_wu_tjuserall.`update` 
                            BETWEEN '.$today.' and '.$theday.'  
                            group by day';
                $daylist=Yii::app()->db->createCommand($daysql)->queryAll();
                foreach ($daylist as &$list){
                    $dayalls[strtotime($list['day'])] = $list['mannum'];
                }
            }
            $listalls=array();
            for ($i=0;$i<$listsize;$i++){
                $lnum= $today+$i*24*60*60;
                $listalls['zxian'][$i]=empty($dayalls[$lnum])?'0':$dayalls[$lnum];
            }
            return $listalls;
    }











/******************************************************付费***************************************************************/
    /************************************************************************************************************************/
    /************************************************************************************************************************/
    /****************************************************区域******************************************************************/
    public  function  actionpayindex(){
        $index=5;
        $page = intval(Yii::app()->request->getParam('page'));

        $timetype = intval(Yii::app()->request->getParam('timetype'));
        if(isset($_POST['ztqs'])){
            $inputfrom =$_POST['ztqs'];
            $starttime  = empty($_POST['ztqs']['starttime'])?strtotime(date('Y-m-d',strtotime('-7 days'))):strtotime($_POST['ztqs']['starttime']);
            $endtime= empty($_POST['ztqs']['endtime'])?strtotime('today'):strtotime($_POST['ztqs']['endtime']);
            $qdone=empty($_POST['ztqs']['qdone'])?'':trim($_POST['ztqs']['qdone']);
            $qdtwo=empty($_POST['ztqs']['qdtwo'])?'':trim($_POST['ztqs']['qdtwo']);
            if($qdone){
                $quedao=$qdone.'-'.$qdtwo;
            }else{
                $quedao='';
            }
            //
            $bb=trim($_POST['ztqs']['bb']);
            $pay=empty($_POST['ztqs']['pay'])?'':intval($_POST['ztqs']['pay']);
            $this->_dayupsum=$this->listffdayall($starttime,$endtime,$quedao,$bb,$pay);
            if($timetype){
                $listalls=$this->listfftimeall($starttime,$endtime,$quedao,$bb,$pay);
                $listtimealls=$listalls['tablelist'];
            }else{
                $listalls=$this->_dayupsum;
                $listtimealls=$listalls['tablelist'];
            }

        }else{
            $inputfrom =array();
            $starttime  = strtotime(date('Y-m-d',strtotime('-7 days')));
            $endtime= strtotime('today');
            $this->_dayupsum=$this->listffdayall($starttime,$endtime,'','','');
            if($timetype){
                $listalls=$this->listfftimeall($starttime,$endtime,'','','');
                $listtimealls=$listalls['tablelist'];

               // $listalls=$this->listffdayall($starttime,$endtime,'','','');
            }else{
                $listalls=$this->_dayupsum;
                $listtimealls=$listalls['tablelist'];
            }
        }

        $alltj=array();
        if($page){
            if($page ==1){
                $alltj['names'] ='付费用户';
                $alltj['dayhours'] =  $this->_dayupsum['zxianffday'];
            }else if($page ==2){
                $alltj['names'] ='付费次数';
                $alltj['dayhours'] =  $this->_dayupsum['zxianffsums'];
            }else{
                $alltj['names'] ='新增用户';
                $alltj['dayhours'] =  $this->_dayupsum['zxianmoney'];
            }

        }else{
            $alltj['names'] ='付费金额';
            $alltj['dayhours'] =  $this->_dayupsum['zxianmoney'];
        }


        $this->_bbqdlist=$this->listqdbb();



        $this->render('payindex', array (
            //'listalls'=>$listalls,
            'listtimealls'=>$listtimealls,
            'timetype'=>$timetype,
            'alltj'=>$alltj,
            'res'=>$index,
            'pages'=>$page,
            'starttime'=>$starttime,
            'endtime'=>$endtime ,
            'searchfrom'=>$inputfrom
        ));
    }


    private function  listffdayall($startday,$endday,$qd='',$bb='',$pay=''){
        /*今日*/
        $today =intval($startday);
        $theday = intval($endday);
        $listsize = ($theday-$today)/3600/24;

        $quedao = $qd;
        $banben = $bb;
        $paydo = $pay;

        $dayalls =array();
        $ffu =array();
        $ffdo =array();
        $ff =array();
        $showdate =array();
        for($i=0;$i<$listsize;$i++)
        {

            $dayalls[$today+$i*24*60*60]=0;
            $ffu[$today+$i*24*60*60]=0;
            $ffdo[$today+$i*24*60*60]=0;
            $ff[$today+$i*24*60*60]=0;

            $showdate[]=date('m-d',$today+$i*24*60*60);
        }
        $this->_datelistall = $showdate;
        $daysql='select 
                  DATE_FORMAT(FROM_UNIXTIME(y_wu_tjuserall.`update`),"%Y-%m-%d") as day,
                  count(*) as mannum 
                from y_wu_tjuserall 
                where  
                locate("'.$quedao.'",y_wu_tjuserall.quedao )  and 
                locate("'.$banben.'",y_wu_tjuserall.version_name )  and 
                y_wu_tjuserall.`update` BETWEEN '.$today.' and '.$theday.'  
                group by day';
        $daylist=Yii::app()->db->createCommand($daysql)->queryAll();
        foreach ($daylist as &$list){
            $dayalls[strtotime($list['day'])] = $list['mannum'];

        }

        /*付费实时*/
        $ffsql='select  
                    DATE_FORMAT(FROM_UNIXTIME(p_vip_log.add_time),"%Y-%m-%d") as day,
                    sum(p_vip_log.money) as  money ,
                    count(*) as daysums
                from p_vip_log 
                where 
                (p_vip_log.pay_channel="'.$paydo.'"  or "'.$paydo.'"="") and 
                locate("'.$quedao.'",p_vip_log.channel )  and 
                locate("'.$banben.'",p_vip_log.version_name )  and
                p_vip_log.`status` = 1 AND  
                p_vip_log.add_time  
                BETWEEN '.$today.' and '.$theday.' 
                     group by day with rollup';
        $fflist=Yii::app()->db_zbdindan->createCommand($ffsql)->queryAll();
        foreach ($fflist as &$flist){
            $ff[strtotime($flist['day'])] = $flist['money'];
            $ffdo[strtotime($flist['day'])] = $flist['daysums'];
        }
        /*付费用户实时*/
        $usersql='select 
                    count(t.mansums) as mansums, t.day as day
                    from  (select  
                        DATE_FORMAT(FROM_UNIXTIME(p_vip_log.add_time),"%Y-%m-%d") as day,
                        p_vip_log.device_id as userid,
                        count(*) as mansums
                    from p_vip_log 
                    where 
                          (p_vip_log.pay_channel="'.$paydo.'"  or "'.$paydo.'"="") and 
                       locate("'.$quedao.'",p_vip_log.channel )  and 
                locate("'.$banben.'",p_vip_log.version_name )  and
                        p_vip_log.`status` = 1 AND  
                        p_vip_log.add_time  
                       BETWEEN '.$today.' and '.$theday.' 
                         group by userid) as t 
                         GROUP BY t.`day`  with rollup';


        $ulist=Yii::app()->db_zbdindan->createCommand($usersql)->queryAll();
        foreach ($ulist as &$thelist){
            $ffu[strtotime($thelist['day'])] = $thelist['mansums'];

        }

        $listalls=array();
        for ($i=0;$i<$listsize;$i++){
            $lnum= $today+$i*24*60*60;
            $listalls['tablelist'][$i]['dateall']=date('m-d',$today+$i*24*60*60);
            $listalls['tablelist'][$i]['mannum']=empty($dayalls[$lnum])?'0':$dayalls[$lnum];
            $listalls['tablelist'][$i]['ffday']=empty($ffu[$lnum])?'0':$ffu[$lnum];
            $listalls['tablelist'][$i]['ffsums']=empty($ffdo[$lnum])?'0':$ffdo[$lnum];
            $listalls['tablelist'][$i]['money']=empty($ff[$lnum])?'0':$ff[$lnum];
            $listalls['zxianmoney'][$i]=empty($ff[$lnum])?'0':$ff[$lnum];
            $listalls['zxianffsums'][$i]=empty($ffdo[$lnum])?'0':$ffdo[$lnum];
            $listalls['zxianffday'][$i]=empty($ffu[$lnum])?'0':$ffu[$lnum];
        }
        $listalls['allmoney']=empty($ff[0])?'0':$ff[0];
        $listalls['allffsums']=empty($ffdo[0])?'0':$ffdo[0];
        $listalls['allffday']=empty($ffu[0])?'0':$ffu[0];

        return $listalls;
    }

    private function  listfftimeall($startday,$endday,$qd='',$bb='',$pay=''){
        /*今日*/
        $today =intval($startday);
        $theday = intval($endday);
        $listsize = ($theday-$today)/3600/24;

        $quedao = $qd;
        $banben = $bb;
        $paydo = $pay;

        $dayalls =array();
        $ffu =array();
        $ffdo =array();
        $ff =array();
        $showdate =array();
        for($i=0;$i<$listsize*24;$i++)
        {

            $dayalls[$today+$i*60*60]=0;
            $ffu[$today+$i*60*60]=0;
            $ffdo[$today+$i*60*60]=0;
            $ff[$today+$i*60*60]=0;

            //$showdate[]=date('m-d',$today+$i*24*60*60);
        }
       // $this->_datelistall = $showdate;
        $daysql='select 
                  DATE_FORMAT(FROM_UNIXTIME(y_wu_tjuserall.`update`),"%Y-%m-%d %H:00") as time,
                  count(*) as mannum 
                from y_wu_tjuserall 
                where  
                locate("'.$quedao.'",y_wu_tjuserall.quedao )  and 
                locate("'.$banben.'",y_wu_tjuserall.version_name )  and 
                y_wu_tjuserall.`update` BETWEEN '.$today.' and '.$theday.'  
                group by time ';
        $daylist=Yii::app()->db->createCommand($daysql)->queryAll();
        foreach ($daylist as &$list){
            $dayalls[strtotime($list['time'])] = $list['mannum'];

        }

        /*付费实时*/
        $ffsql='select  
                    DATE_FORMAT(FROM_UNIXTIME(p_vip_log.add_time),"%Y-%m-%d %H:00") as time,
                    sum(p_vip_log.money) as  money ,
                    count(*) as daysums
                from p_vip_log 
                where 
                (p_vip_log.pay_channel="'.$paydo.'"  or "'.$paydo.'"="") and 
                locate("'.$quedao.'",p_vip_log.channel )  and 
                locate("'.$banben.'",p_vip_log.version_name )  and
                p_vip_log.`status` = 1 AND  
                p_vip_log.add_time  
                BETWEEN '.$today.' and '.$theday.' 
                     group by time with rollup';
        $fflist=Yii::app()->db_zbdindan->createCommand($ffsql)->queryAll();
        foreach ($fflist as &$flist){
            $ff[strtotime($flist['time'])] = $flist['money'];
            $ffdo[strtotime($flist['time'])] = $flist['daysums'];
        }
        /*付费用户实时*/
        $usersql='select 
                    count(t.mansums) as mansums, t.time as time
                    from  (select  
                        DATE_FORMAT(FROM_UNIXTIME(p_vip_log.add_time),"%Y-%m-%d %H:00") as time,
                        p_vip_log.device_id as userid,
                        count(*) as mansums
                    from p_vip_log 
                    where 
                        (p_vip_log.pay_channel="'.$paydo.'"  or "'.$paydo.'"="") and 
                      locate("'.$quedao.'",p_vip_log.channel )  and 
                locate("'.$banben.'",p_vip_log.version_name )  and
                        p_vip_log.`status` = 1 AND  
                        p_vip_log.add_time  
                       BETWEEN '.$today.' and '.$theday.' 
                         group by userid) as t 
                         GROUP BY t.time  with rollup';


        $ulist=Yii::app()->db_zbdindan->createCommand($usersql)->queryAll();
        foreach ($ulist as &$thelist){
            $ffu[strtotime($thelist['time'])] = $thelist['mansums'];

        }

        $listalls=array();
        for ($i=0;$i<$listsize*24;$i++){
            $lnum= $today+$i*60*60;
            $listalls['tablelist'][$i]['dateall']=date('m-d H',$today+$i*60*60);
            $listalls['tablelist'][$i]['mannum']=empty($dayalls[$lnum])?'0':$dayalls[$lnum];
            $listalls['tablelist'][$i]['ffday']=empty($ffu[$lnum])?'0':$ffu[$lnum];
            $listalls['tablelist'][$i]['ffsums']=empty($ffdo[$lnum])?'0':$ffdo[$lnum];
            $listalls['tablelist'][$i]['money']=empty($ff[$lnum])?'0':$ff[$lnum];
            $listalls['zxianmoney'][$i]=empty($ff[$lnum])?'0':$ff[$lnum];
            $listalls['zxianffsums'][$i]=empty($ffdo[$lnum])?'0':$ffdo[$lnum];
            $listalls['zxianffday'][$i]=empty($ffu[$lnum])?'0':$ffu[$lnum];
        }
//        $listalls['allmoney']=empty($ff[0])?'0':$ff[0];
//        $listalls['allffsums']=empty($ffdo[0])?'0':$ffdo[0];
//        $listalls['allffday']=empty($ffu[0])?'0':$ffu[0];

        return $listalls;
    }

/*&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&付费转化&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&*/
    public  function  actionpaychang(){
        $index=6;
        $page = intval(Yii::app()->request->getParam('page'));

        $timetype = intval(Yii::app()->request->getParam('timetype'));
        if(isset($_POST['ztqs'])){
            $inputfrom =$_POST['ztqs'];
            $starttime  = empty($_POST['ztqs']['starttime'])?strtotime(date('Y-m-d',strtotime('-7 days'))):strtotime($_POST['ztqs']['starttime']);
            $endtime= empty($_POST['ztqs']['endtime'])?strtotime('today'):strtotime($_POST['ztqs']['endtime']);
            $qdone=empty($_POST['ztqs']['qdone'])?'':trim($_POST['ztqs']['qdone']);
            $qdtwo=empty($_POST['ztqs']['qdtwo'])?'':trim($_POST['ztqs']['qdtwo']);
            if($qdone){
                $quedao=$qdone.'-'.$qdtwo;
            }else{
                $quedao='';
            }
            //
            $bb=trim($_POST['ztqs']['bb']);
            $pay=empty($_POST['ztqs']['pay'])?'':intval($_POST['ztqs']['pay']);

            if($timetype){
                $this->_dayupsum=$this->paytime($starttime,$endtime,$quedao,$bb,$pay);
                $listtimealls= $this->_dayupsum['tablelist'];
            }else{
                $this->_dayupsum=$this->payday($starttime,$endtime,$quedao,$bb,$pay);
                $listtimealls= $this->_dayupsum['tablelist'];
            }

        }else{
            $inputfrom =array();
            $starttime  = strtotime(date('Y-m-d',strtotime('-7 days')));
            $endtime= strtotime('today');

            if($timetype){
                $this->_dayupsum=$this->paytime($starttime,$endtime,'','','');
                $listtimealls= $this->_dayupsum['tablelist'];

                // $listalls=$this->listffdayall($starttime,$endtime,'','','');
            }else{
                $this->_dayupsum=$this->payday($starttime,$endtime,'','','');
                $listtimealls= $this->_dayupsum['tablelist'];
            }
        }

        $alltj=array();
        if($page){
            if($page ==1){
                $alltj['names'] ='1小时内';
                $alltj['dayhours'] =  $this->_dayupsum['zxianhour'];
            }else if($page ==2){
                $alltj['names'] ='一天内';
                $alltj['dayhours'] =  $this->_dayupsum['zxianday'];
            }else if($page ==3){
                $alltj['names'] ='三天内';
                $alltj['dayhours'] =  $this->_dayupsum['zxianthreeday'];
            }else if($page ==4){
                $alltj['names'] ='七天内';
                $alltj['dayhours'] =  $this->_dayupsum['zxiansevenday'];
            }else{
                $alltj['names'] ='10分钟内';
                $alltj['dayhours'] =  $this->_dayupsum['zxianten'];
            }

        }else{
            $alltj['names'] ='10分钟内';
            $alltj['dayhours'] =  $this->_dayupsum['zxianten'];
        }

        /*树状图数据*/
        $alltj['shu']=$this->_dayupsum['shu'];

        /*树状图结束*/
        $this->_bbqdlist=$this->listqdbb();



        $this->render('paychang', array (
            //'listalls'=>$listalls,
            'listtimealls'=>$listtimealls,
            'timetype'=>$timetype,
            'alltj'=>$alltj,
            'res'=>$index,
            'pages'=>$page,
            'starttime'=>$starttime,
            'endtime'=>$endtime ,
            'searchfrom'=>$inputfrom
        ));
    }

    /*查询时间段计算*/
    private  function payday($startday,$endday,$qd='',$bb='',$pay=''){
        /*今日*/
        $today =intval($startday);
        $theday = intval($endday);
        $listsize = ($theday-$today)/3600/24;

        $quedao = $qd;
        $banben = $bb;
        $paydo = $pay;

        $tenalls =array();
        $hourlls =array();
        $dayalls =array();
        $threealls=array();
        $sevenalls =array();
        $sanzhoualls =array();
        $sizhoualls =array();
        $wuzhoualls =array();
        $showdate=array();
        for($i=0;$i<$listsize;$i++)
        {
            $showdate[]=date('m-d',$today+$i*24*60*60);
        }
        $this->_datelistall = $showdate;
        $tensql='SELECT 
                    t.p_time as p_time,count(*)as daysum
                from(
                select 
                    pingoula_ad.p_vip_log.device_id as p_devid,
                    DATE_FORMAT(FROM_UNIXTIME(pingoula_ad.p_vip_log.add_time),"%Y-%m-%d") as p_time, 
                     man_tjdb.y_wu_tjuserall.device_id   as m_devid ,
                    DATE_FORMAT(FROM_UNIXTIME(man_tjdb.y_wu_tjuserall.`update`),"%Y-%m-%d %H:00:00")as m_time,
                    ROUND((pingoula_ad.p_vip_log.add_time-man_tjdb.y_wu_tjuserall.`update`)/60) as dotime
                     
                from pingoula_ad.p_vip_log
                LEFT JOIN 
                    man_tjdb.y_wu_tjuserall ON  
                    pingoula_ad.p_vip_log.device_id = man_tjdb.y_wu_tjuserall.device_id  
                WHERE 
                        (p_vip_log.pay_channel="'.$paydo.'"  or "'.$paydo.'"="") and 
                         locate("'.$quedao.'",p_vip_log.channel )  and 
                locate("'.$banben.'",p_vip_log.version_name )  and
                        pingoula_ad.p_vip_log.recharge_type =1 and 
                        pingoula_ad.p_vip_log.status =1 and
                        pingoula_ad.p_vip_log.add_time 
                      BETWEEN '.$today.' and '.$theday.' 
                    and 
                    man_tjdb.y_wu_tjuserall.device_id is not null
                    HAVING dotime BETWEEN 0 and 10
                    )t
                    GROUP BY t.p_time
 
                    ';
        $tenlist=Yii::app()->db_zbdindan->createCommand($tensql)->queryAll();
        foreach ($tenlist as &$list){
            $tenalls[strtotime($list['p_time'])] = $list['daysum'];

        }

        $hoursql='SELECT 
                    t.p_time as p_time,count(*)as daysum
                from(
                select 
                    pingoula_ad.p_vip_log.device_id as p_devid,
                    DATE_FORMAT(FROM_UNIXTIME(pingoula_ad.p_vip_log.add_time),"%Y-%m-%d") as p_time, 
                     man_tjdb.y_wu_tjuserall.device_id   as m_devid ,
                    DATE_FORMAT(FROM_UNIXTIME(man_tjdb.y_wu_tjuserall.`update`),"%Y-%m-%d %H:00:00")as m_time,
                    ROUND((pingoula_ad.p_vip_log.add_time-man_tjdb.y_wu_tjuserall.`update`)/60) as dotime
                     
                from pingoula_ad.p_vip_log
                LEFT JOIN 
                    man_tjdb.y_wu_tjuserall ON  
                    pingoula_ad.p_vip_log.device_id = man_tjdb.y_wu_tjuserall.device_id  
                WHERE 
                        (p_vip_log.pay_channel="'.$paydo.'"  or "'.$paydo.'"="") and 
                         locate("'.$quedao.'",p_vip_log.channel )  and 
                locate("'.$banben.'",p_vip_log.version_name )  and
                        pingoula_ad.p_vip_log.recharge_type =1 and 
                        pingoula_ad.p_vip_log.status =1 and
                        pingoula_ad.p_vip_log.add_time 
                      BETWEEN '.$today.' and '.$theday.' 
                    and 
                    man_tjdb.y_wu_tjuserall.device_id is not null
                    HAVING dotime BETWEEN 10 and 60
                    )t
                    GROUP BY t.p_time
                    ';
        $hourlist=Yii::app()->db_zbdindan->createCommand($hoursql)->queryAll();
        foreach ($hourlist as &$hlist){
            $hourlls[strtotime($hlist['p_time'])] = $hlist['daysum'];

        }

        $daysql='SELECT 
                    t.p_time as p_time,count(*)as daysum
                from(
                select 
                    pingoula_ad.p_vip_log.device_id as p_devid,
                    DATE_FORMAT(FROM_UNIXTIME(pingoula_ad.p_vip_log.add_time),"%Y-%m-%d") as p_time, 
                     man_tjdb.y_wu_tjuserall.device_id   as m_devid ,
                    DATE_FORMAT(FROM_UNIXTIME(man_tjdb.y_wu_tjuserall.`update`),"%Y-%m-%d %H:00:00")as m_time,
                    ROUND((pingoula_ad.p_vip_log.add_time-man_tjdb.y_wu_tjuserall.`update`)/60) as dotime
                     
                from pingoula_ad.p_vip_log
                LEFT JOIN 
                    man_tjdb.y_wu_tjuserall ON  
                    pingoula_ad.p_vip_log.device_id = man_tjdb.y_wu_tjuserall.device_id  
                WHERE 
                        (p_vip_log.pay_channel="'.$paydo.'"  or "'.$paydo.'"="") and 
                         locate("'.$quedao.'",p_vip_log.channel )  and 
                locate("'.$banben.'",p_vip_log.version_name )  and
                        pingoula_ad.p_vip_log.recharge_type =1 and 
                        pingoula_ad.p_vip_log.status =1 and
                        pingoula_ad.p_vip_log.add_time 
                      BETWEEN '.$today.' and '.$theday.' 
                    and 
                    man_tjdb.y_wu_tjuserall.device_id is not null
                    HAVING dotime BETWEEN 60 and 1440
                    )t
                    GROUP BY t.p_time
                    ';
        $daylist=Yii::app()->db_zbdindan->createCommand($daysql)->queryAll();
        foreach ($daylist as &$dlist){
            $dayalls[strtotime($dlist['p_time'])] = $dlist['daysum'];

        }
        $threesql='SELECT 
                    t.p_time as p_time,count(*)as daysum
                from(
                select 
                    pingoula_ad.p_vip_log.device_id as p_devid,
                    DATE_FORMAT(FROM_UNIXTIME(pingoula_ad.p_vip_log.add_time),"%Y-%m-%d") as p_time, 
                     man_tjdb.y_wu_tjuserall.device_id   as m_devid ,
                    DATE_FORMAT(FROM_UNIXTIME(man_tjdb.y_wu_tjuserall.`update`),"%Y-%m-%d %H:00:00")as m_time,
                    ROUND((pingoula_ad.p_vip_log.add_time-man_tjdb.y_wu_tjuserall.`update`)/60) as dotime
                     
                from pingoula_ad.p_vip_log
                LEFT JOIN 
                    man_tjdb.y_wu_tjuserall ON  
                    pingoula_ad.p_vip_log.device_id = man_tjdb.y_wu_tjuserall.device_id  
                WHERE 
                        (p_vip_log.pay_channel="'.$paydo.'"  or "'.$paydo.'"="") and 
                         locate("'.$quedao.'",p_vip_log.channel )  and 
                locate("'.$banben.'",p_vip_log.version_name )  and
                        pingoula_ad.p_vip_log.recharge_type =1 and 
                        pingoula_ad.p_vip_log.status =1 and
                        pingoula_ad.p_vip_log.add_time 
                      BETWEEN '.$today.' and '.$theday.' 
                    and 
                    man_tjdb.y_wu_tjuserall.device_id is not null
                    HAVING dotime BETWEEN 1440 and 3*1440
                    )t
                    GROUP BY t.p_time
                    ';
        $threelist=Yii::app()->db_zbdindan->createCommand($threesql)->queryAll();
        foreach ($threelist as &$thlist){
            $threealls[strtotime($thlist['p_time'])] = $thlist['daysum'];

        }
        $sevensql='SELECT 
                    t.p_time as p_time,count(*)as daysum
                from(
                select 
                    pingoula_ad.p_vip_log.device_id as p_devid,
                    DATE_FORMAT(FROM_UNIXTIME(pingoula_ad.p_vip_log.add_time),"%Y-%m-%d") as p_time, 
                     man_tjdb.y_wu_tjuserall.device_id   as m_devid ,
                    DATE_FORMAT(FROM_UNIXTIME(man_tjdb.y_wu_tjuserall.`update`),"%Y-%m-%d %H:00:00")as m_time,
                    ROUND((pingoula_ad.p_vip_log.add_time-man_tjdb.y_wu_tjuserall.`update`)/60) as dotime
                     
                from pingoula_ad.p_vip_log
                LEFT JOIN 
                    man_tjdb.y_wu_tjuserall ON  
                    pingoula_ad.p_vip_log.device_id = man_tjdb.y_wu_tjuserall.device_id  
                WHERE 
                        (p_vip_log.pay_channel="'.$paydo.'"  or "'.$paydo.'"="") and 
                         locate("'.$quedao.'",p_vip_log.channel )  and 
                locate("'.$banben.'",p_vip_log.version_name )  and
                        pingoula_ad.p_vip_log.recharge_type =1 and 
                        pingoula_ad.p_vip_log.status =1 and
                        pingoula_ad.p_vip_log.add_time 
                      BETWEEN '.$today.' and '.$theday.' 
                    and 
                    man_tjdb.y_wu_tjuserall.device_id is not null
                    HAVING dotime BETWEEN 3*1440 and 7*1440
                    )t
                    GROUP BY t.p_time
                    ';
        $sevenlist=Yii::app()->db_zbdindan->createCommand($sevensql)->queryAll();
        foreach ($sevenlist as &$slist){
            $sevenalls[strtotime($slist['p_time'])] = $slist['daysum'];

        }

        if($listsize>=21){
            $sansql='SELECT 
                    t.p_time as p_time,count(*)as daysum
                from(
                select 
                    pingoula_ad.p_vip_log.device_id as p_devid,
                    DATE_FORMAT(FROM_UNIXTIME(pingoula_ad.p_vip_log.add_time),"%Y-%m-%d") as p_time, 
                     man_tjdb.y_wu_tjuserall.device_id   as m_devid ,
                    DATE_FORMAT(FROM_UNIXTIME(man_tjdb.y_wu_tjuserall.`update`),"%Y-%m-%d %H:00:00")as m_time,
                    ROUND((pingoula_ad.p_vip_log.add_time-man_tjdb.y_wu_tjuserall.`update`)/60) as dotime
                     
                from pingoula_ad.p_vip_log
                LEFT JOIN 
                    man_tjdb.y_wu_tjuserall ON  
                    pingoula_ad.p_vip_log.device_id = man_tjdb.y_wu_tjuserall.device_id  
                WHERE 
                        (p_vip_log.pay_channel="'.$paydo.'"  or "'.$paydo.'"="") and 
                         locate("'.$quedao.'",p_vip_log.channel )  and 
                locate("'.$banben.'",p_vip_log.version_name )  and
                        pingoula_ad.p_vip_log.recharge_type =1 and 
                        pingoula_ad.p_vip_log.status =1 and
                        pingoula_ad.p_vip_log.add_time 
                      BETWEEN '.$today.' and '.$theday.' 
                    and 
                    man_tjdb.y_wu_tjuserall.device_id is not null
                    HAVING dotime BETWEEN  7*1440  and 3*7*1440
                    )t
                    GROUP BY t.p_time
                    ';
            $sanlist=Yii::app()->db_zbdindan->createCommand($sansql)->queryAll();
            foreach ($sanlist as &$szlist){
                $sanzhoualls[strtotime($szlist['p_time'])] = $szlist['daysum'];

            }
        }
        if($listsize>=28){
            $sisql='SELECT 
                    t.p_time as p_time,count(*)as daysum
                from(
                select 
                    pingoula_ad.p_vip_log.device_id as p_devid,
                    DATE_FORMAT(FROM_UNIXTIME(pingoula_ad.p_vip_log.add_time),"%Y-%m-%d") as p_time, 
                     man_tjdb.y_wu_tjuserall.device_id   as m_devid ,
                    DATE_FORMAT(FROM_UNIXTIME(man_tjdb.y_wu_tjuserall.`update`),"%Y-%m-%d %H:00:00")as m_time,
                    ROUND((pingoula_ad.p_vip_log.add_time-man_tjdb.y_wu_tjuserall.`update`)/60) as dotime
                     
                from pingoula_ad.p_vip_log
                LEFT JOIN 
                    man_tjdb.y_wu_tjuserall ON  
                    pingoula_ad.p_vip_log.device_id = man_tjdb.y_wu_tjuserall.device_id  
                WHERE 
                        (p_vip_log.pay_channel="'.$paydo.'"  or "'.$paydo.'"="") and 
                         locate("'.$quedao.'",p_vip_log.channel )  and 
                locate("'.$banben.'",p_vip_log.version_name )  and
                        pingoula_ad.p_vip_log.recharge_type =1 and 
                        pingoula_ad.p_vip_log.status =1 and
                        pingoula_ad.p_vip_log.add_time 
                      BETWEEN '.$today.' and '.$theday.' 
                    and 
                    man_tjdb.y_wu_tjuserall.device_id is not null
                    HAVING dotime BETWEEN  3*7*1440  and 4*7*1440
                    )t
                    GROUP BY t.p_time
                    ';
            $silist=Yii::app()->db_zbdindan->createCommand($sisql)->queryAll();
            foreach ($silist as &$szlist){
                $sizhoualls[strtotime($szlist['p_time'])] = $szlist['daysum'];

            }
        }
        if($listsize>=35){
            $wusql='SELECT 
                    t.p_time as p_time,count(*)as daysum
                from(
                select 
                    pingoula_ad.p_vip_log.device_id as p_devid,
                    DATE_FORMAT(FROM_UNIXTIME(pingoula_ad.p_vip_log.add_time),"%Y-%m-%d") as p_time, 
                     man_tjdb.y_wu_tjuserall.device_id   as m_devid ,
                    DATE_FORMAT(FROM_UNIXTIME(man_tjdb.y_wu_tjuserall.`update`),"%Y-%m-%d %H:00:00")as m_time,
                    ROUND((pingoula_ad.p_vip_log.add_time-man_tjdb.y_wu_tjuserall.`update`)/60) as dotime
                     
                from pingoula_ad.p_vip_log
                LEFT JOIN 
                    man_tjdb.y_wu_tjuserall ON  
                    pingoula_ad.p_vip_log.device_id = man_tjdb.y_wu_tjuserall.device_id  
                WHERE 
                        (p_vip_log.pay_channel="'.$paydo.'"  or "'.$paydo.'"="") and 
                         locate("'.$quedao.'",p_vip_log.channel )  and 
                locate("'.$banben.'",p_vip_log.version_name )  and
                        pingoula_ad.p_vip_log.recharge_type =1 and 
                        pingoula_ad.p_vip_log.status =1 and
                        pingoula_ad.p_vip_log.add_time 
                      BETWEEN '.$today.' and '.$theday.' 
                    and 
                    man_tjdb.y_wu_tjuserall.device_id is not null
                    HAVING dotime >5*7*1440
                    )t
                    GROUP BY t.p_time
                    ';
            $wulist=Yii::app()->db_zbdindan->createCommand($wusql)->queryAll();
            foreach ($wulist as &$wlist){
                $wuzhoualls[strtotime($wlist['p_time'])] = $wlist['daysum'];

            }
        }
        $listalls=array();
        for ($i=0;$i<$listsize;$i++){
            $lnum= $today+$i*24*60*60;
            $listalls['tablelist'][$i]['dateall']=date('m-d',$today+$i*24*60*60);
            $listalls['tablelist'][$i]['ten']=empty($tenalls[$lnum])?'0':$tenalls[$lnum];
            $listalls['tablelist'][$i]['hour']=empty($hourlls[$lnum])?'0':$hourlls[$lnum];
            $listalls['tablelist'][$i]['day']=empty($dayalls[$lnum])?'0':$dayalls[$lnum];
            $listalls['tablelist'][$i]['threeday']=empty($threealls[$lnum])?'0':$threealls[$lnum];
            $listalls['tablelist'][$i]['sevenday']=empty($sevenalls[$lnum])?'0':$sevenalls[$lnum];
            $listalls['zxianten'][$i]=empty($tenalls[$lnum])?'0':$tenalls[$lnum];
            $listalls['zxianhour'][$i]=empty($hourlls[$lnum])?'0':$hourlls[$lnum];
            $listalls['zxianday'][$i]=empty($dayalls[$lnum])?'0':$dayalls[$lnum];
            $listalls['zxianthreeday'][$i]=empty($threealls[$lnum])?'0':$threealls[$lnum];
            $listalls['zxiansevenday'][$i]=empty($sevenalls[$lnum])?'0':$sevenalls[$lnum];
        }

        $listalls['shu'][0]= array_sum($tenalls);
        $listalls['shu'][1]=array_sum($hourlls);
        $listalls['shu'][2]=array_sum($dayalls);
        $listalls['shu'][3]=array_sum($threealls);
        $listalls['shu'][4]=array_sum($sevenalls);
        $listalls['shu'][5]=array_sum($sanzhoualls);
        $listalls['shu'][6]=array_sum($sizhoualls);
        $listalls['shu'][7]=array_sum($wuzhoualls);
        return $listalls;

    }
    /*查询时间段计算*/
    private  function paytime($startday,$endday,$qd='',$bb='',$pay=''){
        /*今日*/
        $today =intval($startday);
        $theday = intval($endday);
        $listsize = ($theday-$today)/3600/24;

        $quedao = $qd;
        $banben = $bb;
        $paydo = $pay;

        $tenalls =array();
        $hourlls =array();
        $dayalls =array();
        $threealls=array();
        $sevenalls =array();
        $sanzhoualls =array();
        $sizhoualls =array();
        $wuzhoualls =array();
        $showdate=array();
        for($i=0;$i<$listsize*24;$i++)
        {
            $showdate[]=date('m-d H:00',$today+$i*60*60);
        }
        $this->_datelistall = $showdate;
        $tensql='SELECT 
                    t.p_time as p_time,count(*)as daysum
                from(
                select 
                    pingoula_ad.p_vip_log.device_id as p_devid,
                    DATE_FORMAT(FROM_UNIXTIME(pingoula_ad.p_vip_log.add_time),"%Y-%m-%d %H:00:00") as p_time, 
                     man_tjdb.y_wu_tjuserall.device_id   as m_devid ,
                    DATE_FORMAT(FROM_UNIXTIME(man_tjdb.y_wu_tjuserall.`update`),"%Y-%m-%d %H:00:00")as m_time,
                    ROUND((pingoula_ad.p_vip_log.add_time-man_tjdb.y_wu_tjuserall.`update`)/60) as dotime
                     
                from pingoula_ad.p_vip_log
                LEFT JOIN 
                    man_tjdb.y_wu_tjuserall ON  
                    pingoula_ad.p_vip_log.device_id = man_tjdb.y_wu_tjuserall.device_id  
                WHERE 
                        (p_vip_log.pay_channel="'.$paydo.'"  or "'.$paydo.'"="") and 
                         locate("'.$quedao.'",p_vip_log.channel )  and 
                locate("'.$banben.'",p_vip_log.version_name )  and
                        pingoula_ad.p_vip_log.recharge_type =1 and 
                        pingoula_ad.p_vip_log.status =1 and
                        pingoula_ad.p_vip_log.add_time 
                      BETWEEN '.$today.' and '.$theday.' 
                    and 
                    man_tjdb.y_wu_tjuserall.device_id is not null
                    HAVING dotime BETWEEN 0 and 10
                    )t
                    GROUP BY t.p_time
                    ';
        $tenlist=Yii::app()->db_zbdindan->createCommand($tensql)->queryAll();
        foreach ($tenlist as &$list){
            $tenalls[strtotime($list['p_time'])] = $list['daysum'];

        }
        $hoursql='SELECT 
                    t.p_time as p_time,count(*)as daysum
                from(
                select 
                    pingoula_ad.p_vip_log.device_id as p_devid,
                    DATE_FORMAT(FROM_UNIXTIME(pingoula_ad.p_vip_log.add_time),"%Y-%m-%d %H:00:00") as p_time, 
                     man_tjdb.y_wu_tjuserall.device_id   as m_devid ,
                    DATE_FORMAT(FROM_UNIXTIME(man_tjdb.y_wu_tjuserall.`update`),"%Y-%m-%d %H:00:00")as m_time,
                    ROUND((pingoula_ad.p_vip_log.add_time-man_tjdb.y_wu_tjuserall.`update`)/60) as dotime
                     
                from pingoula_ad.p_vip_log
                LEFT JOIN 
                    man_tjdb.y_wu_tjuserall ON  
                    pingoula_ad.p_vip_log.device_id = man_tjdb.y_wu_tjuserall.device_id  
                WHERE 
                        (p_vip_log.pay_channel="'.$paydo.'"  or "'.$paydo.'"="") and 
                         locate("'.$quedao.'",p_vip_log.channel )  and 
                locate("'.$banben.'",p_vip_log.version_name )  and
                        pingoula_ad.p_vip_log.recharge_type =1 and 
                        pingoula_ad.p_vip_log.status =1 and
                        pingoula_ad.p_vip_log.add_time 
                      BETWEEN '.$today.' and '.$theday.' 
                    and 
                    man_tjdb.y_wu_tjuserall.device_id is not null
                    HAVING dotime BETWEEN 10 and 60
                    )t
                    GROUP BY t.p_time
                    ';
        $hourlist=Yii::app()->db_zbdindan->createCommand($hoursql)->queryAll();
        foreach ($hourlist as &$hlist){
            $hourlls[strtotime($hlist['p_time'])] = $hlist['daysum'];

        }

        $daysql='SELECT 
                    t.p_time as p_time,count(*)as daysum
                from(
                select 
                    pingoula_ad.p_vip_log.device_id as p_devid,
                    DATE_FORMAT(FROM_UNIXTIME(pingoula_ad.p_vip_log.add_time),"%Y-%m-%d %H:00:00") as p_time, 
                     man_tjdb.y_wu_tjuserall.device_id   as m_devid ,
                    DATE_FORMAT(FROM_UNIXTIME(man_tjdb.y_wu_tjuserall.`update`),"%Y-%m-%d %H:00:00")as m_time,
                    ROUND((pingoula_ad.p_vip_log.add_time-man_tjdb.y_wu_tjuserall.`update`)/60) as dotime
                     
                from pingoula_ad.p_vip_log
                LEFT JOIN 
                    man_tjdb.y_wu_tjuserall ON  
                    pingoula_ad.p_vip_log.device_id = man_tjdb.y_wu_tjuserall.device_id  
                WHERE 
                        (p_vip_log.pay_channel="'.$paydo.'"  or "'.$paydo.'"="") and 
                         locate("'.$quedao.'",p_vip_log.channel )  and 
                locate("'.$banben.'",p_vip_log.version_name )  and
                        pingoula_ad.p_vip_log.recharge_type =1 and 
                        pingoula_ad.p_vip_log.status =1 and
                        pingoula_ad.p_vip_log.add_time 
                      BETWEEN '.$today.' and '.$theday.' 
                    and 
                    man_tjdb.y_wu_tjuserall.device_id is not null
                    HAVING dotime BETWEEN 60 and 1440
                    )t
                    GROUP BY t.p_time
                    ';
        $daylist=Yii::app()->db_zbdindan->createCommand($daysql)->queryAll();
        foreach ($daylist as &$dlist){
            $dayalls[strtotime($dlist['p_time'])] = $dlist['daysum'];

        }
        $threesql='SELECT 
                    t.p_time as p_time,count(*)as daysum
                from(
                select 
                    pingoula_ad.p_vip_log.device_id as p_devid,
                    DATE_FORMAT(FROM_UNIXTIME(pingoula_ad.p_vip_log.add_time),"%Y-%m-%d %H:00:00") as p_time, 
                     man_tjdb.y_wu_tjuserall.device_id   as m_devid ,
                    DATE_FORMAT(FROM_UNIXTIME(man_tjdb.y_wu_tjuserall.`update`),"%Y-%m-%d %H:00:00")as m_time,
                    ROUND((pingoula_ad.p_vip_log.add_time-man_tjdb.y_wu_tjuserall.`update`)/60) as dotime
                     
                from pingoula_ad.p_vip_log
                LEFT JOIN 
                    man_tjdb.y_wu_tjuserall ON  
                    pingoula_ad.p_vip_log.device_id = man_tjdb.y_wu_tjuserall.device_id  
                WHERE 
                        (p_vip_log.pay_channel="'.$paydo.'"  or "'.$paydo.'"="") and 
                         locate("'.$quedao.'",p_vip_log.channel )  and 
                locate("'.$banben.'",p_vip_log.version_name )  and
                        pingoula_ad.p_vip_log.recharge_type =1 and 
                        pingoula_ad.p_vip_log.status =1 and
                        pingoula_ad.p_vip_log.add_time 
                      BETWEEN '.$today.' and '.$theday.' 
                    and 
                    man_tjdb.y_wu_tjuserall.device_id is not null
                    HAVING dotime BETWEEN 1440 and 3*1440
                    )t
                    GROUP BY t.p_time
                    ';
        $threelist=Yii::app()->db_zbdindan->createCommand($threesql)->queryAll();
        foreach ($threelist as &$thlist){
            $threealls[strtotime($thlist['p_time'])] = $thlist['daysum'];

        }
        $sevensql='SELECT 
                    t.p_time as p_time,count(*)as daysum
                from(
                select 
                    pingoula_ad.p_vip_log.device_id as p_devid,
                    DATE_FORMAT(FROM_UNIXTIME(pingoula_ad.p_vip_log.add_time),"%Y-%m-%d %H:00:00") as p_time, 
                     man_tjdb.y_wu_tjuserall.device_id   as m_devid ,
                    DATE_FORMAT(FROM_UNIXTIME(man_tjdb.y_wu_tjuserall.`update`),"%Y-%m-%d %H:00:00")as m_time,
                    ROUND((pingoula_ad.p_vip_log.add_time-man_tjdb.y_wu_tjuserall.`update`)/60) as dotime
                     
                from pingoula_ad.p_vip_log
                LEFT JOIN 
                    man_tjdb.y_wu_tjuserall ON  
                    pingoula_ad.p_vip_log.device_id = man_tjdb.y_wu_tjuserall.device_id  
                WHERE 
                        (p_vip_log.pay_channel="'.$paydo.'"  or "'.$paydo.'"="") and 
                         locate("'.$quedao.'",p_vip_log.channel )  and 
                locate("'.$banben.'",p_vip_log.version_name )  and
                        pingoula_ad.p_vip_log.recharge_type =1 and 
                        pingoula_ad.p_vip_log.status =1 and
                        pingoula_ad.p_vip_log.add_time 
                      BETWEEN '.$today.' and '.$theday.' 
                    and 
                    man_tjdb.y_wu_tjuserall.device_id is not null
                    HAVING dotime BETWEEN 3*1440 and 7*1440
                    )t
                    GROUP BY t.p_time
                    ';
        $sevenlist=Yii::app()->db_zbdindan->createCommand($sevensql)->queryAll();
        foreach ($sevenlist as &$slist){
            $sevenalls[strtotime($slist['p_time'])] = $slist['daysum'];

        }
        if($listsize>=21){
            $sansql='SELECT 
                    t.p_time as p_time,count(*)as daysum
                from(
                select 
                    pingoula_ad.p_vip_log.device_id as p_devid,
                    DATE_FORMAT(FROM_UNIXTIME(pingoula_ad.p_vip_log.add_time),"%Y-%m-%d %H:00:00") as p_time, 
                     man_tjdb.y_wu_tjuserall.device_id   as m_devid ,
                    DATE_FORMAT(FROM_UNIXTIME(man_tjdb.y_wu_tjuserall.`update`),"%Y-%m-%d %H:00:00")as m_time,
                    ROUND((pingoula_ad.p_vip_log.add_time-man_tjdb.y_wu_tjuserall.`update`)/60) as dotime
                     
                from pingoula_ad.p_vip_log
                LEFT JOIN 
                    man_tjdb.y_wu_tjuserall ON  
                    pingoula_ad.p_vip_log.device_id = man_tjdb.y_wu_tjuserall.device_id  
                WHERE 
                        (p_vip_log.pay_channel="'.$paydo.'"  or "'.$paydo.'"="") and 
                         locate("'.$quedao.'",p_vip_log.channel )  and 
                locate("'.$banben.'",p_vip_log.version_name )  and
                        pingoula_ad.p_vip_log.recharge_type =1 and 
                        pingoula_ad.p_vip_log.status =1 and
                        pingoula_ad.p_vip_log.add_time 
                      BETWEEN '.$today.' and '.$theday.' 
                    and 
                    man_tjdb.y_wu_tjuserall.device_id is not null
                    HAVING dotime BETWEEN 7*1440 and 3*7*1440
                    )t
                    GROUP BY t.p_time
                    ';
            $sanlist=Yii::app()->db_zbdindan->createCommand($sansql)->queryAll();
            foreach ($sanlist as &$szlist){
                $sanzhoualls[strtotime($szlist['p_time'])] = $szlist['daysum'];

            }
        }
        if($listsize>=28){
            $sisql='SELECT 
                    t.p_time as p_time,count(*)as daysum
                from(
                select 
                    pingoula_ad.p_vip_log.device_id as p_devid,
                    DATE_FORMAT(FROM_UNIXTIME(pingoula_ad.p_vip_log.add_time),"%Y-%m-%d %H:00:00") as p_time, 
                     man_tjdb.y_wu_tjuserall.device_id   as m_devid ,
                    DATE_FORMAT(FROM_UNIXTIME(man_tjdb.y_wu_tjuserall.`update`),"%Y-%m-%d %H:00:00")as m_time,
                    ROUND((pingoula_ad.p_vip_log.add_time-man_tjdb.y_wu_tjuserall.`update`)/60) as dotime
                     
                from pingoula_ad.p_vip_log
                LEFT JOIN 
                    man_tjdb.y_wu_tjuserall ON  
                    pingoula_ad.p_vip_log.device_id = man_tjdb.y_wu_tjuserall.device_id  
                WHERE 
                        (p_vip_log.pay_channel="'.$paydo.'"  or "'.$paydo.'"="") and 
                         locate("'.$quedao.'",p_vip_log.channel )  and 
                locate("'.$banben.'",p_vip_log.version_name )  and
                        pingoula_ad.p_vip_log.recharge_type =1 and 
                        pingoula_ad.p_vip_log.status =1 and
                        pingoula_ad.p_vip_log.add_time 
                      BETWEEN '.$today.' and '.$theday.' 
                    and 
                    man_tjdb.y_wu_tjuserall.device_id is not null
                    HAVING dotime BETWEEN 3*7*1440 and 4*7*1440
                    )t
                    GROUP BY t.p_time
                    ';
            $silist=Yii::app()->db_zbdindan->createCommand($sisql)->queryAll();
            foreach ($silist as &$szlist){
                $sizhoualls[strtotime($szlist['p_time'])] = $szlist['daysum'];

            }
        }
        if($listsize>=35){
            $wusql='SELECT 
                    t.p_time as p_time,count(*)as daysum
                from(
                select 
                    pingoula_ad.p_vip_log.device_id as p_devid,
                    DATE_FORMAT(FROM_UNIXTIME(pingoula_ad.p_vip_log.add_time),"%Y-%m-%d %H:00:00") as p_time, 
                     man_tjdb.y_wu_tjuserall.device_id   as m_devid ,
                    DATE_FORMAT(FROM_UNIXTIME(man_tjdb.y_wu_tjuserall.`update`),"%Y-%m-%d %H:00:00")as m_time,
                    ROUND((pingoula_ad.p_vip_log.add_time-man_tjdb.y_wu_tjuserall.`update`)/60) as dotime
                     
                from pingoula_ad.p_vip_log
                LEFT JOIN 
                    man_tjdb.y_wu_tjuserall ON  
                    pingoula_ad.p_vip_log.device_id = man_tjdb.y_wu_tjuserall.device_id  
                WHERE 
                        (p_vip_log.pay_channel="'.$paydo.'"  or "'.$paydo.'"="") and 
                         locate("'.$quedao.'",p_vip_log.channel )  and 
                locate("'.$banben.'",p_vip_log.version_name )  and
                        pingoula_ad.p_vip_log.recharge_type =1 and 
                        pingoula_ad.p_vip_log.status =1 and
                        pingoula_ad.p_vip_log.add_time 
                      BETWEEN '.$today.' and '.$theday.' 
                    and 
                    man_tjdb.y_wu_tjuserall.device_id is not null
                    HAVING dotime >5*7*1440
                    )t
                    GROUP BY t.p_time
                    ';
            $wulist=Yii::app()->db_zbdindan->createCommand($wusql)->queryAll();
            foreach ($wulist as &$wlist){
                $wuzhoualls[strtotime($wlist['p_time'])] = $wlist['daysum'];

            }
        }
        $listalls=array();
        for ($i=0;$i<$listsize*24;$i++){
            $lnum= $today+$i*60*60;
            $listalls['tablelist'][$i]['dateall']=date('m-d H:00',$today+$i*60*60);
            $listalls['tablelist'][$i]['ten']=empty($tenalls[$lnum])?'0':$tenalls[$lnum];
            $listalls['tablelist'][$i]['hour']=empty($hourlls[$lnum])?'0':$hourlls[$lnum];
            $listalls['tablelist'][$i]['day']=empty($dayalls[$lnum])?'0':$dayalls[$lnum];
            $listalls['tablelist'][$i]['threeday']=empty($threealls[$lnum])?'0':$threealls[$lnum];
            $listalls['tablelist'][$i]['sevenday']=empty($sevenalls[$lnum])?'0':$sevenalls[$lnum];
            $listalls['zxianten'][$i]=empty($tenalls[$lnum])?'0':$tenalls[$lnum];
            $listalls['zxianhour'][$i]=empty($hourlls[$lnum])?'0':$hourlls[$lnum];
            $listalls['zxianday'][$i]=empty($dayalls[$lnum])?'0':$dayalls[$lnum];
            $listalls['zxianthreeday'][$i]=empty($threealls[$lnum])?'0':$threealls[$lnum];
            $listalls['zxiansevenday'][$i]=empty($sevenalls[$lnum])?'0':$sevenalls[$lnum];
        }

        $listalls['shu'][0]= array_sum($tenalls);
        $listalls['shu'][1]=array_sum($hourlls);
        $listalls['shu'][2]=array_sum($dayalls);
        $listalls['shu'][3]=array_sum($threealls);
        $listalls['shu'][4]=array_sum($sevenalls);
        $listalls['shu'][5]=array_sum($sanzhoualls);
        $listalls['shu'][6]=array_sum($sizhoualls);
        $listalls['shu'][7]=array_sum($wuzhoualls);
        return $listalls;

    }

    /*&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&付费习惯&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&*/
    public  function  actionpaymoney(){
        $index=7;
        $page = intval(Yii::app()->request->getParam('page'));
        $alltj=array();
        $timetype = intval(Yii::app()->request->getParam('timetype'));
        if(isset($_POST['ztqs'])){
            $inputfrom =$_POST['ztqs'];
            $starttime  = empty($_POST['ztqs']['starttime'])?strtotime(date('Y-m-d',strtotime('-7 days'))):strtotime($_POST['ztqs']['starttime']);
            $endtime= empty($_POST['ztqs']['endtime'])?strtotime('today'):strtotime($_POST['ztqs']['endtime']);
            $qdone=empty($_POST['ztqs']['qdone'])?'':trim($_POST['ztqs']['qdone']);
            $qdtwo=empty($_POST['ztqs']['qdtwo'])?'':trim($_POST['ztqs']['qdtwo']);
            if($qdone){
                $quedao=$qdone.'-'.$qdtwo;
            }else{
                $quedao='';
            }
            //
            $bb=trim($_POST['ztqs']['bb']);
            $pay=empty($_POST['ztqs']['pay'])?'':intval($_POST['ztqs']['pay']);
            $alltj['shu']=$this->payshu($starttime,$endtime,$quedao,$bb,$pay);
            if($timetype){
                $this->_dayupsum=$this->paychanneltime($starttime,$endtime,$quedao,$bb,$pay);
                $listtimealls= $this->_dayupsum['tablelist'];

            }else{
                $this->_dayupsum=$this->paychannelday($starttime,$endtime,$quedao,$bb,$pay);
                $listtimealls= $this->_dayupsum['tablelist'];
            }

        }else{
            $inputfrom =array();
            $starttime  = strtotime(date('Y-m-d',strtotime('-7 days')));
            $endtime= strtotime('today');
            $alltj['shu']=$this->payshu($starttime,$endtime,'','','');
            if($timetype){
                $this->_dayupsum=$this->paychanneltime($starttime,$endtime,'','','');
                $listtimealls= $this->_dayupsum['tablelist'];

                // $listalls=$this->listffdayall($starttime,$endtime,'','','');
            }else{
                $this->_dayupsum=$this->paychannelday($starttime,$endtime,'','','');
                $listtimealls= $this->_dayupsum['tablelist'];
            }
        }


        if($page){
            if($page ==1){

                $alltj['dayhours'] =  $this->_dayupsum['zxman'];
            }else{

                $alltj['dayhours'] =  $this->_dayupsum['zx'];
            }

        }else{

            $alltj['dayhours'] =  $this->_dayupsum['zx'];
        }


        $this->_bbqdlist=$this->listqdbb();



        $this->render('paymoney', array (
            //'listalls'=>$listalls,
            'listtimealls'=>$listtimealls,
            'timetype'=>$timetype,
            'alltj'=>$alltj,
            'res'=>$index,
            'pages'=>$page,
            'starttime'=>$starttime,
            'endtime'=>$endtime ,
            'searchfrom'=>$inputfrom
        ));
    }
    /*渠道统计*/

    private  function paychannelday($startday,$endday,$qd='',$bb='',$pay=''){
        /*今日*/
        $today =intval($startday);
        $theday = intval($endday);
        $listsize = ($theday-$today)/3600/24;

        $quedao = $qd;
        $banben = $bb;
        $paydo = $pay;

        $zrfalls =array();
        $wxalls =array();
        $alialls =array();
        $zrfmanalls=array();
        $wxmanalls =array();
        $alimanalls =array();
        $showdate=array();
        for($i=0;$i<$listsize;$i++)
        {
            $showdate[]=date('m-d',$today+$i*24*60*60);
        }
        $this->_datelistall = $showdate;
        $zrfsql='select  
                    DATE_FORMAT(FROM_UNIXTIME(p_vip_log.add_time),"%Y-%m-%d") as time,
                    sum(p_vip_log.money) as  money ,
                    count(*) as  mannum 
                    from p_vip_log 
                    where 
                        p_vip_log.pay_channel=1 and 
                     locate("'.$quedao.'",p_vip_log.channel )  and 
                        locate("'.$banben.'",p_vip_log.version_name )  and
                        p_vip_log.`status` = 1 AND 
                     p_vip_log.add_time 
                     BETWEEN '.$today.' and '.$theday.' 
                    group by time
                ';

        $zrflist=Yii::app()->db_zbdindan->createCommand($zrfsql)->queryAll();
        foreach ($zrflist as &$list){
            $zrfalls[strtotime($list['time'])] = $list['money'];
            $zrfmanalls[strtotime($list['time'])] = $list['mannum'];

        }

        $wxsql='select  
                    DATE_FORMAT(FROM_UNIXTIME(p_vip_log.add_time),"%Y-%m-%d") as time,
                    sum(p_vip_log.money) as  money ,
                    count(*) as  mannum 
                    from p_vip_log 
                    where 
                        p_vip_log.pay_channel=2 and 
                     locate("'.$quedao.'",p_vip_log.channel )  and 
                        locate("'.$banben.'",p_vip_log.version_name )  and
                        p_vip_log.`status` = 1 AND 
                     p_vip_log.add_time 
                     BETWEEN '.$today.' and '.$theday.' 
                    group by time
                ';
        $wxlist=Yii::app()->db_zbdindan->createCommand($wxsql)->queryAll();
        foreach ($wxlist as &$wlist){
            $wxalls[strtotime($wlist['time'])] = $wlist['money'];
            $wxmanalls[strtotime($wlist['time'])] = $wlist['mannum'];

        }

        $alisql='select  
                    DATE_FORMAT(FROM_UNIXTIME(p_vip_log.add_time),"%Y-%m-%d") as time,
                    sum(p_vip_log.money) as  money ,
                    count(*) as  mannum 
                    from p_vip_log 
                    where 
                        p_vip_log.pay_channel=3 and 
                      locate("'.$quedao.'",p_vip_log.channel )  and 
                        locate("'.$banben.'",p_vip_log.version_name )  and
                        p_vip_log.`status` = 1 AND 
                     p_vip_log.add_time 
                     BETWEEN '.$today.' and '.$theday.' 
                    group by time 
                ';
        $alilist=Yii::app()->db_zbdindan->createCommand($alisql)->queryAll();
        foreach ($alilist as &$alist){
            $alialls[strtotime($alist['time'])] = $alist['money'];
            $alimanalls[strtotime($alist['time'])] = $alist['mannum'];

        }

        $listalls=array();
        for ($i=0;$i<$listsize;$i++){
            $lnum= $today+$i*24*60*60;
            $listalls['tablelist'][$i]['dateall']=date('m-d',$today+$i*24*60*60);
            $listalls['tablelist'][$i]['zrfmoney']=empty($zrfalls[$lnum])?'0':$zrfalls[$lnum];
            $listalls['tablelist'][$i]['zrfman']=empty($zrfmanalls[$lnum])?'0':$zrfmanalls[$lnum];
            $listalls['tablelist'][$i]['wxmoney']=empty($wxalls[$lnum])?'0':$wxalls[$lnum];
            $listalls['tablelist'][$i]['wxman']=empty($wxmanalls[$lnum])?'0':$wxmanalls[$lnum];
            $listalls['tablelist'][$i]['alimoney']=empty($alialls[$lnum])?'0':$alialls[$lnum];
            $listalls['tablelist'][$i]['aliman']=empty($alimanalls[$lnum])?'0':$alimanalls[$lnum];
            $listalls['zx']['zrf'][$i]=empty($zrfalls[$lnum])?'0':$zrfalls[$lnum];
            $listalls['zxman']['zrf'][$i]=empty($zrfmanalls[$lnum])?'0':$zrfmanalls[$lnum];
            $listalls['zx']['wx'][$i]=empty($wxalls[$lnum])?'0':$wxalls[$lnum];
            $listalls['zxman']['wx'][$i]=empty($wxmanalls[$lnum])?'0':$wxmanalls[$lnum];
            $listalls['zx']['ali'][$i]=empty($alialls[$lnum])?'0':$alialls[$lnum];
            $listalls['zxman']['ali'][$i]=empty($alimanalls[$lnum])?'0':$alimanalls[$lnum];
        }

//        $listalls['allmoney']=empty($ff[0])?'0':$ff[0];
//        $listalls['allffsums']=empty($ffdo[0])?'0':$ffdo[0];
//        $listalls['allffday']=empty($ffu[0])?'0':$ffu[0];
        return $listalls;

    }
    /*付费习惯树状图*/
    private  function payshu($startday,$endday,$qd='',$bb='',$pay=''){
        /*今日*/
        $today =intval($startday);
        $theday = intval($endday);
        $listsize = ($theday-$today)/3600/24;

        $quedao = $qd;
        $banben = $bb;
        $paydo = $pay;

//        $tenalls =array(0,0,0);
//        $hourlls =array(0,0,0);
//        $dayalls =array(0,0,0);
//        $threealls=array(0,0,0);
//        $sevenalls =array(0,0,0);
//        $sanzhoualls =array(0,0,0);
//        $sizhoualls =array(0,0,0);
//        $wuzhoualls =array(0,0,0);
//        $showdate=array();
//        for($i=0;$i<$listsize*24;$i++)
//        {
//            $showdate[]=date('m-d H:00',$today+$i*60*60);
//        }
//        $this->_datelistall = $showdate;
//        $tensql='SELECT
//                  count(*)as daysum,t.pay_channel as pch
//                from(
//                select
//                    ROUND((pingoula_ad.p_vip_log.add_time-man_tjdb.y_wu_tjuserall.`update`)/60) as dotime,
//                     pingoula_ad.p_vip_log.pay_channel as pay_channel
//                from pingoula_ad.p_vip_log
//                LEFT JOIN
//                    man_tjdb.y_wu_tjuserall ON
//                    pingoula_ad.p_vip_log.device_id = man_tjdb.y_wu_tjuserall.device_id
//                WHERE
//                        (p_vip_log.pay_channel="'.$paydo.'"  or "'.$paydo.'"="") and
//                        locate("'.$quedao.'",p_vip_log.channel )  and
//                        locate("'.$banben.'",p_vip_log.version_name )  and
//                        pingoula_ad.p_vip_log.status =1 and
//                        pingoula_ad.p_vip_log.add_time
//                      BETWEEN '.$today.' and '.$theday.'
//                    and
//                    man_tjdb.y_wu_tjuserall.device_id is not null
//                    HAVING pay_channel in(1,2,3) and dotime BETWEEN 0 and 10
//                    )t
//                    GROUP BY pch
//                    ';
//        $tenlist=Yii::app()->db_zbdindan->createCommand($tensql)->queryAll();
//        foreach ($tenlist as &$list){
//            $tenalls[$list['pch']-1] = $list['daysum'];
//        }
//
//        $hoursql='SELECT
//                  count(*)as daysum,t.pay_channel as pch
//                from(
//                select
//                    ROUND((pingoula_ad.p_vip_log.add_time-man_tjdb.y_wu_tjuserall.`update`)/60) as dotime,
//                     pingoula_ad.p_vip_log.pay_channel as pay_channel
//                from pingoula_ad.p_vip_log
//                LEFT JOIN
//                    man_tjdb.y_wu_tjuserall ON
//                    pingoula_ad.p_vip_log.device_id = man_tjdb.y_wu_tjuserall.device_id
//                WHERE
//                        (p_vip_log.pay_channel="'.$paydo.'"  or "'.$paydo.'"="") and
//                        locate("'.$quedao.'",p_vip_log.channel )  and
//                        locate("'.$banben.'",p_vip_log.version_name )  and
//                        pingoula_ad.p_vip_log.status =1 and
//                        pingoula_ad.p_vip_log.add_time
//                      BETWEEN '.$today.' and '.$theday.'
//                    and
//                    man_tjdb.y_wu_tjuserall.device_id is not null
//                    HAVING pay_channel in(1,2,3) and dotime BETWEEN 10 and 60
//                    )t
//                    GROUP BY pch
//                    ';
//        $hourlist=Yii::app()->db_zbdindan->createCommand($hoursql)->queryAll();
//        foreach ($hourlist as &$hlist){
//            $hourlls[$hlist['pch']-1] = $hlist['daysum'];
//
//        }
//
//        $daysql='SELECT
//                  count(*)as daysum,t.pay_channel as pch
//                from(
//                select
//                    ROUND((pingoula_ad.p_vip_log.add_time-man_tjdb.y_wu_tjuserall.`update`)/60) as dotime,
//                     pingoula_ad.p_vip_log.pay_channel as pay_channel
//                from pingoula_ad.p_vip_log
//                LEFT JOIN
//                    man_tjdb.y_wu_tjuserall ON
//                    pingoula_ad.p_vip_log.device_id = man_tjdb.y_wu_tjuserall.device_id
//                WHERE
//                        (p_vip_log.pay_channel="'.$paydo.'"  or "'.$paydo.'"="") and
//                        locate("'.$quedao.'",p_vip_log.channel )  and
//                        locate("'.$banben.'",p_vip_log.version_name )  and
//                        pingoula_ad.p_vip_log.status =1 and
//                        pingoula_ad.p_vip_log.add_time
//                      BETWEEN '.$today.' and '.$theday.'
//                    and
//                    man_tjdb.y_wu_tjuserall.device_id is not null
//                    HAVING pay_channel in(1,2,3) and dotime BETWEEN 60 and 1440
//                    )t
//                    GROUP BY pch
//                    ';
//        $daylist=Yii::app()->db_zbdindan->createCommand($daysql)->queryAll();
//        foreach ($daylist as &$dlist){
//            $dayalls[$dlist['pch']-1] = $dlist['daysum'];
//
//        }
//        $threesql='SELECT
//                  count(*)as daysum,t.pay_channel as pch
//                from(
//                select
//                    ROUND((pingoula_ad.p_vip_log.add_time-man_tjdb.y_wu_tjuserall.`update`)/60) as dotime,
//                     pingoula_ad.p_vip_log.pay_channel as pay_channel
//                from pingoula_ad.p_vip_log
//                LEFT JOIN
//                    man_tjdb.y_wu_tjuserall ON
//                    pingoula_ad.p_vip_log.device_id = man_tjdb.y_wu_tjuserall.device_id
//                WHERE
//                        (p_vip_log.pay_channel="'.$paydo.'"  or "'.$paydo.'"="") and
//                        locate("'.$quedao.'",p_vip_log.channel )  and
//                        locate("'.$banben.'",p_vip_log.version_name )  and
//                        pingoula_ad.p_vip_log.status =1 and
//                        pingoula_ad.p_vip_log.add_time
//                      BETWEEN '.$today.' and '.$theday.'
//                    and
//                    man_tjdb.y_wu_tjuserall.device_id is not null
//                    HAVING pay_channel in(1,2,3) and dotime BETWEEN 1440 and 3*1440
//                    )t
//                    GROUP BY pch
//                    ';
//        $threelist=Yii::app()->db_zbdindan->createCommand($threesql)->queryAll();
//        foreach ($threelist as &$thlist){
//            $threealls[$thlist['pch']-1] = $thlist['daysum'];
//
//        }
//        $sevensql='SELECT
//                  count(*)as daysum,t.pay_channel as pch
//                from(
//                select
//                    ROUND((pingoula_ad.p_vip_log.add_time-man_tjdb.y_wu_tjuserall.`update`)/60) as dotime,
//                     pingoula_ad.p_vip_log.pay_channel as pay_channel
//                from pingoula_ad.p_vip_log
//                LEFT JOIN
//                    man_tjdb.y_wu_tjuserall ON
//                    pingoula_ad.p_vip_log.device_id = man_tjdb.y_wu_tjuserall.device_id
//                WHERE
//                        (p_vip_log.pay_channel="'.$paydo.'"  or "'.$paydo.'"="") and
//                        locate("'.$quedao.'",p_vip_log.channel )  and
//                        locate("'.$banben.'",p_vip_log.version_name )  and
//                        pingoula_ad.p_vip_log.status =1 and
//                        pingoula_ad.p_vip_log.add_time
//                      BETWEEN '.$today.' and '.$theday.'
//                    and
//                    man_tjdb.y_wu_tjuserall.device_id is not null
//                    HAVING pay_channel in(1,2,3) and dotime BETWEEN 3*1440 and 7*1440
//                    )t
//                    GROUP BY pch
//                    ';
//        $sevenlist=Yii::app()->db_zbdindan->createCommand($sevensql)->queryAll();
//        foreach ($sevenlist as &$slist){
//            $sevenalls[$slist['pch']-1] = $slist['daysum'];
//
//        }
//        if($listsize>=21){
//            $sansql='SELECT
//                  count(*)as daysum,t.pay_channel as pch
//                from(
//                select
//                    ROUND((pingoula_ad.p_vip_log.add_time-man_tjdb.y_wu_tjuserall.`update`)/60) as dotime,
//                     pingoula_ad.p_vip_log.pay_channel as pay_channel
//                from pingoula_ad.p_vip_log
//                LEFT JOIN
//                    man_tjdb.y_wu_tjuserall ON
//                    pingoula_ad.p_vip_log.device_id = man_tjdb.y_wu_tjuserall.device_id
//                WHERE
//                        (p_vip_log.pay_channel="'.$paydo.'"  or "'.$paydo.'"="") and
//                        locate("'.$quedao.'",p_vip_log.channel )  and
//                        locate("'.$banben.'",p_vip_log.version_name )  and
//                        pingoula_ad.p_vip_log.status =1 and
//                        pingoula_ad.p_vip_log.add_time
//                      BETWEEN '.$today.' and '.$theday.'
//                    and
//                    man_tjdb.y_wu_tjuserall.device_id is not null
//                    HAVING pay_channel in(1,2,3) and dotime BETWEEN 7*1440 and 3*7*1440
//                    )t
//                    GROUP BY pch
//                    ';
//            $sanlist=Yii::app()->db_zbdindan->createCommand($sansql)->queryAll();
//            foreach ($sanlist as &$szlist){
//                $sanzhoualls[$szlist['pch']-1] = $szlist['daysum'];
//
//            }
//        }
//        if($listsize>=28){
//            $sisql='SELECT
//                  count(*)as daysum,t.pay_channel as pch
//                from(
//                select
//                    ROUND((pingoula_ad.p_vip_log.add_time-man_tjdb.y_wu_tjuserall.`update`)/60) as dotime,
//                     pingoula_ad.p_vip_log.pay_channel as pay_channel
//                from pingoula_ad.p_vip_log
//                LEFT JOIN
//                    man_tjdb.y_wu_tjuserall ON
//                    pingoula_ad.p_vip_log.device_id = man_tjdb.y_wu_tjuserall.device_id
//                WHERE
//                        (p_vip_log.pay_channel="'.$paydo.'"  or "'.$paydo.'"="") and
//                        locate("'.$quedao.'",p_vip_log.channel )  and
//                        locate("'.$banben.'",p_vip_log.version_name )  and
//                        pingoula_ad.p_vip_log.status =1 and
//                        pingoula_ad.p_vip_log.add_time
//                      BETWEEN '.$today.' and '.$theday.'
//                    and
//                    man_tjdb.y_wu_tjuserall.device_id is not null
//                    HAVING pay_channel in(1,2,3) and dotime BETWEEN 3*7*1440 and 4*7*1440
//                    )t
//                    GROUP BY pch
//                    ';
//            $silist=Yii::app()->db_zbdindan->createCommand($sisql)->queryAll();
//            foreach ($silist as &$szlist){
//                $sizhoualls[$szlist['pch']-1] = $szlist['daysum'];
//
//            }
//        }
//
//        if($listsize>=35){
//            $wusql='SELECT
//                  count(*)as daysum,t.pay_channel as pch
//                from(
//                select
//                    ROUND((pingoula_ad.p_vip_log.add_time-man_tjdb.y_wu_tjuserall.`update`)/60) as dotime,
//                     pingoula_ad.p_vip_log.pay_channel as pay_channel
//                from pingoula_ad.p_vip_log
//                LEFT JOIN
//                    man_tjdb.y_wu_tjuserall ON
//                    pingoula_ad.p_vip_log.device_id = man_tjdb.y_wu_tjuserall.device_id
//                WHERE
//                        (p_vip_log.pay_channel="'.$paydo.'"  or "'.$paydo.'"="") and
//                        locate("'.$quedao.'",p_vip_log.channel )  and
//                        locate("'.$banben.'",p_vip_log.version_name )  and
//                        pingoula_ad.p_vip_log.status =1 and
//                        pingoula_ad.p_vip_log.add_time
//                      BETWEEN '.$today.' and '.$theday.'
//                    and
//                    man_tjdb.y_wu_tjuserall.device_id is not null
//                    HAVING pay_channel in(1,2,3) and dotime >5*7*1440
//                    )t
//                    GROUP BY pch
//                    ';
//            $wulist=Yii::app()->db_zbdindan->createCommand($wusql)->queryAll();
//            foreach ($wulist as &$wlist){
//                $wuzhoualls[$wlist['pch']-1] = $wlist['daysum'];
//
//            }
//        }
/*付费类型统计*/
        $moneyslq='select  
                        p_vip_log.money as money,
                         count(p_vip_log.money) as moneysum
                        from p_vip_log 
                        where 
                           (p_vip_log.pay_channel="'.$paydo.'"  or "'.$paydo.'"="") and 
                        locate("'.$quedao.'",p_vip_log.channel )  and 
                        locate("'.$banben.'",p_vip_log.version_name )  and
                            p_vip_log.`status` = 1 AND 
                         p_vip_log.add_time 
                        BETWEEN '.$today.' and '.$theday.' 
                        group by money 
                  ';
        $moneylist=Yii::app()->db_zbdindan->createCommand($moneyslq)->queryAll();
        foreach ($moneylist as $key=>$mnlist){
            $mvalls['money'][$key] = ($mnlist['money']<2)?'测试金额':$mnlist['money'];
            $mvalls['moneysum'][$key] = $mnlist['moneysum'];
        }

        /*付费电影统计*/
        $movieslq='select  
                        p_vip_log.last_watching as movie,
                         count(p_vip_log.last_watching) as thesum
                        from p_vip_log 
                        where 
                           (p_vip_log.pay_channel="'.$paydo.'"  or "'.$paydo.'"="") and 
                        locate("'.$quedao.'",p_vip_log.channel )  and 
                        locate("'.$banben.'",p_vip_log.version_name )  and
                            p_vip_log.`status` = 1 AND 
                         p_vip_log.add_time 
                        BETWEEN '.$today.' and '.$theday.' 
                        group by movie 
                  ';
        $mvlist=Yii::app()->db_zbdindan->createCommand($movieslq)->queryAll();
        foreach ($mvlist as $key=>$mlist){
            $mvalls['movie'][$key] = empty($mlist['movie'])?'未知影片':$mlist['movie'];
            $mvalls['thesum'][$key] = $mlist['thesum'];
        }
        $listalls=array();
//        $listalls['zrf']=array(
//                            $tenalls[0], $hourlls[0], $dayalls[0],
//                            $threealls[0], $sevenalls[0], $sanzhoualls[0],
//                            $sizhoualls[0], $wuzhoualls[0]
//                        );
//        $listalls['wx']=array(
//                            $tenalls[1], $hourlls[1], $dayalls[1],
//                            $threealls[1], $sevenalls[1], $sanzhoualls[1],
//                            $sizhoualls[1], $wuzhoualls[1]
//                        );
//        $listalls['ali']=array(
//                            $tenalls[2], $hourlls[2], $dayalls[2],
//                            $threealls[2], $sevenalls[2], $sanzhoualls[2],
//                            $sizhoualls[2], $wuzhoualls[2]
//                        );
        $listalls['mvname'] = $mvalls['movie'];
        $listalls['thesum'] = $mvalls['thesum'];
        $listalls['money'] = $mvalls['money'];
        $listalls['moneysum'] = $mvalls['moneysum'];
        return $listalls;

    }

    /*按时间*/
    /*渠道统计*/

    private  function paychanneltime($startday,$endday,$qd='',$bb='',$pay=''){
        /*今日*/
        $today =intval($startday);
        $theday = intval($endday);
        $listsize = ($theday-$today)/3600/24;

        $quedao = $qd;
        $banben = $bb;
        $paydo = $pay;

        $zrfalls =array();
        $wxalls =array();
        $alialls =array();
        $zrfmanalls=array();
        $wxmanalls =array();
        $alimanalls =array();
        $showdate=array();
        for($i=0;$i<$listsize*24;$i++)
        {
            $showdate[]=date('m-d H:00',$today+$i*60*60);
        }
        $this->_datelistall = $showdate;
        $zrfsql='select  
                    DATE_FORMAT(FROM_UNIXTIME(p_vip_log.add_time),"%Y-%m-%d %H:00:00") as time,
                    sum(p_vip_log.money) as  money ,
                    count(*) as  mannum 
                    from p_vip_log 
                    where 
                        p_vip_log.pay_channel=1 and 
                         locate("'.$quedao.'",p_vip_log.channel )  and 
                        locate("'.$banben.'",p_vip_log.version_name )  and
                        p_vip_log.`status` = 1 AND 
                     p_vip_log.add_time 
                     BETWEEN '.$today.' and '.$theday.' 
                    group by time
                ';

        $zrflist=Yii::app()->db_zbdindan->createCommand($zrfsql)->queryAll();
        foreach ($zrflist as &$list){
            $zrfalls[strtotime($list['time'])] = $list['money'];
            $zrfmanalls[strtotime($list['time'])] = $list['mannum'];

        }

        $wxsql='select  
                    DATE_FORMAT(FROM_UNIXTIME(p_vip_log.add_time),"%Y-%m-%d %H:00:00") as time,
                    sum(p_vip_log.money) as  money ,
                    count(*) as  mannum 
                    from p_vip_log 
                    where 
                        p_vip_log.pay_channel=2 and 
                        locate("'.$quedao.'",p_vip_log.channel )  and 
                        locate("'.$banben.'",p_vip_log.version_name )  and
                        p_vip_log.`status` = 1 AND 
                     p_vip_log.add_time 
                     BETWEEN '.$today.' and '.$theday.' 
                    group by time
                ';
        $wxlist=Yii::app()->db_zbdindan->createCommand($wxsql)->queryAll();
        foreach ($wxlist as &$wlist){
            $wxalls[strtotime($wlist['time'])] = $wlist['money'];
            $wxmanalls[strtotime($wlist['time'])] = $wlist['mannum'];

        }

        $alisql='select  
                    DATE_FORMAT(FROM_UNIXTIME(p_vip_log.add_time),"%Y-%m-%d %H:00:00") as time,
                    sum(p_vip_log.money) as  money ,
                    count(*) as  mannum 
                    from p_vip_log 
                    where 
                        p_vip_log.pay_channel=3 and 
                        locate("'.$quedao.'",p_vip_log.channel )  and 
                        locate("'.$banben.'",p_vip_log.version_name )  and
                        p_vip_log.`status` = 1 AND 
                     p_vip_log.add_time 
                     BETWEEN '.$today.' and '.$theday.' 
                    group by time 
                ';
        $alilist=Yii::app()->db_zbdindan->createCommand($alisql)->queryAll();
        foreach ($alilist as &$alist){
            $alialls[strtotime($alist['time'])] = $alist['money'];
            $alimanalls[strtotime($alist['time'])] = $alist['mannum'];

        }

        $listalls=array();
        for ($i=0;$i<$listsize*24;$i++){
            $lnum= $today+$i*60*60;
            $listalls['tablelist'][$i]['dateall']=date('m-d H:00',$today+$i*60*60);
            $listalls['tablelist'][$i]['zrfmoney']=empty($zrfalls[$lnum])?'0':$zrfalls[$lnum];
            $listalls['tablelist'][$i]['zrfman']=empty($zrfmanalls[$lnum])?'0':$zrfmanalls[$lnum];
            $listalls['tablelist'][$i]['wxmoney']=empty($wxalls[$lnum])?'0':$wxalls[$lnum];
            $listalls['tablelist'][$i]['wxman']=empty($wxmanalls[$lnum])?'0':$wxmanalls[$lnum];
            $listalls['tablelist'][$i]['alimoney']=empty($alialls[$lnum])?'0':$alialls[$lnum];
            $listalls['tablelist'][$i]['aliman']=empty($alimanalls[$lnum])?'0':$alimanalls[$lnum];
            $listalls['zx']['zrf'][$i]=empty($zrfalls[$lnum])?'0':$zrfalls[$lnum];
            $listalls['zxman']['zrf'][$i]=empty($zrfmanalls[$lnum])?'0':$zrfmanalls[$lnum];
            $listalls['zx']['wx'][$i]=empty($wxalls[$lnum])?'0':$wxalls[$lnum];
            $listalls['zxman']['wx'][$i]=empty($wxmanalls[$lnum])?'0':$wxmanalls[$lnum];
            $listalls['zx']['ali'][$i]=empty($alialls[$lnum])?'0':$alialls[$lnum];
            $listalls['zxman']['ali'][$i]=empty($alimanalls[$lnum])?'0':$alimanalls[$lnum];
        }

//        $listalls['allmoney']=empty($ff[0])?'0':$ff[0];
//        $listalls['allffsums']=empty($ffdo[0])?'0':$ffdo[0];
//        $listalls['allffday']=empty($ffu[0])?'0':$ffu[0];
        return $listalls;

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
