<?php

/**
 * This is the model class for table "{{wu_sgoods}}".
 *
 * The followings are the available columns in table '{{wu_sgoods}}':
 * @property integer $id
 * @property integer $goodsid
 * @property string $s_goodsname
 * @property string $s_goodsimg
 * @property string $s_gooddate
 */
class WuSgoods extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{wu_sgoods}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('goodsid', 'required'),
			array('goodsid', 'numerical', 'integerOnly'=>true),
			array('s_goodsname, s_goodsimg, s_gooddate', 'length', 'max'=>255),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, goodsid, s_goodsname, s_goodsimg, s_gooddate', 'safe', 'on'=>'search'),
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
			'goodsid' => '所属类别',
			's_goodsname' => '标签名',
			's_goodsimg' => '标签图',
			's_gooddate' => '时间',
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
		$criteria->compare('goodsid',$this->goodsid);
		$criteria->compare('s_goodsname',$this->s_goodsname,true);
		$criteria->compare('s_goodsimg',$this->s_goodsimg,true);
		$criteria->compare('s_gooddate',$this->s_gooddate,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return WuSgoods the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	public static  function  getgoodslist(){
        $result = array();
        $model = new WuGoodstype();
        $data  = $model->findAll();
        if($data){
            foreach ($data as $item){
                $result[$item['id']]=$item['goodstype'];
            }
        }

        return $result;
    }
}
