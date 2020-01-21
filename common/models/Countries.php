<?php

namespace common\models;

use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "countries".
 *
 * @property int $id
 * @property string $iso2
 * @property string $iso3
 * @property string $name
 * @property string $local_name
 * @property int $currency_id
 * @property int $dialcode
 * @property string $languages
 * @property int $status
 * @property int $created_at
 * @property int $updated_at
 * @property int $created_by
 * @property int $updated_by
 */
class Countries extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'countries';
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
            [['iso3', 'name'], 'required'],
            [['currency_id', 'dialcode', 'status'], 'integer'],
            [['languages'], 'string'],
            [['iso2', 'iso3'], 'string', 'max' => 11],
            [['name', 'local_name'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'iso2' => 'Iso2',
            'iso3' => 'Iso3',
            'name' => 'Name',
            'local_name' => 'Local Name',
            'currency_id' => 'Currency ID',
            'dialcode' => 'Dialcode',
            'languages' => 'Languages',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'created_by' => 'Created By',
            'updated_by' => 'Updated By',
        ];
    }
    
    public static function getAllCountries()
    {
		 $data = Countries::find()
		->select(['name', 'id'])
		->where(['status' => 1])
		->orderBy(['id' => SORT_ASC])
		->indexBy('id')
		->asArray()
		->column();
		return $data;
    }
    
    public static function getCountryById($countryId)
    {
		 $country_name = (new \yii\db\Query())
					->select(['name'])
					->from('countries')
					->where(['id' => $countryId])
					->one();
		return  $country_name;
    }
}
