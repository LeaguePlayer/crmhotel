<?php

/**
 * This is the model class for table "hotel_order".
 *
 * The followings are the available columns in table 'hotel_order':
 * @property string $id
 * @property integer $tmp_halfmoney
 * @property string $tmp_halfdate
 * @property string $id_hotel
 * @property integer $status
 * @property string $date_stay_begin
 * @property string $date_stay_finish
 * @property string $price_per_day
 * @property string $places
 * @property integer $id_invite
 * @property integer $create_time
 * @property string $remember_time
 * @property string $broken_begin
 * @property string $broken_finish
 * @property integer $ring
 * @property integer $TYC
 */
class HotelOrder extends CActiveRecord
{
    
    
	/**
	 * Returns the static model of the specified AR class.
	 * @return HotelOrder the static model class
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
		return 'hotel_order';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('id_invite', 'required'),
			array('tmp_halfmoney, status, id_invite, create_time, ring, TYC', 'numerical', 'integerOnly'=>true),
			array('id_hotel,tmp_halfdate, date_cleaning, places,date_stay_finish', 'length', 'max'=>50),
            array('uncurrect', 'length', 'max'=>1000),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, tmp_halfmoney, tmp_halfdate, id_hotel, uncurrect, status, date_stay_begin, date_stay_finish,  places, id_invite, create_time, remember_time, broken_begin, broken_finish, ring, TYC', 'safe', 'on'=>'search'),
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
             'cl'=>array(self::HAS_MANY, 'ClientHotel', 'id_order','with'=>'tickets'),
             'cl_s'=>array(self::HAS_MANY, 'ClientHotel', 'id_order','condition'=>'`cl_s`.status=0'),
             
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'tmp_halfmoney' => 'Tmp Halfmoney',
			'tmp_halfdate' => 'Tmp Halfdate',
			'id_hotel' => 'Id Hotel',
			'status' => 'Status',
			'date_stay_begin' => 'Дата начала проживания',
			'date_stay_finish' => 'Date Stay Finish',
		 
			'places' => 'Места проживания',
			'id_invite' => 'Кто заселил?',
			'create_time' => 'Create Time',
			'remember_time' => 'Remember Time',
			'broken_begin' => 'Broken Begin',
			'broken_finish' => 'Broken Finish',
			'ring' => 'Ring',
			'TYC' => 'Tyc',
                        'date_cleaning'=>'Дата следующей уборки',
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
		$criteria->compare('tmp_halfmoney',$this->tmp_halfmoney);
		$criteria->compare('tmp_halfdate',$this->tmp_halfdate,true);
		$criteria->compare('id_hotel',$this->id_hotel,true);
		$criteria->compare('status',$this->status);
		$criteria->compare('date_stay_begin',$this->date_stay_begin,true);
		$criteria->compare('date_stay_finish',$this->date_stay_finish,true);
	
		$criteria->compare('places',$this->places,true);
		$criteria->compare('id_invite',$this->id_invite);
		$criteria->compare('create_time',$this->create_time);
		$criteria->compare('remember_time',$this->remember_time,true);
		$criteria->compare('broken_begin',$this->broken_begin,true);
		$criteria->compare('broken_finish',$this->broken_finish,true);
		$criteria->compare('ring',$this->ring);
		$criteria->compare('TYC',$this->TYC);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
    
    protected function afterSave($checker=0)
    {
           // возможно нужно будет поменять на хостинге на Asia/Yekaterinburg
        if($this->isNewRecord)
        {            
            // Формируем данные
            $data = array('id'=>$this->id);            //
            $id_user = Yii::app()->user->getId();
             $sid = Yii::app()->session->sessionID;                
            $id_action = 0; // 0 - создал, 1 - редактировал, 2 - удалил
            $sql_query = array('table'=>'hotel_order','data'=>$data);
            //Формируем описание            
             $msg = fnc::getStatus($this->status);  
             $msg.= ' '.Hotels::model()->findByPk($this->id_hotel)->getAttribute('name');
             $msg.= ' c '.date('d.m.Y H:i',strtotime($this->date_stay_begin)).' по '.date('d.m.Y H:i',strtotime($this->date_stay_finish));                        
            $short_desc = $msg;            
            //
            $change_time = date('Y-m-d H:i');
            SqlLogs::saveLOG($id_user,$sid,$id_action,$sql_query,$short_desc,$change_time,'created',$this->id,$this->id);   
        }   
    }
    
    public static function checkOrder($sql_date_begin,$sql_date_finish,$id_hotel)
    {
      
        
 
        $freeslot = self::model()->count("( ('$sql_date_begin'<`t`.date_stay_begin and '$sql_date_finish'>=`t`.date_stay_finish) or ('$sql_date_finish'>`t`.date_stay_begin and '$sql_date_finish'<=`t`.date_stay_finish)  or ('$sql_date_begin'>`t`.date_stay_begin and '$sql_date_begin'<`t`.date_stay_finish) ) and id_hotel = {$id_hotel}{$addCondition}");
       
            if($freeslot==0) return true;
            else return false;
    }
    
    public static function updateTime()
    {
        $time=time();
        self::model()->updateAll(array('create_time'=>$time));
    }
    
    
    
    public function updateByPk($pk,$attributes,$condition='',$params=array(),$checker=1,$txt = '')
    {
     
           // возможно нужно будет поменять на хостинге на Asia/Yekaterinburg
        
        
        Yii::trace(get_class($this).'.updateByPk()','system.db.ar.CActiveRecord');
        $builder=$this->getCommandBuilder();
        $table=$this->getTableSchema();
        $criteria=$builder->createPkCriteria($table,$pk,$condition,$params);
        $command=$builder->createUpdateCommand($table,$attributes,$criteria);
        if($checker==1)
        {
            $table_use = HotelOrder::model()->findByPk($pk);
        
             $data = array('id'=>$table_use->id,'id_hotel'=>$table_use->id_hotel,'status'=>$table_use->status,'date_stay_begin'=>$table_use->date_stay_begin,'date_stay_finish'=>$table_use->date_stay_finish,'places'=>$table_use->places,'id_invite'=>$table_use->id_invite,'create_time'=>$table_use->create_time,'remember_time'=>$table_use->remember_time,'broken_begin'=>$table_use->broken_begin,'broken_finish'=>$table_use->broken_finish);
            
            $id_user = Yii::app()->user->getId();
             $sid = Yii::app()->session->sessionID;                
            $id_action = 1; // 0 - создал, 1 - редактировал, 2 - удалил
            $sql_query = array('table'=>'hotel_order','data'=>$data);
            $short_desc = 'Редактирование заказа #'.$table_use->id;
            $change_time = date('Y-m-d H:i');
            if($txt=='') $txt='update';
            
            if($txt!='update')
            SqlLogs::saveLOG($id_user,$sid,$id_action,$sql_query,$short_desc,$change_time,$txt,$table_use->id,$table_use->id);
        }
            
        
        return $command->execute();
    }
    
    
    public function save($checker=0,$tt=false,$runValidation=true,$attributes=null)
    {
    
        if(!$runValidation || $this->validate($attributes))
            return $this->getIsNewRecord() ? $this->insert($attributes,$checker,$tt) : $this->update($attributes,$checker);
        else
            return false;
    }
    
     protected function beforeDelete()
    {
        $time = time();
        ClientHotel::model()->deleteAll("id_order={$this->id}");
        HotelOrder::model()->updateAll(array('create_time'=>$time));
        HotelOrder::model()->deleteByPk($this->id);
    }
    
    public function checkFreeHome($id_order)
    {
        $obj = self::model()->with('cl_s')->findByPk($id_order);
        if(count($obj->cl)>0)
            return true;
        else return false;
    }
    
    
    public function checkUncurrectMoney($id_invite,$date)
    {
        $date = date('Y-m-d',strtotime($date));
        
        $model =  self::model()->with(array('cl'=>array('with'=>array('tickets'=>array('condition'=>"'$date'=date(date_public) and `tickets`.status = 1",'group'=>"id_clienthotel",'select'=>"sum(finish_sum) as finish_sum")))))->count(array('condition'=>"`t`.id_invite = $id_invite and uncurrect != ''"));
        
                if($model==0) return false;
        else return true;
    }
    
    protected function beforeSave()
    {
        if($this->isNewRecord)
            $this->date_cleaning = date('Y-m-d',strtotime("+3 days ".$this->date_stay_begin));
        return true;
    }
    
}