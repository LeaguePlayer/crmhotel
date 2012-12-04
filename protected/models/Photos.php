<?php

class Photos extends CActiveRecord
{
    public $id;
    public $image;
    public $hotel_id;
    public $type_id;
    public $data_sort;
    
    /**
	 * Returns the static model of the specified AR class.
	 * @return Hotels the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
    
    public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
            'hotel'=>array(self::BELONGS_TO, 'SiteHotels', 'hotel_id'),
		);
	}
    
    public function getDbConnection()
    {
        return Yii::app()->site_db;
    }
    
    public function tableName()
    {
         return 'tbl_photos';
    }
}