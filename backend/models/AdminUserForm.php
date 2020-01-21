<?php

namespace backend\models;

use common\models\User;
use common\models\UserProfile;
use Yii;
use yii\base\Exception;
use yii\base\Model;
use yii\helpers\ArrayHelper;

/**
 * Create user form
 */
class AdminUserForm extends Model
{
    public $name;
    public $mobile;
    public $email;
    public $status;
    public $roles;
    public $username;
    public $password;
    public $user_type;

    private $model;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['mobile', 'filter', 'filter' => 'trim'],
            ['mobile', 'required'],
            ['mobile', 'unique', 'targetClass' => User::class, 'filter' => function ($query) {
                if (!$this->getModel()->isNewRecord) {
                    $query->andWhere(['not', ['id' => $this->getModel()->id]]);
                }
            }],
            ['mobile','is10NumbersOnly'],
            ['name','required'],
            [['name'], 'string', 'min' => 2, 'max' => 255],

            ['username', 'filter', 'filter' => 'trim'],
            ['username', 'required'],
            ['username', 'unique', 'targetClass' => User::class, 'filter' => function ($query) {
                if (!$this->getModel()->isNewRecord) {
                    $query->andWhere(['not', ['id' => $this->getModel()->id]]);
                }
            }],

            ['email', 'filter', 'filter' => 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'unique', 'targetClass' => User::class, 'filter' => function ($query) {
                if (!$this->getModel()->isNewRecord) {
                    $query->andWhere(['not', ['id' => $this->getModel()->id]]);
                }
            }],
            [['status'], 'integer'],
            [['roles'], 'each',
                'rule' => ['in', 'range' => ArrayHelper::getColumn(
                    Yii::$app->authManager->getRoles(),
                    'name'
                )]
            ],

            ['password', 'required', 'on' => 'create'],
            ['password', 'string', 'min' => 6],
            ['user_type','safe']
        ];
    }

    /**
     * @return User
     */
    public function getModel()
    {
        if (!$this->model) {
            $this->model = new User();
        }
        return $this->model;
    }

    /**
     * @param User $model
     * @return mixed
     */
    public function setModel($model)
    {
        $this->mobile = $model->mobile;
        $this->username = $model->username;
        $this->email = $model->email;
        $this->status = $model->status;
        $this->model = $model;
        $this->roles = ArrayHelper::getColumn(
            Yii::$app->authManager->getRolesByUser($model->getId()),
            'name'
        );
        return $this->model;
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'mobile' => Yii::t('common', 'Mobile'),
            'email' => Yii::t('common', 'Email'),
            'status' => Yii::t('common', 'Status'),
            'roles' => Yii::t('common', 'Roles')
        ];
    }

    /**
     * Signs user up.
     * @return User|null the saved model or null if saving fails
     * @throws Exception
     */
    public function save()
    {
        if ($this->validate()) {
            $model = $this->getModel();
            $isNewRecord = $model->getIsNewRecord();
            $model->mobile = $this->mobile;
            $model->username = $this->username;
            $model->email = $this->email;
            $model->status = $this->status;

            if(isset($this->user_type)){
                $model->user_type = $this->user_type;
            }


            if ($this->password) {
                $model->setPassword($this->password);
            }

            if (!$model->save()) {
                throw new Exception('Model not saved');
            }

            $profileData=array();
            $profileData['name'] = $this->name;
            if(isset($this->gender)){
                $profileData['gender'] = $this->gender;
            }
            else{
                $profileData['gender'] = UserProfile::GENDER_MALE;
            }
            if ($isNewRecord) {
                $model->afterSignup($profileData);
            }
            else{
                $profileData=UserProfile::findOne(['user_id'=>$model->getId()]);
                $profileData->name=$this->name;
                $profileData->save();
            }
            $auth = Yii::$app->authManager;
            $auth->revokeAll($model->getId());

            if ($this->roles && is_array($this->roles)) {
                foreach ($this->roles as $role) {
                    $auth->assign($auth->getRole($role), $model->getId());
                }
            }

            return !$model->hasErrors();
        }
        return null;
    }

    public function is10NumbersOnly($attribute)
    {
        if (!preg_match('/^[0-9]{10}$/', $this->$attribute)) {
            $this->addError($attribute, 'mobile number exactly 10 digits.');
        }
    }
}
