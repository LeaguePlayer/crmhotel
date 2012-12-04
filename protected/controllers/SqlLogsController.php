<?php

class SqlLogsController extends Controller
{
	//public function actionIndex($id_log=false)
//	{
//	   if(is_numeric($id_log))
//       {
//          $log = SqlLogs::model()->findByPk($id_log);
//          
//          
//                  $array_info = unserialize($log->sql_query);
//                  
//                  switch ($array_info['table'])
//                  {
//                    case 'hotel_order':
//                       
//                          switch ($log->id_action)
//                          {
//                             
//                                
//                                
//                                case '1':
//                                    $modeler = HotelOrder::model()->findByPk($array_info['data']['id']);
//                                    
//                                    foreach ($array_info['data'] as $key=>$value)
//                                    {
//                                        if($key!='id')
//                                        $modeler->$key = $value;
//                                    }
//                                    
//                                    
//                                     $modeler->update();  
//                                    
//                                     
//                                break;
//                                
//                                
//                                case '0':
//                                    $modeler = HotelOrder::model()->deleteByPk($array_info['data']['id']);
//                                break;
//                          }
//                    break;
//                      
//                    case 'ticks':
//                    switch ($log->id_action)
//                          {
//                         case '1':
//                                    $modeler = Ticks::model()->findByPk($array_info['data']['id']);
//                                    foreach ($array_info['data'] as $key=>$value)
//                                    {
//                                        $modeler->$key = $value;
//                                    }
//                                     $modeler->update();                                   
//                                break;
//                                
//                                
//                                case '0':
//                                    $modeler = Ticks::model()->deleteByPk($array_info['data']['id']);
//                                break;
//                         }
//                    break;
//                    
//                    case 'client_hotel':
//                    switch ($log->id_action)
//                          {
//                         case '1':
//                        
//                                    $modeler = ClientHotel::model()->findByPk($array_info['data']['id']);
//                                    foreach ($array_info['data'] as $key=>$value)
//                                    {
//                                        $modeler->$key = $value;
//                                    }
//                                     $modeler->update();                                   
//                                break;
//                                
//                                
//                                case '0':
//                                    $modeler = ClientHotel::model()->deleteByPk($array_info['data']['id'],'',array(),$log->id);
//                                break;
//                        }
//                    break;
//                    
//                    case 'hotels':
//                    switch ($log->id_action)
//                          {
//                         case '1':
//                                    $modeler = Hotels::model()->findByPk($array_info['data']['id']);
//                                    foreach ($array_info['data'] as $key=>$value)
//                                    {
//                                        $modeler->$key = $value;
//                                    }
//                                     $modeler->update();                                   
//                                break;
//                                
//                                
//                                case '0':
//                                    $modeler = Hotels::model()->deleteByPk($array_info['data']['id']);
//                                break;
//                                }
//                    break;
//                                      
//                  }
//                         
//                 $log->status=1;
//                 $log->save();
//                 HotelOrder::updateTime();
//          
//          
//          $this->redirect(array('sqlLogs/index'));
//       }
//	   Yii::app()->getModule('user');
//       $user = UserModule::user(Yii::app()->user->getId());
//	   if($user->superuser==1)
//       {
//            $sqls = SqlLogs::model()->findAll(array('order'=>'change_time DESC,id DESC'));
//            $title = 'Вся история действий пользователей';
//       }
//       else
//       {
//            $sid = Yii::app()->session->sessionID;
//            $sqls = SqlLogs::model()->findAll(array('condition'=>"sid='$sid'",'order'=>'change_time DESC,id DESC'));
//            $title = 'История действий пользователя '.$user->username;
//       }
//	    
//		$this->render('index',array('sqls'=>$sqls,'title'=>$title));
//	}
    
    public function actionGoBack($agree=false)
    {
        
        session_start();
        $sid = session_id();
        
        $user = new Users;
        $id_user  = $user->getMyId();
        
        $last_move = SqlLogs::model()->find(array('order'=>"id DESC",'condition'=>'status=0 and id_user = '.$id_user.' and sid = "'.$sid.'"'));
        
     
        
        if(count($last_move)<=0) die("Нет действий");
        
       
        
        
        $all_last_moves = SqlLogs::model()->findAll("post_id = {$last_move->post_id} and post_type='{$last_move->post_type}' and id_user = {$id_user} and status = 0 and sid='$sid'");
        
        if(!$agree) 
        {
            $status = false;
            $cnt_lists = 0;
            $ul  ='<ul>';
            foreach ($all_last_moves as $move)
            {
                $act='';
                $array_info = unserialize($move->sql_query);
                $table = $array_info['table'];
                switch ($table)
                {
                    case'hotel_order':
                        $modeler_ho = HotelOrder::model()->findByPk($array_info['data']['id']);
                        foreach ($array_info['data'] as $key=>$value)
                        {
                            if($modeler_ho->$key!=$value) $list['ho'][$key]=$value;
                            if($key=='status') $status = $modeler_ho->$key;
                        }
                       
                    break;
                    case'client_hotel':
                        $modeler_ch = ClientHotel::model()->findByPk($array_info['data']['id']);
                        foreach ($array_info['data'] as $key=>$value)
                        {
                            if($modeler_ch->$key!=$value) $list['ch'][$key]=$value;
                        }
                        
                    break;                    
                    case'ticks':
                        $modeler_t = Ticks::model()->findByPk($array_info['data']['id']);
                        foreach ($array_info['data'] as $key=>$value)
                        {
                            if($modeler_t->$key!=$value) $list['t'][$key]=$value;
                        }
                        
                    break;
                }
                $cnt_lists+=count($list);
                
            }
            if($cnt_lists==0) 
            {
                $status = $modeler_ho->status;
                switch ($status)
                {
                    case '6':
                        $act ="ТУЦ Бронирование с предоплатой";
                    break;
                    case '5':
                        $act ="ТУЦ Бронирование";
                    break;
                    case '4':
                        $act ="ТУЦ Заселение";
                    break;
                    
                    case '3':
                        $act ="Бронирование с предоплатой";
                    break;
                    
                    case '2':
                        $act ="Выселение";
                    break;
                    
                    case '1':
                        $act ="Бронирование";
                    break;
                    
                    case '0':
                        $act ="Заселение";
                    break;
                }
            }
            else
            {
              //print_r($list);die();
                if(isset($list['ho']['broken_begin']))
                {
                    if($list['ho']['broken_begin']=='0000-00-00 00:00:00')
                    {
                        $act = 'Продление без предоплаты';
                    }
                    else
                    {
                        $act = 'Оплата продления без предоплаты';
                    }
                }
                elseif(count($list['ho'])>=2)
                {
                    if(isset($list['ho']['date_stay_finish']) and isset($list['ho']['create_time']) and count($list['ho'])==2)
                    {
                        $act = "Продление с предоплатой или изменение брони";
                    }
                    elseif(isset($list['ho']['status']) and isset($list['ch']['status']))
                    {
                        if($modeler_ho->status==2)
                        {
                            $act="Выселение";
                        }
                      
                    }
                    elseif(isset($list['ho']['status']) and isset($list['ho']['create_time']))
                    {
                        
                        switch ($modeler_ho->status)
                        {
                            case '6':
                                $act ="ТУЦ Бронирование с предоплатой или изменение статуса";
                            break;
                            case '5':
                                $act ="ТУЦ Бронирование или изменение статуса";
                            break;
                            case '4':
                                $act ="ТУЦ Оплата брони или изменение статуса";
                            break;
                            
                            case '3':
                                $act ="Бронирование с предоплатой  или изменение статуса";
                            break;
                            
                            case '2':
                                $act ="Выселение или изменение статуса";
                            break;
                            
                            case '1':
                                $act ="Бронирование или изменение статуса";
                            break;
                            
                            case '0':
                                $act ="Оплата брони или изменение статуса";
                            break;
                        }
                    }
                }
                
            }
            
            
            if($act!=''){$ul  .='<li>'.$act.'</li>';}
            $ul  .='</ul>';
            $text = "Точно отменить данное действие?$ul<dd><dt class=\"back_action_yes left\" href=\"/sqlLogs/goback?agree=1\">Да</dt><dt class=\"back_action_no right\" href=\"javascript:void(0);\">Нет</dt></dd>";
            $script = "select_back_action({$last_move->post_id},'$text');";
             $this->renderPartial('/site/_scripts_load',array('script'=>$script),false,true);
            
            die();
        }
         
        
        foreach ($all_last_moves as $log)
        {
            $array_info = unserialize($log->sql_query);
                
                  switch ($array_info['table'])
                  {
                    case 'hotel_order':
                       
                          switch ($log->id_action)
                          {
                             
                                
                                
                                case '1':
                                    $modeler = HotelOrder::model()->findByPk($array_info['data']['id']);
                                    
                                    foreach ($array_info['data'] as $key=>$value)
                                    {
                                        if($key!='id')
                                        $modeler->$key = $value;
                                    }
                                    
                                    
                                     $modeler->update(null,0);  
                                    
                                     
                                break;
                                
                                
                                case '0':
                                    $modeler = HotelOrder::model()->deleteByPk($array_info['data']['id']);
                                break;
                          }
                    break;
                      
                    case 'ticks':
                    switch ($log->id_action)
                          {
                         case '1':
                                    $modeler = Ticks::model()->findByPk($array_info['data']['id']);
                                    foreach ($array_info['data'] as $key=>$value)
                                    {
                                        $modeler->$key = $value;
                                    }
                                     $modeler->update(null,'vpizdy');                                   
                                break;
                                
                                
                                case '0':
                                    $modeler = Ticks::model()->deleteByPk($array_info['data']['id']);
                                break;
                         }
                    break;
                    
                    case 'client_hotel':
                    switch ($log->id_action)
                          {
                         case '1':
                        
                                    $modeler = ClientHotel::model()->findByPk($array_info['data']['id']);
                                    foreach ($array_info['data'] as $key=>$value)
                                    {
                                        $modeler->$key = $value;
                                    }
                                     $modeler->update(null,0);                                   
                                break;
                                
                                
                                case '0':
                                    $modeler = ClientHotel::model()->deleteByPk($array_info['data']['id'],'',array(),$log->id);
                                break;
                        }
                    break;
                    
                    case 'extension_order':
                  
                    
                    switch ($log->id_action)
                          {       
                                case '0':
                                    
                                    $modeler = ExtensionOrder::model()->deleteByPk($array_info['data']['id']);
                                   
                                break;
                        }
                    break;
                    
                    case 'documents':
                  
                    
                    switch ($log->id_action)
                          {       
                                case '0':
                                    
                                    $modeler = Documents::model()->deleteByPk($array_info['data']['id']);
                                   
                                break;
                        }
                    break;
                    
                    case 'hotels':
                    switch ($log->id_action)
                          {
                         case '1':
                                    $modeler = Hotels::model()->findByPk($array_info['data']['id']);
                                    foreach ($array_info['data'] as $key=>$value)
                                    {
                                        $modeler->$key = $value;
                                    }
                                     $modeler->update();                                   
                                break;
                                
                                
                                case '0':
                                    $modeler = Hotels::model()->deleteByPk($array_info['data']['id']);
                                break;
                                }
                    break;
                                      
                  }
                         
                 $log->status=1;
                 $log->save();
                
        }
         HotelOrder::updateTime();
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