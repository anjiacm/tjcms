<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2017/9/22
 * Time: 15:18
 */

class TjdaypayoneiosCommand  extends CConsoleCommand
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
        $this->daypayone();
    }
    /*统计每日新增*/
    private function daypayone(){
        $tr = Yii::app()->db->beginTransaction();
        $daysql='INSERT INTO y_wu_tjselectall(dodate,channel,channel_t,channel_s,app_version,dem_num,onedayuser) 
                        SELECT 
                        t.time as time ,t.chanel as chanel,t.chanel_t as chanel_t,t.chanel_s as chanel_s,t.versionid as versionid,t.dem_num as dem_num,count(*) as sums
                        from(
                          select 
                                UNIX_TIMESTAMP(DATE_FORMAT(FROM_UNIXTIME(p.add_time),"%Y-%m-%d %H:00")) as time,
                        
                                ROUND((p.add_time-y_wu_tjuserallios.`update`)/60) as dotime,
                            p.chanel_bid as chanel,
                             p.chanel_web as chanel_t,
                        p.chanel_sid as chanel_s,
                       
                        p.versionid as versionid,
                         p.dem_num as dem_num
                        from y_vip_log p
                        LEFT JOIN 
                        man_tjdb.y_wu_tjuserallios ON  
                        p.device_id = man_tjdb.y_wu_tjuserallios.device_id  
                        WHERE 
                            p.recharge_type =1 and p.status =1 and p.dem_num=2 and 
                        p.add_time 
                        BETWEEN '.$this->_statdate.'  and '.$this->_enddate.' 
                        and man_tjdb.y_wu_tjuserallios.device_id is not null
                         and p.chanel_bid!=\'\' and p.chanel_web!=\'\' and p.chanel_sid!=\'\' and p.versionid!=\'\'
                          HAVING dotime BETWEEN 60 and 1440
                        )t
                        GROUP BY  time,chanel,chanel_t,chanel_s,versionid,dem_num  
                        ON DUPLICATE KEY UPDATE  onedayuser=VALUES(onedayuser)';

        $dayinsert=Yii::app()->db->createCommand($daysql)->execute();
        if($dayinsert){
            $tr->commit();
            $art['code'] =0;
            $art['msg'] ='写入成功';
            file_put_contents($this->_files.'/tongji/suc/'. $this->_date.'_tj.log','统计1天内付费(不含支付渠道)——'.$dayinsert."\r\n",FILE_APPEND); //测试用
        }else{
            $tr->rollBack();
            $art['code'] =1;
            $art['msg'] ='写入失败';
            file_put_contents($this->_files.'/tongji/err/'. $this->_date.'_tj.log','统计1天内付费(不含支付渠道)——'.$dayinsert.'-失败时间-'.date('Y-m-d H:i:s')."\r\n",FILE_APPEND); //测试用
        }
        echo json_encode($art);
    }


}