<?php

namespace common\models;

use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "states".
 *
 * @property int $id
 * @property string $code
 * @property string $name
 * @property string $local_name
 * @property int $status
 * @property int $created_at
 * @property int $updated_at
 * @property int $created_by
 * @property int $updated_by
 */
class States extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'states';
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
            [['name'], 'required'],
            [['status'], 'integer'],
            [['code', 'name', 'local_name'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'code' => 'Code',
            'name' => 'Name',
            'local_name' => 'Local Name',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'created_by' => 'Created By',
            'updated_by' => 'Updated By',
        ];
    }
    
    public static function getStateByCountryId($country_id)
    {
		 $data = States::find()
		->select(['name', 'id'])
		->where(['country_id' => $country_id])
		->orderBy(['id' => SORT_ASC])
		->indexBy('id')
		->asArray()
		->column();
		return $data;
    }
    public static function getStates() {
					
	 $states = (new \yii\db\Query())
				->select(['states.*'])
				->from('states')
				
				->where(['country_id' => 113]);
	$states = $states->all();
	return $states;	
	}
	
	public static function getStateDetail($state_id) {
					
	 $states = (new \yii\db\Query())
				->select(['states.*'])
				->from('states')
				
				->where(['id' => $state_id]);
	$states = $states->one();
	return $states;	
	}
}
