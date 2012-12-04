<?php

/**
 * This is the model class for table "ticks".
 *
 * The followings are the available columns in table 'ticks':
 * @property string $id
 * @property integer $id_clienthotel
 * @property string $date_period_begin
 * @property string $date_period_finish
 * @property integer $status
 * @property integer $finish_sum
 * @property string $note
 */
class Ticks extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return Ticks the static model class
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
		return 'ticks';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('id_clienthotel, date_period_begin, date_period_finish', 'required'),
			array('id_clienthotel,id_informer,sum_for_days,sum_for_doc, status, finish_sum', 'numerical', 'integerOnly'=>true),
            array('note,date_public', 'length', 'max' => 1000),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id,date_public, id_clienthotel,id_informer,sum_for_days,sum_for_doc, date_period_begin, date_period_finish, status, finish_sum, note', 'safe', 'on'=>'search'),
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
			'id_clienthotel' => 'Id Clienthotel',
			'date_period_begin' => 'Выписка от',
			'date_period_finish' => 'Выписка до',
			'status' => 'Состояние',
			'finish_sum' => 'Общая сумма',
			'note' => 'Комментарий',
  	         'sum_for_doc' => 'Цена за документы',
			'sum_for_days' => 'Цена за проживание',
			'id_informer' => 'Кто забирает?',
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
		$criteria->compare('id_clienthotel',$this->id_clienthotel);
		$criteria->compare('date_period_begin',$this->date_period_begin,true);
		$criteria->compare('date_period_finish',$this->date_period_finish,true);
		$criteria->compare('status',$this->status);
		$criteria->compare('finish_sum',$this->finish_sum);
		$criteria->compare('note',$this->note,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
    
     protected function afterSave()
     {
        $date = ClientHotel::model()->findByPk($this->id_clienthotel);
        $log = Logs::model()->find(array('condition'=>"id_order={$date->id_order} and id_client={$date->id_client}"));
        $log->got_money_docs = $log->got_money_docs + $this->sum_for_doc;
        $log->got_money  = $log->got_money + $this->sum_for_days;
        $log->save();
     }
     
     
}