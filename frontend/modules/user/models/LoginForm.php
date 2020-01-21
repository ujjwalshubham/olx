<?php

namespace frontend\modules\user\models;

use cheatsheet\Time;
use common\models\User;
use Yii;
use yii\base\Model;

/**
 * Login form
 */
class LoginForm extends Model
{
    public $identity;
    public $password;
     public $otp;
    public $rememberMe = true;

    private $user = false;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            // username and password are both required
            [['identity'], 'required'],
            // rememberMe must be a boolean value
            //['rememberMe', 'boolean'],
            // password is validated by validatePassword()
            //['password', 'validatePassword'],
             ['identity', 'validateUser'],
             ['otp','required','on'=>'otp'],
             ['otp', 'validateOtp'],
			 ['otp', 'integer'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'identity' => Yii::t('frontend', 'Mobile'),
            'password' => Yii::t('frontend', 'Password'),
            'rememberMe' => Yii::t('frontend', 'Remember Me'),
        ];
    }


    /**
     * Validates the password.
     * This method serves as the inline validation for password.
     */
     
     
    public function validateOtp()
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();
            if (!$user || !$user->validateUser($this->identity,$this->otp)) {
                    $this->addError('otp', Yii::t('frontend', 'Please Enter Valid OTP'));
            }
        }
    }

    public function validateUser()
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();
            if (!$user || !$user->validateUser($this->identity)) {
                $this->addError('identity', Yii::t('frontend', 'Mobile number not register with '));
            }            
        }
    }     
     
     
     
    //~ public function validatePassword()
    //~ {
        //~ if (!$this->hasErrors()) {
            //~ $user = $this->getUser();
            //~ if (!$user || !$user->validatePassword($this->password)) {
                //~ $this->addError('password', Yii::t('frontend', 'Incorrect Mobile or password.'));
            //~ }
        //~ }
    //~ }

    /**
     * Finds user by [[username]]
     *
     * @return User|null
     */
    public function getUser()
    {
        if ($this->user === false) {			
            $this->user = User::find()
                //->active()
                ->andWhere(['mobile' => $this->identity])
                ->one();
        }
        return $this->user;
    }

    /**
     * Logs in a user using the provided username and password.
     *
     * @return boolean whether the user is logged in successfully
     */
    public function login()
    {
        if ($this->validate()) {
			
            if (Yii::$app->user->login($this->getUser(), $this->rememberMe ? Time::SECONDS_IN_A_MONTH : 0)) {
                return true;
            }
        }  
        return false;
    }
}
