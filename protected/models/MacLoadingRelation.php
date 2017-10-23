<?php

/**
 * This is the model class for table "mac_loading_relation".
 *
 * The followings are the available columns in table 'mac_loading_relation':
 * @property integer $r_id
 * @property integer $r_type
 * @property integer $r_a
 * @property integer $r_b
 */
class MacLoadingRelation extends CActiveRecord
{
    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'mac_loading_relation';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('r_type, r_a, r_b', 'numerical', 'integerOnly' => true),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('r_id, r_type, r_a, r_b', 'safe', 'on' => 'search'),
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
            'vod' => array(self::BELONGS_TO, 'MacVod', 'r_b'),
            'loading' => array(self::BELONGS_TO, 'MacLoading', 'r_a'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'r_id' => 'R',
            'r_type' => 'R Type',
            'r_a' => 'R A',
            'r_b' => 'R B',
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

        $criteria = new CDbCriteria;

        $criteria->compare('r_id', $this->r_id);
        $criteria->compare('r_type', $this->r_type);
        $criteria->compare('r_a', $this->r_a);
        $criteria->compare('r_b', $this->r_b);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    /**
     * @return CDbConnection the database connection used for this class
     */
    public function getDbConnection()
    {
        return Yii::app()->db_quekan;
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return MacLoadingRelation the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }


    public function getApiData($id)
    {
        if (empty($id)) {
            return false;
        }
        $key = 'loading_' . $id;
        $data = Yii::app()->cache->get($key);
        if (empty($data)) {
            $model = new MacLoadingRelation();
            $data = $model->setApiData($id);
        }
        return $data;
    }

    public function setApiData($id)
    {
        if (empty($id)) {
            return false;;
        }
        $model = new MacLoadingRelation();
        $cdb = new CDbCriteria();
        $cdb->compare('r_a', $id);
        $cdb->with = 'vod';
        $cdb->order = 'r_id DESC';
        $data = $model->findAll($cdb);
        if ($data) {
            $array = array();
            foreach ($data as $item) {
                $val = array();
                $val['title'] = $item->vod->d_name;
                $val['sub_title'] = $item->vod->d_subname;
                $val['pic'] = $item->vod->d_pic;
                $val['picthumb'] = $item->vod->d_picthumb;
                $val['hits'] = $item->vod->d_hits;
                $array[] = $val;
            }
            $array_json = CJSON::encode($array);
            Yii::app()->cache->set('loading_' . $id, $array_json);
            return $array_json;
        }
        return false;;
    }


}
