<?php

/**
 * This is the model class for table "documents_price".
 *
 * The followings are the available columns in table 'documents_price':
 * @property string $id
 * @property integer $id_document
 * @property string $node
 * @property integer $price
 */
class DocumentsPrice extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return DocumentsPrice the static model class
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
		return 'documents_price';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('id_document, price, node', 'required'),
			array('id_document, price', 'numerical', 'integerOnly'=>true),
                        array('node, date_edit', 'length', 'max'=>255),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, id_document, node, price', 'safe', 'on'=>'search'),
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
			'id_document' => 'Id Document',
			'node' => 'Комментарий',
			'price' => 'Сумма за документы',
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
		$criteria->compare('id_document',$this->id_document);
		$criteria->compare('node',$this->node,true);
		$criteria->compare('price',$this->price);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
        
        protected  function beforeSave()
        {
            $this->date_edit = date('Y-m-d H:i');
            return true;
        }
        
}