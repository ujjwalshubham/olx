<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "plans".
 *
 * @property int $id
 * @property string $name
 * @property string $alias
 * @property int $package_id
 * @property string $plan_term
 * @property string $pay_mode
 * @property double $amount
 * @property string $image
 * @property int $recommended
 * @property int $active
 * @property int $created_at
 * @property int $updated_at
 */
class Transactions extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'transaction';
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::class,
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'ad_id', 'user_id', 'plan_id', 'package_id', 'user_id'], 'integer'],
            [['payment_type', 'txn_amount', 'type'], 'string'],
       
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'ad_id' => 'Ad ID',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
    
    
}
