<?php

class SiteHotels extends CActiveRecord
{
    public $id;//id
    public $street;//name
    public $cost;//cost
    public $square;
    //public $sleeps;
    public $rooms;//cat_id
    
    /**
	 * Returns the static model of the specified AR class.
	 * @return Hotels the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
    
    /**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
            'photos'=>array(self::HAS_MANY, 'Photos', 'hotel_id'),
            'photosCount'=>array(self::STAT, 'Photos', 'hotel_id'),
            'coords'=>array(self::HAS_ONE, 'Coords', 'hotel_id'),
		);
	}
    
    public function getDbConnection()
    {
        return Yii::app()->site_db;
    }
    
    public function tableName()
    {
         return 'tbl_hotels';
    }
    
    protected function afterDelete()
    {
        SiteHotelsOptions::model()->deleteAll('hotel_id='.$this->id);
    }
}