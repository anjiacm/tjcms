<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2017/9/22
 * Time: 15:18
 */

class TjnewpayuseroneCommand  extends CConsoleCommand
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
        $this->newpayuserone();
    }
    /*统计每日新增*/
    private function newpayuserone(){
        $tr = Yii::app()->db->beginTransaction();
        $paynewsql='INSERT INTO y_wu_tjselectall(dodate,channel,channel_t,channel_s,app_version,newpayuser) 
                        select 
                        UNIX_TIMESTAMP(DATE_FORMAT(FROM_UNIXTIME(p.add_time),"%Y-%m-%d %H:00")) as time,
                        p.chanel_bid as chanel,
                        p.chanel_web as chanel_t,
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
                        and p.chanel_bid!=\'\' and p.chanel_web!=\'\' and p.chanel_sid!=\'\' and p.versionid!=\'\'
                        GROUP BY time,chanel,chanel_s,versionid
                        
                        ON DUPLICATE KEY UPDATE newpayuser=VALUES(newpayuser)';
        $paynewuserinsert=Yii::app()->db->createCommand($paynewsql)->execute();
        if($paynewuserinsert){
            $tr->commit();
            $art['code'] =0;
            $art['msg'] ='写入成功';
            file_put_contents($this->_files.'/tongji/suc/'. $this->_date.'_tj.log','统计新增用户付费(不含支付渠道)——'.$paynewuserinsert."\r\n",FILE_APPEND); //测试用
        }else{
            $tr->rollBack();
            $art['code'] =1;
            $art['msg'] ='写入失败';
            file_put_contents($this->_files.'/tongji/err/'. $this->_date.'_tj.log','统计新增用户付费(不含支付渠道)——'.$paynewuserinsert.'-失败时间-'.date('Y-m-d H:i:s')."\r\n",FILE_APPEND); //测试用
        }
        echo json_encode($art);
    }


}