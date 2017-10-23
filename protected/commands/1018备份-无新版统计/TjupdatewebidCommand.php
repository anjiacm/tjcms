<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2017/9/22
 * Time: 15:18
 */

class TjupdatewebidCommand  extends CConsoleCommand
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
        $this->updatewid();
    }
    /*统计每日新增*/
    private function updatewid(){
        $model=new VipLog();
        $criteria = new CDbCriteria();
        $criteria->addBetweenCondition('add_time',$this->_statdate,$this->_enddate);
        $result = $model->findAll($criteria);
        foreach ($result as $list){
            if($list['chanel_web']==0) {
                $channel[$list['id']] = preg_split('/[-_\*]+/is', $list['channel']);
                $tidin = empty($channel[$list['id']][1]) ? '未知' : $channel[$list['id']][1];;
                $tidmodel = new WuChanelTwo();
                $tidcriteria = new CDbCriteria();
                $tidcriteria->addCondition('chanle_web="' . $tidin . '"');
                $tidresult = $tidmodel->find($tidcriteria);
                if ($tidresult) {
                    $tid = intval($tidresult['id']);
                } else {
                    $tid = 1;
                }
                $savemodel = new VipLog();
                $savemodel->updateBypk($list['id'], array('chanel_web' => $tid));
            }
        }
    }


}