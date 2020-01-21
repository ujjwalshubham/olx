<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\Query;
use yii\helpers\Url;

/**
 * This is the model class for table "cities".
 *
 * @property int $id
 * @property string $name
 * @property string $local_name
 * @property string $latitude
 * @property string $longitude
 * @property string $population
 * @property string $timezone
 * @property int $status
 * @property int $created_at
 * @property int $updated_at
 * @property int $created_by
 * @property int $updated_by
 */
class Cities extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'cities';
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
            [['name', 'local_name'], 'required'],
            [['status'], 'integer'],
            [['name', 'local_name'], 'string', 'max' => 255],
            [['latitude', 'longitude', 'population'], 'string', 'max' => 32],
            [['timezone'], 'string', 'max' => 45],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'local_name' => 'Local Name',
            'latitude' => 'Latitude',
            'longitude' => 'Longitude',
            'population' => 'Population',
            'timezone' => 'Timezone',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'created_by' => 'Created By',
            'updated_by' => 'Updated By',
        ];
    }
    
    public static function getAllCities()
    {
		 $data = Cities::find()
		->select(['name', 'id'])
		//->where(['status' => 1])
		->orderBy(['id' => SORT_ASC])
		->indexBy('id')
		->asArray()
		->column();
		return $data;
    }
    
     public static function getCityById($cityId)
    {
		 $city_name = (new \yii\db\Query())
					->select(['name'])
					->from('cities')
					->where(['id' => $cityId])
					->one();
		 if(!empty($city_name)){
             return  $city_name;
         }
		 else{
		     return array();
         }

    }
    
    public static function getCityByStateId($state_id)
    {
		 $data = Cities::find()
		->select(['name', 'id'])
		->where(['state_id' => $state_id])
		->orderBy(['id' => SORT_ASC])
		->indexBy('id')
		->asArray()
		->column();
		return $data;
    }
    
}
