<?php

/**
 * This is the model class for table "{{wu_gift}}".
 *
 * The followings are the available columns in table '{{wu_gift}}':
 * @property integer $id
 * @property string $giftname
 * @property string $gifturl
 * @property string $giftimg
 * @property integer $gifttype
 * @property integer $giftorder
 * @property string $giftmoney
 * @property string $giftnewmoney
 * @property integer $giftnum
 * @property string $giftdate
 * @property integer $giftnewdo
 * @property integer $giftcontent
 * @property integer $giftqd
 * @property integer $giftbq
 * @property integer $giftkd
 */
class WuGift extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{wu_gift}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('gifttype, giftorder,giftbq,giftqd,giftkd, giftnum, giftpower', 'numerical', 'integerOnly'=>true),
			array('giftname, giftimg, giftmoney, giftnewmoney, giftdate', 'length', 'max'=>255),
			array('gifturl,giftcontent', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, giftname, giftcontent,gifturl, giftbq,giftqd,giftkd,giftimg, gifttype, giftorder,giftnewdo, giftmoney, giftnewmoney, giftnum, giftdate, giftpower', 'safe', 'on'=>'search'),
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
			'giftname' => '商品名',
			'gifturl' => '商品链接',
			'giftimg' => '封面图',
			'gifttype' => '商品类型',
			'giftorder' => '排序',
			'giftmoney' => '商品原价',
			'giftnewmoney' => '商品现价',
			'giftnum' => '点击量',
			'giftdate' => '修改时间',
			'giftpower' => '商品状态',
            'giftcontent' => '商品描述',
            'giftkd' => '快递',
            'giftqd' => '渠道',
            'giftbq' => '标签',
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
		$criteria->compare('giftname',$this->giftname,true);
		$criteria->compare('gifturl',$this->gifturl,true);
        $criteria->compare('giftcontent',$this->giftcontent,true);
		$criteria->compare('giftimg',$this->giftimg,true);
		$criteria->compare('gifttype',$this->gifttype);
		$criteria->compare('giftorder',$this->giftorder);
		$criteria->compare('giftmoney',$this->giftmoney,true);
        $criteria->compare('giftnewdo',$this->giftnewdo,true);
		$criteria->compare('giftnewmoney',$this->giftnewmoney,true);
		$criteria->compare('giftnum',$this->giftnum);
		$criteria->compare('giftdate',$this->giftdate,true);
		$criteria->compare('giftpower',$this->giftpower);
        $criteria->compare('giftqd',$this->giftqd);
        $criteria->compare('giftkd',$this->giftkd);
        $criteria->compare('giftbq',$this->giftbq);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return WuGift the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}


    public static  function  getgoodslist(){
        $result = array();
        $model = new WuType();
        $criteria = new CDbCriteria();
        $criteria->addCondition('s_bigtypeid=3');
        $data  = $model->findAll($criteria);
        if($data){
            foreach ($data as $item){
                $result[$item['id']]=$item['s_typename'];
            }
        }

        return $result;
    }

    public static  function  getgoodstypelist($goodsid){
        $result = array();
        $model = new WuSgoods();
       // $smodel = new WuSgoods();
        $criteria = new CDbCriteria();
        $criteria->addCondition('goodsid='.$goodsid);
        $data  = $model->findAll($criteria);
        if($data){
            foreach ($data as $item){
                $result['s_goodsname'][$item['id']]=$item['s_goodsname'];
                $result['s_goodsimg'][$item['id']]=$item['s_goodsimg'];
            }
        }

        return $result;
    }
}
