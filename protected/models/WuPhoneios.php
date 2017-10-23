<?php

/**
 * This is the model class for table "{{wu_phoneios}}".
 *
 * The followings are the available columns in table '{{wu_phoneios}}':
 * @property integer $id
 * @property string $ip
 * @property string $iphonetype
 * @property string $system
 * @property string $qudao
 * @property integer $channel_bid
 * @property integer $channel_wid
 * @property integer $channel_sid
 * @property integer $dodate
 */
class WuPhoneios extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{wu_phoneios}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('channel_bid, channel_wid, channel_sid, dodate', 'numerical', 'integerOnly'=>true),
			array('ip, iphonetype, system, qudao', 'length', 'max'=>255),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, ip, iphonetype, system, qudao, channel_bid, channel_wid, channel_sid, dodate', 'safe', 'on'=>'search'),
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
			'ip' => 'Ip',
			'iphonetype' => 'Iphonetype',
			'system' => 'System',
			'qudao' => 'Qudao',
			'channel_bid' => 'Channel Bid',
			'channel_wid' => 'Channel Wid',
			'channel_sid' => 'Channel Sid',
			'dodate' => 'Dodate',
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
		$criteria->compare('ip',$this->ip,true);
		$criteria->compare('iphonetype',$this->iphonetype,true);
		$criteria->compare('system',$this->system,true);
		$criteria->compare('qudao',$this->qudao,true);
		$criteria->compare('channel_bid',$this->channel_bid);
		$criteria->compare('channel_wid',$this->channel_wid);
		$criteria->compare('channel_sid',$this->channel_sid);
		$criteria->compare('dodate',$this->dodate);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return WuPhoneios the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
