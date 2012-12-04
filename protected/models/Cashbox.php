<?php

/**
 * This is the model class for table "cashbox".
 *
 * The followings are the available columns in table 'cashbox':
 * @property string $id
 * @property integer $id_visitors
 * @property string $date_period_begin
 * @property string $date_period_finish
 * @property integer $status
 * @property integer $preceding_price
 * @property integer $prepay
 * @property integer $finish_sum
 * @property string $date_public
 */
class Cashbox extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return Cashbox the static model class
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
		return 'cashbox';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('id_visitors, date_period_begin, date_period_finish, status, preceding_price, prepay, finish_sum, date_public', 'required'),
			array('id_visitors, status, preceding_price, prepay, finish_sum', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, id_visitors, date_period_begin, date_period_finish, status, preceding_price, prepay, finish_sum, date_public', 'safe', 'on'=>'search'),
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
			'id_visitors' => 'Id Visitors',
			'date_period_begin' => 'Date Period Begin',
			'date_period_finish' => 'Date Period Finish',
			'status' => 'Status',
			'preceding_price' => 'Preceding Price',
			'prepay' => 'Prepay',
			'finish_sum' => 'Finish Sum',
			'date_public' => 'Date Public',
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
		$criteria->compare('id_visitors',$this->id_visitors);
		$criteria->compare('date_period_begin',$this->date_period_begin,true);
		$criteria->compare('date_period_finish',$this->date_period_finish,true);
		$criteria->compare('status',$this->status);
		$criteria->compare('preceding_price',$this->preceding_price);
		$criteria->compare('prepay',$this->prepay);
		$criteria->compare('finish_sum',$this->finish_sum);
		$criteria->compare('date_public',$this->date_public,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
    
    public static function checkUnpays($visitor)
    {
        
        $all_pay_ticks = self::model()->find(array('condition'=>"id_visitors = {$visitor->id} and status = 1",'select'=>"sum(finish_sum+prepay) as finish_sum"));
        
        $payed = (int)$all_pay_ticks->finish_sum;
        
        $dolg = (int)Cashbox::model()->find(array('condition'=>"id_visitors = {$visitor->id}",'select'=>"sum(preceding_price) as preceding_price"))->preceding_price;
        $result = ($dolg-$payed) * -1;
        
        return $result;
    }
    
     protected function beforeSave()
     {
        if($this->isNewRecord)
          $this->date_public = Reports::correctDatePublic($this->date_public);
        
      
        return true;
     }
}