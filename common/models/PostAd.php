<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\SluggableBehavior;
use yii\db\Query;
use yii\helpers\Url;
use yii\data\Pagination;
use common\models\Settings;


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
class PostAd extends \yii\db\ActiveRecord
{

    const STATUS_PENDING='Pending';
    const STATUS_REJECTED='Rejected';
    const STATUS_INACTIVE='Inactive';
    const STATUS_WARNING='Warning';
    const STATUS_ACTIVE='Active';
    const STATUS_HIDDEN='Hidden';
    const STATUS_RESUBMITTED='Resubmitted';
    const STATUS_EXPIRE='Expire';
	public $custom_field;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'post_ads';
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::class,
            BlameableBehavior::class,
             'slug' => [
                'class' => SluggableBehavior::className(),
                'attribute' => 'title',
                'ensureUnique' => true,
                'immutable' => true,
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            
            [['title', 'slug','address','status','description','city','country','state'], 'required'],
            [['description'], 'string'],
            [['price'], 'match', 'pattern'=>'/^[0-9]{1,12}(\.[0-9]{0,4})?$/'],
            [['mobile'], 'integer'],
            ['mobile', 'filter', 'filter' => 'trim'],
            ['mobile', 'number', 'integerOnly'=>true],
            [['mobile'], 'string','min'=>10, 'max' => 10,'tooShort'=>'Should be 10 digit long' , 'tooLong' => 'Should be 10 digit long' ],
            [['title', 'slug'], 'string', 'max' => 200],
            [['created_at','user_id', 'updated_at', 'created_by', 'updated_by','city','country','state','tags','negotiate','mobile_hidden','mobile','address','status','latitude','longitude','custom_field'],'safe'],
            ['termCondition', 'required','requiredValue' => 1, 'message' => 'Please Accept T&C'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'city' => 'City',
            'country' => 'Country',
            'state' => 'State',
            'address' => 'Address',
            'title' => 'Title',
            'tags' => 'tags',
            'slug' => 'Slug',
            'description' => 'Description',
            'termCondition' => 'Terms And Conditions',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'created_by' => 'Created By',
            'updated_by' => 'Updated By',
        ];
    }

    public function getUserprofile()
    {
        return $this->hasOne(UserProfile::className(), ['user_id' => 'user_id']);
    }

    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    public function getAdscategory()
    {
        return $this->hasMany(PostAdCategory::className(), ['ad_id' => 'id']);
    }

    /*public function getCategory()
    {
        return $this->hasOne(Categories::className(), ['id' => 'cat_id'])->via('adscategory');
    }*/

    public function adsCountByUser($userId){
		$adCount = (new \yii\db\Query())
					->from('post_ads')
					->where(['user_id' => $userId])
					->count();
		return $adCount;
	}
	
	public function featureAdsCountByUser($userId){
		$adCount = (new \yii\db\Query())
					->from('post_ads')
					->where(['user_id' => $userId])
					->andWhere(['ad_type' => 'featured'])
					->andWhere(['status' => PostAd::STATUS_ACTIVE])
					->count();
		return $adCount;
	}
	
	
	public function premiumAdsCountByUser($userId){

		$adCount = (new \yii\db\Query())
					->from('post_ads')
					->where(['user_id' => $userId])
					->andWhere(['ad_type' => array('featured','highlight','urgent')])
					->andWhere(['status' => PostAd::STATUS_ACTIVE])
					->count();
		
		return $adCount;
	}
	
	public function pendingAdsCount($userId){
		$adCount = (new \yii\db\Query())
					->from('post_ads')
					->where(['user_id' => $userId])
					->andWhere(['status' => PostAd::STATUS_PENDING])
					->count();
		return $adCount;
	}
	
	public function activeAdsCount($userId){
		$adCount = (new \yii\db\Query())
					->from('post_ads')
					->where(['user_id' => $userId])
					->andWhere(['status' => PostAd::STATUS_ACTIVE])
					->count();
		return $adCount;
	}
	
	public function warningAdsCount($userId){
		$adCount = (new \yii\db\Query())
					->from('post_ads')
					->where(['user_id' => $userId])
					->andWhere(['status' => PostAd::STATUS_WARNING])
					->count();
		return $adCount;
	}
	
	public function rejectedAdsCount($userId){
		$adCount = (new \yii\db\Query())
					->from('post_ads')
					->where(['user_id' => $userId])
					->andWhere(['status' => PostAd::STATUS_REJECTED])
					->count();
		return $adCount;
	}
	
	public function hiddenAdsCount($userId){
		$adCount = (new \yii\db\Query())
					->from('post_ads')
					->where(['user_id' => $userId])
					->andWhere(['status' => PostAd::STATUS_HIDDEN])
					->count();
		return $adCount;
	}
	
	
	public function resubmitAdsCount($userId){
		$adCount = (new \yii\db\Query())
					->from('post_ads')
					->where(['user_id' => $userId])
					->andWhere(['status' => PostAd::STATUS_RESUBMITTED])
					->count();
		return $adCount;
	}
	
	
	public function favouriteAdsCount($userId){
		$adCount = (new \yii\db\Query())
					->from('wishlist')
					->where(['user_id' => $userId])
					->count();
		return $adCount;
	}
	
	public function checkAdsCount($AdId){
		$adCount = (new \yii\db\Query())
					->from('post_ads')
					->where(['id' => $AdId])
					->count();
		return $adCount;
	}
	
	
	 public function MyAds($user_id = null) {		
		$title = Yii::t('frontend', 'My Ads');
		if(!empty($user_id)){
			$userId = $user_id;
		}else{
			$userId = Yii::$app->user->identity['id'];
		}
		
		$allAds = (new \yii\db\Query())
			->select(['post_ads.*','cities.name as city_name','states.name as state_name','countries.name as country_name'])
			->from('post_ads')
			->leftjoin('cities', 'post_ads.city = cities.id')
			->leftjoin('states', 'post_ads.state = states.id')
			->leftjoin('countries', 'post_ads.country = countries.id')
			->where(['post_ads.user_id' => $userId])
			->orderBy(['post_ads.id' => SORT_DESC]);
				
		$countQuery = clone $allAds;
		$page_size = Settings::getPageSize();
		$pages = new Pagination(['totalCount' => $countQuery->count(), 'pageSize'=>$page_size]);
				
		$allAds = $allAds->offset($pages->offset)->limit($pages->limit)->all();		
				
					
				
		//echo "<pre>"; print_r($allAds);exit;	
			
		$i = 0;	
		$ArrAds = array();
		foreach($allAds as $key=>$value){
			
			$cat_id = (new \yii\db\Query())
					->select(['*'])
					->from('ads_category')
					->where(['ad_id' => $value['id']])
					->all();		
			$value['category_id'] =  $cat_id;
			$ArrAds[$i] = $value;
			
					$images = (new \yii\db\Query())
					->select(['ads_images.*', 'media_upload.file_name', 'media_upload.upload_base_path'])
					->from('ads_images')
					->leftjoin('media_upload', 'ads_images.media_id = media_upload.id')
					->where(['ads_images.ad_id' => $value['id']])
					->all();
			
			$value['ads_images'] =  $images;
			$ArrAds[$i] = $value;
			
			$i++;
		}	
         return array('ArrAds'=>$ArrAds,'pages'=>$pages);
        
     }
     
     
     public function ActiveAds($user_id = null) {		
		$title = Yii::t('frontend', 'Active Ads');
		if(!empty($user_id)){
			$userId = $user_id;
		}else{
			$userId = Yii::$app->user->identity['id'];
		}
			$allAds = (new \yii\db\Query())
				->select(['post_ads.*','cities.name as city_name','states.name as state_name','countries.name as country_name'])
				->from('post_ads')
				->leftjoin('cities', 'post_ads.city = cities.id')
				->leftjoin('states', 'post_ads.state = states.id')
				->leftjoin('countries', 'post_ads.country = countries.id')
				->where(['post_ads.user_id' => $userId])
				->andWhere(['post_ads.status' => PostAd::STATUS_ACTIVE])
				->orderBy(['post_ads.id' => SORT_DESC]);
			$countQuery = clone $allAds;
			$page_size = Settings::getPageSize();
			$pages = new Pagination(['totalCount' => $countQuery->count(), 'pageSize'=>$page_size]);
				
			$allAds = $allAds->offset($pages->offset)->limit($pages->limit)->all();		
				
		//echo "<pre>"; print_r($allAds);exit;	
			
		$i = 0;	
		$ArrAds = array();
		foreach($allAds as $key=>$value){
			
			$cat_id = (new \yii\db\Query())
					->select(['*'])
					->from('ads_category')
					->where(['ad_id' => $value['id']])
					->all();		
			$value['category_id'] =  $cat_id;
			$ArrAds[$i] = $value;
			
					$images = (new \yii\db\Query())
					->select(['ads_images.*', 'media_upload.file_name', 'media_upload.upload_base_path'])
					->from('ads_images')
					->leftjoin('media_upload', 'ads_images.media_id = media_upload.id')
					->where(['ads_images.ad_id' => $value['id']])
					->all();
			
			$value['ads_images'] =  $images;
			$ArrAds[$i] = $value;
			
			$i++;
		}	
         return array('ArrAds'=>$ArrAds,'pages'=>$pages);
     }
     
     
      public function WarningAds($user_id = null) {		
		$title = Yii::t('frontend', 'Warning Ads');
		if(!empty($user_id)){
			$userId = $user_id;
		}else{
			$userId = Yii::$app->user->identity['id'];
		}
			$allAds = (new \yii\db\Query())
				->select(['post_ads.*','cities.name as city_name','states.name as state_name','countries.name as country_name'])
				->from('post_ads')
				->leftjoin('cities', 'post_ads.city = cities.id')
				->leftjoin('states', 'post_ads.state = states.id')
				->leftjoin('countries', 'post_ads.country = countries.id')
				->where(['post_ads.user_id' => $userId])
				->andWhere(['post_ads.status' => PostAd::STATUS_WARNING])
				->orderBy(['post_ads.id' => SORT_DESC]);
			$countQuery = clone $allAds;
			$page_size = Settings::getPageSize();
			$pages = new Pagination(['totalCount' => $countQuery->count(), 'pageSize'=>$page_size]);
				
			$allAds = $allAds->offset($pages->offset)->limit($pages->limit)->all();	
				
		//echo "<pre>"; print_r($allAds);exit;	
			
		$i = 0;	
		$ArrAds = array();
		foreach($allAds as $key=>$value){
			
			$cat_id = (new \yii\db\Query())
					->select(['*'])
					->from('ads_category')
					->where(['ad_id' => $value['id']])
					->all();		
			$value['category_id'] =  $cat_id;
			$ArrAds[$i] = $value;
			
			$images = (new \yii\db\Query())
			->select(['ads_images.*', 'media_upload.file_name', 'media_upload.upload_base_path'])
			->from('ads_images')
			->leftjoin('media_upload', 'ads_images.media_id = media_upload.id')
			->where(['ads_images.ad_id' => $value['id']])
			->all();
			
			$value['ads_images'] =  $images;
			$ArrAds[$i] = $value;
			
			$i++;
		}	
         return array('ArrAds'=>$ArrAds,'pages'=>$pages);
     }
     
      public function RejectedAds($user_id = null) {		
		$title = Yii::t('frontend', 'Rejected Ads');
		if(!empty($user_id)){
			$userId = $user_id;
		}else{
			$userId = Yii::$app->user->identity['id'];
		}
			$allAds = (new \yii\db\Query())
				->select(['post_ads.*','cities.name as city_name','states.name as state_name','countries.name as country_name'])
				->from('post_ads')
				->leftjoin('cities', 'post_ads.city = cities.id')
				->leftjoin('states', 'post_ads.state = states.id')
				->leftjoin('countries', 'post_ads.country = countries.id')
				->where(['post_ads.user_id' => $userId])
				->andWhere(['post_ads.status' => PostAd::STATUS_REJECTED])
				->orderBy(['post_ads.id' => SORT_DESC]);
			$countQuery = clone $allAds;
			$page_size = Settings::getPageSize();
			$pages = new Pagination(['totalCount' => $countQuery->count(), 'pageSize'=>$page_size]);
				
			$allAds = $allAds->offset($pages->offset)->limit($pages->limit)->all();	
				
		//echo "<pre>"; print_r($allAds);exit;	
			
		$i = 0;	
		$ArrAds = array();
		foreach($allAds as $key=>$value){
			
			$cat_id = (new \yii\db\Query())
					->select(['*'])
					->from('ads_category')
					->where(['ad_id' => $value['id']])
					->all();		
			$value['category_id'] =  $cat_id;
			$ArrAds[$i] = $value;
			
					$images = (new \yii\db\Query())
					->select(['ads_images.*', 'media_upload.file_name', 'media_upload.upload_base_path'])
					->from('ads_images')
					->leftjoin('media_upload', 'ads_images.media_id = media_upload.id')
					->where(['ads_images.ad_id' => $value['id']])
					->all();
			
			$value['ads_images'] =  $images;
			$ArrAds[$i] = $value;
			
			$i++;
		}	
         return array('ArrAds'=>$ArrAds,'pages'=>$pages);
     }
     
     
      public function PendingAds($user_id = null) {		
		$title = Yii::t('frontend', 'Pending Ads');
		if(!empty($user_id)){
			$userId = $user_id;
		}else{
			$userId = Yii::$app->user->identity['id'];
		}
		
			$allAds = (new \yii\db\Query())
				->select(['post_ads.*','cities.name as city_name','states.name as state_name','countries.name as country_name'])
				->from('post_ads')
				->leftjoin('cities', 'post_ads.city = cities.id')
				->leftjoin('states', 'post_ads.state = states.id')
				->leftjoin('countries', 'post_ads.country = countries.id')
				->where(['post_ads.user_id' => $userId])
				->andWhere(['post_ads.status' => PostAd::STATUS_PENDING])
				->orderBy(['post_ads.id' => SORT_DESC]);
			$countQuery = clone $allAds;
			$page_size = Settings::getPageSize();
			$pages = new Pagination(['totalCount' => $countQuery->count(), 'pageSize'=>$page_size]);
			$allAds = $allAds->offset($pages->offset)->limit($pages->limit)->all();		
				
				
		//echo "<pre>"; print_r($allAds);exit;	
			
		$i = 0;	
		$ArrAds = array();
		foreach($allAds as $key=>$value){
			
			$cat_id = (new \yii\db\Query())
					->select(['*'])
					->from('ads_category')
					->where(['ad_id' => $value['id']])
					->all();		
			$value['category_id'] =  $cat_id;
			$ArrAds[$i] = $value;
	
					$images = (new \yii\db\Query())
					->select(['ads_images.*', 'media_upload.file_name', 'media_upload.upload_base_path'])
					->from('ads_images')
					->leftjoin('media_upload', 'ads_images.media_id = media_upload.id')
					->where(['ads_images.ad_id' => $value['id']])
					->all();
			
			$value['ads_images'] =  $images;
			$ArrAds[$i] = $value;
			
			$i++;
		}	
         return array('ArrAds'=>$ArrAds,'pages'=>$pages);
     }
     
     
     public function HiddenAds($user_id = null) {		
		$title = Yii::t('frontend', 'Pending Ads');
		if(!empty($user_id)){
			$userId = $user_id;
		}else{
			$userId = Yii::$app->user->identity['id'];
		}
		$allAds = (new \yii\db\Query())
			->select(['post_ads.*','cities.name as city_name','states.name as state_name','countries.name as country_name'])
			->from('post_ads')
			->leftjoin('cities', 'post_ads.city = cities.id')
			->leftjoin('states', 'post_ads.state = states.id')
			->leftjoin('countries', 'post_ads.country = countries.id')
			->where(['post_ads.user_id' => $userId])
			->andWhere(['post_ads.status' => PostAd::STATUS_HIDDEN])
			->orderBy(['post_ads.id' => SORT_DESC]);
		$countQuery = clone $allAds;
		$page_size = Settings::getPageSize();
		$pages = new Pagination(['totalCount' => $countQuery->count(), 'pageSize'=>$page_size]);
		$allAds = $allAds->offset($pages->offset)->limit($pages->limit)->all();		
				
				
		//echo "<pre>"; print_r($allAds);exit;	
			
		$i = 0;	
		$ArrAds = array();
		foreach($allAds as $key=>$value){
			
			$cat_id = (new \yii\db\Query())
					->select(['*'])
					->from('ads_category')
					->where(['ad_id' => $value['id']])
					->all();		
			$value['category_id'] =  $cat_id;
			$ArrAds[$i] = $value;
					
			$images = (new \yii\db\Query())
			->select(['ads_images.*', 'media_upload.file_name', 'media_upload.upload_base_path'])
			->from('ads_images')
			->leftjoin('media_upload', 'ads_images.media_id = media_upload.id')
			->where(['ads_images.ad_id' => $value['id']])
			->all();
			
			$value['ads_images'] =  $images;
			$ArrAds[$i] = $value;
			
			$i++;
		}	
         return array('ArrAds'=>$ArrAds,'pages'=>$pages);
     }
     
     
     public function ResubmittedAds($user_id = null) {		
		$title = Yii::t('frontend', 'Pending Ads');
		if(!empty($user_id)){
			$userId = $user_id;
		}else{
			$userId = Yii::$app->user->identity['id'];
		}
		
		$allAds = (new \yii\db\Query())
			->select(['post_ads.*','cities.name as city_name','states.name as state_name','countries.name as country_name'])
			->from('post_ads')
			->leftjoin('cities', 'post_ads.city = cities.id')
			->leftjoin('states', 'post_ads.state = states.id')
			->leftjoin('countries', 'post_ads.country = countries.id')
			->where(['post_ads.user_id' => $userId])
			->andWhere(['post_ads.status' => PostAd::STATUS_RESUBMITTED])
			->orderBy(['post_ads.id' => SORT_DESC]);
		$countQuery = clone $allAds;
		$page_size = Settings::getPageSize();
		$pages = new Pagination(['totalCount' => $countQuery->count(), 'pageSize'=>$page_size]);
		$allAds = $allAds->offset($pages->offset)->limit($pages->limit)->all();	
						
		//echo "<pre>"; print_r($allAds);exit;	
			
		$i = 0;	
		$ArrAds = array();
		foreach($allAds as $key=>$value){
			
			$cat_id = (new \yii\db\Query())
					->select(['*'])
					->from('ads_category')
					->where(['ad_id' => $value['id']])
					->all();		
			$value['category_id'] =  $cat_id;
			$ArrAds[$i] = $value;
			
					$images = (new \yii\db\Query())
					->select(['ads_images.*', 'media_upload.file_name', 'media_upload.upload_base_path'])
					->from('ads_images')
					->leftjoin('media_upload', 'ads_images.media_id = media_upload.id')
					->where(['ads_images.ad_id' => $value['id']])
					->all();
			
			$value['ads_images'] =  $images;
			$ArrAds[$i] = $value;
			
			$i++;
		}	
          return array('ArrAds'=>$ArrAds,'pages'=>$pages);
     }
     
     public function FavouriteAds($user_id = null) {
		 		
		$title = Yii::t('frontend', 'Favourite Ads');
		if(!empty($user_id)){
			$userId = $user_id;
		}else{
			$userId = Yii::$app->user->identity['id'];
		}
		
		$allAds = (new \yii\db\Query())
			->select(['wishlist.id as wishlist_id','wishlist.ad_id as ad_id','wishlist.user_id as user_id','post_ads.*'])
			->from('wishlist')
			->leftjoin('post_ads', 'wishlist.ad_id = post_ads.id')
			->where(['wishlist.user_id' => $userId])
			->orderBy(['wishlist.id' => SORT_DESC]);
			$countQuery = clone $allAds;
			$page_size = Settings::getPageSize();
		$pages = new Pagination(['totalCount' => $countQuery->count(), 'pageSize'=>$page_size]);
		$allAds = $allAds->offset($pages->offset)->limit($pages->limit)->all();	
		
		$i = 0;	
		$ArrAds = array();
		foreach($allAds as $key=>$value){
			
			$cat_id = (new \yii\db\Query())
					->select(['*'])
					->from('ads_category')
					->where(['ad_id' => $value['ad_id']])
					->all();		
			$value['category_id'] =  $cat_id;
			$ArrAds[$i] = $value;
			
					$images = (new \yii\db\Query())
					->select(['ads_images.*', 'media_upload.file_name', 'media_upload.upload_base_path'])
					->from('ads_images')
					->leftjoin('media_upload', 'ads_images.media_id = media_upload.id')
					->where(['ads_images.ad_id' => $value['ad_id']])
					->all();
			
			$value['ads_images'] =  $images;
			$ArrAds[$i] = $value;
			
			$i++;
		}	
         return array('ArrAds'=>$ArrAds,'pages'=>$pages);
     }
     
     
     public function MyAdsApi($user_id = null) {		
		$title = Yii::t('frontend', 'My Ads');
		if(!empty($user_id)){
			$userId = $user_id;
		}else{
			$userId = Yii::$app->user->identity['id'];
		}
		
		$allAds = (new \yii\db\Query())
				->select(['post_ads.id', 'post_ads.user_id', 'post_ads.title', 'post_ads.slug', 'post_ads.description', 'post_ads.price','post_ads.address','post_ads.status as ad_status','post_ads.ad_type as ad_type','cities.name as city_name','states.name as state_name','countries.name as country_name'])
				->from('post_ads')
				->leftjoin('cities', 'post_ads.city = cities.id')
				->leftjoin('states', 'post_ads.state = states.id')
				->leftjoin('countries', 'post_ads.country = countries.id')
				->where(['post_ads.user_id' => $userId])
				->orderBy(['post_ads.id' => SORT_DESC])
				->all();
	
		$i = 0;	
		$ArrAds = array();
		foreach($allAds as $key=>$value){
			
			$images = (new \yii\db\Query())
			->select(['ads_images.*', 'media_upload.file_name', 'media_upload.upload_base_path'])
			->from('ads_images')
			->leftjoin('media_upload', 'ads_images.media_id = media_upload.id')
			->where(['ads_images.ad_id' => $value['id']])
			->one();
		
			$value['ads_images'] =  $images;
			$ArrAds[$i] = $value;
			
			$i++;
		}	
         return $ArrAds;
     }
     
     
     public function AdsDetail($ads_id = null, $user_id = null) {		
		$title = Yii::t('frontend', 'Ads Detail');
		
			$adDetail = (new \yii\db\Query())
				->select(['post_ads.*','cities.name as city_name','states.name as state_name','countries.name as country_name','user_profile.name as Posted_by','user_profile.avatar_path as avatar_path','user_profile.avatar_base_url as avatar_base_url'])
				->from('post_ads')
				->leftjoin('cities', 'post_ads.city = cities.id')
				->leftjoin('states', 'post_ads.state = states.id')
				->leftjoin('countries', 'post_ads.country = countries.id')
				->leftjoin('user_profile', 'post_ads.user_id = user_profile.user_id')
				->where(['post_ads.id' => $ads_id])
				->one();
			if(!empty($adDetail)){
				$wishlist_count = Wishlist::checkWishlistCount($ads_id, $user_id);
				if(!empty($wishlist_count)){
					$adDetail['wishlist'] =  1;
				}else{
					$adDetail['wishlist'] =  0;
				}
				
				$AvgReview = Review::ReviewCountByAdId($ads_id);
				//echo "<pre>";print_r($AvgReview);exit;
				if(!empty($AvgReview['rating'])){
					$adDetail['review'] =  number_format($AvgReview['rating'], 2);
				}else{
					$adDetail['review'] =  0;
				}
				
			$cat_id = (new \yii\db\Query())
					->select(['*'])
					->from('ads_category')
					->where(['ad_id' => $adDetail['id']])
					->all();		
			$adDetail['category_id'] =  $cat_id;
			
			$images = (new \yii\db\Query())
			->select(['ads_images.*', 'media_upload.file_name', 'media_upload.upload_base_path'])
			->from('ads_images')
			->leftjoin('media_upload', 'ads_images.media_id = media_upload.id')
			->where(['ads_images.ad_id' => $adDetail['id']])
			->all();
			
			$adDetail['ads_images'] =  $images;
		}
         return $adDetail;
     }
	
	
	public function MyWishlistAds($user_id = null) {		
		$title = Yii::t('frontend', 'My Wishlist Ads');
		if(!empty($user_id)){
			$userId = $user_id;
		}else{
			$userId = Yii::$app->user->identity['id'];
		}
		
		
		$allAds = (new \yii\db\Query())
				->select(['post_ads.id', 'post_ads.user_id', 'post_ads.title', 'post_ads.slug', 'post_ads.description', 'post_ads.price','post_ads.address','cities.name as city_name','states.name as state_name','countries.name as country_name'])
				->from('post_ads')
				->innerjoin('wishlist', 'post_ads.id = wishlist.ad_id')
				->leftjoin('cities', 'post_ads.city = cities.id')
				->leftjoin('states', 'post_ads.state = states.id')
				->leftjoin('countries', 'post_ads.country = countries.id')
				->where(['wishlist.user_id' => $userId])
				->orderBy(['post_ads.id' => SORT_DESC])
				->all();
	
		$i = 0;	
		$ArrAds = array();
		foreach($allAds as $key=>$value){
					
		 $images =  (new \yii\db\Query())
					->select(['ads_images.*', 'media_upload.file_name', 'media_upload.upload_base_path'])
					->from('ads_images')
					->leftjoin('media_upload', 'ads_images.media_id = media_upload.id')
					->where(['ads_images.ad_id' => $value['id']])
					->one();
			$value['is_fav'] = 1;
			$value['ads_images'] =  $images;
			$ArrAds[$i] = $value;
			$i++;
		}	
         return $ArrAds;
     }
	
	 public function checkPostCount($AdId){
		$adCount = PostAd::findOne($AdId);
		return $adCount;
	}
	
	public function setAdStatusHidden($post,$id=NULL){
		
		$userId = Yii::$app->User->id;
		$ad = PostAd::findOne($post['pro_id']);
		$ad->status = PostAd::STATUS_HIDDEN;
		$ad->save(); 
		
		$result['status']= PostAd::STATUS_HIDDEN;
		return $result;
    }
    
    
    public function setAdStatusUnHidden($post,$id=NULL){
		
		$userId = Yii::$app->User->id;
		$ad = PostAd::findOne($post['pro_id']);
		$ad->status = PostAd::STATUS_RESUBMITTED;
		$ad->save(); 
		
		$result['status']= PostAd::STATUS_RESUBMITTED;
		return $result;
    }

    /**
     * @return array statuses list
     */
    public static function statuses() {
        return [
            self::STATUS_PENDING => Yii::t('common', 'Pending'),
            self::STATUS_ACTIVE => Yii::t('common', 'Active'),
            self::STATUS_HIDDEN => Yii::t('common', 'Hidden'),
            self::STATUS_RESUBMITTED => Yii::t('common', 'Resubmitted'),
            self::STATUS_WARNING => Yii::t('common', 'Request Modification'),
            self::STATUS_REJECTED => Yii::t('common', 'Rejected'),
            self::STATUS_EXPIRE => Yii::t('common', 'Expire'),
        ];
    }
}
