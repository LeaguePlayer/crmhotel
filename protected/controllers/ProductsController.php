<?php

class ProductsController extends Controller
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
				'actions'=>array('admin','delete','index','view','create','update','autocomplete','monetisation'),
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
		$this->redirect(array('admin'));
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
		$model=new Products;
                $cs=Yii::app()->getClientScript();
                $cs->registerScriptFile(Yii::app()->theme->baseUrl.'/js/products.js', CClientScript::POS_HEAD);
		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);
                $model->date_delivery = date('Y-m-d');
                        
		if(isset($_POST['Products']))
		{
                    $try_model = Products::model()->find("title = :title",array(':title'=>$_POST['Products']['title']));
                    
                    if(is_object($try_model))
                    {
                        unset($model);
                        $model = $try_model;
                        $exist_count= $try_model->brought_cnt;
                    }
                        
			$model->attributes=$_POST['Products'];
                        
                        $model->brought_cnt+=$exist_count;
                    
                        
                        
			if($model->save())
				$this->redirect(array('view','id'=>$model->id));
		}
                
                $model->brought_cnt = (is_numeric($model->brought_cnt) ? $model->brought_cnt : 0);
                $model->purchase_price = (is_numeric($model->purchase_price) ? $model->purchase_price : 0);

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

		if(isset($_POST['Products']))
		{
			$model->attributes=$_POST['Products'];
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
            $this->redirect(array('admin'));
		
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
                $cs=Yii::app()->getClientScript();
                $cs->registerScriptFile(Yii::app()->theme->baseUrl.'/js/products.js', CClientScript::POS_HEAD);
		$model=new Products('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Products']))
			$model->attributes=$_GET['Products'];

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
		$model=Products::model()->findByPk($id);
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
		if(isset($_POST['ajax']) && $_POST['ajax']==='products-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
        
    public function init()
    {
        $user = new Users;
        $id_user = $user->getMyId();
        
       
            $find_user = Users::model()->findByPk($id_user);
            if(is_object($find_user))
            {
               
              if($find_user->sauna_access==2) return true;
            }
            
        if(!Users::getDostup(2)) 
        {
            throw new CHttpException(403,'Недостаточно прав доступа');
            die();
        }
    }
    
    
    
    public function actionAutoComplete() 
    {
        if(Yii::app()->request->isAjaxRequest && isset($_GET['term'])) {
 
            $find = $_GET['term'];
            $criteria = new CDbCriteria;
            $criteria->condition = "title LIKE :tquery";
            $criteria->group = 'title';
            $criteria->distinct = true;
            $criteria->params = array(":tquery"=>'%'.$find.'%');
            $criteria->limit = 5;
            $queries = Products::model()->findAll($criteria);
            $resStr = array();
            $a = 0;
            foreach($queries as $tmpquery) 
            {
                $resStr[$a]["label"] = $tmpquery->title;
                $resStr[$a]["purchase_price"] = $tmpquery->purchase_price;
                $resStr[$a]["sales_price"] = $tmpquery->sales_price;
                $resStr[$a]["id_unit"] = $tmpquery->id_unit;
                //$resStr[$a]["learnedby"] = $tmpquery->learnedby;
                $a++;
            }
            echo CJSON::encode($resStr);       
        } 
    }
    
    public function actionmonetisation($id)
    {
        if(is_numeric($id))
        {
            $model_product = Products::model()->findByPk($id);
            if(is_object($model_product))
            {
                $cs=Yii::app()->getClientScript();
                $cs->registerCSSFile(Yii::app()->theme->baseUrl.'/css/money.css');
                
                $list_products_used = ProductUsed::model()->findAll(array('condition'=>"id_product = :id_product",'order'=>'id DESC','params'=>array(':id_product'=>$id)));
                $model = new ProductUsed;
                $model->id_product = $id;
                $model->date_used = date('Y-m-d');
                $model->price_for_sale = (is_numeric($model->price_for_sale) ? $model->price_for_sale : $model_product->sales_price);
                $actualBalance = Products::getActualBalance($id);
                
                
                if(isset($_POST['ProductUsed']))
                {
                    $model->attributes=$_POST['ProductUsed'];
                    
                    if($actualBalance<$model->count_used)
                        $error = "Вы не можете реализовать товара больше, чем у Вас на складе!";
                    else
                    {
                        if($model->save()) $this->refresh();
                    }
                }
                $model->id_invite = 1;
                $this->render('monetisation_form',array('model_product'=>$model_product,'model'=>$model,'error'=>$error,'list_products_used'=>$list_products_used));
                
            }
            else throw new CHttpException(404,'Не могу найти данную страницу.');
        }
        else throw new CHttpException(404,'Не могу найти данную страницу.');
        
    }
}
