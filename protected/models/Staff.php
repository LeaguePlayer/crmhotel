<?php

/**
 * This is the model class for table "staff".
 *
 * The followings are the available columns in table 'staff':
 * @property string $id
 * @property string $name
 * @property integer $id_account
 */
class Staff extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return Staff the static model class
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
		return 'staff';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('name, id_account', 'required'),
			array('id_account', 'numerical', 'integerOnly'=>true),
			array('name', 'length', 'max'=>255),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, name, id_account', 'safe', 'on'=>'search'),
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
                    'account'=>array(self::HAS_ONE, 'Users', '', 'on'=>'t.id_account = account.id'),
                    'credit_history'=>array(self::HAS_MANY, 'PaymentsOrder', 'id_staff', 'order'=>'credit_history.date_public DESC, credit_history.id DESC','condition'=>'credit_history.id_type=1'),
                    'cashe_history'=>array(self::HAS_MANY, 'PaymentsOrder', 'id_staff', 'order'=>'cashe_history.date_public DESC, cashe_history.id DESC','condition'=>'cashe_history.id_type=0'),
                    'report_history'=>array(self::HAS_MANY, 'PaymentsOrder', 'id_staff', 'order'=>'report_history.date_public DESC, report_history.id DESC','condition'=>'report_history.id_type=2'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'name' => 'ФИО',
			'id_account' => 'Привязать аккаунт',
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
		$criteria->compare('name',$this->name,true);
		$criteria->compare('id_account',$this->id_account);
                
                

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria, 'pagination'=>array( 'pageSize'=>'100', )
		));
	}
    
    public function getAllUsers()
    {
        $objs = self::model()->findAll();        
        foreach ($objs as $obj)
        {
           
            $list[$obj->id] = $obj->name;
        }
        
        return $list;
    }
}