<?php
namespace common\components;
use common\models\AdViews;
use common\models\Categories;
use common\models\CategoriesMedia;
use common\models\Cities;
use common\models\Countries;
use common\models\MediaUpload;
use common\models\Packages;
use common\models\PostAd;
use common\models\PostAdCategory;
use common\models\Settings;
use common\models\States;
use common\models\User;
use common\models\WarningReasons;
use common\models\AdsWarning;
use common\models\Transactions;
use Yii;
use yii\db\Query;
use yii\helpers\Url;
use common\models\UserProfile;
use yii\helpers\ArrayHelper;
use yii\data\Pagination;

class AppHelper {

    public static function userslugify($title) {        $numHits = 0;
        $slug = preg_replace("/-$/", "", preg_replace('/[^a-z0-9]+/i', "-", strtolower($title)));
        if (empty($slug)) {
            return 'n-a';
        }
        $query = new yii\db\Query;
        $query->select(['COUNT(*) AS NumHits'])
                ->from(['user_profile profile'])
                ->where('profile.slug LIKE "' . $slug . '%"');
        $command = $query->createCommand();
        $results = $command->queryAll();
        $numHits = $results[0]['NumHits'];

        $countFinal = $numHits;

        return ($countFinal > 0) ? ($slug . '-' . $countFinal) : $slug;
    }

    public static function errorMessageList($getErrors){
        $response=array();
        $errorkey_obj=$errorkey_obj_msg=array();
        foreach($getErrors as $errorkey => $error){
            $errorkey_obj[]=$errorkey;
            $errorkey_obj_msg[]=$error;
        }
        $response["status"] = 'error';
        $fields_req= implode(',',$errorkey_obj);
        $response['errormsg'] = $getErrors;
        //$response['message_key'] = 'Validation error on field: '.$fields_req;
        $response['message'] = $errorkey_obj_msg;
        return $response;
        
    }

    public static function getCategoryTreeIDs($catID) {
        $category=Categories::findOne($catID);
        $path = array();
        if (!$category['parent_id'] == '' || !$category['parent_id'] == 0) {
            $path[] = $category['parent_id'];
            $path = array_merge(AppHelper::getCategoryTreeIDs($category['parent_id']), $path);
        }
        return $path;
    }

    public static function getCategoryTitle($catID){
        $category=Categories::findOne($catID);
        return $category->title;
    }

    public static function getCategoryImage($category_id){
        $image='';
        $categoryImage=CategoriesMedia::find()->where(['category_id'=>$category_id])->one();
        if(!empty($categoryImage)){
            $mediaupload=MediaUpload::findOne($categoryImage->media_id);
            $image=$mediaupload->url.$mediaupload->upload_base_path.$mediaupload->file_name;
        }
        return $image;
    }
    
    public static function getSubCategory($category_id){
        $sub_categories=Categories::find()->where(['parent_id'=>$category_id])->all();
        return $sub_categories;
    }
    
    
    public static function getSubCategories($category_id)
    {
		$query = new Query();
			$query->select(['*'])->from('categories')
			->where(['parent_id' => $category_id]);
			$command = $query->createCommand();
			$custom_field_ids =  $command->queryAll();
		return $custom_field_ids;
	}
    

    public static function getAllPackagesList(){
        $list=array();
        $packages=Packages::find()->all();
        foreach($packages as $package){
            $list[$package->id]=$package->name;
        }
        return $list;
    }
    
    public static function getPostTime($created_time){
     
       
       $now  = strtotime(date('Y-m-d h:i:s A'));
		// Difference from given timestamp
		$difference = $now - $created_time;
		// If less than one hour (59 minutes and 30 seconds, to be precise), we count minutes
		if($difference < 3570)
		{
			$output = round($difference / 60).' minutes ago ';
		}
		// If less than 23 hours 59 minutes and 30 seconds, we count hours
		elseif ($difference < 86370)
		{
			$output = round($difference / 3600).' hours ago';
		}
		// If less than 6 days 23 hours 59 minutes and 30 seconds, we count days
		elseif ($difference < 604770)
		{
			$output = round($difference / 86400).' days ago';
		}
		// If less than 164 days 23 hours 59 minutes and 30 seconds, we count weeks
		elseif ($difference < 31535970)
		{
			$output = round($difference / 604770).' week ago';
		}
		else
		{
		  $output = round($difference / 31536000).' years ago';
		}
      
        return $output;
    }

    public static function getSiteLogo(){
        $url= Url::to('@frontendUrl').'/images/br-classified.png';
        $setting=Settings::find()->where(['name'=>'logo'])->one();
        if(!empty($setting)){
            $url=Url::to('@frontendUrl').$setting->value;
        }
        return $url;
    }
    
    public function getAdsCountByCategory($cat_id){
		
		$ads_count = (new \yii\db\Query())
					->select(['ads_category.*', 'post_ads.status'])
					->from('ads_category')
					->leftjoin('post_ads', 'ads_category.ad_id = post_ads.id')
					->where(['ads_category.cat_id' => $cat_id])
					->andWhere(['post_ads.status' => PostAd::STATUS_ACTIVE])
					->count();
		return $ads_count;
		
	}
	
	
	public function getAdsByPosition($position){
		$advertisement = (new \yii\db\Query())
					->from('advertising')
					->where(['slug' => $position])
					->one();
		return $advertisement;
		
	}

    public static function getUser_fullName($id) {
        $user = UserProfile::find()->where(['user_id' => $id])->one();
        return ucfirst($user['name']);
    }

    public static function getUserDetail($id) {
        $user = User::findOne($id);
        return $user;
    }


    public static function getAdTitle($id) {
        $advertisement = PostAd::find()->where(['id' => $id])->one();
        return ucfirst($advertisement['title']);
    }
    
    public static function getCustomFieldValue($value) {
       	$fieldvalue = (new \yii\db\Query())
					->select('label')		
					->from('custom_field_options')
					->where(['id' => $value])
					->one()['label'];
		return $fieldvalue;
    }
    

    public static function getTime($date) {
        $datetime1 = new \DateTime();
        $datetime2 = new \DateTime($date);
        $interval = $datetime1->diff($datetime2);

        $year = $interval->format('%y');
        $month = $interval->format('%m');
        $day = $interval->format('%a');
        $hr = $interval->format('%h');
        $minute = $interval->format('%i');
        $second = $interval->format('%S');

        if ($year > 0) {
            $elapsed = '' . $year . ' year ago';
        } elseif ($month > 0) {
            $elapsed = '' . $month . ' month ago';
        } elseif ($day > 0) {
            $elapsed = '' . $day . ' day ago';
        } elseif ($hr > 0) {
            $elapsed = '' . $hr . ' hour ago';
        } elseif ($minute > 0) {
            $elapsed = '' . $minute . ' minute ago';
        } elseif ($second > 0) {
            $elapsed = '' . $second . ' second ago';
        }

        return $elapsed;
    }

    public static function getPostAdDetail($postAd_id){
        $postAd_detail=array();
        $ad_detail=PostAd::findOne($postAd_id);
        if(!empty($ad_detail)){

            $postAd_detail['id']=$ad_detail->id;
            $postAd_detail['user_id']=$ad_detail->user_id;
            $postAd_detail['title']=$ad_detail->title;
            $postAd_detail['slug']=$ad_detail->slug;
            $postAd_detail['description']=$ad_detail->description;
            $postAd_detail['price']=$ad_detail->price;
            $postAd_detail['negotiate']=$ad_detail->negotiate;
            $postAd_detail['mobile']=$ad_detail->mobile;
            $postAd_detail['mobile_hidden']=$ad_detail->mobile_hidden;
            $postAd_detail['tags']=$ad_detail->tags;
            $postAd_detail['address']=$ad_detail->address;
            $postAd_detail['city']=$ad_detail->city;
            $postAd_detail['ad_type']=$ad_detail->ad_type;
            $city=Cities::getCityById($postAd_detail['city']);
            $cityname='';
            if(!empty($city)){
                $cityname=$city['name'];
            }
            $postAd_detail['city_name']=$cityname;

            $postAd_detail['state']=$ad_detail->state;
            $state=States::getStateDetail($postAd_detail['state']);
            $statename='';
            if(!empty($state)){
                $statename=$state['name'];
            }
            $postAd_detail['state_name']=$statename;

            $postAd_detail['country']=$ad_detail->country;
            $country=Countries::getCountryById($postAd_detail['country']);
            $countryname='';
            if(!empty($country)){
                $countryname=$country['name'];
            }
            $postAd_detail['country_name']=$countryname;

            $postAd_detail['status']=$ad_detail->status;
            $postAd_detail['created_at']=$ad_detail->created_at;
            $postAd_detail['updated_at']=$ad_detail->updated_at;
            $categories=AppHelper::getPostAdCategories($postAd_id);
           // $postAd_detail['categories']=$categories;
            $postAd_detail['category']=$categories['category'];
            $postAd_detail['subcategory']=$categories['subcategory'];
            $postAd_detail['viewcount'] = AdViews::AdViewsCountByAdId($postAd_id);

        }
        return $postAd_detail;
    }

    public static function getPostAdCategories($postAd_id){
        $cat_array=array(); $name_array=array();
        $label_category='';
        $categories = PostAdCategory::find()->joinWith('categories')
            ->andWhere(['ads_category.ad_id'=>$postAd_id])
            ->orderBy('categories.parent_id')
            ->all();

        $c=0;
        $name_array['category']='';
        $name_array['subcategory']='';
        foreach($categories as $category){
            $detail=Categories::findOne($category->cat_id);
            if($detail->parent_id == 0){
                $name_array['category']=$detail->title;
            }
            else{
                $name_array['subcategory']=$detail->title;
            }
            /*$cat_array[$c]['id']=$category->cat_id;
            $catname= Categories::getCategoryNameById($category->cat_id);
            $cat_array[$c]['name']=$catname['title'];*/
            $c++;
        }
        return $name_array;
        //return array('list'=>$cat_array,'cat_sub'=>$name_array);
    }
    
    
	public static function getUserSubscription($user_id) {
	$subscription = (new \yii\db\Query())
				->select('*')		
				->from('user_subscription')
				->where(['user_id' => $user_id])
				->one();
	return $subscription;
    }

    
    public static function getUserDefaultSubscription() {
	$subscription = (new \yii\db\Query())
					->select('*')		
					->from('settings')
					->where(['name' => 'default_package'])
					->one();
	return $subscription;
    }
    
    public static function setPostAdValues($adDetail,$model) {
		$model->id = $adDetail['id'];
		$model->title = $adDetail['title'];
		$model->description = $adDetail['description'];
		$model->price = $adDetail['price'];
		$model->mobile = $adDetail['mobile'];
		$model->tags = $adDetail['tags'];
		$model->state = $adDetail['state'];
		$model->city = $adDetail['city'];
		$model->address = $adDetail['address'];
		$model->mobile_hidden = $adDetail['mobile_hidden'];
		$model->negotiate = $adDetail['negotiate'];
		$model->termCondition = $adDetail['termCondition'];
		return $model;
    }

    public static function get_warning_label($type) {
        $warnLabel = WarningReasons::find()->where(['type' => $type])->one();
        return $warnLabel['label'];
    }
    
    public static function getAdWarning($ad_id) {
        $warning = AdsWarning::find()->where(['ad_id' => $ad_id])->one();
        return $warning;
    }
    
    public static function getUserProfileImage($user_id) {
		
		$s3Enable = Settings::getSettingByName('s3_bucket');
		$s3BucketPath = Settings::getSettingByName('s3_bucket_url');
		$localPath = Settings::getSettingByName('image_url_localpath');
		$userprofile = UserProfile::find()->where(['user_id' => $user_id])->one();
		
		if(!isset($userprofile->avatar_path) && empty($userprofile->avatar_path)){
			$baseUrl=Yii::getAlias('@frontendUrl');
			$profile_image = $baseUrl.'/images/user-img.png';
			}else{
			if($s3Enable == 1){
				$profile_image = $s3BucketPath.$userprofile->avatar_path;
			}else{
				$profile_image = $localPath.$userprofile->avatar_path;
			}
		}
        return $profile_image;
    }
    public static function getUserMembershipDate($user_id){
		$transactions = Transactions::find()->orderBy(['id' => SORT_DESC])->where(['user_id' => $user_id])->one();
		$membershipdate = date('d-m-Y',$transactions->created_at);
		return $membershipdate;
	}
    
    
    
}
