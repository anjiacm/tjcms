<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2017/9/22
 * Time: 15:18
 */

class TjupusernewCommand  extends CConsoleCommand
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
        $this->upuser();
    }
    /*统计每日新增*/
    private function upuser(){
        $tr = Yii::app()->db->beginTransaction();
        $upsql='INSERT INTO y_wu_tjselectall(dodate,channel,channel_t,channel_s,app_version,dem_num,newopenuser) 
                select 
                UNIX_TIMESTAMP(DATE_FORMAT(FROM_UNIXTIME(y_wu_tjuseruphournew.`dayupdate`),"%Y-%m-%d %H:00")) as time,
                y_wu_tjuseruphournew.chanel_bid as chanel,
                y_wu_tjuseruphournew.chanel_web as chanel_t,
                y_wu_tjuseruphournew.chanel_sid as chanel_s,
                y_wu_tjuseruphournew.versionid as versionid,
                 y_wu_tjuseruphournew.dem_num as dem_num,
                count(*) as mannum 
                from 
                y_wu_tjuseruphournew 
                where 
                y_wu_tjuseruphournew.`dayupdate`
                BETWEEN '.$this->_statdate.'  and '.$this->_enddate.' 
                 and y_wu_tjuseruphournew.chanel_bid!=\'\' and y_wu_tjuseruphournew.chanel_web!=\'\' and y_wu_tjuseruphournew.chanel_sid!=\'\' and y_wu_tjuseruphournew.versionid!=\'\'
                group by time ,chanel,chanel_t,chanel_s,versionid,dem_num
                ON DUPLICATE KEY UPDATE newopenuser=VALUES(newopenuser)';
        $upinsert=Yii::app()->db->createCommand($upsql)->execute();
        if($upinsert){
            $tr->commit();
            $art['code'] =0;
            $art['msg'] ='写入成功';
            file_put_contents($this->_files.'/tongji/suc/'. $this->_date.'_tj.log','统计日活用户新版——'.$upinsert."\r\n",FILE_APPEND); //测试用
        }else{
            $tr->rollBack();
            $art['code'] =1;
            $art['msg'] ='写入失败';
            file_put_contents($this->_files.'/tongji/err/'. $this->_date.'_tj.log','统计日活用户新版——'.$upinsert.'-失败时间-'.date('Y-m-d H:i:s')."\r\n",FILE_APPEND); //测试用
        }
        echo json_encode($art);
    }


}