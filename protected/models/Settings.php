<?php

/**
 * This is the model class for table "settings".
 *
 * The followings are the available columns in table 'settings':
 * @property string $id
 * @property integer $sauna_last_update
 */
class Settings extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return Settings the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'settings';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('sauna_last_update', 'required'),
			array('sauna_last_update', 'numerical', 'integerOnly'=>true),
            array('message', 'length', 'max'=>5000),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, sauna_last_update,message', 'safe', 'on'=>'search'),
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

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'sauna_last_update' => 'Sauna Last Update',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id,true);
		$criteria->compare('sauna_last_update',$this->sauna_last_update);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
    
    public static function updateTime()
    {
        $settings = self::model()->findByPk(1);
        $settings->sauna_last_update = time();
        $settings->save();
        return true;
    }
}