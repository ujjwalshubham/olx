<?php

namespace frontend\modules\user\controllers;

use common\base\MultiModel;
use common\components\AppFileUploads;
use frontend\modules\user\models\AccountForm;
use Intervention\Image\ImageManagerStatic;
use trntv\filekit\actions\DeleteAction;
use trntv\filekit\actions\UploadAction;
use frontend\modules\user\models\ChangePassword;
use common\models\UserProfile;
use common\models\User;
use common\models\PostAd;
use common\models\Packages;
use common\models\UserVerification;
use common\models\UserSubscription;
use common\models\Transactions;
use common\models\Plans;
use common\models\States;
use common\models\Cities;
use common\models\Categories;
use common\models\Settings;
use common\components\Olx;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\helpers\Url;
use yii\db\Query;
use yii\data\Pagination;
$page_size = Settings::getPageSize();

class DefaultController extends Controller
{
    /**
     * @return array
     */
    public function actions()
    {
        return [
            'avatar-upload' => [
                'class' => UploadAction::class,
                'deleteRoute' => 'avatar-delete',
                'on afterSave' => function ($event) {
                    /* @var $file \League\Flysystem\File */
                    $file = $event->file;
                    $img = ImageManagerStatic::make($file->read())->fit(215, 215);
                    $file->put($img->encode());
                }
            ],
            'avatar-delete' => [
                'class' => DeleteAction::class
            ]
        ];
    }

    /**
     * @return array
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@']
                    ]
                ]
            ]
        ];
    }

    /**
     * @return string|\yii\web\Response
     */
    public function actionIndex()
    {
		
		
		
        $accountForm = new AccountForm();
        $accountForm->setUser(Yii::$app->user->identity);

        $model = new MultiModel([
            'models' => [
                'account' => $accountForm,
                'profile' => Yii::$app->user->identity->userProfile
            ]
        ]);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $locale = $model->getModel('profile')->locale;
            Yii::$app->session->setFlash('forceUpdateLocale');
            Yii::$app->session->setFlash('alert', [
                'options' => ['class' => 'alert-success'],
                'body' => Yii::t('frontend', 'Your account has been successfully saved', [], $locale)
            ]);
            return $this->refresh();
        }
        return $this->render('index', ['model' => $model]);
    }
    
    public function actionDashboard() {
		
		$s3 = Yii::$app->get('s3');

		$result = $s3->commands()->get('1578485730_avatar.jpg')->execute();
		//echo "<pre>"; print_r($s3);exit;
		//echo "<pre>"; print_r($result);exit;
		
		
		$title =  Yii::t('frontend', 'Dashboard');
		$user_id=Yii::$app->user->id;
		$user = User::findOne($user_id);
		$userDetail = UserProfile::findOne($user_id);
		
        $getUserDetail = UserProfile::findOne($user_id);
        $getUserDetail->scenario= UserProfile::SCENARIO_UPROFILE;
        $user->scenario= User::SCENARIO_UPROFILE;
        
        if(Yii::$app->request->post()){
            if($getUserDetail->load(Yii::$app->request->post())){
			$getUserDetail->avatar_path = $userDetail->avatar_path;
                if($getUserDetail->save()){
                    Yii::$app->session->setFlash('success', "Profile Update Successfully");
                }
                //~ else{
                    //~ Yii::$app->session->setFlash('fail', "Profile not updated");
                //~ }
            }
            
            if($user->load(Yii::$app->request->post())){
                if($user->save()){
                    Yii::$app->session->setFlash('success', "Profile Update Successfully");
                }
                //~ else{
                    //~ Yii::$app->session->setFlash('fail', "Profile not updated");
                //~ }
            }
        }
        
        if($_FILES){
			$imageUpload=AppFileUploads::updateProfileImage($user_id,$_FILES);
        }
        return $this->render('dashboard', ['user'=>$user,'userDetail' => $getUserDetail,'title'=>$title]);
    }
    
    public function actionAccountSetting() {
		
		$title = Yii::t('frontend', 'Account Setting');
	
        $userDetail = UserProfile::getUserDetail(Yii::$app->user->identity->id);
        $model = new ChangePassword();
        //$this->performAjaxValidation($model);
        $user = User::find()->where(['id' => Yii::$app->user->identity->id])->one();
        $model->setScenario('changePwd');
        if ($model->load(Yii::$app->request->post())) {
            $valid = $model->validate();
            if ($valid) {
                $user->password = $model->password;
                if ($user->save()) {
                    Yii::$app->getSession()->setFlash('success', 'Password changed successful');
                } else {
                    Yii::$app->getSession()->setFlash('danger', 'Password not changed');
                }
                return $this->redirect(Url::to(['/user/account-setting']));
            }
        }
        return $this->render('account-setting', ['model' => $model, 'userDetail' => $userDetail,'title'=>$title]);
    }
    
    
    
     public function actionMyAds() {
		 
		$userId = Yii::$app->User->id;

		$title = Yii::t('frontend', 'My Ads');
		$data = PostAd::MyAds();
		//echo "<pre>"; print_r($ArrAds);exit;
		
		$ArrAds = $data['ArrAds'];
		$pages = $data['pages'];
		
         return $this->render('my_ads', ['title' => $title,'myads'=>$ArrAds,'pages'=>$pages]);
     }
     
     public function actionHideAd() {
		
		  if(Yii::$app->request->isAjax && Yii::$app->request->post()) {
			$post = Yii::$app->request->post();
			$userId = Yii::$app->User->id;
			
			$result=PostAd::setAdStatusHidden($post);
		}
		return json_encode($result);
     }
     
      public function actionUnhideAd() {
		if(Yii::$app->request->isAjax && Yii::$app->request->post()) {
			$post = Yii::$app->request->post();
			$userId = Yii::$app->User->id;
			
			$result=PostAd::setAdStatusUnHidden($post);
		}
		return json_encode($result);
     }
     
     public function actionPendingAds() {
	
		$title = Yii::t('frontend', 'Pending Ads');
		$data = PostAd::PendingAds();
		$ArrAds = $data['ArrAds'];
		$pages = $data['pages'];
		
         return $this->render('pending_ads', ['title' => $title,'pending_ads'=>$ArrAds,'pages'=>$pages]);
     }
     
     public function actionRejectedAds() {
	
		$title = Yii::t('frontend', 'Rejected Ads');
		$data = PostAd::RejectedAds();
		$ArrAds = $data['ArrAds'];
		$pages = $data['pages'];
        return $this->render('rejected_ads', ['title' => $title,'rejected_ads'=>$ArrAds,'pages'=>$pages]);
     }
     
     
     public function actionWarningAds() {
	
		$title = Yii::t('frontend', 'Warning Ads');
		$data = PostAd::WarningAds();
		
		$ArrAds = $data['ArrAds'];
		$pages = $data['pages'];
        return $this->render('warning_ads', ['title' => $title,'warning_ads'=>$ArrAds,'pages'=>$pages]);
     }
     
     
     public function actionHiddenAds() {
	
		$title = Yii::t('frontend', 'Hidden Ads');
		$data = PostAd::HiddenAds();
		
		$ArrAds = $data['ArrAds'];
		$pages = $data['pages'];
		
		
         return $this->render('hidden_ads', ['title' => $title,'pending_ads'=>$ArrAds,'pages'=>$pages]);
     }
     
     public function actionResubmitAds() {
	
		$title = Yii::t('frontend', 'Resubmitted Ads');
		$data = PostAd::ResubmittedAds();
		$ArrAds = $data['ArrAds'];
		$pages = $data['pages'];
        return $this->render('resubmitted_ads', ['title' => $title,'resubmit_ads'=>$ArrAds,'pages'=>$pages]);
     }
     
     
      public function actionFavouriteAds() {
		$title = Yii::t('frontend', 'Favourite Ads');
		$data = PostAd::FavouriteAds();
		
		$ArrAds = $data['ArrAds'];
		$pages = $data['pages'];
		//echo "<pre>";print_r($ArrAds);exit;
         return $this->render('favourite_ads', ['title' => $title,'favourite_ads'=>$ArrAds,'pages'=>$pages]);
     }
     
     
     public function actionActiveAds() {
		$title = Yii::t('frontend', 'Active Ads');
		$data = PostAd::ActiveAds();
		
		$ArrAds = $data['ArrAds'];
		$pages = $data['pages'];
         return $this->render('active_ads', ['title' => $title,'active_ads'=>$ArrAds,'pages'=>$pages]);
     }
     
     
      public function actionMembership() {
		$title = Yii::t('frontend', 'Membership');
		$packages = Packages::AllPackages();
		
		
	   if(Yii::$app->request->post()) {
			$post = Yii::$app->request->post();
			return $this->render('payment', ['post' => $post]);
		}  
		
		$userId = Yii::$app->user->identity['id'];
		$subscription = UserSubscription::find()->where(['user_id'=>$userId])->one();
		
		$title = Yii::t('frontend', 'Packages');
		$plans = Plans::AllPlans();
		
		if(isset($subscription) && !empty($subscription)){
			return $this->render('membership', ['title' => $title,'packages'=> $packages,'user_subscription'=>$subscription]);
		}else{
			return $this->render('plans', ['title' => $title,'plans'=> $plans,'user_subscription'=>$subscription]);
		}
     }
     
      public function actionTransactions() {
		$page_size = Settings::getPageSize();
		$title = Yii::t('frontend', 'Transactions');
		
		$userId = Yii::$app->user->identity['id'];
		$transactions = Transactions::find()->where(['user_id'=>$userId])->orderBy(['id' => SORT_DESC]);;
		
		$countQuery = clone $transactions;
		$pages = new Pagination(['totalCount' => $countQuery->count(), 'pageSize'=>$page_size]);
		$transactions = $transactions->offset($pages->offset)->limit($pages->limit)->all();
		
		return $this->render('transactions', ['title' => $title,'transactions'=>$transactions,'pages'=>$pages]);
     }
          
     public function actionUpgradeMembership() {
		 
		if(Yii::$app->request->post()) {
		$post = Yii::$app->request->post();
		
		$planDetail = Plans::getPlanDetail($post['plan_id']);
		$plan_term = $planDetail['plan_term'];
		
		 if($plan_term=='WEEKLY'){
			 $to_date = strtotime("+7 days");
		 }elseif($plan_term=='MONTHLY'){
			 $to_date = strtotime("+30 days");
		 }elseif($plan_term=='YEARLY'){
			 $to_date = strtotime("+365 days");
		 }
		
		$query = UserSubscription::find()->where(['user_id'=>$post['user_id']])->one();
		if(isset($query) && !empty($query)){
			$query->plan_id = $post['plan_id'];
			$query->from_date = time();
			$query->to_date = $to_date;
			$query->save();
			if($query->save()){
				$transactionModel = new Transactions();
				$transactionModel->user_id = $post['user_id'];
				$transactionModel->plan_id = $post['plan_id'];
				$transactionModel->package_id = $planDetail['package_id'];
				$transactionModel->txn_amount = $planDetail['amount'];
				$transactionModel->payment_type = 'cash';
				$transactionModel->type = 'membership';
				$transactionModel->txn_date = date('Y-m-d',time());
				$transactionModel->status = 'success';
				$transactionModel->save();
			}
		}else {
		//echo "<pre>"; print_r($post);exit;
			$model = new UserSubscription();
			$model->user_id = $post['user_id'];
			$model->plan_id = $post['plan_id'];
			$model->from_date = time();
			$model->to_date = $to_date;
		    $model->save();
		    if($model->save()){
				$transactionModel = new Transactions();
				$transactionModel->user_id = $post['user_id'];
				$transactionModel->plan_id = $post['plan_id'];
				$transactionModel->package_id = $planDetail['package_id'];
				$transactionModel->txn_amount = $planDetail['amount'];
				$transactionModel->payment_type = 'cash';
				$transactionModel->type = 'membership';
				$transactionModel->txn_date = date('Y-m-d',time());
				$transactionModel->status = 'success';
				$transactionModel->save();
			}
		}
		return true;
		} 
     }
          
      public function actionChangeplan() {
		  
		$userId = Yii::$app->User->id; 
		$user_subscription = UserSubscription::find()
						->andWhere(['user_id' => $userId])
						->one();

		if(Yii::$app->request->post()) {
			$post = Yii::$app->request->post();
			return $this->render('payment', ['post' => $post]);
		}  
		  
		$title = Yii::t('frontend', 'Packages');
		$plans = Plans::AllPlans();
        return $this->render('plans', ['title' => $title,'plans'=> $plans,'user_subscription'=>$user_subscription]);
     }
     
      public function actionAdDetail($slug) {
		  
	    $title = 'Ad Detail';  
		$adDetail = (new \yii\db\Query())
			->select(['*'])
			->from('post_ads')
			->where(['slug' => $slug])
			->one();
			
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
		$ArrAds[$i] = $adDetail;
			
        return $this->render('addetail', ['title' => $title,'adDetail'=>$ArrAds[1]]);
     }
     
     
      public function actionProfile() {
		  
		 $user_id=Yii::$app->user->id;
		 $page_size = Settings::getPageSize();
		 $states = States::getStates();
		 $params = Yii::$app->request->get();
		 $category = array('title'=>'All Categories','id'=>'All');
		 
		 if(isset($params) && !empty($params)){
			 
		 if(isset($params['cat']) &&!empty(trim($params['cat']))){
		$category = Categories::findOne(['id' => $params['cat']]);
		}
		else if(isset($params['subcat']) && !empty(trim($params['subcat']))){
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
			->where(['=', 'post_ads.user_id', $user_id]);	
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
			  $data = PostAd::MyAds();
			  $ArrAds = $data['ArrAds'];
			  $pages = $data['pages'];
		 } 
		 $title = 'My Profile';
         return $this->render('myprofile', ['title' => $title,'myads'=>$ArrAds,'states'=>$states,'category'=>$category,'params'=>$params,'pages'=>$pages]);
     }
     
      public function actionUserProfile($slug) {
		 $userProfile = UserProfile::find()
						->andWhere(['slug' => $slug])
						->one();
		 $userId = $userProfile->user_id;
		
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
			->leftjoin('countries', 'post_ads.country = countries.id');
			 
			
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
			$allAds = $allAds->all();
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
		
         return $this->render('userprofile', ['title' => $title,'myads'=>$ArrAds,'states'=>$states,'category'=>$category,'params'=>$params,'UserProfile'=>$userProfile,'userId'=>$userId]);
     }
     
      public function actionPublicProfile() {
		 $title = 'Public Profile';
         return $this->render('public_profile', ['title' => $title]);
     }
     
     public function actionCountryChange($id) {
		$states = States::getStateByCountryId($id);
        echo json_encode($states);
        exit;
     }
     
     
     public function actionStateChange($id) {
		$cities = Cities::getCityByStateId($id);
        echo json_encode($cities);
        exit;
     }
     
     public function actionProfileFieldEdit(){
        if(Yii::$app->request->isAjax && Yii::$app->request->post()){
            $post=Yii::$app->request->post();
            if(isset($post['user_id']) && !empty($post['user_id'])){
                $user=User::findOne($post['user_id']);
                $userProfile=UserProfile::findOne(['user_id'=>$post['user_id']]);
                echo $this->renderAjax('_edit_input_popup',['type'=>$post['type'],'user'=>$user]); exit();
            }
        }
    }
    
    public function actionReplyByEmail(){
        if(Yii::$app->request->post()){
            $post=Yii::$app->request->post();
      
            if(isset($post['user_id']) && !empty($post['user_id'])){
                $user=User::findOne($post['user_id']);
                $userProfile=UserProfile::findOne(['user_id'=>$post['user_id']]);
                 $mail=User::sendReplyEmail($post,$user,$userProfile);
            }
        }
    }
    
    public function actionSendOtp(){
        $post=Yii::$app->request->post();
        if(isset($post['UserVerification'])){
            $user_id=$post['UserVerification']['user_id'];
            $model=UserVerification::find()->where(['user_id'=>$user_id])->one();
            if($model->otp == $post['UserVerification']['otp']){
                $user=User::findOne($user_id);
                if($post['type'] == 'email'){
                    $user->email=$model->email;
                    $user->email_verify=1;
                    if($user->save()){
                        $userProfile=UserProfile::find()->where(['user_id'=>$user_id])->one();
                        //$userProfile->email=$model->email;
                        $userProfile->save();
                        Yii::$app->getSession()->setFlash('success',"Email updated.");
                        return $this->redirect('dashboard');
                    }
                }
            }
            else{
                Yii::$app->getSession()->setFlash('error',"Invalid OTP Please Try Again.");
                return $this->redirect('dashboard');
            }
        }
        else{
            $type=$post['type'];
            $user_id=$post['id'];
            $user=User::findOne($post['id']);
            if($type == 'email'){
                $checkexist= User::find()->andWhere(['email'=>$post['identity']])->andWhere(['!=','id',$user_id])->one();
                if($checkexist){
                    $data=['identity'=>'Email already register'];
                    $result=['status'=>true,'error'=>true,'data'=>$data];
                }
                else{
                    if($user->email == $post['identity']){
                        $data=['identity'=>'You have entered same email as old'];
                        $result=['status'=>true,'error'=>true,'data'=>$data];
                    }
                    else{
                        //update email & send otp
                        $model=UserVerification::find()->where(['user_id'=>$user_id])->one();
                        if(empty($model)){
                            $model= new UserVerification();
                        }
                        $model->user_id=$user_id;
                        $model->email=$post['identity'];
                        $model->otp=1234;
                        if($model->save()){
							 $model->otp='';
							 echo $this->renderAjax('_verify_otp_popup',['type'=>$post['type'],'user'=>$user,'model'=>$model]); exit();
						}
						else{
							$data=['identity'=>'E-mail is not a valid email address.'];
							$result=['status'=>true,'error'=>true,'data'=>$data];
						}
                    }
                }
            }
            return json_encode($result);
        }
    }
     
    public function beforeAction($action) 
	{ 
		$this->enableCsrfValidation = false; 
		return parent::beforeAction($action); 
	}
}
