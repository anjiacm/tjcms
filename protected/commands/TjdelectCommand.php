<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2017/9/22
 * Time: 15:18
 */

class TjdelectCommand  extends CConsoleCommand
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
        $this->delectall();
    }
    /*统计每日新增*/
    private function delectall(){

        $delsql='TRUNCATE  y_wu_tjselectday';
        $delsqldo=Yii::app()->db->createCommand($delsql)->execute();
        if($delsqldo){

            $art['code'] =0;
            $art['msg'] ='写入成功';
            file_put_contents('../tongji/delect/'. $this->_date.'_tj.log','清除数据表——'.$delsqldo."\r\n",FILE_APPEND); //测试用
        }else{

            $art['code'] =1;
            $art['msg'] ='写入失败';
            file_put_contents('../tongji/delect/'. $this->_date.'_tj.log','清除数据表——'.$delsqldo."\r\n",FILE_APPEND); //测试用
        }

        echo json_encode($art);
    }


}