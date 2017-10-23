<?php

/**
 * This is the model class for table "mac_vod".
 *
 * The followings are the available columns in table 'mac_vod':
 * @property string $d_id
 * @property string $d_name
 * @property string $d_subname
 * @property string $d_enname
 * @property string $d_letter
 * @property string $d_color
 * @property string $d_pic
 * @property string $d_picthumb
 * @property string $d_picslide
 * @property string $d_starring
 * @property string $d_directed
 * @property string $d_tag
 * @property string $d_remarks
 * @property string $d_area
 * @property string $d_lang
 * @property integer $d_year
 * @property integer $d_type
 * @property string $d_type_expand
 * @property string $d_class
 * @property string $d_topic
 * @property integer $d_hide
 * @property integer $d_lock
 * @property integer $d_state
 * @property integer $d_level
 * @property integer $d_usergroup
 * @property integer $d_stint
 * @property integer $d_stintdown
 * @property integer $d_hits
 * @property integer $d_dayhits
 * @property integer $d_weekhits
 * @property integer $d_monthhits
 * @property integer $d_duration
 * @property integer $d_up
 * @property integer $d_down
 * @property string $d_score
 * @property integer $d_scoreall
 * @property integer $d_scorenum
 * @property integer $d_addtime
 * @property integer $d_time
 * @property integer $d_hitstime
 * @property integer $d_maketime
 * @property string $d_content
 * @property string $d_playfrom
 * @property string $d_playserver
 * @property string $d_playnote
 * @property string $d_playurl
 * @property string $d_downfrom
 * @property string $d_downserver
 * @property string $d_downnote
 * @property string $d_downurl
 * @property string $d_publish
 * @property integer $d_doubanid
 * @property string $d_doubanscore
 * @property string $d_jiabin
 * @property string $d_prty
 * @property integer $d_editer
 * @property string $d_picback
 * @property string $d_des
 * @property string $d_prtyapp
 * @property string $d_mpic
 * @property string $d_minipic
 * @property string $d_updesc
 * @property string $d_mslidepic
 * @property integer $d_tpl
 * @property string $d_ban
 * @property string $d_brief
 */
class MacVod extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'mac_vod';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('d_name, d_subname, d_enname, d_letter, d_color, d_pic, d_picthumb, d_picslide, d_starring, d_directed, d_tag, d_remarks, d_area, d_lang, d_year, d_type_expand, d_class, d_lock, d_scoreall, d_addtime, d_time, d_hitstime, d_maketime, d_content, d_playfrom, d_playserver, d_playnote, d_playurl, d_downfrom, d_downserver, d_downnote, d_downurl', 'required'),
			array('d_year, d_type, d_hide, d_lock, d_state, d_level, d_usergroup, d_stint, d_stintdown, d_hits, d_dayhits, d_weekhits, d_monthhits, d_duration, d_up, d_down, d_scoreall, d_scorenum, d_addtime, d_time, d_hitstime, d_maketime, d_doubanid, d_editer, d_tpl', 'numerical', 'integerOnly'=>true),
			array('d_name, d_subname, d_enname, d_pic, d_picthumb, d_picslide, d_starring, d_directed, d_type_expand, d_class, d_topic, d_playfrom, d_playserver, d_playnote, d_downfrom, d_downserver, d_downnote, d_jiabin, d_picback, d_des, d_mpic, d_brief', 'length', 'max'=>255),
			array('d_letter', 'length', 'max'=>1),
			array('d_color', 'length', 'max'=>6),
			array('d_tag, d_remarks', 'length', 'max'=>64),
			array('d_area, d_lang', 'length', 'max'=>16),
			array('d_score', 'length', 'max'=>3),
			array('d_publish, d_doubanscore', 'length', 'max'=>100),
			array('d_prty, d_prtyapp, d_ban', 'length', 'max'=>50),
			array('d_minipic, d_updesc, d_mslidepic', 'length', 'max'=>120),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('d_id, d_name, d_subname, d_enname, d_letter, d_color, d_pic, d_picthumb, d_picslide, d_starring, d_directed, d_tag, d_remarks, d_area, d_lang, d_year, d_type, d_type_expand, d_class, d_topic, d_hide, d_lock, d_state, d_level, d_usergroup, d_stint, d_stintdown, d_hits, d_dayhits, d_weekhits, d_monthhits, d_duration, d_up, d_down, d_score, d_scoreall, d_scorenum, d_addtime, d_time, d_hitstime, d_maketime, d_content, d_playfrom, d_playserver, d_playnote, d_playurl, d_downfrom, d_downserver, d_downnote, d_downurl, d_publish, d_doubanid, d_doubanscore, d_jiabin, d_prty, d_editer, d_picback, d_des, d_prtyapp, d_mpic, d_minipic, d_updesc, d_mslidepic, d_tpl, d_ban, d_brief', 'safe', 'on'=>'search'),
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
			'd_id' => 'D',
			'd_name' => 'D Name',
			'd_subname' => 'D Subname',
			'd_enname' => 'D Enname',
			'd_letter' => 'D Letter',
			'd_color' => 'D Color',
			'd_pic' => 'D Pic',
			'd_picthumb' => 'D Picthumb',
			'd_picslide' => 'D Picslide',
			'd_starring' => 'D Starring',
			'd_directed' => 'D Directed',
			'd_tag' => 'D Tag',
			'd_remarks' => 'D Remarks',
			'd_area' => 'D Area',
			'd_lang' => 'D Lang',
			'd_year' => 'D Year',
			'd_type' => 'D Type',
			'd_type_expand' => 'D Type Expand',
			'd_class' => 'D Class',
			'd_topic' => 'D Topic',
			'd_hide' => 'D Hide',
			'd_lock' => 'D Lock',
			'd_state' => 'D State',
			'd_level' => 'D Level',
			'd_usergroup' => 'D Usergroup',
			'd_stint' => 'D Stint',
			'd_stintdown' => 'D Stintdown',
			'd_hits' => 'D Hits',
			'd_dayhits' => 'D Dayhits',
			'd_weekhits' => 'D Weekhits',
			'd_monthhits' => 'D Monthhits',
			'd_duration' => 'D Duration',
			'd_up' => 'D Up',
			'd_down' => 'D Down',
			'd_score' => 'D Score',
			'd_scoreall' => 'D Scoreall',
			'd_scorenum' => 'D Scorenum',
			'd_addtime' => 'D Addtime',
			'd_time' => 'D Time',
			'd_hitstime' => 'D Hitstime',
			'd_maketime' => 'D Maketime',
			'd_content' => 'D Content',
			'd_playfrom' => 'D Playfrom',
			'd_playserver' => 'D Playserver',
			'd_playnote' => 'D Playnote',
			'd_playurl' => 'D Playurl',
			'd_downfrom' => 'D Downfrom',
			'd_downserver' => 'D Downserver',
			'd_downnote' => 'D Downnote',
			'd_downurl' => 'D Downurl',
			'd_publish' => 'D Publish',
			'd_doubanid' => 'D Doubanid',
			'd_doubanscore' => 'D Doubanscore',
			'd_jiabin' => 'D Jiabin',
			'd_prty' => 'D Prty',
			'd_editer' => 'D Editer',
			'd_picback' => 'D Picback',
			'd_des' => 'D Des',
			'd_prtyapp' => 'D Prtyapp',
			'd_mpic' => 'D Mpic',
			'd_minipic' => 'D Minipic',
			'd_updesc' => 'D Updesc',
			'd_mslidepic' => 'D Mslidepic',
			'd_tpl' => 'D Tpl',
			'd_ban' => 'D Ban',
			'd_brief' => 'D Brief',
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

		$criteria->compare('d_id',$this->d_id,true);
		$criteria->compare('d_name',$this->d_name,true);
		$criteria->compare('d_subname',$this->d_subname,true);
		$criteria->compare('d_enname',$this->d_enname,true);
		$criteria->compare('d_letter',$this->d_letter,true);
		$criteria->compare('d_color',$this->d_color,true);
		$criteria->compare('d_pic',$this->d_pic,true);
		$criteria->compare('d_picthumb',$this->d_picthumb,true);
		$criteria->compare('d_picslide',$this->d_picslide,true);
		$criteria->compare('d_starring',$this->d_starring,true);
		$criteria->compare('d_directed',$this->d_directed,true);
		$criteria->compare('d_tag',$this->d_tag,true);
		$criteria->compare('d_remarks',$this->d_remarks,true);
		$criteria->compare('d_area',$this->d_area,true);
		$criteria->compare('d_lang',$this->d_lang,true);
		$criteria->compare('d_year',$this->d_year);
		$criteria->compare('d_type',$this->d_type);
		$criteria->compare('d_type_expand',$this->d_type_expand,true);
		$criteria->compare('d_class',$this->d_class,true);
		$criteria->compare('d_topic',$this->d_topic,true);
		$criteria->compare('d_hide',$this->d_hide);
		$criteria->compare('d_lock',$this->d_lock);
		$criteria->compare('d_state',$this->d_state);
		$criteria->compare('d_level',$this->d_level);
		$criteria->compare('d_usergroup',$this->d_usergroup);
		$criteria->compare('d_stint',$this->d_stint);
		$criteria->compare('d_stintdown',$this->d_stintdown);
		$criteria->compare('d_hits',$this->d_hits);
		$criteria->compare('d_dayhits',$this->d_dayhits);
		$criteria->compare('d_weekhits',$this->d_weekhits);
		$criteria->compare('d_monthhits',$this->d_monthhits);
		$criteria->compare('d_duration',$this->d_duration);
		$criteria->compare('d_up',$this->d_up);
		$criteria->compare('d_down',$this->d_down);
		$criteria->compare('d_score',$this->d_score,true);
		$criteria->compare('d_scoreall',$this->d_scoreall);
		$criteria->compare('d_scorenum',$this->d_scorenum);
		$criteria->compare('d_addtime',$this->d_addtime);
		$criteria->compare('d_time',$this->d_time);
		$criteria->compare('d_hitstime',$this->d_hitstime);
		$criteria->compare('d_maketime',$this->d_maketime);
		$criteria->compare('d_content',$this->d_content,true);
		$criteria->compare('d_playfrom',$this->d_playfrom,true);
		$criteria->compare('d_playserver',$this->d_playserver,true);
		$criteria->compare('d_playnote',$this->d_playnote,true);
		$criteria->compare('d_playurl',$this->d_playurl,true);
		$criteria->compare('d_downfrom',$this->d_downfrom,true);
		$criteria->compare('d_downserver',$this->d_downserver,true);
		$criteria->compare('d_downnote',$this->d_downnote,true);
		$criteria->compare('d_downurl',$this->d_downurl,true);
		$criteria->compare('d_publish',$this->d_publish,true);
		$criteria->compare('d_doubanid',$this->d_doubanid);
		$criteria->compare('d_doubanscore',$this->d_doubanscore,true);
		$criteria->compare('d_jiabin',$this->d_jiabin,true);
		$criteria->compare('d_prty',$this->d_prty,true);
		$criteria->compare('d_editer',$this->d_editer);
		$criteria->compare('d_picback',$this->d_picback,true);
		$criteria->compare('d_des',$this->d_des,true);
		$criteria->compare('d_prtyapp',$this->d_prtyapp,true);
		$criteria->compare('d_mpic',$this->d_mpic,true);
		$criteria->compare('d_minipic',$this->d_minipic,true);
		$criteria->compare('d_updesc',$this->d_updesc,true);
		$criteria->compare('d_mslidepic',$this->d_mslidepic,true);
		$criteria->compare('d_tpl',$this->d_tpl);
		$criteria->compare('d_ban',$this->d_ban,true);
		$criteria->compare('d_brief',$this->d_brief,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
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
	 * @return MacVod the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
