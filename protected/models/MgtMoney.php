<?php

/**
 * This is the model class for table "mgt_money".
 *
 * The followings are the available columns in table 'mgt_money':
 * @property string $id
 * @property integer $id_clienthotel
 * @property integer $cost
 * @property string $date_public
 */
class MgtMoney extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return MgtMoney the static model class
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
		return 'mgt_money';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('id_clienthotel, cost, date_public', 'required'),
			array('id_clienthotel, cost', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, id_clienthotel, cost, date_public', 'safe', 'on'=>'search'),
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
			'id_clienthotel' => 'Id Clienthotel',
			'cost' => 'Cost',
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
		$criteria->compare('cost',$this->cost);
		$criteria->compare('date_public',$this->date_public,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
    
    
   public static function getScore($id,$date_begin=false)
   {
    
       $period = ClientHotel::model()->findByPk($id);
       $n=1;
       
       $exmodel = self::model()->count(array("condition"=>"'$period->date_stay_begin'=`t`.date_public and `t`.id_clienthotel=$id",'group'=>'date_public'));

       if($exmodel==0)
        $model = self::model()->findAll(array("condition"=>"'$period->date_stay_begin'<=`t`.date_public and date(`t`.date_public)<=date('$period->date_stay_finish') and `t`.id_clienthotel=$id and `t`.id = (select `m`.id from `mgt_money` `m` where `m`.cost <> `t`.cost and `m`.id_clienthotel=$id limit 1) or `t`.id = (select max(id) from `mgt_money` `m` where date(`m`.date_public) < date('$period->date_stay_begin') and `m`.id_clienthotel=$id)",'group'=>'date_public'));
      
       if(count($model)==0)
        $model = self::model()->findAll(array("condition"=>"'$period->date_stay_begin'<=`t`.date_public and date(`t`.date_public)<date('$period->date_stay_finish') and `t`.id_clienthotel=$id and `t`.id = (select max(id) from `mgt_money` `m` where date(`m`.date_public) = date(`t`.date_public) and `m`.id_clienthotel=$id)",'group'=>'date_public'));
        // echo count($model);die();
    
       if(count($model)==0)
        $model = self::model()->findAll(array("condition"=>"'$period->date_stay_begin'>`t`.date_public and `t`.id_clienthotel=$id and `t`.id = (select max(id) from `mgt_money` `m` where date(`m`.date_public) = date(`t`.date_public) and `m`.id_clienthotel=$id)",'group'=>'date_public'));
        
      $all = count($model);
   

        
       if(count($model)>0)
       {
           foreach ($model as $m)
           {
        
                if($n!=1) $result[$n-1]['finish_date'] = $m->date_public;
             
                $result[$n]['cost'] = $m->cost;
                
                if($n==1)
                $result[$n]['begin_date'] = ($date_begin ? $date_begin : $period->date_stay_begin);
                else 
                $result[$n]['begin_date'] = ($date_begin ? $date_begin : $m->date_public);
                            
                if($all == $n) $result[$n]['finish_date'] = $period->date_stay_finish;
                $n++;
           }
          //  fnc::mpr($result);
       
           foreach ($result as $s)
           {
              $sum += fnc::intervalDays($s['begin_date'],$s['finish_date'])*$s['cost'];
           }
          // echo fnc::intervalDays($s['begin_date'],$s['finish_date']);
           if(!$date_begin)
           $minus = Ticks::model()->find(array('condition'=>"id_clienthotel=$id and status = 1",'select'=>"sum(finish_sum) as finish_sum"));
           $plus = Ticks::model()->find(array('condition'=>"id_clienthotel=$id and status = 0",'select'=>"sum(sum_for_doc) as sum_for_doc"));

           $sum = (int)$sum - (int)$minus->finish_sum + (int)$plus->sum_for_doc;
           
       }
       else $sum = 0;
      
   //  echo $sum;
       return $sum;
   }
   
       public static function getScoreFixed($id,$date_begin=false,$date_finish=false)
   {
    
       $period = ClientHotel::model()->findByPk($id);
       $n=1;

       $model = self::model()->findAll(array("condition"=>"'$period->date_stay_begin'<=`t`.date_public and date(`t`.date_public)<=date('$period->date_stay_finish') and `t`.id_clienthotel=$id and `t`.id = (select max(id) from `mgt_money` `m` where date(`m`.date_public) = date(`t`.date_public) and `m`.id_clienthotel=$id)",'group'=>'date_public'));
      $all = count($model);

       if(count($model)>0)
       {
           foreach ($model as $m)
           {
          
                if($n!=1) $result[$n-1]['finish_date'] = $m->date_public;
             
                $result[$n]['cost'] = $m->cost;
                
                if($n==1)
                $result[$n]['begin_date'] = ($date_begin ? $date_begin : $period->date_stay_begin);
                else 
                $result[$n]['begin_date'] = ($date_begin ? $date_begin : $m->date_public);
                            
                if($all == $n) $result[$n]['finish_date'] = $period->date_stay_finish;
                $n++;
           }
          //  fnc::mpr($result);die();
       
           foreach ($result as $s)
           {
              $sum += fnc::intervalDays($s['begin_date'],$s['finish_date'])*$s['cost'];
           }
           
           if(!$date_begin)
           $minus = Ticks::model()->find(array('condition'=>"id_clienthotel=$id and status = 1",'select'=>"sum(finish_sum) as finish_sum"));
           $plus = Ticks::model()->find(array('condition'=>"id_clienthotel=$id and status = 0",'select'=>"sum(sum_for_doc) as sum_for_doc"));
   
           $sum = $sum - $minus->finish_sum + $plus->sum_for_doc;
            
       }
       else $sum = 0;
       
    
       return $sum;
   }
   
   
   
   public static function getScoreForDoc($id,$date_begin=false)
   {
    
       $period = ClientHotel::model()->findByPk($id);
       $load_documents = Documents::model()->find(array('condition'=>"id_clienthotel = $id",'group'=>'id_invite','select'=>"sum((select price from documents_price where id_document=t.id order by date_edit DESC, id DESC LIMIT 1)) as status,id_invite"));               
       
       $payed_docs_by_user_sum = $load_documents->status*10;        
       $n=1;
       
       $exmodel = self::model()->count(array("condition"=>"'$period->date_stay_begin'=`t`.date_public and `t`.id_clienthotel=$id",'group'=>'date_public'));

       if($exmodel==0)
        $model = self::model()->findAll(array("condition"=>"'$period->date_stay_begin'<=`t`.date_public and date(`t`.date_public)<=date('$period->date_stay_finish') and `t`.id_clienthotel=$id and `t`.id = (select `m`.id from `mgt_money` `m` where `m`.cost <> `t`.cost and `m`.id_clienthotel=$id limit 1) or `t`.id = (select max(id) from `mgt_money` `m` where date(`m`.date_public) < date('$period->date_stay_begin') and `m`.id_clienthotel=$id)",'group'=>'date_public'));
       
       if(count($model)==0)
        $model = self::model()->findAll(array("condition"=>"'$period->date_stay_begin'<=`t`.date_public and date(`t`.date_public)<date('$period->date_stay_finish') and `t`.id_clienthotel=$id and `t`.id = (select max(id) from `mgt_money` `m` where date(`m`.date_public) = date(`t`.date_public) and `m`.id_clienthotel=$id)",'group'=>'date_public'));
        
       if(count($model)==0)
        $model = self::model()->findAll(array("condition"=>"'$period->date_stay_begin'>`t`.date_public and `t`.id_clienthotel=$id and `t`.id = (select max(id) from `mgt_money` `m` where date(`m`.date_public) = date(`t`.date_public) and `m`.id_clienthotel=$id)",'group'=>'date_public'));
        
      $all = count($model);
   

        
       if(count($model)>0)
       {
           foreach ($model as $m)
           {
         
                if($n!=1) $result[$n-1]['finish_date'] = $m->date_public;
             
                $result[$n]['cost'] = $m->cost;
                
                if($n==1)
                $result[$n]['begin_date'] = ($date_begin ? $date_begin : $period->date_stay_begin);
                else 
                $result[$n]['begin_date'] = ($date_begin ? $date_begin : $m->date_public);
                            
                if($all == $n) $result[$n]['finish_date'] = $period->date_stay_finish;
                $n++;
           }
         //   fnc::mpr($result);
       
           foreach ($result as $s)
           {
            
                if($period->price_for>0)
                    $sum += fnc::intervalDays($s['begin_date'],$s['finish_date'])*($period->price_for-$s['cost']);
                else $sum += fnc::intervalDays($s['begin_date'],$s['finish_date'])*$s['cost'];
              
           }
           
          
           
       }
       else $sum = 0;
      
       $sum -=$payed_docs_by_user_sum;
    
       return $sum;
   }

}