<?php

/**
 * This is the model class for table "visitors".
 *
 * The followings are the available columns in table 'visitors':
 * @property string $id
 * @property integer $id_place
 * @property integer $id_invite
 * @property string $date_stay_begin
 * @property string $date_stay_finish
 * @property integer $status
 */
class Visitors extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return Visitors the static model class
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
		return 'visitors';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('id_place, id_invite, date_stay_begin, date_stay_finish, status', 'required'),
			array('id_place, id_client, id_invite, status', 'numerical', 'integerOnly'=>true),
                    array('learnedby', 'length', 'max'=>255),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, id_place, learnedby, id_client, id_invite, date_stay_begin, date_stay_finish, status', 'safe', 'on'=>'search'),
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
            'cash'=>array(self::HAS_MANY, 'Cashbox', 'id_visitors'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'id_place' => 'ID_Места',
			'id_invite' => 'Кто заселил?',
			'date_stay_begin' => 'Дата начало проживания',
			'date_stay_finish' => 'Дата конец проживания',
			'status' => 'Статус',
                    'learnedby'=>'От куда узнали?'
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
		$criteria->compare('id_place',$this->id_place);
		$criteria->compare('id_invite',$this->id_invite);
		$criteria->compare('date_stay_begin',$this->date_stay_begin,true);
		$criteria->compare('date_stay_finish',$this->date_stay_finish,true);
		$criteria->compare('status',$this->status);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
    
    
    public static function checkOrder($sql_date_begin,$sql_date_finish,$id_place=1,$exist = false)
    {     
       if($exist) $addCondition = " and id!={$exist}";
        $freeslot = self::model()->count("( ('$sql_date_begin'<`t`.date_stay_begin and '$sql_date_finish'>=`t`.date_stay_finish) or ('$sql_date_finish'>`t`.date_stay_begin and '$sql_date_finish'<=`t`.date_stay_finish)  or ('$sql_date_begin'>`t`.date_stay_begin and '$sql_date_begin'<`t`.date_stay_finish) ) and id_place = {$id_place}{$addCondition}");
        
     
            if($freeslot==0) return true;
            else return false;
    }
    
    
    public static function calculation($date_begin,$time_begin,$n_hour)
    {
        $sum = 0;
        
        $n_hour_ex = explode(",",$n_hour);
        
        if(count($n_hour_ex)>1)
        {
            $n_hour = $n_hour_ex[0];
            $n_min = (!is_numeric($n_hour_ex[1]) ? 0 : $n_hour_ex[1]);
        }
        else $n_min = 0;
        
        if($n_min>60) return "ERROR: Более 60 минут нельзя";
        
        $date_begin = date('Y-m-d',strtotime($date_begin)).' '.$time_begin;
        $date_finish = date('Y-m-d H:i',strtotime("+{$n_hour} hour +{$n_min} min".$date_begin));
        
        $distance_days = fnc::intervalDays($date_begin,$date_finish);
        
        $raznica =  strtotime($date_finish) - strtotime($date_begin);
        
        $raznica = $raznica/3600;
       
        $graphic_work = fnc::loadGraphic();
      
     
        for($hour=1;$hour<=$raznica;$hour++)
        {
           
           $temp_date = (int)date('H',strtotime("+{$hour} hour".$date_begin));
           $sum += $graphic_work[$temp_date];
           
           if((int)$hour == (int)$raznica) 
           {   
              
                if ((int)$raznica != $raznica)
                {
                    $tmp_hour = $hour+1;
                    $temp_date = (int)date('H',strtotime("+{$tmp_hour} hour".$date_begin));
                    $sum_tmp = $graphic_work[$temp_date];
                    $sum += $sum_tmp/60*$n_min;
                }
           }
        }
        
        
        return $sum;
    }
    
    public static function getDolg($date_begin,$date_finish)
    {
        $sum = 0;
        $date_begin = date('Y-m-d H:i',strtotime($date_begin));
        $date_finish = date('Y-m-d H:i',strtotime($date_finish));
        
        $raznica =  strtotime($date_finish) - strtotime($date_begin);
        $raznica = ceil($raznica/3600);
      
        $graphic_work = fnc::loadGraphic();
     
        for($hour=1;$hour<=$raznica;$hour++)
        {
           $temp_date = (int)date('H',strtotime("+{$hour} hour".$date_begin));
           
           $sum += $graphic_work[$temp_date];
           
        }
       
        
        return $sum;
    }
    
    protected function afterSave()
    {
        if($this->isNewRecord)
        {
            $settings = Settings::model()->findByPk(1);
            $settings->sauna_last_update = time();
            $settings->save();
        }
        
        return true;
    }
    
    protected function beforeDelete()
    {
        Cashbox::model()->deleteAll("id_visitors='$this->id'");
        return true;
    }
    
    protected function afterDelete()
    {
        $settings = Settings::model()->findByPk(1);
        $settings->sauna_last_update = time();
        $settings->save();
        return true;
    }
    
}