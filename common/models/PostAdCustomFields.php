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
class PostAdCustomFields extends \yii\db\ActiveRecord
{
	
	const STATUS_NOT_ACTIVE = 0;
    const STATUS_ACTIVE = 1;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'post_ads_custom_fields';
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
            
            [['ad_id', 'field_id'], 'required'],
            [['created_at', 'updated_at', 'created_by', 'updated_by','value'],'safe']
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'created_by' => 'Created By',
            'updated_by' => 'Updated By',
        ];
    }
    
    public function adsCountByUser($userId){
		$adCount = (new \yii\db\Query())
					->from('post_ads')
					->where(['user_id' => $userId])
					->count();
		return $adCount;
	}
	
	public function PostAdCustomDelete($ad_id)
    {       
		 $condition = ['ad_id'=>$ad_id];
		$postCustomDelete = PostAdCustomFields::deleteAll($condition);
		  return $postCustomDelete;
	}
	
}
