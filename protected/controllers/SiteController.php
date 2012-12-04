<?php

class SiteController extends Controller
{
	/**
	 * Declares class-based actions.
	 */
	public function actions()
	{
		return array(
			// captcha action renders the CAPTCHA image displayed on the contact page
			'captcha'=>array(
				'class'=>'CCaptchaAction',
				'backColor'=>0xFFFFFF,
			),
			// page action renders "static" pages stored under 'protected/views/site/pages'
			// They can be accessed via: index.php?r=site/page&view=FileName
			'page'=>array(
				'class'=>'CViewAction',
			),
		);
	}
    
    

	/**
	 * This is the default 'index' action that is invoked
	 * when an action is not explicitly requested by users.
	 */
	public function actionIndex($cat = false,$left=false,$to=false,$since=false)
	{       
	  
	      // возможно нужно будет поменять на хостинге на Asia/Yekaterinburg
	   if(Yii::app()->request->isAjaxRequest)
       {                     
            $this->renderPartial('/site/_calendar', array('days_back'=>$since,'days_prev'=>$to,'left'=>$left), false, true);
            die();                
       }
	   
        $user = new Users;
        $id_user = $user->getMyId();

      
        if(!is_numeric($id_user))
        {
            $this->redirect('/login');
            die();
        }
        
        $load_user = Users::model()->findByPk($id_user);
        if($load_user->sauna_access==2)
        {
            $this->redirect('/sauna/');
            die();
        }
      
        if(!isset(Yii::app()->session['tyc_only']))        
            Yii::app()->session['tyc_only']=0;  
            
            
        $tommorow = date('Y-m-d',strtotime("-1 day -1 hour".date('Y-m-d H:i')));
        
        $checkLastReport = Reports::model()->count("date = '$tommorow'");
        if($checkLastReport==0) $show_form = true;
        
        $this->alist = Alist::model()->findAll(array('condition'=>"id_user = $id_user and status = 0",'order'=>'id DESC'));
        $settings = Settings::model()->findByPk(1);
        
                
		$this->render('index',array(
			
            'show'=>$show_form,
            'user'=>$user,
            'settings'=>$settings,
           
		));
	}
    
    public function actionRewrite()
    {
        $back_url = $_SERVER['HTTP_REFERER'];
        $session = new CHttpSession();
        $session->open();
        
        if(isset(Yii::app()->session['tyc_only']) and Yii::app()->session['tyc_only']==1)        
            Yii::app()->session['tyc_only']=0;        
        else
            Yii::app()->session['tyc_only'] = 1;
            
            if(!Yii::app()->request->isAjaxRequest)
        $this->redirect($back_url);
        else die();
    }
    
    
    public function actionMessage()
    {
        $settings = Settings::model()->findByPk(1);
        
        if(isset($_POST['message']))
        {
            $message = $_POST['message'];
            $settings->message = $message;
            $settings->update();
            
            
                    $this->render('/hotelOrder/fancyclose');
            
            
                    
                    die("Идёт переадресация...");
        }
        
        $this->render('message',array('settings'=>$settings));
    }
    
    
    public function actionChangefilt()
    {
        $back_url = $_SERVER['HTTP_REFERER'];
        $session = new CHttpSession();
        $session->open();
        
        if(isset(Yii::app()->session['all_homes']) and Yii::app()->session['all_homes']==1)        
            Yii::app()->session['all_homes']=0;        
        else
            Yii::app()->session['all_homes'] = 1;
            
            
        if(!Yii::app()->request->isAjaxRequest)
        $this->redirect($back_url);
        else die();
    }

	/**
	 * This is the action to handle external exceptions.
	 */
	public function actionError()
	{
	    if($error=Yii::app()->errorHandler->error)
	    {
	    	if(Yii::app()->request->isAjaxRequest)
	    		echo $error['message'];
	    	else
	        	$this->render('error', $error);
	    }
	}

	/**
	 * Displays the contact page
	 */
	public function actionContact()
	{
		$model=new ContactForm;
		if(isset($_POST['ContactForm']))
		{
			$model->attributes=$_POST['ContactForm'];
			if($model->validate())
			{
				$headers="From: {$model->email}\r\nReply-To: {$model->email}";
				mail(Yii::app()->params['adminEmail'],$model->subject,$model->body,$headers);
				Yii::app()->user->setFlash('contact','Thank you for contacting us. We will respond to you as soon as possible.');
				$this->refresh();
			}
		}
		$this->render('contact',array('model'=>$model));
	}

	/**
	 * Displays the login page
	 */
	public function actionLogin()
	{
		$model=new LoginForm;

		// if it is ajax validation request
		if(isset($_POST['ajax']) && $_POST['ajax']==='login-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}

		// collect user input data
		if(isset($_POST['LoginForm']))
		{
			$model->attributes=$_POST['LoginForm'];
			// validate user input and redirect to the previous page if valid
			if($model->validate() && $model->login())
				$this->redirect(Yii::app()->user->returnUrl);
		}
		// display the login form
		$this->render('login',array('model'=>$model));
	}

	/**
	 * Logs out the current user and redirect to homepage.
	 */
	public function actionLogout()
	{
		Yii::app()->user->logout();
		$this->redirect(Yii::app()->homeUrl);
	}
    
    
    public function actionreLoadMenu()
    {        
        echo $this->renderPartial('_menu_doc');
    }
    public function actionreLoadMessageBox()
    {        
        $settings = Settings::model()->findByPk(1);
        echo $this->renderPartial('_menu_box',array('settings'=>$settings));
    }
    
    
    
   
    
}