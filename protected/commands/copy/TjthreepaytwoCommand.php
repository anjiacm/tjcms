<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2017/9/22
 * Time: 15:18
 */

class TjthreepaytwoCommand  extends CConsoleCommand
{
    public $_statdate ;
    public $_enddate ;
    public $_date ;
    public $_files;
    public function init()
    {
        $this->_statdate=strtotime(date("Y-m-d",strtotime("-1 day"))) ;
        $this->_enddate=strtotime(date("Y-m-d"));
        $this->_date = date("Y-m-d");
        $this->_files=dirname(__FILE__);
    }

    public function run($args)
    {
        $this->threepaytwo();
    }
    /*统计每日新增*/
    private function threepaytwo(){
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
                    from y_vip_log p
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
            file_put_contents($this->_files.'/tongji/suc/'. $this->_date.'_tj.log','统计3天内付费(含支付渠道)——'.$threeinsert."\r\n",FILE_APPEND); //测试用
        }else{
            $tr->rollBack();
            $art['code'] =1;
            $art['msg'] ='写入失败';
            file_put_contents($this->_files.'/tongji/err/'. $this->_date.'_tj.log','统计3天内付费(含支付渠道)——'.$threeinsert.'-失败时间-'.date('Y-m-d H:i:s')."\r\n",FILE_APPEND); //测试用
        }
        echo json_encode($art);
    }


}