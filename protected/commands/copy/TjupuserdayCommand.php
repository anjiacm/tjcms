<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2017/9/22
 * Time: 15:18
 */

class TjupuserdayCommand  extends CConsoleCommand
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
        $this->upuser();
    }
    /*统计每日新增*/
    private function upuser(){
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
            $art['msg'] ='写入成功';
            //file_put_contents($this->_files.'/tongji/suc/'. $this->_date.'_tj.log','统计日活用户——'.$upinsert."\r\n",FILE_APPEND); //测试用
        }else{
            $tr->rollBack();
            $art['code'] =1;
            $art['msg'] ='写入失败';
           // file_put_contents($this->_files.'/tongji/err/'. $this->_date.'_tj.log','统计日活用户——'.$upinsert.'-失败时间-'.date('Y-m-d H:i:s')."\r\n",FILE_APPEND); //测试用
        }
        echo json_encode($art);
    }


}