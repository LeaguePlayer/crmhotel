<?php

/**
 * This is the model class for table "payments_order".
 *
 * The followings are the available columns in table 'payments_order':
 * @property string $id
 * @property integer $id_invite
 * @property integer $id_staff
 * @property double $price
 * @property string $date_public
 * @property integer $status
 */
class PaymentsOrder extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return PaymentsOrder the static model class
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
		return 'payments_order';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('id_invite, id_staff, price', 'required'),
			array('id_invite,credit_option, status, id_type, id_staff, status', 'numerical', 'integerOnly'=>true),
			array('price', 'numerical'),
                    array('date_public', 'length', 'max'=>255),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id,id_type,credit_option, id_invite, id_staff, price, date_public, status', 'safe', 'on'=>'search'),
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
        
        public static function getType($n=false)
        {
            $types = array('Зарплата','Кредит','Деньги под отчет');
            if(is_numeric($n))
                return $types[$n];
            else 
                return $types;
        }

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'id_invite' => 'Кто выдал',
			'id_staff' => 'Кому выдали',
			'price' => 'Сумма',
			'date_public' => 'Дата выдачи',
			'status' => 'Состояние',
                    'id_type'=>'Тип платежа',
                    'credit_option'=>'Погашение?',
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
		$criteria->compare('id_invite',$this->id_invite);
		$criteria->compare('id_staff',$this->id_staff);
		$criteria->compare('price',$this->price);
		$criteria->compare('date_public',$this->date_public,true);
		$criteria->compare('status',$this->status);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
    
    
    protected function beforeSave()
     {
        if($this->isNewRecord)
            $this->date_public = Reports::correctDatePublic(date('Y-m-d')).' '.date('H:i');
        
       
        return true;
     }
}