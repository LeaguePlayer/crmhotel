<?php

class UsersController extends Controller
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
				'actions'=>array('login','logout'),
				'users'=>array('*'),
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array(''),
				'users'=>array('@'),
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('admin','delete','create','update'),
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
	   if(!Users::getDostup(1)) 
            {
                throw new CHttpException(403,'Недостаточно прав доступа');
                die();
            }
		$model=new Users;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Users']))
		{
			$model->attributes=$_POST['Users'];
            $model->password = md5($model->password);
            
             $model->works_to = date('Y-m-d',strtotime($model->works_to)).' '.$_POST['Users']['time'];
            
			if($model->save())
				$this->redirect(array('admin'));
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
	     if(!Users::getDostup(1)) 
            {
                throw new CHttpException(403,'Недостаточно прав доступа');
                die();
            }
		$model=$this->loadModel($id);

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);
        
		if(isset($_POST['Users']))
		{
			$last_pas = $model->password;
            $model->attributes=$_POST['Users'];
            
            
            $model->works_to = date('Y-m-d',strtotime($model->works_to)).' '.$_POST['Users']['time'];
            
          
            
            if($_POST['Users']['password']==$last_pas)
                $model->password = $model->password;
            else $model->password = md5($_POST['Users']['password']);
            
            
            
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
	     if(!Users::getDostup(1)) 
            {
                throw new CHttpException(403,'Недостаточно прав доступа');
                die();
            }
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
		$dataProvider=new CActiveDataProvider('Users');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
	    if(!Users::getDostup(1)) 
        {
            throw new CHttpException(403,'Недостаточно прав доступа');
            die();
        }
		$model=new Users('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Users']))
			$model->attributes=$_GET['Users'];

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
		$model=Users::model()->findByPk($id);
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
		if(isset($_POST['ajax']) && $_POST['ajax']==='users-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
    
    public function  actionLogin()
    {
        
        $model  = new Users;          
        if(!is_numeric($model->getMyId()))
        {    
            $error=false; 
            if(isset($_POST['log']))
            {
                $data = $_POST['log']; 
                $identity=new UserIdentity($data['name'],$data['password']);
                               
                $identity->authenticate();                
                         
              //  fnc::mpr($identity);
            //  fnc::mpr($identity);die();
                    if($identity->login($identity))
                    {
                        $this->redirect('/');
                             
                               
                    } else $error .= "<li>Не правильный логин или пароль!</li>"; 
                
                       
                
            }
    
            $this->render('login',array('error'=>$error));
        }
        else $this->redirect('/');
       
    }
    
     public function actionLogout()
     {
        $model  = new Users;
        if(is_numeric($model->getMyId()))        
        {
           
            if(Users::logout())
              $this->redirect(array('login'));
        }      
        else
            $this->redirect(array('login'));
     }
}
