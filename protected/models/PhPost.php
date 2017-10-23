<?php

/**
 * This is the model class for table "{{ph_post}}".
 *
 * The followings are the available columns in table '{{ph_post}}':
 * @property integer $id
 * @property string $title
 * @property string $sub_title
 * @property integer $sort_order
 * @property integer $hits
 * @property string $img_h
 * @property string $img_v
 * @property string $img_l
 * @property string $img_s
 * @property string $status
 * @property string $copyfrom
 * @property integer $createtime
 * @property integer $updatetime
 */
class PhPost extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{ph_post}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('sort_order, hits, createtime, updatetime,status', 'numerical', 'integerOnly'=>true),
			array('title, sub_title', 'length', 'max'=>100),
			array('copyfrom', 'length', 'max'=>50),
			array('img_h, img_v, img_l, img_s', 'length', 'max'=>255),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, title, sub_title, sort_order, hits, img_h, img_v, img_l, img_s, createtime, updatetime', 'safe', 'on'=>'search'),
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
			'sort_order' => '排序',
			'hits' => '点击量',
			'img_h' => '横图',
			'img_v' => '竖图',
			'img_l' => '大图',
			'img_s' => '小图',
			'createtime' => '创建时间',
			'updatetime' => '更新时间',
			'status' => '状态',
			'copyfrom' => '来源',
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
		$criteria->compare('sort_order',$this->sort_order);
		$criteria->compare('hits',$this->hits);
		$criteria->compare('img_h',$this->img_h,true);
		$criteria->compare('img_v',$this->img_v,true);
		$criteria->compare('img_l',$this->img_l,true);
		$criteria->compare('img_s',$this->img_s,true);
		$criteria->compare('createtime',$this->createtime);
		$criteria->compare('updatetime',$this->updatetime);
		$criteria->compare('status',$this->status);
		$criteria->compare('copyfrom',$this->copyfrom);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return PhPost the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	public static function getPostList()
	{
		$model = new PhPost();
		$cdb = new CDbCriteria();
		$cdb->order = 'id DESC';
		$data = $model->findAll($cdb);
		$return = array();
		if($data){
			foreach($data as $item){
				$return[$item->id]=$item->title;
			}
		}
		return $return;
	}

	public static function getPostListForJs()
	{
		$model = new PhPost();
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
}
