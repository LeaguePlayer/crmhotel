<?php

class HotelOrderController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/column2';

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
	   $user = new Users;
		return array(
			array('allow',  // allow all users to perform 'index' and 'view' actions
				'actions'=>array(''),
				'users'=>array('*'),
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array(''),
				'users'=>array('@'),
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('reserve','autocomplete','report','getlastchange','monitoring','AddMGTMoney','fastupdate','eviction','extend','users','loadHotels','create','delete','actionperday','edit_order','editFinally','goalist','deletelist','uncurrect','forma3g'),
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
	   if(!Users::getDostup(5)) 
        {
            throw new CHttpException(403,'Недостаточно прав доступа');
            die();
        }
       
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
            
             if(isset($id_order_last))
             {
                $real_begin_order = HotelOrder::model()->findByPk($id_order_last);
                
                if($current_date==date('Y-m-d',strtotime($real_begin_order->date_stay_begin)))
                {
                   
                    $id_hotel = $_GET['id'];
                    HotelOrder::model()->updateByPk($id_order_last,array("id_hotel"=>$id_hotel,'create_time'=>time()));
                  //  ClientHotels::model()->updateByPk($id_client_hotel,array(""=>""));
                    $this->render('fancyclose');
                    die();
                }
             }
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
                                if(fnc::definePlatformPC())
                        $this->render('fancyclose');
                    else
                        $this->redirect('/');
                    die("Идёт переадресация...");
                      
                    }
				    
                
           
			
		}

		$this->render('create',array(
			'model'=>$model,
            'status'=>$status,
            'id'=>$id,
            'date_stay'=>$date,
		));
	}
    
    
   public function actioneditUncurrect()
   {
        if(!Users::getDostup(2)) 
        {
            throw new CHttpException(403,'Недостаточно прав доступа');
            die();
        }
        
        if(Yii::app()->request->isAjaxRequest)
        {
            if(isset($_POST['param']))
            {                
                $type = $_POST['param']['type'];
                $id = $_POST['param']['id'];
                $uncurrect = $_POST['param']['uncurrect'];                
                switch ($type)
                {
                    case 'ticks':
                        $HO = HotelOrder::model()->findByPk($id);
                        $HO->uncurrect = $uncurrect;
                        $HO->save();
                    break;
                }
                
                
                if($uncurrect=="") echo "EMPTY";
                else echo "FILL";
            }
            
        }
   }
   
   public function actionUncurrect()
   {
        if(!Users::getDostup(2)) 
        {
            throw new CHttpException(403,'Недостаточно прав доступа');
            die();
        }
        
       if(Yii::app()->request->isAjaxRequest)
       {
            $type = $_POST['type'];
            $id = $_POST['id'];
            switch ($type)
            {
                case 'ticks':
                    $HO = HotelOrder::model()->findByPk($id);
                    if(is_object($HO))
                    {
                        echo "<form class='edit_uncurrent_form'>";
                        echo "<input type='hidden' value='$id' name='param[id]' />";
                        echo "<input type='hidden' value='$type' name='param[type]' />";
                        echo "<textarea name='param[uncurrect]'>$HO->uncurrect</textarea>";
                        echo "<br />";
                        echo "<input type='submit' class='send_button' />";
                        echo "<a href='javascript:void(0);' class='close_button'>закрыть</a>";
                        echo "</form>";
                    }
                break;
            }
            
       }
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

		$this->render('update',array(
			'model'=>$model,
		));
	}

	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionDelete($id)
	{
	   if(!Users::getDostup(5)) 
        {
            throw new CHttpException(403,'Недостаточно прав доступа');
            die();
        }
        
		
		if(Yii::app()->request->isPostRequest)
		{
			// we only allow deletion via POST request
			$this->loadModel($id)->delete();

			// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
		 if(fnc::definePlatformPC())
                        $this->render('fancyclose');
                    else
                        $this->redirect('/');
                    die("Идёт переадресация...");
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
	   if(!Users::getDostup(5)) 
        {
            throw new CHttpException(403,'Недостаточно прав доступа');
            die();
        }
        
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
    
    public function actionAutoComplete() 
    {
        if(Yii::app()->request->isAjaxRequest && isset($_GET['term'])) {
 
            $find = $_GET['term'];
            $criteria = new CDbCriteria;
            $criteria->condition = "phone LIKE :tquery";
            $criteria->group = 'id_client';
            $criteria->params = array(":tquery"=>'%'.$find.'%');
            $criteria->limit = 10;
            $queries = Phones::model()->findAll($criteria);
            $resStr = array();
            $a = 0;
            foreach($queries as $tmpquery) 
            {
                $user_name = Clients::model()->findByPk($tmpquery->id_client);
                $notes = Notice::model()->count("id_client = $tmpquery->id_client");
                if($user_name->name!='') $seporator = ' - ';
                else $seporator='';
                if($notes>0) $notes_r = " ($notes)"; 
                else $notes_r='';
               // 89220455189
               // 8-922-045-5189
                
                $resStr[$a]["label"] = $user_name->name.$seporator.$tmpquery->phone.$notes_r;
                
                $str = $tmpquery->phone;
                
            	$resStr[$a]["value"] = $tmpquery->phone;
                 
            	
    
                $resStr[$a]["xyi"] = $user_name->id;
                $resStr[$a]["clear"] = CHtml::link('<img title="Очистить поля пользователя" src="/images/cancel.png">','javascript:void(0);');
                $resStr[$a]["notes"] = $notes;
                if($notes>0)
                    $resStr[$a]["notes_link"] = CHtml::link('<img title="Присутствуют замечания" src="/images/note_s.png">',array('/notice/create/id_user/'.$user_name->id));
                    else $resStr[$a]["notes_link"] = ' ';
                $resStr[$a]["username"] = $user_name->name;
                $a++;
            }
            echo CJSON::encode($resStr);       
        } 
    }
    

   public static function num2str($num) {
    $nul='ноль';
    $ten=array(
        array('','один','два','три','четыре','пять','шесть','семь', 'восемь','девять'),
        array('','одна','две','три','четыре','пять','шесть','семь', 'восемь','девять'),
    );
    $a20=array('десять','одиннадцать','двенадцать','тринадцать','четырнадцать' ,'пятнадцать','шестнадцать','семнадцать','восемнадцать','девятнадцать');
    $tens=array(2=>'двадцать','тридцать','сорок','пятьдесят','шестьдесят','семьдесят' ,'восемьдесят','девяносто');
    $hundred=array('','сто','двести','триста','четыреста','пятьсот','шестьсот', 'семьсот','восемьсот','девятьсот');
    $unit=array( // Units
        array('копейка' ,'копейки' ,'копеек',	 1),
        array('рубль'   ,'рубля'   ,'рублей'    ,0),
        array('тысяча'  ,'тысячи'  ,'тысяч'     ,1),
        array('миллион' ,'миллиона','миллионов' ,0),
        array('миллиард','милиарда','миллиардов',0),
    );
    //
    list($rub,$kop) = explode('.',sprintf("%015.2f", floatval($num)));
    $out = array();
    if (intval($rub)>0) {
        foreach(str_split($rub,3) as $uk=>$v) { // by 3 symbols
            if (!intval($v)) continue;
            $uk = sizeof($unit)-$uk-1; // unit key
            $gender = $unit[$uk][3];
            list($i1,$i2,$i3) = array_map('intval',str_split($v,1));
            // mega-logic
            $out[] = $hundred[$i1]; # 1xx-9xx
            if ($i2>1) $out[]= $tens[$i2].' '.$ten[$gender][$i3]; # 20-99
            else $out[]= $i2>0 ? $a20[$i3] : $ten[$gender][$i3]; # 10-19 | 1-9
            // units without rub & kop
            if ($uk>1) $out[]= self::morph($v,$unit[$uk][0],$unit[$uk][1],$unit[$uk][2]);
        }
    }
    else $out[] = $nul;
    $out[] = self::morph(intval($rub), $unit[1][0],$unit[1][1],$unit[1][2]); // rub
    $out[] = $kop.' '.self::morph($kop,$unit[0][0],$unit[0][1],$unit[0][2]); // kop
    return trim(preg_replace('/ {2,}/', ' ', join(' ',$out)));
}

/**
 * Склоняем словоформу
 * @ author runcore
 */
public static function morph($n, $f1, $f2, $f5) {
    $n = abs(intval($n)) % 100;
    if ($n>10 && $n<20) return $f5;
    $n = $n % 10;
    if ($n>1 && $n<5) return $f2;
    if ($n==1) return $f1;
    return $f5;
}
  
    
    
    public function actionforma3g($id_clienthotel)
    {
//        if(!Users::getDostup(2)) 
//        {
//            throw new CHttpException(403,'Недостаточно прав доступа');
//            die();
//        }
//        
        $load_settings = Settings::model()->findByPk(1);
        $my_clienthotel = ClientHotel::model()->with('client')->findByPk($id_clienthotel);
        
        if(is_object($load_settings) and is_object($my_clienthotel))
        {
            Yii::app()->clientScript->registerCoreScript('jquery');
            
            $date_start['day'] = date('d',strtotime($my_clienthotel->date_stay_begin)); 
            $mnt = date('M',strtotime($my_clienthotel->date_stay_begin));
            $date_start['month'] = fnc::getMonth($mnt); 
            $date_start['year'] = date('Y',strtotime($my_clienthotel->date_stay_begin)); 
            $date_start['time'] = date('H:i',strtotime($my_clienthotel->date_stay_begin));
            
            $date_finish['day'] = date('d',strtotime($my_clienthotel->date_stay_finish)); 
            $mnt = date('M',strtotime($my_clienthotel->date_stay_finish));
            $date_finish['month'] = fnc::getMonth($mnt); 
            $date_finish['year'] = date('Y',strtotime($my_clienthotel->date_stay_finish)); 
            $date_finish['time'] = date('H:i',strtotime($my_clienthotel->date_stay_finish)); 
            
            $cnt_days = fnc::intervalDays($my_clienthotel->date_stay_begin, $my_clienthotel->date_stay_finish);
            $all_money = Ticks::model()->find(array('condition'=>"id_clienthotel = {$my_clienthotel->id} and status = 1 and finish_sum>0",'select'=>'sum(finish_sum) as finish_sum'));
            $per_day  = round($all_money->finish_sum / $cnt_days);
            $nds = $all_money->finish_sum*0.18;
            $myy_nds = self::num2str($nds);
            $myy_sum = self::num2str($all_money->finish_sum);
            $nds_array = explode('.',$nds);
            if(count($nds_array)==2)
            {
                
                $nds_array[1] = round($nds_array[1],-2);
                $nds_array[1] = substr($nds_array[1],0, 2);
                $nds = implode('-', $nds_array);
            }
            else $nds .= "-00";
            
            
          
            
            $style = "<style>body {width:800px; margin: auto;} table tr {padding:0; margin:0;width: 100%;} table tr td {border-bottom: 1px solid #000;border-right: 1px solid #000;padding:8px 6px; margin:0;} table thead tr td {font-weight:bold; text-align:center;} table thead tr td {border-top:1px solid #000;}</style>";
            $script = "<script type=\"text/javascript\" src=\"/js/jquery.js\"></script><script type=\"text/javascript\" src=\"/js/editable.js\"></script>";
            
            
            $content =  "<div style='text-align:right;'>Утверждена<br>
                    Приказом Министерства финансов<br>
                    Российской Федерации<br>
                    от 13 декабря 1993 г. N 121<br>
                    <br><br>
                    Форма N 3-Г
                    </div>";
        
            $content .= "Гостиница {$load_settings->hotel_name}<br>Город {$load_settings->city}<br>Адрес {$load_settings->street}<br><br><br>"; 
            
            $need_nulls = 6 - strlen($my_clienthotel->id);
            $my_score = "";
            for ($n = 0; $n < $need_nulls; $n++)
                $my_score.='0';
            
            
            
            $my_score.=$my_clienthotel->id;
            
            $content .= "СЧЕТ № {$my_score} от {$date_start[day]} {$date_start[month]} {$date_start[year]} г.<br><br>";
            $content .= "Заказчик: {$my_clienthotel->client->name}<br><br>";
            $content .= "Проживающий: {$my_clienthotel->client->name}, Россия, Москва<br><br>";
            $content .= "Заезд: {$date_start[time]} {$date_start[day]} {$date_start[month]} {$date_start[year]} г.<br>";
            $content .= "Выезд: {$date_finish[time]} {$date_finish[day]} {$date_finish[month]} {$date_finish[year]} г.<br><br>";
            
            $content .= "<table BORDER='0' CELLPADDING='0' CELLSPACING='0' >";
                $content .= "<thead>";
                    $content .= "<tr>";
                        $content .= "<td style='border-left:1px solid #000;'>";
                            $content .= "№ п/п";
                        $content .= "</td>";
                        $content .= "<td>";
                            $content .= "Виды платежей";
                        $content .= "</td>";
                        $content .= "<td>";
                            $content .= "Единица измерен.";
                        $content .= "</td>";
                        $content .= "<td>";
                            $content .= "Кол-во единиц";
                        $content .= "</td>";
                        $content .= "<td>";
                            $content .= "Цена (руб.)";
                        $content .= "</td>";
                        $content .= "<td>";
                            $content .= "Сумма (руб.)";
                        $content .= "</td>";
                    $content .= "<tr>";
                $content .= "</thead>";
                $content .= "<tbody>";
                    $content .= "<tr>";
                        $content .= "<td style='border-left:1px solid #000;width: 50px;text-align:center;'>";
                            $content .= "1";
                        $content .= "</td>";
                        $content .= "<td>";
                            $content .= "Бронь";
                        $content .= "</td>";
                        $content .= "<td style='text-align:center;'>";
                            $content .= "%";
                        $content .= "</td>";
                        $content .= "<td class='canedit' style='text-align:center;'>";
                            $content .= "0";
                        $content .= "</td>";
                        $content .= "<td class='canedit' style='text-align:right;'>";
                            $content .= "0-00";
                        $content .= "</td>";
                        $content .= "<td class='canedit' style='text-align:right;'>";
                            $content .= "0-00";
                        $content .= "</td>";
                    $content .= "<tr>";
                    $content .= "<tr>";
                        $content .= "<td style='border-left:1px solid #000;width: 50px;text-align:center;'>";
                            $content .= "2";
                        $content .= "</td>";
                        $content .= "<td>";
                            $content .= "Проживание {$my_clienthotel->client->name} с {$date_start[day]} {$date_start[month]} {$date_start[year]} по {$date_finish[day]} {$date_finish[month]} {$date_finish[year]}";
                        $content .= "</td>";
                        $content .= "<td style='text-align:center;'>";
                            $content .= "сутки";
                        $content .= "</td>";
                        $content .= "<td class='canedit' style='text-align:center;'>";
                            $content .= "{$cnt_days}";
                        $content .= "</td>";
                        $content .= "<td class='canedit' style='text-align:right;'>";
                            $content .= "{$per_day}-00";
                        $content .= "</td>";
                        $content .= "<td class='canedit' style='text-align:right;'>";
                            $content .= "{$all_money->finish_sum}-00";
                        $content .= "</td>";
                    $content .= "<tr>";
                    $content .= "<tr>";
                        $content .= "<td style='border-left:1px solid #000;width: 50px;text-align:center;'>";
                            $content .= "3";
                        $content .= "</td>";
                        $content .= "<td>";
                            $content .= "Телевизор";
                        $content .= "</td>";
                        $content .= "<td style='text-align:center;'>";
                            $content .= "сутки";
                        $content .= "</td>";
                        $content .= "<td class='canedit' style='text-align:center;'>";
                            $content .= "{$cnt_days}";
                        $content .= "</td>";
                        $content .= "<td class='canedit' style='text-align:right;'>";
                            $content .= "0-00";
                        $content .= "</td>";
                        $content .= "<td class='canedit' style='text-align:right;'>";
                            $content .= "0-00";
                        $content .= "</td>";
                    $content .= "<tr>";
                    $content .= "<tr>";
                        $content .= "<td style='border-left:1px solid #000;width: 50px;text-align:center;'>";
                            $content .= "4";
                        $content .= "</td>";
                        $content .= "<td>";
                            $content .= "Холодильник";
                        $content .= "</td>";
                        $content .= "<td style='text-align:center;'>";
                            $content .= "сутки";
                        $content .= "</td>";
                        $content .= "<td class='canedit' style='text-align:center;'>";
                            $content .= "{$cnt_days}";
                        $content .= "</td>";
                        $content .= "<td class='canedit' style='text-align:right;'>";
                            $content .= "0-00";
                        $content .= "</td>";
                        $content .= "<td class='canedit' style='text-align:right;'>";
                            $content .= "0-00";
                        $content .= "</td>";
                    $content .= "<tr>";
                    $content .= "<tr>";
                        $content .= "<td style='border-left:1px solid #000;width: 50px;text-align:center;'>";
                            $content .= "5";
                        $content .= "</td>";
                        $content .= "<td>";
                            $content .= "Телефон (ограничен нумерацией город Тюмень)";
                        $content .= "</td>";
                        $content .= "<td style='text-align:center;'>";
                            $content .= "сутки";
                        $content .= "</td>";
                        $content .= "<td class='canedit' style='text-align:center;'>";
                            $content .= "{$cnt_days}";
                        $content .= "</td>";
                        $content .= "<td class='canedit' style='text-align:right;'>";
                            $content .= "0-00";
                        $content .= "</td>";
                        $content .= "<td class='canedit' style='text-align:right;'>";
                            $content .= "0-00";
                        $content .= "</td>";
                    $content .= "<tr>";
                $content .= "</tbody>";
                $content .= "<tr>";
                        $content .= "<td style='border:none;'>";
                            $content .= "";
                        $content .= "</td>";
                        $content .= "<td style='border:none;'>";
                            $content .= "";
                        $content .= "</td>";
                        $content .= "<td style='border:none;'>";
                            $content .= "";
                        $content .= "</td>";
                        $content .= "<td style='border:none;'>";
                            $content .= "";
                        $content .= "</td>";
                        $content .= "<td style='border:none;'>";
                            $content .= "ИТОГО:";
                        $content .= "</td>";
                        $content .= "<td class='canedit' style='border-left: 1px solid #000; text-align:right;'>";
                            $content .= "{$all_money->finish_sum}-00";
                        $content .= "</td>";
                    $content .= "<tr>";
                    $content .= "<tr>";
                        $content .= "<td style='border:none;'>";
                            $content .= "";
                        $content .= "</td>";
                        $content .= "<td style='border:none;'>";
                            $content .= "";
                        $content .= "</td>";
                        $content .= "<td style='border:none;'>";
                            $content .= "";
                        $content .= "</td>";
                        $content .= "<td style='border:none;'>";
                            $content .= "";
                        $content .= "</td>";
                        $content .= "<td style='border:none;'>";
                            $content .= "В том числе НДС:";
                        $content .= "</td>";
                        $content .= "<td class='canedit' style='border-left: 1px solid #000; text-align:right;'>";
                            $content .= "{$nds}";
                        $content .= "</td>";
                    $content .= "<tr>";
            $content .= "</table>";
            $content .= "<br><br><br>";
            $content .= "Итого получено по счету:  <div class='canedit' style='display:inline;'>{$myy_sum}</div><br><br>";
            $content .= "В том числе НДС 18%: <div class='canedit' style='display:inline;'>{$myy_nds}</div><br>";
            $content .= "<br><br><br>";
            $content .= "Дежурный администратор:  ________________________________________ ({$load_settings->director})<br>";
            $content .= "<div style='padding-left:330px'>м.п.</div>";
            
           
           
            
            $content .= $style.$script;
            
        }
        
        
//      $content = iconv("CP1251", "UTF-8", $content);
//       
//        header("Pragma: no-cache");
//        header("Expires: 0");
//        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
//        header("Cache-Control: private", false);
//        header("Content-type: application/vnd.ms-word");
//        header("Content-Disposition: attachment; filename=\"3g.doc");
//        header("Content-Transfer-Encoding: binary");
//        ob_clean();
//        flush();
        echo $content;
//        Yii::app()->end();
    }
    
  
    
    public function actionReserve($id,$date,$type = false,$TYC=false)
    {
  
        if(!Users::getDostup(5)) 
        {
            throw new CHttpException(403,'Недостаточно прав доступа');
            die();
        }
        
        date_default_timezone_set("Asia/Dhaka");
        
        if(isset($this->user_info['ticks']) and !$type)
        {
          
            switch($this->user_info['ticks'])
            {
                case 'YES':
                    $this->redirect(array('/hotelOrder/reserve','type'=>'with_money','date'=>$date,'id'=>$id));
                    die();
                break;
                case 'NO':
                    $this->redirect(array('/hotelOrder/reserve','type'=>'nomoney','date'=>$date,'id'=>$id));
                     die();
                break;
            }
        }
      //  fnc::mpr($this->user_info);
        if(isset($_POST))
        {
           
            if(isset($_POST['button_checker']))
            {
                
                $reserve_param = $_POST['HotelOrder'];
                $stay  = $_POST['stay'];      
                $param  = $_POST['param'];    
                $users_in_hotel = $_POST['hotel'];      
                $reserve_param['date_stay_begin'] = date('Y-m-d H:i:s',strtotime($stay['date'].' '.$stay['time']));
                
                switch($type)
                {
                    case 'with_money':
                       $reserve_param['status'] = ($reserve_param['TYC']==1 ? 4 : 0);
                    break;
                    case 'nomoney':
                       $reserve_param['status'] = ($reserve_param['TYC']==1 ? 5 : 1);
                    break;
                    case 'halfmoney':
                       $reserve_param['status'] = ($reserve_param['TYC']==1 ? 6 : 3);
                    break;
                }
                
                switch ($reserve_param['TYC'])
                {
                    case 1: // ТУЦ
                        foreach($users_in_hotel as $user)
                        $array_dates[] = $user['user_finish'];
                        sort($array_dates);
                        $reserve_param['date_stay_finish'] = date('Y-m-d',strtotime($array_dates[count($array_dates)-1])).' 14:00:00';
                    break;
                    default: // Гостиницы
                    
                        if(isset($param['hour'])) 
                            $reserve_param['date_stay_finish'] = date('Y-m-d H:i',strtotime("+{$param['next_days']} hour ".$reserve_param['date_stay_begin']));
                        else 
                            $reserve_param['date_stay_finish'] = date('Y-m-d',strtotime("+{$param['next_days']} day ".$stay['date'])).' 14:00:00';
                    break;
                }
               
                
                    
                    if(HotelOrder::checkOrder($reserve_param['date_stay_begin'],$reserve_param['date_stay_finish'],$id))
                    {
                        
                        if($this->user_info['full_rereserve'])
                        {
                             $rand = rand(0,99999);
                             $prodlenie = 'rereserve'.$rand;
                             $switcher = true;
                        }
                        else
                        {
                            $prodlenie=0;
                            $switcher = false;
                        }
                        $model = new HotelOrder;
                        $model->attributes=$reserve_param;
                        $model->date_stay_begin = $reserve_param['date_stay_begin'];
                        $model->date_stay_finish = $reserve_param['date_stay_finish']; 
                        $hotel_check_save = false;               
                        if($model->save($prodlenie,$switcher))
                        {
                            $hotel_check_save=true;
                            $n=0;
                            foreach($users_in_hotel as $user)
                            {
                                $n++;
                                if(trim($user['user_id'])=='' and !isset($this->user_info))                            
                                {
                                   
                                    $id_client = '';
                                    if(($user['user_phone'][0]!='') or ($user['user_name']!='' and $model->TYC==1))
                                    {
                                        $new_client =  new Clients;
                                        $new_client->name = $user['user_name'];
                                        if($new_client->save())
                                        {
                                            foreach ($user['user_phone'] as $phone)
                                            {
                                                if($phone!='')
                                                {
                                                    $new_phone = new Phones;
                                                    $new_phone->id_client = $new_client->id;
                                                    $new_phone->phone = $phone;
                                                    $new_phone->save();
                                                    
                                                }                                            
                                            }
                                        }
                                        $id_client = $new_client->id;
                                    }
                                    else $error .= fnc::returnError("Пользователь №$n не был добавлен, по причине, не указан номер телефона!");
                                }
                                elseif($this->user_info['full_rereserve'])
                                    $id_client = $this->user_info['id_user'];
                                else
                                $id_client = $user['user_id'];
                                
                                if($this->user_info['full_rereserve'])
                                {
                                    $new_client_hotel  = ClientHotel::model()->findByPk($this->user_info['id_clienthotel']);
                                    $ex_hotel_order = $new_client_hotel->id_order;
                                    $new_client_hotel->id_order = $model->id;
                                    if($new_client_hotel->save($prodlenie,true))
                                    {
                                        Alist::model()->deleteByPk($this->user_info['alist']);
                                        unset(Yii::app()->request->cookies['rereserve']);
                                        unset($this->user_info);
                                        
                                        $cookie = new CHttpCookie('hide_panel', true);                                         
                                        Yii::app()->request->cookies['hide_panel'] = $cookie;
            
                                        if(!HotelOrder::checkFreeHome($ex_hotel_order)) HotelOrder::model()->deleteByPk($ex_hotel_order);
                                    }
                                }
                                else
                                {
                                    $new_client_hotel = new ClientHotel;
                                    $new_client_hotel->id_client = $id_client;
                                    $new_client_hotel->id_order = $model->id;
                                    $new_client_hotel->date_stay_begin = $model->date_stay_begin;
                                    $new_client_hotel->date_stay_finish = ($model->TYC==1 ? date('Y-m-d',strtotime($user['user_finish'])).' 14:00:00' : $model->date_stay_finish);                            
                                    $new_client_hotel->status = 0;
                                    $new_client_hotel->from = $user['user_from'];
                                    $new_client_hotel->price_for = $user['user_document'];
                                   
                                    if($new_client_hotel->save())
                                    {
                                        if($model->status == 1 or $model->status == 3 or $model->status == 5 or $model->status == 6)
                                        {
                                            $photes = Phones::model()->findAll("id_client = $new_client_hotel->id_client");
                                            $hotel = Hotels::model()->findByPk($model->id_hotel);
                                            if(count($photes)>0) 
                                            {
                                               
                                                foreach ($photes as $phone)
                                                {
                                                   fnc::sendSMS($model->id,$phone->phone,$hotel->name,$new_client_hotel->date_stay_begin,$model->TYC,true);
                                                } 
                                            }
                                        }
                                        
                                           
                                         
                                        $new_mgt_money = new MgtMoney;
                                        $new_mgt_money->id_clienthotel = $new_client_hotel->id;
                                        $new_mgt_money->cost = $user['user_score'];
                                        $new_mgt_money->date_public = date('Y-m-d H:i:s',strtotime($model->date_stay_begin));
                                        $new_mgt_money->save();
                                        
                                        
                                         
                                            $interval_days = fnc::intervalDays($new_client_hotel->date_stay_begin,$new_client_hotel->date_stay_finish);
                                            
                                            $sum_for_hotel = $interval_days * $user['user_score'];
                                            if($user['user_document']>0) $sum_for_doc = ($user['user_document']-$user['user_score'])*0.1*$interval_days;
                                            $new_tick = new Ticks;
                                            $new_tick->id_clienthotel = $new_client_hotel->id;
                                            $new_tick->date_period_begin = $new_client_hotel->date_stay_begin;
                                            $new_tick->date_period_finish = $new_client_hotel->date_stay_finish;
                                            
                                            $cheker_ho = fnc::checkNeedTick($model->status);
                                            $new_tick->status = ($cheker_ho ? 1 : 0); // Оплачена
                                            $new_tick->finish_sum = ($cheker_ho ? $sum_for_hotel : 0);
                                            $new_tick->id_invite  = $model->id_invite;
                                            $new_tick->sum_for_days = $sum_for_hotel;
                                            $new_tick->sum_for_doc = ($sum_for_doc ? $sum_for_doc : 0);
                                            $new_tick->date_public = date('Y-m-d H:i');
                                            
                                            
                                            
                                            if(!empty($this->user_info))
                                            {
                                                if($model->status==0)
                                                {
                                                    $return_money = MgtMoney::getScoreFixed($this->user_info['id_clienthotel'],$new_client_hotel->date_stay_begin,$new_client_hotel->date_stay_finish);
                                                    if($sum_for_hotel<=$return_money) $new_tick->date_period_finish = $new_client_hotel->date_stay_finish;
                                                    else 
                                                    {
                                                        $resultation_money = round($return_money/$sum_for_hotel*24);
                                                        $new_tick->date_period_finish = date('Y-m-d H:i',strtotime("+$resultation_money hour".$new_client_hotel->date_stay_begin));
                                                    }
                                                    
                                                    $new_tick->finish_sum = ($cheker_ho ? $return_money : 0); 
                                                    $new_tick->sum_for_days = $return_money;
                                                    $new_tick->save();
                                                    
                                                       $last_tick_by_user = Ticks::model()->find(array('condition'=>"id_clienthotel={$this->user_info['id_clienthotel']} and status = 1",'order'=>"date_period_finish DESC"));
                                                    $last_tick_by_user->date_period_finish = $model->date_stay_begin;
                                                    $last_tick_by_user->save();
                                                    
                                                    $money_go_back = new Ticks;
                                                    $money_go_back->id_clienthotel = $this->user_info['id_clienthotel'];
                                                    $money_go_back->date_period_begin = $new_client_hotel->date_stay_begin;
                                                    $money_go_back->date_period_finish = $model->date_stay_begin;
                                                    
                                                   
                                                    $money_go_back->status = 1; // Оплачена
                                                    $money_go_back->finish_sum = -1*$return_money;
                                                   
                                                    $money_go_back->sum_for_days = -1*$return_money;
                                                    $money_go_back->sum_for_doc = 0;
                                                    $money_go_back->date_public = date('Y-m-d H:i');
                                                    $money_go_back->save();
                                                }
                                                
                                                
                                                $new_client_hotel  = ClientHotel::model()->findByPk($this->user_info['id_clienthotel']);
                                             
                                                
                                                Ticks::model()->deleteAll("id_clienthotel={$this->user_info['id_clienthotel']} and status = 0");
                                                $ex_hotel_order = $new_client_hotel->id_order;
                                                $new_client_hotel->date_stay_finish = $model->date_stay_begin;
                                               // $how_days = fnc::intervalDays($new_client_hotel->date_stay_begin,$new_client_hotel->date_stay_finish);
//                                                echo $how_days;die();
                                                if($new_client_hotel->save($prodlenie,true))
                                                {
                                                    Alist::model()->deleteByPk($this->user_info['alist']);
                                                    unset(Yii::app()->request->cookies['rereserve']);
                                                    
                                                    $cookie = new CHttpCookie('hide_panel', true);                                         
                                                    Yii::app()->request->cookies['hide_panel'] = $cookie;
                                                    
                                                    $find_users_living_in_this_hotel = HotelOrder::model()->with('cl_s')->findByPk($ex_hotel_order,"`cl_s`.date_stay_begin<'{$model->date_stay_begin}' and '{$model->date_stay_finish}'<`cl_s`.date_stay_finish");
                        
                                                    if(count($find_users_living_in_this_hotel->cl_s)==0)
                                                    {
                                                        $to_update_HO = HotelOrder::model()->findByPk($ex_hotel_order);
                                                        $to_update_HO->date_stay_finish = $model->date_stay_begin;
                                                        //$to_update_HO->status = 2;
                                                        $to_update_HO->save($prodlenie,true);
                                                    }
                                                }
                                            }
                                            elseif($model->status != 1 and $model->status!=5)
                                            {                                            
                                                if($new_tick->save())
                                                {
                                                    
                                                    if($sum_for_doc and fnc::checkNeedTick($model->status))
                                                    {
                                                        
                                                        $belong_to_home = Hotels::model()->findByPk($model->id);
                                                        $new_document = new Documents;
                                                    
                                                        $new_document->id_invite = $model->id_invite;
                                                        $new_document->date_public = date('Y-m-d H:i:s');
                                                        $new_document->status = 1;
                                                        $new_document->id_clienthotel = ($model->TYC==1 ? $new_client_hotel->id : 0);
                                                        if($new_document->save('created',true,$model->id))  
                                                        {
                                                            
                                                            $price_for_document = new DocumentsPrice;
                                                            $price_for_document->id_document = $new_document->id;
                                                            $price_for_document->node = "ТУЦ ".$belong_to_home->name;
                                                            $price_for_document->price = $sum_for_doc;
                                                            $price_for_document->save();  
                                                        }
                                                    }
                                                }                                            
                                            }
                                        
                                    }
                                
                                }
                                
                                
                            }
                        }
                    }
                    else
                    {
                        $error = fnc::returnError('Пересечение дат');
                    }
                
                
                
            }
        }
        
        if($hotel_check_save)
        {
            if(fnc::definePlatformPC())
                $this->render('fancyclose');
            else
                $this->redirect('/');
            die("Идёт переадресация...");
        }
        
        if(Yii::app()->request->isAjaxRequest)
        {
            $home_type = $_POST['home_type'];
            $places = $_POST['places'];
            if(is_numeric($home_type))
            {               
                 $this->renderPartial('_user_form', array('TYC'=>$home_type,'places'=>$places,'date'=>$date), false, true);
            }
            die();
        }
        
        $platform = fnc::definePlatformPC();
        if($type)
        {
           
            $hidden_blog_post_pay=false;
            $model = new HotelOrder;
            $hotel = Hotels::model()->findByPk($id);
            $model->id_hotel = $hotel->id;
            $infodate = fnc::getRealDay(date('D',strtotime($date)))." ".$date;            
            
            
            switch($type)
            {
                case 'with_money':
                   $caption = "Забронировать с оплатой {$hotel->name}";                  
                   $status = 0;
                break;
                case 'nomoney':
                   $caption = "Бронирование без оплаты {$hotel->name}";
                   $status = 1;
                break;
                case 'halfmoney':
                   $caption = "Бронирование с предоплатой {$hotel->name}";
                   $hidden_blog_post_pay = true;
                   $model->tmp_halfmoney = 0;
                   $model->tmp_halfdate = date('Y-m-d H:i');
                   $status = 3;
                break;
            }
            $date_to_sql = date('Y-m-d',strtotime($date));
            
            // ЕСЛИ ПЕРЕСЕЛЕНИЕ ТО ЗАГРУЖАЕМ ДАННЫЕ ИЗ КУКОВ
            if(isset($this->user_info['time']))
                $found_on_this_day = $this->user_info['time'];
            else
                $found_on_this_day = HotelOrder::model()->find(array('condition'=>"id_hotel = $id and date(date_stay_finish)='$date_to_sql'",'order'=>"id DESC",'limit'=>1));   
            
            if(isset($this->user_info['date'])) $date = $this->user_info['date'];
            
            // КОЦЕН ЗАГРУЗКИ ДАННЫХ ИЗ КУКОВ
            
            $model->TYC = ($TYC=='true' ? 1 : 0);
            if($model->TYC==0) 
            {
                $model->TYC = $hotel->default_type;
            }
            
           
            
            $model->status = $status;
            $model->id_invite = $hotel->default_host;
            $model->create_time =  time();
            $this->render('with_money',
                                array(
                                    'platform'=>$platform,
                                    'id_hotel'=>$id,
                                    'hotel'=>$hotel,
                                    'date'=>$date,
                                    'model'=>$model,
                                    'caption'=>$caption,
                                    'infodate'=>$infodate,
                                    'error'=>$error,
                                    'hidden_blog_post_pay'=>$hidden_blog_post_pay,
                                    'date_for_picker'=>(isset($found_on_this_day->date_stay_finish) ? date('H:i',strtotime($found_on_this_day->date_stay_finish)) : date('H:i'))
                                )
                            );
        }
        else
        {
            $this->render('index',
                array(
                    'platform'=>$platform,
                    'id_hotel'=>$id,
                    'date'=>$date,
                    
                )
            );
        }
    }
    
     public function actionReport($date,$type)
     {
        if(!Users::getDostup(5)) 
        {
            throw new CHttpException(403,'Недостаточно прав доступа');
            die();
        }
        
        
        
        switch ($type)
        {
            case 'by_day':
            
            $tmn_date = date('Y-m-d',strtotime($date));

            $connection=Yii::app()->db; // так можно сделать, если в конфигурации описан компонент соединения "db"
            $SQL = "select `o`.id_invite,sum(finish_sum),(sum(sum_for_days)) as sum_for_days from `hotel_order` `o` left join `client_hotel` `c` on `c`.id_order=`o`.id left join `ticks` `t` on `t`.id_clienthotel=`c`.id where date(date_public)='$tmn_date' and `t`.status!=5 group by `o`.id_invite";
            $command=$connection->createCommand($SQL);  
            $report=$command->query();
            
            $SQL_goback = "select `o`.id_invite,sum(finish_sum),(sum(sum_for_days)+sum(sum_for_doc)) as sum_for_days from `hotel_order` `o` left join `client_hotel` `c` on `c`.id_order=`o`.id left join `ticks` `t` on `t`.id_clienthotel=`c`.id where date(date_public)='$tmn_date' and `t`.status=5 group by `o`.id_invite";
            $command_goback=$connection->createCommand($SQL_goback);  
            $report_goback=$command_goback->query();
            
            $current_date =  date('Y-m-d',strtotime($date));
            $report_docs = Documents::model()->findAll(array('condition'=>"date(date_public)='$current_date' and status = 1",'group'=>'id_invite','select'=>"sum(sum_docs) as sum_docs,id_invite"));
            
            
            $payments = PaymentsOrder::model()->findAll(array('condition'=>"date(date_public)='$current_date'",'group'=>'id_invite','select'=>"sum(price) as price,id_invite"));
                  
                   
                    $this->renderPartial('report',array(
		            'date'=>$date,
                    'type'=>$type,
                    'report'=>$report,
                    'payments'=>$payments,
                  'docs'=>$report_docs,
                  'goback'=>$report_goback,
                  'model'=>$model,
                   	));
            break;

        }
        
     }
     
     public function actionGetLastChange($since,$to,$user_time=0,$left=0)
    {
        if(!Users::getDostup(5)) 
        {
            throw new CHttpException(403,'Недостаточно прав доступа');
            die();
        }
        
        $count_order = HotelOrder::model()->count();
        if($count_order>0)
            $create_time = HotelOrder::model()->find(array('order'=>'create_time DESC'))->getAttribute('create_time');
        
        
        if($user_time<$create_time or $user_time==0)
        {
            $user = new Users;
            $id_user = $user->getMyId();
            $this->alist = Alist::model()->findAll(array('condition'=>"id_user = $id_user and status = 0",'order'=>'id DESC')); 
            if(count($this->alist)>0)
            {
                $alists="<div id='actions_list'>";
                foreach ($this->alist as $alist)
                {
                    $alists .= "<div class='query' rel='$alist->id'>
                            <div class='close' rel='$alist->id'></div>            
                            <div class='info $alist->post_type'>
                                $alist->short_desc
                            </div>            
                            <div class='panel'>
                                <a class='left' href='javascript:void(0);'>Переселить</a>
                            </div>            
                    </div>";
               }
               $alists.='</div>';
            }
            
            
            
            
            
            $check_rereserved = (Yii::app()->request->cookies['hide_panel']==1 ? 'RESERVED' : '');
            unset(Yii::app()->request->cookies['hide_panel']);
                      
            $this->renderPartial('/site/_calendar', array('days_back'=>$since,'days_prev'=>$to,'left'=>$left), false, true);
            echo CJSON::encode('DELENIE:'.time());      
            echo 'DELENIE:'.$alists;     
            echo 'DELENIE:'.$check_rereserved;   
        }
    }
    
    
    public function actionMonitoring($id,$date)
    {
        if(!Users::getDostup(5)) 
        {
            throw new CHttpException(403,'Недостаточно прав доступа');
            die();
        }
        
        if(isset($_POST['type']))
        {
            $rand = rand(0,99999);
            $prodlenie = 'redaktirovanie'.$rand;
                        
            $type = $_POST['type'];
            switch ($type)
            {
                case 'sms':
                      
                    $now = date('Y-m-d H:i');
                    
                    
                    $sms_to_remember = Sms::model()->findAll(array('condition'=>"id_order=$id and status = 0"));
                    
                        foreach($sms_to_remember as $sms_send)
                        {
                            fnc::sendSMS(0,$sms_send->phone,$sms_send->street,"1970-01-01",$sms_send->city,true,false);
                            $sms_send->status = 1;
                            $sms_send->save();
                        }
                        echo "OK";
                    
                break;
                
                case 'resetting':
                    $HO = HotelOrder::model()->findByPk($id);
                    $check_users = false;
                    
                if(isset($_POST['resetting']['begin']['date'],$_POST['resetting']['begin']['time'],$_POST['resetting']['finish']['date'],$_POST['resetting']['finish']['time']))
                {
                    $date_stay_begin = date('Y-m-d',strtotime($_POST['resetting']['begin']['date']));
                    $date_stay_begin .= ' '.$_POST['resetting']['begin']['time'];                    
                    $date_stay_finish = date('Y-m-d',strtotime($_POST['resetting']['finish']['date']));
                    $date_stay_finish .= ' '.$_POST['resetting']['finish']['time'];
                    
                    $free_space = HotelOrder::model()->count("id!= {$id} and id_hotel = {$HO->id_hotel} and ( ( date_stay_begin <'{$date_stay_finish}' and '{$date_stay_finish}'<=date_stay_finish ) or ( '{$date_stay_begin}'<=date_stay_begin and date_stay_finish<='{$date_stay_finish}' ) )"); 
                    if($free_space==0)
                    {
                        $HO->date_stay_finish = $date_stay_finish;
                        $HO->date_stay_begin = $date_stay_begin;
                        $check_users = true;
                    }
                    
                }
                    
                    $status  = $_POST['resetting']['status'];
                    $HO->status = $status;
                    
                    $time = time();
                    $HO->create_time = $time;  
                    $HO->save($prodlenie,true);
                    
                    if($check_users)
                    {
                        $cls = ClientHotel::model()->findAll("id_order = $id");
                        foreach ($cls as $cl)
                        {
                            $cl->date_stay_begin = $date_stay_begin;
                            $cl->date_stay_finish = $date_stay_finish;
                            if($cl->save($prodlenie,true))
                            {
                                Ticks::model()->deleteAll("id_clienthotel={$cl->id} and status = 0");
                            }
                        }
                    }
                    
                    
                                                            
                   if(fnc::definePlatformPC())
                        $this->render('fancyclose');
                    else
                        $this->redirect('/');
                    die("Идёт переадресация...");
                    
                break;
                
                case 'edit_clean':
                    $HO = HotelOrder::model()->findByPk($id);
                    if(isset($_POST['go_later']))
                    {
                        $my_next_date = date('Y-m-d',strtotime($_POST['clean']['date_cleaning']));
                    }
                    elseif($_POST['clean_complete'])
                    {
                        $today = date('Y-m-d');
                        $my_next_date = date('Y-m-d',strtotime("+3 days ".$today));
                    }
            
                    $HO->date_cleaning = $my_next_date;
                    $time = time();
                    $HO->create_time = $time;  
                    $HO->save($prodlenie,true);
               
                    
                break;
                
                case 'ring':
                    $time = time();
                    HotelOrder::model()->updateByPk($id,array('ring'=>0,'create_time'=>$time));
                    $this->render('fancyclose');
                    die();
                break;
                case 'edit':
                    if($_POST['remember']['date']!='' and $_POST['remember']['time']!='')
                        $_POST['edit']['remember_time'] = date('Y-m-d H:i',strtotime($_POST['remember']['date'].' '.$_POST['remember']['time']));
                   
                   $model = HotelOrder::model()->findByPk($id); // Получили заказ, который мы просматриваем
                   HotelOrder::updateTime();
                   foreach ($_POST['edit'] as $key=>$value)
                        $model->$key =$value;
                  
                   if($model->save($prodlenie,true))
                   {
                   //     $this->render('fancyclose');
//                        die();
                   }
                break;
                
                case 'TYC':                
                    $days_cont = $_POST['tyc']['days'];                    
                    if(is_numeric($days_cont))
                    {
                        $rand = rand(0,99999);
                        $prodlenie = 'prodlenie_tyc'.$rand;
                        $ho = HotelOrder::model()->findByPk($id);                        
                        $finish_date = date('Y-m-d H:i',strtotime("+$days_cont day".$ho->date_stay_finish));
                        $time = time();
                        
                        
                        if(HotelOrder::model()->updateByPk($id,array('date_stay_finish'=>$finish_date,'create_time'=>$time),"",array(),1,$prodlenie))
                        {
                            $list_ids = ClientHotel::model()->findAll("id_order = $id");
                            foreach ($list_ids as $ids)
                            {
                                ClientHotel::model()->updateByPk($ids->id,array('date_stay_finish'=>$finish_date),"",array(),1,$prodlenie);
                            }                            
                            
                                $this->render('fancyclose');
                                die();
                            
                            
                        }
                    }
                    else die('Не правильный формат "Колличество дней"');
                break;
            }
        }
         
        
        $model = HotelOrder::model()->findByPk($id); // Получили заказ, который мы просматриваем
        if(is_object($model))
        {
            
        
            $hotel = Hotels::model()->findByPk($model->id_hotel); // Получили квартиру для этого заказа
            $day_eng = date('D',strtotime($date)); // Получаем день недели на английском   
            
            $information = array(); // создаём пустой массив с информацией
            $tmp_ch = ClientHotel::model()->findAll("id_order=$id");
            foreach ($tmp_ch as $ch)
                $ids_ch .= $ch->id.',';
            if(strlen($ids_ch)>0) $ids_ch = substr($ids_ch,0,-1);
            
            $information['h1'] = $hotel->name;
            $information['h2'] = fnc::getRealDay($day_eng)." ".$date;
            $information['remember_date'] = date('d.m.Y',strtotime($model->remember_time));
            $information['remember_time'] = date('H:i',strtotime($model->remember_time));
            $information['begin_date'] = date('d.m.Y',strtotime($model->date_stay_begin));
            $information['begin_time'] = date('H:i',strtotime($model->date_stay_begin));
            $information['finish_date'] = date('d.m.Y',strtotime($model->date_stay_finish));
            $information['finish_time'] = date('H:i',strtotime($model->date_stay_finish));
            $information['hotel_category'] = $hotel->id_cat;
            $information['show_remember'] = false;
            $information['sms_send'] = Sms::model()->find("id_order = $id");
            $information['date'] = $date;
            $information['date_cleaning'] = date('d.m.Y',strtotime($model->date_cleaning));
            $information['date_sql'] = $date_sql = date('Y-m-d',strtotime($date)); // Дата в SQL формате
            $information['freeusers'] = $model->places - ClientHotel::model()->count("status = 0 and id_order = $id and date(date_stay_begin)<='{$information['date_sql']}' and '{$information['date_sql']}' <= date(date_stay_finish)");
            
            
            if($ids_ch>0)
            {
                $information['get_money'] = Ticks::model()->find(array('select'=>"sum(finish_sum) as finish_sum",'condition'=>"id_clienthotel in ($ids_ch) and status=1"))->finish_sum;
                $information['remove_money'] = Ticks::model()->find(array('select'=>"sum(finish_sum) as finish_sum",'condition'=>"id_clienthotel in ($ids_ch) and status=5"))->finish_sum;
            }
            
            $information['cnt_on_this_date'] = HotelOrder::model()->count("id_hotel={$model->id_hotel} and (date(date_stay_begin)='$date_sql' or date(date_stay_finish)='$date_sql')");
            if($information['remember_date']==$date) $information['show_remember']=true;     
            
            $users = ClientHotel::model()->findAll(array('condition'=>"id_order=$id and date(date_stay_begin)<='{$information['date_sql']}' and '{$information['date_sql']}' <= date(date_stay_finish)",'order'=>'status ASC,date_stay_finish DESC'));  
           
          	$this->render('monitoring',array(
    			'model'=>$model,     
                'info'=>$information,   
                'users'=>$users,   
                
    		));
        
        } else throw new CHttpException(400,'Заказ уже был удалён или было произведена отмена последнего действия. Обновите страницу!');
    }
    
    public function actionAddMGTMoney($date,$id_clienthotel)
    {
        if(!Users::getDostup(5)) 
        {
            throw new CHttpException(403,'Недостаточно прав доступа');
            die();
        }
        $loader = false;
         
        if(isset($_POST['mgt']))
        {
            $getted_sql_date = date('Y-m-d',strtotime($_POST['mgt']['date_public']));
            
            $mgt = MgtMoney::model()->find(array("condition"=>"date(date_public)<='$getted_sql_date' and id_clienthotel=$id_clienthotel",'order'=>'id DESC'));
          
           
            if($mgt->cost!=$_POST['mgt']['cost'])
            {
                
                
               
                $mgt_sql_date = date('Y-m-d',strtotime($mgt->date_public));
            
                
                if($mgt_sql_date == $getted_sql_date)
                    $mgt->delete();
                $model = new MgtMoney;
                $model->attributes=$_POST['mgt'];
                if($model->save())
                {
                    $ticks_free = Ticks::model()->findAll(array('condition'=>"id_clienthotel = :id_clienthotel and status = 0",'params'=>array(':id_clienthotel'=>$model->id_clienthotel)));
                    if(count($ticks_free)>0)
                    {
                        foreach ($ticks_free as $tick) $tick->delete();
                    }
                   
                }
            }   
            $loader = true;
        }
        if(isset($_POST['cl']))
        {
            $cl = ClientHotel::model()->findByPk($id_clienthotel);
            $cl->from = $_POST['cl']['from'];
            $cl->price_for = $_POST['cl']['price_for'];
            $cl->save();
            $loader = true;
        }
        if($loader)
        {
            if(fnc::definePlatformPC())
                        $this->render('fancyclose');
                    else
                        $this->redirect('/');
                    die("Идёт переадресация...");
            
        }
         $this->render('_form_mgt',array('date'=>$date,'id_clienthotel'=>$id_clienthotel));
    }
    
        public function actionFastUpdate($id_order,$date)
         {
            if(!Users::getDostup(5)) 
        {
            throw new CHttpException(403,'Недостаточно прав доступа');
            die();
        }
        
             
            if(isset($_SERVER['HTTP_X_REQUESTED_WITH']) && !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') 
            {
            $model = $this->loadModel($id_order);
            $users = ClientHotel::model()->findAll(array('condition'=>"id_order=$id_order",'order'=>'status ASC, date_stay_finish DESC'));
            
            $this->renderPartial('_live_users',array('users'=>$users,'model'=>$model,'date'=>$date,'reloadscript'=>1));
                die();
            }           
            
         }
         
         
         public function actionEviction($id_order,$id_user,$type,$date,$id_clienthotel=false)
         {
            if(!Users::getDostup(5)) 
        {
            throw new CHttpException(403,'Недостаточно прав доступа');
            die();
        }
             
            
            $rand = rand(0,99999);
            $prodlenie = 'oplata'.$rand;
            
            $date_sql = date('Y-m-d',strtotime($date));
          //  fnc::mpr($_GET);die();
            switch ($type)
            {
                case 'exit':
                    $prodlenie = 'viselenie'.$rand;
                    $ClientHotel = ClientHotel::model()->findByPk($id_clienthotel);
                    $ClientHotel->date_stay_finish = $date_sql.' 14:00:00';
                    $ClientHotel->status = 1;
                    if($ClientHotel->save($prodlenie,true))
                    {
                        $how_users = ClientHotel::model()->count(array('condition'=>"status = 0 and id_order = {$id_order}"));
                        if($how_users==0)
                        {
                            $HO = HotelOrder::model()->findByPk($id_order);
                            $HO->date_stay_finish = $date_sql.' 14:00:00';
                            $HO->status = 2;
                            $HO->create_time = time();                            
                            $HO->remember_time = '0000-00-00 00:00:00';
                            $HO->broken_begin = '0000-00-00 00:00:00';
                            $HO->broken_finish = '0000-00-00 00:00:00';
                            $HO->update(null,$prodlenie);
                            
                            $get_hotel = Hotels::model()->findByPk($HO->id_hotel);
                            $get_hotel->dirty=1;
                            $get_hotel->save($prodlenie,true,$HO->id);
                        }
                    }
                    
                    if(fnc::definePlatformPC())
                        $this->render('fancyclose');
                    else
                        echo "<script type='text/javascript'>window.location='/';</script>";
                    die("Идёт переадресация...");
                break;
                
                case 'exit_with_money':
                       if(isset($_POST['return']))
                     {
                      // fnc::mpr($_POST);die();
                        $rand = rand(0,99999);
                        $prodlenie = 'pererashet_exit'.$rand;
                        $time = time();
                        HotelOrder::updateTime();
                               
                        $model=new Ticks;    
    		        $model->attributes=$_POST['return'];
                        
                         
                        $model->status=5;
                        $model->sum_for_days = $model->sum_for_days*-1;
                        $model->finish_sum = $model->sum_for_days+$model->sum_for_doc;
                        $model->note = "Выселение с перерасчётом";
                        
                        $model->date_public = date('Y-m-d H:i');
                        
                        //echo $model->id_invite;die();
                        
                        if($model->save($prodlenie,true,$id_order))
                        {
                           
                                //$id_hotel =  HotelOrder::model()->findByPk($id_order)->id_hotel;
                                
                                $ClientHotel = ClientHotel::model()->findByPk($_POST['return']['id_clienthotel']);
                              //  echo $_POST['return']['date_period_begin'].':00';die();
                                $ClientHotel->date_stay_finish = $_POST['return']['date_period_begin'].':00';
                                $ClientHotel->status = 1;
                                if($ClientHotel->save($prodlenie,true))
                                {
                                    $how_users = ClientHotel::model()->count(array('condition'=>"status = 0 and id_order = {$id_order}"));
                                    if($how_users==0)
                                    {
                                        $HO = HotelOrder::model()->findByPk($id_order);
                                      //  echo $HO->date_stay_finish;
                                        $HO->date_stay_finish = $_POST['return']['date_period_begin'].':00';
                                      //  echo $HO->date_stay_finish;die();
                                        $HO->status = 2;
                                        $HO->create_time = time();                            
                                        $HO->remember_time = '0000-00-00 00:00:00';
                                        $HO->broken_begin = '0000-00-00 00:00:00';
                                        $HO->broken_finish = '0000-00-00 00:00:00';
                                        if($HO->save($prodlenie,true))
                                        {
                                            $hotel = Hotels::model()->findByPk($HO->id_hotel);
                                            $hotel->dirty = 1;
                                            $hotel->save($prodlenie,true,$HO->id);
                                        }
                                       
                                    }
                                }
                                
                              //  $client = ClientHotel::model()->find(array('condition'=>"id_order=$id_order and id_client=$id_user and date(date_stay_begin)<='$today' and '$today'<=date(date_stay_finish)"));
//                                
//                                ClientHotel::model()->updateByPk($client->id,array('status'=>1,'date_stay_finish'=>$today_with_time),"",array(),1,$comment);
                                
                           //     $cnt_clients = ClientHotel::model()->count(array('condition'=>"id_order=$id_order and status=0"));
//                                
//                                if($cnt_clients==0)
//                                {
//                                    Hotels::model()->updateByPk($id_hotel,array('dirty'=>1));
//                                    HotelOrder::model()->updateByPk($id_order,array('date_stay_finish'=>$today_with_time,'status'=>2),"",array(),1,$comment);
//                                }
              
                                  
                        }
                        if(fnc::definePlatformPC())
                        $this->render('fancyclose');
                            else
                                $this->redirect('/');
                            die("Идёт переадресация...");
                     }else
                     {
                        
                        $ClientHotel = ClientHotel::model()->find(array('condition'=>"id_order=$id_order and id_client=$id_user",'order'=>'ID DESC'));
                        $TICK = Ticks::model()->find(array('condition'=>"id_clienthotel={$ClientHotel->id}",'order'=>'id desc','select'=>"sum(finish_sum) as finish_sum,id_invite"));
                       
                        
                        $DOLG = HotelOrder::model()->find(array('condition'=>"id=$id_order",'select'=>"to_days(date_stay_finish)-to_days(now()) as `status`"));
                       
                        $return_sum = MgtMoney::getScore($ClientHotel->id,date('Y-m-d'));
                        $begin = date('Y-m-d H:i');
                        $finish = $ClientHotel->date_stay_finish;
                        $this->render('_with_money',array('id_clienthotel'=>$ClientHotel->id,'begin'=>$begin,'finish'=>$finish,'return_days'=>$DOLG->status,'sum_all_days'=>$TICK->finish_sum,'return_sum'=>$return_sum,'id_invite_last'=>$TICK->id_invite));
                     }
                    
                    die();
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
                    
                    $this->render('changer',array('form'=>$loadForm));
                    die();
                break;
                
                case 'got_money':
                    $all_unpay_ticks = Ticks::model()->findAll("id_clienthotel = {$id_clienthotel} and status = 0");
                    
                    $HO = HotelOrder::model()->findByPk($id_order);
                  
                    if(count($all_unpay_ticks)>0)
                    {
                        
                        
                        foreach ($all_unpay_ticks as $ticks_on_pay)
                        {
//                            $ticks_on_pay->finish_sum = round($ticks_on_pay->sum_for_days,-2);
//                            $ticks_on_pay->sum_for_days = round($ticks_on_pay->sum_for_days,-2);
                            $ticks_on_pay->finish_sum = $ticks_on_pay->sum_for_days;
                            $ticks_on_pay->sum_for_days = $ticks_on_pay->sum_for_days;
                            $ticks_on_pay->status = ($HO->TYC==1 ? 6 : 1);
                            $ticks_on_pay->date_public = date('Y-m-d H:i:s');
                            
                            if($ticks_on_pay->sum_for_doc>0)                                
                            {
                                $belong_to_home = Hotels::model()->findByPk($HO->id_hotel);
                                $new_document = new Documents;
                               // $new_document->node = "ТУЦ ".$belong_to_home->name;
                               // $new_document->sum_docs = round($ticks_on_pay->sum_for_doc,-2);
                                $new_document->id_invite = $HO->id_invite;
                                $new_document->date_public = date('Y-m-d H:i:s');
                                $new_document->status = 0;
                                $new_document->id_clienthotel = ($HO->TYC==1 ? $ticks_on_pay->id_clienthotel : 0);
                                if($new_document->save($prodlenie,true,$id_order))
                                {
                                    $price_for_document = new DocumentsPrice;
                                    $price_for_document->id_document = $new_document->id;
                                    $price_for_document->node = "ТУЦ ".$belong_to_home->name;
                                   // $price_for_document->price = round($ticks_on_pay->sum_for_doc,-2);
                                    $price_for_document->price = $ticks_on_pay->sum_for_doc;
                                    $price_for_document->save();
                                }
                            }
                            
                            $ticks_on_pay->save($prodlenie,true,$id_order);
                        }
                        
                    }
                    else
                    {
                        $cl = ClientHotel::model()->findByPk($id_clienthotel);
                        
                        $get_money = MgtMoney::getScore($id_clienthotel);         
                        $sum_for_docs = MgtMoney::getScoreForDoc($id_clienthotel);       
                                 
                       $sum_for_days = $get_money;
                                              
                       $new_tick = new Ticks;
                       $new_tick->id_clienthotel = $id_clienthotel;
                       $new_tick->id_invite = $HO->id_invite;
                       $new_tick->date_period_begin = $cl->date_stay_begin;
                       $new_tick->date_period_finish = $cl->date_stay_finish;
                       $new_tick->status = ($HO->TYC==1 ? 6 : 1);
                       $new_tick->finish_sum = $sum_for_days;
                       $new_tick->sum_for_days = $sum_for_days;
                       $new_tick->date_public = date('Y-m-d H:i:s');
                       $new_tick->save($prodlenie,true,$id_order);
                      
                       if($cl->price_for>0)                                
                        {
                            
                       // $sum_with_doc = round(($sum_for_docs*0.1),-2);
                        $sum_with_doc = ($sum_for_docs*0.1);
                                                
                          //  $sum_with_doc = round((($cl->price_for*$interval_1) - $get_money) * 0.1,-2);
                            $belong_to_home = Hotels::model()->findByPk($HO->id_hotel);
                            $new_document = new Documents;
                            
                            $new_document->id_invite = $HO->id_invite;
                            $new_document->status = 0;
                            $new_document->date_public = date('Y-m-d H:i:s');
                            $new_document->id_clienthotel = ($HO->TYC==1 ? $id_clienthotel : 0);
                            if($new_document->save($prodlenie,true,$id_order))
                                {
                                    $price_for_document = new DocumentsPrice;
                                    $price_for_document->id_document = $new_document->id;
                                    $price_for_document->node = "ТУЦ ".$belong_to_home->name;
                                    $price_for_document->price = $sum_with_doc;
                                    $price_for_document->save();
                                }
                        }
                    }
                    
                       
                        $how_users = ClientHotel::model()->findAll(array('condition'=>"status = 0 and id_order = {$id_order}"));
                       
                        if(count($how_users)>0)
                        {
                            
                            $ready_to_update_ho = true; 
                            $all_right=true;
                            foreach ($how_users as $cl) 
                            {
                                $find_ticket = Ticks::model()->find(array("condition"=>"`t`.id_clienthotel = $cl->id",'select'=>"`t`.date_period_begin, (select `p`.date_period_finish from `ticks` `p` where `p`.id_clienthotel = $cl->id order by `p`.date_period_finish DESC limit 1) as date_period_finish",'order'=>'`t`.date_period_begin ASC'));
                             
                                if(strtotime($find_ticket->date_period_begin) != strtotime($cl->date_stay_begin) or strtotime($find_ticket->date_period_finish) != strtotime($cl->date_stay_finish))                                 
                                    $all_right=false;                                 
                                 
                               
                            }
                            if($all_right)
                            {
                                if($HO->TYC==1)
                                    $HO->status=4;
                                else
                                    $HO->status=0;
                            }
                        }
                      
                       
                        if($ready_to_update_ho)
                        {
                                $HO->create_time = time();
                               // $HO->status = ($HO->TYC==1 ? 4 : 0);
                                $HO->remember_time = '0000-00-00 00:00:00';
                                $HO->broken_begin = '0000-00-00 00:00:00';
                                $HO->broken_finish = '0000-00-00 00:00:00';
                                $HO->update(null,$prodlenie);   
                        }
                    
                    
                break;
            }
            
            if(fnc::definePlatformPC())
            {
                if($HO->TYC==1)
                    $this->redirect($_SERVER["HTTP_REFERER"]);
                else                
                    $this->render('fancyclose');
            }
            else
                $this->redirect('/');
            die("Идёт переадресация...");
            
         }
         
         public function actionExtend()
         {
            if(!Users::getDostup(5)) 
            {
                throw new CHttpException(403,'Недостаточно прав доступа');
                die();
            }
             
            
            $rand = rand(0,99999);
            $prodlenie = 'prodlenie'.$rand;
            $hours = ($_POST['switcher']=='undefined' ? 0 : 1);
            $message_error = '';
            $cont_days = $_POST['days'];
            $errors = 'OK';
            if(count($_POST['g_user'])>0)
            {
                
                if(count($_POST['g_user']['newLive'])>0)
                {
                    foreach ($_POST['g_user']['newLive'] as $id_clienthotel)
                    {
                        
                            $cnt_ticks = Ticks::model()->count("id_clienthotel = {$id_clienthotel} and status = 0");
                            $client_hotel = ClientHotel::model()->findByPk($id_clienthotel);
                            if($cnt_ticks>0)
                            {                                
                                $last_phone = Phones::model()->find(array('condition'=>"id_client = {$client_hotel->id_client}",'order'=>'id DESC'));
                                echo "У пользователя с номером телефона [{$last_phone->phone}], имеются не оплаченные счета, его проживание не было продленно";
                                $errors='';
                                continue;
                            }
                            $HO = HotelOrder::model()->findByPk($client_hotel->id_order);
                            
                            $last_tick = Ticks::model()->find(array('order'=>'date_period_finish DESC','condition'=>"id_clienthotel = {$id_clienthotel}"));
                            $date_period_begin = $last_tick->date_period_finish;
                            
                            if($hours==0)
                                $date_period_finish = date('Y-m-d',strtotime("+$cont_days day ".$date_period_begin)).' 14:00:00';
                            else
                                $date_period_finish = date('Y-m-d H:i',strtotime("+$cont_days hours ".$date_period_begin));
                            
                            $free_space = HotelOrder::model()->count("id_hotel = {$HO->id_hotel} and ( ( date_stay_begin <'$date_period_finish' and '$date_period_finish'<=date_stay_finish ) or ( '$date_period_begin'<=date_stay_begin and date_stay_finish<='$date_period_finish' ) )");
                            
                            if($free_space==0)
                            {
                                $client_hotel->date_stay_finish = $date_period_finish;
                                if($client_hotel->save($prodlenie,true))
                                {
                                    
                                    if($HO->date_stay_finish<$date_period_finish)
                                    {
                                        $HO->date_stay_finish = $date_period_finish;
                                        $HO->create_time = time();
                                        $HO->update(null,$prodlenie);
                                    }
                                    $new_tick = new Ticks;
                                    $new_tick->id_clienthotel = $id_clienthotel;
                                    $new_tick->id_invite = $HO->id_invite;
                                    $new_tick->date_period_begin = $date_period_begin;
                                    $new_tick->date_period_finish = $date_period_finish;
                                    $new_tick->status = 1;
                                    
                                    $interval_days = fnc::intervalDays($date_period_begin,$date_period_finish);
                                    if($hours==0)
                                    {
                                        $cost_on_this_day = MgtMoney::model()->find(array("condition"=>"date(date_public)<='$date_period_begin' and id_clienthotel=$id_clienthotel",'order'=>'date_public DESC'))->cost;
                                        if($client_hotel->price_for > 0) $sum_for_doc = ($client_hotel->price_for-$cost_on_this_day)*0.1*$interval_days;
                                        else $sum_for_doc=0;
                                    }
                                    else $cost_on_this_day=500;
                                    
                                    
                                    $new_tick->finish_sum = $cost_on_this_day*$interval_days;
                                    $new_tick->sum_for_days = $cost_on_this_day*$interval_days;
                                    $new_tick->sum_for_doc = $sum_for_doc;
                                    $new_tick->date_public = date('Y-m-d H:i:s');
                                    $new_tick->save($prodlenie,true,$HO->id);
                                    
                                }
                            }
                            else 
                            {
                                $errors = "При продлении получается пересечение дат, продление НЕВОЗМОЖНО!";
                               
                                break;
                            }
                            
                    }
                }
                
               
                
                if(count($_POST['g_user']['no_newLive'])>0)
                {
                    foreach ($_POST['g_user']['no_newLive'] as $id_clienthotel)
                    {
                            if($_POST['remember']!=='undefined' and $_POST['remember_date']!=='undefined')
                            {
                               $remember_post_date = date("Y-m-d H:i:s",strtotime($_POST['remember_date'].' '.$_POST['remember']));
                            }
                             
                           
                            $client_hotel = ClientHotel::model()->findByPk($id_clienthotel);
                            $HO = HotelOrder::model()->findByPk($client_hotel->id_order);
                            
                            $last_tick = Ticks::model()->find(array('order'=>'date_period_finish DESC','condition'=>"id_clienthotel = {$id_clienthotel}"));
                            
                            $date_period_begin = $last_tick->date_period_finish;
                            
                           if($hours==0)
                                $date_period_finish = date('Y-m-d',strtotime("+$cont_days day ".$date_period_begin)).' 14:00:00';
                            else
                                $date_period_finish = date('Y-m-d H:i',strtotime("+$cont_days hours ".$date_period_begin));
                            
                             $free_space = HotelOrder::model()->count("id_hotel = {$HO->id_hotel} and ( ( `date_stay_begin` <'$date_period_finish' and '$date_period_finish'<=`date_stay_finish` ) or ( '$date_period_begin'<=`date_stay_begin` and `date_stay_finish`<='$date_period_finish' ) )");
                            
                            if($free_space==0)
                            {
                                $client_hotel->date_stay_finish = $date_period_finish;
                                if($client_hotel->save($prodlenie,true))
                                {
                                    
                                    if(strtotime($HO->date_stay_finish)<strtotime($date_period_finish))
                                    {
                                       
                                        $HO->date_stay_finish = $date_period_finish;
                                        
                                        
                                        
                                            if(isset($remember_post_date)) 
                                            {
                                                $HO->remember_time = $remember_post_date;
                                                $HO->broken_begin = $date_period_begin;
                                                $HO->broken_finish = $date_period_finish;
                                            }
                                        
                                        
                                      //  $HO->status = ($HO->TYC==1 ? 5 : 1);
                                        $HO->create_time = time();
                                        
                                       // HotelOrder::model()->updateByPk($id_order,array('date_stay_finish'=>$finish_date,'create_time'=>$time),"",array(),1,$prodlenie);
                                        $HO->update(null,$prodlenie);
                                        
                                    }
                                    $new_tick = new Ticks;
                                    $new_tick->id_clienthotel = $id_clienthotel;
                                    $new_tick->id_invite = $HO->id_invite;
                                    $new_tick->date_period_begin = $date_period_begin;
                                    $new_tick->date_period_finish = $date_period_finish;
                                    $new_tick->status = 0;
                                    
                                    $interval_days = fnc::intervalDays($date_period_begin,$date_period_finish);
                                    if($hours==0)
                                    {
                                        $cost_on_this_day = MgtMoney::model()->find(array("condition"=>"date(date_public)<='$date_period_begin' and id_clienthotel=$id_clienthotel",'order'=>'date_public DESC'))->cost;
                                        if($client_hotel->price_for > 0) $sum_for_doc = ($client_hotel->price_for-$cost_on_this_day)*0.1*$interval_days;
                                        else $sum_for_doc=0;
                                    }
                                    else $cost_on_this_day=500;
                                    
                                    $new_tick->finish_sum = 0;
                                    $new_tick->sum_for_days = $cost_on_this_day*$interval_days;
                                    $new_tick->sum_for_doc = $sum_for_doc;
                                    $new_tick->date_public = date('Y-m-d H:i:s');
                                    $new_tick->save($prodlenie,true,$HO->id);                                
                                }
                             }
                             else
                             {
                                $errors = "При продлении получается пересечение дат, продление НЕВОЗМОЖНО!";
                                break;
                             }
                            
                    }
                }
                HotelOrder::updateTime();
            }
            else $errors = 'Выберите пользователя для продления';
            
            
            if($errors=='OK')
            {
                $new_extension = new ExtensionOrder;
                $new_extension->id_order = $client_hotel->id_order;
                $new_extension->date_public = $date_period_begin;
                $new_extension->save($prodlenie,true);
                
                if(!fnc::definePlatformPC()) $errors ='redirect';
                
                         
            }
            
            echo $errors;
            die();
         }
         
         
    public function actionUsers($id_order,$date)
    {
        if(!Users::getDostup(5)) 
        {
            throw new CHttpException(403,'Недостаточно прав доступа');
            die();
        }
        
       $model = $this->loadModel($id_order);
         if(isset($_POST['button_checker']))
         {
            $rand = rand(0,99999);
            $prodlenie = 'podselenie'.$rand;
            
            $date_sql_just_date = date('Y-m-d',strtotime($date));
          
            $date_sql = date('Y-m-d',strtotime($date)).' '.date('H:i:s');
            $users_in_hotel = $_POST['hotel'];
            $hotel_check_save=true;
            $miss_close_fancy = false;
                        $n=0;
                       // fnc::mpr($users_in_hotel);die();
                        foreach($users_in_hotel as $user)
                        {
                                $id_client='';
                            //    fnc::mpr($user);die();
                                $n++;
                                if(trim($user['user_id'])=='')                            
                                {
                                    
                                    if(($user['user_phone'][0]!='') or ($user['user_name']!='' and $model->TYC==1))
                                    {
                                        $new_client =  new Clients;
                                        $new_client->name = $user['user_name'];
                                        if($new_client->save())
                                        {
                                            foreach ($user['user_phone'] as $phone)
                                            {
                                                if($phone!='')
                                                {
                                                    $new_phone = new Phones;
                                                    $new_phone->id_client = $new_client->id;
                                                    $new_phone->phone = $phone;
                                                    $new_phone->save();
                                                    
                                                }                                            
                                            }
                                        }
                                        $id_client = $new_client->id;
                                    }
                                    else $error .= fnc::returnError("Пользователь №$n не был добавлен, по причине, не указан номер телефона!");
                                }
                                else
                                $id_client = $user['user_id'];
                             
                                
                                $new_client_hotel = new ClientHotel;
                                $new_client_hotel->id_client = $id_client;
                                $new_client_hotel->id_order = $id_order;
                                $new_client_hotel->date_stay_begin = $date_sql;
                                $new_client_hotel->date_stay_finish = ($model->TYC==1 ? date('Y-m-d',strtotime($user['user_finish'])).' 14:00:00' : $model->date_stay_finish);                            
                                $new_client_hotel->status = 0;
                                $new_client_hotel->from = $user['user_from'];
                                $new_client_hotel->price_for = $user['user_document'];
                              
                                $free_space = HotelOrder::model()->count("id!= {$model->id} and id_hotel = {$model->id_hotel} and ( ( date_stay_begin <'{$new_client_hotel->date_stay_finish}' and '{$new_client_hotel->date_stay_finish}'<=date_stay_finish ) or ( '{$new_client_hotel->date_stay_begin}'<=date_stay_begin and date_stay_finish<='{$new_client_hotel->date_stay_finish}' ) )");     
                           
                                if($free_space==0)
                                {
                                //НАЧАЛО
                                    if($new_client_hotel->save($prodlenie,true,$id_order))
                                    {
                                        if($model->status == 1 or $model->status == 3 or $model->status == 5 or $model->status == 6)
                                        {
                                            $photes = Phones::model()->findAll("id_client = $new_client_hotel->id_client");
                                            $hotel = Hotels::model()->findByPk($model->id_hotel);
                                            if(count($photes)>0) 
                                            {
                                               
                                                foreach ($photes as $phone)
                                                {
                                                   fnc::sendSMS($model->id,$phone->phone,$hotel->name,$new_client_hotel->date_stay_begin,$model->TYC,true);
                                                } 
                                            }
                                        }
                                        
                                        $new_mgt_money = new MgtMoney;
                                        $new_mgt_money->id_clienthotel = $new_client_hotel->id;
                                        $new_mgt_money->cost = $user['user_score'];
                                        $new_mgt_money->date_public = $new_client_hotel->date_stay_begin;
                                        $new_mgt_money->save($prodlenie,true);
                                        
                                        
                                        $model->status = ($model->TYC==1 ? 5 : 1);
                                        if(strtotime($new_client_hotel->date_stay_finish)>strtotime($model->date_stay_finish))
                                        {
                                            
                                            $model->date_stay_finish = $new_client_hotel->date_stay_finish;
                                            $model->create_time = time();
                                            $model->save($prodlenie,true);
                                        }
                                        else $model->save($prodlenie,true);
                                            
                                        
                                         
                                            $interval_days = fnc::intervalDays($new_client_hotel->date_stay_begin,$new_client_hotel->date_stay_finish);
                                            
                                            $sum_for_hotel = $interval_days * $user['user_score'];
                                            if($user['user_document']>0) $sum_for_doc = ($user['user_document']-$user['user_score'])*0.1*$interval_days;
                                            $new_tick = new Ticks;
                                            $new_tick->id_clienthotel = $new_client_hotel->id;
                                            $new_tick->date_period_begin = $new_client_hotel->date_stay_begin;
                                            $new_tick->date_period_finish = $new_client_hotel->date_stay_finish;
                                            
                                            $new_tick->status = (fnc::checkNeedTick($model->status) ? 1 : 0);  
                                            $new_tick->finish_sum = (fnc::checkNeedTick($model->status) ? $sum_for_hotel : 0);
                                            $new_tick->sum_for_days = $sum_for_hotel;
                                            $new_tick->sum_for_doc = ($sum_for_doc ? $sum_for_doc : 0);
                                            $new_tick->date_public = date('Y-m-d H:i');
                                            if($new_tick->save($prodlenie,true,$id_order))
                                            {
                                                if($sum_for_doc and fnc::checkNeedTick($model->status))
                                                {
                                                    $belong_to_home = Hotels::model()->findByPk($HO->id_hotel);
                                                    
                                                    $new_document = new Documents;
                                                    //$new_document->sum_docs = $sum_for_doc;
                                                    $new_document->id_invite = $model->id_invite;
                                                    $new_document->status = 1;
                                                    $new_document->date_public = date('Y-m-d H:i:s');
                                                    $new_document->id_clienthotel = ($model->TYC==1 ? $new_client_hotel->id : 0);
                                                    if($new_document->save($prodlenie,true,$id_order))  
                                                    {
                                                        $price_for_document = new DocumentsPrice;
                                                        $price_for_document->id_document = $new_document->id;
                                                        $price_for_document->node = "ТУЦ ".$belong_to_home->name;
                                                        $price_for_document->price = $sum_with_doc;
                                                        $price_for_document->save();   
                                                    }
                                                }
                                            }
                                        
                                    }
                                    // конец 
                                }
                                
                        }
                        
                        HotelOrder::updateTime();
                        
                            if(fnc::definePlatformPC())
                                $this->render('fancyclose');
                            else
                                $this->redirect('/');
                            die("Идёт переадресация...");
                        
                   
         }
         
         $hotel_cost = Hotels::model()->findByPk($model->id_hotel)->cost;
         $cs=Yii::app()->getClientScript(); 
         $cs->registerScriptFile(Yii::app()->theme->baseUrl.'/js/timepicker/jquery.ui.timepicker.js?v=0.2.4', CClientScript::POS_HEAD); 
         $cs->registerScriptFile(Yii::app()->theme->baseUrl.'/js/timepicker/include/jquery.ui.core.min.js', CClientScript::POS_HEAD); 
         $cs->registerScriptFile(Yii::app()->theme->baseUrl.'/js/timepicker/include/jquery.ui.widget.min.js', CClientScript::POS_HEAD); 
         $cs->registerScriptFile(Yii::app()->theme->baseUrl.'/js/hotelOrder.js', CClientScript::POS_HEAD); 
         
         $date_sql_just_date = date('Y-m-d',strtotime($date));
         $clients_hotel = ClientHotel::model()->count("id_order = $id_order and status = 0 and date(date_stay_begin)<='$date_sql_just_date' and '$date_sql_just_date'<=date(date_stay_finish)");
         $free = $model->places - $clients_hotel;
      
         $this->render('_user_form', array('TYC'=>$model->TYC,'date'=>$date,'cost'=>$hotel_cost,'places'=>($free > 0 ? $free : 0))); 
       
    }     
         
         
         public function actionloadHotels()
         {
            if(!Users::getDostup(5)) 
            {
                throw new CHttpException(403,'Недостаточно прав доступа');
                die();
            }
        
            $id = $_POST['country_id'];
              $data=Hotels::model()->findAll('id_cat='.$id);
             
                $data=CHtml::listData($data,'id','name');
                foreach($data as $value=>$name)
                {
                    echo CHtml::tag('option',
                               array('value'=>$value),CHtml::encode($name),true);
                }
         }     
         
         
         
         public function actionActionPerDay()
         {
            if(!Users::getDostup(5)) 
            {
                throw new CHttpException(403,'Недостаточно прав доступа');
                die();
            }
        
            $report = ClientHotel::model()->with(array('tickets_one'=>array('condition'=>"date(date_public)=date(now()) and `tickets_one`.status=1",'select'=>'sum(sum_for_days) as sum_for_days','group'=>'id_clienthotel')))->findAll();
                                                        
                                                        
                                                        
            $this->render('apd',array('report'=>$report)); 
         }
         
         
         public function actionEdit_order($step) 
         {
            if(!Users::getDostup(5)) 
            {
                throw new CHttpException(403,'Недостаточно прав доступа');
                die();
            }
            if(isset($_SERVER['HTTP_X_REQUESTED_WITH']) && !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') 
            {
                $id_order = $_POST['id_order_GET'];
                $HO = HotelOrder::model()->findByPk($id_order);
                        foreach ($_POST['fastedit'] as $id_clienthotel => $values)
                        {
                            
                            $cl = ClientHotel::model()->findByPk($id_clienthotel);
                            $last = Phones::model()->find(array('condition'=>"id_client = {$cl->id_client}",'order'=>'id DESC','limit'=>1));
                            $date_stay_begin = date('Y-m-d',strtotime($values['begin_date'])).' '.$values['begin_time'];
                            $date_stay_finish = date('Y-m-d',strtotime($values['finish_date'])).' '.$values['finish_time'];
                            $free_space = HotelOrder::model()->find("id!= $HO->id and id_hotel = {$HO->id_hotel} and ( ( date_stay_begin <'$date_stay_finish' and '$date_stay_finish'<=date_stay_finish ) or ( '$date_stay_begin'<=date_stay_begin and date_stay_finish<='$date_stay_finish' ) or ( date_stay_finish >'$date_stay_begin' and '$date_stay_begin'>=date_stay_begin ) ) and status!=2");
                            
                            
                        
                            
                            if($free_space==0)                            
                            {
                                if(strtotime($date_stay_finish)>strtotime($date_stay_begin))                                
                                {
                                    $msg .= "<div class='happy_user'>Пользователь с телефоном {$last->phone} готов к изменению заселения</div>";
                                    if($step==2)
                                    {
                                        $rand = rand(0,99999);
                                        $prodlenie = 'fast_edit_'.$rand;
                                        
                                        if(strtotime($date_stay_begin)!=strtotime($cl->date_stay_begin))
                                        {
                                            $my_first_mgt_money = MgtMoney::model()->find(array('condition'=>"id_clienthotel = {$cl->id}",'order'=>"date_public ASC"));
                                            $my_first_mgt_money->date_public = $date_stay_begin;
                                            $my_first_mgt_money->update();
                                        }
                                        
                                            $ex_date_stay_finish = $cl->date_stay_finish;
                                            $cl->date_stay_begin = $date_stay_begin;
                                            $cl->date_stay_finish = $date_stay_finish;
                                            
                                            $chck_interval = fnc::intervalDays($ex_date_stay_finish,$date_stay_finish);
                                            if($chck_interval==1)
                                            {
                                                $result = strtotime($date_stay_finish) - strtotime($ex_date_stay_finish);
                                                if($result/3600<23)
                                                {
                                                    $HO->broken_begin = $ex_date_stay_finish;
                                                    $HO->broken_finish = $date_stay_finish;
                                                    $rechange = true;
                                                    //die('srabotalo');
                                                }
                                            }
                                            //echo $result/3600;die();
                                            if($cl->save($prodlenie,true))
                                            {
                                                Ticks::model()->deleteAll("id_clienthotel = $cl->id and status = 0");
                                            }
                                            $msg='OK';
                                        
                                    }
                                }
                                else $msg .= "<div class='poor_user'>Невозможно изменить проживания, для пользователя с номером телефона {$last->phone}, по причине, неправильный формат проживания</div>";
                            }
                            else $msg .= "<div class='poor_user'>Невозможно изменить проживания, для пользователя с номером телефона {$last->phone}, по причине, пересечения дат, после изменения</div>";
                        }
                        
                        if($step==2)
                        {
                            $cl_find = ClientHotel::model()->find(array(
                                                                        'select'=>"`t`.date_stay_begin, (select `b`.date_stay_finish from `client_hotel` `b` where `b`.id_order = $id_order and `b`.status = 0 order by date_stay_finish DESC limit 1) as date_stay_finish",
                                                                        'condition'=>"`t`.id_order = {$id_order} and `t`.status=0",
                                                                        'order'=>'`t`.date_stay_begin ASC',                                                                                
                                                                        ));
                                                                        
                                                                       
                            
                            if(strtotime($cl_find->date_stay_finish)>strtotime($HO->date_stay_finish) and !$rechange)
                            {                                
                                $HO->status = ($HO->TYC==1 ? 5 : 1);
                            }
                            //echo $HO->broken_begin;die();
                            $HO->date_stay_begin = $cl_find->date_stay_begin;
                            $HO->date_stay_finish = $cl_find->date_stay_finish;
                                
                            $HO->create_time = time();
                            $HO->update(null,$prodlenie);
                        }                        
                  echo $msg;
            }
            else
            {
                throw new CHttpException(404,'Неправильный запрос');
                die();
            }
         }     
         
         
         public function actioneditFinally()
         {
            if(!Users::getDostup(3)) 
            {
                throw new CHttpException(403,'Недостаточно прав доступа');
                die();
            }
            if(Yii::app()->request->isAjaxRequest)
            {
                $id_clienthotel = $_POST['id_clienthotel'];
                $finally = $_POST['finally'];
                ClientHotel::model()->updateByPk($id_clienthotel,array('finally'=>$finally));
                $time = time();
                HotelOrder::updateTime();
            }
            else throw new CHttpException(404,'Неправильный запрос');
         }
         
         public function actioneditArrived()
         {
            if(!Users::getDostup(3)) 
            {
                throw new CHttpException(403,'Недостаточно прав доступа');
                die();
            }
            if(Yii::app()->request->isAjaxRequest)
            {
                $id_clienthotel = $_POST['id_clienthotel'];
                $finally = $_POST['arrived'];
                ClientHotel::model()->updateByPk($id_clienthotel,array('arrived'=>$finally));
                $time = time();
                HotelOrder::updateTime();
            }
            else throw new CHttpException(404,'Неправильный запрос');
         }
         
         
         public function actionGoAlist($post_id,$id_order,$post_type,$post_data)
         {
            if(!Users::getDostup(3)) 
            {
                throw new CHttpException(403,'Недостаточно прав доступа');
                die();
            }
            if(Yii::app()->request->isAjaxRequest)
            {
                $post_data = date('Y-m-d',strtotime($post_data));
                $load = new Users;
                $alist = new Alist;
                if(is_numeric($load->getMyId()))
                {
                    $alist->id_user = $load->getMyId();
                    $alist->post_type = $post_type;
                    switch ($post_type)
                    {
                        case 'rereserve':
                            $HO = HotelOrder::model()->findByPk($id_order);
                            $home = Hotels::model()->findByPk($HO->id_hotel);
                            $desc = "Переселение клиента из <strong>$home->name</strong> ?";
                        break;
                    }
                    $alist->short_desc = $desc;
                    $alist->status=0;
                    $alist->post_id = $post_id;
                    $alist->post_data = $post_data;
                    if($alist->save())
                        {
                            HotelOrder::updateTime();
                            echo "OK";
                            
                        }
                    else echo "ERROR";
                }
                else throw new CHttpException(404,'Вы не авторизированы');
                
            }
            else throw new CHttpException(404,'Неправильный запрос');
            
         }    
         
         public function actionDeleteList($id)
         {
            if(!Users::getDostup(3)) 
            {
                throw new CHttpException(403,'Недостаточно прав доступа');
                die();
            }
            if(Yii::app()->request->isAjaxRequest)
            {
                $alist = Alist::model()->findByPk($id);
                if($alist->delete())
                    echo "OK";
                else echo "ERROR";
            }
            else throw new CHttpException(404,'Неправильный запрос');
         }       
     
     public function init()
     {
        if(isset($_COOKIE['rereserve']))
        {
            $id_alist = $_COOKIE['rereserve'];
            if(is_numeric($id_alist))
            {
                $alist = Alist::model()->findByPk($id_alist);
                switch($alist->post_type)
                {
                    case 'rereserve':
                        $obj = ClientHotel::model()->findByPk($alist->post_id);
                        $client = Clients::model()->with('phone')->findByPk($obj->id_client);
                        $ticks  = Ticks::model()->find("id_clienthotel = {$obj->id} and status=1 and finish_sum>0");
                        $array['date']=date('d.m.Y',strtotime($alist->post_data));
                        $array['time']=date('H:i');
                        $array['alist'] = $id_alist;
                        $array['id_ho'] = $obj->id_order;
                        $array['next_days'] = fnc::intervalDays($alist->post_data,$obj->date_stay_finish);
                        $array['phone'] = $client->phone->phone;
                        $array['name'] = $client->name;
                        $array['id_user'] = $client->id;
                        $array['id_clienthotel'] = $obj->id;
                        $array['ticks'] = (is_object($ticks) ? 'YES' : 'NO');
                        if($alist->post_data==date('Y-m-d',strtotime($obj->date_stay_begin))) $array['full_rereserve']=true;
                        
                    break;
                    default:
                         $array=array('');
                    break;
                }
                
                $this->user_info = $array;
            }
        }
       
        
     }
}