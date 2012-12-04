<?php

class ReportsController extends Controller
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
				'actions'=>array(''),
				'users'=>array('*'),
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array(''),
				'users'=>array('@'),
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('admin','delete','get','captcha','listing','dublicate','ByMonth','list'),
				'users'=>array('*'),
			),
		
		);
	}
        
        
        public function actionByMonth($year,$month)
        {
            $result = array();
            $result['year'] = $year;
            $result['month'] = fnc::getMonth($month);
            if($month<10) $month = "0".$month;
            $result['all_days'] = $days_cnt = cal_days_in_month(CAL_GREGORIAN, $month, $year);
            $result['month_num'] = $month;
            
            
            
            
            $all_ticks = Ticks::model()->with('with_ch')->findAll(array('condition'=>"month(date_public)='$month' and year(date_public)='$year'",'order'=>'date_public ASC','group'=>'id_clienthotel','select'=>'id,sum(finish_sum) as finish_sum,day(date_public) as date_public'));
            $hotels = Hotels::model()->findAll(array('order'=>'default_type ASC, id_cat ASC'));
            
            
           
            
            $result['hotels'] = CHtml::listData($hotels, 'id', 'name');
            $result['ticks'] = CHtml::listData($all_ticks,  'date_public','finish_sum', 'with_ch.with_ho.id_hotel');
            
           // fnc::mpr($result);die();
            
            
            $this->renderPartial('month_report',array('result'=>$result));
        }
        
        public function actionList()
        {
            
            if(is_numeric($_GET['month']))
            {
                
                $this->redirect("/reports/bymonth/year/2012/month/{$_GET['month']}");
            }
            
            echo "<a href='/' style='font-size:25px;'>Вернуться назад</a>";
            echo "<br><br><br>";
            echo "Выберите месяц<br>";
            
            for($i=5;$i<=date('m');$i++) $result[$i] = fnc::getMonth($i);
            
            echo "<form>";
            echo CHtml::dropDownList('month', date('m'), $result);
            echo "<input type='submit' value='Посмотреть'>";
            echo "</form>";
        }
    
    public function actions(){
        return array(
            'captcha'=>array(
                'class'=>'CCaptchaAction',
               
                'testLimit'=>'1', 
              
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
	public function actionGet($date,$type)
	{
		$model=new Reports;
       

        
		switch ($type)
        {
            case 'by_day':
            $tmn_date = date('Y-m-d',strtotime($date));
            $today = date('Y-m-d');
            
            if(strtotime($today)>=strtotime($tmn_date))
                $agree_form = true;
            else $agree_form = false;
            
            $find_report = Reports::model()->find("date = :date_public",array(':date_public'=>$tmn_date));
            $found_report = (count($find_report)==1 ? true : false);
            
            
                if(!$found_report)
                {                
                    $connection=Yii::app()->db; // так можно сделать, если в конфигурации описан компонент соединения "db"
                   //echo $SQL_LAST = "select `o`.id_invite,sum(finish_sum),(sum(sum_for_days)) as sum_for_days from `hotel_order` `o` left join `client_hotel` `c` on `c`.id_order=`o`.id left join `ticks` `t` on `t`.id_clienthotel=`c`.id where date(date_public)='$tmn_date' and `t`.status!=5 group by `o`.id_invite having sum(finish_sum)>0";
                   $SQL = "select `t`.id_invite,ROUND((sum(finish_sum)+IFNULL(sum((select dp.price from documents dd inner join documents_price dp on dp.id_document = dd.id where dd.id_clienthotel = c.id order by dp.date_edit DESC, dp.id DESC LIMIT 1)),0)),-2) as finish_sum from `hotel_order` `o` left join `client_hotel` `c` on `c`.id_order=`o`.id left join `ticks` `t` on `t`.id_clienthotel=`c`.id where date(date_public)='$tmn_date' and `t`.status not in (5,6) group by `t`.id_invite having sum(finish_sum)>0";
                    $command=$connection->createCommand($SQL);  
                    $reports=$command->query();
                    
                    $SQL_goback = "select `t`.id_invite,sum(finish_sum),(sum(sum_for_days)+sum(sum_for_doc)) as sum_for_days from `hotel_order` `o` left join `client_hotel` `c` on `c`.id_order=`o`.id left join `ticks` `t` on `t`.id_clienthotel=`c`.id where date(date_public)='$tmn_date' and `t`.status=5 group by `o`.id_invite";
                    $command_goback=$connection->createCommand($SQL_goback);  
                    $report_goback=$command_goback->query();
                    
                    $current_date =  date('Y-m-d',strtotime($date));
                    $report_docs = Documents::model()->findAll(array('condition'=>"id_clienthotel = 0 and post_type='' and date(date_public)='$current_date' and status = 1",'group'=>'id_invite','select'=>"sum((select price from documents_price where id_document=t.id order by date_edit DESC, id DESC LIMIT 1)) as status,id_invite")); 
                    
                    
                    $report_service = ProductUsed::model()->findAll(array('group'=>'t.id_invite','condition'=>"t.date_used = :date_used", 'select'=>'sum(t.price_for_sale * t.count_used) as status,id_invite', 'params'=>array(':date_used'=>$tmn_date)));
                    
                    
                    
                    $payments = PaymentsOrder::model()->findAll(array('condition'=>"date(date_public)='$current_date' and credit_option=0",'group'=>'id_invite','select'=>"sum(price)-IFNULL((select sum(price) from payments_order pp where pp.id_invite=t.id_invite and  date(date_public)='$current_date' and credit_option=1),0) as price,id_invite"));          
                     
                    $SQL = "select `o`.id_invite,sum(finish_sum+prepay) as finish_sum from `visitors` `o` left join `cashbox` `t` on `t`.id_visitors=`o`.id where date(date_public)='$tmn_date' and `t`.status in (1,2) group by `o`.id_invite";
                    $command=$connection->createCommand($SQL);  
                    $sauna=$command->query();     
                    
                }
                else
                {
                    $array_report = unserialize($find_report->array_report);
                    $reports = $array_report['oplata'];
                    $report_goback = $array_report['vozvrat'];
                    $report_docs = $array_report['documents'];
                    $report_service = $array_report['service'];
                    $payments = $array_report['zatrati'];
                    $sauna = $array_report['sauna'];
                }
            
            
                  if(!$found_report)
                  {                    
                        if(isset($_POST['Reports']))
                        {
                            $n = 0;
                            foreach ($reports as $report)
                            {
                                if($report['finish_sum']=='') $report['finish_sum']=0;                                          
                                $go_to_database['oplata'][$n]['finish_sum'] = $report['finish_sum'];
                                $go_to_database['oplata'][$n]['id_invite'] = $report['id_invite'];
                                $n++;
                            }
                            foreach ($report_goback as $report)
                            {
                                if($report['sum(finish_sum)']=='') $report['sum(finish_sum)']=0;                                             
                                $go_to_database['vozvrat'][$n]['sum(finish_sum)'] = $report['sum(finish_sum)'];
                                $go_to_database['vozvrat'][$n]['id_invite'] = $report['id_invite'];
                                $n++;
                            }
                            foreach ($report_docs as $doc)
                            {
                                $go_to_database['documents'][$n]['sum(finish_sum)'] = $doc->status;
                                $go_to_database['documents'][$n]['id_invite'] = $doc->id_invite;
                                $n++;      
                            }
                            foreach ($report_service as $doc)
                            {
                                $go_to_database['service'][$n]['sum(finish_sum)'] = $doc->status;
                                $go_to_database['service'][$n]['id_invite'] = $doc->id_invite;
                                $n++;      
                            }
                            foreach ($payments as $doc)
                            {
                                $go_to_database['zatrati'][$n]['sum(finish_sum)'] = $doc->price;
                                $go_to_database['zatrati'][$n]['id_invite'] = $doc->id_invite;
                                $n++; 
                            }
                            
                            foreach ($sauna as $doc)
                            {
                                $go_to_database['sauna'][$n]['finish_sum'] = $doc['finish_sum'];
                                $go_to_database['sauna'][$n]['id_invite'] = $doc['id_invite'];
                                $n++; 
                            }
                           
                            $user = new Users;
                            $id_user  = $user->getMyId();
                            $model->attributes=$_POST['Reports'];
                            $model->array_report = serialize($go_to_database);
                            $model->date = $tmn_date;
                            $model->id_user = $id_user;
                            $model->save();
                            $this->refresh();
                        }                    
                    }
                    else $found_report = true;
                   
                    $this->renderPartial('report',array(
		            'date'=>$date,
                    'type'=>$type,
                    'report'=>$reports,
                    'payments'=>$payments,
                    'sauna'=>$sauna,
                  'docs'=>$report_docs,
                  'goback'=>$report_goback,
                        'service'=>$report_service,
                  'model'=>$model,
                  'found_report'=>$found_report,
                        'find_report'=>$find_report,
                  'agree_form'=>$agree_form,
                   	));
            break;

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

		if(isset($_POST['Reports']))
		{
			$model->attributes=$_POST['Reports'];
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
		$dataProvider=new CActiveDataProvider('Reports');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new Reports('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Reports']))
			$model->attributes=$_GET['Reports'];

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
		$model=Reports::model()->findByPk($id);
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
		if(isset($_POST['ajax']) && $_POST['ajax']==='reports-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
    
    public function actionListing()
    {
        if(Yii::app()->request->isAjaxRequest)
        {
            $type = $_POST['type'];
            $id = $_POST['id'];
            $date = $_POST['date'];
        
            switch ($type)
            {
                case 'ticks':
                    $hows =  HotelOrder::model()->with(array('cl'=>array('with'=>array('tickets'=>array('condition'=>"'$date'=date(date_public) and `tickets`.status = 1",'group'=>"id_clienthotel",'select'=>"sum(finish_sum) as finish_sum")))))->findAll(array('condition'=>"`tickets`.id_invite = $id"));
                    
                    
                    if(count($hows)>0)
                    {
                        
                        foreach ($hows as $how)
                        {
                            
                            echo "<ul>";
                            $hotel = Hotels::model()->findByPk($how->id_hotel);
                            
                            $class = ($how->uncurrect=='' ? " " : " show_icon");
                            $uncurrect = "<div rel='$type' alt='$how->id' class='uncurrect_money{$class}'></div>";                        
                            echo "<li>$hotel->name{$uncurrect}</li>";
                            
                            foreach($how->cl as $cl)
                            {
                                
                                $find_payed_docs = Documents::model()->find(array('condition'=>"id_clienthotel = {$cl->id} and status = 1",'group'=>'id_invite','select'=>"sum((select price from documents_price where id_document=t.id order by date_edit DESC, id DESC LIMIT 1)) as status,id_invite"));  
                                
                                $client = Clients::model()->findByPk($cl->id_client);
                                if($client->name!='')
                                {
                                    echo "<ul>";
                                    echo "<li>$client->name</li>";
                                }                                
                                    foreach ($cl->tickets as $tick)
                                    {
                                        echo "<ul>";
                                        echo "<li>{$tick->finish_sum} руб. за проживание</li>";
                                        echo "</ul>";
                                    }
                                    if($find_payed_docs->status>0)
                                    {
                                        echo "<ul>";
                                        echo "<li>{$find_payed_docs->status} руб. за документы</li>";
                                        echo "</ul>";
                                    }
                                if($client->name!='')
                                {
                                    echo "</ul>";
                                }
                                
                            }
                            echo "</ul>";
                        }
                    }
                break;
                
                
                case 'sauna':
                    $hows =  Visitors::model()->with(array('cash'=>array("group"=>'id_visitors',"select"=>"sum(finish_sum+prepay) as finish_sum",'condition'=>"`cash`.status=1 and '$date'=date_public")))->findAll(array('condition'=>"`t`.id_invite = $id"));
                    if(count($hows)>0)
                    {
                        foreach ($hows as $how)
                        {
                            
                                $client = Clients::model()->findByPk($how->id_client);
                                if($client->name!='')
                                {
                                    echo "<ul>";
                                    echo "<li>$client->name</li>";
                                }                                
                                    foreach ($how->cash as $tick)
                                    {
                                        
                                        echo "<ul>";
                                        echo "<li>{$tick->finish_sum}</li>";
                                        echo "</ul>";
                                    }
                                if($client->name!='')
                                {
                                    echo "</ul>";
                                }
                                
                           
                        }
                    }
                break;
                
                case 'zatrati':
                    
                    $hows = PaymentsOrder::model()->findAll(array('condition'=>"'$date'=date(date_public) and id_invite = $id"));
                    if(count($hows)>0)
                    {
                        echo "<ul>";
                        foreach ($hows as $how)
                        {
                            
                                $client = Staff::model()->findByPk($how->id_staff);
                                
                                    $my_type = PaymentsOrder::getType($how->id_type);
                                    $ext_type = ($how->credit_option==1 ? ' возврат' : '');
                                    echo "<li>{$client->name} - {$how->price} руб. ($my_type{$ext_type})</li>"; 
                                    
                        }
                        echo "</ul>";
                    }
                break;
            }
        }
    }
    
    
    public function actionDublicate()
    {
        if(fnc::ajax())
        {
            $id_report = $_POST['id_report'];
            $type = $_POST['type'];
            
            if(is_numeric($id_report))
            {
                switch($type)
                {
                    case 'delete':
                        $model = Reports::model()->findByPk($id_report);
                        if(is_object($model))
                        {
                            $model->dublicate_report = "";
                            if($model->update())
                            {
                                echo "DELETED";
                            }

                        }
                    break;
                    case 'update':
                        $model = Reports::model()->findByPk($id_report);
                        $html = $_POST['html'];
                        if(is_object($model))
                        {
                            $model->dublicate_report = $html;
                            if($model->update())
                            {
                                echo "UPDATED";
                            }

                        }
                    break;
                }
            }

            
        }
        
        
    }
    
 




    public function init()
    {
        if(!Users::getDostup(2)) 
        {
            throw new CHttpException(403,'Недостаточно прав доступа');
            die();
        }
    }
}
