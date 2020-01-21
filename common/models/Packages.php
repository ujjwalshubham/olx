<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "packages".
 *
 * @property int $id
 * @property string $name
 * @property int $ad_limit
 * @property int $ad_duration
 * @property double $featured_project_fee
 * @property int $featured_duration
 * @property double $urgent_project_fee
 * @property int $urgent_duration
 * @property double $highlight_project_fee
 * @property int $highlight_duration
 * @property int $group_removable
 * @property int $top_search_result
 * @property int $show_on_home
 * @property int $show_in_home_search
 * @property int $created_at
 * @property int $updated_at
 */
class Packages extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'packages';
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
            [['name','ad_limit', 'ad_duration', 'featured_duration', 'urgent_duration', 'highlight_duration',
                'featured_project_fee', 'urgent_project_fee', 'highlight_project_fee'], 'required'],
            [['ad_limit', 'ad_duration', 'featured_duration', 'urgent_duration', 'highlight_duration',
                'group_removable', 'top_search_result', 'show_on_home', 'show_in_home_search'], 'integer'],
            [['featured_project_fee', 'urgent_project_fee', 'highlight_project_fee'], 'number'],
            [['name'], 'string', 'max' => 100],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Package Name',
            'ad_limit' => 'Ad Posting Limit(Max No)',
            'ad_duration' => 'Ad Duration',
            'featured_project_fee' => 'Featured Badge Fee',
            'featured_duration' => 'Featured Duration',
            'urgent_project_fee' => 'Urgent Badge Fee',
            'urgent_duration' => 'Urgent Duration',
            'highlight_project_fee' => 'Highlight Badge Fee',
            'highlight_duration' => 'Highlight Duration',
            'group_removable' => 'Removable',
            'top_search_result' => 'Top in search results and category.',
            'show_on_home' => 'Show ad on home page premium ad section.',
            'show_in_home_search' => 'Show ad on home page search result list.',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
    
     public function AllPackages($user_id = null) {	
		 
		$title = Yii::t('frontend', 'Membership Plans');
		if(!empty($user_id)){
			$userId = $user_id;
		}else{
			$userId = Yii::$app->user->identity['id'];
		}
		
		$allPackages = (new \yii\db\Query())
				->select(['*'])
				->from('packages')
				->orderBy(['id' => SORT_DESC])
				->all();
		//echo "<pre>"; print_r($allAds);exit;	
         return $allPackages;
     }
     
      public function getPackageDetail($plan_id) {	
		  
		$allPackages = (new \yii\db\Query())
				->select(['*'])
				->from('packages')
				->orderBy(['id' => SORT_DESC])
				->all();
		//echo "<pre>"; print_r($allAds);exit;	
         return $allPackages;
     }
     
     public function getPackageDescription($id) {	
		$packageDetail = (new \yii\db\Query())
				->select(['*'])
				->from('packages')
				->where(['id' => $id])
				->one();
		//echo "<pre>"; print_r($allAds);exit;	
         return $packageDetail;
     }    
}
