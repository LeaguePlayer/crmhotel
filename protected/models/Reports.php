<?php

/**
 * This is the model class for table "reports".
 *
 * The followings are the available columns in table 'reports':
 * @property string $id
 * @property integer $id_user
 * @property string $date
 * @property string $array_report
 */
class Reports extends CActiveRecord
{
    public $verifyCode;
	/**
	 * Returns the static model of the specified AR class.
	 * @return Reports the static model class
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
		return 'reports';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
	   
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('id_user, date, array_report', 'required'),
			array('id_user', 'numerical', 'integerOnly'=>true),
            array(
                'verifyCode',
                'captcha',
                // авторизованным пользователям код можно не вводить
               
            ),
                    array('dublicate_report', 'length', 'max'=>255),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, id_user,dublicate_report, verifyCode, date, array_report', 'safe', 'on'=>'search'),
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
			'id_user' => 'Id User',
			'date' => 'Дата отчёта',
			'array_report' => 'Array Report',
                        'dublicate_report'=>'Дубликат отчета',
                        'verifyCode' => 'Код проверки',
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
		$criteria->compare('id_user',$this->id_user);
		$criteria->compare('date',$this->date,true);
		$criteria->compare('array_report',$this->array_report,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
    
    public function correctDatePublic($date)
    {
        $sql_date = date('Y-m-d',strtotime($date));
        $last_day = date('Y-m-d',strtotime("-1 day".$date));
        $next_day = date('Y-m-d',strtotime("+1 day".$date));
        $find_ex_report = self::model()->count("date = :date_public",array(':date_public'=>$last_day));
        if($find_ex_report==0)
            return $last_day;
        else
        {
            $find_today_report = self::model()->count("date = :date_public",array(':date_public'=>$sql_date));
            if($find_today_report==0)
                return $sql_date;
            else
            return $next_day;
        }
    }
}