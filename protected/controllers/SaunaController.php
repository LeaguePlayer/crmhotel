<?php

class SaunaController extends Controller
{
    public $layout='//layouts/column3';
    
	public function actionIndex($date=false)
	{
	    if(!$date) 
        {
            $today = date('Y-m-d');
            $this->redirect("/sauna/index/date/$today");
        }
        
        if(date('Y-m-d',strtotime($date))=='1970-01-01')
            throw new CHttpException(404,'Неправильный формат даты.');
            
        
	    $user = new Users;
        $id_user = $user->getMyId();
        
       
            $find_user = Users::model()->findByPk($id_user);
            if(is_object($find_user))
            {
               
                if(!$find_user->sauna_access == 1 and !$find_user->sauna_access == 2)
                 throw new CHttpException(403,'Недостаточно прав доступа');
            }
            else $this->redirect('/');
        
        
        
        $visitors = Visitors::model()->findAll(array("condition"=>"date(date_stay_begin)='$date' or date(date_stay_finish)='$date'",'order'=>"id_place ASC,date_stay_begin DESC, date_stay_finish ASC"));
       
        
		$this->render('index',array('user'=>$user,'date'=>$date,'visitors'=>$visitors));
	}
    
    
    public function actionUpdate($date,$time=0)
    {
        if(Yii::app()->request->isAjaxRequest)
        {
            $settings = Settings::model()->findByPk(1);
            
            if($settings->sauna_last_update>$time)
            {
                $user = new Users;
                $id_user = $user->getMyId();            
                
                $visitors = Visitors::model()->findAll(array("condition"=>"date(date_stay_begin)='$date' or date(date_stay_finish)='$date'",'order'=>"id_place ASC,date_stay_begin DESC, date_stay_finish ASC"));       
                
        		$this->renderPartial('_heart',array('user'=>$user,'date'=>$date,'visitors'=>$visitors));
            }
        }
    }
    

	// Uncomment the following methods and override them if needed
	/*
	public function filters()
	{
		// return the filter configuration for this controller, e.g.:
		return array(
			'inlineFilterName',
			array(
				'class'=>'path.to.FilterClass',
				'propertyName'=>'propertyValue',
			),
		);
	}

	public function actions()
	{
		// return external action classes, e.g.:
		return array(
			'action1'=>'path.to.ActionClass',
			'action2'=>array(
				'class'=>'path.to.AnotherActionClass',
				'propertyName'=>'propertyValue',
			),
		);
	}
	*/
}