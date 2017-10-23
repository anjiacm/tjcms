<?php

/**
 * This is the model class for table "{{ph_application}}".
 *
 * The followings are the available columns in table '{{ph_application}}':
 * @property integer $id
 * @property string $title
 * @property integer $os_type
 * @property string $icon
 * @property string $animation
 * @property integer $sort_order
 * @property integer $status
 */
class PhApplication extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{ph_application}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('os_type, sort_order, status', 'numerical', 'integerOnly'=>true),
			array('title,sub_title', 'length', 'max'=>100),
			array('icon, animation,url', 'length', 'max'=>255),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, title, os_type, icon, animation,url, sort_order, status', 'safe', 'on'=>'search'),
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
			'title' => '应用名',
			'os_type' => '应用系统',
			'icon' => '图标',
			'animation' => '动图',
			'sort_order' => '排序',
			'status' => '状态',
			'url'=>'下载地址',
			'sub_title'=>'子应用名',
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
		$criteria->compare('title',$this->title,true);
		$criteria->compare('sub_title',$this->sub_title,true);
		$criteria->compare('os_type',$this->os_type);
		$criteria->compare('icon',$this->icon,true);
		$criteria->compare('animation',$this->animation,true);
		$criteria->compare('sort_order',$this->sort_order);
		$criteria->compare('status',$this->status);
		$criteria->compare('url',$this->url);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return PhApplication the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	public static function getOsTypeArray()
	{
		return array(1 => "安卓", 2 => "iOS");
	}

	public static function getAppList($os_type = '')
	{
		$model = new PhApplication();
		$cdb = new CDbCriteria();
		$cdb->compare('status',1);
		$cdb->compare('os_type',$os_type);
		$data =$model->findAll($cdb);
		$array = array();
		if($data){
			$array=Helper::objectToArray($data);
		}
		return $array;
	}

	public static function getAppSelectList($os_type = '')
	{
		$model = new PhApplication();
		$cdb = new CDbCriteria();
		$cdb->compare('status',1);
		$cdb->compare('os_type',$os_type);
		$data =$model->findAll($cdb);
		$array = array();
		if($data){
			foreach($data as $item){
				if($item->sub_title){
					$array[$item->id]=$item->title.'-'.$item->sub_title;
				}else{
					$array[$item->id]=$item->title;
				}

			}
		}
		return $array;
	}
}
