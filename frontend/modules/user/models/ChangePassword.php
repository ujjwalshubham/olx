<?php
namespace frontend\modules\user\models;

use common\models\User;
use common\models\UserToken;
use yii\base\InvalidParamException;
use yii\base\Model;
use \yii\db\ActiveRecord;
use Yii;

/**
 * Password reset form
 */
class ChangePassword extends ActiveRecord
{
    /**
     * @var
     */
    public $oldpassword;
    public $password;
    public $cpassword;


      /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['password', 'oldpassword', 'cpassword'], 'string', 'min' => 8, 'on' => 'changePwd'],
            [['oldpassword','password','cpassword'], 'required', 'on' => 'changePwd'],
            ['oldpassword','checkpassword', 'on' => 'changePwd'],
            ['cpassword', 'compare', 'compareAttribute' => 'password', 'skipOnEmpty' => false, 'on' => 'changePwd']
        ];
    }

    /**
     * @return array
     */
    public function attributeLabels()
    {
        return [
            'oldpassword'=>Yii::t('frontend', 'Current Password'),
            'password'=>Yii::t('frontend', 'New Password'),
            'cpassword'=>Yii::t('frontend', 'Confirm Password')
        ];
    }

    //matching the old password with your existing password.
    public function checkpassword($attribute, $params)
    {
        $user = User::find()->where(['id' => Yii::$app->user->identity->id])->one();
        if (!(Yii::$app->getSecurity()->validatePassword($this->oldpassword, $user->password_hash))) {
           return $this->addError($attribute, 'Current password is incorrect.');
        }
    }
}
