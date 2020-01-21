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
class Review extends \yii\db\ActiveRecord
{
	
	const STATUS_NOT_ACTIVE = 'Inactive';
    const STATUS_ACTIVE = 'Active';
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'reviews';
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
            
            [['ad_id', 'user_id'], 'integer'],
            [['ad_id', 'user_id', 'rating','comment'], 'required'],
            [['comment'], 'string'],
           
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
    
    public function checkReviewCount($AdId, $userId){
		$reviewCount = (new \yii\db\Query())
					->from('reviews')
					->where(['ad_id' => $AdId])
					->andwhere(['user_id' => $userId])
					->andwhere(['status' => 'Active'])
					->count();
		return $reviewCount;
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
	
	  public function checkAdReviewCount($AdId){
		$reviewCount = (new \yii\db\Query())
					->from('reviews')
					->where(['ad_id' => $AdId])
					->andwhere(['status' => 'Active'])
					->count();
		return $reviewCount;
	}
	
	public function getAdAllReviews($AdId){
		$reviews = (new \yii\db\Query())
					->select(['reviews.*','user_profile.name as name','user_profile.avatar_path as avatar'])
					->from('reviews')
					->leftjoin('user_profile', 'reviews.user_id = user_profile.user_id')
					->where(['reviews.ad_id' => $AdId])
					->andwhere(['reviews.status' => 'Active'])
					->limit(6)
					->orderBy(['id' => SORT_DESC])
					->all();
		return $reviews;
	}

    /**
     * Returns booking statuses list
     * @return array|mixed
     */
    public static function statuses()
    {
        return [
            self::STATUS_ACTIVE => Yii::t('db', 'Active'),
            self::STATUS_NOT_ACTIVE => Yii::t('db', 'InActive')
        ];
    }



}
