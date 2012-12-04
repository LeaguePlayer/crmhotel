<?php

/**
 * This is the model class for table "alist".
 *
 * The followings are the available columns in table 'alist':
 * @property string $id
 * @property integer $id_user
 * @property string $post_type
 * @property string $short_desc
 * @property integer $status
 * @property integer $post_id
 * @property string $post_data
 */
class Alist extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return Alist the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'alist';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('id_user, post_type, short_desc, status, post_id, post_data', 'required'),
			array('id_user, status, post_id', 'numerical', 'integerOnly'=>true),
			array('post_type, short_desc', 'length', 'max'=>255),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, id_user, post_type, short_desc, status, post_id, post_data', 'safe', 'on'=>'search'),
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
			'id_user' => 'Id User',
			'post_type' => 'Post Type',
			'short_desc' => 'Short Desc',
			'status' => 'Status',
			'post_id' => 'Post',
			'post_data' => 'Post Data',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id,true);
		$criteria->compare('id_user',$this->id_user);
		$criteria->compare('post_type',$this->post_type,true);
		$criteria->compare('short_desc',$this->short_desc,true);
		$criteria->compare('status',$this->status);
		$criteria->compare('post_id',$this->post_id);
		$criteria->compare('post_data',$this->post_data,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}