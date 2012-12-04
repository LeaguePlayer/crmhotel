<?php

/**
 * This is the model class for table "ticks".
 *
 * The followings are the available columns in table 'ticks':
 * @property string $id
 * @property integer $id_clienthotel
 * @property string $date_period_begin
 * @property string $date_period_finish
 * @property integer $status
 * @property integer $finish_sum
 * @property string $note
 * @property integer $id_informer
 * @property integer $sum_for_days
 * @property integer $sum_for_doc
 * @property string $date_public
 */
class Ticks extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return Ticks the static model class
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
		return 'ticks';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('id_clienthotel, id_invite, date_period_begin, date_period_finish, status, sum_for_days', 'required'),
			array('id_clienthotel, id_invite, status, finish_sum, id_informer, sum_for_days, sum_for_doc', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, id_clienthotel, date_period_begin, date_period_finish, status, finish_sum, note, id_informer, sum_for_days, sum_for_doc, date_public', 'safe', 'on'=>'search'),
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
                    'with_ch'=>array(self::HAS_ONE, 'ClientHotel', '','on'=>'t.id_clienthotel = with_ch.id','with'=>'with_ho'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'id_clienthotel' => 'Id Clienthotel',
			'date_period_begin' => 'Date Period Begin',
			'date_period_finish' => 'Date Period Finish',
			'status' => 'Status',
			'finish_sum' => 'Finish Sum',
			'note' => 'Note',
			'id_informer' => 'Id Informer',
			'sum_for_days' => 'Sum For Days',
			'sum_for_doc' => 'Sum For Doc',
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
		$criteria->compare('id_clienthotel',$this->id_clienthotel);
		$criteria->compare('date_period_begin',$this->date_period_begin,true);
		$criteria->compare('date_period_finish',$this->date_period_finish,true);
		$criteria->compare('status',$this->status);
		$criteria->compare('finish_sum',$this->finish_sum);
		$criteria->compare('note',$this->note,true);
		$criteria->compare('id_informer',$this->id_informer);
		$criteria->compare('sum_for_days',$this->sum_for_days);
		$criteria->compare('sum_for_doc',$this->sum_for_doc);
		$criteria->compare('date_public',$this->date_public,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
    
         public static function check($id_order,$minus_users = 0)
     {
        $cc = 0;
        $result = true;
        $clients =  ClientHotel::model()->findAll(array("condition"=>"id_order = {$id_order}"));
        foreach ($clients as $client)
        {
            $test = self::model()->count(array('condition'=>"(select date_period_begin from ticks where status=1 and id_clienthotel=$client->id order by date_period_begin ASC limit 1) = '$client->date_stay_begin' and (select date_period_finish from ticks where status=1 and id_clienthotel=$client->id order by date_period_finish DESC limit 1) = '$client->date_stay_finish'"));
            if($test==0) $cc++;
        }
        $cc = $cc - $minus_users;
        
        if($cc!=0) $result = false;
        return $result;
     }
     
     
        public function updateByPk($pk,$attributes,$condition='',$params=array(),$checker=1,$id_order)
    {
       // echo "checker = $checker and id_order =$id_order";die();
           // возможно нужно будет поменять на хостинге на Asia/Yekaterinburg
        
        
        Yii::trace(get_class($this).'.updateByPk()','system.db.ar.CActiveRecord');
        $builder=$this->getCommandBuilder();
        $table=$this->getTableSchema();
        $criteria=$builder->createPkCriteria($table,$pk,$condition,$params);
        $command=$builder->createUpdateCommand($table,$attributes,$criteria);
        if($checker!='vpizdy')
        {
            $table_user = Ticks::model()->findByPk($pk);
        
             $data = array(
                 'id'=>$table_user->id
                 ,'id_clienthotel'=>$table_user->id_clienthotel
                 ,'date_period_begin'=>$table_user->date_period_begin
                 ,'date_period_finish'=>$table_user->date_period_finish
                 ,'status'=>$table_user->status
                 ,'finish_sum'=>$table_user->finish_sum
                 ,'note'=>$table_user->note
                 ,'id_informer'=>$table_user->id_informer
                 ,'sum_for_days'=>$table_user->sum_for_days
                 ,'sum_for_doc'=>$table_user->sum_for_doc
                 ,'date_public'=>$table_user->date_public
             );
            //
            $id_user = Yii::app()->user->getId();
             $sid = Yii::app()->session->sessionID;                
            $id_action = 1; // 0 - создал, 1 - редактировал, 2 - удалил
            $sql_query = array('table'=>'ticks','data'=>$data);
            $short_desc = 'Редактирование счёта';
            $change_time = date('Y-m-d H:i');
            if(!is_numeric($checker)) $txt = $checker;
            else $txt='update';
            //$tmp_id = SqlLogs::model()->find("tmp_id={$table_user->id_clienthotel} and post_type='$txt'");       
            
            if($txt!='update')
                SqlLogs::saveLOG($id_user,$sid,$id_action,$sql_query,$short_desc,$change_time,$txt,$id_order);
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
    
    
    
     protected function afterSave($checker=0,$id_order=false)
     {
           // возможно нужно будет поменять на хостинге на Asia/Yekaterinburg
        if($this->isNewRecord)
        {
            
            // Формируем данные
            $data = array('id'=>$this->id);
            //
            $id_user = Yii::app()->user->getId();
             $sid = Yii::app()->session->sessionID;                
            $id_action = 0; // 0 - создал, 1 - редактировал, 2 - удалил
            $sql_query = array('table'=>'ticks','data'=>$data);
            //Формируем описание
             
             switch($this->status)
             {
                case '1':
                    $msg = 'Оплата счёта пользователя';  
                break;
                case '0':
                    $msg = 'Выписка счёта пользователю';  
                break;
             }
             $id_client= ClientHotel::model()->findByPk($this->id_clienthotel)->getAttribute('id_client');
             $msg.=' '.Clients::model()->findByPk($id_client)->getAttribute('name');
             
             $msg.= ' c '.date('d.m.Y H:i',strtotime($this->date_period_begin)).' по '.date('d.m.Y H:i',strtotime($this->date_period_finish));
                        
            $short_desc = $msg;
            
            //
            if(is_numeric($checker)) 
            {
                $txt = 'created';
              //  $tmp_id = SqlLogs::model()->find("tmp_id={$this->id_clienthotel} and post_type='$txt'");
            }
            else 
            {
                
                $txt = $checker;
              //  $tmp_id = SqlLogs::model()->find("tmp_id!=0 and post_type='$txt'");    
            }
            
                       
                                            
            
            $change_time = date('Y-m-d H:i');
            
          
            SqlLogs::saveLOG($id_user,$sid,$id_action,$sql_query,$short_desc,$change_time,$txt,$id_order);
        
        }
        
        
      //  $date = ClientHotel::model()->findByPk($this->id_clienthotel);
       // $log = Logs::model()->find(array('condition'=>"id_order={$date->id_order} and id_client={$date->id_client}"));
//        $log->got_money_docs = $log->got_money_docs + $this->sum_for_doc;
//        $log->got_money  = $log->got_money + $this->sum_for_days;
//        $log->save();
     }
     
     
     protected function beforeSave()
     {
      
        $this->date_public = Reports::correctDatePublic($this->date_public);
        
      
        return true;
     }
     
     
     public static function getMyPurse($id_clienthotel)
     {
        
     }
     
     
}