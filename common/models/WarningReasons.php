<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "warning_reasons".
 *
 * @property int $id
 * @property string $type
 * @property string $label
 * @property string $options
 * @property int $status
 * @property int $created_at
 * @property int $updated_at
 */
class WarningReasons extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'warning_reasons';
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
            [['label'], 'required'],
            [['label', 'options'], 'string'],
            [['status'], 'integer'],
            [['type'], 'string', 'max' => 256],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'type' => 'Type',
            'label' => 'Label',
            'options' => 'Options',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    public static function getReson() {
        return self::find()->where(['status' => 1])->groupBy(['type'])->all();
    }
}
