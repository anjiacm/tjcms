<?php

/**
 * This is the model class for table "{{wu_tjdayuser}}".
 *
 * The followings are the available columns in table '{{wu_tjdayuser}}':
 * @property integer $id
 * @property integer $userid
 * @property string $device_id
 * @property string $dayupdate
 * @property string $quedao
 * @property string $version_name
 * @property integer $upsum
 * @property integer $chanel_bid
 * @property integer $chanel_sid
 * @property integer $versionid
 * @property integer $chanel_web
 */
class WuTjdayuser extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{wu_tjdayuser}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('userid, upsum,chanel_bid,chanel_web,chanel_sid,versionid', 'numerical', 'integerOnly'=>true),
			array('device_id, dayupdate, quedao, version_name', 'length', 'max'=>255),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, userid, device_id, dayupdate, quedao, version_name, upsum', 'safe', 'on'=>'search'),
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
			'id' => 'ID',
			'userid' => '用户id',
			'device_id' => '设备号',
			'dayupdate' => '上线日期',
			'quedao' => '渠道号',
			'version_name' => '版本号',
            'upsum' => '每日上线次数',
            'chanel_bid' => 'chanel_bid',
            'chanel_sid' => 'chanel_sid',
            'versionid' => 'versionid',
            'chanel_web'=>'chanel_web',
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
		$criteria->compare('userid',$this->userid);
		$criteria->compare('device_id',$this->device_id,true);
		$criteria->compare('dayupdate',$this->dayupdate,true);
		$criteria->compare('quedao',$this->quedao,true);
		$criteria->compare('version_name',$this->version_name,true);
		$criteria->compare('upsum',$this->upsum);
        $criteria->compare('chanel_bid',$this->chanel_bid,true);
        $criteria->compare('chanel_sid',$this->chanel_sid,true);
        $criteria->compare('versionid',$this->versionid,true);
        $criteria->compare('chanel_web',$this->chanel_web,true);
		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return WuTjdayuser the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
