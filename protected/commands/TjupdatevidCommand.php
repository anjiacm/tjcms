<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2017/9/22
 * Time: 15:18
 */

class TjupdatevidCommand  extends CConsoleCommand
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
        $this->updatevid();
    }
    /*统计每日新增*/
    private function updatevid(){
        $model=new VipLog();
        $criteria = new CDbCriteria();
        $criteria->addBetweenCondition('add_time',$this->_statdate,$this->_enddate);
        $result = $model->findAll($criteria);
        foreach ($result as $list){
            if($list['versionid']==0) {
                $vidin = empty($list['version_name']) ? '未知' : $list['version_name'];
                $vidmodel = new WuVersion();
                $vidcriteria = new CDbCriteria();
                $vidcriteria->addCondition('version_name="' . $vidin . '"');
                $vidresult = $vidmodel->find($vidcriteria);
                if ($vidresult) {
                    $sid = intval($vidresult['id']);
                } else {
                    $sid = 15;
                }
                $savemodel = new VipLog();
                $savemodel->updateBypk($list['id'], array('versionid' => $sid));
            }
        }
    }


}