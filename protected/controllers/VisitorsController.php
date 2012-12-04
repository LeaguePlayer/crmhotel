<?php

class VisitorsController extends Controller
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
		return array(
			array('allow',  // allow all users to perform 'index' and 'view' actions
				'actions'=>array('index','view'),
				'users'=>array('*'),
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array('create','update'),
				'users'=>array('@'),
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('admin','delete','reserve','recalc','monitor','autocomplete'),
				'users'=>array('*'),
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
	public function actionCreate()
	{
		$model=new Visitors;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Visitors']))
		{
			$model->attributes=$_POST['Visitors'];
			if($model->save())
				$this->redirect(array('view','id'=>$model->id));
		}

		$this->render('create',array(
			'model'=>$model,
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

		if(isset($_POST['Visitors']))
		{
			$model->attributes=$_POST['Visitors'];
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
		if(Yii::app()->request->isPostRequest)
		{
			// we only allow deletion via POST request
			$this->loadModel($id)->delete();

			// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
			if(!isset($_GET['ajax']))
				$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
		}
		else
			throw new CHttpException(400,'Invalid request. Please do not repeat this request again.');
	}

	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
		$dataProvider=new CActiveDataProvider('Visitors');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new Visitors('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Visitors']))
			$model->attributes=$_GET['Visitors'];

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
		$model=Visitors::model()->findByPk($id);
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
		if(isset($_POST['ajax']) && $_POST['ajax']==='visitors-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
    
    
    public function actionReserve($date,$time)
    {
        $model = new Visitors;
        
        if(isset($_POST['Visitors'],$_POST['field']))
        {
            
            $user = $_POST['hotel'][0];
            $n++;
            
                                if(trim($user['user_id'])=='')                            
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
                                else $id_client = $user['user_id'];
                                    
            
            $model->attributes=$_POST['Visitors'];
            
            $n_hour_ex = explode(",",$_POST['field']['how']);
        
            if(count($n_hour_ex)>1)
            {
                $n_hour = $n_hour_ex[0];
                $n_min = (!is_numeric($n_hour_ex[1]) ? 0 : $n_hour_ex[1]);
            }
            else
            {
                $n_hour = $_POST['field']['how'];
                $n_min = 0;
            }
            
            $model->date_stay_begin = date('Y-m-d',strtotime($_POST['field']['date'])).' '.$_POST['field']['time'];
            $model->date_stay_finish = date('Y-m-d H:i',strtotime("+{$n_hour} hour +{$n_min} min".$model->date_stay_begin));
            
            $model->id_client = $id_client;
            if($_POST['field']['prepay']>0)
                $model->status = 2;
            elseif($_POST['field']['pay']==1)
                $model->status = 1;
            else
                $model->status = 0;
               
            if(!empty($id_client))
            {
               
                if(Visitors::checkOrder($model->date_stay_begin,$model->date_stay_finish,$model->id_place)) 
                {
                    if($model->save())
                    {
                        $photes = Phones::model()->findAll("id_client = $model->id_client");
                                          
                                            if(count($photes)>0) 
                                            {
                                               
                                                foreach ($photes as $phone)
                                                {
                                                   // echo $phone->phone;
                                                   fnc::sendSMSSauna($phone->phone,$model->date_stay_begin);
                                                } 
                                               //die();
                                            }
                        
                        $full_sum = $_POST['field']['sum'];
                        
                        $new_tick = new Cashbox;
                        $new_tick->id_visitors = $model->id;
                        $new_tick->date_period_begin = $model->date_stay_begin;
                        $new_tick->date_period_finish = $model->date_stay_finish;
                        $new_tick->status = (($model->status==1 or $model->status==2) ? 1 : 0);  
                        $new_tick->preceding_price = $full_sum;
                        $new_tick->prepay = ($model->status==2 ? $_POST['field']['prepay'] : 0);
                        $new_tick->finish_sum = ($model->status==1 ? $full_sum : 0);
                        $new_tick->date_public = date('Y-m-d');
                        
                        $new_tick->save();
                        
                    }
                    
                    $this->redirect("/sauna/index/date/$date");
                    die("Идёт переадресация...");
                }
                else
                    $error.=fnc::returnError('Пересечение дат');
            } 
            else $error.=fnc::returnError('Нужно ввести информацию о клиенте');
            
        }
        $model->id_invite = 1;
        $model->id_place = 1; // Т.К. всего одна сауна
        $cnvr_time = fnc::convertHour($time);
        
        $find_young_visit = Visitors::model()->find(array('condition'=>"hour(date_stay_finish)='$cnvr_time' and date(date_stay_finish)='$date' and  id_place = {$model->id_place}",'order'=>'date_stay_finish DESC'));
        if(is_object($find_young_visit))
            $cnvr_time .= ':'.date('i',strtotime($find_young_visit->date_stay_finish));
        else
            $cnvr_time .= ':00';
        
        $information['date']  = date('d.m.Y',strtotime($date));
        $information['time']  = $cnvr_time;
        
       $data = array('time'=>$time,'date'=>$date, 'model'=>$model, 'info'=>$information,'error'=>$error);
       if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest')
            $this->renderPartial('form',$data,false,true);
       else
            $this->render('form',$data);
    }
    
    
    public function actionRecalc($date_begin,$time_begin,$n_hour)
    {
        echo Visitors::calculation($date_begin,$time_begin,$n_hour);
    }
    
    
    public function actionMonitor($date,$time,$id)
    {
        $uri = '/css/visitors.css';
         Yii::app()->clientScript->registerCssFile($uri, 'screen, projection');
        $full_sql_date = $date." $time:00";
        $visitor = Visitors::model()->with('cash')->findByPk($id,"date(date_stay_begin)<=date('$full_sql_date') and '$full_sql_date'<=date_stay_finish");
        if(is_object($visitor))
        {
           // fnc::mpr($_POST);
            if(isset($_POST['edit']))
            {
                $edit = $_POST['edit'];
                
                if($edit['status']=='delete_row')
                {                    
                    if($visitor->delete())                    
                        $this->redirect("/sauna/index/date/$date");
                }
                else
                {
                    $visitor->attributes=$_POST['edit'];
                    $visitor->save();
                }
            }
            elseif(isset($_POST['extension']))
            {
                
                $extension = $_POST['extension'];
                
                
                $n_hour_ex = explode(",",$extension['hours']);
       
                if(count($n_hour_ex)>1)
                {
                    $n_hour = $n_hour_ex[0];
                    $n_min = (!is_numeric($n_hour_ex[1]) ? 0 : $n_hour_ex[1]);
                }
                else
                {
                    $n_hour = $_POST['extension']['hours'];
                    $n_min = 0;
                }
               
                $potencial_finish_date = date('Y-m-d H:i',strtotime("+{$n_hour} hour +{$n_min} min".$visitor->date_stay_finish));       
                        
                if(Visitors::checkOrder($visitor->date_stay_begin,$potencial_finish_date,$visitor->id_place,$visitor->id)) 
                {
                    $sum_for_ext = $extension['sum']*$extension['hours'];
                    if($n_min>0)
                    {
                        $tmp_sum = $extension['sum']/60*$n_min;
                        $sum_for_ext+=$tmp_sum;
                    }
                    if(isset($extension['pay']))
                    {
                       $dolg = Cashbox::checkUnpays($visitor);
                        
                         if($dolg<=0)
                         {
                            $all_pay_ticks = Cashbox::model()->find(array('condition'=>"id_visitors = {$visitor->id} and status = 1",'select'=>"sum(finish_sum+prepay) as finish_sum"));
                          //  $payed = (int)$all_pay_ticks->finish_sum;                            
                            $itogo =   $sum_for_ext;
                          //  $itogo = $potencial_sum - $payed;
                           
                            
                            
                            $new_tick = new Cashbox;
                            $new_tick->id_visitors = $visitor->id;
                            $new_tick->date_period_begin = $visitor->date_stay_finish;
                            $new_tick->date_period_finish = $potencial_finish_date;
                            $new_tick->status = 1;  
                            $new_tick->preceding_price = $itogo;
                            $new_tick->prepay = 0;
                            $new_tick->finish_sum = $itogo;
                            $new_tick->date_public = date('Y-m-d');
                          
                            if($new_tick->save())
                            {
                                $visitor->date_stay_finish = $potencial_finish_date;
                                
                                if($visitor->save())
                                {
                                 //   die('work');
                                    Settings::updateTime();
                                    $this->redirect("/sauna/index/date/$date");
                                }
                            }
                             // die('nowork');
                         }
                         else 
                         {
                            $dolg = abs($dolg);
                            $error.=fnc::returnError('Невозможно продление с оплатой. Клиент имеет задолжность в размере '.$dolg.' руб');
                         }
                    }
                    else
                    {
                        
                        $visitor->date_stay_finish = $potencial_finish_date;
                        
                            $new_tick = new Cashbox;
                            $new_tick->id_visitors = $visitor->id;
                            $new_tick->date_period_begin = $visitor->date_stay_finish;
                            $new_tick->date_period_finish = $potencial_finish_date;
                            $new_tick->status = 0;  
                            $new_tick->preceding_price = $sum_for_ext;
                            $new_tick->prepay = 0;
                            $new_tick->finish_sum = 0;
                            $new_tick->date_public = date('Y-m-d');
                            if($new_tick->save())
                            {
                                $visitor->status = 0;
                                if($visitor->save())
                                {
                                    Settings::updateTime();
                                    $this->redirect("/sauna/index/date/$date");
                                }
                                else $error.=fnc::returnError('Не удалось продлить проживание');
                            }
                        
                        
                    }
                } else $error.=fnc::returnError('Пересечение дат');
            }
            elseif(isset($_POST['got_money']))
            {
                 $payed = Cashbox::model()->find(array('condition'=>"id_visitors = {$visitor->id} and status = 1",'select'=>"sum(finish_sum+prepay) as finish_sum, prepay,preceding_price,id,date_public"));
                $dolg = abs($information['pay'] -  (int)Cashbox::model()->find(array('condition'=>"id_visitors = {$visitor->id}",'select'=>"sum(preceding_price) as preceding_price"))->preceding_price);
                
                
                $itogo = abs((int)$payed->finish_sum-$dolg);
                
               
                if($payed->prepay>0 and (strtotime(Reports::correctDatePublic(date('Y-m-d')))  ==  strtotime($payed->date_public)))
                {
                    $payed->prepay = 0;
                    $payed->finish_sum = $payed->preceding_price;
                   // echo $payed->finish_sum;die();
                    if($payed->update())
                    {
                        
                        Cashbox::model()->deleteAll("status=0 and id_visitors = {$visitor->id}");
                        $visitor->status = 1;
                        $visitor->save();
                        Settings::updateTime();
                        $this->redirect("/sauna/index/date/$date");
                    }
                }
                elseif($itogo>0)
                {
                    if($payed->prepay>0)
                    {
                        $payed->finish_sum = $payed->prepay;
                        $payed->preceding_price = $payed->prepay;
                        $payed->prepay = 0;
                        $payed->update();
                    }
                    
                    $last_tick = Cashbox::model()->find(array('condition'=>"id_visitors = {$visitor->id} and status = 1",'order'=>"date_period_finish DESC"));
                    $new_tick = new Cashbox;
                    $new_tick->id_visitors = $visitor->id;
                    $new_tick->date_period_begin = (is_object($last_tick) ? $last_tick->date_period_finish : $visitor->date_stay_begin);
                    $new_tick->date_period_finish = $visitor->date_stay_finish;
                    $new_tick->status = 1;  
                    $new_tick->preceding_price = $itogo;
                    $new_tick->prepay = 0;
                    $new_tick->finish_sum = $itogo;
                    $new_tick->date_public = date('Y-m-d');
                    if($new_tick->save())
                    {
                        Cashbox::model()->deleteAll("status=0 and id_visitors = {$visitor->id}");
                        $visitor->status = 1;
                        $visitor->save();
                        Settings::updateTime();
                        $this->redirect("/sauna/index/date/$date");
                    }
                }
            }
            elseif(isset($_POST['exit_live']))
            {
                $visitor->status = 3;
                if(strtotime($visitor->date_stay_finish) > time())
                    $visitor->date_stay_finish = date('Y-m-d H:i');
                
                    
                if($visitor->save())
                {
                    Settings::updateTime();
                    $this->redirect("/sauna/index/date/$date");
                }
            }
            
            $information['pay'] = (int)Cashbox::model()->find(array('condition'=>"id_visitors = {$visitor->id} and status = 1",'select'=>"sum(finish_sum+prepay) as finish_sum"))->finish_sum;
          //  $information['unpay'] = ($information['pay'] - Visitors::getDolg($visitor->date_stay_begin,$visitor->date_stay_finish)); ЭТА СТРОЧКА БЫЛА ДО
          $information['unpay'] = ($information['pay'] - (int)Cashbox::model()->find(array('condition'=>"id_visitors = {$visitor->id}",'select'=>"sum(preceding_price) as preceding_price"))->preceding_price);
          $information['prepay'] = Cashbox::model()->findAll(array('condition'=>"id_visitors = {$visitor->id} and status = 1 and prepay>0"));
          
          
                if($information['unpay']>0)
                {
                    $information['return'] = $information['unpay'];
                    unset($information['unpay']);
                }
                else $information['unpay'] = abs($information['unpay']);
                
            
             $user = Clients::model()->with('phones')->findByPk($visitor->id_client);
             $data = array('time'=>$time,'date'=>$date, 'visitor'=>$visitor,'user'=>$user,'error'=>$error,'info'=>$information);
               if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest')
                    $this->renderPartial('monitor',$data,false,true);
               else
                    $this->render('monitor',$data);
        }
        else throw new CHttpException(404,'Зеселение не было найдено.');
    }
    
    public function init()
    {
        $user = new Users;
        $id_user = $user->getMyId();
        $find_user = Users::model()->findByPk($id_user);
        if(is_object($find_user))
        {
           
            if(!$find_user->sauna_access == 1 and !$find_user->sauna_access == 2)
             throw new CHttpException(403,'Недостаточно прав доступа');
        }
        else $this->redirect('/');
        
       
    
         
         return parent::init();

    
    }
    
    
    
    public function actionAutoComplete() 
    {
        if(Yii::app()->request->isAjaxRequest && isset($_GET['term'])) {
 
            $find = $_GET['term'];
            $criteria = new CDbCriteria;
            $criteria->condition = "learnedby LIKE :tquery";
            $criteria->group = 'learnedby';
            $criteria->params = array(":tquery"=>'%'.$find.'%');
            $criteria->limit = 5;
            $criteria->distinct = true;
            $queries = Visitors::model()->findAll($criteria);
            $resStr = array();
            $a = 0;
            foreach($queries as $tmpquery) 
            {
                $resStr[$a]["label"] = $tmpquery->learnedby;
                //$resStr[$a]["learnedby"] = $tmpquery->learnedby;
                $a++;
            }
            echo CJSON::encode($resStr);       
        } 
    }
    
    
}
