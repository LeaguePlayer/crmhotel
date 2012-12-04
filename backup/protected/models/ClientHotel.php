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
			array('id_client,status, id_order', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, id_client,status, id_order, date_stay_begin, date_stay_finish', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
	   $date = $_GET['date'];
        $tmn_date = date('Y-m-d',strtotime($date));
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
            'with_ticks' => array(self::HAS_MANY, 'Ticks', 'id_clienthotel','select'=>'(sum(sum_for_days)+sum(sum_for_doc)) as sum_for_days,date(`date_public`) as date_public','condition'=>"date(date_public)='$tmn_date'"),
            'with_ticks_public' => array(self::HAS_MANY, 'Ticks', 'id_clienthotel','select'=>'sum(finish_sum) as finish_sum, sum(sum_for_days) as sum_for_days,date(`date_public`) as date_public','condition'=>"date(date_public)='$tmn_date'"),
            
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

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
    
    public static function SyncSave($id_client,$id_Order,$date_begin,$date_finish,$status=1)
    {
        $cnt_current = ClientHotel::model()->count(array('condition'=>"id_client=$id_client and id_order=$id_Order"));
        if($cnt_current<1){
           // возможно нужно будет поменять на хостинге на Asia/Yekaterinburg
            $client_hotel = new ClientHotel;
            $client_hotel->id_client = $id_client;
            $client_hotel->id_order = $id_Order;
            $client_hotel->date_stay_begin = $date_begin;
            $client_hotel->date_stay_finish = $date_finish;
          
            $client_hotel->save();  
            if($status==0)
            {
                $order = HotelOrder::model()->findByPk($id_Order);
                $day_intervals = fnc::intervalDays($date_begin,$date_finish);
                $sum_for_days = $order->price_per_day*$day_intervals;
                $new_tick = new Ticks;
                $new_tick->id_clienthotel = $client_hotel->id;
                $new_tick->date_period_begin = $date_begin;
                $new_tick->date_period_finish=$date_finish;
                $new_tick->status=1;
                $new_tick->finish_sum=$sum_for_days;
                $new_tick->sum_for_days=$sum_for_days;
                $new_tick->sum_for_doc=0;
                $new_tick->date_public=date('Y-m-d H:i');
                $new_tick->save();
            }
          }
    }
    
    protected function afterSave()
    {
      
        $was_log = Logs::model()->find(array('condition'=>"id_client={$this->id_client} and id_order = {$this->id_order}",'order'=>'id desc'));
   
        if(count($was_log)>0)
        {
            $was_log->date_stay_finish = $this->date_stay_begin;
            $was_log->save();
        }
        $ORDER = HotelOrder::model()->findByPk($this->id_order);        
        $log = new Logs;
        $log->id_client = $this->id_client;
        $log->id_order = $this->id_order;
        $log->id_invite = $ORDER->id_invite;
        $log->price_per_day = $ORDER->price_per_day;
        $log->got_money = 0;
        $log->got_money_docs = 0;
        $log->date_stay_begin = $this->date_stay_begin;
        $log->date_stay_finish = $this->date_stay_finish;
        $log->save();
    }
    

    
    
    
    
}