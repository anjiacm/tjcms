<?php

/**
 * This is the model class for table "{{wu_tjuserallnew}}".
 *
 * The followings are the available columns in table '{{wu_tjuserallnew}}':
 * @property CDbConnection $dbConnection The database connection used by active record.
 * @property integer $id
 * @property integer $old_id
 * @property string $quedao
 * @property integer $updates
 * @property string $version_name
 * @property string $device_id
 * @property integer $usersum
 * @property integer $chanel_bid
 * @property integer $chanel_sid
 * @property integer $versionid
 * @property integer $chanel_web
 * @property integer $dem_num
 */
class WuTjuserallnew extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{wu_tjuserallnew}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('old_id, updates, usersum, chanel_bid, chanel_sid, versionid, chanel_web, dem_num', 'numerical', 'integerOnly'=>true),
			array('quedao, version_name, device_id', 'length', 'max'=>255),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, old_id, quedao, updates, version_name, device_id, usersum, chanel_bid, chanel_sid, versionid, chanel_web, dem_num', 'safe', 'on'=>'search'),
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
			'old_id' => 'Old',
			'quedao' => 'Quedao',
			'updates' => 'Updates',
			'version_name' => 'Version Name',
			'device_id' => 'Device',
			'usersum' => 'Usersum',
			'chanel_bid' => 'Chanel Bid',
			'chanel_sid' => 'Chanel Sid',
			'versionid' => 'Versionid',
			'chanel_web' => 'Chanel Web',
			'dem_num' => 'Dem Num',
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
		$criteria->compare('old_id',$this->old_id);
		$criteria->compare('quedao',$this->quedao,true);
		$criteria->compare('updates',$this->updates);
		$criteria->compare('version_name',$this->version_name,true);
		$criteria->compare('device_id',$this->device_id,true);
		$criteria->compare('usersum',$this->usersum);
		$criteria->compare('chanel_bid',$this->chanel_bid);
		$criteria->compare('chanel_sid',$this->chanel_sid);
		$criteria->compare('versionid',$this->versionid);
		$criteria->compare('chanel_web',$this->chanel_web);
		$criteria->compare('dem_num',$this->dem_num);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return WuTjuserallnew the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
