<?php
namespace frontend\modules\user\controllers;

use common\base\MultiModel;
use frontend\modules\user\models\AccountForm;
use common\components\AppHelper;
use common\components\Olx;
use Intervention\Image\ImageManagerStatic;
use trntv\filekit\actions\DeleteAction;
use trntv\filekit\actions\UploadAction;
use common\models\UserProfile;
use common\models\Categories;
use common\models\Cities;
use common\models\Countries;
use common\models\Packages;
use common\models\Plans;
use common\models\PostAd;
use common\models\PostAdCategory;
use common\models\Transactions;
use common\models\UserSubscription;
use common\models\PostAdCustomFields;
use common\models\PostAdImages;
use common\models\SettingsCategory;
use common\models\MediaUpload;
use common\models\Review;
use common\models\States;
use common\components\AppFileUploads;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\db\Query;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;

class PostadController extends Controller
{
	const STATUS_NOT_ACTIVE = 0;
    const STATUS_ACTIVE = 1;
    const STATUS_PENDING='Pending';
	
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
		$setting_slug = 'ad_post';
		$ad_setting = SettingsCategory::findOne(['slug' => $setting_slug]);
		$ad_setting_fields = (new \yii\db\Query())
				->select(['*'])
				->from('settings')
				->where(['category' => $ad_setting['id']])
				->all();
				
		foreach($ad_setting_fields as $key=>$field){
			if($field['name']=='max_image_upload'){
				$max_image_upload = $field['value'];
			}
		}
				
		$categories = Olx::getAllCategory();
		$countries = Countries::getAllCountries();
		$cities = array();
		$states = array();
		
		$model = new PostAd();
		$post_cat_model = new PostAdCategory();
		$post_custom_fields_model = new PostAdCustomFields();
		$ad_images_model = new PostAdImages();
     
        $userId = Yii::$app->user->identity['id'];
        
        if(Yii::$app->request->post()){			
			//echo "<pre>"; print_r(Yii::$app->request->post());exit;
			$ad_type = Yii::$app->request->post('ad_type');
			
			$shouldBeActivated = $this->shouldBeActivated();
            if($model->load(Yii::$app->request->post())){
				 $model->status = PostAd::STATUS_PENDING;
				 $model->latitude = 26.8638;
                 $model->longitude = 75.7793;
				 if(isset($ad_type) && !empty($ad_type)){
					 $model->ad_type = $ad_type; 
				 }else{
					 $model->ad_type = 'free'; 
				 }
				 $model->country = 113;
				 $model->user_id =  $userId;
				 //echo "<pre>"; print_r($model);exit;
                if($model->save()){
				$id = Yii::$app->db->getLastInsertID();
				$post_data = Yii::$app->request->post();
				$categories = explode(',',$post_data['PostAdCategory']['cat_id'][0]);
				//echo "<pre>"; print_r($categories);exit;

					foreach($categories as $key=>$category){
						$post_cat_model = new PostAdCategory();
						$post_cat_model->cat_id =  $category;
						$post_cat_model->ad_id = $id;
						$post_cat_model->save();
					}
					
					if(isset($post_data['PostAd']['custom_field'])){
						$custom_fields = 	$post_data['PostAd']['custom_field'];
						foreach($custom_fields as $key=>$custom_field){
							if(isset($custom_field) && !empty($custom_field)){
								$post_ad_fields_model = new PostAdCustomFields();

								if(is_array($custom_field)){
									$custom_field_keys = 	array_values($custom_field);
									$keys = 	implode(",",$custom_field_keys);
									$post_ad_fields_model->value = $keys;
								}	
								else{
									$post_ad_fields_model->value = $custom_field;
								}			
									
								$post_ad_fields_model->ad_id =  $id;					
								$post_ad_fields_model->field_id = $key;
								
								if($post_ad_fields_model->save()){
									
								}
							}
						}
					}
					
					if($_FILES){
						$imageUpload = MediaUpload::uploadAdImage($id,$_FILES);
					}
					
					$ad_detail = PostAd::findOne($id);
                    Yii::$app->session->setFlash('success', "Ad Saved Successfully");
                    
                    if(isset($ad_type) && !empty($ad_type)){
						return $this->redirect('payment/'.$ad_detail->slug.'/'.$ad_detail->id);
					}else{
						return $this->redirect('ad-detail/'.$ad_detail->slug);
					}
                }
            }
        }
        
        return $this->render('index', ['model' => $model,'ad_images_model' => $ad_images_model,'post_cat_model'=>$post_cat_model,'categories'=>$categories,'cities'=>$cities,'countries'=>$countries,'states'=>$states,'max_image_upload'=>$max_image_upload]);
    }
        
    public function actionEditAd($id)
    {
		$baseurl = Url::base();
		$userId = Yii::$app->user->identity['id'];
		//  Post Ad Setting //
		$setting_slug = 'ad_post';
		$ad_setting = SettingsCategory::findOne(['slug' => $setting_slug]);
					
		$ad_setting_fields = (new \yii\db\Query())
				->select(['*'])
				->from('settings')
				->where(['category' => $ad_setting['id']])
				->all();
				
		foreach($ad_setting_fields as $key=>$field){
			if($field['name']=='max_image_upload'){
				$max_image_upload = $field['value'];
			}
		}
		//  Post Ad Setting //
		
		$adDetail = (new \yii\db\Query())
					->select(['*'])
					->from('post_ads')
					->where(['id' => $id])
					->andWhere(['user_id' => $userId])
					->one();
		$i = 1;	
		$ArrAds = array();
		$cat_id = (new \yii\db\Query())
					->select(['ads_category.id','ads_category.ad_id','ads_category.cat_id','categories.title','categories.slug'])
					->from('ads_category')
					->leftjoin('categories', 'ads_category.cat_id = categories.id')
					->where(['ads_category.ad_id' => $adDetail['id']])
					->all();
		
		$adDetail['category_id'] =  $cat_id;
		
		$images = (new \yii\db\Query())
					->select(['id','ad_id','media_id'])
					->from('ads_images')
					->where(['ad_id' => $adDetail['id']])
					->all();
		$adDetail['ads_images'] =  $images;
	
		$custom_fields = (new \yii\db\Query())
						->select(['id','ad_id','field_id','value'])
						->from('post_ads_custom_fields')
						->where(['ad_id' => $adDetail['id']])
						->all();
		$adDetail['custom_fields_data'] =  $custom_fields;
		
		//  Default Custom Fields By Id//
		$query = new Query();
				 $query->select(['id','field_id','category_id'])->from('categories_custom_fields')
			    ->where(['category_id' => $cat_id[1]['cat_id']]);
		$command = $query->createCommand();
		$custom_field_ids =  $command->queryAll();
	
		
		$a = 1;	
		$ArrCFields = array();	
        foreach($custom_field_ids as $key=>$custom_field_id){

		$custom_fields = (new \yii\db\Query())
						->select(['id','field_type_id','label','isRequired','status'])
						->from('custom_fields')
						->where(['id' => $custom_field_id['field_id']])
						->all();
		
		$custom_field_options = (new \yii\db\Query())
						->select(['id','field_id','label'])
						->from('custom_field_options')
						->where(['field_id' => $custom_field_id['field_id']])
						->all();
		
		$custom_field_id['custom_fields']	 = $custom_fields[0];
		
		if(!empty($custom_field_options)){
			$custom_field_id['custom_fields_options']	 = $custom_field_options;
		}
		$ArrCFields[$a] = $custom_field_id;
		$a++;
		}
		
		$b = 1;	
		$ArrCFields2 = array();	
		foreach($ArrCFields as $key=>$value){
		$custom_fields_type = (new \yii\db\Query())
							->select(['id','type','data_type','label','options_enabled','options_label','status'])
							->from('custom_field_types')
							->where(['id' => $value['custom_fields']['field_type_id']])
							->all();
	
			$value['custom_fields_type']	 = $custom_fields_type[0];
			$ArrCFields2[$b] = $value;
			$b++;
		}
		//  Default Custom Fields By Id//
		$ArrAds = $adDetail;	
		$categories = Olx::getAllCategory();
		$countries = Countries::getAllCountries();
		$states = ArrayHelper::map(States::find()->where(array('country_id' => $adDetail['country']))->all(), 'id', 'name');
		$cities = ArrayHelper::map(Cities::find()->where(array('state_id' => $adDetail['state']))->all(), 'id', 'name');
		
		//$model = new PostAd();
		$model = PostAd::findOne($id);
		
		$post_cat_model = new PostAdCategory();
		$post_custom_fields_model = new PostAdCustomFields();
		$ad_images_model = new PostAdImages();
     
        $userId = Yii::$app->user->identity['id'];
        
        if(Yii::$app->request->post()){
			//echo "<pre>"; print_r(Yii::$app->request->post());exit;
			$custom_field_data = PostAdCustomFields::deleteAll(['ad_id' => $id]);
		
			$ad_type = Yii::$app->request->post('ad_type');
			
			$shouldBeActivated = $this->shouldBeActivated();
            if($model->load(Yii::$app->request->post())){
				 $model->status = PostAd::STATUS_RESUBMITTED;
				 if(isset($ad_type) && !empty($ad_type)){
					 $model->ad_type = $ad_type; 
				 }else{
					 $model->ad_type = $adDetail['ad_type']; 
				 }
				 $model->country = 113;
				 $model->user_id =  $userId;
				 //echo "<pre>"; print_r($model);exit;
                if($model->save()){
				$post_data = Yii::$app->request->post();
					if(isset($post_data['PostAd']['custom_field'])){
					$custom_fields = 	$post_data['PostAd']['custom_field'];
						foreach($custom_fields as $key=>$custom_field){
							$post_ad_fields_model = new PostAdCustomFields();

							if(is_array($custom_field)){
								$custom_field_keys = 	array_values($custom_field);
								$keys = 	implode(",",$custom_field_keys);
								
								$post_ad_fields_model->value = $keys;
							}	
							else{
								$post_ad_fields_model->value = $custom_field;
							}			
								
							$post_ad_fields_model->ad_id =  $id;					
							$post_ad_fields_model->field_id = $key;
							
							if($post_ad_fields_model->save()){
								
							}
						}
					}
					
					if($_FILES){
						$imageUpload = MediaUpload::uploadAdImage($id,$_FILES);
					}
					
					$ad_detail = PostAd::findOne($id);
                    Yii::$app->session->setFlash('success', "Ad Update Successfully");
                    
                    if(isset($ad_type) && !empty($ad_type)){
						return $this->redirect('../payment/'.$ad_detail->slug.'/'.$ad_detail->id);
					}else{
						return $this->redirect($id);
					}
                }
            }
        }
		
		$model=AppHelper::setPostAdValues($adDetail,$model);	
		
		//echo "<pre>"; print_r($ArrAds);exit;
        return $this->render('edit_ad', ['model' => $model,'ad_images_model' => $ad_images_model,'post_cat_model'=>$post_cat_model,'categories'=>$categories,'cities'=>$cities,'countries'=>$countries,'states'=>$states,'adDetail'=>$ArrAds,'max_image_upload'=>$max_image_upload,'custom_fields'=>$ArrCFields2]);
    }
    
    public function actionGetSubCategory(){
		
		$cat_id = Yii::$app->request->post('cat_id');
		$cookies = Yii::$app->request->cookies;
		
		if(isset($cookies['_locale'])){
		$lang = $cookies['_locale']->value;
		}else{
		$lang = 'en';	
		}
	
        $lists= new Query();
        $lists = Categories::find();
        $lists->select(['categories.*', 
					  'categories_lang.id as  cat_lang_id',
					  'categories_lang.category_id','categories_lang.locale','categories_lang.title as cat_lang_title','categories_lang.description as cat_lang_description','categories_lang.slug as cat_lang_slug']);  
        $lists->joinWith('categories_lang');
        $lists->andWhere(['categories.status' => 1,'categories.parent_id'=>$cat_id]);
        $lists->all();
        $command = $lists->createCommand();
        $categories = $command->queryAll();
       // echo "<pre>"; print_r($categories);exit;
        
        $ArrCatLang =  array();
		$i = 0;
		
		foreach($categories as $key=>$catgory){
			if($lang=='pt-BR'){
				if($catgory['cat_lang_title']==''){
					$catgory['title'] = 	$catgory['title'];
				}else{
					$catgory['title'] = 	$catgory['cat_lang_title'];
				}
			
				if($catgory['cat_lang_description']==''){
					$catgory['description'] = 	$catgory['description'];
				}else{
					$catgory['description'] = 	$catgory['cat_lang_description'];
				}
				
			}elseif($lang=='en'){
				$catgory['title'] = 	$catgory['title'];
				$catgory['description'] = 	$catgory['description'];
			}
			
			$ArrCatLang[$i] = $catgory;
			$i++;
		}
		
        $aa = Yii::t('frontend', 'Select Sub Category');
        $html  = '';
        $html .= '<div class="title_sub">'.$aa.'<div id="sub-category-loader" style="visibility: hidden;"></div></div><ul id="sub_category">';
        foreach($ArrCatLang as $key=>$categories){ //print_r($categories);die;
			$html .= '<li class="sub-category-item"><a class="sub-cat-title" href="#" sub_cat_id='.$categories['id'].' sub_cat_name='.$categories['title'].'>'.$categories['title'].'<span class="selected_text"></span></a></li>';
		}
		$html .= '</ul>';
		echo $html;die;
	}
	

	public function actionGetCategoryCustomField(){
		$cat_id = Yii::$app->request->post('cat_id');
		$model = new PostAd();
		
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
						->select(['id','field_id','label'])
						->from('custom_field_options')
						->where(['field_id' => $custom_field_id['field_id']])
						->all();
		
		$custom_field_id['custom_fields'] = $custom_fields[0];
		
		if(!empty($custom_field_options)){
			$custom_field_id['custom_fields_options']	 = $custom_field_options;
		}
			$ArrAds[$i] = $custom_field_id;
			$i++;
		}
		
		$j = 1;	
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

        $form = ActiveForm::begin(['id' => 'post-ad']);
		return $this->renderPartial('custom_fields', ['model'=>$model,'custom_fields' => $ArrAds2,'form'=>$form]);
		echo json_encode($ArrAds2);die;
	}
	
	public function actionGetFields(){
		$cat_id = Yii::$app->request->post('cat_id');
		$setting_slug = 'ad_post';
		$ad_setting = SettingsCategory::findOne(['slug' => $setting_slug]);
					
		$ad_setting_fields = (new \yii\db\Query())
				->select(['*'])
				->from('settings')
				->where(['category' => $ad_setting['id']])
				->all();
		
		echo json_encode($ad_setting_fields);die;
	}
	
	public function actionAdPayment($slug,$id){
		$ad_detail = PostAd::find()
					 ->andWhere(['id' => $id])
					 ->one();
	
		$user_id = $ad_detail->user_id;
		$ad_type = $ad_detail->ad_type;
		
		$userplan = AppHelper::getUserSubscription($user_id);
		if(isset($userplan) && !empty($userplan)){
			$package_detail = Plans::getPlanDetail($userplan['plan_id']);
		}else{
			$default_subscription = AppHelper::getUserDefaultSubscription(); 
			$package_detail = Packages::getPackageDescription($default_subscription['value']);
		}
		
		$title = 'payment';
		return $this->render('payment', ['packages' => $package_detail,'ad_detail' => $ad_detail]);
	}
	
	
	public function beforeAction($action) 
	{ 
		$this->enableCsrfValidation = false; 
		return parent::beforeAction($action); 
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
    
    public function actionSubmitReview(){
		
		$response = array();
        $params = Yii::$app->request->post();        
       
		$wishlist = Review::checkReviewCount($params['ad_id'], $params['user_id']);
		if(empty($wishlist)){
			$model = new Review();
			$model['user_id'] = $params['user_id'];
			$model['ad_id'] = $params['ad_id'];
			$model['rating'] = $params['rating'];
			$model['comment'] = $params['comment'];
			if($model->save()){
				$response = Yii::t('frontend', 'Successfully added to Review');
				echo json_encode($response);
				exit;
			}
		}else{
			 $response = Yii::t('frontend', 'Already added review this Post.');
		}
       echo json_encode($response);
       exit;
	}
	
	
	public function actionDeleteMedia(){
		
        $params = Yii::$app->request->post();        
		$media_id = $params['media_id'];
			
			$delete_media = MediaUpload::find()->where(['id'=> $media_id])->one();
			$file_name = $delete_media->file_name;
			
			$uploadDir = Yii::getAlias('@storage/web/source/advertisement/');
			
			unlink($uploadDir . $file_name);
			
			if(!empty($delete_media)){
			     $delete_media->delete();
			}    
			$delete_ad_image = PostAdImages::find()->where(['media_id'=>$media_id])->one();
			if(!empty($delete_ad_image)){
			     $delete_ad_image->delete();
			} 
			if($delete_media && $delete_ad_image){
				$response = 'Image Delete successfully';
				echo json_encode($response);
				exit;
			}
			
       echo json_encode($response);
        exit;
	}
	
	public function actionAdvertisePlan() {
		if(Yii::$app->request->post()){
			$post = Yii::$app->request->post();
			
			$model = new Transactions();
			$model->ad_id = $post['ad_id'];
			$model->user_id = $post['user_id'];
			$model->txn_amount = $post['amount'];
			$model->txn_date = date('Y-m-d',time());
			$model->type = 'postad';
			$model->payment_type = 'cash';
			if($model->save()){
				return true;
			}
		} 
    }
    
}
