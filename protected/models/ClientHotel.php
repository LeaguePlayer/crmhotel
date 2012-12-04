<?php

/**
 * This is the model class for table "client_hotel".
 *
 * The followings are the available columns in table 'client_hotel':
 * @property string $id
 * @property integer $id_client
 * @property integer $id_order
 * @property string $date_stay_begin
 * @property string $date_stay_finish
 * @property integer $status
 * @property string $from
 * @property integer $price_for
 */
class ClientHotel extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return ClientHotel the static model class
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
		return 'client_hotel';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('id_client, id_order, date_stay_begin, date_stay_finish', 'required'),
			array('id_client, id_order, finally, status, price_for, arrived', 'numerical', 'integerOnly'=>true),
			array('from', 'length', 'max'=>255),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, id_client, id_order, finally, date_stay_begin, date_stay_finish, status, from, price_for, arrived', 'safe', 'on'=>'search'),
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
                    'tickets'=>array(self::HAS_MANY, 'Ticks', 'id_clienthotel'),
                    'tickets_one'=>array(self::HAS_ONE, 'Ticks', 'id_clienthotel'),
                    'client'=>array(self::HAS_ONE, 'Clients', '', 'on'=>'t.id_client=client.id'),
                    'with_ho'=>array(self::HAS_ONE, 'HotelOrder', '', 'on'=>'with_ch.id_order=with_ho.id'),
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
			'date_stay_begin' => 'Date Stay Begin',
			'date_stay_finish' => 'Date Stay Finish',
			'status' => 'Status',
			'from' => 'From',
			'price_for' => 'Price For',
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
		$criteria->compare('id_client',$this->id_client);
		$criteria->compare('id_order',$this->id_order);
		$criteria->compare('date_stay_begin',$this->date_stay_begin,true);
		$criteria->compare('date_stay_finish',$this->date_stay_finish,true);
		$criteria->compare('status',$this->status);
		$criteria->compare('from',$this->from,true);
		$criteria->compare('price_for',$this->price_for);

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
            $sql_query = array('table'=>'client_hotel','data'=>$data);
             //Формируем описание
             
             $msg = 'Заселение';  
             
             $msg.=' '.Clients::model()->findByPk($this->id_client)->getAttribute('name');
             //sleep(2);
             $id_hotel = HotelOrder::model()->findByPk($this->id_order)->getAttribute('id_hotel');
             $msg.= ' в '.Hotels::model()->findByPk($id_hotel)->getAttribute('name');
             $msg.= ' c '.date('d.m.Y H:i',strtotime($this->date_stay_begin)).' по '.date('d.m.Y H:i',strtotime($this->date_stay_finish));
                        
            $short_desc = $msg;
           
            if(is_numeric($checker)) $txt = 'created';
            else $txt = $checker;
            
           
            //
            $change_time = date('Y-m-d H:i');
            SqlLogs::saveLOG($id_user,$sid,$id_action,$sql_query,$short_desc,$change_time,$txt,$this->id_order,$this->id);
        
        }       
        
    }
    
    
    public function updateByPk($pk,$attributes,$condition='',$params=array(),$checker=1,$txt='')
    {
        
           // возможно нужно будет поменять на хостинге на Asia/Yekaterinburg
        if($checker==1)
        {
            $table_user = ClientHotel::model()->findByPk($pk);
       
             $data = array('id'=>$table_user->id,'id_client'=>$table_user->id_client,'id_order'=>$table_user->id_order,'date_stay_begin'=>$table_user->date_stay_begin,'date_stay_finish'=>$table_user->date_stay_finish,'status'=>$table_user->status);
            //
          
            $id_user = Yii::app()->user->getId();
             $sid = Yii::app()->session->sessionID;                
            $id_action = 1; // 0 - создал, 1 - редактировал, 2 - удалил
            $sql_query = array('table'=>'client_hotel','data'=>$data);
            $short_desc = 'Редактирование заселения клиента #'.$table_user->id;
            $change_time = date('Y-m-d H:i');
            if($txt=='') $txt='update';
            
            if($txt!='update')
            SqlLogs::saveLOG($id_user,$sid,$id_action,$sql_query,$short_desc,$change_time,$txt,$table_user->id_order,$table_user->id);
        }
        
           
        
        Yii::trace(get_class($this).'.updateByPk()','system.db.ar.CActiveRecord');
        $builder=$this->getCommandBuilder();
        $table=$this->getTableSchema();
        $criteria=$builder->createPkCriteria($table,$pk,$condition,$params);
        $command=$builder->createUpdateCommand($table,$attributes,$criteria);
        
        
        
        return $command->execute();
    }
    
    
    public static function SyncSave($id_client,$id_Order,$date_begin,$date_finish,$cost,$status=1,$work = true)
    {
        echo "WORK<br />";
    }
    
    
    public function save($checker=0,$tt=false,$runValidation=true,$attributes=null)
    {
           
        if(!$runValidation || $this->validate($attributes))
            return $this->getIsNewRecord() ? $this->insert($attributes,$checker,$tt) : $this->update($attributes,$checker);
        else
            return false;
    }
    
    protected function beforeSave()
    {
        $this->date_stay_begin = date('Y-m-d H:i',strtotime($this->date_stay_begin));
        $this->date_stay_finish = date('Y-m-d H:i',strtotime($this->date_stay_finish));
        return true;
    }
}