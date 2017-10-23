<?php

class DefaultController extends FrontBase
{
    protected $_typelist;
    protected $_goodskdlist;
    protected $_goodsqdlist;
    protected $_goodsbqlist;

    public function init()
    {
        parent::init();
        $this->layout = 'shop.views.layouts.main';

        $this->_typelist=WuApp::typealllist();

    }

    public function actionIndex()
	{
        $model=new WuApp;
        $bigmodel=new WuBigType;
        $stypemodel=new WuType;
        $giftmodel=new WuGift;
        $hbmodel=new WuHbtj;

        $yycriteria = new CDbCriteria();
        $gamecriteria = new CDbCriteria();
        $fulicriteria = new CDbCriteria();
        $hbcriteria = new CDbCriteria();

        $hbcriteria->addCondition('hbtypeid=4');
        $hbcriteria->addCondition('hbpower=0');
        $hbcriteria->order= 't.hborder';
        $hbresult = $hbmodel->findAll($hbcriteria);

        $gamecriteria->addCondition('appbigtypeid=1');
        $gamecriteria->addCondition('apppower=0');
        $gamecriteria->order= 't.apporder';
        $gameresult = $model->findAll($gamecriteria);

        $yycriteria->addCondition('appbigtypeid=2');
        $yycriteria->addCondition('apppower=0');
        $yycriteria->order= 't.apporder';
        $yyresult = $model->findAll($yycriteria);

        $fulicriteria->addCondition('giftpower=0');
        $fulicriteria->order='t.giftorder';
        $fuliresult = $giftmodel->findAll($fulicriteria);

		$this->render('index', array('model' => $model,'gameresult'=>$gameresult,'yyresult'=>$yyresult,'fuliresult'=> $fuliresult,'hbresult'=>$hbresult));
	}
    public function actionappres()
    {
        $model=new WuApp;
        $bigmodel=new WuBigType;
        $stypemodel=new WuType;
        $hbmodel=new WuHbtj;
        /*参数传递*/
        $appid = intval(Yii::app()->request->getParam('appid'));

        $gamecriteria = new CDbCriteria();
        $gameorcriteria = new CDbCriteria();
        $gameorcriteria->addNotInCondition('id',array($appid));
        $gamecriteria->addCondition('id='.$appid);
        $gameresult = $model->find($gamecriteria);
        $gameorcriteria->addCondition('appbigtypeid='.$gameresult['appbigtypeid']);
        $gameorcriteria->limit =4;

        $gameorresult = $model->findAll($gameorcriteria);
        $listimg = explode(',',$gameresult['appimg']);

        $this->render('Appres', array('model' => $model,'gameresult'=>$gameresult,'gameorresult'=>$gameorresult,'listimg'=>$listimg));
    }

    public function actionapplist()
    {
        $model=new WuApp;
        $bigmodel=new WuBigType;
        $stypemodel=new WuType;
        $hbmodel=new WuHbtj;

        /*参数传递*/
        $typeid = intval(Yii::app()->request->getParam('typeid'));
        $thesmtypeid = intval(Yii::app()->request->getParam('thesmtypeid'));

        /*查询大类*/
        $bigcriteria = new CDbCriteria();
        $bigcriteria->addCondition('id='.$typeid );
        $bigresult = $bigmodel->find($bigcriteria);

        /*查询海报*/
        $hbcriteria = new CDbCriteria();
        $hbcriteria->addCondition('hbtypeid='.$typeid );
        $hbcriteria->addCondition('hbpower=0');
        $hbcriteria->order= 't.hborder';
        $hbresult = $hbmodel->findAll($hbcriteria);

        /*查询样式列表*/
        $stypecriteria = new CDbCriteria();
        $stypecriteria->addCondition('s_bigtypeid='.$typeid );
        $stypecriteria->addCondition('s_power=0');
        $stypecriteria->order= 't.s_order';
        $styperesult = $stypemodel->findAll($stypecriteria);

        /*查询应用*/
        $yycriteria = new CDbCriteria();
        $yycriteria->addCondition('appbigtypeid='.$typeid );
        if($thesmtypeid){
            $yycriteria->addCondition('appsmtypeid='.$thesmtypeid );
        }
        $yycriteria->addCondition('apppower=0');
        $yycriteria->order= 't.apporder';
        $yycriteria->limit= 10;
        $yyresult = $model->findAll($yycriteria);

        $this->render('applist', array('model' => $model,'thesmtypeid'=>$thesmtypeid,'bigresult'=>$bigresult,'hbresult'=>$hbresult,'styperesult'=>$styperesult,'yyresult'=>$yyresult));
    }
    /*商业中心*/
    public function actionfulilist()
    {
        $model=new WuGift;
        $bigmodel=new WuBigType;
        $stypemodel=new WuType;
        $hbmodel=new WuHbtj;

        $this->_goodskdlist=WuGift::getgoodstypelist(1);
        $this->_goodsqdlist=WuGift::getgoodstypelist(2);
        $this->_goodsbqlist=WuGift::getgoodstypelist(3);
        /*参数传递*/
        $typeid = intval(Yii::app()->request->getParam('typeid'));
        $gifttype = intval(Yii::app()->request->getParam('gifttype'));

        /*查询大类*/
        $bigcriteria = new CDbCriteria();
        $bigcriteria->addCondition('id='.$typeid );
        $bigresult = $bigmodel->find($bigcriteria);

        /*查询海报*/
        $hbcriteria = new CDbCriteria();
        $hbcriteria->addCondition('hbtypeid='.$typeid );
        $hbcriteria->addCondition('hbpower=0');
        $hbcriteria->order= 't.hborder';
        $hbresult = $hbmodel->findAll($hbcriteria);

        /*查询样式列表*/
        $stypecriteria = new CDbCriteria();
        $stypecriteria->addCondition('s_bigtypeid='.$typeid );
        $stypecriteria->addCondition('s_power=0');
        $stypecriteria->order= 't.s_order';

        $styperesult = $stypemodel->findAll($stypecriteria);

        /*查询商品*/
        $yycriteria = new CDbCriteria();
        if($gifttype){
            $yycriteria->addCondition('gifttype='.$gifttype );
        }
        $yycriteria->addCondition('giftpower=0');
        $yycriteria->order= 't.giftorder';
        $yycriteria->offset = 0;
        $yycriteria->limit= 10;
        $yyresult = $model->findAll($yycriteria);

        $this->render('fulilist', array('model' => $model,'gifttype'=>$gifttype,'bigresult'=>$bigresult,'hbresult'=>$hbresult,'styperesult'=>$styperesult,'yyresult'=>$yyresult));
    }

    /*滚动加载*/
    public  function  actiongiftscolldo(){
        $model=new WuGift;
        $gifttype = intval(Yii::app()->request->getParam('gifttype'));
        $page = empty($_REQUEST['page'])?1:intval($_REQUEST['page']);
        /*查询商品*/
        $yycriteria = new CDbCriteria();
        if($gifttype){
            $yycriteria->addCondition('gifttype='.$gifttype );
        }
        $yycriteria->addCondition('giftpower=0');
        $yycriteria->order= 't.giftorder';
        $size =10;
        $yycriteria->limit= $size;
        $yycriteria->offset = ($page-1)*$size;
        $yyresult = $model->findAll($yycriteria);
        $kdlist =$this->_goodskdlist=WuGift::getgoodstypelist(1);
        $qdlist =$this->_goodsqdlist=WuGift::getgoodstypelist(2);
        $bqlist =$this->_goodsbqlist=WuGift::getgoodstypelist(3);

        if($yyresult){
            $art['qdlist'] = $qdlist;
            $art['bqlist'] = $bqlist;
            $art['kdlist'] = $kdlist;
            $art['yylist'] = json_decode(CJSON::encode($yyresult),TRUE);
            $art['code'] = 0;
            $art['msg'] = '数据查询成功';
        }else{
            $art['code'] = 1;
            $art['msg'] = '无数据';
        }
        Helper::ajaxReturn($art);
    }
    /*其他滚动加载*/
    public  function  actionappscolldo(){
        $model=new WuApp;
        $typeid = intval(Yii::app()->request->getParam('typeid'));
        $thesmtypeid = intval(Yii::app()->request->getParam('thesmtypeid'));
        $page = empty($_REQUEST['page'])?1:intval($_REQUEST['page']);

        /*查询应用*/
        $yycriteria = new CDbCriteria();
        $yycriteria->addCondition('appbigtypeid='.$typeid );
        if($thesmtypeid){
            $yycriteria->addCondition('appsmtypeid='.$thesmtypeid );
        }
        $yycriteria->addCondition('apppower=0');
        $yycriteria->order= 't.apporder';
        $size =10;
        $yycriteria->limit= $size;
        $yycriteria->offset = ($page-1)*$size;
        $yyresult = $model->findAll($yycriteria);
        $typlist = $this->_typelist=WuApp::typealllist();

        if($yyresult){
            $art['typelist'] = $typlist;
            $art['yylist'] = json_decode(CJSON::encode($yyresult),TRUE);
            $art['code'] = 0;
            $art['msg'] = '数据查询成功';
        }else{
            $art['code'] = 1;
            $art['msg'] = '无数据';
        }
        Helper::ajaxReturn($art);
    }
    public function  actiontext(){
        $model=new WuGift;
        $themodel = new WuSgoods();
        $yycriteria = new CDbCriteria();
        $yyresult = $model->findAll();
        $abc = $this->_goodsqdlist=WuGift::getgoodstypelist(2);
        $thetypes =array();
        foreach($yyresult as$key=>  $list){

            $thetypes[$key] =  $list['giftqd'];



        }
        var_dump( $abc[$thetypes]);
    }
    public function actiondownum(){
        $model=new WuApp;
        $appid = intval(Yii::app()->request->getParam('id'));
        $gamecriteria = new CDbCriteria();
        $gameorcriteria = new CDbCriteria();
        $gameorcriteria->addCondition('id='.$appid);
        $gameresult = $model->find($gameorcriteria);
        $model->updateCounters(array('appnumyes'=>1),'id='.$appid);
        if($model){
            $art['code'] = 0;
            $art['dourl'] = $gameresult['appurl'];
            $art['msg'] = '修改成功';
        }else{
            $art['code'] = 1;
            $art['msg'] = '修改失败';
        }
        Helper::ajaxReturn($art);
    }
    public function actiongiftdownum(){
        $model=new WuGift;
        $giftid = intval(Yii::app()->request->getParam('id'));

        $gameorcriteria = new CDbCriteria();
        $gameorcriteria->addCondition('id='.$giftid);
        $gameresult = $model->find($gameorcriteria);
        $model->updateCounters(array('giftnewdo'=>1),'id='.$giftid);
        if($model){
            $art['code'] = 0;
            $art['dourl'] = $gameresult['gifturl'];
            $art['msg'] = '修改成功';
        }else{
            $art['code'] = 1;
            $art['msg'] = '修改失败';
        }
        Helper::ajaxReturn($art);
    }
}