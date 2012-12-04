<?php

/**
 * This is the model class for table "hotels".
 *
 * The followings are the available columns in table 'hotels':
 * @property string $id
 * @property string $name
 * @property string $img
 */
class Hotels extends CActiveRecord
{
    // Для чекбокса синхронизации с сайтом
    public $sinc;
	/**
	 * Returns the static model of the specified AR class.
	 * @return Hotels the static model class
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
		return 'hotels';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('name, id_cat', 'required'),
            array('full_desc, short_desc', 'safe'),
			array('name', 'length', 'max'=>255),
            array('bell,ring,quest,admin_message', 'length', 'max'=>1000),
            	array('cost,dirty,default_host,default_type,wifi', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id,dirty,bell,quest,admin_message,default_type,ring,wifi, name,default_host,id_cat,cost,square', 'safe', 'on'=>'search'),
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
           'with_order'=>array(self::HAS_ONE, 'HotelOrder', 'id_hotel'),
           'options'=>array(self::MANY_MANY, 'Option', 'hotel_option(hotel_id,option_id)', 'order'=>'options.id'),
		);
	}
    
    

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
			return array(
			'id' => 'ID',
			'name' => 'Адрес',		
            'id_cat' =>'Колличество комнат',
            'default_host' => 'Квартира привязана к',
            'cost' => 'Стоимость',
            'square' => 'Площадь',
            'bell' => 'Оповещение',
			'default_type' => 'Тип квартиры',		
            'quest'=>'Задачи для завхоза',
            'admin_message'=>'Сообщение от администратора',
            'wifi'=>'Wi-Fi',
            'sinc'=>'Синхронизировать с сайтом',
            'full_desc'=>'Описание',
            'short_desc'=>'Краткое описание',
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
		
		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
    
    public static function getItems()
    {
        $hotels = self::model()->findAll();
        foreach ($hotels as $hotel)
        {
            $items[$hotel->id] = $hotel->name;
        }
        return $items;
    }
    
    public static function getItem($id)
    {
        $hotel = self::model()->findByPk($id);
        return $hotel;
    }
    
    protected function afterDelete()
    {
        HotelOption::model()->deleteAll('hotel_id='.$this->id);
        
        if(Yii::app()->params['site_sinchronization'])
        {
            $siteHotel = SiteHotels::model()->findByAttributes(array('post_id'=>$this->id, 'post_type'=>'homecity'));
            if ($siteHotel)
                $siteHotel->delete();
        }
    }
    
    protected function afterSave($checker=0)
    {
        try
        {
            if (!$this->isNewRecord)
            {
                HotelOption::model()->deleteAll('hotel_id='.$this->id);
            }
            
            if (isset($_POST['Hotels']['options']))
            {
                foreach($_POST['Hotels']['options'] as $opt_id => $flag)
                {
                    $hotelOption = new HotelOption;
                    $hotelOption->hotel_id = $this->id;
                    $hotelOption->option_id = $opt_id;
                    $hotelOption->save(false);
                }
            }
            
            // Синхронизация с сайтом
            if ($this->sinc)
            {
                $siteHotel = SiteHotels::model()->findByAttributes(array('post_id'=>$this->id, 'post_type'=>'homecity'));
                if (!$siteHotel)
                {
                    $siteHotel = new SiteHotels;
                }
                
                $siteHotel->street = $this->name;
                $siteHotel->cost = $this->cost;
                $siteHotel->square = $this->square;
                $siteHotel->cat_id = $this->id_cat;
                $siteHotel->full_desc = $this->full_desc;
                $siteHotel->short_desc = $this->short_desc;
                $siteHotel->post_id = $this->id;
                $siteHotel->post_type = 'homecity';
                
                if (!$siteHotel->save(false))
                    throw new CHttpException(403, "Не могу сохранить на сайт");
                
                if (!$this->isNewRecord)
                {
                    SiteHotelsOptions::model()->deleteAll('hotel_id='.$siteHotel->id);
                }
                
                if (isset($_POST['Hotels']['options']))
                {
                    foreach($_POST['Hotels']['options'] as $opt_id => $flag)
                    {
                        Yii::app()->site_db->createCommand()->insert('tbl_hotels_options', array(
                            'hotel_id' => $siteHotel->id,
                            'option_id' => $opt_id,
                        ));
                    }
                }
            }
        }
        catch (Exception $e)
        {
            return false;
        }
    }
    
    
      public function updateByPk($pk,$attributes,$condition='',$params=array(),$checker = false,$id_order=false)
    {
           // возможно нужно будет поменять на хостинге на Asia/Yekaterinburg
        
        
        Yii::trace(get_class($this).'.updateByPk()','system.db.ar.CActiveRecord');
        $builder=$this->getCommandBuilder();
        $table=$this->getTableSchema();
        $criteria=$builder->createPkCriteria($table,$pk,$condition,$params);
        $command=$builder->createUpdateCommand($table,$attributes,$criteria);
        
        if(is_numeric($id_order) and $checker!=1)
        {
            $table_user = Hotels::model()->findByPk($pk);
    
             $data = array(
                 'id'=>$table_user->id
                 ,'name'=>$table_user->name
                 ,'id_cat'=>$table_user->id_cat
                 ,'cost'=>$table_user->cost
                 ,'default_type'=>$table_user->default_type
                 ,'dirty'=>$table_user->dirty
                 ,'default_host'=>$table_user->default_host
                 ,'bell'=>$table_user->bell
               
             );
            
            $id_user = Yii::app()->user->getId();
             $sid = Yii::app()->session->sessionID;                
            $id_action = 1;  //0 - создал, 1 - редактировал, 2 - удалил
            $sql_query = array('table'=>'hotels','data'=>$data);
            $short_desc = 'Редактирование квартиры';
            $change_time = date('Y-m-d H:i');
            SqlLogs::saveLOG($id_user,$sid,$id_action,$sql_query,$short_desc,$change_time,$checker,$id_order);
        
        }
            
        
        
        return $command->execute();
    }
    
    
        public function save($checker=0,$tt=false,$id_order = false,$runValidation=true,$attributes=null)
    {
    
        if(!$runValidation || $this->validate($attributes))
            return $this->getIsNewRecord() ? $this->insert($attributes,$checker,$tt,$id_order) : $this->update($attributes,$checker,$id_order);
        else
            return false;
    }
}