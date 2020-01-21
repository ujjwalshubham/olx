<!-- header section start-->
<?php
   use yii\helpers\Html;
   use yii\helpers\Url;
   use common\models\PostAd;
   use common\models\MediaUpload;
   use common\models\Categories;
   use common\models\Cities;
   use common\components\AppHelper;
   use yii\widgets\ActiveForm;
   use common\components\Olx;
   if (Yii::$app->User->id) {
       //$UserProfile = Yii::$app->user->identity->userProfile; 
       //$userId = Yii::$app->User->id;
       $myadscount = PostAd::adsCountByUser($userId);
   }
   
    $categories = Olx::getAllCategory();
   //echo "<pre>"; print_r($UserProfile);exit;
   
   ?>
<section id="main" class="category-page mid_container">
   <div class="container">
      <!-- breadcrumb -->
      <div class="breadcrumb-section">
         <ol class="breadcrumb">
            <li><a href="<?php echo \Yii::$app->request->BaseUrl; ?>"><i class="fa fa-home"></i>  <?php echo Yii::t('frontend', 'Home')?></a></li>
            <li><?php echo $title?> </li>
            <div class="ml-auto back-result"><a href=""><i class="fa fa-angle-double-left"></i>  <?php echo Yii::t('frontend', 'Back to Results')?></a> </div>
         </ol>
      </div>
      <!-- breadcrumb -->
      <div class="panel-user-details">
         <div class="user-details section">
            <div class="row">
               <div class="col-sm-3 col-lg-2">
                  <div class="user-img profile-img"> 
                     <?php if(!empty($UserProfile['avatar_path'])){?>
                     <img src="<?php echo $UserProfile['avatar_base_url'].'/'.$UserProfile['avatar_path']?>" alt="<?php echo $UserProfile['name']?>" class="img-responsive"> 
                     <?php } else {?>
                     <img src="<?php echo \Yii::$app->request->BaseUrl; ?>/images/user-img.png" alt="user" class="img-responsive"> 
                     <?php } ?>
                  </div>
               </div>
               <div class="col-sm-12 col-lg-6">
                  <div class="user-admin">
                     <h3><?php echo $UserProfile->name?> </h3>
                     <p><?php echo $UserProfile->about?></p>
                     <section class="contacts">
                        <figure class="social-links"><i class="fa fa-map-marker"></i><a href="javascript:void(0)" ><?php echo $UserProfile->address?></a></figure>
                        <figure class="social-links"><i class="fa fa-phone"></i><a href="javascript:void(0)"><?php echo Yii::$app->user->identity->mobile?></a></figure>
                        <figure class="social-links"><a href=""><i class="fa fa-envelope"></i><?php echo Yii::$app->user->identity->email?></a></figure>
                        <?php if($UserProfile->website){?>
                        <figure class="social-links"><i class="fa fa-globe"></i><a href="<?php echo $UserProfile->website?>" target="_blank"><?php echo $UserProfile->website?></a></figure>
                        <?php }?>
                     </section>
                     <!--end contacts--> 
                     <!-- social-links -->
                     <p><?php echo Yii::t('frontend', 'Social Profile')?></p>
                     <ul class="social_share margin-top-100 pull-right">
                        <?php if(isset($UserProfile->facebook_url) && !empty($UserProfile->facebook_url)){?>  
                        <li><a href="<?php echo $UserProfile->facebook_url;?>" target="_blank" class="facebook"><i class="fa fa-facebook"></i></a></li>
                        <?php } ?>
                        <?php if(isset($UserProfile->twitter_url) && !empty($UserProfile->twitter_url)){?>  
                        <li><a href="<?php echo $UserProfile->twitter_url;?>" target="_blank" class="twitter"><i class="fa fa-twitter"></i></a></li>
                        <?php } ?>
                        <?php if(isset($UserProfile->google_plus_url) && !empty($UserProfile->google_plus_url)){?>  
                        <li><a href="<?php echo $UserProfile->google_plus_url;?>" target="_blank" class="google"><i class="fa fa-google-plus"></i></a></li>
                        <?php } ?>
                        <?php if(isset($UserProfile->instagram_url) && !empty($UserProfile->instagram_url)){?>  
                        <li><a href="<?php echo $UserProfile->instagram_url;?>" target="_blank" class="linkden"><i class="fa fa-linkedin"></i></a></li>
                        <?php } ?>
                        <?php if(isset($UserProfile->linkedin_url) && !empty($UserProfile->linkedin_url)){?>  
                        <li><a href="<?php echo $UserProfile->linkedin_url;?>" target="_blank" class="instagram"><i class="fa fa-instagram"></i></a></li>
                        <?php } ?>
                        <?php if(isset($UserProfile->youtube_url) && !empty($UserProfile->youtube_url)){?>  
                        <li><a href="<?php echo $UserProfile->youtube_url;?>" target="_blank" class="youtube"><i class="fa fa-youtube"></i></a></li>
                        <?php } ?>
                     </ul>
                     <!-- social-links --> 
                  </div>
               </div>
               <div class="col-sm-12 col-lg-4">
                  <div class="user-ads-details">
                     <!--<div class="site-visit">
                        <h3><a href="#">23.4k</a></h3>
                        <small><?php echo Yii::t('frontend', 'Visits') ?></small> 
                        </div>-->
                     <div class="my-quickad">
                        <h3><a href="#">10</a></h3>
                        <small><?php echo Yii::t('frontend', 'Featured') ?></small> 
                     </div>
                     <div class="favourites">
                        <h3><a href="#"><?php echo $myadscount?></a></h3>
                        <small><?php echo Yii::t('frontend', 'Total Ads') ?></small> 
                     </div>
                  </div>
                  <!--end social--> 
               </div>
            </div>
         </div>
      </div>
      <!-- Inner Search section start -->
      <div class="section inner_p_search">
         <div id="ListingForm">
			 
			<?php $form = ActiveForm::begin([ 'method' => 'get','action' => [''],'options' => []]) ?>
			 
            <div class="row">
				<?php $cat_image = AppHelper::getCategoryImage($category['id']);
                  ?>
				
               <div class="col-md-4">
                  <div class="dropdown category-dropdown open">
                     <a data-toggle="dropdown" href="#" aria-expanded="true"><span class="change-text">
                     <?php if(isset($cat_image) && !empty($cat_image)){?>
                     <img src="<?php echo $cat_image?>" style="width: 20px;"> 
                     <?php } else if($category['id']=='All'){?>
                    
                     <?php } else {?>
                     <img class="image-icon"src="<?php echo \Yii::$app->request->BaseUrl; ?>/images/no-image.png">
                     <?php } ?>
                     <?php echo $category['title']?></span><i class="fa fa-navicon"></i></a>
                     <ul class="dropdown-menu category-change " id="category-change" style="display: none;">
                        <li><a href="#" data-cat-type="all"><i class="fa fa-th"></i>All Categories</a></li>
                        
                        <?php foreach($categories as $key=>$category){?> 
                        <?php $cat_image = AppHelper::getCategoryImage($category['id']);?>
                        <li>
                          <a href="#" data-ajax-id="<?php echo $category['id']?>" data-cat-type="maincat">
                            <?php if(isset($cat_image) && !empty($cat_image)){?>
                           <img src="<?php echo $cat_image?>" style="width: 20px;"> 
                           <?php } else {?>
                           <img src="<?php echo \Yii::$app->request->BaseUrl; ?>/images/no-image.png"  style="width: 20px;">	
                           <?php } ?>
                          <?php echo $category['title']?>                       
                          </a>
                          <?php $sub_categories = AppHelper::getSubCategory($category['id']);?>
                           <ul>
                              <?php foreach($sub_categories as $key=>$sub_cat){?> 
                              <li><a href="#" data-ajax-id="<?php echo $sub_cat['id']?>" data-cat-type="subcat"><?php echo $sub_cat['title']?></a></li>
                              <?php } ?>
                           </ul>
                        </li>
                        <?php } ?>
                     </ul>
                  </div>
               </div>
               <div class="col-md-3">
                    <?php if(isset($params['keywords'])){?>
                  <input type="text" class="form-control" name="keywords" id="keywords" value="<?php echo $params['keywords']?>" placeholder="What ?">
                  <?php } else {?>
					  <input type="text" class="form-control" name="keywords" id="keywords" value="" placeholder="What ?">
				  <?php } ?>
               </div>
               <div class="col-md-3 banner-icon"><i class="fa fa-map-marker"></i>
                  <input type="text" class="form-control location" id="searchStateCity" name="location" data-toggle="modal" data-target="#city_slct_modal" placeholder="<?php echo Yii::t('frontend', 'Where?')?>">
               </div>
                <input type="hidden" name="latitude" id="latitude" value="">
			    <input type="hidden" name="longitude" id="longitude" value="">
			    <input type="hidden" name="placetype" id="searchPlaceType" value="">
			    <input type="hidden" name="placeid" id="searchPlaceId" value="">
                <input type="hidden" id="input-maincat" name="cat" value=""/>	 
                <input type="hidden" id="input-subcat" name="subcat" value=""/>	
                <input type="hidden" id="input-subcat" name="username" value="<?php echo $userId?>"> 
               <div class="col-md-2">
                   <button data-ajax-response="map" type="submit" name="Submit" class="form-control bt_blue"><i class="fa fa-search"></i>  <?php echo Yii::t('frontend', 'Search')?></button>
               </div>
            </div>
         
         
         <?php ActiveForm::end(); ?>
         </div>
      </div>
      <!-- Inner Search section End -->
      <div class="row">
         <div class="col-lg-2 d-none d-lg-block text-center">
            <?php 
               $left_ad =  AppHelper::getAdsByPosition('left_sidebar');
               ?>
            <div class="advertisement" id="quickad-left"> 
               <?php echo  $left_ad['code_large_format'] ?>
            </div>
         </div>
         <div class="col-lg-8">
            <?php if(!empty($myads)){?>
            <div class="section product_section ">
               <div class="row">
                  <div class="col-sm-12">
                     <div class="section-title featured-top"><h4><?php echo Yii::t('frontend', 'All Ad') ?></h4></div>
                     <div class="featured_slider h_products">
                        <div class="row">
                           <?php foreach($myads as $key=>$myad){?>
                               <div class="col-12 col-sm-6 col-md-4">
                                  <div class="sad_block">
                                     <div class="sad_container">
                                        <?php if($myad['ads_images']){
                                            $media_id = $myad['ads_images'][0]['media_id'];
                                            $image = MediaUpload::getImageByMediaId($media_id); ?>
                                            <div class="item-image-box">
                                               <a href="">
                                               <img src="<?php echo $image['url'].'/'. $image['upload_base_path'].'/'.$image['file_name']?>">
                                               </a>
                                            </div>
                                        <?php } else { ?>
                                             <a href="">
                                                 <img src="<?php echo \Yii::$app->request->BaseUrl; ?>/images/no-image.jpg">
                                             </a>
                                        <?php } ?>
                                      <div class="ad_info">
                                           <h4> <?php echo Html::a($myad['title'], ['/user/ad-detail/'.$myad['slug']], ['class' => '']); ?> </h4>
                                           <ol class="breadcrumb">
                                              <?php foreach ($myad['category_id'] as $category){?>
                                              <?php $cat_name = Categories::getCategoryNameById($category['cat_id']);?>
                                              <li><a href=""><?php echo  $cat_name['title']?></a></li>
                                              <?php } ?>
                                           </ol>
                                           <ul class="item-details">
                                              <li><i class="fa fa-map-marker"></i><a href="javascript:void(0)"><?php echo $myad['city_name']?></a></li>
                                              <?php
                                                 $post_time =  AppHelper::getPostTime($myad['created_at']);
                                                 ?>
                                              <li><i class="fa fa-clock-o"></i><?php echo $post_time;?></li>
                                           </ul>
                                           <?php
                                              $wishlist = Olx::wishlistData($userId, $myad['id']);
                                               ?>
                                           <div class="ad_meta">
                                              <span class="item_price"> <?php echo $myad['price']?>  ₹ </span>
                                              <ul class="contact-options pull-right" id="set-favorite">
                                                 <?php if($userId){?>
                                                 <?php if(isset($wishlist['id']) && !empty($wishlist['id']) ){?>
                                                 <li><a name="wishlist_btn" href="javascript:void(0)" attrId="<?php echo $myad['id']?>" class="fa fa-heart last_icn wishBtnManageColor wishlist_btn_red " title="set favorite"></a></li>
                                                 <?php } else {?>
                                                 <li><a name="wishlist_btn" id="wishlist_btn" href="javascript:void(0)" attrId="<?php echo $myad['id']?>" class="fa fa-heart wishlist_btn" title="set favorite"></a></li>
                                                 <?php } ?>
                                                 <?php } else {?>
                                                 <li><a href="<?php echo  Yii::$app->urlManager->createAbsoluteUrl(['login']);  ?>" class="fa fa-heart" title="set favorite"></a></li>
                                                 <?php } ?>
                                              </ul>
                                           </div>
                                        </div>
                                  </div>
                               </div>
                               </div>
                           <?php } ?>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
            <?php } ?>
         </div>
         <div class="col-lg-2 d-none d-lg-block hidden-sm text-center">
            <?php 
               $right_ad =  AppHelper::getAdsByPosition('right_sidebar');
               ?>
            <div class="advertisement" id="quickad-right"> 
               <?php echo $right_ad['code_large_format']?>
            </div>
         </div>
      </div>
   </div>
</section>
<!-- city popup index start -->
<div class="modal fade cty_mdl_slf" id="city_slct_modal" tabindex="-1" role="dialog" aria-labelledby="selectCountryLabel"
   aria-hidden="true">
   <div class="modal-dialog modal-lg cstm_mdl_dlg" id="countryModal">
      <div class="modal-content">
         <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
         </div>
         <div class="modal-body quick-states" id="country-popup">
            <div class="mdl_cty_mn">
               <div class="mdl_cty_hd">
                  <input type="text" name="" id="inputStateCity" class="form-control light cityfield ca2" value="" placeholder="Type your city name">
                  <a href="javascript:void(0)"><span class="flag flag-in"></span> &nbsp;<?php echo Yii::t('frontend', 'Change Country')?></a>
                  <div id="searchDisplay" style="display:none;"></div>
               </div>
               <!--<div class="mdl_cty_ppl_ct">
                  <h4>Popular cities :</h4>
                  <ul>
                     <li>
                        <a href="#">Mumbai</a>
                     </li>
                     <li>
                        <a href="#">Jaipur</a>
                     </li>
                     <li>
                        <a href="#">Delhi</a>
                     </li>
                     <li>
                        <a href="#">Goa</a>
                     </li>
                     <li>
                        <a href="#">Mumbai</a>
                     </li>
                     <li>
                        <a href="#">Jaipur</a>
                     </li>
                     <li>
                        <a href="#">Delhi</a>
                     </li>
                     <li>
                        <a href="#">Surat</a>
                     </li>
                     <li>
                        <a href="#">Mumbai</a>
                     </li>
                     <li>
                        <a href="#">Jaipu</a>
                     </li>
                     <li>
                        <a href="#">Delhi</a>
                     </li>
                     <li>
                        <a href="#">Goa</a>
                     </li>
                  </ul>
                  </div>-->
               <div class="btm_stts_mn">
                  <div class="viewport">
                     <div class="row full" id="getCities">
                        <div class="col-sm-12 col-md-12 loader" style="display: none"></div>
                        <div id="results" class="animate-bottom">
                           <ul class="column col-md-12 col-sm-12 cities">
                              <li class="active">
                                 <a href="#" class="selectme" data-id="in" data-name="All India" data-type="country">All india<i class="fa fa-angle-right" aria-hidden="true"></i></a>
                              </li>
                              <?php foreach($states as $key=>$state){?>
                              <li>
                                 <a href="javascript:void(0)" id="<?php echo $state['id']?>"  data-id="<?php echo $state['id']?>" class="statedata"data-name="<?php echo $state['name']?>"><?php echo $state['name']?><i class="fa fa-angle-right" aria-hidden="true"></i></a>
                              </li>
                              <?php } ?>
                           </ul>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
         <div class="modal-footer">
         </div>
      </div>
   </div>
</div>
<!-- city popup index end -->
<style>
   ul.social_share li a .fa{padding-top:7px!important}
</style>
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.1/jquery.min.js"></script>
<script>
   $(document).ready(function(){
       // Show hide popover
       $(".category-dropdown a").click(function(){
           $("#category-change").slideToggle("fast");
       });
   });
   
   
</script>
