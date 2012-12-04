<?php

/**
 * This is the model class for table "documents".
 *
 * The followings are the available columns in table 'documents':
 * @property string $id
 * @property integer $sum_docs
 * @property string $node
 * @property integer $id_invite
 * @property string $date_public
 */
class Documents extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return Documents the static model class
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
		return 'documents';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('id_invite, date_public', 'required'),
			array('status, id_clienthotel, id_invite', 'numerical', 'integerOnly'=>true),
                        array('post_type', 'length', 'max'=>255),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, id_invite, status, id_clienthotel, date_public, post_type', 'safe', 'on'=>'search'),
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
                        'prices'=>array(self::HAS_MANY, 'DocumentsPrice', 'id_document','order'=>'prices.date_edit DESC, prices.id DESC'),
                    'price'=>array(self::HAS_ONE, 'DocumentsPrice', 'id_document','order'=>'price.date_edit DESC, price.id DESC','joinType'=>'inner join'),
                    'cl'=>array(self::HAS_ONE, 'ClientHotel', '','on'=>'cl.id=t.id_clienthotel'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'price'=>'Стоимость за документы',
                    'node'=>'Комментарий',
                    
			'id_invite' => 'Кто выписал?',
			'date_public' => 'Дата выдачи',
                  
            'status' => 'Оплачено?',
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

		$criteria=new CDbCriteria(array('with'=>'price','params'=>array(':post_type'=>$_GET['type'])));
              //  $criteria->with('price');
                   $criteria->addCondition("post_type=:post_type");
		$criteria->compare('t.id',$this->id,true);
	
		$criteria->compare('id_invite',$this->id_invite);
		$criteria->compare('date_public',$this->date_public,true);
        
        $criteria->order = 't.id dESC';

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
    
    
    protected function afterSave($checker=0,$id_order = false)
    {      
        
        if($this->isNewRecord)
        {
            
            // Формируем данные
            $data = array('id'=>$this->id);
            //
            $id_user = Yii::app()->user->getId();
             $sid = Yii::app()->session->sessionID;                
            $id_action = 0; // 0 - создал, 1 - редактировал, 2 - удалил
            $sql_query = array('table'=>'documents','data'=>$data);
             //Формируем описание
             
             $short_desc = 'Выписка документа #'.$this->id;  
             
            
                        
            
           
            if(is_numeric($checker)) $txt = 'created';
            else $txt = $checker;
            
           
            //
            $change_time = date('Y-m-d H:i');
            SqlLogs::saveLOG($id_user,$sid,$id_action,$sql_query,$short_desc,$change_time,$txt,$id_order,$this->id);
        
        }       
        
    }
    
    
        public function save($checker=0,$tt=false,$id_order=false,$runValidation=true,$attributes=null)
    {
           
        if(!$runValidation || $this->validate($attributes))
            return $this->getIsNewRecord() ? $this->insert($attributes,$checker,$tt,$id_order) : $this->update($attributes,$checker);
        else
            return false;
    }
    
    
     protected function beforeSave()
     {
        $this->date_public = Reports::correctDatePublic($this->date_public);
       
        return true;
     }
    
    
}