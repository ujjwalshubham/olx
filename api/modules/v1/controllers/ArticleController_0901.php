<?php

namespace api\modules\v1\controllers;

use common\components\Olx;
use common\actions\SetLocaleAction;
use common\models\User;
use api\modules\v1\resources\Article;
use yii\data\ActiveDataProvider;
use yii\rest\ActiveController;
use yii\rest\IndexAction;
use yii\rest\OptionsAction;
use yii\rest\CreateAction;
use yii\rest\UpdateAction;
use yii\rest\DeleteAction;
use yii\rest\Serializer;
use yii\rest\ViewAction;
use yii\web\HttpException;
use frontend\modules\category\models\category;
use common\models\PostAd;
use common\models\Cities;
use common\models\Countries;
use common\models\States;
use common\models\SettingsCategory;
use common\models\Wishlist;
use common\models\Review;
use common\models\PostAdCategory;
use common\models\PostAdCustomFields;
use common\models\MediaUpload;
use common\components\AppFileUploads;
use common\components\QuickBlox;

use frontend\modules\user\models\SignupForm;
use common\components\MyHelper;
use common\components\AppHelper;

use frontend\modules\user\models\LoginForm;
Use frontend\modules\user\models\PasswordResetRequestForm;
use common\models\UserProfile;
use common\models\PostAdImages;
use Yii;
use yii\web\Response;
use yii\helpers\Url;
use yii\db\Query;
use yii\db\Expression;

/**
 * @SWG\Swagger(
 *     schemes={"http","https"},
 *     basePath="/",
 *     @SWG\Info(
 *         version="1.0.0",
 *         title="Yii2-Starter-Kit API Documentation",
 *         description="API description...",
 *         termsOfService="",
 *         @SWG\License(
 *             name="BSD License",
 *             url="https://raw.githubusercontent.com/yii2-starter-kit/yii2-starter-kit/master/LICENSE.md"
 *         )
 *     ),
 * )
 * @author Eugene Terentev <eugene@terentev.net>
 */
class ArticleController extends ActiveController
{
	const STATUS_NOT_ACTIVE = 0;
	const STATUS_ACTIVE = 1;
    /**
     * @var string
     */
    public $modelClass = 'api\modules\v1\resources\Article';

    /**
     * @SWG\Get(path="/v1/article/index",
     *     tags={"article", "index"},
     *     summary="Retrieves the collection of Articles.",
     *     @SWG\Response(
     *         response = 200,
     *         description = "Article collection response",
     *         @SWG\Schema(ref = "#/definitions/Article")
     *     ),
     * )
     *
     * @SWG\Get(path="/v1/article/view",
     *     tags={"Article"},
     *     summary="Displays data of one article only",
     *     @SWG\Response(
     *         response = 200,
     *         description = "Used to fetch information of a specific article.",
     *         @SWG\Schema(ref = "#/definitions/Article")
     *     ),
     * )
     *
     * @SWG\Options(path="/v1/article/options",
     *     tags={"Article"},
     *     summary="Displays the options for the article resource.",
     *     @SWG\Response(
     *         response = 200,
     *         description = "Displays the options available for the Article resource",
     *         @SWG\Schema(ref = "#/definitions/Article")
     *     ),
     * )
     */
     
     
    
     public function init(){
		  $lang = Yii::$app->request->post()['lang'];
          \Yii::$app->language = array_key_exists($lang, Yii::$app->params['availableLocales']) ? $lang : 'en';
         
		/*if(isset(Yii::$app->params['language']))
			\Yii::$app->language = Yii::$app->params['language'];
		else 
			\Yii::$app->language = 'en';*/
	
	}
    public function actions()
    {
    
        return [
            'index' => [
                'class' => IndexAction::class,
                'modelClass' => $this->modelClass,
                'prepareDataProvider' => [$this, 'prepareDataProvider']
            ],
            'view' => [
                'class' => ViewAction::class,
                'modelClass' => $this->modelClass,
                'findModel' => [$this, 'findModel']
            ],
            'options' => [
                'class' => OptionsAction::class,

            ]
        ];
    }
 public function actionFastdd()
    {
        die('adsdfsdfafsdf');
    }
    /**
     * @return ActiveDataProvider
     */
    public function prepareDataProvider()
    {
        return new ActiveDataProvider(array(
            'query' => Article::find()->with('category', 'articleAttachments')->published()
        ));
    }

    /**
     * @param $id
     * @return array|null|\yii\db\ActiveRecord
     * @throws HttpException
     */
     
     
    /*public function findModel($id)
    {
        $model = Article::find()
            ->published()
            ->andWhere(['id' => (int)$id])
            ->one();
        if (!$model) {
            throw new HttpException(404);
        }
        return $model;
    }*/
    
    
    protected function findModel($id) {
        if (($model = UserProfile::findOne(['user_id' => $id])) !== null) {
            return $model;
        } else {
            $response = MyHelper::responsearray(1, true, Yii::t('frontend', 'User Does not exists'));
            \Yii::$app->response->format = Response::FORMAT_JSON;
            return $response;
        }
    }

    
    public function actionSignup() {
        $response = $data = array();
        $params = Yii::$app->request->post();
        $model = new SignupForm();
        if (!empty($params['mobile']) && !empty($params['devicetoken']) && !empty($params['device_type'])) {
            $checkEmail = 0;
            if (!empty($params['mobile']))
                $checkEmail = User::getEmailExist($email = '', $params['mobile']); 
             
            if ($checkEmail > 0) {
                $otp = '1234';
                User::updateAll(array('devicetoken' => $params['devicetoken'], 'device_type' => $params['device_type'], 'deviceid' => $params['deviceid'], 'otp' => $otp), '(mobile = "' . $params['mobile'] . '")');
                $userDetail = User::getUserByMobile($params['mobile']);
                $data = array('mobile'=>$userDetail->mobile,'otp'=>$userDetail->otp);
                $response = MyHelper::responsearray(0, false, Yii::t('frontend', 'Please enter otp to verify.'), $data);
            } else {
                $model->mobile = $params['mobile'];
                $model->devicetoken = $params['devicetoken'];
                $model->deviceid = $params['deviceid'];
                $model->device_type = $params['device_type'];
                if ($model->validate()) {                      
                    $user = $model->signup();
                    $userDetail = UserProfile::getUserDetail($user->id);
                    $data = array('mobile'=>$userDetail['mobile'],'otp'=>$userDetail['otp']);
                    $response = MyHelper::responsearray(0, false, Yii::t('frontend', 'Your account has been successfully created. Please enter otp to verify.'), $data);
                } else {
                    $response = MyHelper::validationErrorMessage($model->getErrors());
                }
            }
        } else {
            $response = MyHelper::responsearray(1, true, Yii::t('frontend', 'Mandatory fields are required'));
        }
        \Yii::$app->response->format = Response::FORMAT_JSON;
        return $response;
    }
    
    
     public function actionSociallogin() {

        $response = $data = array();
        $params = Yii::$app->request->post();
        
        $model = new SignupForm();
       
        //$model->scenario = SignupForm::SCENARIO_API;
        
        if (isset($params['social_token']) && $params['social_token'] != '' && isset($params['email']) && $params['email'] != '' && isset($params['social_type']) && $params['social_type'] != '') {
            $checkEmail = 0;
            
          //echo "<pre>"; print_r($model);exit;
            if (!empty($params['email']))
                $checkEmail = User::getSocailEmailExist($params['email']); 
                
                
              //echo "<pre>"; print_r($userId);exit;
            if ($checkEmail > 0) {
				
				 
				$userId = User::getIdByEmail($params['email']); 
				User::updateAll(array('devicetoken' => $params['devicetoken'], 'device_type' => $params['device_type'], 'deviceid' => $params['deviceid']), '(email = "' . $params['email'] . '")');
                $userDetail = UserProfile::getUserDetail($userId->id);
                
                $dataArray = array('userid' => $userDetail['id'], 'status' => $userDetail['status'],'name' => $userDetail['name'], 'email' => $userDetail['email'],'access_token' =>$userDetail['access_token'],  'avatar' => $userDetail['avatar_path'] != '' ? $userDetail['avatar_base_url'] . '/thumb_' . $userDetail['avatar_path'] : '');
                
                $response = MyHelper::responsearray(0, false, Yii::t('frontend', 'Your account has been successfully Login'), $userDetail);
            } else {
                

                $model->email = $params['email'];
                $model->social_type = $params['social_type'];
                $model->social_token = $params['social_token'];
                $model->devicetoken = $params['devicetoken'];
                $model->deviceid = $params['deviceid'];
                $model->device_type = $params['device_type'];
                $model->status = 2;
                //$model->user_type = "user";
               

                if ($model->validate()) {
					// echo "<pre>"; print_r($model);exit;
                    $user = $model->signup();
                   
                    //QuickBlox::quickboxSinupUser($user->id);
                    $userDetail = UserProfile::getUserDetail($user->id);
                    //$data['quickbloxid'] = $userDetail['quickblox_id'];
                    $data = array();
                    $response = MyHelper::responsearray(0, false, Yii::t('frontend', 'Your account has been successfully created.'), $userDetail);
                } else { 
                    $response = MyHelper::validationErrorMessage($model->getErrors());
                }
            }
        } else {
            $response = MyHelper::responsearray(1, true, Yii::t('frontend', 'Mandatory fields are required'));
        }
        \Yii::$app->response->format = Response::FORMAT_JSON;
        return $response;
    }
    
    
    
    public function actionLogin() {
        $response = array();
        $LoginForm = array();
        $params = Yii::$app->request->post();
        $model = new LoginForm();
        $LoginForm['LoginForm'] = Yii::$app->request->post();
        if (isset($params['devicetoken']) && isset($params['identity']) && isset($params['password'])) {
            if ($model->load($LoginForm) && $model->login()) {
                User::updateAll(array('devicetoken' => $params['devicetoken'], 'device_type' => $params['device_type'], 'deviceid' => $params['deviceid']), '(email = "' . $params['identity'] . '") OR (mobile = "' . $params['identity'] . '") ');
                $userDetail = UserProfile::getUserDetail($model->user->id);
                
                $dataArray = array('userid' => $userDetail['id'], 'status' => $userDetail['status'],'name' => $userDetail['name'], 'email' => $userDetail['email'],'access_token' =>$userDetail['access_token'],  'avatar' => $userDetail['avatar_path'] != '' ? $userDetail['avatar_base_url'] . '/thumb_' . $userDetail['avatar_path'] : '');

                $response = MyHelper::responsearray(0, false, Yii::t('frontend', 'Login Successful'), $dataArray);
            } else {
				
                $response = MyHelper::responsearray(1, true, Yii::t('frontend', 'Incorrect credential or your account not verify', $params['lang']));
            }
        } else {
            $response = MyHelper::responsearray(1, true, Yii::t('frontend', 'Email, password, token fields are required'));
        }
        \Yii::$app->response->format = Response::FORMAT_JSON;
        return $response;
    }
    
    public function actionMyprofile() {
        $response = $data = $reviews = array();
        $params = Yii::$app->request->post();
        $model = new SignupForm();
        
       // echo "<pre>";print_r($params);exit;
        if (isset($params['access_token']) && !empty($params['access_token'])) {
			$checkUser = User::findIdentityByAccessToken($params['access_token']);
            if (!empty($checkUser)) {//echo "<pre>";print_r($checkUser);exit;
                $userDetail = UserProfile::getUserDetail($checkUser->id);
                $userDetail['membership'] = 'Free';
                $userDetail['total'] = PostAd::adsCountByUser($checkUser->id);;
                $userDetail['premium'] = PostAd::premiumAdsCountByUser($checkUser->id);
               // echo "<pre>";print_r($userDetail);exit;
            
                $response = MyHelper::responsearray(0, false, Yii::t('frontend', 'Success'), $userDetail);
            } else {
                $response = MyHelper::responsearray(1, true, Yii::t('frontend', 'User does not exists'));
            }
        } else {
            $response = MyHelper::responsearray(1, true, Yii::t('frontend', 'access_token fields are required'));
        }
        \Yii::$app->response->format = Response::FORMAT_JSON;
        return $response;
    }
    
    
    public function actionViewprofileById() {
        $response = $data = $reviews = array();
        $params = Yii::$app->request->post();
     
        
       // echo "<pre>";print_r($params);exit;
        if (isset($params['user_id']) && !empty($params['user_id'])) {
			$checkUser = User::findByUserId($params['user_id']);
            if (!empty($checkUser)) {//echo "<pre>";print_r($checkUser);exit;
                $userDetail = UserProfile::getUserDetail($checkUser->id);
            
                $response = MyHelper::responsearray(0, false, Yii::t('frontend', 'Success'), $userDetail);
            } else {
                $response = MyHelper::responsearray(1, true, Yii::t('frontend', 'User does not exists'));
            }
        } else {
            $response = MyHelper::responsearray(1, true, Yii::t('frontend', 'User id fields are required'));
        }
        \Yii::$app->response->format = Response::FORMAT_JSON;
        return $response;
    }
    
    public function actionUpdateprofile() {
		
        $response = $data = array();
        $params = Yii::$app->request->post();
        if (isset($params['access_token']) && !empty($params['access_token']) && isset($params['name']) && !empty($params['name']) && isset($params['address']) && !empty($params['address'])) {
			$checkUser = User::findIdentityByAccessToken($params['access_token']);
			if (!empty($checkUser)) {
            $profile = UserProfile::setModel($this->findModel($checkUser->id));
            
            $avatar = $profile['avatar_path'];
            $postarr['UserProfile']['name'] = $params['name'];
            $postarr['UserProfile']['address'] = $params['address'];
            $postarr['UserProfile']['website'] = $params['website'];
            $postarr['UserProfile']['about'] = $params['about'];
            $postarr['UserProfile']['facebook_url'] = $params['facebook_url'];
            $postarr['UserProfile']['google_plus_url'] = $params['google_plus_url'];
            $postarr['UserProfile']['twitter_url'] = $params['twitter_url'];
            $postarr['UserProfile']['instagram_url'] = $params['instagram_url'];
            $postarr['UserProfile']['linkedin_url'] = $params['linkedin_url'];
            $postarr['UserProfile']['youtube_url'] = $params['youtube_url'];
            
            if ($profile->load($postarr)) {
                if ($profile->save()) {
					
					if(isset($_FILES['profile_image']) && !empty($_FILES['profile_image'])){
						
						//Yii::info($_FILES['profile_image'], __METHOD__);
						$images[] = '';
						foreach($_FILES['profile_image'] as $key=>$value){
							$images['profile_image'][$key]['avatar_path'] = $value;
							
						}
						
						$imageUpload=AppFileUploads::updateProfileImage($checkUser->id, array('UserProfile' =>array_filter($images['profile_image'])));
						
					}	
                    $updateProfile = UserProfile::getUserDetail($checkUser->id);
                   
                    //$dataArray = array('name' => $updateProfile['name'], 'avatar'    => $updateProfile['avatar_path'] != '' ? $updateProfile['avatar_base_url'] . '/small_user_' . $updateProfile['avatar_path'] : '');

                    $response = MyHelper::responsearray(0, false, Yii::t('frontend', 'Profile updated successful'), $updateProfile);
                }
            }
		}else {
                $response = MyHelper::responsearray(1, true, Yii::t('frontend', 'User does not exists'));
            }
            
        } else {
            $response = MyHelper::responsearray(1, true, Yii::t('frontend', 'Mandatory fields are required'));
        }
        \Yii::$app->response->format = Response::FORMAT_JSON;
        return $response;
    }
    
    
    public function actionUpdateEmail() {
		
        $response = $data = array();
        $params = Yii::$app->request->post();
       
        if (isset($params['access_token']) && !empty($params['access_token']) && isset($params['email']) && !empty($params['email'])) {
			$checkUser = User::findIdentityByAccessToken($params['access_token']);
		
			if (!empty($checkUser)) {
            $profile = UserProfile::setModel($this->findModel($checkUser->id));
          
            $postarr['User']['email'] = $params['email'];
            $postarr['User']['email_otp'] = (int)1234;
            $postarr['User']['email_verify'] = 0;
         
            if ($checkUser->load($postarr)) {
				
                if ($checkUser->save()) {
                    $updateProfile = UserProfile::getUserDetail($checkUser->id);
                    $response = MyHelper::responsearray(0, false, Yii::t('frontend', 'Enter the 4-digit code send via SMS on '.$checkUser['mobile']), $updateProfile);
                }else{
				echo "<pre>"; print_r( $checkUser->getErrors());exit;	
				}
            }
		}else {
                $response = MyHelper::responsearray(1, true, Yii::t('frontend', 'User does not exists'));
            }
            
        } else {
            $response = MyHelper::responsearray(1, true, Yii::t('frontend', 'Mandatory fields are required'));
        }
        \Yii::$app->response->format = Response::FORMAT_JSON;
        return $response;
    }
    
    
    
    
    public function actionForgotpassword() {
        $response = $data = array();
        $model = new PasswordResetRequestForm();
        $params = Yii::$app->request->post();
        $data['PasswordResetRequestForm']['email'] = $params['email'];
        $checkEmailOrPhone = User::getEmailExist($params['email'], '');
        $key = $params['email'];
        if ($params['email'] != '' && isset($params['email'])) {
            if ($checkEmailOrPhone == 0) {
                $response = MyHelper::responsearray(1, true, Yii::t('frontend', 'This email address has not been registered.'));
            } else if ($model->load($data) && $model->validate()) {
                if ($model->sendEmail()) {
                    $response = MyHelper::responsearray(0, false, Yii::t('frontend', 'Check your email for further instructions'));
                } else {
                    $response = MyHelper::responsearray(1, true, Yii::t('frontend', 'Sorry, we are unable to reset password for email provided'));
                }
            } else {
                $response = MyHelper::responsearray(1, true, Yii::t('frontend', 'There is no user with such ') . $key);
            }
        } else {
            $response = MyHelper::responsearray(1, true, Yii::t('frontend', 'Mandatory fields are required'));
        }
        \Yii::$app->response->format = Response::FORMAT_JSON;
        return $response;
    }
    
    public function actionCategory() {
		
		$setting_slug = 'ad_post';
		$ad_setting = SettingsCategory::findOne(['slug' => $setting_slug]);
		$ad_setting_fields = (new \yii\db\Query())
				->select(['*'])
				->from('settings')
				->where(['category' => $ad_setting['id']])
				->andWhere(['name' => 'price_field'])
				->one();
		
	    $price_field_cat_ids =  explode(',',$ad_setting_fields['value']);
		
        $response = $data = array();
        $params = Yii::$app->request->post();
         
        $categories = Olx::getAllCategory($params['lang'], $params['cat_id']);
        if(in_array($params['cat_id'],$price_field_cat_ids)){
					$price_show = 1;	
				}else{
					$price_show = 0;	
				}
        //echo "<pre>"; print_r($categories);exit;
       
        $rows = array();
        $catDetail = array();

        if ($categories) {
            foreach ($categories as $data) { //echo "<pre>";print_r($data);exit;
                
                $rows['id'] = $data['id'];
                $rows['title'] = $data['title'];
                $rows['slug'] = $data['slug'];
                $rows['description'] = $data['description'];
                $rows['image'] = AppHelper::getCategoryImage($data['id']);
                $rows['price_show'] = $price_show;
                //$rows['description'] = $subCats;
                //$rows['icon'] = $data['fullimage'] != '' ? $data['base_url'] . '/' . $data['fullimage'] : Url::to('@frontendUrl') . '/images/fav1.png';
				
                $catDetail[] = $rows;
            }
            $response['code'] = 0;
            $response['error'] = false;
           // $response['message'] = Yii::t('frontend', 'Success');
           if(!empty($params['cat_id'])){
			    $catDetails = $catDetail;
		   }else{
			   $catDetails = $catDetail;
			   //$allArr = array('0' => array('all'=>'All'));
			   //$catDetails = array_merge($allArr, $catDetail);
           
		}
		$response['data'] = $catDetails;
		$response['price_show'] = $price_show;
		
$response = MyHelper::responsearray(0, false, Yii::t('frontend', 'Success'), $catDetails);
        } else {
            $response = MyHelper::responsearray(1, true, Yii::t('frontend', 'Category not found'));
        }

        \Yii::$app->response->format = Response::FORMAT_JSON;
        return $response;
    }
    
    
    public function actionLatestads() {
        $response = $data = $reviews = array();
        $params = Yii::$app->request->post();
        
       $allAds = (new \yii\db\Query())
				->select(['post_ads.id', 'post_ads.user_id', 'post_ads.title', 'post_ads.slug', 'post_ads.description', 'post_ads.price','post_ads.address','cities.name as city_name','states.name as state_name','countries.name as country_name'])
				->from('post_ads')
				->leftjoin('cities', 'post_ads.city = cities.id')
				->leftjoin('states', 'post_ads.state = states.id')
				->leftjoin('countries', 'post_ads.country = countries.id');
				
        if (isset($params['access_token']) && !empty($params['access_token'])) {			
			$checkUser = User::findIdentityByAccessToken($params['access_token']);
            if (!empty($checkUser)) {//echo "<pre>";print_r($checkUser);exit;				
                $allAds = $allAds->where(['!=', 'post_ads.user_id', $checkUser->id]);				
            } 
        } 
        
        if (isset($params['country_id']) && !empty($params['country_id'])) {
                $allAds = $allAds->andwhere(['=', 'post_ads.country', $params['country_id']]);
        }
        
        if (isset($params['state_id']) && !empty($params['state_id'])) {
                $allAds = $allAds->andwhere(['=', 'post_ads.state', $params['state_id']]);
        }
        
        if (isset($params['city_id']) && !empty($params['city_id'])) {
                $allAds = $allAds->andwhere(['=', 'post_ads.city', $params['city_id']]);
        } 
        $total = $allAds->count();
        $allAds = $allAds->limit($params['limit'])
					->offset($params['offset'])
				->orderBy(['id' => SORT_DESC])
				->all();
		$remaining_record = $total - $params['offset'];
		
        $i = 0;
		$ArrAds['features'] = array();
		$ArrAds['latest'] = array();
		if(!empty($allAds)){
		foreach($allAds as $key=>$value){
			
			/*$cat_id = (new \yii\db\Query())
					->select(['*'])
					->from('ads_category')
					->where(['ad_id' => $value['id']])
					->all();
			$value['category_id'] =  $cat_id;
			$ArrAds['latest'][$i] = $value;*/
			
		
				$images = (new \yii\db\Query())
					->select(['ads_images.*', 'media_upload.file_name', 'media_upload.upload_base_path'])
					->from('ads_images')
					->leftjoin('media_upload', 'ads_images.media_id = media_upload.id')
					->where(['ads_images.ad_id' => $value['id']])
					->one();
					
			
			$value['ads_images'] =  $images;
			//$ArrAds['title'] = "features";
			$ArrAds['latest'][$i] = $value;
			
			$i++;
		}
	}
		
		
		
		 /*$featuresAds = (new \yii\db\Query())
				->select(['post_ads.*','cities.name as city_name','states.name as state_name','countries.name as country_name'])
				->from('post_ads')
				->leftjoin('cities', 'post_ads.city = cities.id')
				->leftjoin('states', 'post_ads.state = states.id')
				->leftjoin('countries', 'post_ads.country = countries.id');
        if (isset($params['access_token']) && !empty($params['access_token'])) {			
			$checkUser = User::findIdentityByAccessToken($params['access_token']);
            if (!empty($checkUser)) {		
                $featuresAds = $featuresAds->where(['!=', 'post_ads.user_id', $checkUser->id]);				
            } 
        }
        
        if (isset($params['country_id']) && !empty($params['country_id'])) {
                $featuresAds = $featuresAds->andwhere(['=', 'post_ads.country', $params['country_id']]);
        }
        
        if (isset($params['state_id']) && !empty($params['state_id'])) {
                $featuresAds = $featuresAds->andwhere(['=', 'post_ads.state', $params['state_id']]);
        } 
        
        if (isset($params['city_id']) && !empty($params['city_id'])) {
                $featuresAds = $featuresAds->andwhere(['=', 'post_ads.city', $params['city_id']]);
        }
        $features_total = $featuresAds->count();
        $remaining_features_record = $features_total - $params['offset'];
        $featuresAds = $featuresAds->limit($params['limit'])
					->offset($params['offset'])
				->orderBy(new Expression('rand()'))
				->all();
        $j = 0;
		if(!empty($featuresAds)){
		foreach($featuresAds as $key=>$value){
			
			$cat_id = (new \yii\db\Query())
					->select(['*'])
					->from('ads_category')
					->where(['ad_id' => $value['id']])
					->all();
			$value['category_id'] =  $cat_id;
			$ArrAds['features'][$j] = $value;
			
			$images = (new \yii\db\Query())
					->select(['ads_images.*', 'media_upload.file_name', 'media_upload.upload_base_path'])
					->from('ads_images')
					->leftjoin('media_upload', 'ads_images.media_id = media_upload.id')
					->where(['ads_images.ad_id' => $value['id']])
					->all();
			
			$value['ads_images'] =  $images;
			
			$ArrAds['features'][$j] = $value;
			
			$j++;
		}
	}*/
	
		
		/*$ArrAds1 = array('0'=>array('title'=>'latest','total_record'=>$total,'remaining_record'=>$remaining_record,'datas'=>$ArrAds['latest']), '1'=>array('title'=>'features','total_record'=>$features_total,'remaining_features_record'=>$remaining_features_record,'datas'=>$ArrAds['features']));*/
		
		$ArrAds1 = array('title'=>'latest','total_record'=>$total,'remaining_record'=>$remaining_record,'datas'=>$ArrAds['latest']);
	if(!empty($ArrAds['latest'])){
        $response = MyHelper::responsearray(0, false, Yii::t('frontend', 'Success'), $ArrAds1);
	}else{
		$response = MyHelper::responsearray(0, false, Yii::t('frontend', 'Data Not Found.'), $ArrAds1);
	}
        \Yii::$app->response->format = Response::FORMAT_JSON;
        return $response;
    }
    
    
    public function actionFeatures() {
        $response = $data = $reviews = array();
        $params = Yii::$app->request->post();
        $ArrAds['features'] = array();
       $featuresAds = (new \yii\db\Query())
				->select(['post_ads.id', 'post_ads.user_id', 'post_ads.title', 'post_ads.slug', 'post_ads.description', 'post_ads.price','post_ads.address','cities.name as city_name','states.name as state_name','countries.name as country_name'])
				->from('post_ads')
				->leftjoin('cities', 'post_ads.city = cities.id')
				->leftjoin('states', 'post_ads.state = states.id')
				->leftjoin('countries', 'post_ads.country = countries.id');
        if (isset($params['access_token']) && !empty($params['access_token'])) {			
			$checkUser = User::findIdentityByAccessToken($params['access_token']);
            if (!empty($checkUser)) {//echo "<pre>";print_r($checkUser);exit;				
                $featuresAds = $featuresAds->where(['!=', 'post_ads.user_id', $checkUser->id]);				
            } 
        }
        
        if (isset($params['country_id']) && !empty($params['country_id'])) {
                $featuresAds = $featuresAds->andwhere(['=', 'post_ads.country', $params['country_id']]);
        }
        
        if (isset($params['state_id']) && !empty($params['state_id'])) {
                $featuresAds = $featuresAds->andwhere(['=', 'post_ads.state', $params['state_id']]);
        } 
        
        if (isset($params['city_id']) && !empty($params['city_id'])) {
                $featuresAds = $featuresAds->andwhere(['=', 'post_ads.city', $params['city_id']]);
        }
        $features_total = $featuresAds->count();
        $remaining_features_record = $features_total - $params['offset'];
        $featuresAds = $featuresAds->limit($params['limit'])
					->offset($params['offset'])
				->orderBy(new Expression('rand()'))
				->all();
        $j = 0;
		//$ArrAds['title'] = 'features';
		if(!empty($featuresAds)){
		foreach($featuresAds as $key=>$value){
			
			/*$cat_id = (new \yii\db\Query())
					->select(['*'])
					->from('ads_category')
					->where(['ad_id' => $value['id']])
					->all();
			$value['category_id'] =  $cat_id;
			$ArrAds['features'][$j] = $value;*/
			
			$images = (new \yii\db\Query())
					->select(['ads_images.*', 'media_upload.file_name', 'media_upload.upload_base_path'])
					->from('ads_images')
					->leftjoin('media_upload', 'ads_images.media_id = media_upload.id')
					->where(['ads_images.ad_id' => $value['id']])
					->one();
			
			$value['ads_images'] =  $images;
			//$ArrAds['title'] = "latest";
			
			$ArrAds['features'][$j] = $value;
			
			$j++;
		}
	}
		$ArrAds1 = array('title'=>'features','total_record'=>$features_total,'remaining_features_record'=>$remaining_features_record,'datas'=>$ArrAds['features']);
		if(!empty($ArrAds['features'])){
        $response = MyHelper::responsearray(0, false, Yii::t('frontend', 'Success'), $ArrAds1);
	}else{
		$response = MyHelper::responsearray(0, false, Yii::t('frontend', 'Success'), $ArrAds1);
	}
        \Yii::$app->response->format = Response::FORMAT_JSON;
        return $response;
    }
    
    
    
    
    public function actionGetCategoryCustomField() {
        $response = $data = $reviews = array();
			$params = Yii::$app->request->post();
          $cat_id = Yii::$app->request->post('cat_id'); 
		  $model = new PostAd();
        
        //echo "<pre>";print_r(Yii::$app->request->post());exit;
       // if (isset($params['access_token']) && !empty($params['access_token'])) {
			//$checkUser = User::findIdentityByAccessToken($params['access_token']);
           /* if (!empty($checkUser)) {
                $userDetail = UserProfile::getUserDetail($checkUser->id);*/
                
                $query = new Query();
				 $query->select(['id','field_id','category_id'])->from('categories_custom_fields')
			    ->where(['category_id' => $cat_id]);
				$command = $query->createCommand();
				$custom_field_ids =  $command->queryAll();
				
				$i = 1;	
				$ArrAds = array();	
			foreach($custom_field_ids as $key=>$custom_field_id){

			$custom_fields = (new \yii\db\Query())
						->select(['id','field_type_id','label','isRequired','status'])
						->from('custom_fields')
						->where(['id' => $custom_field_id['field_id']])
						->all();
		
			$custom_field_options = (new \yii\db\Query())
						->select(['id','field_id','label','isCheck'])
						->from('custom_field_options')
						->where(['field_id' => $custom_field_id['field_id']])
						->all();
		
		$custom_field_id['custom_fields']	 = $custom_fields[0];
		
		if(!empty($custom_field_options)){
		$custom_field_id['custom_fields_options']	 = $custom_field_options;
		}
		$ArrAds[$i] = $custom_field_id;
		$i++;
		}
		
		$j = 0;	
		$ArrAds2 = array();	
		foreach($ArrAds as $key=>$value){
		
		$custom_fields_type = (new \yii\db\Query())
							->select(['id','type','data_type','label','options_enabled','options_label','status'])
							->from('custom_field_types')
							->where(['id' => $value['custom_fields']['field_type_id']])
							->all();
	
			$value['custom_fields_type']	 = $custom_fields_type[0];
			$ArrAds2[$j] = $value;
			$j++;
		}
		//echo "<pre>";print_r($ArrAds2);exit;
            
                $response = MyHelper::responsearray(0, false, Yii::t('frontend', 'Success'), $ArrAds2);
            /*} else {
                $response = MyHelper::responsearray(1, true, Yii::t('frontend', 'User does not exists'));
            }
        } else {
            $response = MyHelper::responsearray(1, true, Yii::t('frontend', 'Access Token fields are required'));
        }*/
        //echo "<pre>";print_r($response);exit;
       // echo json_encode($response);exit;
       \Yii::$app->response->format = Response::FORMAT_JSON;
        return $response;
    }
    
    
    
    public function actionAddPost() {

        $response = $data = array();
        $categories = Olx::getAllCategory();
		$cities = Cities::getAllCities();
        $params = Yii::$app->request->post();

        Yii::info($params, __METHOD__);
        
        $model = new PostAd();
		$post_cat_model = new PostAdCategory();
		$post_custom_fields_model = new PostAdCustomFields();
		$ad_images_model = new PostAdImages();
       
        //$model->scenario = SignupForm::SCENARIO_API;
        
        if (isset($params['access_token']) && $params['access_token'] != '' && isset($params['lang']) && $params['lang'] != '' && isset($params['PostAd']) && $params['PostAd'] != '' && isset($params['PostAdCategory']) && $params['PostAdCategory'] != '') {
			
		$checkUser = User::findIdentityByAccessToken($params['access_token']);	
      
        if(!empty($checkUser)){	
					
			$shouldBeActivated = $this->shouldBeActivated();
			$postAd = json_decode($params['PostAd']);
			if($model->load(Yii::$app->request->post())){
			
            if($model->load(array('PostAd' =>(array)$postAd[0]))){
				
				 $model->status = 'Pending';
				 $model->user_id =  $checkUser->id;
				
                if($model->save()){
					
					//echo "<pre>";print_r($model);exit;
				 $id = Yii::$app->db->getLastInsertID();
			
				$categories = explode(',', $params['PostAdCategory']);
				//echo "<pre>"; print_r($categories);exit;

					foreach($categories as $key=>$category){
						$post_cat_model = new PostAdCategory();
						$post_cat_model->cat_id =  $category;
						$post_cat_model->ad_id = $id;
						$post_cat_model->save();
					}
				//echo "<pre>"; print_r($categories);exit;
				$custom_fields = json_decode($params['custom_field']);
				
				
				foreach($custom_fields as $key=>$custom_field){
					
					$field_id = key((array)$custom_field);
					
					$value = $custom_field->$field_id;
					//echo "<pre>"; print_r($custom_field);exit;
					
					$post_ad_fields_model = new PostAdCustomFields();
					
					$post_ad_fields_model->ad_id  = $id;
					$post_ad_fields_model->field_id  = $field_id;
					$post_ad_fields_model->value  = $value;
					$post_ad_fields_model->save();
				}
				
					
					if($_FILES['images']){
						$imageUpload=MediaUpload::uploadAdImage($id,array('images' => $_FILES['images']));
						//echo "<pre>";print_r($imageUpload);exit;
					}	
					$response = MyHelper::responsearray(0, false, Yii::t('frontend', 'Ad Saved Successfully'));
                }else {
					
					echo "<pre>"; print_r( $model->getErrors());exit;
                $response = MyHelper::responsearray(1, true, Yii::t('frontend', 'User does not save'));
            }
            } 
		}
        } else {
                $response = MyHelper::responsearray(1, true, Yii::t('frontend', 'User does not exists'));
            }
           
        } else {
            $response = MyHelper::responsearray(1, true, Yii::t('frontend', 'Mandatory fields are required'));
        }
        \Yii::$app->response->format = Response::FORMAT_JSON;
        return $response;
    }
    
    
    public function shouldBeActivated()
    {
        /** @var Module $userModule */
        $userModule = Yii::$app->getModule('user');
        if (!$userModule) {
            return false;
        } elseif ($userModule->shouldBeActivated) {
            return true;
        } else {
            return false;
        }
    }
    
    
    public function actionCity() {
        $response = array();
        $cData = array();
        $params = Yii::$app->request->post();
        $cityData = Cities::getCityByStateId($params['state_id']);
         foreach ($cityData as $key => $dataCou) {
            $rows['id'] = $key;
            $rows['name'] = $dataCou;
            $cData[] = $rows;
        }
        $response = MyHelper::responsearray(0, false, Yii::t('frontend', 'Success'), $cData);

        \Yii::$app->response->format = Response::FORMAT_JSON;
        return $response;
    }
    
    public function actionCountry() {
        $response = array();
        $cData = array();
        $countryData = Countries::getAllCountries();
        foreach ($countryData as $key => $dataCou) {
            $rows['id'] = $key;
            $rows['name'] = $dataCou;
            $cData[] = $rows;
        }
        $response = MyHelper::responsearray(0, false, Yii::t('frontend', 'Success'), $cData);

        \Yii::$app->response->format = Response::FORMAT_JSON;
        return $response;
    }
    
    public function actionState() {
        $response = array();
        $sData = array();
        $params = Yii::$app->request->post();
        $state = new States();
       
        if ($params['country_id'] != '') {
			$stateData = States::getStateByCountryId($params['country_id']);
        foreach ($stateData as $key => $dataCou) {
            $rows['id'] = $key;
            $rows['name'] = $dataCou;
            $sData[] = $rows;
        }
            $response = MyHelper::responsearray(0, false, Yii::t('frontend', 'Success'), $sData);
        } else {
            $response = MyHelper::responsearray(1, true, Yii::t('frontend', 'Mandatory fields are required'));
        }
        \Yii::$app->response->format = Response::FORMAT_JSON;
        return $response;
    }
    
    public function actionAdslistByUserId() {
        $response = array();
        //$cData = array();
        $params = Yii::$app->request->post();        
         if (isset($params['access_token']) && $params['access_token'] != '' && isset($params['lang']) && $params['lang'] != '') {
			
		$checkUser = User::findIdentityByAccessToken($params['access_token']);	        
        if(!empty($checkUser)){	
			$ArrAds = PostAd::MyAdsApi($checkUser->id);	
        
        $response = MyHelper::responsearray(0, false, Yii::t('frontend', 'Success'), $ArrAds);
		}else {
                $response = MyHelper::responsearray(1, true, Yii::t('frontend', 'User does not exists'));
            }
         }else {
            $response = MyHelper::responsearray(1, true, Yii::t('frontend', 'Mandatory fields are required'));
        }  
        \Yii::$app->response->format = Response::FORMAT_JSON;
        return $response;
    }
    
    
    public function actionAdsDetailById() {
        $response = array();
        $params = Yii::$app->request->post();  
        $userId = "";      
         if (isset($params['ads_id']) && $params['ads_id'] != '' && isset($params['lang']) && $params['lang'] != '') {	
			 if(!empty($params['access_token'])){
				$checkUser = User::findIdentityByAccessToken($params['access_token']);
				if(!empty($checkUser)){
					$userId = $checkUser->id;
				}else {
                $response = MyHelper::responsearray(1, true, Yii::t('frontend', 'User does not exists'));
            }
			}	
			$ArrAds = PostAd::AdsDetail($params['ads_id'], $userId);	
			if(!empty($ArrAds)){
			$response = MyHelper::responsearray(0, false, Yii::t('frontend', 'Success'), $ArrAds);
		}else{
			 $response = MyHelper::responsearray(1, true, Yii::t('frontend', 'Data Not Found.'));
		}
		
         }else {
            $response = MyHelper::responsearray(1, true, Yii::t('frontend', 'Mandatory fields are required'));
        }  
        \Yii::$app->response->format = Response::FORMAT_JSON;
        return $response;
    }
    
    public function actionAddWishlist()
    {		
		$response = array();
        $params = Yii::$app->request->post();        
         if (isset($params['access_token']) && $params['access_token'] != '' && isset($params['ads_id']) && $params['ads_id'] != '' && isset($params['lang']) && $params['lang'] != '') {
			 $checkUser = User::findIdentityByAccessToken($params['access_token']);	        
			if(!empty($checkUser)){			
			$ArrAds = PostAd::checkAdsCount($params['ads_id']);	
			if(!empty($ArrAds)){
				$wishlist = Wishlist::checkWishlistCount($params['ads_id'], $checkUser->id);
				if(empty($wishlist)){
					//echo "<pre>";print_r($wishlist);exit;
				$model = new Wishlist();
				$model['user_id'] = $checkUser->id;
				$model['ad_id'] = $params['ads_id'];
				if($model->save()){
			$response = MyHelper::responsearray(0, false, Yii::t('frontend', 'Successfully added to wishlist'));
			}
			}else{
			$deleteWishlist = Wishlist::find()
			  ->where(['ad_id'=>$params['ads_id']])
			  ->andwhere(['user_id'=>$checkUser->id])
			  ->one()
			  ->delete();
			
			$response = MyHelper::responsearray(0, false, Yii::t('frontend', 'Successfully removed to wishlist.'));
		}
		
		}else{
			 $response = MyHelper::responsearray(1, true, Yii::t('frontend', 'Ads Not Found.'));
		}
		}else{
                $response = MyHelper::responsearray(1, true, Yii::t('frontend', 'User does not exists'));
            }
         }else {
            $response = MyHelper::responsearray(1, true, Yii::t('frontend', 'Mandatory fields are required'));
        }  
        \Yii::$app->response->format = Response::FORMAT_JSON;
        return $response;
        
    }
    
    
    
    public function actionRemoveWishlist()
    {		
		$response = array();
        $params = Yii::$app->request->post();        
         if (isset($params['access_token']) && $params['access_token'] != '' && isset($params['ads_id']) && $params['ads_id'] != '' && isset($params['lang']) && $params['lang'] != '') {
			 $checkUser = User::findIdentityByAccessToken($params['access_token']);	        
			if(!empty($checkUser)){			
			$ArrAds = PostAd::checkAdsCount($params['ads_id']);	
			if(!empty($ArrAds)){
				$wishlist = Wishlist::checkWishlistCount($params['ads_id'], $checkUser->id);
				if(!empty($wishlist)){
					//echo "<pre>";print_r($wishlist);exit;
					$deleteWishlist = Wishlist::find()
			  ->where(['ad_id'=>$params['ads_id']])
			  ->andwhere(['user_id'=>$checkUser->id])
			  ->one()
			  ->delete();
				if($deleteWishlist){
			$response = MyHelper::responsearray(0, false, Yii::t('frontend', 'Successfully removed to wishlist'));
			}
		}else{
			$response = MyHelper::responsearray(1, true, Yii::t('frontend', 'Data Not Found in Wishlist.'));
		}
		
		}else{
			 $response = MyHelper::responsearray(1, true, Yii::t('frontend', 'Ads Not Found.'));
		}
		}else{
                $response = MyHelper::responsearray(1, true, Yii::t('frontend', 'User does not exists'));
            }
         }else {
            $response = MyHelper::responsearray(1, true, Yii::t('frontend', 'Mandatory fields are required'));
        }  
        \Yii::$app->response->format = Response::FORMAT_JSON;
        return $response;
        
    }
    
    
    public function actionWishlistAdsByUserId() {
        $response = array();
        //$cData = array();
        $params = Yii::$app->request->post();        
         if (isset($params['access_token']) && $params['access_token'] != '' && isset($params['lang']) && $params['lang'] != '') {
			
		$checkUser = User::findIdentityByAccessToken($params['access_token']);	        
        if(!empty($checkUser)){	
			$ArrAds = PostAd::MyWishlistAds($checkUser->id);
			if(!empty($ArrAds)){
        $response = MyHelper::responsearray(0, false, Yii::t('frontend', 'Success'), $ArrAds);
	}else{
		$response = MyHelper::responsearray(1, true, Yii::t('frontend', 'Data Not Found'));
	}
		}else {
                $response = MyHelper::responsearray(1, true, Yii::t('frontend', 'User does not exists'));
            }
         }else {
            $response = MyHelper::responsearray(1, true, Yii::t('frontend', 'Mandatory fields are required'));
        }  
        \Yii::$app->response->format = Response::FORMAT_JSON;
        return $response;
    }
    
    
    
    public function actionAllAdsList() {
        $response = array();
        $params = Yii::$app->request->post();    
         if (isset($params['lang']) && $params['lang'] != '') {			 
			 $allAds = (new \yii\db\Query())
				->select(['post_ads.*','cities.name as city_name','states.name as state_name','countries.name as country_name'])
				->from('post_ads')
				->leftjoin('cities', 'post_ads.city = cities.id')
				->leftjoin('states', 'post_ads.state = states.id')
				->leftjoin('countries', 'post_ads.country = countries.id')
				->leftjoin('post_ads_custom_fields', 'post_ads.id = post_ads_custom_fields.ad_id');
				
			 if(!empty($params['cat_id'])){				
					$allAds = $allAds->innerjoin('ads_category', 'post_ads.id = ads_category.ad_id')
					->where(['=', 'ads_category.cat_id', $params['cat_id']]);			
			}
			
			
			if(!empty($params['access_token'])){
				$checkUser = User::findIdentityByAccessToken($params['access_token']);
				if(!empty($checkUser)){
					$allAds = $allAds->andwhere(['!=', 'post_ads.user_id', $checkUser->id]);
				}
			}
			
			if(!empty($params['range1'])){
				$allAds = $allAds->andFilterWhere(['>=', 'post_ads.price', $params['range1']]);				
			}
			
			if(!empty($params['range2'])){
				$allAds = $allAds->andFilterWhere(['<=', 'post_ads.price', $params['range2']]);				
			}
			
			
			//$custom_check = json_decode($params['custom_check']);
			//echo "<pre>";print_r((array)$custom_check);exit;
			
			
			
			if(isset($params['custom_check']) && !empty($params['custom_check'])){
				$custom_check = (array)json_decode($params['custom_check']);				
				//$allAds = $allAds->leftjoin('post_ads_custom_fields', 'post_ads.id = post_ads_custom_fields.ad_id');
					
			 $query_array=array();			 
			 if (isset($custom_check) && !empty($custom_check)) {
                foreach ($custom_check as $needle) {
                    $query_array[] = sprintf('FIND_IN_SET("%s",`post_ads_custom_fields`.`value`)', $needle);
                }
                $query_str = implode(' OR ', $query_array);
                $allAds->andWhere(new \yii\db\Expression($query_str));			
			}
			
			}
			
			if(isset($params['custom']) && !empty($params['custom'])){
				$custom = (array)json_decode($params['custom']);
				//$allAds = $allAds->leftjoin('post_ads_custom_fields', 'post_ads.id = post_ads_custom_fields.ad_id');
				//$bcus = array_filter($bcustom);
				if(isset($custom) && !empty($custom)){				
						$allAds = $allAds->andFilterWhere(['IN', 'post_ads_custom_fields.value', $custom]);			
				}
				
			}
			
			
			if(!empty($params['search_key'])){				
				$allAds = $allAds->andFilterWhere(['like', 'post_ads.title', $params['search_key']]);				
			}
			
			$allAds = $allAds->andWhere(['=', 'post_ads.status', 'Active']);
			
			if(!empty($params['orderby']) &&  strtolower($params['orderby']) == 'price_desc'){				
				$allAds = $allAds->orderBy(['post_ads.price' => SORT_DESC]);				
			}elseif(!empty($params['orderby']) &&  strtolower($params['orderby']) == 'price_asc'){			
				$allAds = $allAds->orderBy(['post_ads.price' => SORT_ASC]);				
			}elseif(!empty($params['orderby']) &&  strtolower($params['orderby']) == 'id_asc'){			
				$allAds = $allAds->orderBy(['post_ads.id' => SORT_ASC]);				
			}else{				
				$allAds = $allAds->orderBy(['post_ads.id' => SORT_DESC]);				
			}
			//echo $allAds->createCommand()->getRawSql();exit;
			
			$allAds = $allAds->all();
			
				
        $i = 0;
		$ArrAds = array();
		foreach($allAds as $key=>$value){			
			/*$cat_id = (new \yii\db\Query())
					->select(['*'])
					->from('ads_category')
					->where(['ad_id' => $value['id']])
					->all();
			$value['category_id'] =  $cat_id;
			$ArrAds[$i] = $value;*/
			
		
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
        
        $response = MyHelper::responsearray(0, false, Yii::t('frontend', 'Success'), $ArrAds);
		
         }else {
            $response = MyHelper::responsearray(1, true, Yii::t('frontend', 'Mandatory fields are required'));
        }  
        \Yii::$app->response->format = Response::FORMAT_JSON;
        return $response;
    }
    
    
    public function actionAddReview()
    {		
		$response = array();
        $params = Yii::$app->request->post();        
         if (isset($params['access_token']) && $params['access_token'] != '' && isset($params['ads_id']) && $params['ads_id'] != '' && isset($params['lang']) && $params['lang'] != '' && isset($params['rating']) && $params['rating'] != '' && isset($params['comment']) && $params['comment'] != '') {
			 $checkUser = User::findIdentityByAccessToken($params['access_token']);	        
			if(!empty($checkUser)){			
			$ArrAds = PostAd::checkAdsCount($params['ads_id']);	
			if(!empty($ArrAds)){
				$wishlist = Review::checkReviewCount($params['ads_id'], $checkUser->id);
				if(empty($wishlist)){
					//echo "<pre>";print_r($wishlist);exit;
				$model = new Review();
				$model['user_id'] = $checkUser->id;
				$model['ad_id'] = $params['ads_id'];
				$model['rating'] = $params['rating'];
				$model['comment'] = $params['comment'];
				if($model->save()){
			$response = MyHelper::responsearray(0, false, Yii::t('frontend', 'Successfully added to Review'));
			}
			}else{
				 $response = MyHelper::responsearray(1, true, Yii::t('frontend', 'Already added review this Post.'));
			}
		
		}else{
			 $response = MyHelper::responsearray(1, true, Yii::t('frontend', 'Ads Not Found.'));
		}
		}else{
                $response = MyHelper::responsearray(1, true, Yii::t('frontend', 'User does not exists'));
            }
         }else {
            $response = MyHelper::responsearray(1, true, Yii::t('frontend', 'Mandatory fields are required'));
        }  
        \Yii::$app->response->format = Response::FORMAT_JSON;
        return $response;
        
    }
   
    public function actionUpdatePost() {
	
        $response = $data = array();
        $categories = Olx::getAllCategory();
		$cities = Cities::getAllCities();
        $params = Yii::$app->request->post();
        
        //$model = new PostAd();
		$post_cat_model = new PostAdCategory();
		$post_custom_fields_model = new PostAdCustomFields();
		$ad_images_model = new PostAdImages();
       
        //$model->scenario = SignupForm::SCENARIO_API;
        
        if (isset($params['ads_id']) && $params['ads_id'] != '' && isset($params['access_token']) && $params['access_token'] != '' && isset($params['lang']) && $params['lang'] != '' && isset($params['PostAd']) && $params['PostAd'] != '' && isset($params['PostAdCategory']) && $params['PostAdCategory'] != '') {
			
		$checkUser = User::findIdentityByAccessToken($params['access_token']);	
        
        if(!empty($checkUser)){	
			 $model =  PostAd::checkPostCount($params['ads_id']);	
				if(!empty($model)){	
			$shouldBeActivated = $this->shouldBeActivated();
			$postAd = json_decode($params['PostAd']);
			
			//$model->title =  'adfadf';
			//$model->save();
		//echo "<pre>";print_r($model);exit;
           //echo "<pre>";print_r($postAd[0]);exit;
				
			//if($model->load(Yii::$app->request->post())){
            //if($model->load((array)$postAd[0])){
							
				 $model->title = $postAd[0]->title;
				 $model->description = $postAd[0]->description;
				 $model->mobile = $postAd[0]->mobile;
				 $model->mobile_hidden = $postAd[0]->mobile_hidden;
				 $model->tags = $postAd[0]->tags;
				 $model->country = $postAd[0]->country;
				 $model->state = $postAd[0]->state;
				 $model->city = $postAd[0]->city;
				 $model->price = $postAd[0]->price;
				 $model->address = $postAd[0]->address;
				 $model->latitude = $postAd[0]->latitude;
				 $model->longitude = $postAd[0]->longitude;
				 $model->termCondition = $postAd[0]->termCondition;
				 $model->negotiate = $postAd[0]->negotiate;
				//echo "<pre>";print_r($model);exit;
                if($model->save()){
					$postcatDelete =  PostAdCategory::PostAdCatDelete($model->id);
					$postcustomDelete =  PostAdCustomFields::PostAdCustomDelete($model->id);
			   //echo $model->id;exit;
				$categories = explode(',', $params['PostAdCategory']);
				//echo "<pre>"; print_r($categories);exit;

					foreach($categories as $key=>$category){
						$post_cat_model = new PostAdCategory();
						$post_cat_model->cat_id =  $category;
						$post_cat_model->ad_id = $model->id;
						$post_cat_model->save();
					}
				//echo "<pre>"; print_r($categories);exit;
				$custom_fields = json_decode($params['custom_field']);
				//echo "<pre>"; print_r((array)$custom_fields[0]);
					foreach((array)$custom_fields[0] as $key=>$custom_field){
						$post_ad_fields_model = new PostAdCustomFields();

						if(is_array($custom_field)){			
						$custom_field_keys = 	array_keys((array)$custom_field[0]);						
						$keys = 	implode(",",$custom_field_keys);						
						$post_ad_fields_model->value = $keys;
						}	
						else{
							$post_ad_fields_model->value = $custom_field;
						}			
							
						$post_ad_fields_model->ad_id =  $model->id;					
						$post_ad_fields_model->field_id = $key;
						//echo "<pre>"; print_r($post_ad_fields_model);exit;
						if($post_ad_fields_model->save()){
							
						}
					}
					
					if(isset($_FILES['images']) && !empty($_FILES['images'])){
						$imageUpload=MediaUpload::uploadAdImage($model->id,array('images' => $_FILES['images']));
						//echo "<pre>";print_r($imageUpload);exit;
					}	
					$response = MyHelper::responsearray(0, false, Yii::t('frontend', 'Ad Update Successfully'));
                }else {
                $response = MyHelper::responsearray(1, true, Yii::t('frontend', 'User does not save'));
            }
            //} 
		//}
	}else {
                $response = MyHelper::responsearray(1, true, Yii::t('frontend', 'Post Id does not exists'));
            }
        } else {
                $response = MyHelper::responsearray(1, true, Yii::t('frontend', 'User does not exists'));
            }
           
        } else {
            $response = MyHelper::responsearray(1, true, Yii::t('frontend', 'Mandatory fields are required'));
        }
        \Yii::$app->response->format = Response::FORMAT_JSON;
        return $response;
    }
    
    
    
    public function actionOtpVerify() {
        $response = array();
        $otp = array();
        $params = Yii::$app->request->post();
        //$model = new LoginForm();
        //$LoginForm['LoginForm'] = Yii::$app->request->post();
        $post = Yii::$app->request->post();
        $users = User::getUserByMobile($post['identity']);
        //echo "<pre>";print_r($users);exit;
        if (isset($params['identity']) && isset($params['otp'])) {
            if (!empty($users)) {
                User::updateAll(array('email_verify' =>1), '(otp = "' . $params['otp'] . '") AND (mobile = "' . $params['identity'] . '") ');
                if($users->otp == $params['otp']){
                $userDetail = UserProfile::getUserDetail($users->id);
                
                $dataArray = array('userid' => $userDetail['id'], 'status' => $userDetail['status'],'name' => $userDetail['name'], 'email' => $userDetail['email'],'access_token' =>$userDetail['access_token'],  'avatar' => $userDetail['avatar_path'] != '' ? $userDetail['avatar_base_url'] . '/' . $userDetail['avatar_path'] : '');

                $response = MyHelper::responsearray(0, false, Yii::t('frontend', 'Otp Verify Successful'), $dataArray);
			}else{
				$response = MyHelper::responsearray(1, true, Yii::t('frontend', 'Incorrect otp', $params['lang']));
			}
            } else {
				
                $response = MyHelper::responsearray(1, true, Yii::t('frontend', 'Incorrect credential or your account not verify', $params['lang']));
            }
        } else {
            $response = MyHelper::responsearray(1, true, Yii::t('frontend', 'Email, password, token fields are required'));
        }
        \Yii::$app->response->format = Response::FORMAT_JSON;
        return $response;
    }
    
    
    public function actionEmailOtpVerify() {
		
        $response = array();
        $otp = array();
        $params = Yii::$app->request->post();
        
        //$model = new LoginForm();
        //$LoginForm['LoginForm'] = Yii::$app->request->post();
        $post = Yii::$app->request->post();
        $users = User::findIdentityByAccessToken($params['access_token']);
       
        if (isset($params['email_otp'])) {
            if (!empty($users)) {
				
				/*echo $users->email_otp.'/';
				echo $params['email_otp'];
				exit;*/
                
                if((string)$users->email_otp == (string)$params['email_otp']){
					
				User::updateAll(array('email_verify' =>1), '(email_otp = "' . $params['email_otp'] . '")');	
				
                $userDetail = UserProfile::getUserDetail($users->id);
                
                $dataArray = array('userid' => $userDetail['id'], 'status' => $userDetail['status'],'name' => $userDetail['name'], 'email' => $userDetail['email'],'access_token' =>$userDetail['access_token'],  'avatar' => $userDetail['avatar_path'] != '' ? $userDetail['avatar_base_url'] . '/' . $userDetail['avatar_path'] : '');

                $response = MyHelper::responsearray(0, false, Yii::t('frontend', 'Otp Verify Successful'), $dataArray);
			}else{

				$response = MyHelper::responsearray(1, true, Yii::t('frontend', 'Incorrect otp', $params['lang']));
			}
            } else {
				
                $response = MyHelper::responsearray(1, true, Yii::t('frontend', 'Incorrect credential or your account not verify', $params['lang']));
            }
        } else {
            $response = MyHelper::responsearray(1, true, Yii::t('frontend', 'Email, password, token fields are required'));
        }
        \Yii::$app->response->format = Response::FORMAT_JSON;
        return $response;
    }
   
}
