<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2017/9/22
 * Time: 15:18
 */

class TjpaysumtwodayCommand  extends CConsoleCommand
{
    public $_statdate ;
    public $_enddate ;
    public $_date ;
    public $_files;
    public function init()
    {
        $this->_statdate=strtotime(date("Y-m-d")) ;
        $this->_enddate=strtotime(date("Y-m-d",strtotime("+1 day")));
        $this->_date = date("Y-m-d");
        $this->_files=dirname(__FILE__);
    }

    public function run($args)
    {
        $this->paysumtwo();
    }
    /*统计每日新增*/
    private function paysumtwo(){
        $tr = Yii::app()->db->beginTransaction();
        $paysumsql='INSERT INTO y_wu_tjselectday(dodate,channel,channel_t,channel_s,app_version,dem_num,pay_type,paysum) 
                    select  
                    UNIX_TIMESTAMP(DATE_FORMAT(FROM_UNIXTIME(p.add_time),"%Y-%m-%d %H:00")) as time,
                    p.chanel_bid as chanel,
                    p.chanel_web as chanel_t,
                    p.chanel_sid as chanel_s,
                    p.versionid as versionid,
                     p.dem_num as dem_num,
                    p.pay_channel as pay_channel,
                    count(*) as daynum
                    from 
                    y_vip_log  p
                    where  p.add_time 
                    BETWEEN '.$this->_statdate.'  and '.$this->_enddate.' 
                    and p.`status` =1
                    and p.`dem_num` =1
                    and p.chanel_bid!=\'\' and p.chanel_web!=\'\' and p.chanel_sid!=\'\' and p.versionid!=\'\' 
                    GROUP BY time,chanel,chanel_t,chanel_s,versionid,dem_num,pay_channel
                    
                    ON DUPLICATE KEY UPDATE paysum=VALUES(paysum)';
        $paysuminsert=Yii::app()->db->createCommand($paysumsql)->execute();
        if($paysuminsert){
            $tr->commit();
            $art['code'] =0;
            $art['msg'] ='写入成功';
            //file_put_contents($this->_files.'/tongji/suc/'. $this->_date.'_tj.log','统计付费次数(含支付渠道)——'.$paysuminsert."\r\n",FILE_APPEND); //测试用
        }else{
            $tr->rollBack();
            $art['code'] =1;
            $art['msg'] ='写入失败';
           // file_put_contents($this->_files.'/tongji/err/'. $this->_date.'_tj.log','统计付费次数(含支付渠道)——'.$paysuminsert.'-失败时间-'.date('Y-m-d H:i:s')."\r\n",FILE_APPEND); //测试用
        }
        echo json_encode($art);
    }


}