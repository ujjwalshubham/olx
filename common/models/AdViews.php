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
class AdViews extends \yii\db\ActiveRecord
{
	
	const STATUS_NOT_ACTIVE = 0;
    const STATUS_ACTIVE = 1;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'ad_views';
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
            
            [['ad_id'], 'integer'],
            [['ip_address'], 'string'],
            [['created_at', 'updated_at', 'created_by', 'updated_by'],'safe']
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
    
    public function AdViewsCountByAdId($AdId){
		$adviewCount = (new \yii\db\Query())
					->from('ad_views')
					->where(['ad_id' => $AdId])
					->andwhere(['status' => 'Active'])
					->groupBy(['ip_address'])
					->count();
		return $adviewCount;
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
