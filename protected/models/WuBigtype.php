<?php

/**
 * This is the model class for table "{{wu_bigtype}}".
 *
 * The followings are the available columns in table '{{wu_bigtype}}':
 * @property integer $id
 * @property string $typename
 * @property string $dodate
 * @property integer $power
 */
class WuBigtype extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{wu_bigtype}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('typename, dodate', 'required'),
			array('power', 'numerical', 'integerOnly'=>true),
			array('typename, dodate', 'length', 'max'=>255),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, typename, dodate, power', 'safe', 'on'=>'search'),
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
            'stype' => array(self::BELONGS_TO, 'WuType', 'id',),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => '序号',
			'typename' => '类别名',
			'dodate' => '添加时间',
			'power' => '状态',
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
		$criteria->compare('typename',$this->typename,true);
		$criteria->compare('dodate',$this->dodate,true);
		$criteria->compare('power',$this->power);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return WuBigtype the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	public static function getList()
    {
        $result = array();
        $model = new WuBigtype();
        $data  = $model->findAll();
        if($data){
            foreach ($data as $item){
                $result[$item['id']]=$item['typename'];
            }
        }
        return $result;
    }
}
