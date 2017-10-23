<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2017/9/22
 * Time: 15:18
 */

class TjadduserCommand  extends CConsoleCommand
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
        $this->adduser();
    }
    /*统计每日新增*/
    private function adduser(){
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
            file_put_contents( $this->_files.'/tongji/suc/'. $this->_date.'_tj.log','统计新增用户——'.$addinsert."\r\n",FILE_APPEND); //测试用
        }else{
            $tr->rollBack();
            $art['code'] =1;
            $art['msg'] ='写入失败';
            file_put_contents($this->_files.'/tongji/err/'. $this->_date.'_tj.log','统计新增用户——'.$addinsert.'-失败时间-'.date('Y-m-d H:i:s')."\r\n",FILE_APPEND); //测试用
        }

        echo json_encode($art);
    }


}