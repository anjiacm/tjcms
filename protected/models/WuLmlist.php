<?php

/**
 * This is the model class for table "{{wu_lmlist}}".
 *
 * The followings are the available columns in table '{{wu_lmlist}}':
 * @property integer $id
 * @property string $lmname
 * @property string $lm
 * @property integer $dodate
 * @property integer $power
 */
class WuLmlist extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{wu_lmlist}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('dodate, power', 'numerical', 'integerOnly'=>true),
			array('lmname, lm', 'length', 'max'=>255),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, lmname, lm, dodate, power', 'safe', 'on'=>'search'),
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
			'lmname' => 'Lmname',
			'lm' => 'Lm',
			'dodate' => 'Dodate',
			'power' => 'Power',
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
		$criteria->compare('lmname',$this->lmname,true);
		$criteria->compare('lm',$this->lm,true);
		$criteria->compare('dodate',$this->dodate);
		$criteria->compare('power',$this->power);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return WuLmlist the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	/*列出特殊联盟的数据*/
	public  static  function  lmlistall(){
	    $lmlist=array(
	        1=>'5293',
            2=>'爱联盟|AlM',
            3=>'墨禾|mh',
            4=>'聚诚',
            5=>'麦收',
        );
        return $lmlist;
    }
}
