<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\Query;

/**
 * This is the model class for table "settings".
 *
 * @property int $id
 * @property int $category
 * @property string $name
 * @property string $value
 * @property int $created_at
 * @property int $updated_at
 */
class Settings extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'settings';
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
            [['category'], 'required'],
            [['category'], 'integer'],
            [['value'], 'string'],
            [['name'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'category' => 'Category',
            'name' => 'Name',
            'value' => 'Value',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
    
    public function getPageSize() {
        $query = new Query();
        $query->select(['*'])->from('settings')
                ->where(['name' => 'page_size']);

        $command = $query->createCommand();
        return $command->queryOne()['value'];
    }
    
    public function getSettingByName($name) {
        $query = new Query();
        $query->select(['*'])->from('settings')
                ->where(['name' => $name]);

        $command = $query->createCommand();
        return $command->queryOne()['value'];
    }
    
    
}
