<?php

/**
 * This is the model class for table "sql_logs".
 *
 * The followings are the available columns in table 'sql_logs':
 * @property string $id
 * @property integer $id_user
 * @property integer $sid
 * @property integer $id_action
 * @property string $sql_query
 * @property string $short_desc
 * @property string $change_time
 */
class SqlLogs extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return SqlLogs the static model class
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
		return 'sql_logs';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('id_user,status,post_type, sid, id_action, sql_query, short_desc, change_time', 'required'),
			array('id_action,tmp_id,post_id', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, tmp_id, id_user,status, sid,post_id,post_type, id_action, sql_query, short_desc, change_time', 'safe', 'on'=>'search'),
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
			'sid' => 'Sid',
			'id_action' => 'Id Action',
			'sql_query' => 'Sql Query',
			'short_desc' => 'Short Desc',
			'change_time' => 'Change Time',
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
		$criteria->compare('sid',$this->sid);
		$criteria->compare('id_action',$this->id_action);
		$criteria->compare('sql_query',$this->sql_query,true);
		$criteria->compare('short_desc',$this->short_desc,true);
		$criteria->compare('change_time',$this->change_time,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
    
    public static function saveLOG($id_user,$sid,$id_action,$sql_query,$short_desc,$change_time,$post_type,$post_id,$tmp_id=0)
    {
        $user = new Users;
             $id_user  = $user->getMyId();
            $sql_log = new SqlLogs;
            $sql_log->id_user = $id_user;
            $sql_log->sid = $sid;
            $sql_log->id_action = $id_action;
            $sql_log->sql_query = serialize($sql_query);
            $sql_log->short_desc = $short_desc;
            $sql_log->change_time = $change_time;
            $sql_log->post_type = $post_type;
            $sql_log->post_id = $post_id;
            $sql_log->tmp_id = $tmp_id;
            $sql_log->status=0;
            $sql_log->save();
    }
}