<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\Query;
/**
 * This is the model class for table "languages".
 *
 * @property int $id
 * @property string $title
 * @property string $code
 * @property string $direction
 * @property int $status
 * @property int $created_at
 * @property int $updated_at
 */
class Languages extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'languages';
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
            [['title'], 'required'],
            [['direction'], 'string'],
            [['status'], 'integer'],
            [['title', 'code'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Title',
            'code' => 'Code',
            'direction' => 'Direction',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
    
    public static function getAllLanguages()
    {
       $query = new Query();
        $query->select(['*'])->from('languages')
					->andWhere(['status' => 1]);

        $command = $query->createCommand();
        return $command->queryAll();
    }
    
}
