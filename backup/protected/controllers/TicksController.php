<?php

class TicksController extends Controller
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
	public function actionCreate($id_user,$id_order,$date)
	{
	      // возможно нужно будет поменять на хостинге на Asia/Yekaterinburg
		$model=new Ticks;
        $current_date =  date('Y-m-d',strtotime($date));
		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Ticks']))
		{
			$model->attributes=$_POST['Ticks'];
            
            $model->date_period_finish = $_POST['Ticks']['days_list'][count($_POST['Ticks']['days_list'])-1].' 14:00:00';
            
            
               $clienthotel = ClientHotel::model()->find("id_client=$id_user and id_order=$id_order and date(date_stay_begin)<='$current_date' and '$current_date'<=date(date_stay_finish) and status=0");
           
           
           
            $was_ticks = Ticks::model()->find(array('order'=>'id DESC','condition'=>"status=0 and id_clienthotel={$clienthotel->id}"));
            $date_public = date('Y-m-d H:i');
            
             $time = time();
                HotelOrder::model()->updateAll(array('create_time'=>$time));
                
            if(count($was_ticks)>0)
            {
                $sum_for_days = $model->sum_for_days + $was_ticks->sum_for_days;
                $sum_for_doc = $model->sum_for_doc + $was_ticks->sum_for_doc;
                Ticks::model()->updateByPk($was_ticks->id,array('date_period_finish'=>$model->date_period_finish,'date_public'=>$date_public,'sum_for_doc'=>$sum_for_doc,'sum_for_days'=>$sum_for_days));
                $this->render('/hotelOrder/fancyclose');
            }
            else
            {
            
            $model->date_public = $date_public;
            
			if($model->save())
            {
               $this->render('/hotelOrder/fancyclose');
            }
            }
            //$model->date_period_begin;
            
				
		}
        else
        {            
           // ВЫполняется при выписке чека!
           $gameover=1;
           $clienthotel = ClientHotel::model()->find("id_client=$id_user and id_order=$id_order and date(date_stay_begin)<='$current_date' and '$current_date'<=date(date_stay_finish) and status=0");
           $model->id_clienthotel=$clienthotel->id;
           
           $was_ticks = Ticks::model()->find(array('condition'=>"id_clienthotel={$model->id_clienthotel}",'order'=>'id DESC'));
           
      //     echo count($was_ticks);die();
           if(count($was_ticks)==0)  $model->date_period_begin = $clienthotel->date_stay_begin;
           else $model->date_period_begin = $was_ticks->date_period_finish;
           
           $model->date_period_finish = $current_date.' 14:00:00';
           $model->status=0;
           $model->sum_for_doc = 0;
           
           $order = HotelOrder::model()->findByPk($id_order);
           $day_intervals = fnc::intervalDays($model->date_period_begin,$clienthotel->date_stay_finish);
           
      
           
           if($was_ticks->date_period_finish==$clienthotel->date_stay_finish)
                $gameover=0;
                
           $periods = Ticks::model()->find(array("select"=>"`t`.date_period_begin,(select `ts`.date_period_finish from `ticks` `ts` where `ts`.id_clienthotel={$clienthotel->id}  and `ts`.status=1 order by `ts`.date_period_finish DESC LIMIT 1) as date_period_finish",'order'=>'`t`.date_period_begin ASC','condition'=>"`t`.id_clienthotel={$clienthotel->id} and `t`.status=1"));
          // echo $periods->date_period_begin;
//           echo $periods->date_period_finish;
            $stays = ClientHotel::model()->find(array("select"=>"`t`.date_stay_begin,(select `ts`.date_stay_finish from `client_hotel` `ts` where `ts`.id={$clienthotel->id} order by `ts`.date_stay_finish DESC LIMIT 1) as date_stay_finish",'order'=>'`t`.date_stay_begin ASC','condition'=>"`t`.id={$clienthotel->id}"));
           // echo $stays->date_stay_begin;
            if(($stays->date_stay_begin==$periods->date_period_begin) and ($stays->date_stay_finish==$periods->date_period_finish))            
                $status=1;            
            else            
                $status=0;
            
          
           $model->sum_for_days=$day_intervals*$order->price_per_day;
            
          // $model->date_period_begin;
        }

		$this->render('create',array(
			'model'=>$model,
            'order'=>$order,
            'gameover'=>$gameover,
            'clienthotel'=>$clienthotel,
            'status'=>$status,
            
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

		if(isset($_POST['Ticks']))
		{
			$model->attributes=$_POST['Ticks'];
            $id_clienthotel=Ticks::model()->findByPk($id)->getAttribute('id_clienthotel');
            $id_order = ClientHotel::model()->findByPk($id_clienthotel)->getAttribute('id_order');
            HotelOrder::model()->updateByPk($id_order,array('status'=>0));
            $time = time();
                HotelOrder::model()->updateAll(array('create_time'=>$time));
			if($model->save())
				$this->render('/hotelOrder/fancyclose');
		}
        $model->status=1;
        $finish_sum=Ticks::model()->findByPk($id,array('select'=>"(sum(sum_for_days)+sum(sum_for_doc)) as sum_for_days"));
        $model->finish_sum = $finish_sum->sum_for_days;
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
		$dataProvider=new CActiveDataProvider('Ticks');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new Ticks('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Ticks']))
			$model->attributes=$_GET['Ticks'];

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
		$model=Ticks::model()->findByPk($id);
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
		if(isset($_POST['ajax']) && $_POST['ajax']==='ticks-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
