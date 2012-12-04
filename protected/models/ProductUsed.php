<?php

/**
 * This is the model class for table "product_used".
 *
 * The followings are the available columns in table 'product_used':
 * @property string $id
 * @property integer $id_product
 * @property integer $status
 * @property integer $count_used
 * @property string $date_used
 */
class ProductUsed extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return ProductUsed the static model class
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
		return 'product_used';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('id_product, id_invite, status, price_for_sale, count_used, date_used', 'required'),
			array('id_product, id_invite, status, count_used', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, id_product, status, count_used, date_used', 'safe', 'on'=>'search'),
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
			'id_product' => 'Id Product',
			'status' => 'Состояние',
                        'id_invite'=>'Кто продал?',
			'count_used' => 'Штук продано',
			'date_used' => 'Дата реализации',
                    'price_for_sale'=>'Стоимость реализации за штуку',
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
		$criteria->compare('id_product',$this->id_product);
		$criteria->compare('status',$this->status);
		$criteria->compare('count_used',$this->count_used);
		$criteria->compare('date_used',$this->date_used,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
        
        protected function beforeSave()
     {
        $this->date_used = Reports::correctDatePublic($this->date_used);
       
        return true;
     }
}