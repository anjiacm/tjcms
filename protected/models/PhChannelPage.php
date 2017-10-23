<?php

/**
 * This is the model class for table "{{ph_channel_page}}".
 *
 * The followings are the available columns in table '{{ph_channel_page}}':
 * @property integer $id
 * @property string $channel_title
 * @property integer $page_id
 * @property string $page_url
 * @property string $android_id
 * @property string $ios_id
 * @property integer $theme
 * @property integer $sort_order
 * @property integer $createtime
 * @property integer $updatetime
 * @property integer $playtime
 * @property integer $tv_num
 * @property string $content_title
 * @property string $content_sub_title
 * @property string $statistics
 * @property string $playactorlist
 */
class PhChannelPage extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{ph_channel_page}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('page_id, theme, sort_order, createtime, updatetime, playtime,channel_id,showtime,tv_num', 'numerical', 'integerOnly'=>true),
			array('channel_title, android_id, ios_id,statistics,speciallist,playactorlist', 'length', 'max'=>100),
			array('content_title,content_sub_title', 'length', 'max'=>50),
			array('page_url', 'length', 'max'=>255),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, channel_title, page_id, page_url, android_id, ios_id,statistics,speciallist, theme, sort_order,tv_num, createtime, updatetime, playtime,showtime,channel_id,content_title,content_sub_title', 'safe', 'on'=>'search'),
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
			'channel' => array(self::BELONGS_TO, 'PhChannel', 'channel_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'channel_title' => '渠道名',
			'page_id' => '内容id',
			'page_url' => '内容连接',
			'android_id' => '安卓',
			'ios_id' => 'iOS',
			'theme' => '模版',
			'sort_order' => '排序',
			'createtime' => '创建时间',
			'updatetime' => '更新时间',
			'playtime' => '播放时间(分)',
			'channel_id' => '渠道',
			'content_title' => '内容标题',
			'content_sub_title' => '内容标题英文缩写',
			'showtime' => '展示间隔(分)',
			'statistics' => '统计',
			'tv_num' => '集数',
			'speciallist' => '专题列表',
			'playactorlist' => '演员列表',
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
		$criteria->compare('channel_title',$this->channel_title,true);
		$criteria->compare('page_id',$this->page_id);
		$criteria->compare('page_url',$this->page_url,true);
		$criteria->compare('android_id',$this->android_id);
		$criteria->compare('ios_id',$this->ios_id);
		$criteria->compare('theme',$this->theme);
		$criteria->compare('sort_order',$this->sort_order);
		$criteria->compare('createtime',$this->createtime);
		$criteria->compare('updatetime',$this->updatetime);
		$criteria->compare('playtime',$this->playtime);
		$criteria->compare('channel_id',$this->channel_id);
		$criteria->compare('content_title',$this->content_title);
		$criteria->compare('content_sub_title',$this->content_sub_title);
		$criteria->compare('showtime',$this->showtime);
		$criteria->compare('statistics',$this->statistics);
		$criteria->compare('tv_num',$this->tv_num);
		$criteria->compare('speciallist',$this->speciallist);
		$criteria->compare('playactorlist',$this->playactorlist);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return PhChannelPage the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
