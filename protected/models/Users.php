<?php

/**
 * This is the model class for table "users".
 *
 * The followings are the available columns in table 'users':
 * @property string $id
 * @property string $username
 * @property string $password
 * @property integer $access
 * @property string $works_to
 */
class Users extends CActiveRecord
{
    
    public $myid = null;
	/**
	 * Returns the static model of the specified AR class.
	 * @return Users the static model class
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
		return 'users';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('username, password, access', 'required'),
			array('access,sauna_access', 'numerical', 'integerOnly'=>true),
			array('username, password, works_to', 'length', 'max'=>255),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, username,sauna_access, password, access, works_to', 'safe', 'on'=>'search'),
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
			'username' => 'Логин',
			'password' => 'Пароль',
			'access' => 'Уровень доступа',
			'works_to' => 'Время работы',
            'sauna_access'=>'Доступ к сауне',
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
		$criteria->compare('username',$this->username,true);
		$criteria->compare('password',$this->password,true);
		$criteria->compare('access',$this->access);
		$criteria->compare('works_to',$this->works_to,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
    
    
        public function getAllUsers($check = true)
    {
        $objs = self::model()->findAll();
        if($check)
        $list[0] = 'Не привязывать аккаунт';
        foreach ($objs as $obj)
        {
           
            $list[$obj->id] = $obj->username;
        }
        
        return $list;
    }
    
    
    public function getUser($id)
    {
        $objs = self::model()->findByPk($id);
       
       
        
        return $objs;
    }
    
    
        public function getMyId()
    {
         $session=new CHttpSession;
          $session->open();
          $this->myid = $session['user_id'];
          $session->close();
          return $this->myid;
    }
    
    public function Logout()
    {
        $session=new CHttpSession;
          $session->open();
          unset($session['user_id']);
          $session->close();
          Yii::app()->user->logout();
          return true;
    }
    
    public  function isAdmin($id)
    {
        $user = Users::model()->findByPk($id);
        if($user->access==1)
            return true;
        else return false;
    }
    
    public function getAccess()
    {
          $session=new CHttpSession;
          $session->open();
          $this->myid = $session['user_id'];
          $session->close();
          $user = self::model()->findByPk($this->myid);
          return $user->access;
    }
    
    public function sauna_access_check()
    {
          $session=new CHttpSession;
          $session->open();
          $this->myid = $session['user_id'];
          $session->close();
          $user = self::model()->findByPk($this->myid);
          if($user->sauna_access==1)
          return true;
          else return false;
    }
    
    protected function beforeSave()
    {
       
        
        if($this->access!=5)
            $this->works_to = '0000-00-00 00:00:00';
        
        return true;
    }
    
    public function getDostup($access,$got_all=false)
    {
       
        $now = date('Y-m-d H:i:s');
        $user = new Users;
        $myid = $user->getMyId();
    
         
        $obj_allow_user = self::model()->findByPk($myid);
        
        
        if(is_object($obj_allow_user))
        {
             if( ($obj_allow_user->access <= $access and $obj_allow_user->access!=4) or $got_all )             
             {
                if($obj_allow_user->access==5)
                {                    
                    
                    if(strtotime($now) <= strtotime($obj_allow_user->works_to))
                        return true;
                    else return false;
                }
                return  true;
             }
             else return false;
        }
        else return false;
        
    
       
        return $array_allow;
    }
}