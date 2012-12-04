<?php

/**
 * This is the model class for table "hotel_option".
 *
 * The followings are the available columns in table 'hotel_option':
 * @property integer $id
 * @property integer $hotel_id
 * @property integer $option_id
 */
class HotelOption extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return HotelOption the static model class
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
		return 'hotel_option';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('hotel_id, option_id', 'required'),
			array('hotel_id, option_id', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, hotel_id, option_id', 'safe', 'on'=>'search'),
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
            'option'=>array(self::BELONGS_TO, 'Options', 'option_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'hotel_id' => 'Hotel',
			'option_id' => 'Option',
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

		$criteria->compare('id',$this->id);
		$criteria->compare('hotel_id',$this->hotel_id);
		$criteria->compare('option_id',$this->option_id);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}