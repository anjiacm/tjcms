<?php

/**
 * This is the model class for table "{{vip_log}}".
 *
 * The followings are the available columns in table '{{vip_log}}':
 * @property integer $id
 * @property integer $order_id
 * @property string $device_id
 * @property string $money
 * @property integer $status
 * @property integer $add_time
 * @property integer $recharge_type
 * @property string $channel
 * @property string $version_name
 * @property integer $pay_channel
 * @property integer $chanel_bid
 * @property integer $chanel_sid
 * @property integer $versionid
 * @property integer $chanel_web
 * @property integer $dem_num
 * @property string $last_watching
 * @property integer $pay_type
 */
class VipLog extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{vip_log}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('order_id, device_id, add_time', 'required'),
			array('order_id, status, add_time, recharge_type, pay_channel, chanel_bid, chanel_sid, versionid, chanel_web, dem_num, pay_type', 'numerical', 'integerOnly'=>true),
			array('device_id, channel, version_name', 'length', 'max'=>255),
			array('money', 'length', 'max'=>11),
			array('last_watching', 'length', 'max'=>100),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, order_id, device_id, money, status, add_time, recharge_type, channel, version_name, pay_channel, chanel_bid, chanel_sid, versionid, chanel_web, dem_num, last_watching, pay_type', 'safe', 'on'=>'search'),
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
			'order_id' => 'Order',
			'device_id' => 'Device',
			'money' => 'Money',
			'status' => 'Status',
			'add_time' => 'Add Time',
			'recharge_type' => 'Recharge Type',
			'channel' => 'Channel',
			'version_name' => 'Version Name',
			'pay_channel' => 'Pay Channel',
			'chanel_bid' => 'Chanel Bid',
			'chanel_sid' => 'Chanel Sid',
			'versionid' => 'Versionid',
			'chanel_web' => 'Chanel Web',
			'dem_num' => 'Dem Num',
			'last_watching' => 'Last Watching',
			'pay_type' => 'Pay Type',
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
		$criteria->compare('order_id',$this->order_id);
		$criteria->compare('device_id',$this->device_id,true);
		$criteria->compare('money',$this->money,true);
		$criteria->compare('status',$this->status);
		$criteria->compare('add_time',$this->add_time);
		$criteria->compare('recharge_type',$this->recharge_type);
		$criteria->compare('channel',$this->channel,true);
		$criteria->compare('version_name',$this->version_name,true);
		$criteria->compare('pay_channel',$this->pay_channel);
		$criteria->compare('chanel_bid',$this->chanel_bid);
		$criteria->compare('chanel_sid',$this->chanel_sid);
		$criteria->compare('versionid',$this->versionid);
		$criteria->compare('chanel_web',$this->chanel_web);
		$criteria->compare('dem_num',$this->dem_num);
		$criteria->compare('last_watching',$this->last_watching,true);
		$criteria->compare('pay_type',$this->pay_type);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return VipLog the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
