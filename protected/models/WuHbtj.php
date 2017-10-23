<?php

/**
 * This is the model class for table "{{wu_hbtj}}".
 *
 * The followings are the available columns in table '{{wu_hbtj}}':
 * @property integer $id
 * @property string $hbimg
 * @property string $hburl
 * @property integer $hbtypeid
 * @property integer $hborder
 * @property string $hbdodate
 * @property string $hbtext
 * @property integer $hbpower
 */
class WuHbtj extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{wu_hbtj}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('hbtypeid, hborder, hbpower', 'numerical', 'integerOnly'=>true),
			array('hbimg, hburl, hbdodate', 'length', 'max'=>255),
			array('hbtext', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, hbimg, hburl, hbtypeid, hborder, hbdodate, hbtext, hbpower', 'safe', 'on'=>'search'),
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
			'hbimg' => '图片',
			'hburl' => '海报链接',
			'hbtypeid' => '所属类型',
			'hborder' => '排序',
			'hbdodate' => '添加时间',
			'hbtext' => '描述',
			'hbpower' => '状态',
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
		$criteria->compare('hbimg',$this->hbimg,true);
		$criteria->compare('hburl',$this->hburl,true);
		$criteria->compare('hbtypeid',$this->hbtypeid);
		$criteria->compare('hborder',$this->hborder);
		$criteria->compare('hbdodate',$this->hbdodate,true);
		$criteria->compare('hbtext',$this->hbtext,true);
		$criteria->compare('hbpower',$this->hbpower);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return WuHbtj the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
