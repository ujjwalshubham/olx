<?php

namespace common\models;

use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "advertising".
 *
 * @property int $id
 * @property string $position
 * @property string $slug
 * @property string $provider_name
 * @property string $code_large_format
 * @property string $code_tablet_format
 * @property string $code_phone_format
 * @property int $status
 * @property int $created_at
 * @property int $updated_at
 * @property int $created_by
 * @property int $updated_by
 */
class Advertising extends \yii\db\ActiveRecord
{
    const STATUS_ACTIVE = 1;
    const STATUS_NOT_ACTIVE = 0;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'advertising';
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::class,
            BlameableBehavior::class
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['position', 'slug', 'provider_name'], 'required'],
            [['code_large_format', 'code_tablet_format'], 'string'],
            [['status'], 'integer'],
            [['position', 'slug'], 'string', 'max' => 45],
            [['provider_name', 'code_phone_format'], 'string', 'max' => 100],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'position' => 'Position',
            'slug' => 'Slug',
            'provider_name' => 'Provider Name',
            'code_large_format' => 'Tracking Code (Large Format):',
            'code_tablet_format' => 'Tracking Code (Tablet Format):',
            'code_phone_format' => 'Tracking Code (Phone Format):',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'created_by' => 'Created By',
            'updated_by' => 'Updated By',
        ];
    }

    /**
     * Returns user statuses list
     * @return array|mixed
     */
    public static function statuses()
    {
        return [
            self::STATUS_ACTIVE => Yii::t('common', 'Active'),
            self::STATUS_NOT_ACTIVE => Yii::t('common', 'Not Active'),
        ];
    }
}
