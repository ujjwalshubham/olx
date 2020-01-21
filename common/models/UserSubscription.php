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
class UserSubscription extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user_subscription';
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
            [['user_id', 'package_id','plan_id','from_date','to_date','created_at','updated_at','created_by','updated_by','status'], 'safe'],
        ];
    }

    /*
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'plan_id' => 'Plan ID',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
    
    public function getUserSubscription($user_id) {	
		$subscription = (new \yii\db\Query())
					->select(['user_subscription.*','plans.name as plan_name'])
					->from('user_subscription')
					->where(['user_subscription.user_id' => $user_id])
					->leftjoin('plans', 'user_subscription.plan_id = plans.id')
					->one();

         return $subscription;
     }
    
}
