<?php

/**
 * This is the model class for table "{{wu_type}}".
 *
 * The followings are the available columns in table '{{wu_type}}':
 * @property integer $id
 * @property string $s_typename
 * @property integer $s_bigtypeid
 * @property string $s_typeimg
 * @property string $s_dodate
 * @property string $s_power
 * @property integer $s_order
 */
class WuType extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{wu_type}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('s_typename, s_bigtypeid, s_dodate', 'required'),
			array('s_bigtypeid, s_order', 'numerical', 'integerOnly'=>true),
			array('s_typename, s_typeimg, s_dodate, s_power', 'length', 'max'=>255),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, s_typename, s_bigtypeid, s_typeimg, s_dodate, s_power, s_order', 'safe', 'on'=>'search'),
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
            'stype' => array(self::BELONGS_TO, 'WuBigtype', 's_bigtypeid',),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => '序号',
			's_typename' => '类别名',
			's_bigtypeid' => '所属品类',
			's_typeimg' => '类型图标',
			's_dodate' => '上传时间',
			's_power' => '类型状态',
			's_order' => '类型序列',
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
		$criteria->compare('s_typename',$this->s_typename,true);
		$criteria->compare('s_bigtypeid',$this->s_bigtypeid);
		$criteria->compare('s_typeimg',$this->s_typeimg,true);
		$criteria->compare('s_dodate',$this->s_dodate,true);
		$criteria->compare('s_power',$this->s_power,true);
		$criteria->compare('s_order',$this->s_order);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return WuType the static model class
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
