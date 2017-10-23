<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2017/9/22
 * Time: 15:18
 */

class TjupdatebidCommand  extends CConsoleCommand
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
        $this->updatebid();
    }
    /*统计每日新增*/
    private function updatebid(){
        $model=new VipLog();
        $criteria = new CDbCriteria();
        $criteria->addBetweenCondition('add_time',$this->_statdate,$this->_enddate);
        $result = $model->findAll($criteria);
        foreach ($result as $list){
            //$tr = Yii::app()->db->beginTransaction();
            if($list['chanel_bid']==0) {
                $channel[$list['id']] = preg_split('/[-_\*]+/is', $list['channel']);
                $bidin = empty($channel[$list['id']][0]) ? '未知' : $channel[$list['id']][0];
                $bidmodel = new WuChanelOne();
                $bidcriteria = new CDbCriteria();
                $bidcriteria->addCondition('chanle="' . $bidin . '"');
                $bidresult = $bidmodel->find($bidcriteria);
                if ($bidresult) {
                    $bid = intval($bidresult['id']);
                } else {
                    $bid = 6;
                }
                $savemodel = new VipLog();
                $savemodel->updateBypk($list['id'], array('chanel_bid' => $bid));
            }
        }
    }



}