<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2017/9/22
 * Time: 15:18
 */

class TjaddorderCommand  extends CConsoleCommand
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
        $this->addorder();
    }
    /*统计每日新增*/
    private function addorder(){
        $tr = Yii::app()->db->beginTransaction();
        $addsql='INSERT INTO y_vip_log(order_id,device_id,money,status,add_time,recharge_type,channel,version_name,pay_channel,dem_num,last_watching,pay_type) 
                    SELECT
                     d.id,
                     d.device_id,
                     d.money,
                     d.status,
                     d.add_time,
                     d.recharge_type,
                     d.channel,
                     d.version_name,
                     d.pay_channel,
                     d.dev_num as dem_num,
                     d.last_watching,
                     d.pay_type
                    FROM
                     pingoula_ad.`p_vip_log` d
                   
                    WHERE
                     d.status =1 
                    and  d.add_time BETWEEN '.$this->_statdate.'  and '.$this->_enddate.' 
                    ON DUPLICATE KEY UPDATE 
                    device_id=VALUES(device_id),
                    money=VALUES(money),
                    status=VALUES(status),
                    add_time=VALUES(add_time),
                    channel=VALUES(channel),
                    recharge_type=VALUES(recharge_type),
                    version_name=VALUES(version_name),
                    pay_channel=VALUES(pay_channel),
                    dem_num=VALUES(dem_num),
                    last_watching=VALUES(last_watching),
                    pay_type=VALUES(pay_type)';

        $addinsert=Yii::app()->db->createCommand($addsql)->execute();
        if($addinsert){
            $tr->commit();
            $art['code'] =0;
            $art['msg'] ='写入成功';
            file_put_contents( $this->_files.'/tongji/suc/'. $this->_date.'_tj.log','拷贝统计订单表——'.$addinsert."\r\n",FILE_APPEND); //测试用
        }else{
            $tr->rollBack();
            $art['code'] =1;
            $art['msg'] ='写入失败';
            file_put_contents($this->_files.'/tongji/err/'. $this->_date.'_tj.log','拷贝统计订单表——'.$addinsert.'-失败时间-'.date('Y-m-d H:i:s')."\r\n",FILE_APPEND); //测试用
        }

        echo json_encode($art);
    }


}