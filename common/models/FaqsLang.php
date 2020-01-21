<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "faqs_lang".
 *
 * @property int $id
 * @property int $faq_id
 * @property string $locale
 * @property string $title
 * @property string $description
 * @property int $created_at
 * @property int $updated_at
 */
class FaqsLang extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'faqs_lang';
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
            [['faq_id', 'locale', 'title', 'description'], 'required'],
            [['faq_id'], 'integer'],
            [['title', 'description'], 'string'],
            [['locale'], 'string', 'max' => 32],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'faq_id' => 'Faq ID',
            'locale' => 'Locale',
            'title' => 'Title',
            'description' => 'Description',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
}
