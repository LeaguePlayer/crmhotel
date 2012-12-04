<?php

/**
 * This is the model class for table "logs".
 *
 * The followings are the available columns in table 'logs':
 * @property integer $id
 * @property integer $id_client
 * @property integer $id_order
 * @property integer $id_invite
 * @property integer $price_per_day
 * @property integer $got_money
 * @property string $date_stay_begin
 * @property string $date_stay_finish
 */
class Logs extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return Logs the static model class
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
		return 'logs';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('id_client, id_order, id_invite, price_per_day, got_money, date_stay_begin, date_stay_finish', 'required'),
			array('id_client,got_money_docs, id_order, id_invite, price_per_day, got_money', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id,got_money_docs, id_client, id_order, id_invite, price_per_day, got_money, date_stay_begin, date_stay_finish', 'safe', 'on'=>'search'),
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
			'id_client' => 'Id Client',
			'id_order' => 'Id Order',
			'id_invite' => 'Id Invite',
			'price_per_day' => 'Price Per Day',
			'got_money' => 'Got Money',
			'date_stay_begin' => 'Date Stay Begin',
			'date_stay_finish' => 'Date Stay Finish',
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
		$criteria->compare('id_client',$this->id_client);
		$criteria->compare('id_order',$this->id_order);
		$criteria->compare('id_invite',$this->id_invite);
		$criteria->compare('price_per_day',$this->price_per_day);
		$criteria->compare('got_money',$this->got_money);
		$criteria->compare('date_stay_begin',$this->date_stay_begin,true);
		$criteria->compare('date_stay_finish',$this->date_stay_finish,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}