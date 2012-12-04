<?php

/**
 * This is the model class for table "products".
 *
 * The followings are the available columns in table 'products':
 * @property string $id
 * @property string $title
 * @property double $purchase_price
 * @property double $sales_price
 * @property integer $brought_cnt
 * @property string $date_delivery
 */
class Products extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return Products the static model class
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
		return 'products';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('title,  purchase_price, sales_price, brought_cnt, date_delivery', 'required'),
			array('brought_cnt', 'numerical', 'integerOnly'=>true,'min'=>1),
			array('purchase_price,id_unit, sales_price', 'numerical'),
			array('title', 'length', 'max'=>255),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, title, purchase_price, sales_price, brought_cnt, date_delivery', 'safe', 'on'=>'search'),
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
			'title' => 'Название',
			'purchase_price' => 'Закупочная цена за 1 ед.',
			'sales_price' => 'Розничная цена за 1 ед.',
			'brought_cnt' => 'Поставлено штук',
			'date_delivery' => 'Дата поставки',
                        'id_unit'=>'Единица измерения',
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
		$criteria->compare('title',$this->title,true);
		$criteria->compare('purchase_price',$this->purchase_price);
		$criteria->compare('sales_price',$this->sales_price);
		$criteria->compare('brought_cnt',$this->brought_cnt);
		$criteria->compare('date_delivery',$this->date_delivery,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
                        'sort' => array('defaultOrder' => 'date_delivery DESC')
		));
	}
        
        public static function getActualStatus($n=false)
        {
            $array = array(0=>'Продан',1=>'Списан');
            if(is_numeric($n))
                return $array[$n];
            else return $array;
        }
        
        public static function getActualBalance($id_product)
        {
            $find_product = Products::model()->findByPk($id_product);
            if(is_object($find_product))
            {
                $products_used = ProductUsed::model()->find(array('condition'=>"id_product = :id_product",'select'=>'id, sum(count_used) as count_used','group'=>'id_product','params'=>array(':id_product'=>$id_product)));
                $products_used->count_used = (is_numeric($products_used->count_used) ? $products_used->count_used : 0);
                $result = $find_product->brought_cnt - $products_used->count_used;
                return $result;
            }
        }
        
        public static function getUnit($n=false)
        {
            $result = array('штук','грамм');
            if(is_numeric($n))
                return $result[$n];
            else 
                return $result;
            
        }
}