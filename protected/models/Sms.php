<?php

/**
 * This is the model class for table "sms".
 *
 * The followings are the available columns in table 'sms':
 * @property string $id
 * @property string $phone
 * @property string $street
 * @property integer $city
 * @property string $date_public
 * @property integer $status
 */
class Sms extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return Sms the static model class
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
		return 'sms';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('phone, street, city, date_public, status', 'required'),
			array('city, status', 'numerical', 'integerOnly'=>true),
			array('phone,id_order', 'length', 'max'=>15),
			array('street', 'length', 'max'=>255),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, phone,id_order, street, city, date_public, status', 'safe', 'on'=>'search'),
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
			'phone' => 'Phone',
			'street' => 'Street',
			'city' => 'City',
			'date_public' => 'Date Public',
			'status' => 'Status',
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
		$criteria->compare('phone',$this->phone,true);
		$criteria->compare('street',$this->street,true);
		$criteria->compare('city',$this->city);
		$criteria->compare('date_public',$this->date_public,true);
		$criteria->compare('status',$this->status);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}