<?php
/*注：
        涉及付费内容 分为
        含支付渠道支付  和  不含支付渠道支付
        字段名：pay_type
*/
class DefaultController extends FrontBase
{
    public $_statdate ;
    public $_enddate ;
    public $_date ;
    public function init()
    {
        $this->_statdate=strtotime(date("Y-m-d",strtotime("-1 day"))) ;
        $this->_enddate=strtotime(date("Y-m-d"));
        $this->_date = date("Y-m-d");
    }
	public function actionIndex()
	{
	    var_dump('111');
	    exit();
		$this->render('index');
	}

	/*统计每日新增*/
	public function actionadduser(){
        $tr = Yii::app()->db->beginTransaction();
	    $addsql='INSERT INTO y_wu_tjselectall(dodate,channel,channel_s,app_version,adduser) 
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
            $art['msg'] ='写入成功';
            file_put_contents('./tongji/suc/'. $this->_date.'_tj.log','统计新增用户——'.$addinsert."\r\n",FILE_APPEND); //测试用
        }else{
            $tr->rollBack();
            $art['code'] =1;
            $art['msg'] ='写入失败';
            file_put_contents('./tongji/err/'. $this->_date.'_tj.log','统计新增用户——'.$addinsert.'-失败时间-'.date('Y-m-d H:i:s')."\r\n",FILE_APPEND); //测试用
        }
        echo json_encode($art);
    }
    /*统计每日活跃*/
    public  function actionupuser(){
        $tr = Yii::app()->db->beginTransaction();
        $upsql='INSERT INTO y_wu_tjselectall(dodate,channel,channel_s,app_version,openuser) 
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
            $art['msg'] ='写入成功';
        }else{
            $tr->rollBack();
            $art['code'] =1;
            $art['msg'] ='写入失败';
        }
        echo json_encode($art);
    }

    /*统计付费用户数 不含支付渠道*/
    public function  actionpayuserone(){
        $tr = Yii::app()->db->beginTransaction();
        $payusersql='INSERT INTO y_wu_tjselectall(dodate,channel,channel_s,app_version,payuser) 
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
            $art['msg'] ='写入成功';
        }else{
            $tr->rollBack();
            $art['code'] =1;
            $art['msg'] ='写入失败';
        }
        echo json_encode($art);
    }

    /*统计付费用户数 含支付渠道*/
    public function  actionpayusertwo(){
        $tr = Yii::app()->db->beginTransaction();
        $payusersql='INSERT INTO y_wu_tjselectall(dodate,channel,channel_s,app_version,pay_type,payuser) 
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
            $art['msg'] ='写入成功';
        }else{
            $tr->rollBack();
            $art['code'] =1;
            $art['msg'] ='写入失败';
        }
        echo json_encode($art);
    }

    /*统计付费金额  不含支付渠道*/
    public function  actionpaymoneyone(){
        $tr = Yii::app()->db->beginTransaction();
        $paymoneysql='INSERT INTO y_wu_tjselectall(dodate,channel,channel_s,app_version,paymoney) 
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
            $art['msg'] ='写入成功';
        }else{
            $tr->rollBack();
            $art['code'] =1;
            $art['msg'] ='写入失败';
        }
        echo json_encode($art);
    }

    /*统计付费金额   含支付渠道*/
    public function  actionpaymoneytwo(){
        $tr = Yii::app()->db->beginTransaction();
        $paymoneysql='INSERT INTO y_wu_tjselectall(dodate,channel,channel_s,app_version,pay_type,paymoney) 
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
            $art['msg'] ='写入成功';
        }else{
            $tr->rollBack();
            $art['code'] =1;
            $art['msg'] ='写入失败';
        }
        echo json_encode($art);
    }

    /*统计新增用户付费  不含支付渠道*/
    public function  actionnewpayuserone(){
        $tr = Yii::app()->db->beginTransaction();
        $paynewsql='INSERT INTO y_wu_tjselectall(dodate,channel,channel_s,app_version,newpayuser) 
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
            $art['msg'] ='写入成功';
        }else{
            $tr->rollBack();
            $art['code'] =1;
            $art['msg'] ='写入失败';
        }
        echo json_encode($art);
    }

    /*统计新增用户付费  含支付渠道*/
    public function  actionnewpayusertwo(){
        $tr = Yii::app()->db->beginTransaction();
        $paynewsql='INSERT INTO y_wu_tjselectall(dodate,channel,channel_s,app_version,pay_type,newpayuser) 
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
            $art['msg'] ='写入成功';
        }else{
            $tr->rollBack();
            $art['code'] =1;
            $art['msg'] ='写入失败';
        }
        echo json_encode($art);
    }

    /*统计付费次数  不含支付渠道*/
    public function  actionpaysumone(){
        $tr = Yii::app()->db->beginTransaction();
        $paysumsql='INSERT INTO y_wu_tjselectall(dodate,channel,channel_s,app_version,paysum) 
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
            $art['msg'] ='写入成功';
        }else{
            $tr->rollBack();
            $art['code'] =1;
            $art['msg'] ='写入失败';
        }
        echo json_encode($art);
    }

    /*统计付费次数  含支付渠道*/
    public function  actionpaysumtwo(){
        $tr = Yii::app()->db->beginTransaction();
        $paysumsql='INSERT INTO y_wu_tjselectall(dodate,channel,channel_s,app_version,pay_type,paysum) 
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
            $art['msg'] ='写入成功';
        }else{
            $tr->rollBack();
            $art['code'] =1;
            $art['msg'] ='写入失败';
        }
        echo json_encode($art);
    }

    /*统计首次付费 10分钟以内  不含支付渠道*/
    public function  actiontenpayone(){
        $tr = Yii::app()->db->beginTransaction();
        $tensql='INSERT INTO y_wu_tjselectall(dodate,channel,channel_s,app_version,tenuser) 
                        SELECT 
                        t.time as time ,t.chanel as chanel,t.chanel_s as chanel_s,t.versionid as versionid,count(*) as sums
                        from(
                          select 
                                UNIX_TIMESTAMP(DATE_FORMAT(FROM_UNIXTIME(p.add_time),"%Y-%m-%d %H:00")) as time,
                        
                                ROUND((p.add_time-y_wu_tjuserall.`update`)/60) as dotime,
                            p.chanel_bid as chanel,
                        p.chanel_sid as chanel_s,
                        p.versionid as versionid
                        from pingoula_ad.p_vip_log p
                        LEFT JOIN 
                        man_tjdb.y_wu_tjuserall ON  
                        p.device_id = man_tjdb.y_wu_tjuserall.device_id  
                        WHERE 
                            p.recharge_type =1 and p.status =1 and 
                        p.add_time 
                        BETWEEN '.$this->_statdate.'  and '.$this->_enddate.' 
                        and man_tjdb.y_wu_tjuserall.device_id is not null
                         and p.chanel_bid!=\'\' and p.chanel_sid!=\'\' and p.versionid!=\'\'
                          HAVING dotime BETWEEN 0 and 10
                        )t
                        GROUP BY  time,chanel,chanel_s,versionid  
                        ON DUPLICATE KEY UPDATE tenuser=VALUES(tenuser)';
        $teninsert=Yii::app()->db->createCommand($tensql)->execute();
        if($teninsert){
            $tr->commit();
            $art['code'] =0;
            $art['msg'] ='写入成功';
        }else{
            $tr->rollBack();
            $art['code'] =1;
            $art['msg'] ='写入失败';
        }
        echo json_encode($art);
    }

    /*统计首次付费 10分钟以内  含支付渠道*/
    public function  actiontenpaytwo(){
        $tr = Yii::app()->db->beginTransaction();
        $tensql='INSERT INTO y_wu_tjselectall(dodate,channel,channel_s,app_version,pay_type,tenuser) 
                    SELECT 
                    t.time as time ,t.chanel as chanel,t.chanel_s as chanel_s,t.versionid as versionid,t.pay_channel as pay_channel, count(*) as sums
                    from(
                      select 
                            UNIX_TIMESTAMP(DATE_FORMAT(FROM_UNIXTIME(p.add_time),"%Y-%m-%d %H:00")) as time,
                    
                            ROUND((p.add_time-y_wu_tjuserall.`update`)/60) as dotime,
                        p.chanel_bid as chanel,
                    p.chanel_sid as chanel_s,
                    p.versionid as versionid,
                    p.pay_channel as pay_channel
                    from pingoula_ad.p_vip_log p
                    LEFT JOIN 
                    man_tjdb.y_wu_tjuserall ON  
                    p.device_id = man_tjdb.y_wu_tjuserall.device_id  
                    WHERE 
                        p.recharge_type =1 and p.status =1 and 
                    p.add_time 
                    BETWEEN '.$this->_statdate.'  and '.$this->_enddate.' 
                    and man_tjdb.y_wu_tjuserall.device_id is not null
                     and p.chanel_bid!=\'\' and p.chanel_sid!=\'\' and p.versionid!=\'\'
                      HAVING dotime BETWEEN 0 and 10
                    )t
                    GROUP BY  time,chanel,chanel_s,versionid,pay_channel
                    ON DUPLICATE KEY UPDATE tenuser=VALUES(tenuser)';
        $teninsert=Yii::app()->db->createCommand($tensql)->execute();
        if($teninsert){
            $tr->commit();
            $art['code'] =0;
            $art['msg'] ='写入成功';
        }else{
            $tr->rollBack();
            $art['code'] =1;
            $art['msg'] ='写入失败';
        }
        echo json_encode($art);
    }

    /*统计首次付费 1小时以内  不含支付渠道*/
    public function  actionhourpayone(){
        $tr = Yii::app()->db->beginTransaction();
        $hoursql='INSERT INTO y_wu_tjselectall(dodate,channel,channel_s,app_version,houruser) 
                        SELECT 
                        t.time as time ,t.chanel as chanel,t.chanel_s as chanel_s,t.versionid as versionid,count(*) as sums
                        from(
                          select 
                                UNIX_TIMESTAMP(DATE_FORMAT(FROM_UNIXTIME(p.add_time),"%Y-%m-%d %H:00")) as time,
                        
                                ROUND((p.add_time-y_wu_tjuserall.`update`)/60) as dotime,
                            p.chanel_bid as chanel,
                        p.chanel_sid as chanel_s,
                        p.versionid as versionid
                        from pingoula_ad.p_vip_log p
                        LEFT JOIN 
                        man_tjdb.y_wu_tjuserall ON  
                        p.device_id = man_tjdb.y_wu_tjuserall.device_id  
                        WHERE 
                            p.recharge_type =1 and p.status =1 and 
                        p.add_time 
                        BETWEEN '.$this->_statdate.'  and '.$this->_enddate.' 
                        and man_tjdb.y_wu_tjuserall.device_id is not null
                         and p.chanel_bid!=\'\' and p.chanel_sid!=\'\' and p.versionid!=\'\'
                          HAVING dotime BETWEEN 10 and 60
                        )t
                        GROUP BY  time,chanel,chanel_s,versionid  
                        ON DUPLICATE KEY UPDATE houruser=VALUES(houruser)';
        $hourinsert=Yii::app()->db->createCommand($hoursql)->execute();
        if($hourinsert){
            $tr->commit();
            $art['code'] =0;
            $art['msg'] ='写入成功';
        }else{
            $tr->rollBack();
            $art['code'] =1;
            $art['msg'] ='写入失败';
        }
        echo json_encode($art);
    }

    /*统计首次付费 1小时以内   含支付渠道*/
    public function  actionhourpaytwo(){
        $tr = Yii::app()->db->beginTransaction();
        $hoursql='INSERT INTO y_wu_tjselectall(dodate,channel,channel_s,app_version,pay_type,houruser) 
                    SELECT 
                    t.time as time ,t.chanel as chanel,t.chanel_s as chanel_s,t.versionid as versionid,t.pay_channel as pay_channel, count(*) as sums
                    from(
                      select 
                            UNIX_TIMESTAMP(DATE_FORMAT(FROM_UNIXTIME(p.add_time),"%Y-%m-%d %H:00")) as time,
                    
                            ROUND((p.add_time-y_wu_tjuserall.`update`)/60) as dotime,
                        p.chanel_bid as chanel,
                    p.chanel_sid as chanel_s,
                    p.versionid as versionid,
                    p.pay_channel as pay_channel
                    from pingoula_ad.p_vip_log p
                    LEFT JOIN 
                    man_tjdb.y_wu_tjuserall ON  
                    p.device_id = man_tjdb.y_wu_tjuserall.device_id  
                    WHERE 
                        p.recharge_type =1 and p.status =1 and 
                    p.add_time 
                    BETWEEN '.$this->_statdate.'  and '.$this->_enddate.' 
                    and man_tjdb.y_wu_tjuserall.device_id is not null
                     and p.chanel_bid!=\'\' and p.chanel_sid!=\'\' and p.versionid!=\'\'
                      HAVING dotime BETWEEN 10 and 60
                    )t
                    GROUP BY  time,chanel,chanel_s,versionid,pay_channel
                    ON DUPLICATE KEY UPDATE houruser=VALUES(houruser)';
        $hourinsert=Yii::app()->db->createCommand($hoursql)->execute();
        if($hourinsert){
            $tr->commit();
            $art['code'] =0;
            $art['msg'] ='写入成功';
        }else{
            $tr->rollBack();
            $art['code'] =1;
            $art['msg'] ='写入失败';
        }
        echo json_encode($art);
    }

    /*统计首次付费 一天以内  不含支付渠道*/
    public function  actiondaypayone(){
        $tr = Yii::app()->db->beginTransaction();
        $daysql='INSERT INTO y_wu_tjselectall(dodate,channel,channel_s,app_version,onedayuser) 
                        SELECT 
                        t.time as time ,t.chanel as chanel,t.chanel_s as chanel_s,t.versionid as versionid,count(*) as sums
                        from(
                          select 
                                UNIX_TIMESTAMP(DATE_FORMAT(FROM_UNIXTIME(p.add_time),"%Y-%m-%d %H:00")) as time,
                        
                                ROUND((p.add_time-y_wu_tjuserall.`update`)/60) as dotime,
                            p.chanel_bid as chanel,
                        p.chanel_sid as chanel_s,
                        p.versionid as versionid
                        from pingoula_ad.p_vip_log p
                        LEFT JOIN 
                        man_tjdb.y_wu_tjuserall ON  
                        p.device_id = man_tjdb.y_wu_tjuserall.device_id  
                        WHERE 
                            p.recharge_type =1 and p.status =1 and 
                        p.add_time 
                        BETWEEN '.$this->_statdate.'  and '.$this->_enddate.' 
                        and man_tjdb.y_wu_tjuserall.device_id is not null
                         and p.chanel_bid!=\'\' and p.chanel_sid!=\'\' and p.versionid!=\'\'
                          HAVING dotime BETWEEN 60 and 1440
                        )t
                        GROUP BY  time,chanel,chanel_s,versionid  
                        ON DUPLICATE KEY UPDATE  onedayuser=VALUES(onedayuser)';
        $dayinsert=Yii::app()->db->createCommand($daysql)->execute();
        if($dayinsert){
            $tr->commit();
            $art['code'] =0;
            $art['msg'] ='写入成功';
        }else{
            $tr->rollBack();
            $art['code'] =1;
            $art['msg'] ='写入失败';
        }
        echo json_encode($art);
    }

    /*统计首次付费 一天以内   含支付渠道*/
    public function  actiondaypaytwo(){
        $tr = Yii::app()->db->beginTransaction();
        $daysql='INSERT INTO y_wu_tjselectall(dodate,channel,channel_s,app_version,pay_type,onedayuser) 
                    SELECT 
                    t.time as time ,t.chanel as chanel,t.chanel_s as chanel_s,t.versionid as versionid,t.pay_channel as pay_channel, count(*) as sums
                    from(
                      select 
                            UNIX_TIMESTAMP(DATE_FORMAT(FROM_UNIXTIME(p.add_time),"%Y-%m-%d %H:00")) as time,
                    
                            ROUND((p.add_time-y_wu_tjuserall.`update`)/60) as dotime,
                        p.chanel_bid as chanel,
                    p.chanel_sid as chanel_s,
                    p.versionid as versionid,
                    p.pay_channel as pay_channel
                    from pingoula_ad.p_vip_log p
                    LEFT JOIN 
                    man_tjdb.y_wu_tjuserall ON  
                    p.device_id = man_tjdb.y_wu_tjuserall.device_id  
                    WHERE 
                        p.recharge_type =1 and p.status =1 and 
                    p.add_time 
                    BETWEEN '.$this->_statdate.'  and '.$this->_enddate.' 
                    and man_tjdb.y_wu_tjuserall.device_id is not null
                     and p.chanel_bid!=\'\' and p.chanel_sid!=\'\' and p.versionid!=\'\'
                      HAVING dotime BETWEEN 60 and 1440
                    )t
                    GROUP BY  time,chanel,chanel_s,versionid,pay_channel
                    ON DUPLICATE KEY UPDATE onedayuser=VALUES(onedayuser)';
        $dayinsert=Yii::app()->db->createCommand($daysql)->execute();
        if($dayinsert){
            $tr->commit();
            $art['code'] =0;
            $art['msg'] ='写入成功';
        }else{
            $tr->rollBack();
            $art['code'] =1;
            $art['msg'] ='写入失败';
        }
        echo json_encode($art);
    }

    /*统计首次付费 3天以内  不含支付渠道*/
    public function  actionthreepayone(){
        $tr = Yii::app()->db->beginTransaction();
        $threesql='INSERT INTO y_wu_tjselectall(dodate,channel,channel_s,app_version,threeday) 
                        SELECT 
                        t.time as time ,t.chanel as chanel,t.chanel_s as chanel_s,t.versionid as versionid,count(*) as sums
                        from(
                          select 
                                UNIX_TIMESTAMP(DATE_FORMAT(FROM_UNIXTIME(p.add_time),"%Y-%m-%d %H:00")) as time,
                        
                                ROUND((p.add_time-y_wu_tjuserall.`update`)/60) as dotime,
                            p.chanel_bid as chanel,
                        p.chanel_sid as chanel_s,
                        p.versionid as versionid
                        from pingoula_ad.p_vip_log p
                        LEFT JOIN 
                        man_tjdb.y_wu_tjuserall ON  
                        p.device_id = man_tjdb.y_wu_tjuserall.device_id  
                        WHERE 
                            p.recharge_type =1 and p.status =1 and 
                        p.add_time 
                        BETWEEN '.$this->_statdate.'  and '.$this->_enddate.' 
                        and man_tjdb.y_wu_tjuserall.device_id is not null
                         and p.chanel_bid!=\'\' and p.chanel_sid!=\'\' and p.versionid!=\'\'
                          HAVING dotime BETWEEN 1440 and 3*1440
                        )t
                        GROUP BY  time,chanel,chanel_s,versionid  
                        ON DUPLICATE KEY UPDATE threeday=VALUES(threeday)';
        $threeinsert=Yii::app()->db->createCommand($threesql)->execute();
        if($threeinsert){
            $tr->commit();
            $art['code'] =0;
            $art['msg'] ='写入成功';
        }else{
            $tr->rollBack();
            $art['code'] =1;
            $art['msg'] ='写入失败';
        }
        echo json_encode($art);
    }

    /*统计首次付费  3天以内  含支付渠道*/
    public function  actionthreepaytwo(){
        $tr = Yii::app()->db->beginTransaction();
        $threesql='INSERT INTO y_wu_tjselectall(dodate,channel,channel_s,app_version,pay_type,threeday) 
                    SELECT 
                    t.time as time ,t.chanel as chanel,t.chanel_s as chanel_s,t.versionid as versionid,t.pay_channel as pay_channel, count(*) as sums
                    from(
                      select 
                            UNIX_TIMESTAMP(DATE_FORMAT(FROM_UNIXTIME(p.add_time),"%Y-%m-%d %H:00")) as time,
                    
                            ROUND((p.add_time-y_wu_tjuserall.`update`)/60) as dotime,
                        p.chanel_bid as chanel,
                    p.chanel_sid as chanel_s,
                    p.versionid as versionid,
                    p.pay_channel as pay_channel
                    from pingoula_ad.p_vip_log p
                    LEFT JOIN 
                    man_tjdb.y_wu_tjuserall ON  
                    p.device_id = man_tjdb.y_wu_tjuserall.device_id  
                    WHERE 
                        p.recharge_type =1 and p.status =1 and 
                    p.add_time 
                    BETWEEN '.$this->_statdate.'  and '.$this->_enddate.' 
                    and man_tjdb.y_wu_tjuserall.device_id is not null
                     and p.chanel_bid!=\'\' and p.chanel_sid!=\'\' and p.versionid!=\'\'
                      HAVING dotime BETWEEN 1440 and 3*1440
                    )t
                    GROUP BY  time,chanel,chanel_s,versionid,pay_channel
                    ON DUPLICATE KEY UPDATE threeday=VALUES(threeday)';
        $threeinsert=Yii::app()->db->createCommand($threesql)->execute();
        if($threeinsert){
            $tr->commit();
            $art['code'] =0;
            $art['msg'] ='写入成功';
        }else{
            $tr->rollBack();
            $art['code'] =1;
            $art['msg'] ='写入失败';
        }
        echo json_encode($art);
    }

    /*统计首次付费 7天以内  不含支付渠道*/
    public function  actionsevenpayone(){
        $tr = Yii::app()->db->beginTransaction();
        $sevsql='INSERT INTO y_wu_tjselectall(dodate,channel,channel_s,app_version,sevenday) 
                        SELECT 
                        t.time as time ,t.chanel as chanel,t.chanel_s as chanel_s,t.versionid as versionid,count(*) as sums
                        from(
                          select 
                                UNIX_TIMESTAMP(DATE_FORMAT(FROM_UNIXTIME(p.add_time),"%Y-%m-%d %H:00")) as time,
                        
                                ROUND((p.add_time-y_wu_tjuserall.`update`)/60) as dotime,
                            p.chanel_bid as chanel,
                        p.chanel_sid as chanel_s,
                        p.versionid as versionid
                        from pingoula_ad.p_vip_log p
                        LEFT JOIN 
                        man_tjdb.y_wu_tjuserall ON  
                        p.device_id = man_tjdb.y_wu_tjuserall.device_id  
                        WHERE 
                            p.recharge_type =1 and p.status =1 and 
                        p.add_time 
                        BETWEEN '.$this->_statdate.'  and '.$this->_enddate.' 
                        and man_tjdb.y_wu_tjuserall.device_id is not null
                         and p.chanel_bid!=\'\' and p.chanel_sid!=\'\' and p.versionid!=\'\'
                          HAVING dotime BETWEEN 3*1440 and 7*1440
                        )t
                        GROUP BY  time,chanel,chanel_s,versionid  
                        ON DUPLICATE KEY UPDATE sevenday=VALUES(sevenday)';
        $sevinsert=Yii::app()->db->createCommand($sevsql)->execute();
        if($sevinsert){
            $tr->commit();
            $art['code'] =0;
            $art['msg'] ='写入成功';
        }else{
            $tr->rollBack();
            $art['code'] =1;
            $art['msg'] ='写入失败';
        }
        echo json_encode($art);
    }

    /*统计首次付费  7天以内  含支付渠道*/
    public function  actionsevenpaytwo(){
        $tr = Yii::app()->db->beginTransaction();
        $sevsql='INSERT INTO y_wu_tjselectall(dodate,channel,channel_s,app_version,pay_type,sevenday) 
                    SELECT 
                    t.time as time ,t.chanel as chanel,t.chanel_s as chanel_s,t.versionid as versionid,t.pay_channel as pay_channel, count(*) as sums
                    from(
                      select 
                            UNIX_TIMESTAMP(DATE_FORMAT(FROM_UNIXTIME(p.add_time),"%Y-%m-%d %H:00")) as time,
                    
                            ROUND((p.add_time-y_wu_tjuserall.`update`)/60) as dotime,
                        p.chanel_bid as chanel,
                    p.chanel_sid as chanel_s,
                    p.versionid as versionid,
                    p.pay_channel as pay_channel
                    from pingoula_ad.p_vip_log p
                    LEFT JOIN 
                    man_tjdb.y_wu_tjuserall ON  
                    p.device_id = man_tjdb.y_wu_tjuserall.device_id  
                    WHERE 
                        p.recharge_type =1 and p.status =1 and 
                    p.add_time 
                    BETWEEN '.$this->_statdate.'  and '.$this->_enddate.' 
                    and man_tjdb.y_wu_tjuserall.device_id is not null
                     and p.chanel_bid!=\'\' and p.chanel_sid!=\'\' and p.versionid!=\'\'
                      HAVING dotime BETWEEN  3*1440 and 7*1440
                    )t
                    GROUP BY  time,chanel,chanel_s,versionid,pay_channel
                    ON DUPLICATE KEY UPDATE sevenday=VALUES(sevenday)';
        $sevinsert=Yii::app()->db->createCommand($sevsql)->execute();
        if($sevinsert){
            $tr->commit();
            $art['code'] =0;
            $art['msg'] ='写入成功';
        }else{
            $tr->rollBack();
            $art['code'] =1;
            $art['msg'] ='写入失败';
        }
        echo json_encode($art);
    }

    /*统计首次付费 7天以后  不含支付渠道*/
    public function  actionotherdaypayone(){
        $tr = Yii::app()->db->beginTransaction();
        $otherdsql='INSERT INTO y_wu_tjselectall(dodate,channel,channel_s,app_version,otherday) 
                        SELECT 
                        t.time as time ,t.chanel as chanel,t.chanel_s as chanel_s,t.versionid as versionid,count(*) as sums
                        from(
                          select 
                                UNIX_TIMESTAMP(DATE_FORMAT(FROM_UNIXTIME(p.add_time),"%Y-%m-%d %H:00")) as time,
                        
                                ROUND((p.add_time-y_wu_tjuserall.`update`)/60) as dotime,
                            p.chanel_bid as chanel,
                        p.chanel_sid as chanel_s,
                        p.versionid as versionid
                        from pingoula_ad.p_vip_log p
                        LEFT JOIN 
                        man_tjdb.y_wu_tjuserall ON  
                        p.device_id = man_tjdb.y_wu_tjuserall.device_id  
                        WHERE 
                            p.recharge_type =1 and p.status =1 and 
                        p.add_time 
                        BETWEEN '.$this->_statdate.'  and '.$this->_enddate.' 
                        and man_tjdb.y_wu_tjuserall.device_id is not null
                         and p.chanel_bid!=\'\' and p.chanel_sid!=\'\' and p.versionid!=\'\'
                          HAVING dotime > 7*1440
                        )t
                        GROUP BY  time,chanel,chanel_s,versionid  
                        ON DUPLICATE KEY UPDATE  otherday=VALUES(otherday)';
        $otherinsert=Yii::app()->db->createCommand($otherdsql)->execute();
        if($otherinsert){
            $tr->commit();
            $art['code'] =0;
            $art['msg'] ='写入成功';
        }else{
            $tr->rollBack();
            $art['code'] =1;
            $art['msg'] ='写入失败';
        }
        echo json_encode($art);
    }

    /*统计首次付费  7天以后  含支付渠道*/
    public function  actionotherdaypaytwo(){
        $tr = Yii::app()->db->beginTransaction();
        $otherdsql='INSERT INTO y_wu_tjselectall(dodate,channel,channel_s,app_version,pay_type,otherday) 
                    SELECT 
                    t.time as time ,t.chanel as chanel,t.chanel_s as chanel_s,t.versionid as versionid,t.pay_channel as pay_channel, count(*) as sums
                    from(
                      select 
                            UNIX_TIMESTAMP(DATE_FORMAT(FROM_UNIXTIME(p.add_time),"%Y-%m-%d %H:00")) as time,
                    
                            ROUND((p.add_time-y_wu_tjuserall.`update`)/60) as dotime,
                        p.chanel_bid as chanel,
                    p.chanel_sid as chanel_s,
                    p.versionid as versionid,
                    p.pay_channel as pay_channel
                    from pingoula_ad.p_vip_log p
                    LEFT JOIN 
                    man_tjdb.y_wu_tjuserall ON  
                    p.device_id = man_tjdb.y_wu_tjuserall.device_id  
                    WHERE 
                        p.recharge_type =1 and p.status =1 and 
                    p.add_time 
                    BETWEEN '.$this->_statdate.'  and '.$this->_enddate.' 
                    and man_tjdb.y_wu_tjuserall.device_id is not null
                     and p.chanel_bid!=\'\' and p.chanel_sid!=\'\' and p.versionid!=\'\'
                      HAVING dotime > 7*1440
                    )t
                    GROUP BY  time,chanel,chanel_s,versionid,pay_channel
                    ON DUPLICATE KEY UPDATE otherday=VALUES(otherday)';
        $otherinsert=Yii::app()->db->createCommand($otherdsql)->execute();
        if($otherinsert){
            $tr->commit();
            $art['code'] =0;
            $art['msg'] ='写入成功';
        }else{
            $tr->rollBack();
            $art['code'] =1;
            $art['msg'] ='写入失败';
        }
        echo json_encode($art);
    }

    public function actionarpu(){
        $date=strtotime(date('Y-m-d H:00:00'));
        $olddate = strtotime(date('Y-m-d H:00:00',$date-3600));
        $week = date("w");
        $time = date("H")-1;
        $powersql='SELECT 
                       power
                   FROM 
                      `y_wu_emailpower`
                  WHERE 
                      id = 1
                      ';
        $powerdo=Yii::app()->db->createCommand($powersql)->queryAll();
        if($powerdo['power'][0]==1){
            $sql='SELECT
                    DATE_FORMAT(FROM_UNIXTIME(dodate),"%Y-%m-%d %H:00") as time,
                    sum(adduser) as adduser,
                    sum(openuser) as openuser,
                    sum(payuser) as payuser,
                    sum(newpayuser) as newpayuser,
                    sum(paymoney) as paymoney
                FROM
                    y_wu_tjselectday
                WHERE 
                    dodate 
                BETWEEN '.$olddate.' and '.$date.'
                AND
                 
                     pay_type=0
                     group by
                time  ';
            $ztqsall=Yii::app()->db->createCommand($sql)->queryAll();
            $arpu =empty($ztqsall[0]['adduser'])?'0':sprintf("%.2f",$ztqsall[0]['paymoney']/$ztqsall[0]['adduser']);
            $sitesql='SELECT 
                       arpu
                   FROM 
                      `y_wu_dayarpu`
                  WHERE 
                      weekday = '.$week.' and
                      dodate ='.$time.'
                      ';
            $listalls=Yii::app()->db->createCommand($sitesql)->queryAll();
            $sitearpu =empty($listalls[0]['arpu'])?'0':$listalls[0]['arpu'];
            if($sitearpu<$arpu){//设置的比实际的高，警报开始
                $emalis=array('546167337@qq.com','978749522@qq.com');
                for($i=0;$i<count($emalis);$i++){
                    $useremail=$emalis[$i];
                    $title='警告！男人影院-'.date('Y-m-d H:00:00',$date-3600).'时段的ARPU低于设置值';
                    $touseradd=$useremail;//$_POST['email'];
                    $tocontenr='统计：'.date('Y-m-d H:00:00',$date-3600).'时段的ARPU低于设置值，设置值：'.$sitearpu.'. 实际值：'.$arpu.' ';
                    $res=$add= Helper::sendMail($title,$touseradd,$tocontenr);
                    if($res==true){
                        file_put_contents('./tongji/arpu/'. $this->_date.'_emailsuc.log','email——发送成功名单：'.$emalis[$i]."\r\n",FILE_APPEND); //测试用
                    }else{
                        file_put_contents('./tongji/arpu/'. $this->_date.'_emailerr.log','email——发送失败名单：'.$emalis[$i]."\r\n",FILE_APPEND); //测试用
                    }

                }
            }else{
                file_put_contents('./tongji/arpu/'. $this->_date.'_arpu.log','arpu——统计时段'.date('Y-m-d H:00:00',$date-3600)."\r\n",FILE_APPEND); //测试用
            }
        }else{
            var_dump('不执行警告');
            exit();
        }


    }





}