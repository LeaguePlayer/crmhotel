<?php

/**
 * This is the model class for table "hotel_order".
 *
 * The followings are the available columns in table 'hotel_order':
 * @property string $id
 * @property string $id_hotel
 * @property integer $status
 * @property string $date_stay_begin
 * @property string $date_stay_finish
 * @property string $price_per_day
 * @property string $places
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
			array('id_hotel,id_invite, status, date_stay_begin, date_stay_finish, price_per_day, places', 'required'),
			array('status, id_invite,create_time', 'numerical', 'integerOnly'=>true),
			array('id_hotel, price_per_day, places', 'length', 'max'=>10),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, id_hotel,id_invite, status,create_time, date_stay_begin, date_stay_finish, price_per_day, places', 'safe', 'on'=>'search'),
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
            'with_client' => array(self::HAS_MANY, 'ClientHotel', 'id_order'),
           
            
                            //    'with'=>array('with_ticks'=>array('select'=>'sum(sum_for_days) as sum_for_days1')),
//                                // но нужно выбрать только пользователей с опубликованными записями
//                              //  'joinType'=>'left JOIN',
//                                'condition'=>"date(date_public)='$tmn_date'",
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'id_hotel' => 'Квартира',
			'status' => 'Состояние',
			'date_stay_begin' => 'Дата начала проживания',
			'date_stay_finish' => 'Дата конца проживания',
			'price_per_day' => 'Стоимость',
			'places' => 'Колличество мест',
            'id_invite'=>'Кто заселил',
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
		$criteria->compare('id_hotel',$this->id_hotel,true);
		$criteria->compare('status',$this->status);
		$criteria->compare('date_stay_begin',$this->date_stay_begin,true);
		$criteria->compare('date_stay_finish',$this->date_stay_finish,true);
		$criteria->compare('price_per_day',$this->price_per_day,true);
		$criteria->compare('places',$this->places,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
    
    protected function afterSave()
    {
 
     
        self::SyncAfterSaveUsers($_POST['users'],$this->id,$this->date_stay_begin,$this->date_stay_finish,$this->status);
        
            
    }
    
    public static function SyncAfterSaveUsers($array,$id_order,$date_begin,$date_finish,$status=1)
    {
        if(isset($array)) 
        {           
        
            foreach($array as $user)
            {    
                if($user['id']!='')
                {
                    
                    ClientHotel::SyncSave($user['id'],$id_order,$date_begin,$date_finish,$status);     
                }
                else
                {
                    $newUser=false;
                    foreach ($user['phone'] as $phone)
                    {
                        if($phone!='') $newUser=true;
                    }
                    if($newUser){
                            $client = new Clients;
                            $client->name = $user['name'];
                          //  $client->phone = ;
                            $client->save();       
                            
                            foreach ($user['phone'] as $phone)
                            {
                                $new_phone = new Phones;
                                $new_phone->id_client = $client->id;
                                $new_phone->phone = $phone;
                                $new_phone->save();
                            } 
                               
                        ClientHotel::SyncSave($client->id,$id_order,$date_begin,$date_finish,$status);          
                        }
                }
                    
                       
            }
        }
    }
    
    protected function beforeDelete()
    {
        $time = time();
        ClientHotel::model()->deleteAll("id_order={$this->id}");
        HotelOrder::model()->updateAll(array('create_time'=>$time));
        HotelOrder::model()->deleteByPk($this->id);
    }
    
    protected function afterDelete()
    {
       
    }
    
}