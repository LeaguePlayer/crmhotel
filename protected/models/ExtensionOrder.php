<?php

/**
 * This is the model class for table "extension_order".
 *
 * The followings are the available columns in table 'extension_order':
 * @property string $id
 * @property integer $id_order
 * @property string $date_public
 */
class ExtensionOrder extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return ExtensionOrder the static model class
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
		return 'extension_order';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('id_order, date_public', 'required'),
			array('id_order', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, id_order, date_public', 'safe', 'on'=>'search'),
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
			'id_order' => 'Id Order',
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
		$criteria->compare('id_order',$this->id_order);
		$criteria->compare('date_public',$this->date_public,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
    
    public function save($checker=0,$tt=false,$runValidation=true,$attributes=null)
    {
        
        if(!$runValidation || $this->validate($attributes))
            return $this->getIsNewRecord() ? $this->insert($attributes,$checker,$tt) : $this->update($attributes,$checker);
        else
            return false;
    }
    
    protected function afterSave($checker=0)
    {
        $log_one = SqlLogs::model()->find(array('condition'=>"post_type='$checker'"));  
        // Формируем данные
        $data = array('id'=>$this->id);        
        $id_user = Yii::app()->user->getId();
        $sid = Yii::app()->session->sessionID;                
        $id_action = 0; // 0 - создал, 1 - редактировал, 2 - удалил
        $sql_query = array('table'=>'extension_order','data'=>$data);
        //Формируем описание
        $short_desc = 'Полоса деления #'.$this->id;        
        $change_time = date('Y-m-d H:i');    
        SqlLogs::saveLOG($id_user,$sid,$id_action,$sql_query,$short_desc,$change_time,$checker,$log_one->post_id);
    }
    
    
}