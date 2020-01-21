<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\SluggableBehavior;
use yii\db\Query;
use yii\helpers\Url;

/**
 * This is the model class for table "categories".
 *
 * @property int $id
 * @property int $parent_id
 * @property string $title
 * @property string $slug
 * @property string $description
 * @property int $status
 * @property int $created_at
 * @property int $updated_at
 * @property int $created_by
 * @property int $updated_by
 */
class UserVisits extends \yii\db\ActiveRecord
{
	
	const STATUS_NOT_ACTIVE = 0;
    const STATUS_ACTIVE = 1;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user_visits';
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
            
            [['to_id','from_id'], 'integer'],
            [['ip_address'], 'string'],
            [['created_at', 'updated_at'],'safe']
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
           
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'created_by' => 'Created By',
            'updated_by' => 'Updated By',
        ];
    }
    
    public function visitsCountOfUser($user_id){
		$visitsCount = (new \yii\db\Query())
					->from('user_visits')
					->where(['to_id' => $user_id])
					//->groupBy(['ip_address'])
					->count();
		return $visitsCount;
	}
	
	public function ReviewCountByAdId($AdId){
		$reviewAvg = (new \yii\db\Query())
					->select('avg(reviews.rating) as rating')
					->from('reviews')
					->where(['ad_id' => $AdId])
					->andwhere(['status' => 'Active'])             
					->one();
		return $reviewAvg;
	}
	
	 
}
