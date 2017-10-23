<?php

/**
 * This is the model class for table "{{wu_tjselectall_time}}".
 *
 * The followings are the available columns in table '{{wu_tjselectall_time}}':
 * @property integer $id
 * @property integer $channel
 * @property integer $channel_s
 * @property integer $channe_all
 * @property integer $dodate
 * @property integer $pay_type
 * @property integer $app_version
 * @property string $app_movie
 * @property integer $payuser
 * @property integer $adduser
 * @property integer $openuser
 * @property integer $newpayuser
 * @property double $paymoney
 * @property integer $timetype
 * @property integer $tenuser
 * @property integer $houruser
 * @property integer $onedayuser
 * @property integer $threeday
 * @property integer $sevenday
 * @property integer $otherday
 * @property integer $zrfuser
 * @property double $zrfmoney
 * @property integer $wxuser
 * @property double $wxmoney
 * @property integer $aliuser
 * @property double $alimoney
 */
class WuTjselectallTime extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{wu_tjselectall_time}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('channel, channel_s, channe_all, dodate, pay_type, app_version, payuser, adduser, openuser, newpayuser, timetype, tenuser, houruser, onedayuser, threeday, sevenday, otherday, zrfuser, wxuser, aliuser', 'numerical', 'integerOnly'=>true),
			array('paymoney, zrfmoney, wxmoney, alimoney', 'numerical'),
			array('app_movie', 'length', 'max'=>255),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, channel, channel_s, channe_all, dodate, pay_type, app_version, app_movie, payuser, adduser, openuser, newpayuser, paymoney, timetype, tenuser, houruser, onedayuser, threeday, sevenday, otherday, zrfuser, zrfmoney, wxuser, wxmoney, aliuser, alimoney', 'safe', 'on'=>'search'),
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
			'channel' => 'Channel',
			'channel_s' => 'Channel S',
			'channe_all' => 'Channe All',
			'dodate' => 'Dodate',
			'pay_type' => 'Pay Type',
			'app_version' => 'App Version',
			'app_movie' => 'App Movie',
			'payuser' => 'Payuser',
			'adduser' => 'Adduser',
			'openuser' => 'Openuser',
			'newpayuser' => 'Newpayuser',
			'paymoney' => 'Paymoney',
			'timetype' => 'Timetype',
			'tenuser' => 'Tenuser',
			'houruser' => 'Houruser',
			'onedayuser' => 'Onedayuser',
			'threeday' => 'Threeday',
			'sevenday' => 'Sevenday',
			'otherday' => 'Otherday',
			'zrfuser' => 'Zrfuser',
			'zrfmoney' => 'Zrfmoney',
			'wxuser' => 'Wxuser',
			'wxmoney' => 'Wxmoney',
			'aliuser' => 'Aliuser',
			'alimoney' => 'Alimoney',
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
		$criteria->compare('channel',$this->channel);
		$criteria->compare('channel_s',$this->channel_s);
		$criteria->compare('channe_all',$this->channe_all);
		$criteria->compare('dodate',$this->dodate);
		$criteria->compare('pay_type',$this->pay_type);
		$criteria->compare('app_version',$this->app_version);
		$criteria->compare('app_movie',$this->app_movie,true);
		$criteria->compare('payuser',$this->payuser);
		$criteria->compare('adduser',$this->adduser);
		$criteria->compare('openuser',$this->openuser);
		$criteria->compare('newpayuser',$this->newpayuser);
		$criteria->compare('paymoney',$this->paymoney);
		$criteria->compare('timetype',$this->timetype);
		$criteria->compare('tenuser',$this->tenuser);
		$criteria->compare('houruser',$this->houruser);
		$criteria->compare('onedayuser',$this->onedayuser);
		$criteria->compare('threeday',$this->threeday);
		$criteria->compare('sevenday',$this->sevenday);
		$criteria->compare('otherday',$this->otherday);
		$criteria->compare('zrfuser',$this->zrfuser);
		$criteria->compare('zrfmoney',$this->zrfmoney);
		$criteria->compare('wxuser',$this->wxuser);
		$criteria->compare('wxmoney',$this->wxmoney);
		$criteria->compare('aliuser',$this->aliuser);
		$criteria->compare('alimoney',$this->alimoney);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return WuTjselectallTime the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
