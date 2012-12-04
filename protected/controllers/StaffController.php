<?php

class StaffController extends Controller
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
				'actions'=>array('admin','delete','money','manipulations'),
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
		$this->redirect(array('admin'));
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
		$model=new Staff;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Staff']))
		{
			$model->attributes=$_POST['Staff'];
			if($model->save())
				$this->redirect(array('staff/admin'));
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

		if(isset($_POST['Staff']))
		{
			$model->attributes=$_POST['Staff'];
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
            $this->redirect(array('admin'));
		$dataProvider=new CActiveDataProvider('Staff');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new Staff('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Staff']))
			$model->attributes=$_GET['Staff'];

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
		$model=Staff::model()->findByPk($id);
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
		if(isset($_POST['ajax']) && $_POST['ajax']==='staff-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
        
        public function actionMoney($id)
        {
            
            if(is_numeric($id))
            {
                $selected_user = Staff::model()->with('account')->findByPk($id);
                
                if(is_object($selected_user))
                {
                    $userinfo['cache'] = Staff::model()->with('cashe_history')->findByPk($id)->cashe_history;
                    
                    $userinfo['credit'] = Staff::model()->with('credit_history')->findByPk($id)->credit_history;
                    $userinfo['report'] = Staff::model()->with('report_history')->findByPk($id)->report_history;
    
                    $cs=Yii::app()->getClientScript();
                    $cs->registerScriptFile(Yii::app()->theme->baseUrl.'/js/money.js', CClientScript::POS_HEAD);
                    $cs->registerCSSFile(Yii::app()->theme->baseUrl.'/css/money.css');

                    $model = new PaymentsOrder;
                    $model->id_staff = $id;
                    $model->price = 0;
                    $model->id_invite = 0;
                    $model->status = 0;
                    $model->credit_option = 0;
                    
                    if(isset($_POST['PaymentsOrder']))
                    {
                        $model->attributes = $_POST['PaymentsOrder'];
                        if($model->save())
                        {
                            $this->redirect(array('admin'));
                        }
                    }
                    
                    $this->render('money',array('selected_user'=>$selected_user,'model'=>$model,'userinfo'=>$userinfo));
                }
                
            }
            
        }
        
        public function actionManipulations()
        {
            if(fnc::ajax())
            {
                $id = $_POST['id_paymend'];
                if(is_numeric($id))
                {
                    
                    $type = $_POST['type'];
                    switch($type)
                    {
                        case 'setStatus':
                            $status = $_POST['status'];
                            $model = PaymentsOrder::model()->findByPk($id);
                            if(is_object($model))
                            {
                                $model->status = $status;
                                if($model->update())
                                {
                                    echo "COMPLETE";
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
