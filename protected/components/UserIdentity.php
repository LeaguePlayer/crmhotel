<?php

/**
 * UserIdentity represents the data needed to identity a user.
 * It contains the authentication method that checks if the provided
 * data can identity the user.
 */
class UserIdentity extends CUserIdentity
{
    
    public $myid = null;
	/**
	 * Authenticates a user.
	 * The example implementation makes sure if the username and password
	 * are both 'demo'.
	 * In practical applications, this should be changed to authenticate
	 * against some persistent user identity storage (e.g. database).
	 * @return boolean whether authentication succeeds.
	 */
	public function authenticate()
	{
	   $this->password = md5($this->password);
	   $find_user = Users::model()->findAll();
    
       foreach ($find_user as $user)        
            $users[$user->username] = $user->password;
             
        	
	        
		if(!isset($users[$this->username]))
			$this->errorCode=self::ERROR_USERNAME_INVALID;
		else if($users[$this->username]!==$this->password)
			$this->errorCode=self::ERROR_PASSWORD_INVALID;
		else
        {
           // $this->_id=$record->id;
            $this->setState('name', $this->username);
            $this->errorCode=self::ERROR_NONE;
        }
		
		return !$this->errorCode;
	}
    
    public function login($identity)
    {
   
        if($identity->errorCode==0)        
        {
               $user = Users::model()->find("username = :name and password = :password",array(':name'=>$identity->username,':password'=>$identity->password));
           
              $session=new CHttpSession;
              $session->open();
              $session['user_id'] = $user->id;
              $session->close();
              $duration=3600; // 1 days
              Yii::app()->user->login($identity,$duration);
              return true;
        }
        else return false;
        
       
    }
    

    
    
}