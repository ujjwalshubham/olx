<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "plans".
 *
 * @property int $id
 * @property string $name
 * @property string $alias
 * @property int $package_id
 * @property string $plan_term
 * @property string $pay_mode
 * @property double $amount
 * @property string $image
 * @property int $recommended
 * @property int $active
 * @property int $created_at
 * @property int $updated_at
 */
class Plans extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'plans';
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
            [['name', 'amount','package_id'], 'required'],
            [['package_id', 'recommended', 'active'], 'integer'],
            [['plan_term', 'pay_mode'], 'string'],
            [['amount'], 'number'],
            [['name'], 'string', 'max' => 100],
            [['alias'], 'string', 'max' => 20],
            [['image'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Plan Name',
            'alias' => 'Alias',
            'package_id' => 'Choose Package',
            'plan_term' => 'Plan Term',
            'pay_mode' => 'Payment Mode',
            'amount' => 'Plan Amount',
            'image' => 'Image',
            'recommended' => 'Recommended',
            'active' => 'Active',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
    
    
      public function AllPlans($user_id = null) {	
		 
		$title = Yii::t('frontend', 'Membership Plans');
		if(!empty($user_id)){
			$userId = $user_id;
		}else{
			$userId = Yii::$app->user->identity['id'];
		}
		
		$allPlans = (new \yii\db\Query())
					->select(['plans.*','packages.id as pack_id','packages.name as pack_name','packages.ad_limit as ad_limit','packages.ad_duration as ad_duration','packages.featured_project_fee as featured_project_fee','packages.featured_duration as featured_duration','packages.urgent_project_fee as urgent_project_fee','packages.urgent_duration as urgent_duration','packages.highlight_project_fee as 	highlight_project_fee','packages.highlight_duration as 	highlight_duration','packages.group_removable as group_removable','packages.top_search_result as top_search_result','packages.show_on_home as show_on_home','packages.show_in_home_search as show_in_home_search'])
					->from('plans')
					->leftjoin('packages', 'plans.package_id = packages.id')
					->all();

         return $allPlans;
     }
     
     public function getPlanDetail($plan_id) {	
		$plan_detail = (new \yii\db\Query())
					->select(['plans.*','packages.id as pack_id','packages.name as pack_name','packages.ad_limit as ad_limit','packages.ad_duration as ad_duration','packages.featured_project_fee as featured_project_fee','packages.featured_duration as featured_duration','packages.urgent_project_fee as urgent_project_fee','packages.urgent_duration as urgent_duration','packages.highlight_project_fee as 	highlight_project_fee','packages.highlight_duration as 	highlight_duration','packages.group_removable as group_removable','packages.top_search_result as top_search_result','packages.show_on_home as show_on_home','packages.show_in_home_search as show_in_home_search'])
					->from('plans')
					->where(['plans.id' => $plan_id])
					->leftjoin('packages', 'plans.package_id = packages.id')
					->one();

         return $plan_detail;
     }
}
