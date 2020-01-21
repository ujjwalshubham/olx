<?php

namespace frontend\controllers;

use cheatsheet\Time;
use common\sitemap\UrlsIterator;
use common\components\Olx;
use frontend\models\ContactForm;
use frontend\models\FeedbackForm;
use frontend\models\ReportAdForm;
use frontend\modules\user\models\SignupForm;
use frontend\modules\user\models\LoginForm;
use common\models\Wishlist;
use common\models\UserProfile;
use common\models\User;
use yii\widgets\ActiveForm;
use common\models\States;
use common\models\PostAd;
use common\models\Faqs;
use common\models\Review;
use common\models\Settings;
use common\models\AdViews;
use common\models\UserVisits;
use Sitemaped\Element\Urlset\Urlset;
use Sitemaped\Sitemap;
use Yii;
use yii\filters\PageCache;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\web\Response;
use yii\web\NotFoundHttpException;
use yii\data\Pagination;
/**
 * Site controller
 */
class SiteController extends Controller
{
    /**
     * @return array
     */
    public function behaviors()
    {
        return [
            [
                'class' => PageCache::class,
                'only' => ['sitemap'],
                'duration' => Time::SECONDS_IN_AN_HOUR,
            ]
        ];
    }

    /**
     * @inheritdoc
     */
     
    public function beforeAction($action) {
    $this->enableCsrfValidation = false;
    return parent::beforeAction($action);
}
     
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction'
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null
            ],
            'set-locale' => [
                'class' => 'common\actions\SetLocaleAction',
                'locales' => array_keys(Yii::$app->params['availableLocales'])
            ]
        ];
    }

    /**
     * @return string
     */
    public function actionIndex()
    {
		$states = States::getStates();
		$userId = Yii::$app->user->identity['id'];
	
		$allAds = (new \yii\db\Query())
				->select(['*'])
				->from('post_ads')
				->where(['status'=>"Active"])
				->limit(9)
				->orderBy(['id' => SORT_DESC])
				->all();
		$allAdsCount = 	(new \yii\db\Query())
					->select(['*'])
					->from('post_ads')
					->where(['status'=>"Active"])
					->orderBy(['id' => SORT_DESC])
					->count();	
		
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
					->select(['*'])
					->from('ads_images')
					->where(['ad_id' => $value['id']])
					->all();
			
			$value['ads_images'] =  $images;
			$ArrAds[$i] = $value;
			$i++;
		}
		//echo "<pre>"; print_r($ArrAds);exit;
        return $this->render('index', ['myads'=>$ArrAds,'states'=>$states,'adsCount'=>$allAdsCount]);
    }

    /**
     * @return string|Response
     */
   /* public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post())) {
            if ($model->contact(Yii::$app->params['adminEmail'])) {
                Yii::$app->getSession()->setFlash('alert', [
                    'body' => Yii::t('frontend', 'Thank you for contacting us. We will respond to you as soon as possible.'),
                    'options' => ['class' => 'alert-success']
                ]);
                return $this->refresh();
            }

            Yii::$app->getSession()->setFlash('alert', [
                'body' => \Yii::t('frontend', 'There was an error sending email.'),
                'options' => ['class' => 'alert-danger']
            ]);
        }

        return $this->render('contact', [
            'model' => $model
        ]);
    }*/
    
    
    public function actionContact()
    {
        $model = new ContactForm();
        $postAttr = Yii::$app->request->post();
      
        if ($model->load(Yii::$app->request->post())) {
			// echo "<pre>"; print_r(Yii::$app->params['adminEmail']);exit; 
			
            if ($model->contact(Yii::$app->params['adminEmail'],$postAttr)) {
                Yii::$app->getSession()->setFlash('alert', [
                    'body'=>Yii::t('db', 'Thank you for contacting us. We will respond to you as soon as possible.'),
                    'options'=>['class'=>'alert-success']
                ]);
                return $this->refresh();
            } else {
                Yii::$app->getSession()->setFlash('alert', [
                    'body'=>\Yii::t('db', 'There was an error sending email.'),
                    'options'=>['class'=>'alert-danger']
                ]);
            }
        }
        return $this->render('contact', ['model' => $model ]);
    }

    
    public function actionAddWishlist()
    {		
        $model = new Wishlist();
        $userId = Yii::$app->user->id;
        $ad_id = Yii::$app->request->post()['ad_id'];
        if(Yii::$app->request->post()){
			$model['user_id'] = $userId;
			$model['ad_id'] = $ad_id;
			if($model->save()){
				$message = 'Successfully added to wishlist';
				echo json_encode($message);die;
			}
        }
    }
    
    public function actionRemoveWishlist()
    {	
        $model = new Wishlist();
        $userId = Yii::$app->user->id;
        $ad_id = Yii::$app->request->post()['ad_id'];
        
        if(Yii::$app->request->post()){
			  $wishlist = Wishlist::find()
			  ->where(['ad_id'=>$ad_id])
			  ->andwhere(['user_id'=>$userId])
			  ->one()
			  ->delete();
			   if($wishlist){
                    $message = 'Successfully removed to wishlist';
                    echo json_encode($message);die;
                }
        }
    }
    /**
     * @param string $format
     * @param bool $gzip
     * @return string
     * @throws BadRequestHttpException
     */
   /* public function actionSitemap($format = Sitemap::FORMAT_XML, $gzip = false)
    {
        $links = new UrlsIterator();
        $sitemap = new Sitemap(new Urlset($links));

        Yii::$app->response->format = Response::FORMAT_RAW;

        if ($gzip === true) {
            Yii::$app->response->headers->add('Content-Encoding', 'gzip');
        }

        if ($format === Sitemap::FORMAT_XML) {
            Yii::$app->response->headers->add('Content-Type', 'application/xml');
            $content = $sitemap->toXmlString($gzip);
        }
        else if ($format === Sitemap::FORMAT_TXT) {
            Yii::$app->response->headers->add('Content-Type', 'text/plain');
            $content = $sitemap->toTxtString($gzip);
        }
        else {
            throw new BadRequestHttpException('Unknown format');
        }

        $linksCount = $sitemap->getCount();
        if ($linksCount > 50000) {
            Yii::warning(\sprintf('Sitemap links count is %d'), $linksCount);
        }

        return $content;
    }*/
    
     public function actionAdDetail($slug) {
		 
		$userId = Yii::$app->user->identity['id'];
		$allAds = (new \yii\db\Query())
			->select(['*'])
			->from('post_ads')
			->where(['status'=> PostAd::STATUS_ACTIVE])
			->andWhere(['ad_type'=> PostAd::STATUS_ACTIVE])
			->where(['user_id' => $userId])
			->andWhere(['ad_type' => array('featured','highlight','urgent')])
			->andWhere(['status' => PostAd::STATUS_ACTIVE])
			->orderBy(['id' => SORT_DESC])
			->all();
		
	    $title = 'Ad Detail'; 
	    if($userId){
			$adDetail = (new \yii\db\Query())
			->select(['*'])
			->from('post_ads')
			->where(['slug' => $slug])
			->one();
		} else{
			$adDetail = (new \yii\db\Query())
			->select(['*'])
			->from('post_ads')
			->where(['slug' => $slug])
			->andWhere(['status' => PostAd::STATUS_ACTIVE])
			->one();
		}
		
			
		if(isset($adDetail) && !empty($adDetail)){
			
		$ad_id = 	$adDetail['id'];
		$ip_address = $_SERVER['REMOTE_ADDR']; 
		
		$check_ip = (new \yii\db\Query())
					->select(['*'])
					->from('ad_views')
					->where(['ad_id' => $ad_id])
					->andWhere(['ip_address' => $ip_address])
					->one();
					
		if(empty($check_ip)){
		$model = new AdViews();
		if($ad_id  && $ip_address){
			$model->ad_id = $ad_id;
			$model->ip_address =  $ip_address;
			$model->save();
		}}
		
		$ad_views_count = AdViews::AdViewsCountByAdId($adDetail['id']);
			
		$avgrating = Review::ReviewCountByAdId($adDetail['id']);
		$review_count = Review::checkAdReviewCount($adDetail['id']);
		$reviews =  Review::getAdAllReviews($adDetail['id']);
		
		$i = 1;	
		$ArrAds = array();
		$cat_id = (new \yii\db\Query())
					->select(['*'])
					->from('ads_category')
					->where(['ad_id' => $adDetail['id']])
					->all();
		$adDetail['category_id'] =  $cat_id;
		
		$images = (new \yii\db\Query())
					->select(['*'])
					->from('ads_images')
					->where(['ad_id' => $adDetail['id']])
					->all();
		$adDetail['ads_images'] =  $images;
		
		$custom_field_ids = (new \yii\db\Query())
					->select(['id','ad_id','field_id','value'])
					->from('post_ads_custom_fields	')
					->where(['ad_id' => $adDetail['id']])
					->all();
		 // echo "<pre>"; print_r($custom_field_ids);exit;
		 
		 $b = 1;	
		 $ArrCustomFields = array();	 
		  foreach($custom_field_ids as $key=>$custom_field_id){
		  $custom_fields = (new \yii\db\Query())
						->select(['custom_fields.id','custom_fields.field_type_id','custom_fields.label','custom_fields.isRequired','custom_field_types.id as custom_type_id','custom_field_types.type'])
						->from('custom_fields')
						->leftjoin('custom_field_types', 'custom_fields.field_type_id = custom_field_types.id')
						->where(['custom_fields.id' => $custom_field_id['field_id']])
						->all();
		  $custom_field_id['custom_fields']	 = $custom_fields[0];
		  $ArrCustomFields[$b] = $custom_field_id;
		  $b++;
		  }
		  
		  //echo "<pre>"; print_r($ArrCustomFields);exit;
		  
		  if(isset($ArrCustomFields)){
			  $ArrCustomFields = $ArrCustomFields;
		  }
		  else{
			  $ArrCustomFields = array();
		  }
		  
		$ArrAds[$i] = $adDetail;
		//echo "<pre>"; print_r($ArrAds);exit;
		$cat_id = $adDetail['category_id'][0]['cat_id'];

		$related_ads_ids = (new \yii\db\Query())
							->select(['ad_id'])
							->from('ads_category')
							->where(['cat_id' => $cat_id])
							->all();
	
		if(isset($related_ads_ids) && !empty($related_ads_ids)){
			
			$ad_list_arrays = array();
			$a = 1;
			foreach($related_ads_ids as $key=>$value){
				$ad_list = (new \yii\db\Query())
							->select(['*'])
							->from('post_ads')
							->where(['status'=> PostAd::STATUS_ACTIVE])
							->andWhere(['id'=> $value['ad_id']])
							->andWhere(['!=', 'id', $adDetail['id']])
							->orderBy(['id' => SORT_DESC])
							->one();
							
				$ad_list_arrays[$a] = $ad_list;
				$a++;		
			}
			$ad_list_arrays = array_filter($ad_list_arrays);
			$j = 0;
			$RelatedAds = array();
			
			foreach($ad_list_arrays as $key=>$value){
				
		
				$cat_id = (new \yii\db\Query())
						->select(['*'])
						->from('ads_category')
						->where(['ad_id' => $value['id']])
						->one();
				$value['category_id'] =  $cat_id;
				$RelatedAds[$j] = $value;
				
				$images = (new \yii\db\Query())
						->select(['*'])
						->from('ads_images')
						->where(['ad_id' => $value['id']])
						->one();
				
				$value['ads_images'] =  $images;
				$RelatedAds[$j] = $value;
				$j++;
			}
		}
        return $this->render('addetail', ['title' => $title,'adDetail'=>$ArrAds[1],'avg_rating'=>$avgrating,'review_count'=>$review_count,'reviews'=>$reviews,'ad_views_count'=>$ad_views_count,'adCustomFields'=>$ArrCustomFields,'relatedAds'=>$RelatedAds]);
		}else{
			 throw new NotFoundHttpException('The requested page does not exist.');
		}
     }
    
    
    public function actionFaq() {
		$faqs = Olx::getAllFaqs();
		$title = 'FAQ';
		return $this->render('faq', ['title' => $title,'faqs' => $faqs]);
	}
	 
	public function actionFeedback()
    {
        $model = new FeedbackForm();
        $postAttr = Yii::$app->request->post();
      
        if ($model->load(Yii::$app->request->post())) {
			// echo "<pre>"; print_r(Yii::$app->params['adminEmail']);exit; 
			
            if ($model->feedback($postAttr)) {
                Yii::$app->getSession()->setFlash('alert', [
                    'body'=>Yii::t('frontend', 'Thank you for your valuable feedback'),
                    'options'=>['class'=>'alert-success']
                ]);
                return $this->refresh();
            } else {
				
                Yii::$app->getSession()->setFlash('alert', [
                    'body'=>\Yii::t('frontend', 'There was an error sending email.'),
                    'options'=>['class'=>'alert-danger']
                ]);
            }
        }
        return $this->render('feedback', [
            'model' => $model
        ]);
    }
    
    
    public function actionReport()
    {
        $model = new ReportAdForm();
        $postAttr = Yii::$app->request->post();
        
  
      
        if ($model->load(Yii::$app->request->post())) {
			// echo "<pre>"; print_r(Yii::$app->params['adminEmail']);exit; 
			
            if ($model->feedback($postAttr)) {
                Yii::$app->getSession()->setFlash('alert', [
                    'body'=>Yii::t('frontend', 'Thank you for your Reporting'),
                    'options'=>['class'=>'alert-success']
                ]);
                return $this->refresh();
            } else {
                Yii::$app->getSession()->setFlash('alert', [
                    'body'=>\Yii::t('frontend', 'There was an error sending email.'),
                    'options'=>['class'=>'alert-danger']
                ]);
            }
        }
        return $this->render('reportad', [
            'model' => $model
        ]);
    }
    
    
    public function actionSitemap()
    {
		$title = 'Site-Map';
        return $this->render('sitemap', ['title' => $title]);
    }
    
    public function actionAjaxSignup(){
		
        $result=['status'=>true,'error'=>true];
        $model = new SignupForm();
        if (Yii::$app->request->isAjax && Yii::$app->request->post()) {
		
            $signup= Yii::$app->request->post();
            $mobile_no = $signup['SignupForm']['mobile'];
            
            $model->load($signup);
            $signup=$signup['SignupForm'];
            
            $user=$model->signup(Yii::$app->request->post());
          
            if(!empty($user)){
				
			}else {
			$user = User::getUserByMobile($mobile_no);	
			}
           
            if (!$model->getErrors()) {
                //$message = $user->otp.' is the OTP for accessing your account. PLS DO NOT SHARE IT WITH ANYONE.';
                //$sendSms=Notifications::send_sms($message,$user->phone,'No',$user->countrycode,1);
                $data=$this->renderAjax('otp-verify',['user'=>$user]);  
                $result=['status'=>true,'error'=>false,'data'=>$data];
            }
            else{
                $getErrors=$model->getErrors();
                echo "<pre>"; print_r($getErrors);die;
            }
        }
       return json_encode($result);
    }
    
    public function actionAjaxUnique(){
    $result=['phone'=>false];
    if(Yii::$app->request->isAjax && Yii::$app->request->post()) {
        $post=Yii::$app->request->post('SignupForm');
        $result=User::ajaxUnique($post);
    }
    return json_encode($result);
    }
    
    
    public function actionEmailUnique(){
    if(Yii::$app->request->isAjax && Yii::$app->request->post()) {
        $post=Yii::$app->request->post();
        $result=User::ajaxEmailUnique($post);
    }
    return json_encode($result);
    }
    
    
    public function actionEmailOtpVerify(){
    if(Yii::$app->request->isAjax && Yii::$app->request->post()) {
        $post = Yii::$app->request->post();
        $result=User::setEmailOtp($post);
    }
    return json_encode($result);
    }
    
    
    public function actionCheckEmailOtp(){
		
    if(Yii::$app->request->isAjax && Yii::$app->request->post()) {
        $post = Yii::$app->request->post();
        $result=User::checkEmailOtp($post);
    }
    return json_encode($result);
    }
    
	public function actionOtpVerify(){
		
	$model = new LoginForm();
	$model->scenario = 'otp';
	 if (Yii::$app->request->isAjax) {
			$model->load($_POST);
			Yii::$app->response->format = Response::FORMAT_JSON;
			return ActiveForm::validate($model);
	}
	if (Yii::$app->request->post()){
		$model->load(Yii::$app->request->post());
		if($model->getUser()){
			if($model->login()){
				$userId = Yii::$app->User->id;
				if(!empty($userId)){
					$userDetail = User::getUserDetails($userId);
					if(!empty($userDetail['name']) && (!empty($userDetail['email']))){
						return $this->redirect(array('/'));
					}else{
					 return $this->redirect(array('/user/dashboard'));	
					}
				}
			}else{
				Yii::$app->getSession()->setFlash('error','Invalid OTP Please Try Again.');
			}
		}else{
			Yii::$app->getSession()->setFlash('error','Invalid OTP Please Try Again.');
		}
	}
	return $this->redirect(array('/'));
	}
    
    
    
    public function actionOtpVerification()
    {
		$model = new SignupForm();
		$title = 'Otp Verification';
        return $this->render('otp', ['title' => $title,'model' => $model]);
    }
    
    
    public function actionUserProfile($slug) {
		
		$page_size = Settings::getPageSize();
		
		$adUserDetail = UserProfile::findOne(['slug' => $slug]);
		$adUserId = $adUserDetail->user_id;
		
		$userId = Yii::$app->user->identity['id'];
		if(Yii::$app->user->isGuest){
			$userId = 0;
		}
		
		$ip_address = $_SERVER['REMOTE_ADDR'];
		$check_ip = (new \yii\db\Query())
					->select(['*'])
					->from('user_visits')
					->where(['ip_address' => $ip_address])
					->andWhere(['from_id' => $userId])
					->andWhere(['to_id' => $adUserId])
					->one();
					
		if(empty($check_ip)){
			if($adUserId != $userId){	
				$model = new UserVisits();
					$model->to_id = $adUserId;
					$model->from_id = $userId;
					$model->ip_address =  $ip_address;
					$model->save();
			}
		}
		
		 $userProfile = UserProfile::find()
					    ->andWhere(['slug' => $slug])
						->one();
		 
		 $userId = $userProfile->user_id;
		 $user = User::find()
				->andWhere(['id' => $userId])
				->one();
		
		 $states = States::getStates();
		 $params = Yii::$app->request->get();
		 
		 $category = array('title'=>'All Categories','id'=>'All');
		 
		if(isset($params) && !empty($params)){
		if(isset($params['cat']) && !empty($params['cat'])){
		$category = Categories::findOne(['id' => $params['cat']]);
		}
		else if(isset($params['subcat']) && !empty($params['subcat'])){
			$category = Categories::findOne(['id' => $params['subcat']]);;
		}else{
			$category = array('title'=>'All Categories','id'=>'All');
		}
		
		if(!empty($params['subcat'])){
			$sub_category = Categories::findOne(['id' => $params['subcat']]);
		}
					 
		    $allAds = (new \yii\db\Query())
				->select(['post_ads.*','cities.name as city_name','states.name as state_name','countries.name as country_name'])
				->from('post_ads')
				->innerjoin('ads_category', 'post_ads.id = ads_category.ad_id')
				->leftjoin('cities', 'post_ads.city = cities.id')
				->leftjoin('states', 'post_ads.state = states.id')
				->leftjoin('countries', 'post_ads.country = countries.id')
				->where(['=', 'post_ads.user_id', $adUserId]);	
			 
			if(!empty($params['placetype']) && $params['placetype'] == 'state'){				
					$allAds = $allAds->where(['=', 'post_ads.state', $params['placeid']]);			
			}
			if(!empty($params['placetype']) && $params['placetype'] == 'city'){				
					 $allAds = $allAds->where(['=', 'post_ads.city', $params['placeid']]);			
			}
			if(!empty($params['placetype']) && $params['placetype'] == 'country'){
				if($params['placeid'] == 'in'){
					$country_id = 113;
				}else{
					$country_id = $params['placeid'];
				}				
					$allAds = $allAds->where(['=', 'post_ads.country', $country_id]);			
			}
			
			if(!empty($params['keywords']) && empty($params['cat'])){
							
				$allAds = $allAds->andFilterWhere(['like', 'post_ads.title', $params['keywords']]);				
			}
			
			if(!empty($params['keywords']) && !empty($params['cat'])){
							
				$allAds = $allAds->andFilterWhere(['like', 'post_ads.title', $params['keywords']]);
				$allAds = $allAds->andFilterWhere(['=', 'ads_category.cat_id', $params['cat']]);					
			}
			
			if(!empty($params['cat'])){				
					//$allAds = $allAds->innerjoin('ads_category', 'post_ads.id = ads_category.ad_id')
					$allAds = $allAds->andFilterWhere(['=', 'ads_category.cat_id', $params['cat']]);			
			}
			
			if(!empty($params['subcat'])){				
					//$allAds = $allAds->innerjoin('ads_category', 'post_ads.id = ads_category.ad_id')
					$allAds = $allAds->andFilterWhere(['=', 'ads_category.cat_id', $params['subcat']]);			
			}
			
			if(!empty($params['username'])){				
					//$allAds = $allAds->innerjoin('ads_category', 'post_ads.id = ads_category.ad_id')
					$allAds = $allAds->andFilterWhere(['=', 'post_ads.user_id', $params['username']]);			
			}
			
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
			$allAds = $allAds->where(['post_ads.user_id' => $userId]);
			$allAds = $allAds->groupBy(['post_ads.id']);
			$countQuery = clone $allAds;
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
		
		 }else{
			$ArrAds = PostAd::MyAds();
		 } 
		 
		 $title = 'My Profile';
		
         return $this->render('userprofile', ['title' => $title,'myads'=>$ArrAds,'states'=>$states,'category'=>$category,'params'=>$params,'UserProfile'=>$userProfile,'userId'=>$userId,'user'=>$user,'adUserId'=>$adUserId,'pages'=>$pages]);
     } 
}
