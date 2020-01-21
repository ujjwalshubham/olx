<?php
namespace frontend\controllers;


use Yii;

use yii\data\ActiveDataProvider;
use yii\helpers\Url;
use yii\web\Controller;
use yii\db\Query;
use common\components\Olx;
use common\components\AppHelper;
use common\models\Categories;
use common\models\PostAd;
use common\models\States;
use common\models\Settings;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\widgets\ActiveForm;
use yii\data\Pagination;

/**
 * Site controller
 */
class SearchController extends Controller
{
    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction'
            ]
        ];
    }
   
    public function actionThanks()
    {
        return $this->render('thanks');
    }
    
    public function actionGetSearchList(){
		
		 \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $data=array();
        if(Yii::$app->request->post()){
            $post = Yii::$app->request->post();
            $q=trim($post['term']);
           
            $query = new Query;
            if($q != ''){
                $words=explode(' ',$q);
                $words= Olx::search_permute($words);
                $adlist=Olx::getAdSearchListArray($words);
               
                foreach($adlist as $result){
                    $data[]=array('id'=>$result['id'],'category_check'=>'Advertisement','category'=>Yii::t('frontend','Advertisement'),'label'=>$result['title'],'city'=>$result['city_name'],'avator'=>$result['image'],'query'=>$result['slug'],'filters'=>'Advertisement');
                }
               
                /*Category List search*/
                $categories = Olx::getCategorySearchListArray($words);
                if(!empty($categories)){
                    foreach($categories as $cat){
                        $data[]=array('id'=>$cat['id'],'category_check'=>'Category','category'=>Yii::t('frontend','Category'),'query'=>$cat['slug'],'label'=>$cat['title'],'filters'=>'Category','category_id'=>$cat['id']);
                    }
                }
                $out = array_values($data);
            }
            else{
                $data= Olx::getAllCategory(); 
                foreach($data as $category){
					$cat_image = AppHelper::getCategoryImage($category['id']); 
					
                    $out[]=array('id'=>$category['id'],'category_check'=>'Default','category'=>'Category','query'=>$category['slug'],'title'=>Yii::t('db',$category['title']),'label'=>Yii::t('frontend',$category['title']),'filters'=>'Category','avator'=>$cat_image,'category_id'=>$category['id']);
                }
            }            
            return $out; exit();
        }
    }
    
    
     public function actionGetDetailurl(){
	
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        if(Yii::$app->request->post()){
            $post = Yii::$app->request->post();
		
            $id=$post['id'];
            $out=array();

            if($id != ''){
				if(isset($post['filter'])){
					if(isset($post['slug'])){
						if($post['filter'] == 'Category'){
							$path='/category/';
						}
						else{
							$path='/user/ad-detail/';
						}
						$out = array('result'=>'success','fullpath'=>0,'filter'=>$post['filter'],'slug'=>$post['slug'],'path'=>$path);
					}
					else{
						$out = array('result'=>'success','fullpath'=>0,'filter'=>$post['filter'],'path'=>'/search?results_type='.$post['filter']);
					}
				  
					$this->setSearchCookie($out);
					return $out;exit();
				}
                return $out;exit();
            }
            elseif(isset($post['filter'])){
                $restype=strtolower($post['filter']);
                if(isset($post['slug'])){
                    $out = array('result'=>'success','fullpath'=>0,'filter'=>$restype,'slug'=>$post['slug'],'path'=>'/search?results_type='.$restype.'&q=');
                }
                else{
                    $out = array('result'=>'success','fullpath'=>0,'filter'=>$restype,'path'=>'/search?results_type='.$restype);
                }
                $this->setSearchCookie($out);
                return $out;exit();
            }
            else{

            }
        }
        return array('result'=>'fail');exit();
    }

	public function setSearchCookie($codearray){
        $baseurl =$_SERVER['HTTP_HOST'];
        $json = json_encode($codearray, true);
        setcookie('search_filter', $json, time()+60*60, '/',$baseurl , false);
        return $codearray;
    }
    
	public function actionGetCities() {
	$params = Yii::$app->request->post();
	$cities = (new \yii\db\Query())
				->select(['cities.*','states.name as state_name'])
				->from('cities')
				->leftjoin('states', 'cities.state_id = states.id')
				->where(['cities.country_id' => 113]);
					
	if(!empty($params['dataString'])){	
		$cities = $cities->andFilterWhere(['like', 'cities.name', $params['dataString']]);	
		$cities = $cities->all();			
	}
		$html = '';
        $html .= '<ul class="searchResgeo">';
        $html .= '<li><a href="#" class="title selectme" data-id="" data-name="" data-type="">Any City</a></li>';
        foreach($cities as $key=>$city){ //print_r($categories);die;
			$html .= '<li><a  href="#" class="title selectme" data-id='.$city['id'].' data-name='.$city['name'].' data-type="city">'.$city['name'].',<span class="color-9"> '.$city['state_name'].'</span></a></li>';
		 }
		$html .= '</ul>';
		echo $html;die;
	}
	
	public function actionGetStates() {
	$params = Yii::$app->request->post();
	$states =States::getStates();
		
		$html = '';
        $html .= '<ul class="column col-md-12 col-sm-12 cities">';
        $html .= '<li class="active"><a href="#" class="selectme" data-id="113" data-name="All India" data-type="country">All india<i class="fa fa-angle-right" aria-hidden="true"></i></a></li>';
        foreach($states as $key=>$state){ //print_r($categories);die;
			$html .= '<li><a  href="javascript:void(0)" class="statedata" data-id='.$state['id'].' data-name='.$state['name'].' data-type="state">'.$state['name'].'<i class="fa fa-angle-right" aria-hidden="true"></i></a></li>';
		 }
		$html .= '</ul>';
		echo $html;die;
		
	}
	
	public function actionGetCitiesByState() {
		
	$params = Yii::$app->request->post();
	$state_id = 	$params['id'];
	$state_detail = States::getStateDetail($state_id);
	
	 $cities = (new \yii\db\Query())
				->select(['cities.*'])
				->from('cities')
				->where(['state_id' => $state_detail['id']]);
	 $cities = $cities->all();	
	
		$html = '';
        $html .= '<ul class="column col-md-12 col-sm-12 cities">';
        $html .= '<li class="selected active"><a href="#" id="changeState"><strong><i class="fa fa-arrow-left"></i>Change Region</strong></a></li>';
        $html .= '<li class="selected active"><a href="#" class="selectme" data-id="'.$state_detail['id'].'" data-name="'.$state_detail['name'].', Region" data-type="state"><strong>Whole '.$state_detail['name'].'</strong></a></li>';
        
        foreach($cities as $key=>$city){ //print_r($categories);die;
			$html .= '<li><a  href="#"  class="title selectme" data-id='.$city['id'].' data-name='.$city['name'].' data-type="city">'.$city['name'].'</a></li>';
		 }
		$html .= '</ul>';
		echo $html;die;
		
	}
	
    
    public function actionCategory($slug, $city=''){
		
		$page_size = Settings::getPageSize();
		$userId = Yii::$app->user->identity['id'];
		$states = States::getStates();
        $string=Yii::$app->request->queryParams;
        $params = array();
     
        $lists = $lists2 = array();$user = array(); $v1 = '';$q='';
        $recordlimit=10; $count_result = $count_result1=0; $getlastPagination =$getlastPagination1=0;
        $type='Category';
        $cityslug=0;

        /*if(isset($string['page']) && !empty($string['page'])){
            $pagination = $string['page'];
            $page = $string['page'] - 1;
            $offset= $recordlimit * $page ;
        }
        else{
            $pagination = 1;
            $offset=0;
        }*/

        $out = array('result'=>'success','fullpath'=>0,'filter'=>'Category','slug'=>$slug,'path'=>'/category/');
        $this->setSearchCookie($out);

        if(isset($string['city']) && !empty($string['city'])){
            $cityslug=1;
            $city=$string['city'];
            if(isset($_COOKIE['location_filter'])){
                $cookie = $_COOKIE['location_filter'];
                //$cookie = stripslashes($cookie);
                $location = json_decode($cookie, true);
                $locationname=Appelavocat::slugifyCity($location['name']);
                if($locationname == $city){
                    $user=Appelavocat::getLocationUserList($location['lat'],$location['lng']);
                }
                else{
                    $fetchlatlong=Appelavocat::get_lat_long($city);
                    if(!empty($fetchlatlong)){
                        $user=Appelavocat::getLocationUserList($fetchlatlong['lat'],$fetchlatlong['lng']);
                        $setlatlong=$this->setLocationCookieFilter($fetchlatlong);
                    }
                }
            }
            else{
                $fetchlatlong=Appelavocat::get_lat_long($city);
                if(!empty($fetchlatlong)){
                    $user=Appelavocat::getLocationUserList($fetchlatlong['lat'],$fetchlatlong['lng']);
                    $setlatlong=$this->setLocationCookieFilter($fetchlatlong);
                }
            }
        }
        else{
            $baseurl =$_SERVER['HTTP_HOST'];
            setcookie ("location_filter", "", time() - 3600,'/', $baseurl , false);
        }
        $q=$slug;
        $category = Categories::findOne(['slug' => $slug]);
        $new_cat_id = $category->id;
       /*================================= Custom Fields=====================*/ 
       
       $sub_categories = AppHelper::getSubCategories($new_cat_id);
       $a = 0;	
	   $sub_cat_array = array();	
       foreach($sub_categories as $key=>$value){
			$sub_cat_array[$a] = $value['id'];
			$a++; 
	   }
		$query = new Query();
				 $query->select(['id','field_id','category_id'])->from('categories_custom_fields')
			    ->where(['IN', 'category_id', $sub_cat_array])
			    ->groupBy(['field_id']);
			    
		$command = $query->createCommand();
		$custom_field_ids =  $command->queryAll();

	    //echo "<pre>"; print_r($custom_field_ids);exit;
	    
		$b = 1;	
		$ArrFields = array();	
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
		$ArrFields[$b] = $custom_field_id;
		$b++;
		}
		
		$c = 1;	
		$ArrFields2 = array();	
		foreach($ArrFields as $key=>$value){
		
		$custom_fields_type = (new \yii\db\Query())
							->select(['id','type','data_type','label','options_enabled','options_label','status'])
							->from('custom_field_types')
							->where(['id' => $value['custom_fields']['field_type_id']])
							->all();
	
			$value['custom_fields_type']	 = $custom_fields_type[0];
			$ArrFields2[$c] = $value;
			$c++;
		}
       
       /*===================================End Custom Fields===========================*/ 
        $allAds = (new \yii\db\Query())
				->select(['post_ads.*','cities.name as city_name','states.name as state_name','countries.name as country_name'])
				->from('post_ads')
				->leftjoin('cities', 'post_ads.city = cities.id')
				->leftjoin('states', 'post_ads.state = states.id')
				->leftjoin('countries', 'post_ads.country = countries.id')
				->orderBy(['post_ads.id' => SORT_DESC]);
        
         if(!empty($category)){				
					$allAds = $allAds->innerjoin('ads_category', 'post_ads.id = ads_category.ad_id')
					->where(['=', 'ads_category.cat_id', $category->id]);			
				}

	    $allAds = $allAds->andWhere(['=', 'post_ads.status', 'Active']);
        // echo $allAds->createCommand()->getRawSql();exit;
        $allAds = $allAds->groupBy(['post_ads.id']);	
        $countQuery = clone $allAds;
        $pages = new Pagination(['totalCount' => $countQuery->count(), 'pageSize'=>$page_size]);
        $allAds = $allAds->offset($pages->offset)->limit($pages->limit)->all();
       
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
					->select(['ads_images.*', 'media_upload.file_name', 'media_upload.upload_base_path', 'media_upload.upload_base_path'])
					->from('ads_images')
					->leftjoin('media_upload', 'ads_images.media_id = media_upload.id')
					->where(['ads_images.ad_id' => $value['id']])
					->all();
			
			$value['ads_images'] =  $images;
			$ArrAds[$i] = $value;
			
			$i++;
		}
       
     
		//echo "<pre>"; print_r($allAds);exit;
      
        /*if(!empty($category)){
            $q=$category->title;
            $ad_ids =(new \yii\db\Query())
				->select('ad_id')
				->from('ads_category')
				->where(['cat_id' => $category->id])
				->orderBy(['id' => SORT_DESC])
				->all();
           // echo "<pre>"; print_r($ad_ids);exit;
            
           // $model = UserCategory::find()->where(['cat_id' => $category->id])->select('user_id')->asArray()->all();

            $userarray=array();
            foreach($ad_ids as $val){
                $userarray[]=$val['ad_id'];
            }

            if(isset($string['city']) && !empty($string['city'])){
                $narray=array_intersect($user,$userarray);
                $userarray=array_values($narray);
            }
			
            if(!empty($userarray)){
			
                $lists= new Query();
                $lists = PostAd::find();
                $lists->where(['id'=>$userarray]);
                $lists->andWhere(['status'=>PostAd::STATUS_ACTIVE]);

                $countQuery = clone $lists;
                $totalpages = new Pagination(['totalCount' => $countQuery->count()]);


                $lists->limit($recordlimit);
                $lists->offset($offset);

                //$lists->all();
                $command = $lists->createCommand();
                $lists = $command->queryAll();
                
                echo "<pre>"; print_r($lists);exit;
            }
        }*/


       /* if(isset($totalpages)){
            $count_result=$totalpages->totalCount;
        }
        $getlastPagination=Appelavocat::getlastPagination($count_result,$recordlimit);*/
		//echo "<pre>"; print_r($category);exit;
    
        return $this->render('search',['allAds'=>$ArrAds,'userid'=>$userId,'city'=>$city,'result_type'=>$type,'query_slug'=>$q,'count_result'=>$count_result,'recordlimit'=>$recordlimit,'getlastPagination'=>$getlastPagination,'string'=>$string,'slug'=>$slug,'category'=>$category,'states'=>$states,'custom_fields'=>$ArrFields2,'new_cat_id'=>$new_cat_id,'params'=>$params,'pages'=>$pages]);
    }
 
    /**
     * @inheritdoc
     */
     public function actionGetSearch() {
		
		$page_size = Settings::getPageSize(); 
		$response = array();
		$states = States::getStates();
		
        $params = Yii::$app->request->get();
        //echo "<pre>";print_r($params);exit;

		if(trim($params['cat']) &&!empty(trim($params['cat']))){
			$category = Categories::findOne(['id' => $params['cat']]);
		}
		else if(!empty(trim($params['subcat']))){
			$category = Categories::findOne(['id' => $params['subcat']]);;
			
		}else{
			$category = (object)array('title'=>'All Categories','id'=>'All','parent_id'=>Null);
		}
		
		if(!empty($params['subcat'])){
			$sub_category = Categories::findOne(['id' => $params['subcat']]);
		}
		
	/*=================Custom Fielgs category=======================*/
	   if($category->parent_id==0){
	   $sub_categories = AppHelper::getSubCategories($category->id);
       $a = 0;	
	   $sub_cat_array = array();	
       foreach($sub_categories as $key=>$value){
		  
			$sub_cat_array[$a] = $value['id'];
			$a++; 
	   }

		$query = new Query();
				 $query->select(['id','field_id','category_id'])->from('categories_custom_fields')
			    ->where(['IN', 'category_id', $sub_cat_array])
			    ->groupBy(['field_id']);
			    
		$command = $query->createCommand();
		$custom_field_ids =  $command->queryAll();
		
		}else {
	
	    $query = new Query();
				 $query->select(['id','field_id','category_id'])->from('categories_custom_fields')
			    ->where(['category_id' => $category->id]);
		$command = $query->createCommand();
		$custom_field_ids =  $command->queryAll();
		}
		
		
		$b = 1;	
		$ArrFields = array();	
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
		$ArrFields[$b] = $custom_field_id;
		$b++;
		}
		
		$c = 1;	
		$ArrFields2 = array();	
		foreach($ArrFields as $key=>$value){
		
		$custom_fields_type = (new \yii\db\Query())
							->select(['id','type','data_type','label','options_enabled','options_label','status'])
							->from('custom_field_types')
							->where(['id' => $value['custom_fields']['field_type_id']])
							->all();
	
			$value['custom_fields_type']	 = $custom_fields_type[0];
			$ArrFields2[$c] = $value;
			$c++;
		}
	/*=====================Custom Field Ids================//*/
			 $allAds = (new \yii\db\Query())
				->select(['post_ads.*','cities.name as city_name','states.name as state_name','countries.name as country_name'])
				->from('post_ads')
				->innerjoin('ads_category', 'post_ads.id = ads_category.ad_id')
				->leftjoin('cities', 'post_ads.city = cities.id')
				->leftjoin('states', 'post_ads.state = states.id')
				->leftjoin('countries', 'post_ads.country = countries.id')
				->orderBy(['post_ads.id' => SORT_DESC]);
			 
			
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
				$allAds = $allAds->orFilterWhere(['like', 'post_ads.description', $params['keywords']]);				
				$allAds = $allAds->orFilterWhere(['like', 'post_ads.tags', $params['keywords']]);				
			}
			
			if(!empty($params['keywords']) && !empty($params['cat'])){
							
				$allAds = $allAds->andFilterWhere(['like', 'post_ads.title', $params['keywords']]);
				$allAds = $allAds->orFilterWhere(['like', 'post_ads.description', $params['keywords']]);				
				$allAds = $allAds->orFilterWhere(['like', 'post_ads.tags', $params['keywords']]);	
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
			
			if(!empty($params['range1'])){
				$allAds = $allAds->andFilterWhere(['>=', 'post_ads.price', $params['range1']]);				
			}
			
			if(!empty($params['range2'])){
				$allAds = $allAds->andFilterWhere(['<=', 'post_ads.price', $params['range2']]);				
			}
			//echo "<pre>";print_r($params['custom']);exit;
			if(isset($params['custom']) && !empty($params['custom'])){
				
				$allAds = $allAds->leftjoin('post_ads_custom_fields', 'post_ads.id = post_ads_custom_fields.ad_id');
				foreach($params['custom'] as $Arr){
					if(is_array($Arr)){ 
					$acustom[] = $Arr;
				}else{
					$bcustom[] = $Arr;
				}
				}
							
			 $query_array=array();
			 
			 if (isset($acustom[0] ) && !empty($acustom[0] )) {
                foreach ($acustom[0] as $needle) {
                    $query_array[] = sprintf('FIND_IN_SET("%s",`post_ads_custom_fields`.`value`)', $needle);
                }
                $query_str = implode(' OR ', $query_array);
                $allAds->andWhere(new \yii\db\Expression($query_str));			
			}
			$bcus = array_filter($bcustom);
			if(isset($bcus) && !empty($bcus)){				
					$allAds = $allAds->andFilterWhere(['IN', 'post_ads_custom_fields.value', $bcus]);			
			}
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
		    	
			$allAds = $allAds->groupBy(['post_ads.id']);
			
			$countQuery = clone $allAds;
			$pages = new Pagination(['totalCount' => $countQuery->count(), 'pageSize'=>$page_size]);
			$allAds = $allAds->offset($pages->offset)->limit($pages->limit)->all();
				
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
		
	  $title = 'Ads';
	  //echo "<pre>"; print_r($ArrAds);exit;
	  
      return $this->render('search', ['title' => $title,'allAds'=>$ArrAds,'category'=>$category,'states'=>$states,'params'=>$params,'custom_fields'=>$ArrFields2,'pages'=>$pages]);  
      //echo "<pre>";print_r($ArrAds);exit;
	}
	
	public function actionListing() {
		
	   $page_size = Settings::getPageSize();
	   $response = array();
	   $states = States::getStates();
	   $category = (object)array('title'=>'All Categories','id'=>'All','parent_id'=>Null);
		
	/*====================Custom Fielgs category====================*/
		
	   if($category->parent_id==0){
	   $sub_categories = AppHelper::getSubCategories($category->id);
       $a = 0;	
	   $sub_cat_array = array();	
       foreach($sub_categories as $key=>$value){
			$sub_cat_array[$a] = $value['id'];
			$a++; 
	   }

		$query = new Query();
				 $query->select(['id','field_id','category_id'])->from('categories_custom_fields')
			    ->where(['IN', 'category_id', $sub_cat_array])
			    ->groupBy(['field_id']);
			    
		$command = $query->createCommand();
		$custom_field_ids =  $command->queryAll();
		
		}else {
	    $query = new Query();
				 $query->select(['id','field_id','category_id'])->from('categories_custom_fields')
			    ->where(['category_id' => $category->id]);
		$command = $query->createCommand();
		$custom_field_ids =  $command->queryAll();
		}
		
		$b = 1;	
		$ArrFields = array();	
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
		$ArrFields[$b] = $custom_field_id;
		$b++;
		}
		
		$c = 1;	
		$ArrFields2 = array();	
		foreach($ArrFields as $key=>$value){
		
		$custom_fields_type = (new \yii\db\Query())
							->select(['id','type','data_type','label','options_enabled','options_label','status'])
							->from('custom_field_types')
							->where(['id' => $value['custom_fields']['field_type_id']])
							->all();
	
			$value['custom_fields_type']	 = $custom_fields_type[0];
			$ArrFields2[$c] = $value;
			$c++;
		}
	/*=====================Custom Field Ids================//*/
	
       		 
			 $allAds = (new \yii\db\Query())
				->select(['post_ads.*','cities.name as city_name','states.name as state_name','countries.name as country_name'])
				->from('post_ads')
				->innerjoin('ads_category', 'post_ads.id = ads_category.ad_id')
				->leftjoin('cities', 'post_ads.city = cities.id')
				->leftjoin('states', 'post_ads.state = states.id')
				->leftjoin('countries', 'post_ads.country = countries.id')
				->orderBy(['post_ads.id' => SORT_DESC]);
			 
			
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
				$allAds = $allAds->orFilterWhere(['like', 'post_ads.description', $params['keywords']]);				
				$allAds = $allAds->orFilterWhere(['like', 'post_ads.tags', $params['keywords']]);				
			}
			
			if(!empty($params['keywords']) && !empty($params['cat'])){
				$allAds = $allAds->andFilterWhere(['like', 'post_ads.title', $params['keywords']]);
				$allAds = $allAds->orFilterWhere(['like', 'post_ads.description', $params['keywords']]);				
				$allAds = $allAds->orFilterWhere(['like', 'post_ads.tags', $params['keywords']]);	
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
			
			if(!empty($params['range1'])){
				$allAds = $allAds->andFilterWhere(['>=', 'post_ads.price', $params['range1']]);				
			}
			
			if(!empty($params['range2'])){
				$allAds = $allAds->andFilterWhere(['<=', 'post_ads.price', $params['range2']]);				
			}
			//echo "<pre>";print_r($params['custom']);exit;
			if(isset($params['custom']) && !empty($params['custom'])){
				
				$allAds = $allAds->leftjoin('post_ads_custom_fields', 'post_ads.id = post_ads_custom_fields.ad_id');
				foreach($params['custom'] as $Arr){
					if(is_array($Arr)){ 
					$acustom[] = $Arr;
					}else{
						$bcustom[] = $Arr;
					}
				}
							
			 $query_array=array();
			 
			 if (isset($acustom[0] ) && !empty($acustom[0] )) {
                foreach ($acustom[0] as $needle) {
                    $query_array[] = sprintf('FIND_IN_SET("%s",`post_ads_custom_fields`.`value`)', $needle);
                }
                $query_str = implode(' OR ', $query_array);
                $allAds->andWhere(new \yii\db\Expression($query_str));			
			}
			$bcus = array_filter($bcustom);
			if(isset($bcus) && !empty($bcus)){				
					$allAds = $allAds->andFilterWhere(['IN', 'post_ads_custom_fields.value', $bcus]);			
			}
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
		    	
			$allAds = $allAds->groupBy(['post_ads.id']);
			$countQuery = clone $allAds;
			$pages = new Pagination(['totalCount' => $countQuery->count(), 'pageSize'=>$page_size]);
			$allAds = $allAds->offset($pages->offset)->limit($pages->limit)->all();
			
		
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
		
	  $title = 'Ads';
	 
      return $this->render('listing', ['title' => $title,'allAds'=>$ArrAds,'category'=>$category,'states'=>$states,'pages'=>$pages]);  
      //echo "<pre>";print_r($ArrAds);exit;
	}
    public function beforeAction($action) {
		$this->enableCsrfValidation = false;
		return parent::beforeAction($action);
	}
}
