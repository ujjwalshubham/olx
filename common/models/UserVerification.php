<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "user_verification".
 *
 * @property int $id
 * @property int $user_id
 * @property string $email
 * @property int $otp
 * @property int $email_verified
 * @property int $created_at
 * @property int $updated_at
 */
class UserVerification extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user_verification';
    }
    
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::class
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'otp'], 'required'],
            [['user_id', 'otp', 'email_verified'], 'integer'],
            [['email'], 'string', 'max' => 255],
            ['email', 'email'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'email' => 'Email',
            'otp' => 'Otp',
            'email_verified' => 'Email Verified',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
}
