<?php

class SitePage extends CActiveRecord
{
    /**
	 * Returns the static model of the specified AR class.
	 * @return Hotels the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
    
    public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('content, title, url', 'required'),
			array('visible', 'numerical', 'integerOnly'=>true),
			array('meta_description, meta_title, meta_keywords, title, url', 'length', 'max'=>255),
            array('url', 'match', 'pattern' => '/^[A-Za-z_]*$/', 'message' => 'Разрешённые символы: строчные буквы латинского алфавита и знак подчеркивания.'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('url,content,title,meta_description,meta_title,meta_keywords', 'safe', 'on'=>'search'),
		);
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
         return 'tbl_pages';
    }
    
    public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'title' => 'Заголовок',
            'content' => 'Текст страницы',
            'url' => 'URL',
			'visible' => 'Показывать на сайте',
		);
	}
    
    public function search()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}