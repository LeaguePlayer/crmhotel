<?php

class ApiController extends Controller
{
    /**
	 * Specifies the access control rules.
	 * This method is used by the 'accessControl' filter.
	 * @return array access control rules
	 */
	public function accessRules()
	{
		return array(
			array('allow',  // allow all users to perform 'index' and 'view' actions
				'actions'=>array('getPhotos'),
				'users'=>array('*'),
			),
		
		);
	}
    
    
    /**
     * Синхронизация с сайтом
     * company_id = 0 соответсвует квартирам crm
     * city_id = 1 - Тюмень
     */
    public function actionSincHotels()
    {
        
//        $siteHotels = SiteHotels::model()->findAll();
//        foreach ($siteHotels as $row)
//        {
//            $row->post_type = "homecity";
//            $row->save(false);
//        }
//        die();
        $deleted = 0;
        $updated = 0;
        $inserted = 0;
        // Удаляем с сайта несуществующие
        $siteHotels = SiteHotels::model()->findAll('company_id=0');
        foreach ($siteHotels as $row)
        {
            $crmHotel = Hotels::model()->findByAttributes(array('id'=>$row->post_id));
            if (!$crmHotel) {
                $deleted += ($row->delete()) ? 1 : 0;
            }
        }
        // Обновляем остальное
        $crmHotels = Hotels::model()->with('options')->findAll(array(
            'order' => 't.id'
        ));
        foreach ($crmHotels as $source)
        {
            $siteHotel = SiteHotels::model()->findByAttributes(array('post_id'=>$source->id, 'post_type'=>'homecity'));
            if ($siteHotel)
            {
                $isUpdate = false;
                if($source->name != $siteHotel->street) {
                    $siteHotel->street = $source->name;
                    $isUpdate = true;
                }
                if($source->cost != $siteHotel->cost) {
                    $siteHotel->cost = $source->cost;
                    $isUpdate = true;
                }
                if($source->square && $source->square != $siteHotel->square) {
                    $siteHotel->square = $source->square;
                    $isUpdate = true;
                }
                if($siteHotel->city_id != 1) {
                    $siteHotel->city_id = 1;
                    $isUpdate = true;
                }
                if($source->id_cat != $siteHotel->cat_id) {
                    $siteHotel->cat_id = $source->id_cat;
                    $isUpdate = true;
                }
                if($source->full_desc != $siteHotel->full_desc) {
                    $siteHotel->full_desc = $source->full_desc;
                    $isUpdate = true;
                }
                if($source->short_desc != $siteHotel->short_desc) {
                    $siteHotel->short_desc = $source->short_desc;
                    $isUpdate = true;
                }
                
                
                // Обновление связанной таблицы с опциями
                $arrSiteOptIds = array();
                $arrCrmOptIds = array();
                foreach($source->options as $crmOpt)
                {
                    $arrCrmOptIds[] = $crmOpt->id;
                }
                $siteOptions = SiteHotelsOptions::model()->findAll('hotel_id='.$siteHotel->id);
                foreach($siteOptions as $sOpt)
                {
                    $arrSiteOptIds[] = $sOpt->option_id;
                }
                
                $allOptions = Option::model()->findAll();
                foreach($allOptions as $opt)
                {
//                    print_r($opt->id);print_r($arrSiteOptIds);print_r($arrCrmOptIds);
//                    print_r("inSite:".in_array($opt->id, $arrSiteOptIds));
//                    print_r("inCrm:".in_array($opt->id, $arrCrmOptIds)."<br>");
                    if (in_array($opt->id, $arrSiteOptIds) && !in_array($opt->id, $arrCrmOptIds))
                    {
                        SiteHotelsOptions::model()->findByAttributes(array(
                            'hotel_id'=>$siteHotel->id,
                            'option_id'=>$opt->id
                        ))->delete();
                        $isUpdate = true;
                        //print_r("-опция ".$opt->id."<br>");
                    }
                    else if (!in_array($opt->id, $arrSiteOptIds) && in_array($opt->id, $arrCrmOptIds))
                    {
                        Yii::app()->site_db->createCommand()->insert('tbl_hotels_options', array(
                            'hotel_id' => $siteHotel->id,
                            'option_id' => $opt->id,
                        ));
                        $isUpdate = true;
                        //print_r("+опция ".$opt->id."<br>");
                    }
                }
                
                if($isUpdate) {
                    $updated += ($siteHotel->save(false)) ? 1 : 0;
                }
                
            }
            else {
                $siteHotel = new SiteHotels;
                $siteHotel->SID = $source->SID;
                $siteHotel->street = $source->name;
                $siteHotel->cost = $source->cost;
                $siteHotel->square = $source->square;
                $siteHotel->rooms = $source->id_cat;
                $inserted += ($siteHotel->save(false)) ? 1 : 0;
            }
        }
        $this->render('_sincInfo', array(
            'deleted'=>$deleted,
            'updated'=>$updated,
            'inserted'=>$inserted,
        ));
    }
    
//    /**
//     * Забираем из БД сайта JSON-массив квартир
//     */
//    public function actionGetJsonHotels($id = null, $page = 1, $rows_count = -1)
//    {
//    	if ($rows_count <= 0)
//    		$rows_count = Yii::app()->params['rows_count'];
//		$criteria = new CDbCriteria;
//        //$criteria->join = 'join tbl_photos ON t.id=hotel_id';
//		$criteria->offset = ($page - 1) * $rows_count;
//		$criteria->limit = $rows_count;
//        if ($id)
//            $siteHotels = array(SiteHotels::model()->findByPk($id));
//        else
//            $siteHotels = SiteHotels::model()->findAll($criteria);
//        $result = array();
//        foreach ($siteHotels as $hotel)
//        {
//            $result[$hotel->id]['street'] = $hotel->street;
//            $result[$hotel->id]['cost'] = $hotel->cost;
//            $result[$hotel->id]['square'] = $hotel->square;
//            $result[$hotel->id]['rooms'] = $hotel->rooms;
//            foreach ($hotel->photos as $k => $photo)
//            {
//                $result[$hotel->id]['photos'][$photo->id]['url'] = $photo->name;
//                $result[$hotel->id]['photos'][$photo->id]['data_sort'] = $photo->data_sort;
//                $result[$hotel->id]['photos'][$photo->id]['type_photo'] = $photo->type_id;
//            }
//        }
//        //echo "<pre>";
//        echo json_encode($result);
//        //echo "</pre>";
//    }

    
    /**
     *  Дата начала и конца брони квартиры в указанный период времени
     */
    public function actionGetJsonOrder($id, $month, $year)
    {
        switch ($month)
        {
            case 0:
                $month = '12';
                break;
            case 1:
            case 2:
            case 3:
            case 4:
            case 5:
            case 6:
            case 7:
            case 8:
            case 9:
                $month = '0'.$month;
                break;
        }
        if (count(str_split("$year", 1)) == 2) $year = '20'.$year;
        
        $date_begin = $year.'-'.$month.'-01 00:00:01';
        $date_end = $year.'-'.$month.'-31 23:59:59';
        $criteria = new CDbCriteria;
        $criteria->order = 'date_stay_begin ASC';
        $criteria->addCondition('id_hotel=' . $id);
        $criteria->addBetweenCondition('date_stay_begin', $date_begin, $date_end);
        $hotel_order = HotelOrder::model()->findAll($criteria);
        foreach ($hotel_order as $h_ord)
        {
            $ret[] = array(
                'date_begin' => $h_ord->date_stay_begin,
                'date_end' => $h_ord->date_stay_finish,
            );
        }
        echo json_encode($ret);
    }
    
    /**
     *  Получение фоток квартиры с сайта
     */
    public function actionGetPhotos()
    {
        $criteria = new CDbCriteria;
        if (isset($_POST['hotel_id']))
        {
            $siteHotel = SiteHotels::model()->findByAttributes(array('post_id'=>$_POST['hotel_id'], 'post_type'=>'homecity'));
            $criteria->addCondition('hotel_id='.$siteHotel->id);
            $criteria->order = 'data_sort';
        }
        if (isset($_POST['photos']))
        {
            $id_photos = (json_decode($_POST['photos']));
            $criteria->addInCondition('id', $id_photos);
        }
            
        $photos = Photos::model()->findAll($criteria);
        foreach ($photos as $photo)
        {
            $data[] = array(
                'id' => $photo->id,
                'name' => $photo->name,
                'type' => $photo->type_id,
            );
        }
        echo json_encode($data);
    }
    
    /**
     * @param $source = 'psearc' - people's map (search on the name of infrastructure) OR
     * @param $source = 'geocode' - yandex official map (search for addresses and coordinates);
     */
    public function getCoordsFromYandexMap($text, $results = 10, $fullresponse = false, $source = 'psearch')
    {
        $params = array(
    		'text' => $text, 		// текст запроса
    		'format'  => 'json',    // формат ответа
    		'results' => $results,  // количество выводимых результатов
			'lang'    => 'ru',
            //'ll'      => '1.111, 1.111',            // задаёт долготу и широту центра области поиска(в градусах)
            //'spn'     => '0.555, 0.555',            // область поиска (в градусах)
            //'rspn'    => '0',           // ограничить ли поиск объектов областью, заданной с помощью параметров ll и spn
		);
        switch ($source)
        {
            default: {
                $response = json_decode(file_get_contents('http://psearch-maps.yandex.ru/1.x/?'.http_build_query($params, '', '&')));
                $counts = $response->response->GeoObjectCollection->metaDataProperty->PSearchMetaData->PSearchRequest->results;
                if ($counts === 0) return $this->getCoordsFromYandexMap($text, $results, $fullresponse, 'geocode');
            }
            case 'geocode': {
                $params['geocode'] = $params['text'];
                unset($params['text']);
                $response = json_decode(file_get_contents('http://geocode-maps.yandex.ru/1.x/?'.http_build_query($params, '', '&')));
                $counts = $response->response->GeoObjectCollection->metaDataProperty->GeocoderResponseMetaData->found;
            }
        }
        //echo "<pre>";
        //print_r($response);
		if ($counts > 0)
        {
            if ($fullresponse) return $response;
            $pos = $response->response->GeoObjectCollection->featureMember[0]->GeoObject->Point->pos;
            preg_match_all("/\d+\.\d+/", $pos, $coords);
            return $coords[0];
        }
        return null;
    }
    
    /**
     * Скрипт заполнения таблицы координат в базе данных сайта
     */
    public function actionSetHotelsCoordsAutomatic($id = null)
    {
        if ($id)
            $siteHotels = array( SiteHotels::model()->findAllByAttributes(array('post_id'=>$id, 'post_type'=>'homecity')) );
        else
            $siteHotels = SiteHotels::model()->findAll();
        
        $succes = 0;
        $count = count($siteHotels);
        foreach ($siteHotels as $hotel)
        {
            $coords = $this->getCoordsFromYandexMap("Тюмень ".$hotel->street, 1, false, 'geocode');
            if ($coords)
            {
                $hotel->coord1 = $coords[0];
                $hotel->coord2 = $coords[1];
                $count--;
                if ($hotel->save())
                {
                    $succes++;
                }
            }
        }
        echo "Обновлено $succes записей";
    }
    
    /**
     * Данный метод преобразовывает полученные фотографии в строки
     * и отправляет их на контроллер сайта.
     */
    public function actionUploadPhoto()
    {
        if(is_numeric($_POST['hotel_id']))
        {
            $siteHotel = SiteHotels::model()->findByAttributes(array('post_id'=>$_POST['hotel_id'], 'post_type'=>'homecity'));
            if (!$siteHotel)
            {
                echo "Не найдено квартиры.";
                exit;
            }
            $hotel_id = $siteHotel->id;
            $url_info = parse_url(Yii::app()->params['siteDomain'].'/index.php/hotels/addPhoto');
            $files = self::fixGlobalFilesArray($_FILES);
            if (empty($_FILES))
            {
                echo "Файл не загружен";
                die();
            }
            else
            {
                $counter = 0;
                foreach ($files['Photos'] as $key => $file)
                {
                    if($file["size"] > 1024*3*1024)
                    {
                        echo ("Размер файла превышает три мегабайта");
                        exit;
                    }
                    // Проверяем загружен ли файл
                    if(is_uploaded_file($file["tmp_name"]))
                    {
                        // Если файл загружен успешно, переименовываем и запихиваем его в массив
                        $file_ext = fnc::getExtensionFile($file["name"], true);
                        $file['name'] = md5($file["name"].time().date('y-m-d')).$file_ext;
                        // массив для передачи ответа клиентскому скрипту
                        $names[] = $file['name'];
                        $fp = fopen($file['tmp_name'], "r");
                        $content = fread($fp, $file['size']);
                        fclose($fp);
                        $data['name'.$counter] = $file['name'];
                        $data['content'.$counter] = base64_encode($content);
                        $counter++;
                    }
                    else echo("Ошибка загрузки файла");
                }
                $data['hotel_id'] = $hotel_id;
                // подготавливаем массив перед отправкой его на контроллер сайта
                $data = http_build_query($data, '', '&');
                // формирование заголовков и отправка на сайт
                $fp = fsockopen(@$url_info['host'], 80, $errno, $errstr, 6);
                if (!$fp) die("0|Не могу соединиться с ".@$url_info['host']);
                
                $out  = "POST ".@$url_info['path']." HTTP/1.1\r\n";
                $out .= "Host: ".@$url_info['host']."\r\n";
                $out .= "Content-Type: application/x-www-form-urlencoded\r\n";
                $out .= "Content-Length: ".strlen($data)."\r\n";
                $out .= "Connection: close\r\n\r\n";
                $out .= $data;
                
                fputs($fp, $out);
                $in = '';
                while (($line = fgets($fp, 8192))!==false) $in .= $line;
                fclose($fp);
                $part = preg_split("/\r\n\r\n/", $in);
                echo json_encode(preg_split("/,/", $part[1]));
            }
        }
    }
    
    public function actionRemovePhoto()
    {
        if(isset($_POST['id_photo']))
        {
            $url_info = parse_url(Yii::app()->params['siteDomain'].'/index.php/hotels/deletePhoto');
            $data = 'id_photo=' . $_POST['id_photo'];
            // формирование заголовков и отправка на сайт
            $fp = fsockopen(@$url_info['host'], 80, $errno, $errstr, 6);
            if (!$fp) die("0|Не могу соединиться с ".@$url_info['host']);
            
            $out  = "POST ".@$url_info['path']." HTTP/1.1\r\n";
            $out .= "Host: ".@$url_info['host']."\r\n";
            $out .= "Content-Type: application/x-www-form-urlencoded\r\n";
            $out .= "Content-Length: ".strlen($data)."\r\n";
            $out .= "Connection: close\r\n\r\n";
            $out .= $data;
            
            fputs($fp, $out);
            $in = '';
            while (($line = fgets($fp, 8192))!==false) $in .= $line;
            fclose($fp);
            
            if (substr_count($in, 'Не могу удалить') > 0)
                echo "Облом";
            else
                echo "OK";
        }
    }
    
    public function actionUpdateDataPhotos()
    {
        if (isset($_POST['sort_photos']))
        {
            foreach ($_POST['sort_photos'] as $data_sort => $id_photo)
            {
                Yii::app()->site_db->createCommand()
                    ->update('tbl_photos', array('data_sort' => $data_sort), 'id='.$id_photo);
            }
        }
        
        if (isset($_POST['update_type_photo']))
        {
            $type = $_POST['update_type_photo']['type'];
            $id_photo = $_POST['update_type_photo']['id_photo'];
            Yii::app()->site_db->createCommand()
                ->update('tbl_photos', array('type_id' => $type), 'id='.$id_photo);
        }
    }
    
    public static function fixGlobalFilesArray($files)
    {
        $ret = array();
        if(isset($files['tmp_name']))
        {
            if (is_array($files['tmp_name']))
            {
                foreach($files['name'] as $idx => $name)
                {
                    $ret[$idx] = array(
                        'name' => $name,
                        'tmp_name' => $files['tmp_name'][$idx],
                        'size' => $files['size'][$idx],
                        'type' => $files['type'][$idx],
                        'error' => $files['error'][$idx]
                    );
                }
            }
            else
            {
                $ret = $files;
            }
        }
        else
        {
            foreach ($files as $key => $value)
            {
                $ret[$key] = self::fixGlobalFilesArray($value);
            }
        }
        return $ret;
    }
}
