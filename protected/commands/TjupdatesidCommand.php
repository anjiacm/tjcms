<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2017/9/22
 * Time: 15:18
 */

class TjupdatesidCommand  extends CConsoleCommand
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
        $this->updatesid();
    }
    /*统计每日新增*/
    private function updatesid(){
        $model=new VipLog();
        $criteria = new CDbCriteria();
        $criteria->addBetweenCondition('add_time',$this->_statdate,$this->_enddate);
        $result = $model->findAll($criteria);
        foreach ($result as $list){
            if($list['chanel_sid']==0){
                $channel[$list['id']]=preg_split('/[-_\*]+/is', $list['channel']);
                $sidin=  empty($channel[$list['id']][2])?'未知':$channel[$list['id']][2];
                $sidmodel=new WuChanelThree();
                $sidcriteria = new CDbCriteria();
                $sidcriteria->addCondition('chanle_movie="'.$sidin.'"');
                $sidresult = $sidmodel->find($sidcriteria);
                if($sidresult){
                    $sid=intval($sidresult['id']);
                }else{
                    $sid= 3;
                }
                $savemodel=new VipLog();
                $savemodel->updateBypk($list['id'],array('chanel_sid'=>$sid));
            }


        }

    }


}