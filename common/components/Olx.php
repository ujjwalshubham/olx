<?php

namespace common\components;
use common\models\Categories;
use Yii;
use yii\db\Query;
use yii\helpers\Url;
use common\models\UserProfile;
use common\models\Wishlist;
use common\models\CategoriesLang;
use yii\helpers\ArrayHelper;
use common\models\Cities;
use common\models\Faqs;
use common\models\MediaUpload;
use yii\data\Pagination;
		$cookies = Yii::$app->request->cookies;
		if(isset($cookies['_locale']) && !empty($cookies['_locale'])){
		$lang = $cookies['_locale']->value;
		}else{
		$lang = 'en';	
		}

class Olx {
	
	
	public static function getAllCategory($lang_app = null, $cat_id = null)
    {
		$cookies = Yii::$app->request->cookies;		
		if(isset($cookies['_locale']) && !empty($cookies['_locale'])){
			$lang = $cookies['_locale']->value;
		}else if(!empty($lang_app)){
			$lang = $lang_app;	
		}else{
			$lang = 'en';	
		}
		
		if(!empty($cat_id)){
			$catId = $cat_id;
		}else{
			$catId = 0;
		}
		
		//$lang = $cookies['_locale']->value;
		
        $lists= new Query();
        $lists = Categories::find();
        $lists->select(['categories.*', 
						  'categories_lang.id as  cat_lang_id',
						  'categories_lang.category_id','categories_lang.locale','categories_lang.title as cat_lang_title','categories_lang.description as cat_lang_description','categories_lang.slug as cat_lang_slug']);  
        $lists->joinWith('categories_lang');
        $lists->andWhere(['categories.status' => 1,'categories.parent_id'=>$catId]);
        $lists->all();
        $command = $lists->createCommand();
        $categories = $command->queryAll();
		
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
		
		//echo "<pre>"; print_r( $categories );exit;
		return $ArrCatLang;
    }
    
    public static function wishlistData($user_id,$ad_id){
		$lists= new Query();
        $lists = Wishlist::find();
        $lists->select('wishlist.*');
        $lists->andWhere(['ad_id' => $ad_id,'user_id'=>$user_id]);
        $lists->one();
        $command = $lists->createCommand();
        $wishlist = $command->queryOne();
        
       return  $wishlist;
	}
	
	public static function search_permute($items, $perms = array( )) {
		
        $back = array();
        if (empty($items)) {
            $back[] = join(' ', $perms);
        } else {
            for ($i = count($items) - 1; $i >= 0; --$i) {
                $newitems = $items;
                $newperms = $perms;
                list($foo) = array_splice($newitems, $i, 1);
                array_unshift($newperms, $foo);
                $back = array_merge($back, Olx::search_permute($newitems, $newperms));
            }
        }
        return $back;
    }
    
    
    public static function getAdSearchListArray($words){
		
        $query= new Query();
        $usersearch=array();
        foreach($words as $word){
            $usersearch[] ="post_ads.title LIKE '%".$word."%'";
            $usersearch[] ="post_ads.description LIKE '%".$word."%'";
            $usersearch[] ="post_ads.tags LIKE '%".$word."%'";
        }
        $v1=implode(' or ', $usersearch);
        
        $query->select(['id', 'title','slug','description','city','address','tags'])
            ->from(['post_ads'])
            ->where(['status' => 'Active'])
            ->andFilterWhere(['or', $v1])
            ->orderBy('id')
            ->limit(20);

        $command = $query->createCommand();
        $results = $command->queryAll();
        
        $i = 0;
		$ArrAds = array();
		
		foreach($results as $key=>$value){
			$city_name =  Cities::getCityById($value['city']);
			$value['city_name'] =  $city_name['name'];
			
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
			
			
			if($images){
			$media_id = $images[0]['media_id'];
			$image = MediaUpload::getImageByMediaId($media_id);
			$new_image  = $image['url'].'/'. $image['upload_base_path'].'/'.$image['file_name'];
			$value['image'] =  $new_image;
			}else{
			$value['image'] = '';	
			}
			
			$ArrAds[$i] = $value;
			
			$i++;
		}
        //echo "<pre>"; print_r($results);exit;
        return $ArrAds;
    }
    
    
     public static function getCategorySearchListArray($words){
        $catsearch=array();
        foreach($words as $word){
            $catsearch[] ="categories.title LIKE '%".$word."%'";
            $catsearch[] ="categories.slug LIKE '%".$word."%'";
        }
        $v2=implode(' or ', $catsearch);

        $query = new Query;

        $query->select(['id','title', 'slug'])
            ->from(['categories'])
            ->where(['status'=> 1])
            ->andFilterWhere(['or', $v2])
            ->orderBy('id')
            ->limit(20);
        $command = $query->createCommand();
        $categories = $command->queryAll();
        return $categories;
    }
    
      public static function getTypeDefaultListArray(){
        $query= new Query();
        $query->select([ 'id','title'])
            ->from(['post_ads'])
            ->andFilterWhere(['status'=> 'Active'])
            ->orderBy('id')
            ->limit(20);
        $command = $query->createCommand();
        $data = $command->queryAll();
        return $data;
    }
    
    
    public static function getAllFaqs($lang_app = null)
    {

		$cookies = Yii::$app->request->cookies;		
		if(isset($cookies['_locale']) && !empty($cookies['_locale'])){
			$lang = $cookies['_locale']->value;
		}else if(!empty($lang_app)){
			$lang = $lang_app;	
		}else{
			$lang = 'en';	
		}
		
	
		//$lang = $cookies['_locale']->value;
		
        $lists= new Query();
        $lists = Faqs::find();
        $lists->select(['faqs.*', 
						  'faqs_lang.id as  faq_lang_id',
						  'faqs_lang.faq_id','faqs_lang.locale','faqs_lang.title as faq_lang_title','faqs_lang.description as faq_lang_description']);  
        $lists->joinWith('faqs_lang');
        $lists->andWhere(['faqs.status' => 1]);
        $lists->all();
        $command = $lists->createCommand();
        $faqs = $command->queryAll();
		
		$ArrFaqLang =  array();
		$i = 0;
		
		foreach($faqs as $key=>$faq){
			if($lang=='pt-BR'){
				if($faq['faq_lang_title']==''){
					$faq['title'] = 	$faq['title'];
				}else{
					$faq['title'] = 	$faq['faq_lang_title'];
				}
			
				if($faq['faq_lang_description']==''){
					$faq['description'] = 	$faq['description'];
				}else{
					$faq['description'] = 	$faq['faq_lang_description'];
				}
				
			}elseif($lang=='en'){
				$faq['title'] = 	$faq['title'];
				$faq['description'] = 	$faq['description'];
			}
			
			$ArrFaqLang[$i] = $faq;
			$i++;
		}
		//echo "<pre>"; print_r( $categories );exit;
		return $ArrFaqLang;
    }

 
}
