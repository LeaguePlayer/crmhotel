<?php

class SiteHotelsOptions extends CActiveRecord
{    
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
		);
	}
    
    public function getDbConnection()
    {
        return Yii::app()->site_db;
    }
    
    public function tableName()
    {
         return 'tbl_hotels_options';
    }
}