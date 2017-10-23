<?php

/**
 * This is the model class for table "{{wu_tjuserall}}".
 *
 * The followings are the available columns in table '{{wu_tjuserall}}':
 * @property CDbConnection $dbConnection The database connection used by active record.
 * @property integer $id
 * @property string $quedao
 * @property string $update
 * @property string $version_name
 * @property string $device_id
 * @property integer $usersum
 * @property integer $chanel_bid
 * @property integer $chanel_sid
 * @property integer $versionid
 * @property integer $chanel_web
 */
class WuTjuserall extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{wu_tjuserall}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('usersum,versionid,chanel_web,chanel_sid,chanel_bid', 'numerical', 'integerOnly'=>true),
			array('quedao, update, version_name, device_id', 'length', 'max'=>255),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id,chanel_sid,versionid,chanel_bid, quedao, update, version_name, device_id, usersum', 'safe', 'on'=>'search'),
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
			'quedao' => '渠道号',
			'update' => '注册日期',
			'version_name' => '版本号',
			'device_id' => '设备号',
			'usersum' => '总进入次数',
            'chanel_sid'=>'子渠道号',
            'chanel_bid'=>'母渠道号',
            'versionid'=>'版本id',
            'chanel_web'=>'网站渠道id',
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
		$criteria->compare('quedao',$this->quedao,true);
		$criteria->compare('update',$this->update,true);
		$criteria->compare('version_name',$this->version_name,true);
		$criteria->compare('device_id',$this->device_id,true);
		$criteria->compare('chanel_sid',$this->chanel_sid);
        $criteria->compare('chanel_bid',$this->chanel_bid);
        $criteria->compare('versionid',$this->versionid);
        $criteria->compare('chanel_web',$this->chanel_web);
		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return WuTjuserall the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
