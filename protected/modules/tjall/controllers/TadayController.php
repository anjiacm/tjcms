<?php
/*注：
        涉及付费内容 分为
        含支付渠道支付  和  不含支付渠道支付
        字段名：pay_type
*/
class TadayController extends FrontBase
{
    public $_statdate ;
    public $_enddate ;
    public $_date ;
    public function init()
    {
        $this->_statdate=strtotime(date("Y-m-d")) ;
        $this->_enddate=strtotime(date("Y-m-d",strtotime("+1 day")));
        $this->_date = date("Y-m-d");

        $file = './update.txt';
        if(file_exists($file)) {
            $updateold = file_get_contents('./update.txt'); //测试用
            $updateold = json_decode($updateold, true);
            $now=time();
            if($now-$updateold<300){
                $res['code']=7;
                $res['msg'] ='请不要频繁更新（5分钟一次）';
                echo json_encode($res);
                exit();
            }else{
                $delsql='TRUNCATE  y_wu_tjselectday';
                $delsqldo=Yii::app()->db->createCommand($delsql)->execute();
                file_put_contents('./update.txt',time()); //测试用
            }
        }else{
            $delsql='TRUNCATE  y_wu_tjselectday';
            $delsqldo=Yii::app()->db->createCommand($delsql)->execute();
            file_put_contents('./update.txt',time()); //测试用
        }

    }
	public function actionIndex()
	{
	    $add=$this->adduser();
	    if($add['code']==0){
            $up=$this->upuser();
            if($up['code']==0){
                $ffuserone=$this->payuserone();
                if($ffuserone['code']==0){
                    $ffusertwo=$this->payusertwo();
                    if($ffusertwo['code']==0){
                        $ffmoneyone=$this->paymoneyone();
                        if($ffmoneyone['code']==0){
                            $ffmoneytwo=$this->paymoneytwo();
                            if($ffmoneytwo['code']==0){
                                $ffsumone=$this->paysumone();
                                if($ffsumone['code']==0){
                                    $ffsumtwo=$this->paysumtwo();
                                    if($ffsumtwo['code']==0){
                                        $ffnewdoone=$this->newpayuserone();
                                        if($ffnewdoone['code']==0){
                                            $ffnewdotwo=$this->newpayusertwo();
                                            if($ffnewdotwo['code']==0){
                                                $res['code']=0;
                                                $res['msg'] ='更新完成';
                                            }else{
                                                $res['code']=1;
                                                $res['msg'] =$ffnewdotwo['msg'];
                                            }
                                        }else{
                                            $res['code']=1;
                                            $res['msg'] =$ffnewdoone['msg'];
                                        }
                                    }else{
                                        $res['code']=1;
                                        $res['msg'] =$ffsumtwo['msg'];
                                    }
                                }else{
                                    $res['code']=1;
                                    $res['msg'] =$ffsumone['msg'];
                                }
                            }else{
                                $res['code']=1;
                                $res['msg'] =$ffmoneytwo['msg'];
                            }
                        }else{
                            $res['code']=1;
                            $res['msg'] =$ffmoneyone['msg'];
                        }
                    }else{
                        $res['code']=1;
                        $res['msg'] =$ffusertwo['msg'];
                    }
                }else{
                    $res['code']=1;
                    $res['msg'] =$ffuserone['msg'];
                }
            }else{
                $res['code']=1;
                $res['msg'] =$up['msg'];
            }
        }else{
	        $res['code']=1;
	        $res['msg'] =$add['msg'];
        }
        echo json_encode($res);
	}

	/*统计每日新增*/
	private function adduser(){
        $tr = Yii::app()->db->beginTransaction();
        $addsql='INSERT INTO y_wu_tjselectday(dodate,channel,channel_s,app_version,adduser) 
                select 
                UNIX_TIMESTAMP(DATE_FORMAT(FROM_UNIXTIME(y_wu_tjuserall.`update`),"%Y-%m-%d %H:00")) as time,
                y_wu_tjuserall.chanel_bid as chanel,
                y_wu_tjuserall.chanel_sid as chanel_s,
                y_wu_tjuserall.versionid as versionid,
                count(*) as mannum 
                from 
                y_wu_tjuserall 
                where 
                y_wu_tjuserall.`update`
                 BETWEEN '.$this->_statdate.'  and '.$this->_enddate.' 
                and y_wu_tjuserall.chanel_bid!=\'\' and y_wu_tjuserall.chanel_sid!=\'\' and y_wu_tjuserall.versionid!=\'\'
                group by time ,chanel,chanel_s,versionid
                
                ON DUPLICATE KEY UPDATE adduser=VALUES(adduser)';
        $addinsert=Yii::app()->db->createCommand($addsql)->execute();
        if($addinsert){
            $tr->commit();
            $art['code'] =0;
            $art['msg'] ='新增用户更新成功';
            //file_put_contents('./tongji/suc/'. $this->_date.'_tj.log','统计新增用户——'.$addinsert."\r\n",FILE_APPEND); //测试用
        }else{
            $tr->rollBack();
            $art['code'] =1;
            $art['msg'] ='新增用户更新失败';
           // file_put_contents('./tongji/err/'. $this->_date.'_tj.log','统计新增用户——'.$addinsert.'-失败时间-'.date('Y-m-d H:i:s')."\r\n",FILE_APPEND); //测试用
        }
        return $art;
    }
    /*统计每日活跃*/
    private  function upuser(){
        $tr = Yii::app()->db->beginTransaction();
        $upsql='INSERT INTO y_wu_tjselectday(dodate,channel,channel_s,app_version,openuser) 
                select 
                UNIX_TIMESTAMP(DATE_FORMAT(FROM_UNIXTIME(y_wu_tjuseruphour.`dayupdate`),"%Y-%m-%d %H:00")) as time,
                y_wu_tjuseruphour.chanel_bid as chanel,
                y_wu_tjuseruphour.chanel_sid as chanel_s,
                y_wu_tjuseruphour.versionid as versionid,
                count(*) as mannum 
                from 
                y_wu_tjuseruphour 
                where 
                y_wu_tjuseruphour.`dayupdate`
                BETWEEN '.$this->_statdate.'  and '.$this->_enddate.' 
                 and y_wu_tjuseruphour.chanel_bid!=\'\' and y_wu_tjuseruphour.chanel_sid!=\'\' and y_wu_tjuseruphour.versionid!=\'\'
                group by time ,chanel,chanel_s,versionid
                ON DUPLICATE KEY UPDATE openuser=VALUES(openuser)';
        $upinsert=Yii::app()->db->createCommand($upsql)->execute();
        if($upinsert){
            $tr->commit();
            $art['code'] =0;
            $art['msg'] ='日活更新成功';
        }else{
            $tr->rollBack();
            $art['code'] =1;
            $art['msg'] ='日活更新失败';
        }
        return $art;
    }

    /*统计付费用户数 不含支付渠道*/
    private function  payuserone(){
        $tr = Yii::app()->db->beginTransaction();
        $payusersql='INSERT INTO y_wu_tjselectday(dodate,channel,channel_s,app_version,payuser) 
                     SELECT t.time,t.chanel,t.chanel_s,t.versionid,count(*) as daysums  from (select  
                    UNIX_TIMESTAMP(DATE_FORMAT(FROM_UNIXTIME(p.add_time),"%Y-%m-%d %H:00")) as time,
                    p.device_id as userid,
                    p.chanel_bid as chanel,
                    p.chanel_sid as chanel_s,
                    p.versionid as versionid
                    from 
                    pingoula_ad.p_vip_log  p
                    where  p.add_time 
                     BETWEEN '.$this->_statdate.'  and '.$this->_enddate.' 
                     and p.`status` =1
                    and p.chanel_bid!=\'\' and p.chanel_sid!=\'\' and p.versionid!=\'\' 
                    GROUP BY userid) t
                    GROUP BY time,chanel,chanel_s,versionid
                    
                    ON DUPLICATE KEY UPDATE payuser=VALUES(payuser)';
        $payuserinsert=Yii::app()->db->createCommand($payusersql)->execute();

        if($payuserinsert){
            $tr->commit();
            $art['code'] =0;
            $art['msg'] ='付费用户更新成功';
        }else{
            $tr->rollBack();
            $art['code'] =1;
            $art['msg'] ='付费用户更新失败(不含支付)';
        }
        return $art;
    }

    /*统计付费用户数 含支付渠道*/
    private function  payusertwo(){
        $tr = Yii::app()->db->beginTransaction();
        $payusersql='INSERT INTO y_wu_tjselectday(dodate,channel,channel_s,app_version,pay_type,payuser) 
                        SELECT t.time,t.chanel,t.chanel_s,t.versionid,t.pay_channel,count(*) as daysums  from (select  
                        UNIX_TIMESTAMP(DATE_FORMAT(FROM_UNIXTIME(p.add_time),"%Y-%m-%d %H:00")) as time,
                        p.device_id as userid,
                        p.chanel_bid as chanel,
                        p.chanel_sid as chanel_s,
                        p.versionid as versionid,
                        p.pay_channel as pay_channel
                        from 
                        pingoula_ad.p_vip_log  p
                        where  p.add_time 
                        BETWEEN '.$this->_statdate.'  and '.$this->_enddate.' 
                         and p.`status` =1
                        and p.chanel_bid!=\'\' and p.chanel_sid!=\'\' and p.versionid!=\'\' 
                        GROUP BY userid) t
                        GROUP BY time,chanel,chanel_s,versionid,pay_channel
                        
                        ON DUPLICATE KEY UPDATE payuser=VALUES(payuser)';
        $payuserinsert=Yii::app()->db->createCommand($payusersql)->execute();
        if($payuserinsert){
            $tr->commit();
            $art['code'] =0;
            $art['msg'] ='付费用户更新成功';
        }else{
            $tr->rollBack();
            $art['code'] =1;
            $art['msg'] ='付费用户更新失败（含支付）';
        }
        return $art;
    }

    /*统计付费金额  不含支付渠道*/
    private function  paymoneyone(){
        $tr = Yii::app()->db->beginTransaction();
        $paymoneysql='INSERT INTO y_wu_tjselectday(dodate,channel,channel_s,app_version,paymoney) 
                         select 
                         UNIX_TIMESTAMP(DATE_FORMAT(FROM_UNIXTIME(p.add_time),"%Y-%m-%d %H:00")) as time,
                        p.chanel_bid as chanel,
                        p.chanel_sid as chanel_s,
                        p.versionid as versionid,
                        sum(p.money) as money 
                        from 
                        pingoula_ad.p_vip_log  p
                        where 
                        p.`status` = 1 AND 
                         p.add_time
                          BETWEEN '.$this->_statdate.'  and '.$this->_enddate.' 
                        and p.chanel_bid!=\'\' and p.chanel_sid!=\'\' and p.versionid!=\'\'
                        GROUP BY time,chanel,chanel_s,versionid
                        ON DUPLICATE KEY UPDATE paymoney=VALUES(paymoney)';
        $paymoneyinsert=Yii::app()->db->createCommand($paymoneysql)->execute();
        if($paymoneyinsert){
            $tr->commit();
            $art['code'] =0;
            $art['msg'] ='付费金额更新成功';
        }else{
            $tr->rollBack();
            $art['code'] =1;
            $art['msg'] ='付费金额更新失败（不含支付）';
        }
        return $art;
    }

    /*统计付费金额   含支付渠道*/
    private function  paymoneytwo(){
        $tr = Yii::app()->db->beginTransaction();
        $paymoneysql='INSERT INTO y_wu_tjselectday(dodate,channel,channel_s,app_version,pay_type,paymoney) 
                     select 
                     UNIX_TIMESTAMP(DATE_FORMAT(FROM_UNIXTIME(p.add_time),"%Y-%m-%d %H:00")) as time,
                    p.chanel_bid as chanel,
                    p.chanel_sid as chanel_s,
                    p.versionid as versionid,
                    p.pay_channel as pay_channel,
                    sum(p.money) as money 
                    from 
                    pingoula_ad.p_vip_log  p
                    where 
                    p.`status` = 1 AND 
                     p.add_time
                     BETWEEN '.$this->_statdate.'  and '.$this->_enddate.' 
                    and p.chanel_bid!=\'\' and p.chanel_sid!=\'\' and p.versionid!=\'\'
                    GROUP BY time,chanel,chanel_s,versionid,pay_channel
                    ON DUPLICATE KEY UPDATE paymoney=VALUES(paymoney)';
        $paymoneyinsert=Yii::app()->db->createCommand($paymoneysql)->execute();
        if($paymoneyinsert){
            $tr->commit();
            $art['code'] =0;
            $art['msg'] ='付费金额更新成功';
        }else{
            $tr->rollBack();
            $art['code'] =1;
            $art['msg'] ='付费金额更新失败（含支付）';
        }
        return $art;
    }

    /*统计新增用户付费  不含支付渠道*/
    private function  newpayuserone(){
        $tr = Yii::app()->db->beginTransaction();
        $paynewsql='INSERT INTO y_wu_tjselectday(dodate,channel,channel_s,app_version,newpayuser) 
                        select 
                        UNIX_TIMESTAMP(DATE_FORMAT(FROM_UNIXTIME(p.add_time),"%Y-%m-%d %H:00")) as time,
                        p.chanel_bid as chanel,
                        p.chanel_sid as chanel_s,
                        p.versionid as versionid,
                            count(*) as sums  
                            from 
                        pingoula_ad.p_vip_log p
                            where 
                            p.device_id in 
                            (select 
                                y_wu_tjuserall.device_id 
                                from  y_wu_tjuserall 
                                where 
                                y_wu_tjuserall.`update` 
                                BETWEEN '.$this->_statdate.'  and '.$this->_enddate.' 
                            ) 
                        and  
                        p.add_time 
                         BETWEEN '.$this->_statdate.'  and '.$this->_enddate.' 
                        and p.status =1
                        and p.chanel_bid!=\'\' and p.chanel_sid!=\'\' and p.versionid!=\'\'
                        GROUP BY time,chanel,chanel_s,versionid
                        
                        ON DUPLICATE KEY UPDATE newpayuser=VALUES(newpayuser)';
        $paynewuserinsert=Yii::app()->db->createCommand($paynewsql)->execute();
        if($paynewuserinsert){
            $tr->commit();
            $art['code'] =0;
            $art['msg'] ='新增用户付费更新成功';
        }else{
            $tr->rollBack();
            $art['code'] =1;
            $art['msg'] ='新增用户付费更新失败（不含支付）';
        }
        return $art;
    }

    /*统计新增用户付费  含支付渠道*/
    private function  newpayusertwo(){
        $tr = Yii::app()->db->beginTransaction();
        $paynewsql='INSERT INTO y_wu_tjselectday(dodate,channel,channel_s,app_version,pay_type,newpayuser) 
                    select 
                    UNIX_TIMESTAMP(DATE_FORMAT(FROM_UNIXTIME(p.add_time),"%Y-%m-%d %H:00")) as time,
                    p.chanel_bid as chanel,
                    p.chanel_sid as chanel_s,
                    p.versionid as versionid,
                    p.pay_channel as pay_channel,
                        count(*) as sums  
                        from 
                    pingoula_ad.p_vip_log p
                        where 
                        p.device_id in 
                        (select 
                            y_wu_tjuserall.device_id 
                            from  y_wu_tjuserall 
                            where 
                            y_wu_tjuserall.`update`  
                          BETWEEN '.$this->_statdate.'  and '.$this->_enddate.' 
                        ) 
                    and  
                     
                    p.add_time 
                    BETWEEN '.$this->_statdate.'  and '.$this->_enddate.' 
                    and p.status =1
                    and p.chanel_bid!=\'\' and p.chanel_sid!=\'\' and p.versionid!=\'\'
                    GROUP BY time,chanel,chanel_s,versionid,pay_channel
                    ON DUPLICATE KEY UPDATE newpayuser=VALUES(newpayuser)';
        $paynewuserinsert=Yii::app()->db->createCommand($paynewsql)->execute();
        if($paynewuserinsert){
            $tr->commit();
            $art['code'] =0;
            $art['msg'] ='新增用户付费更新成功';
        }else{
            $tr->rollBack();
            $art['code'] =1;
            $art['msg'] ='新增用户付费更新失败（含支付）';
        }
        return $art;
    }

    /*统计付费次数  不含支付渠道*/
    private function  paysumone(){
        $tr = Yii::app()->db->beginTransaction();
        $paysumsql='INSERT INTO y_wu_tjselectday(dodate,channel,channel_s,app_version,paysum) 
                    select  
                    UNIX_TIMESTAMP(DATE_FORMAT(FROM_UNIXTIME(p.add_time),"%Y-%m-%d %H:00")) as time,
                    p.chanel_bid as chanel,
                    p.chanel_sid as chanel_s,
                    p.versionid as versionid,
                    count(*) as daynum
                    from 
                    pingoula_ad.p_vip_log  p
                    where  p.add_time 
                    BETWEEN '.$this->_statdate.'  and '.$this->_enddate.' 
                     and p.`status` =1
                    and p.chanel_bid!=\'\' and p.chanel_sid!=\'\' and p.versionid!=\'\' 
                    GROUP BY time,chanel,chanel_s,versionid
                    
                    ON DUPLICATE KEY UPDATE paysum=VALUES(paysum)';
        $paysuminsert=Yii::app()->db->createCommand($paysumsql)->execute();
        if($paysuminsert){
            $tr->commit();
            $art['code'] =0;
            $art['msg'] ='付费次数更新成功';
        }else{
            $tr->rollBack();
            $art['code'] =1;
            $art['msg'] ='付费次数更新失败（不含支付）';
        }
        return $art;
    }

    /*统计付费次数  含支付渠道*/
    private function  paysumtwo(){
        $tr = Yii::app()->db->beginTransaction();
        $paysumsql='INSERT INTO y_wu_tjselectday(dodate,channel,channel_s,app_version,pay_type,paysum) 
                    select  
                    UNIX_TIMESTAMP(DATE_FORMAT(FROM_UNIXTIME(p.add_time),"%Y-%m-%d %H:00")) as time,
                    p.chanel_bid as chanel,
                    p.chanel_sid as chanel_s,
                    p.versionid as versionid,
                    p.pay_channel as pay_channel,
                    count(*) as daynum
                    from 
                    pingoula_ad.p_vip_log  p
                    where  p.add_time 
                    BETWEEN '.$this->_statdate.'  and '.$this->_enddate.' 
                    and p.`status` =1
                    and p.chanel_bid!=\'\' and p.chanel_sid!=\'\' and p.versionid!=\'\' 
                    GROUP BY time,chanel,chanel_s,versionid,pay_channel
                    
                    ON DUPLICATE KEY UPDATE paysum=VALUES(paysum)';
        $paysuminsert=Yii::app()->db->createCommand($paysumsql)->execute();
        if($paysuminsert){
            $tr->commit();
            $art['code'] =0;
            $art['msg'] ='付费次数成功';
        }else{
            $tr->rollBack();
            $art['code'] =1;
            $art['msg'] ='付费次数失败（含支付）';
        }
        return $art;
    }




}