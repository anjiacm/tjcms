<?php

/**
 * This is the model class for table "{{wu_app}}".
 *
 * The followings are the available columns in table '{{wu_app}}':
 * @property integer $id
 * @property string $appname
 * @property integer $appbigtypeid
 * @property integer $appsmtypeid
 * @property string $apptitle
 * @property string $appicon
 * @property integer $appdonum
 * @property string $appimg
 * @property string $appnumyes
 * @property string $appcontent
 * @property string $appsize
 * @property integer $apporder
 * @property string $appdodate
 * @property integer $apppower
 * @property integer $appurl
 */
class WuApp extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{wu_app}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('appbigtypeid, appsmtypeid, appdonum, appnumyes, apporder, apppower', 'numerical', 'integerOnly'=>true),
			array('appname, apptitle,appsize, appdodate,appicon', 'length', 'max'=>255),
			array('appimg, appcontent,appurl', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, appname, appicon,appurl, appbigtypeid,appsize, appsmtypeid, apptitle, appdonum, appnumyes, appimg, appcontent, apporder, appdodate, apppower', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(

		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => '序号',
			'appname' => '应用名',
			'appbigtypeid' => '应用类型',
			'appsmtypeid' => '应用标签',
			'apptitle' => '应用副标题',
            'appurl' => '应用下载地址',
			'appdonum' => '点击量',
            'appicon' => '应用icon',
            'appsize' => '应用大小',
			'appimg' => '应用推广图',
			'appcontent' => '应用介绍',
			'apporder' => '排序',
			'appdodate' => '添加时间',
			'apppower' => '状态',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 *
	 * Typical usecase:
	 * - Initialize the model fields with values from filter form.
	 * - Execute this method to get CActiveDataProvider instance which will filter
	 * models according to data in model fields.
	 * - Pass data provider to CGridView, CListView or any similar widget.
	 *
	 * @return CActiveDataProvider the data provider that can return the models
	 * based on the search/filter conditions.
	 */
	public function search()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('appname',$this->appname,true);
        $criteria->compare('appicon',$this->appicon,true);
		$criteria->compare('appbigtypeid',$this->appbigtypeid);
		$criteria->compare('appsmtypeid',$this->appsmtypeid);
		$criteria->compare('apptitle',$this->apptitle,true);
        $criteria->compare('appsize',$this->appsize,true);
        $criteria->compare('appurl',$this->appurl);
		$criteria->compare('appdonum',$this->appdonum);
        $criteria->compare('appnumyes',$this->appnumyes);
		$criteria->compare('appimg',$this->appimg,true);
		$criteria->compare('appcontent',$this->appcontent,true);
		$criteria->compare('apporder',$this->apporder);
		$criteria->compare('appdodate',$this->appdodate,true);
		$criteria->compare('apppower',$this->apppower);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return WuApp the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}


	public static  function  typealllist(){
        $result = array();
        $result['bigtype'] = array();
        $result['stype'] = array();
        $bigmodel = new WuBigtype();
        $smodel = new Wutype();
        $bigdata  = $bigmodel->findAll();
        $sdata  = $smodel->findAll();
        if($bigdata){
            foreach ($bigdata as $bigitem){
                $result['bigtype'][$bigitem['id']]=$bigitem['typename'];
            }
            foreach ($sdata as $sitem){
                $result['stype'][$sitem['id']]=$sitem['s_typename'];
            }
        }

        return $result;

    }
    public static function appreslist(){
        $bigmodel = new WuBigtype();
        $smodel = new Wutype();
        $stypecriteria = new CDbCriteria();
        $result = array();

        $bigdata  = $bigmodel->findAll();

        foreach ($bigdata as &$list){
            $result['sm']=array();
            $stype['s_bigtypeid'] = $list['id'];
            $stypecriteria->addCondition('s_bigtypeid='.$stype['s_bigtypeid']);
            $smdata  = $smodel->findAll();


        }
        //var_dump($bigdata);
        return $result;
    }

}
