<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2017/9/22
 * Time: 15:18
 */

class TjemailCommand  extends CConsoleCommand
{
    public $_statdate ;
    public $_enddate ;
    public $_date ;
    public $_files;
    public $_email;
    public $weekArr=array(7,1,2,3,4,5,6);
    public function init()
    {
        $this->_statdate=strtotime(date("Y-m-d")) ;
        $this->_enddate=strtotime(date("Y-m-d",strtotime("+1 day")));
        $this->_date = date("Y-m-d");
        $this->_files=dirname(__FILE__);
       // $this->_email=array('735124756@qq.com','748871002@qq.com','huangkai@yonrun.com');
        $this->_email=array('546167337@qq.com');
    }

    public function run($args)
    {
        $this->arpu();
    }
    /*统计每日新增*/
    private function arpu(){

        $date=strtotime(date('Y-m-d H:00:00'));
        $olddate = strtotime(date('Y-m-d H:00:00',$date-3600));
        $weeknow = date("w");
        $week= $this->weekArr[$weeknow];
        $time = date("H")-1;
        $powersql='SELECT 
                       power
                   FROM 
                      `y_wu_emailpower`
                  WHERE 
                      id = 1
                      ';
        $powerdo=Yii::app()->db->createCommand($powersql)->queryAll();
        if($powerdo[0]['power']==1) {
            $sql = 'SELECT
                    DATE_FORMAT(FROM_UNIXTIME(dodate),"%Y-%m-%d %H:00") as time,
                    sum(adduser) as adduser,
                    sum(openuser) as openuser,
                    sum(payuser) as payuser,
                    sum(newpayuser) as newpayuser,
                    sum(paymoney) as paymoney
                FROM
                    y_wu_tjselectday
                WHERE 
                    dodate 
                BETWEEN ' . $olddate . ' and ' . $date . '
                AND
                 
                     pay_type=0
                     group by
                time  ';
            $ztqsall = Yii::app()->db->createCommand($sql)->queryAll();
            $arpu = empty($ztqsall[0]['adduser']) ? '0' : sprintf("%.2f", $ztqsall[0]['paymoney'] / $ztqsall[0]['adduser']);
            $sitesql = 'SELECT 
                       arpu
                   FROM 
                      `y_wu_dayarpu`
                  WHERE 
                      weekday = ' . $week . ' and
                      dodate =' . $time . '
                      ';
            $listalls = Yii::app()->db->createCommand($sitesql)->queryAll();
            $sitearpu = empty($listalls[0]['arpu']) ? '0' : $listalls[0]['arpu'];
            if ($sitearpu < $arpu) {//设置的比实际的高，警报开始

                for ($i = 0; $i < count($this->_email); $i++) {
                    $useremail = $this->_email[$i];
                    $title = '警告！ARPU低于设置值-' . date('Y-m-d H:00:00', $date - 3600) . '时段的ARPU低于设置值';
                    $touseradd = $useremail;//$_POST['email'];
                    $tocontenr = '统计：' . date('Y-m-d H:00:00', $date - 3600) . '时段的ARPU低于设置值，设置值：' . $sitearpu . '. 实际值：' . $arpu . ' ';
                    $res = Helper::sendMail($title, $touseradd, $tocontenr);
                    if ($res == true) {
                        file_put_contents($this->_files . '/tongji/arpu/' . $this->_date . '_emailsuc.log', 'email——发送成功名单：' . $this->_email[$i] . "\r\n", FILE_APPEND); //测试用
                    } else {
                        file_put_contents($this->_files . '/tongji/arpu/' . $this->_date . '_emailerr.log', 'email——发送失败名单：' . $this->_email[$i] . "\r\n", FILE_APPEND); //测试用
                    }

                }
            } else {
                file_put_contents($this->_files . '/tongji/arpu/' . $this->_date . '_arpu.log', 'arpu——统计时段' . date('Y-m-d H:00:00', $date - 3600) . "\r\n", FILE_APPEND); //测试用
            }
        }else{
            file_put_contents($this->_files . '/tongji/arpu/' . $this->_date . '_arpu.log', date('Y-m-d H:00:00', $date) ."不执行警告 \r\n", FILE_APPEND); //测试用
        }
    }

}