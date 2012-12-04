<?php

class HotelsController extends Controller
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
				'actions'=>array('index','view','addBell','addRing','remember','testReg'),
				'users'=>array('*'),
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array('create','update','manageGallery','uploadPhoto', 'resortPhotos'),
				'users'=>array('@'),
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('admin','delete','dirty'),
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
		$model=new Hotels;
        $model->sinc = Yii::app()->params['site_sinchronization'];

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Hotels']))
		{
			$model->attributes=$_POST['Hotels'];
            $model->sinc = $_POST['Hotels']['sinc'];
         //  fnc::mpr($_POST['Hotels']);
          //  die();
			if($model->save())
            {
                $model=new Hotels;
                $model->sinc = $_POST['Hotels']['sinc'];
            }
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
        $model->sinc = Yii::app()->params['site_sinchronization'];
        

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Hotels']))
		{
			$model->attributes=$_POST['Hotels'];
            $model->sinc = $_POST['Hotels']['sinc'];
			if($model->save())
				$this->redirect(array('admin'));
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
		$dataProvider=new CActiveDataProvider('Hotels');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new Hotels('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Hotels']))
			$model->attributes=$_GET['Hotels'];

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
		$model=Hotels::model()->findByPk($id);
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
		if(isset($_POST['ajax']) && $_POST['ajax']==='hotels-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
    
    public function actionDirty($id)
    {
        if(isset($_POST['dirty']))
        {
            Hotels::model()->updateByPk($id,array('dirty'=>$_POST['dirty']));
            $time = time();
            HotelOrder::model()->updateAll(array('create_time'=>$time));
            if(fnc::definePlatformPC())
                        $this->render('/hotelOrder/fancyclose');
                    else
                        $this->redirect('/');
                    die("Идёт переадресация...");
        }
        else
        {
            $model = Hotels::model()->findByPk($id);
            $this->render('dirty',array(
    			'model'=>$model,
    		));
        }
    }
    
    public function actionaddBell($id)
    {
        if(isset($_POST['Hotels']['bell']))
        {
            $hotel = Hotels::model()->findByPk($id);
            $messsage = $_POST['Hotels']['bell'];
            $quest = $_POST['Hotels']['quest'];
            $admin_message = $_POST['Hotels']['admin_message'];
            $time = time();
            if($hotel->quest!=$quest and $quest!='')
            {
                if(Yii::app()->params['host']!='local')
                {
                    include_once('sms.class.php');                       
                        
                        $phone = '79220789922';
                        $message_sms = $quest." - ".$hotel->name;  
                        
                        fnc::sendSMSLight($phone,$message_sms,"hotel72.ru");
                }
            }
            HotelOrder::model()->updateAll(array('create_time'=>$time));
            Hotels::model()->updateByPk($id,array('bell'=>$messsage,'quest'=>$quest,'admin_message'=>$admin_message));
        }
        
        if(fnc::definePlatformPC())
                        $this->render('/hotelOrder/fancyclose');
                    else
                        $this->redirect('/');
                    die("Идёт переадресация...");
    }
    
      public function actionaddRing($id)
    {
        if(isset($_POST['Hotels']['ring']))
        {
            $hotel = Hotels::model()->findByPk($id);
            $messsage = $_POST['Hotels']['ring'];
            if($hotel->ring==$messsage) $messsage = '';
            $time = time();
            HotelOrder::model()->updateAll(array('create_time'=>$time));
            Hotels::model()->updateByPk($id,array('ring'=>$messsage));
        }
        
        if(fnc::definePlatformPC())
                        $this->render('/hotelOrder/fancyclose');
                    else
                        $this->redirect('/');
                    die("Идёт переадресация...");
    }
    
    public function actionRemember()
    {
        
        $needUpdate = HotelOrder::model()->updateAll(array("ring"=>1),"date(date_stay_begin)=date(NOW()) and status=1");
        echo "OK";
        
        
    }
    
    public function actionManageGallery($hotel_id)
    {
        $hotel = $this->loadModel($hotel_id);
        //$photos = $hotel->photos;
        
        $this->render('manage_gallery', array(
            'hotel'=>$hotel,
            //'photos'=>$photos,
        ));
    }
    
    public function init()
    {
        if(!Users::getDostup(5,true)) 
        {
            throw new CHttpException(403,'Недостаточно прав доступа');
            die();
        }
    }
    
}
