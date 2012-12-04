<?php

class DocumentsController extends Controller
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
				'actions'=>array('admin','delete','ChangeStatus','ChangeStatusTick'),
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
	public function actionCreate($type='')
	{
            if($type=='service') $this->redirect('/products/admin/');
	      // возможно нужно будет поменять на хостинге на Asia/Yekaterinburg
		$model=new Documents;
                $exmodel = new DocumentsPrice;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);
                $model->post_type = $type;
                if($model->post_type!='')
                    $model->status = 1;
		if(isset($_POST['Documents']))
		{
                        $exmodel->attributes=$_POST['DocumentsPrice'];
			$model->attributes=$_POST['Documents'];
                        
                        if($model->post_type=='')
                            $model->status = ($model->id_invite==2 ? 0 : 1);
                        
                        $model->date_public = date("Y-m-d H:i");
			if($model->save())
                        {
                           
                            $exmodel->id_document = $model->id;
                            $exmodel->node = 'Выписка документа';
                            if($exmodel->save())
                            {
                               if(fnc::definePlatformPC())
                                $this->render('/hotelOrder/fancyclose');
                            else
                                $this->redirect('/');
                            }
                                
                        }
                        
				
                       die("Идёт переадресация...");
		}
        
                
                
                
                $docs_for_drivers = Documents::model()->with('price')->findAll("t.status=0 and post_type='{$model->post_type}'");
                
                if($model->post_type=='')
                {
                    $all_found_fly_ticks = ClientHotel::model()->with(array('tickets_one'=>array('select'=>'sum(finish_sum) as finish_sum','condition'=>'tickets_one.status=6','group'=>'id_clienthotel')))->findAll(array('order'=>'t.id DESC','select'=>'t.id'));
                $array_with_all_found_fly_ticks = CHtml::listData($all_found_fly_ticks, 'id', 'tickets_one.finish_sum');
                }
                
               
               
      
        
		$this->render('create',array(
			'model'=>$model,
                    'exmodel'=>$exmodel,
            'docs_for_drivers'=>$docs_for_drivers,
                    'array_with_all_found_fly_ticks'=>$array_with_all_found_fly_ticks,
		));
	}

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdate($id)
	{
            
                $listing=Documents::model()->with('prices')->findByPk($id);
		$model=Documents::model()->with('price')->findByPk($id);
                if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
                    
                $exmodel = new DocumentsPrice;
                $exmodel->price = $model->price->price;
                $exmodel->node = $model->price->node;
                
                $my_tick = Ticks::model()->find(array('condition'=>"id_clienthotel = :id_clienthotel",'params'=>array(":id_clienthotel"=>$model->id_clienthotel),'order'=>'id DESC'));

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);
                if(isset($_POST['Ticks']))
                {
                    $my_tick->attributes=$_POST['Ticks'];
                    $my_tick->update();
                }
		if(isset($_POST['Documents']))
		{
                    
                    
			$model->attributes=$_POST['Documents'];
			if($model->save())
                        {
                            if(isset($_POST['DocumentsPrice']))
                            {
                                $exmodel->attributes=$_POST['DocumentsPrice'];
                                $exmodel->id_document = $id;
                                if($exmodel->price!=$model->price->price or $exmodel->node!=$model->price->node)
                                    if($exmodel->save())
                                     $this->redirect(array('admin','type'=>$model->post_type));
                            }
                            
                        }
				
		}
                
                
                $exmodel->node = "";
                
            
                
		$this->render('update',array(
			'model'=>$model,
                    'exmodel'=>$exmodel,
                    'listing'=>$listing,
                    'my_tick'=>$my_tick,
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
		$dataProvider=new CActiveDataProvider('Documents');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin($type='')
	{
		$model=new Documents('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Documents']))
			$model->attributes=$_GET['Documents'];
        
        
        $docs_for_drivers = Documents::model()->with('price')->findAll("t.status=0 and post_type='{$model->post_type}'");
                
                if($model->post_type=='')
                {
                    $all_found_fly_ticks = ClientHotel::model()->with(array('tickets_one'=>array('select'=>'sum(finish_sum) as finish_sum','condition'=>'tickets_one.status=6','group'=>'id_clienthotel')))->findAll(array('order'=>'t.id DESC','select'=>'t.id'));
                $array_with_all_found_fly_ticks = CHtml::listData($all_found_fly_ticks, 'id', 'tickets_one.finish_sum');
                }
        
		$this->render('admin',array(
			'model'=>$model,
                    'type'=>$type,
            'docs_for_drivers'=>$docs_for_drivers,
                    'array_with_all_found_fly_ticks'=>$array_with_all_found_fly_ticks,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer the ID of the model to be loaded
	 */
	public function loadModel($id)
	{
		$model=Documents::model()->findByPk($id);
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
		if(isset($_POST['ajax']) && $_POST['ajax']==='documents-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
        
    public function init()
    {
        if(!Users::getDostup(3)) 
        {
            throw new CHttpException(403,'Недостаточно прав доступа');
            die();
        }
    }
    
    
    public function actionChangeStatus()
    {
        if(Yii::app()->request->isAjaxRequest and isset($_POST['id']))
        {
            $id_doc = $_POST['id'];
            $id_clienthotel = $_POST['id_clienthotel'];
            $now = date('Y-m-d H:i');
            $now = Reports::correctDatePublic($now);
            Documents::model()->updateByPk($id_doc,array('status'=>1,'date_public'=>$now));
            self::tickwithsix($id_clienthotel);
            echo 'OK';
        }
        else throw new CHttpException(403,'Недостаточно прав доступа');
    }
    
    public function actionChangeStatusTick()
    {
        if(Yii::app()->request->isAjaxRequest and isset($_POST['id']))
        {
            $id_clienthotel = $_POST['id'];
            if(self::tickwithsix($id_clienthotel)) echo "OK";
            
        }
        else throw new CHttpException(403,'Недостаточно прав доступа');
    }
    
    public static function tickwithsix($id_clienthotel)
    {
            $found_all_fly_ticks = Ticks::model()->findAll("id_clienthotel = {$id_clienthotel} and status=6");
            if(count($found_all_fly_ticks)>0)
            {
                foreach($found_all_fly_ticks as $tick)
                {
                    $tick->status=1;
                    $tick->date_public = date('Y-m-d');
                    $tick->update();
                }
                return true;
            }
            else return false;
    }
}
