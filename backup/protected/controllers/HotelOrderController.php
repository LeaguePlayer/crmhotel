<?php

class HotelOrderController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/column3';

	/**
	 * @return array action filters
	 */
	public function filters()
	{
		return array(
			'accessControl', // perform access control for CRUD operations
		);
	}

	/**
	 * Specifies the access control rules.
	 * This method is used by the 'accessControl' filter.
	 * @return array access control rules
	 */
	public function accessRules()
	{
		return array(
			array('allow',  // allow all users to perform 'index' and 'view' actions
				'actions'=>array('index','view','AutoComplete','monitoring','getCalendar','removeslotsbycal','gobackcalendar','changefirstday','UpdateAjaxHeader','UpdateAjaxUsers','genercal','newcal','GetLastChange','rechange','reserve','invites','CashChange','users','FastUpdate','fastupdatetimer','Eviction','report','loadHotels','updateMails','Extend','history'),
				'users'=>array('*'),
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array('create','update'),
				'users'=>array('@'),
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('admin','delete'),
				'users'=>array('admin'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}

	/**
	 * Displays a particular model.
	 * @param integer $id the ID of the model to be displayed
	 */
	public function actionView($id)
	{
		$this->render('view',array(
			'model'=>$this->loadModel($id),
		));
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate($id,$date)
	{
	   
       
	         // возможно нужно будет поменять на хостинге на Asia/Yekaterinburg
       $current_date =  date('Y-m-d',strtotime($date));
		$model=new HotelOrder;
        
        if(CHtml::encode($date)==date('d.m.Y')) $status = 0;
        else $status = 1;       
        
        
        
		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);
        
        

		if(isset($_POST['HotelOrder']))
		{
		    if(isset($_GET['rereserve']['id_clientHotel'])) $id_client_hotel = $_GET['rereserve']['id_clientHotel'];
            if(isset($_GET['rereserve']['id_order_last'])) $id_order_last = $_GET['rereserve']['id_order_last'];
           
			$model->attributes=$_POST['HotelOrder'];
            $time = $_POST['timepicker'];
           
              
            if($time<'03:00')
            $model->date_stay_begin =  date('Y-m-d',strtotime('-1 day'.$model->date_stay_begin)).' '.$time;
            else
            $model->date_stay_begin =  date('Y-m-d',strtotime($model->date_stay_begin)).' '.$time;
           
           
            $days_how = $_POST['howdays'];
            
            if(!is_numeric($days_how) or $days_how<=0) die("Ошибка в указание кол-ва дней. <a href='javascript: history.go(-1)'>Вернитесь назад</a> и повторите попытку");
            
            if($_POST['howhous']==1)
            {
                $model->date_stay_finish =  date('Y-m-d',strtotime('+'.$days_how.' day'.$model->date_stay_begin)).' 14:00:00';    
            }              
              else
              {
                
                $model->date_stay_finish =  date('Y-m-d H:i',strtotime('+'.$days_how.' hour'.$model->date_stay_begin));   
              }
              
        if($id_order_last!='') $model->status=0;
              
            
           // $model->date_stay_finish =  date('Y-m-d',strtotime($model->date_stay_finish)).' 14:00:00';
  
            $model->create_time = time();
            
             
                    if($model->save())
                    {
                       if(isset($id_client_hotel))
                       {
                        $live_users_now = ClientHotel::model()->count("id_order=$id_order_last");
                        if(($live_users_now-1)<1) HotelOrder::model()->updateByPk($id_order_last,array('date_stay_finish'=>$model->date_stay_begin));
                        ClientHotel::model()->updateByPk($id_client_hotel,array('date_stay_finish'=>$model->date_stay_begin));
                        $newClientHotel = new ClientHotel;
                        $newClientHotel->id_client = ClientHotel::model()->findByPk($id_client_hotel)->getAttribute('id_client');
                        $newClientHotel->id_order=$model->id;
                        $newClientHotel->date_stay_begin = $model->date_stay_begin;
                        $newClientHotel->date_stay_finish = $model->date_stay_finish;
                        $newClientHotel->status=0;
                        $newClientHotel->save();
                        $tick = Ticks::model()->find(array('condition'=>"id_clienthotel=$id_client_hotel and '$current_date'<=date(date_period_finish)",'order'=>"date_period_finish DESC"));
                    //    echo "ID=".$tick->id."<br>";
//                        echo "cnt=".count($tick)."<br>";
//                        echo "date_finish=".$model->date_stay_finish."<br>";
//                        die();
                        if(count($tick)>0)
                        {
                            $price_per_day = HotelOrder::model()->findByPk($id_order_last)->getAttribute('price_per_day');
                            $period = fnc::intervalDays($model->date_stay_begin,$model->date_stay_finish);
                            $res = $model->price_per_day*$period;
                            $finish = $tick->finish_sum-$period*$price_per_day;
                            $spd = $tick->sum_for_days-$period*$price_per_day;
                            
                            Ticks::model()->updateByPk($tick->id,array('date_period_finish'=>$model->date_stay_begin,'sum_for_days'=>$spd,'finish_sum'=>$finish));
                            $get_new_tick = new Ticks;                 
                            $get_new_tick->id_clienthotel = $newClientHotel->id;
                            $get_new_tick->date_period_begin =  $model->date_stay_begin;
                            $get_new_tick->date_period_finish = $model->date_stay_finish;
                            $get_new_tick->status = 1;
                            $get_new_tick->finish_sum = $res;
                            $get_new_tick->note = '';
                            $get_new_tick->id_informer = 0;
                            $get_new_tick->sum_for_days = $res;
                            $get_new_tick->sum_for_doc = 0;
                            $get_new_tick->date_public = date('Y-m-d H:i');
                            $get_new_tick->save();
                        }
                        
                       }
                        $this->render('fancyclose');
                    }
				    
                
           
			
		}

		$this->render('create',array(
			'model'=>$model,
            'status'=>$status,
            'id'=>$id,
            'date_stay'=>$date,
		));
	}

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdate($id)
	{
		$model=$this->loadModel($id);

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['HotelOrder']))
		{
			$model->attributes=$_POST['HotelOrder'];
			if($model->save())
				$this->redirect(array('view','id'=>$model->id));
		}

		$this->render('/');
	}
    
    public function actionMonitoring($id,$date)
    {
        $model=$this->loadModel($id);
        if(isset($_POST['id_order']))
        {
         
            if(isset($_SERVER['HTTP_X_REQUESTED_WITH']) && !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
                 
            $id_order =  $_POST['id_order'];
                 
                 foreach($_POST['users'] as $user)
                 {
                    $array_ready=false;
                    for($a=0;$a<=$_POST['count_dates']+1;$a++)
                    {
                        
                           if($user['select_days'][$a]!='')
                           {
                           
                                 if(!$array_ready)  $date_begin = $user['select_days'][$a];
                                 $array_ready=true;
                                           
                           }
                           elseif($array_ready)
                           {
                                $date_finish =$last_day.' 15:00:00';
                                $array_ready = false;
                                 if($user['id']!='')
                                            {
                                                ClientHotel::SyncSave($user['id'],$id_order,$date_begin,$date_finish);     
                                            }
                                            elseif($user['name']!='')
                                            {
                                                $client = new Clients;
                                                $client->name = $user['name'];
                                                $client->phone = $user['phone'];
                                                $client->save();                
                                                ClientHotel::SyncSave($client->id,$id_order,$date_begin,$date_finish);          
                                            }
                           }
                           $last_day = $user['select_days'][$a];
                    }
                 
                    
                    
                 }
                 $time_now= time();
                HotelOrder::model()->updateByPk($id,array('create_time'=>$time_now));
                
                $this->renderPartial('/hotelOrder/_users', array('model'=>$model,'date'=>$date), false, true);
                die();
            }
        }
        
        
        
        
        $dates_int = HotelOrder::model()->find(array('select'=>'TO_DAYS(`date_stay_finish`) - TO_DAYS(`date_stay_begin`)  as places','condition'=>"id=$id"));
      
        if($dates_int->places>0) $dates_int->places = fnc::getRealWord($dates_int->places);
        else 
        {
            $dates_int = HotelOrder::model()->find(array('select'=>'HOUR(`date_stay_finish`) - HOUR(`date_stay_begin`)  as places','condition'=>"id=$id"));
            $dates_int->places = fnc::getRealWord($dates_int->places,'час','часа','часов');
        }
        
        $name_hotel = Hotels::model()->findByPk($model->id_hotel);
        $hotel_category = $name_hotel->id_cat;
        
        	$this->render('monitoring',array(
			'model'=>$model,
            'dataProvider' => $dataProvider,
            'int_dates'=>$dates_int->places,
            'hotel'=>$name_hotel->name,
            'date'=>$date,
            'current_places'=>$model->places,
            'hotel_category'=>$hotel_category,
            'id_order'=>$id,
		));
    }

	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionDelete($id)
	{
		if(Yii::app()->request->isPostRequest)
		{
			// we only allow deletion via POST request
			$this->loadModel($id)->delete();

			// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
		 $this->render('fancyclose');
		}
		else
			throw new CHttpException(400,'Invalid request. Please do not repeat this request again.');
	}

	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
	   
		$dataProvider=new CActiveDataProvider('HotelOrder');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new HotelOrder('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['HotelOrder']))
			$model->attributes=$_GET['HotelOrder'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer the ID of the model to be loaded
	 */
	public function loadModel($id)
	{
		$model=HotelOrder::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param CModel the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='hotel-order-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
    
        public function actionAutoComplete() {  
 
        if(Yii::app()->request->isAjaxRequest && isset($_GET['term'])) {
 
            $criteria = new CDbCriteria;
            $criteria->condition = "phone LIKE :tquery";
            $criteria->group = 'id_client';
            $criteria->params = array(":tquery"=>'%'.$_GET['term'].'%');
            $criteria->limit = 10;
            $queries = Phones::model()->findAll($criteria);
            $resStr = array();
            $a = 0;
            foreach($queries as $tmpquery) {
                $user_name = Clients::model()->findByPk($tmpquery->id_client);
                $resStr[$a]["label"] = $user_name->name." - ".$tmpquery->phone;
                $resStr[$a]["value"] = $tmpquery->phone;
                $resStr[$a]["xyi"] = $user_name->id;
                $resStr[$a]["username"] = $user_name->name;
                $a++;
            }
            echo CJSON::encode($resStr);       
        }
 
}

    public function actiongetCalendar($id,$date)
    {
               if(isset($_SERVER['HTTP_X_REQUESTED_WITH']) && !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') 
               {
                     $time_now = time();
                     HotelOrder::model()->updateByPk($id, array('date_stay_finish'=>$date.' 14:00:00','create_time'=>$time_now));
                     $this->renderPartial('calendar');
                }
    }
    
    public function actionremoveslotsbycal($id,$date)
    {
                       if(isset($_SERVER['HTTP_X_REQUESTED_WITH']) && !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') 
               {
                     $this_order = HotelOrder::model()->findByPk($id,array('select'=>'DATE(date_stay_begin) as date_stay_begin'))->getAttribute('date_stay_begin');
                     $time_now = time();
                     HotelOrder::model()->updateByPk($id, array('date_stay_finish'=>$date.' 14:00:00','create_time'=>$time_now));
             
                     ClientHotel::model()->updateAll(array('date_stay_finish'=>$date.' 14:00:00'),"id_order=$id and date_stay_begin<'$date' and '$date'<date(date_stay_finish)");
                     if($this_order==$date) ClientHotel::model()->deleteAll('id_order='.$id);
                     $this->renderPartial('calendar');
                }
    }
    public function actiongobackcalendar($id,$date)
    {
                  if(isset($_SERVER['HTTP_X_REQUESTED_WITH']) && !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') 
               {
               
                     HotelOrder::model()->updateByPk($id, array('date_stay_begin'=>$date.' '.date('H:i')));
                     $this->renderPartial('calendar');
                }
    }
    
        public function actionchangefirstday($id,$date)
    {
                  if(isset($_SERVER['HTTP_X_REQUESTED_WITH']) && !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') 
               {
                     $today = date('Y-m-d');  
                     $date_two = date('Y-m-d',strtotime('+1 day'.$date)).' 14:00:00';   
                     $time_now = time();
                     if($today>=$date) $status_house=0;
                     else $status_house=1;
                    
                     HotelOrder::model()->updateByPk($id, array('date_stay_begin'=>$date,'date_stay_finish'=>$date_two,'create_time'=>$time_now,'status'=>$status_house));
                     
                     
               
                    
                     $this->renderPartial('calendar');
                }
    }

      public function actionUpdateAjaxHeader($id)
    {
        $tmp_id_hotel = HotelOrder::model()->findByPk($id)->getAttribute('id_hotel');

        $name_hotel = Hotels::model()->findByPk($tmp_id_hotel);
        $dates_int = HotelOrder::model()->find(array('select'=>'TO_DAYS(`date_stay_finish`) - TO_DAYS(`date_stay_begin`)  as places','condition'=>"id=$id"));
        $dates_int->places = fnc::getRealWord($dates_int->places);
        $this->renderPartial('_header', array('int_dates'=>$dates_int->places,'hotel'=>$name_hotel->name,), false, true);
        
    
    }
    
    public function actionUpdateAjaxUsers($id,$date)
    {
        $this->renderPartial('_users', array('model'=>$this->loadModel($id),'date'=>$date,), false, true);
        
    }
    

    public function actionnewcal($since,$to)
    {
            // возможно нужно будет поменять на хостинге на Asia/Yekaterinburg
         $hotels = Hotels::model()->findAll();
        $this->renderPartial('/site/_calendar', array('hotels'=>$hotels,'days_back'=>$since,'days_prev'=>$to), false, true);
    }
    
    public function actionGetLastChange($since,$to,$user_time)
    {
        $count_order = HotelOrder::model()->count();
        if($count_order>0)        
            $create_time = HotelOrder::model()->find(array('order'=>'create_time DESC'))->getAttribute('create_time');
        
        
        if($user_time<$create_time)
        {
               // возможно нужно будет поменять на хостинге на Asia/Yekaterinburg
            $hotels = Hotels::model()->findAll();
            $this->renderPartial('/site/_calendar', array('hotels'=>$hotels,'days_back'=>$since,'days_prev'=>$to), false, true);
            echo CJSON::encode('DELENIE:'.time());       
        }
    }
    
        public function actionupdateMails($user_time)
    {
        $count_order = HotelOrder::model()->count();
        if($count_order>0)        
            $create_time = HotelOrder::model()->find(array('order'=>'create_time DESC'))->getAttribute('create_time');
        
        
        if($user_time<$create_time)
        {
               // возможно нужно будет поменять на хостинге на Asia/Yekaterinburg
            $hotels = Hotels::model()->findAll();
            $this->renderPartial('/site/_menu', array(), false, true);
            echo CJSON::encode('DELENIE:'.time());       
        }
    }
    
    public function actionrechange($places,$id,$date)
    {
          if(isset($_SERVER['HTTP_X_REQUESTED_WITH']) && !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') 
               {
                    if(!is_numeric($places)) die();
                    $time_now= time();
                    HotelOrder::model()->updateByPk($id,array('places'=>$places,'create_time'=>$time_now));
                    $model=$this->loadModel($id);                 
                    $this->redirect("/?r=hotelOrder/monitoring&id=$id&date=$date");
                    
               }
    }
    
    public function actionReserve($id,$date)
    {
        if(isset($_GET['type'])) $type=$_GET['type'];
        
       
        if(isset($_GET['nomoney']))
        {
            $model=new HotelOrder;
            
        $status=1;
            
          //  if(CHtml::encode($date)==date('d.m.Y')) $status = 0;
//            else $status = 1;       
            
          	if(isset($_POST['HotelOrder']))
		{
			$model->attributes=$_POST['HotelOrder'];
            $time = $_POST['timepicker'];
           
            if($time<'03:00')
            $model->date_stay_begin =  date('Y-m-d',strtotime('-1 day'.$model->date_stay_begin)).' '.$time;
            else
            $model->date_stay_begin =  date('Y-m-d',strtotime($model->date_stay_begin)).' '.$time;
           
            
             
            $days_how = $_POST['howdays'];
            
            if(!is_numeric($days_how) or $days_how<=0) die("Ошибка в указание кол-ва дней. <a href='javascript: history.go(-1)'>Вернитесь назад</a> и повторите попытку");
            
            if($_POST['howhous']==1)
            {
                $model->date_stay_finish =  date('Y-m-d',strtotime('+'.$days_how.' day'.$model->date_stay_begin)).' 14:00:00';    
            }              
              else
              {
                
                $model->date_stay_finish =  date('Y-m-d H:i',strtotime('+'.$days_how.' hour'.$model->date_stay_begin));   
              }
              
              $model->id_invite = 0;
              $model->price_per_day = 0;
            
           // $model->date_stay_finish =  date('Y-m-d',strtotime($model->date_stay_finish)).' 14:00:00';
  
            $model->create_time = time();
             if(isset($_SERVER['HTTP_X_REQUESTED_WITH']) && !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
                $model->save();
                $this->redirect(array('monitoring','id'=>$this->id,'date'=>$date));
                }
                else
                {
                    if($model->save())
                    	$this->render('fancyclose');
				    //$this->redirect(array('fancyclose','id'=>$model->id,'date'=>$date));
                }
           
			
		}
            
            	$this->render('reserve',array(
			'model'=>$model,
            'status'=>$status,
            'id'=>$id,
            'date_stay'=>$date,
            'type'=>$type,
		));
            
        }
        elseif(isset($_GET['money']))
        {
               // возможно нужно будет поменять на хостинге на Asia/Yekaterinburg
        
		$model=new HotelOrder;
        
     //      if(CHtml::encode($date)==date('d.m.Y'))
//            {
//                $today = date('Y-m-d',strtotime($date));
//                $get_time = HotelOrder::model()->find(array('condition'=>"id_hotel = $id and date(date_stay_finish) = '$today'"));
//                
//                if(count($get_time)>0) $status = 1;
//                else $status = 0;
//            }
//            else $status = 1;    
        $status=0;
        
        
        
		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);
        
        

		if(isset($_POST['HotelOrder']))
		{
			$model->attributes=$_POST['HotelOrder'];
            $time = $_POST['timepicker'];
           
            if($time<'03:00')
            $model->date_stay_begin =  date('Y-m-d',strtotime('-1 day'.$model->date_stay_begin)).' '.$time;
            else
            $model->date_stay_begin =  date('Y-m-d',strtotime($model->date_stay_begin)).' '.$time;
           
             
            $days_how = $_POST['howdays'];
            
            if(!is_numeric($days_how) or $days_how<=0) die("Ошибка в указание кол-ва дней. <a href='javascript: history.go(-1)'>Вернитесь назад</a> и повторите попытку");
            
            if($_POST['howhous']==1)
            {
                $model->date_stay_finish =  date('Y-m-d',strtotime('+'.$days_how.' day'.$model->date_stay_begin)).' 14:00:00';    
            }              
              else
              {
                
                $model->date_stay_finish =  date('Y-m-d H:i',strtotime('+'.$days_how.' hour'.$model->date_stay_begin));   
              }
              
              
            
           // $model->date_stay_finish =  date('Y-m-d',strtotime($model->date_stay_finish)).' 14:00:00';
  
            $model->create_time = time();
             if(isset($_SERVER['HTTP_X_REQUESTED_WITH']) && !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
                $model->save();
                $this->redirect(array('monitoring','id'=>$this->id,'date'=>$date));
                }
                else
                {
                    if($model->save())
				    $this->render('fancyclose');
                }
           
			
		}

		$this->render('create',array(
			'model'=>$model,
            'status'=>$status,
            'id'=>$id,
            'date_stay'=>$date,
            'type'=>$type,
		));
        }
        else
        {
            $content = "<style>.switch_reserve
                        {
                            padding-top:8px;
                            width: 400px;
                            margin:auto;
                        }
                        .switch_reserve a
                        {
                            border-radius:8px;
                            margin:0 8px;
                            padding: 8px 12px;
                            background: #3B456A;
                            color:#fff;
                            text-shadow:1px 0 1px #fff;
                            text-decoration:none;
                        }</style>";
            $content .= "<div class='switch_reserve'>";
            $content .= CHtml::link('С предоплатой', array('hotelOrder/reserve','type'=>$type, 'money'=>'yes', 'id'=>$id, 'date'=>$date ));             
            $content .= CHtml::link('Без предоплаты', array('hotelOrder/reserve','type'=>$type, 'nomoney'=>'yes', 'id'=>$id, 'date'=>$date ));  
            $content .="</div>";   
            echo $content;
            die();
        }
    }
    
    public function actionInvites($id,$id_invite)
    {
         if(isset($_SERVER['HTTP_X_REQUESTED_WITH']) && !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') 
         {
            HotelOrder::model()->updateByPk($id,array('id_invite'=>$id_invite));
         }
    }
    
        public function actionCashChange($id,$price)
    {
         if(isset($_SERVER['HTTP_X_REQUESTED_WITH']) && !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') 
         {
            HotelOrder::model()->updateByPk($id,array('price_per_day'=>$price));
         }
    }
    
            public function actionUsers($id_order,$date)
    {
         
        $model = $this->loadModel($id_order);
        
            if(isset($_POST['users']))
            {
                
                 
            $id_order =  $_POST['id_order'];
                 
                 foreach($_POST['users'] as $user)
                 {
                    $array_ready=false;
                    for($a=0;$a<=$_POST['count_dates']+1;$a++)
                    {
                        
                           if($user['select_days'][$a]!='')
                           {
                           
                                 if(!$array_ready)  $date_begin = $user['select_days'][$a];
                                 $array_ready=true;
                                           
                           }
                           elseif($array_ready)
                           {
                                 $date_finish_last = $last_day;
                                $date_finish =$last_day.' 14:00:00';
                                $array_ready = false;
                                $date_begin_last = $date_begin;
                                $date_begin = $date_begin.' '.date('H:i');
                                 if($user['id']!='')
                                            {
                                                $id_user =$user['id'];
                                                $obj_user = ClientHotel::model()->find("id_client=$id_user and id_order=$id_order and '$date_finish_last'<date(date_stay_begin)");
                                                if(count($obj_user)>0)
                                                {
                                                    ClientHotel::model()->updateByPk($obj_user->id,array('date_stay_begin'=>$date_begin));
                                                }else
                                                {
                                                    $obj_user = ClientHotel::model()->find("id_client=$id_user and id_order=$id_order");
                                                    if(count($obj_user)>0)
                                                    {
                                                        ClientHotel::model()->updateByPk($obj_user->id,array('date_stay_finish'=>$date_finish));
                                                    }
                                                    else ClientHotel::SyncSave($user['id'],$id_order,$date_begin,$date_finish,0); 
                                                }
                                                
                                                    
                                            }
                                            elseif($user['name']!='')
                                            {
                                                $client = new Clients;
                                                $client->name = $user['name'];
                                               
                                                $client->save();       
                                                    $new_phone = new Phones;
                                                    $new_phone->id_client = $client->id;
                                                    $new_phone->phone = $user['phone'];
                                                    $new_phone->save();         
                                                ClientHotel::SyncSave($client->id,$id_order,$date_begin,$date_finish,0);          
                                            }
                           }
                           $last_day = $user['select_days'][$a];
                    }
                 
                    
                    
                 }
                 $time_now= time();
                HotelOrder::model()->updateByPk($id_order,array('create_time'=>$time_now,'status'=>0));
                
                 	$this->renderPartial('_live_users',array('model'=>$model,'date'=>$date),false,true);
                die();
            }
            
            	$this->render('users',array(
			'model'=>$model,
            'date'=>$date,
 
		));
         }
         
         public function actionFastUpdate($id_order,$date)
         {
            if(isset($_SERVER['HTTP_X_REQUESTED_WITH']) && !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') 
            {
            $model = $this->loadModel($id_order);
            
            $this->renderPartial('_live_users',array('model'=>$model,'date'=>$date),false,true);
                die();
            }           
            
         }
         
         public function actionFastUpdateTimer($time,$id_order)
         {            
             if(isset($_SERVER['HTTP_X_REQUESTED_WITH']) && !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') 
            {
                $order_date = HotelOrder::model()->findByPk($id_order);
                $order_date->date_stay_finish =  date('Y-m-d',strtotime($order_date->date_stay_finish)); 
                $order_date->date_stay_finish .= ' '.$time;
                HotelOrder::model()->updateByPk($id_order,array('date_stay_finish'=>$order_date->date_stay_finish));
                die();
            }
         }
         
         public function actionEviction($id_order,$id_user,$type,$date)
         {
             
            $date_converted = date('Y-m-d',strtotime($date));
            $today = date('Y-m-d');
            $today_with_time = date('Y-m-d H:i');
            switch($type)
            {
                case 'exit':
                    $id_hotel =  HotelOrder::model()->findByPk($id_order)->getAttribute('id_hotel');
                   
                    $time = time();
                    HotelOrder::model()->updateByPk($id_order,array('create_time'=>$time));
                    $client = ClientHotel::model()->find(array('condition'=>"id_order=$id_order and id_client=$id_user and date(date_stay_begin)<='$today' and '$today'<=date(date_stay_finish)"));
                    $client->date_stay_finish = $today_with_time;
                    $client->status=1;
                    $client->save();
                    $cnt_clients = ClientHotel::model()->count(array('condition'=>"id_order=$id_order and status=0"));
                    
                    if($cnt_clients==0)
                    {
                        Hotels::model()->updateByPk($id_hotel,array('dirty'=>1));
                        HotelOrder::model()->updateByPk($id_order,array('date_stay_finish'=>$today_with_time,'status'=>2));
                    }
                    $this->renderPartial('_live_users',array('model'=>$this->loadModel($id_order),'date'=>$date),false,true);
                break;
                
                case 'exit_with_money':
                 if(isset($_POST['return']))
                 {
                   
                    $model=new Ticks;    
		          	$model->attributes=$_POST['return'];
                    $model->status=1;
                    $model->sum_for_days = $model->sum_for_days*-1;
                    $model->finish_sum = $model->sum_for_days+$model->sum_for_doc;
                    $model->note = "Выселение с перерасчётом";
                    $model->id_informer = 0;
                    $model->date_public = date('Y-m-d H:i');
                    if($model->save())
                    {
                                $id_hotel =  HotelOrder::model()->findByPk($id_order)->getAttribute('id_hotel');
                           
                            $time = time();
                            HotelOrder::model()->updateByPk($id_order,array('create_time'=>$time));
                            $client = ClientHotel::model()->find(array('condition'=>"id_order=$id_order and id_client=$id_user and date(date_stay_begin)<='$today' and '$today'<=date(date_stay_finish)"));
                            $client->date_stay_finish = $today_with_time;
                            $client->status=1;
                            $client->save();
                            $cnt_clients = ClientHotel::model()->count(array('condition'=>"id_order=$id_order and status=0"));
                            
                            if($cnt_clients==0)
                            {
                                 Hotels::model()->updateByPk($id_hotel,array('dirty'=>1));
                                HotelOrder::model()->updateByPk($id_order,array('date_stay_finish'=>$today_with_time,'status'=>2));
                            }
                              // $cnt_users = ClientHotel::model()->count("id_order=$id_order");
        //                       if($cnt_users-1<)
                              
                    }
                    $this->render('fancyclose');
                 }else
                 {
                    $ClientHotel = ClientHotel::model()->find(array('condition'=>"id_order=$id_order and id_client=$id_user",'order'=>'ID DESC'));
                    $TICK = Ticks::model()->find(array('condition'=>"id_clienthotel={$ClientHotel->id}",'order'=>'id desc','select'=>"sum(finish_sum) as finish_sum"));
                    $DOLG = HotelOrder::model()->find(array('condition'=>"id=$id_order",'select'=>"to_days(date_stay_finish)-to_days(now()) as `status`,(price_per_day*(to_days(date_stay_finish)-to_days(now()))) as `places`"));
                    $begin = date('Y-m-d H:i');
                    $finish = $ClientHotel->date_stay_finish;
                    $this->render('_with_money',array('id_clienthotel'=>$ClientHotel->id,'begin'=>$begin,'finish'=>$finish,'return_days'=>$DOLG->status,'sum_all_days'=>$TICK->finish_sum,'return_sum'=>$DOLG->places));
                 }
                     
                break;
                
                
            
                
                
                case 'rereserve':
             
                     $client = ClientHotel::model()->find(array('condition'=>"id_order=$id_order and id_client=$id_user and date(date_stay_begin)<='$date_converted' and '$date_converted'<=date(date_stay_finish)"));
                    $loadForm = CHtml::beginForm("?r=hotelOrder/create&date=$date",'GET');
                    $loadForm .= '<label>Выберите категорию</label>';
                    $loadForm .= CHtml::dropDownList('country_id','', fnc::getCategory(),
                    array(
                    'ajax' => array(
                    'type'=>'POST', //request type
                    'url'=>CController::createUrl('hotelOrder/loadHotels'), //url to call.
                    //Style: CController::createUrl('currentController/methodToCall')
                    'update'=>'#id', //selector to update
                    //'data'=>'js:javascript statement' 
                    //leave out the data key to pass all form values through
                    ))); 
                     
                    //empty since it will be filled by the other dropdown
                    $loadForm .= '<label>Выберите квартиру</label>';
                    $loadForm .= CHtml::dropDownList('id','', array());
                    $loadForm .= CHtml::hiddenField('rereserve[id_clientHotel]',$client->id);
                    $loadForm .= CHtml::hiddenField('rereserve[id_order_last]',$id_order);
                    $loadForm .= CHtml::submitButton('Подобрать квартиру');
                    $loadForm .= CHtml::endForm();
                    
                    $this->renderPartial('changer',array('form'=>$loadForm),false,true);
                    
                break;
                
                
                default:
                break;
            }
            
            die();
         }
         
         public function actionReport($date,$type)
         {
            switch ($type)
            {
                case 'by_day':
                
                $tmn_date = date('Y-m-d',strtotime($date));
                        //$report = HotelOrder::model()->with(array(
//                        'with_client'=>array(
//                                // записи нам не нужны
//                                'select'=>false,
//                                'with'=>'with_ticks',
//                                // но нужно выбрать только пользователей с опубликованными записями
//                              //  'joinType'=>'left JOIN',
//                              //  'condition'=>"date(date_public)='$tmn_date'",
//                                
//                            )
//                        ))->findAll(array('select'=>'id_invite','group'=>'id_invite',));
//                        
//                        $report_cashed = HotelOrder::model()->with(array(
//                        'with_client'=>array(
//                                // записи нам не нужны
//                                'select'=>false,
//                                'with'=>'with_ticks_public',
//                                // но нужно выбрать только пользователей с опубликованными записями
//                              //  'joinType'=>'left JOIN',
//                              //  'condition'=>"date(date_public)='$tmn_date'",
//                                
//                            )
//                        ))->findAll(array('select'=>'id_invite','group'=>'id_invite'));

                $connection=Yii::app()->db; // так можно сделать, если в конфигурации описан компонент соединения "db"
                $SQL = "select `o`.id_invite,sum(finish_sum),(sum(sum_for_days)+sum(sum_for_doc)) as sum_for_days from `hotel_order` `o` left join `client_hotel` `c` on `c`.id_order=`o`.id left join `ticks` `t` on `t`.id_clienthotel=`c`.id where date(date_public)='$tmn_date' group by `o`.id_invite";
                $command=$connection->createCommand($SQL);
                
                $report=$command->query();
                // многократно вызываем read() до возврата методом значения false
               
                
                // используем foreach для построчного обхода данных
         //       foreach($dataReader as $row) 
//                { 
//                    fnc::mpr($row);
//                }
//                die();
                // получаем все строки разом в одном массиве
                

                       
                       
                        //array('condition'=>"date(date_period_begin)<='$tmn_date' and '$tmn_date'<=date(date_period_finish)",'group'=>'id_invite','select'=>'sum(price_per_day) as price_per_day,id_invite')
               	        $this->renderPartial('report',array(
			            'date'=>$date,
                        'type'=>$type,
                        'report'=>$report,
                      
	                   	));
                break;

            }
            
         }
         
         public function actionloadHotels()
         {
            $id = $_POST['country_id'];
              $data=Hotels::model()->findAll('id_cat='.$id);
             
                $data=CHtml::listData($data,'id','name');
                foreach($data as $value=>$name)
                {
                    echo CHtml::tag('option',
                               array('value'=>$value),CHtml::encode($name),true);
                }
         }
         
         
         public function actionExtend()
         {
            if(isset($_POST['newLive']))
            {
                $days = $_POST['days'];
                $day = $_POST['date_by_GET'];
                $day = date('Y-m-d',strtotime($day)); 
                $id_order = $_POST['id_order_GET'];
                if(isset($days) and $days!='')
                {
                    
                    
                        foreach ($_POST['newLive'] as $one_boy)
                    {
                        
                        $id_clienthotel = ClientHotel::model()->find(array('condition'=>"id_client=$one_boy and id_order=$id_order and date(date_stay_begin)<='$day' and '$day'<=date(date_stay_finish)"))->getAttribute('id');
                        $live_to = ClientHotel::model()->findByPk($id_clienthotel)->getAttribute('date_stay_finish');
                        $finish_date = date('Y-m-d',strtotime("+$days day".$live_to)); 
                        $finish_date = $finish_date.' 14:00:00';
                        ClientHotel::model()->updateByPk($id_clienthotel,array('date_stay_finish'=>$finish_date));
                        $log = Logs::model()->find(array('condition'=>"id_order=$id_order"));
                        $log->date_stay_finish = $finish_date;
                        $log->save();
                        $price_per_day = HotelOrder::model()->findByPk($id_order)->getAttribute('price_per_day');
                            $new_tick = new Ticks;                     	 	 	 
                            $new_tick->id_clienthotel = $id_clienthotel;
                            $new_tick->date_period_begin = $live_to;
                            $new_tick->date_period_finish = $finish_date;
                            $new_tick->status = 1;
                            $new_tick->finish_sum = $price_per_day*$days;
                            $new_tick->note = '';
                            $new_tick->id_informer = 0;
                            $new_tick->sum_for_days = $price_per_day*$days;
                            $new_tick->sum_for_doc = 0;
                            $new_tick->date_public = date('Y-m-d H:i');
                            $new_tick->save();
                    }
                    $time = time();
                    HotelOrder::model()->updateByPk($id_order,array('date_stay_finish'=>$finish_date,'create_time'=>$time));
                   
                    
                }
                
            }
         }
         
         public function actionHistory($id_user,$id_order)
         {
            $logs = Logs::model()->findAll(array('condition'=>"id_client=$id_user",'order'=>'id DESC'));
              $this->render('history',array(
			           'logs'=>$logs,
                      
	                   	));
         }
    

}
