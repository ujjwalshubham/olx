<?php

namespace frontend\modules\user\models;

use cheatsheet\Time;
use common\commands\SendEmailCommand;
use common\models\Groups;
use common\models\User;
use common\models\UserProfile;
use common\models\UserToken;
use frontend\modules\user\Module;
use Yii;
use yii\base\Exception;
use yii\base\Model;
use yii\helpers\Url;
use common\components\MailSend;
use common\components\MyHelper;

/**
 * Signup form
 */
class SignupForm extends Model
{
   
    public $username;
    public $mobile;
    public $email;
    public $password;
    public $cpassword;
    public $name;
    public $devicetoken;
    public $deviceid;
    public $device_type;
    public $social_type;
    public $social_token;
    public $status;
    const SCENARIO_SIGNUP = 'signup';
    const SCENARIO_API = 'signup_api';

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
			
            ['mobile', 'filter', 'filter' => 'trim'],
           // ['mobile', 'required'],
            ['mobile', 'required', 'skipOnEmpty' => false, 'on' => self::SCENARIO_SIGNUP],
          
            /*['mobile', 'number', 'integerOnly'=>true],
            [['mobile'], 'string','min'=>10, 'max' => 10,'tooShort'=>'Should be 10 digit long.(Example: 9012687986)' , 'tooLong' => 'Should be 10 digit long.(Example: 9012687986)' ],
            */
            
            /*['mobile', 'unique',
                'targetClass' => '\common\models\User',
                'message' => Yii::t('frontend', 'This Mobile No has already been taken.')
            ],*/

            ['mobile', 'filter', 'filter' => 'trim'],
            ['mobile','is10NumbersOnly'],
			[['devicetoken', 'deviceid', 'device_type', 'social_token', 'social_type'], 'safe', 'on' => self::SCENARIO_API],
           
        ];
    }

    /**
     * @return array
     */
    public function attributeLabels()
    {
        return [
            'mobile' => Yii::t('frontend', 'Mobile Number'),
            'name' => Yii::t('frontend', 'Full Name'),
            'email' => Yii::t('frontend', 'Email'),
            'password' => Yii::t('frontend', 'Password'),
            'cpassword' => Yii::t('frontend', 'Confirm Password'),
        ];
    }

    /**
     * Signs user up.
     *
     * @return User|null the saved model or null if saving fails
     * @throws Exception
     */
    public function signup()
    {
        if ($this->validate()) {
			
			$mobile = $this->mobile;
			$userexist = User::getUserByMobile($mobile);
			
			if(!empty($userexist)){
			$user = new User();	
			
			$otp = 1234;
			$updateOtp = User::updateOtpLogin($otp,$userexist->id);
			
				
			}
			else {
				
		    $shouldBeActivated = $this->shouldBeActivated();
            $user = new User();
            $user->mobile = trim($this->mobile);
            $user->email = $this->email;
           // $user->otp = mt_rand(100000, 999999);;
            $user->otp = 1234;
            $user->devicetoken = $this->devicetoken;
            $user->deviceid = $this->deviceid;
            $user->device_type = $this->device_type;
            $user->social_type = $this->social_type;
            $user->social_token = $this->social_token;
            $user->status=2;
            //$user->quickblox_password = $this->password;
            
            $user->setPassword($this->password);
            if (!$user->save()) {
				//echo '<pre>';print_r($user->getErrors());die;
                throw new Exception("User couldn't be  saved");
            };
            
            $id = Yii::$app->db->getLastInsertID();
           
            
			$profileData['phone'] = $this->mobile;
            $user->afterSignup($profileData);
            return $user;
           
				
				
			}
			
          
        }

        return null;
    }

    /**
     * @return bool
     */
    public function shouldBeActivated()
    {
        /** @var Module $userModule */
        $userModule = Yii::$app->getModule('user');
        
        //echo "<pre>"; print_r($userModule);exit;
        if (!$userModule) {
            return false;
        } elseif ($userModule->shouldBeActivated) {
            return true;
        } else {
            return false;
        }
    }

    public function is10NumbersOnly($attribute){
        if (!preg_match('/^[0-9]{10}$/', $this->$attribute)) {
            $this->addError($attribute, 'mobile number exactly 10 digits.');
        }
        elseif (!preg_match('/^[1-9][0-9]*$/', $this->$attribute)) {
            $this->addError($attribute, 'Enter Correct Mobile Number.');
        }
    }
}