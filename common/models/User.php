<?php

namespace common\models;

use common\commands\AddToTimelineCommand;
use common\components\AppHelper;
use common\components\DrsPanel;
use common\models\query\UserQuery;
use common\commands\SendEmailCommand;
use Yii;
use yii\behaviors\AttributeBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;
use yii\web\IdentityInterface;
use yii\db\Query;

/**
 * User model
 *
 * @property integer $id
 * @property string $username
 * @property string $password_hash
 * @property string $email
 * @property string $auth_key
 * @property string $access_token
 * @property string $oauth_client
 * @property string $oauth_client_user_id
 * @property string $publicIdentity
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $logged_at
 * @property string $password write-only password
 *
 * @property \common\models\UserProfile $userProfile
 */
class User extends ActiveRecord implements IdentityInterface
{
    const STATUS_NOT_ACTIVE = 1;
    const STATUS_ACTIVE = 2;
    const STATUS_DELETED = 3;

    const ROLE_USER = 'user';
    const ROLE_MANAGER = 'manager';
    const ROLE_ADMINISTRATOR = 'administrator';

    const EVENT_AFTER_SIGNUP = 'afterSignup';
    const EVENT_AFTER_LOGIN = 'afterLogin';
    const SCENARIO_UPROFILE='updateprofile';

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%user}}';
    }

    /**
     * @inheritdoc
     */
    public static function findIdentity($id)
    {
        return static::find()
            ->active()
            ->andWhere(['id' => $id])
            ->one();
    }

    /**
     * @return UserQuery
     */
    public static function find()
    {
        return new UserQuery(get_called_class());
    }

    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        return static::find()
            ->active()
            ->andWhere(['access_token' => $token])
            ->one();
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return User|array|null
     */
    public static function findByUsername($username)
    {
        return static::find()
            ->active()
            ->andWhere(['username' => $username])
            ->one();
    }
    
    
     public static function findByUserId($userId)
    {
        return static::find()
            ->active()
            ->andWhere(['id' => $userId])
            ->one();
    }

    /**
     * Finds user by username or email
     *
     * @param string $login
     * @return User|array|null
     */
    public static function findByLogin($login)
    {
        return static::find()
            ->active()
            ->andWhere(['or', ['username' => $login], ['email' => $login]])
            ->one();
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::class,
            'auth_key' => [
                'class' => AttributeBehavior::class,
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => 'auth_key'
                ],
                'value' => Yii::$app->getSecurity()->generateRandomString()
            ],
            'access_token' => [
                'class' => AttributeBehavior::class,
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => 'access_token'
                ],
                'value' => function () {
                    return Yii::$app->getSecurity()->generateRandomString(40);
                }
            ]
        ];
    }

    /**
     * @return array
     */
    public function scenarios()
    {
        return ArrayHelper::merge(
            parent::scenarios(),
            [
                'oauth_create' => [
                    'oauth_client', 'oauth_client_user_id', 'email', 'username', '!status'
                ]
            ]
        );
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['username', 'email'], 'unique'],
            [['email_otp','email_verify'], 'safe'],
            ['email', 'email'],
            ['status', 'default', 'value' => self::STATUS_NOT_ACTIVE],
            ['status', 'in', 'range' => array_keys(self::statuses())],
            [['username'], 'filter', 'filter' => '\yii\helpers\Html::encode']
        ];
    }

    /**
     * Returns user statuses list
     * @return array|mixed
     */
    public static function statuses()
    {
        return [
            self::STATUS_NOT_ACTIVE => Yii::t('common', 'Not Active'),
            self::STATUS_ACTIVE => Yii::t('common', 'Active')
           // self::STATUS_DELETED => Yii::t('common', 'Deleted')
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'username' => Yii::t('common', 'Username'),
            'email' => Yii::t('common', 'E-mail'),
            'status' => Yii::t('common', 'Status'),
            'access_token' => Yii::t('common', 'API access token'),
            'created_at' => Yii::t('common', 'Created at'),
            'updated_at' => Yii::t('common', 'Updated at'),
            'logged_at' => Yii::t('common', 'Last login'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserProfile()
    {
        return $this->hasOne(UserProfile::class, ['user_id' => 'id']);
    }

    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * @inheritdoc
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return boolean if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return Yii::$app->getSecurity()->validatePassword($password, $this->password_hash);
    }

    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password_hash = Yii::$app->getSecurity()->generatePasswordHash($password);
    }

    /**
     * Creates user profile and application event
     * @param array $profileData
     */
    public function afterSignup(array $profileData = [])
    {
        $this->refresh();
        Yii::$app->commandBus->handle(new AddToTimelineCommand([
            'category' => 'user',
            'event' => 'signup',
            'data' => [
                'public_identity' => $this->getPublicIdentity(),
                'user_id' => $this->getId(),
                'created_at' => $this->created_at
            ]
        ]));
        $profile = new UserProfile();
        $profile->locale = Yii::$app->language;
        
      
        $profile->load($profileData, '');
        $this->link('userProfile', $profile);
        $this->trigger(self::EVENT_AFTER_SIGNUP);
        // Default role
        //$auth = Yii::$app->authManager;
        //$auth->assign($auth->getRole(User::ROLE_USER), $this->getId());
    }

    /**
     * @return string
     */
    public function getPublicIdentity()
    {
        if ($this->userProfile && $this->userProfile->getFullname()) {
            return $this->userProfile->getFullname();
        }
        if ($this->username) {
            return $this->username;
        }
        return $this->email;
    }

    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }
    
    public static function getEmailExist($email = null, $mobile) {
        return self::find()->where(['email' => $email])->orwhere(['mobile' => $mobile])->count();
    }
    
    public static function getSocailEmailExist($email) {
        return self::find()->where(['email' => $email])->count();
    }
    
    public function getUserDetails($userid) {
        $query = new Query();
        $query->select(['*'])->from('user')
                ->join('LEFT JOIN', 'user_profile', 'user_profile.user_id = user.id')
                ->andWhere(['user_profile.user_id' => '' . $userid . '']);

        $command = $query->createCommand();
        return $command->queryOne();
    }

    public static function checkUser($userId) {
        return self::find()->where(['id' => $userId])->count();
    }
    
     public static function getIdByEmail($email) {
		   return static::find()
            ->active()
            ->andWhere(['email' => $email])
            ->one();
        //return self::find()->where(['email' => $email]);
    }
    
     public function validateUser($identity,$otp=NULL){
 
			if(!empty($otp)){
				 $search['otp']=$otp;
				   return static::find()
					//->active()
					->andWhere(['mobile'=> $identity])
					->andWhere($search)
					->one();
			}
			else{
				  return static::find()
            //->active()
            ->andWhere(['mobile'=> $identity])
            //->andWhere($search)
            ->one();
			}
    }
    
     public function ajaxUnique($post,$id=NULL){
        $phone=User::find()
            ->andWhere(['mobile'=> $post['mobile']])
            ->one();
            $result['mobile']=($phone)?true:false;
        return $result;
    }
    
    public function setEmailOtp($post,$id=NULL){
		$userId = Yii::$app->User->id;
		$user = User::findOne($userId);
		$user->email_otp = 1234;
		$user->email = $post['email'];
		$user->save();  // equivalent to $model->update();
		$result['email']= $post['email'];
		$result['email_otp']= $user->email_otp;
		return $result;
    }
    
    public function checkEmailOtp($post,$id=NULL){
		$userId = Yii::$app->User->id;
		$user = User::findOne($userId);
		$otp = $post['otp'];

		if($user['email_otp']==$otp){
		$user->email_verify =1;
		$user->save();  // equivalent to $model->update();
		$result['message'] = true;
		return $result;	
		}
		else{
		$result['message'] = false;
		return $result;	
		}
    }
    
    
     public function ajaxEmailUnique($post,$id=NULL){
        $email=User::find()
            ->andWhere(['email'=> $post['email']])
            ->andWhere(['email_verify'=> 1])
            ->one();
       $result['email']=($email)?true:false;
        return $result;
    }
    
    public function getUserByMobile($mobile){
		$user=User::find()
            ->andWhere(['mobile'=> $mobile])
            ->one();
        return $user;
	}
	
	public function updateOtpLogin($otp,$id){
		$user = User::findOne($id);
		$user->otp =$otp;
		$user->save();  // equivalent to $model->update();
		return $user;
	}
	
	
	public function sendReplyEmail($post,$user,$userProfile)
    {
		
        if ($user) {
            Yii::$app->commandBus->handle(new SendEmailCommand([
                'subject' => Yii::t('frontend', 'Email Form user'),
                'view' => 'replyemail',
                'to' => $user->email,
                'params' => [
                    'name' => $post['name'],
                    'email'=> $post['email'],
                    'phone'=> $post['phone'],
                    'message'=> $post['message'],
                    'ad_id'=> $post['ad_id'],
                    'title'=> $post['title'],
                    'cat1'=> $post['cat1'],
                    'cat2'=> $post['cat2'],
                    'user'=>$user,
                    'userProfile'=>$userProfile,
                ]
            ]));
            return true;
        }
        return false;
    }
}
