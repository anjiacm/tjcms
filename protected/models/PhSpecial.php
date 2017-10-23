<?php

/**
 * This is the model class for table "{{ph_special}}".
 *
 * The followings are the available columns in table '{{ph_special}}':
 * @property integer $id
 * @property string $title
 * @property string $sub_title
 * @property string $description
 * @property integer $showstyle
 * @property string $cover_img
 * @property integer $createtime
 * @property integer $updatetime
 */
class PhSpecial extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{ph_special}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('showstyle, createtime, updatetime', 'numerical', 'integerOnly'=>true),
			array('title, sub_title', 'length', 'max'=>100),
			array('description, cover_img,postlist', 'length', 'max'=>255),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, title, sub_title, description, showstyle, cover_img, createtime, updatetime,postlist', 'safe', 'on'=>'search'),
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
			'title' => '标题',
			'sub_title' => '子标题',
			'description' => '简介',
			'showstyle' => '显示类型',
			'cover_img' => '封面',
			'createtime' => '创建时间',
			'updatetime' => '更新时间',
			'postlist' => '文章列表',
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
		$criteria->compare('description',$this->description,true);
		$criteria->compare('showstyle',$this->showstyle);
		$criteria->compare('cover_img',$this->cover_img,true);
		$criteria->compare('createtime',$this->createtime);
		$criteria->compare('updatetime',$this->updatetime);
		$criteria->compare('postlist',$this->postlist);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return PhSpecial the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	public static function getSpecialListForJs()
	{
		$model = new PhSpecial();
		$cdb = new CDbCriteria();
		$cdb->order = 'id DESC';
		$data = $model->findAll($cdb);
		$return = array();
		if($data){
			foreach($data as $item){
				$return[]=array(
					'id'=>$item->id,
					'title'=>$item->title,
				);
			}
		}
		return $return;
	}

	public static function getShowStyle()
	{
		$array = array(
			1=>'横图',
			2=>'竖图',
			3=>'大图',
			4=>'专集',
		);
		return $array;
	}
}
