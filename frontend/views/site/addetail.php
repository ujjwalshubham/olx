<?php
   use common\components\SidenavWidget; 
   use common\components\ProfileheaderWidget; 
   use yii\helpers\Html;
   use yii\widgets\ActiveForm;
   use yii\web\View;
   use yii\helpers\Url;
   use kartik\file\FileInput;
   use common\components\Olx;
   use common\models\Categories;
   use common\models\Cities;
   use common\models\Countries;
   use common\models\UserProfile;
   use common\models\MediaUpload;
   use common\components\AppHelper;
   
   $this->title = Yii::t('frontend', 'Ad Detail') . ' | OLX';
   $userId = Yii::$app->user->identity['id'];
   if (Yii::$app->User->id) {
		$UserProfile = Yii::$app->user->identity->userProfile;
   }
   $baseurl =\Yii::$app->getUrlManager()->getBaseUrl(); 
?> 
<style>
button.close {top: 0!important;}
.label.featured {background-color: #e96b1f;.wishlist_btn_red{color:#fff !important;}}
</style>
<link rel="stylesheet" href="//netdna.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css">
<section id="main" class="category-page mid_container">
   <div class="container">
      <!-- breadcrumb -->
      <div class="breadcrumb-section">
         <ol class="breadcrumb">
            <li><a href="<?php echo \Yii::$app->request->BaseUrl; ?>"><i class="fa fa-home"></i> <?php echo Yii::t('frontend', 'Home')?></a></li>
            <?php foreach ($adDetail['category_id'] as $category){?>
            <?php $cat_name = Categories::getCategoryNameById($category['cat_id']);?>
            <li><?php echo  $cat_name['title']?></li>
            <?php } ?>
            <div class="ml-auto back-result"><a href=""><i class="fa fa-angle-double-left"></i>
               <?php echo Yii::t('frontend', 'back to results')?></a>
            </div>
         </ol>
      </div>
      <!-- breadcrumb -->
      <?php if (Yii::$app->session->hasFlash('success')){ ?>
      <div class="modal fade" id="myModal" role="dialog">
         <div class="modal-dialog">
            <div class="modal-content">
               <div class="modal-header header-modal">
                  <button type="button" class="close" data-dismiss="modal" aria-label=""><span>×</span></button>
               </div>
               <div class="modal-body">
                  <div class="thank-you-pop">
                     <img src="http://goactionstations.co.uk/wp-content/uploads/2017/03/Green-Round-Tick.png" alt="">
                     <h1><?php echo Yii::t('frontend', 'Success!')?></h1>
                     <p><?php echo Yii::t('frontend', 'Your advertise successfully uploaded.Please wait for approval.')?></p>
                     <p><?php echo Yii::t('frontend', 'Thanks')?></p>
                  </div>
               </div>
            </div>
         </div>
      </div>
      <?php } ?>
      <?php
         $advertiser = $adDetail['user_id'];
         $advertiser_detail = UserProfile::getUserDetail($advertiser);
         ?>
      <div class="section product_section ">
         <div class="row">
            <div class="col-md-8">
               <div class="ad-details">
                  <h1 class="title"><?php echo $adDetail['title']?></h1>
                  <?php
                     $post_time =  AppHelper::getPostTime($adDetail['created_at']);
                     ?>
                  <span class="icon"><i class="fa fa-clock-o"></i><a href="javascript:void(0);"><?php echo $post_time?></a></span>
                  <?php $city = Cities::getCityById($adDetail['city']);?>
                  <?php $country = Countries::getCountryById($adDetail['country']);?>
                  <span class="icon"><i class="fa fa-map-marker"></i><a href="javascript:void(0);"><?php echo $city['name']?>, <?php echo $country['name']?></a></span>
                  <span class="icon"><i class="fa fa-eye"></i><a href="javascript:void(0);"><?php echo Yii::t('frontend', 'Ad Views')?>:<?php echo $ad_views_count?></a></span>
                  <span> <?php echo Yii::t('frontend', 'Ad ID')?>:<a href="javascript:void(0);" class="time"> <?php echo $adDetail['id']?></a></span>
                  <?php if($adDetail['ad_type']=='featured'){?>
                  <span class="featured_bg ad_label"><?php echo $adDetail['ad_type']?></span>
                  <?php } else if($adDetail['ad_type']=='highlight'){?>
                  <span class="hightlight_bg ad_label"><?php echo $adDetail['ad_type']?></span>   
                  <?php } else if($adDetail['ad_type']=='urgent'){?>
                  <span class="urgent_bg ad_label"><?php echo $adDetail['ad_type']?></span> 
                  <?php } ?>
               </div>
               <div class="pd_slider">
                  <?php if(isset($adDetail['price']) && !empty($adDetail['price'])){?>
                  <div class="ribbon ribbon-clip ribbon-reverse"><span class="ribbon-inner"><?php echo $adDetail['price']?> ₹</span></div>
                  <?php } ?>
                  <div class="slider slider-for">
                     <?php foreach($adDetail['ads_images'] as $key=>$images){?>
                     <?php $image = MediaUpload::getImageByMediaId($images['media_id']);?>
                     <?php //echo "<pre>"; print_r($image);exit;?>
                     <div>
                        <img src="<?php echo $image['url'].'/'. $image['upload_base_path'].'/'.$image['file_name']?>" style="height:717px;">
                     </div>
                     <?php } ?>
                  </div>
                  <div class="slider slider-nav">
                     <?php foreach($adDetail['ads_images'] as $key=>$images){?> 
                     <?php $image = MediaUpload::getImageByMediaId($images['media_id']);?>
                     <div>
                        <img src="<?php echo $image['url'].'/'. $image['upload_base_path'].'/'.$image['file_name']?>">
                     </div>
                     <?php } ?>
                  </div>
               </div>
               <div class="product_tabarea">
                  <ul class="nav nav-tabs" role="tablist">
                     <li class="nav-item">
                        <i class="nav-link active" data-toggle="tab" href="#home"><?php echo Yii::t('frontend', 'Ads Details')?></i>
                     </li>
                     <li class="nav-item">
                        <i class="nav-link" data-toggle="tab" href="#menu1"><?php echo Yii::t('frontend', 'Reviews')?> (<?php echo $review_count?>)</i>
                     </li>
                  </ul>
                  <div class="tab-content">
                     <div id="home" class="container tab-pane active">
                        <div class="quick-info">
                           <div class="detail-title">
                              <h2 class="title-left"><?php echo Yii::t('frontend', 'Additionals Details')?></h2>
                           </div>
                           <ul class="clearfix">
                              <li>
                                 <div class="inner clearfix"><span class="label"><?php echo Yii::t('frontend', 'Ad ID')?></span><span class="desc"><?php echo $adDetail['id']?></span></div>
                              </li>
                              <li>
                                 <div class="inner clearfix"><span class="label"><?php echo Yii::t('frontend', 'Posted On')?></span><span class="desc"><?php echo $post_time?></span></div>
                              </li>
                              <?php if(isset($adDetail['tags']) && !empty($adDetail['tags'])){?>
                              <li>
                                 <div class="inner clearfix"><span class="label"><?php echo Yii::t('frontend', 'tags')?></span><span class="desc"><?php echo $adDetail['tags'];?></span></div>
                              </li>
                              <?php }?>
                              <?php if(!empty($adDetail['mobile']) && $adDetail['mobile_hidden']==0){?>
                              <li>
                                 <div class="inner clearfix"><span class="label"><?php echo Yii::t('frontend', 'Mobile')?></span><span class="desc"><?php echo $adDetail['mobile'];?></span></div>
                              </li>
                              <?php }?>
                              <li>
                                 <div class="inner clearfix"><span class="label"><?php echo Yii::t('frontend', 'Ad Views')?></span><span class="desc"><?php echo $ad_views_count?></span></div>
                              </li>
                              <?php if(isset($adDetail['price']) && !empty($adDetail['price'])){?>
                              <li>
                                 <div class="inner clearfix"><span class="label"><?php echo Yii::t('frontend', 'Price')?></span><span class="desc"><?php echo $adDetail['price']?> ₹ <?php if($adDetail['negotiate']==1){?>negotiate<?php } ?></span></div>
                              </li>
                              <?php }?>
                              <?php //echo "<pre>"; print_r($adCustomFields);exit;?>
                              <?php foreach($adCustomFields as $key=>$fields){?>
                              <?php if($fields['custom_fields']['type']=='checkbox'){
                                 $array_values = explode(',',$fields['value']) ;?>
                              <li>
                                 <div class="inner clearfix"><span class="label"><?php echo $fields['custom_fields']['label']?></span><span class="desc">
                                    <?php 
                                       $fieldv = array();
                                       foreach($array_values as $key=>$val){
                                       $custom_field_value = Apphelper::getCustomFieldValue($val); 
                                        $fieldv[] =  $custom_field_value ;
                                       ?>
                                    <?php } echo  implode(', ',$fieldv) ;?></span>
                                 </div>
                              </li>
                              <?php }else if($fields['custom_fields']['type']=='radio' || $fields['custom_fields']['type']=='select'){
                                 $custom_field_value = Apphelper::getCustomFieldValue($fields['value']);  
                                   ?>
                              <li>
                                 <div class="inner clearfix"><span class="label"><?php echo $fields['custom_fields']['label']?></span><span class="desc"><?php echo $custom_field_value?></span></div>
                              </li>
                              <?php } else {?>
                              <li>
                                 <div class="inner clearfix"><span class="label"><?php echo $fields['custom_fields']['label']?></span><span class="desc"><?php echo $fields['value']?></span></div>
                              </li>
                              <?php } } ?>
                           </ul>
                        </div>
                        <div class="description">
                           <div class="detail-title">
                              <h2 class="title-left"><?php echo Yii::t('frontend', 'Description')?></h2>
                           </div>
                           <div class="user-html">
                              <p><?php echo $adDetail['description']?></p>
                           </div>
                        </div>
                        <div class="description">
                           <div class="detail-title">
                              <h2 class="title-left"><?php echo Yii::t('frontend', 'Location')?></h2>
                           </div>
                           <div class="location_map">
                              <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3559.116987958013!2d75.77341231555715!3d26.86802398314681!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x396db5b390edee4d%3A0x179ebce856537028!2sBR%20Softech%20Pvt.%20Ltd!5e0!3m2!1sen!2sin!4v1569655457196!5m2!1sen!2sin" width="100%" height="200" frameborder="0" style="border:0;" allowfullscreen=""></iframe>
                           </div>
                        </div>
                     </div>
                     <div id="menu1" class="container tab-pane fade">
                        <?php if(isset($reviews) && !empty($reviews)){?> 
                        <?php foreach($reviews as $key=>$review){?>
                        <div class="reviewbox">
                           <div class="reviewimgbox">
                              <?php if(!empty($review['avatar'])){?>
                              <img src="<?php echo \Yii::$app->request->BaseUrl.'/'.$review['avatar']?>" alt="<?php echo $review['name']?>">
                              <?php } else {?>
                              <img src="<?php echo \Yii::$app->request->BaseUrl; ?>/images/user-img.png" alt="user" class="img-responsive"> 
                              <?php } ?>
                           </div>
                           <div class="reviewbox_detail">
                              <h3>
                                 <?php for($i=0; $i<$review['rating']; $i++){?>  
                                 <i class="fa fa-star active" aria-hidden="true"></i>
                                 <?php } ?>
                                 <span><?php echo date('d M Y',$review['created_at'])?></span>
                              </h3>
                              <span>
                                 <?php echo Yii::t('frontend', 'by')?> 
                                 <name> <?php echo $review['name']?></name>
                              </span>
                              <?php echo $review['comment']?>
                           </div>
                        </div>
                        <?php }?>
                        <?php } else {?>
                        <div class="reviewbox">
                           <h4><?php echo Yii::t('frontend', 'There are currently no reviews for this Advertisement.')?> </h4>
                        </div>
                        <?php } ?>
                        <?php if(isset($userId)){?>
                        <div class="review_area">
                           <p id="review_success" style="color:#65b165"></p>
                           <h3><?php echo Yii::t('frontend', 'Add Your Review')?> </h3>
                           <p><?php echo Yii::t('frontend', 'How would you rate this product?')?></p>
                           <div class="stars">
                              <input class="star star-5" id="star-5" type="radio" name="rating" value="5"/>
                              <label class="star star-5" for="star-5"></label>
                              <input class="star star-4" id="star-4" type="radio" name="rating" value="4"/>
                              <label class="star star-4" for="star-4"></label>
                              <input class="star star-3" id="star-3" type="radio" name="rating" value="3"/>
                              <label class="star star-3" for="star-3"></label>
                              <input class="star star-2" id="star-2" type="radio" name="rating" value="2"/>
                              <label class="star star-2" for="star-2"></label>
                              <input class="star star-1" id="star-1" type="radio" name="rating" value="1"/>
                              <label class="star star-1" for="star-1"></label>
                           </div>
                           <div class="error_msg_rating error"></div>
                           <div class="row">
                              <div class="col-sm-12 form-group">
                                 <label><sup class="text-red">*</sup> <?php echo Yii::t('frontend', 'Reviews')?></label>
                                 <textarea class="form-control" placeholder="Your Review" id="comment" name="comment"></textarea>
                                 <div class="error_msg_comment error"></div>
                                 <input type="hidden" id="ad_id" name="ad_id" value="<?php echo $adDetail['id']?>">
                                 <input type="hidden" id="user_id" name="user_id" value="<?php echo $userId?>">
                              </div>
                              <div class="col-sm-12 form-group">
                                 <button class="btn btn-outline" id="submit_review"><?php echo Yii::t('frontend', 'Submit Review')?></button>
                              </div>
                           </div>
                        </div>
                        <?php } else {?>
                        <div>
                           <a class="modal-trigger btn btn-primary" data-toggle="modal" data-target="#register-popup" href="javascript:void(0);"><?php echo Yii::t('frontend', 'Login to write review')?>
                           </a>
                        </div>
                        <?php } ?>
                     </div>
                  </div>
               </div>
            </div>
            <div class="col-md-4">
               <div class="ad-details">
                  <div class="aside">
                     <div class="aside-header"><?php echo Yii::t('frontend', 'Contact Advertiser')?></div>
                     <div class="aside-body text-center">
                        <!-- short-info -->
                        <div class="user-info ">
                           <div class="profile-picture">
                              <?php $advertiser_id = $advertiser_detail['id'];
                                 $profile_image = AppHelper::getUserProfileImage($advertiser_id);?>
                              <a href="<?php echo $baseurl.'/user/my-profile'; ?>"><img  src="<?php echo $profile_image; ?>" alt="Demo"></a>
                           </div>
                           <h4>
                              <a href="<?php echo $baseurl.'/user/my-profile'; ?>"> <?php echo $advertiser_detail['name']?> </a>
                           </h4>
                           <?php $post_time =  AppHelper::getPostTime($advertiser_detail['created_at']);?>
                           <p><strong><?php echo Yii::t('frontend', 'Joined')?>: </strong><a href="javascript:void(0)">
                              <?php echo $post_time?></a>
                           </p>
                        </div>
                        <!-- short-info -->
                        <!-- contact-advertiser -->
                        <div class="contact-advertiser">
                           <?php if(isset($userId) && !empty($userId)){?>
                           <a class="modal-trigger btn btn-warning" href="#loginPopUp"><i class="fa fa-comment-o"></i><?php echo Yii::t('frontend', 'Chat Now')?></a>
                           <?php } else {?>
                           <a class="modal-trigger btn btn-warning" data-toggle="modal" data-target="#register-popup" href="#"><i class="fa fa-comment-o"></i><?php echo Yii::t('frontend', 'Login to chat')?></a>
                           <?php } ?>
                           <a href="javascript:void(0);"  data-toggle="modal" data-target="#emailToSeller" class="btn btn-info bt_blue"><i class="fa fa-envelope"></i><?php echo Yii::t('frontend', 'Reply by email')?></a>
                        </div>
                        <!-- contact-advertiser -->
                        <!-- social-links -->
                        <div class="social-links text-center">
                           <h4><?php echo Yii::t('frontend', 'Share this Ad')?></h4>
                           <script type='text/javascript' src='https://platform-api.sharethis.com/js/sharethis.js#property=5d8070f3ab6f1000123c9078&product=sticky-share-buttons' async='async'></script>
                           <div class="social-share jssocials">
                              <div class="jssocials-shares">
                                 <div class="jssocials-share jssocials-share-email"></div>
                                 <div class="sharethis-inline-share-buttons"></div>
                              </div>
                           </div>
                           <!--end social-->
                        </div>
                        <!-- social-links -->
                     </div>
                  </div>
                  <!-- Rating-info -->
                  <div class="aside margin-top-20">
                     <div class="aside-body ">
                        <div class="more-info">
                           <!-- **** Start reviews **** -->
                           <div class="starReviews">
                              <!-- Show average-rating -->
                              <div class="average-rating">
                                 <h4><?php echo Yii::t('frontend', 'Average Rating')?></h4>
                                 <?php if(isset($avg_rating['rating']) && !empty($avg_rating['rating'])){
                                    $avg_rating['rating'] = $avg_rating['rating'];
                                    }else{
                                    $avg_rating['rating'] = 0.00; 
                                    }
                                    
                                     $number = number_format($avg_rating['rating'], 2);
                                     $n = $number;
                                    
                                     $whole = floor($n);
                                     $fraction = $n - $whole;
                                                            ?>
                                 <p><strong><?php echo $avg_rating['rating']?></strong> <?php echo Yii::t('frontend', 'average based on')?> <strong><?php echo $review_count?></strong> <?php echo Yii::t('frontend', 'Reviews')?>.</p>
                                 <div class="rating-passive" data-rating="0">
                                    <span class="stars">
                                       <?php for($i=0; $i<$whole; $i++){?>
                                       <figure class="fa fa-star"></figure>
                                       <?php } ?>
                                       <?php if(($fraction>.50) ||($fraction==.50)){?>
                                       <figure class="fa fa-star-half"></figure>
                                       <?php } ?>
                                    </span>
                                 </div>
                              </div>
                           </div>
                        </div>
                     </div>
                  </div>
                  <!-- Rating-info -->
                  <!-- short-info -->
                  <div class="aside margin-top-20">
                     <div class="aside-body ">
                        <div class="more-info">
                           <h4><?php echo Yii::t('frontend', 'More Info')?></h4>
                           <!-- social-icon -->
                           <?php $wishlist = Olx::wishlistData($userId, $adDetail['id']);?>
                           <ul id="set-favorite" class="set-favourite">
                              <?php if($userId){?>
                              <?php if(isset($wishlist['id']) && !empty($wishlist['id']) ){?>	  
                              <li><a name="wishlist_btn" href="javascript:void(0)" attrId="<?php echo $adDetail['id']?>" class="fa fa-heart fav_464 last_icn wishBtnManageColor redlike wishlist_btn_red  wishlist_btn_red_new" title="set favorite"></a><span ><?php echo Yii::t('frontend', 'Save Ad as favourite')?></span></li>
                              <?php } else {?>
                              <li><a name="wishlist_btn" id="wishlist_btn" href="javascript:void(0)" attrId="<?php echo $adDetail['id']?>" class="fa fa-heart fav_464 wishlist_btn redlike" title="set favorite"></a><span ><?php echo Yii::t('frontend', 'Save Ad as favourite')?></span></li>
                              <?php } ?>
                              <?php } else {?>
                              <li><a href="" data-toggle="modal" data-target="#register-popup" class="fa fa-heart" title="set favorite"></a><span ><?php echo Yii::t('frontend', 'Save Ad as favourite')?></span></li>
                              <?php } ?>
                           </ul>
                           <ul>
                              <li><i class="fa fa-user-plus"></i><a href="<?php echo $baseurl.'/user/profile/'.$advertiser_detail['slug']; ?>"><?php echo Yii::t('frontend', 'More Ads By')?><span><?php echo $advertiser_detail['name']?></span><span class="uf"><?php echo Yii::t('frontend', 'View Profile')?> </span></a></li>
                              <li><i class="fa fa-exclamation-triangle"></i><a href="<?php echo $baseurl.'/report'; ?>"><?php echo Yii::t('frontend', 'Report This ad')?></a></li>
                           </ul>
                           <!-- social-icon -->
                        </div>
                     </div>
                  </div>
                  <!-- short-info -->
                  <!-- Advertise-Box -->
                  <div class="quickad-section" id="quickad-right">
                     <div class="text-center visible-md visible-lg">
                        <?php $right_ad =  AppHelper::getAdsByPosition('right_sidebar');?> 
                        <div class="text-center d-none d-lg-block hidden-sm visible-md ">
                           <?php echo $right_ad['code_large_format']?>
                        </div>
                     </div>
                  </div>
                  <!-- Advertise-Box -->
               </div>
            </div>
         </div>
      </div>
      <div class="section product_section ">
         <div class="row">
            <div class="col-sm-12">
               <div class="section-title featured-top">
                  <h4><?php echo Yii::t('frontend', 'Recommended Ads for You')?></h4>
               </div>
               <div class="featured_slider">
                  <div class="slider detail_ad_slider">
                     <?php if(isset($relatedAds) && !empty($relatedAds)){
                        foreach($relatedAds as $key=>$relatedAd){
                         if($relatedAd['ads_images']){
                        	$media_id = $relatedAd['ads_images']['media_id'];
                        	$image = MediaUpload::getImageByMediaId($media_id);
                          }
                        ?>
                     <div>
                        <div class="sad_block">
                           <div class="sad_container">
                              <div class="item-image-box">
                                 <a href="<?php echo  Yii::$app->urlManager->createAbsoluteUrl(['user/ad-detail/'.$relatedAd['slug']]);  ?>">
                                    <?php if($relatedAd['ads_images']){?>
                                    <img src="<?php echo $image['url'].'/'. $image['upload_base_path'].'/'.$image['file_name']?>" alt="<?php echo $relatedAd['title']?>" title="<?php echo $relatedAd['title']?>">
                                    <?php if($relatedAd['ad_type']=='featured'){?>
                                    <div class="item-image-box-tag featured_bg"><?php echo $relatedAd['ad_type']?></div>
                                    <?php } else if($relatedAd['ad_type']=='highlight'){?>
                                    <div class="item-image-box-tag hightlight_bg"><?php echo $relatedAd['ad_type']?></div>
                                    <?php } else if($relatedAd['ad_type']=='urgent'){?>
                                    <div class="item-image-box-tag urgent_bg"><?php echo $relatedAd['ad_type']?></div>
                                    <?php } } else {?>
                                    <img src="<?php echo \Yii::$app->request->BaseUrl; ?>/images/products/default.jpg" alt="product" title="<?php echo $relatedAd['title']?>"> 
                                    <?php if($relatedAd['ad_type']=='featured'){?>
                                    <div class="item-image-box-tag featured_bg"><?php echo $relatedAd['ad_type']?></div>
                                    <?php } else if($relatedAd['ad_type']=='highlight'){?>
                                    <div class="item-image-box-tag hightlight_bg"><?php echo $relatedAd['ad_type']?></div>
                                    <?php } else if($relatedAd['ad_type']=='urgent'){?>
                                    <div class="item-image-box-tag urgent_bg"><?php echo $relatedAd['ad_type']?></div>
                                    <?php } }?>
                                 </a>
                              </div>
                              <div class="ad_info">
                                 <?php if (strlen($relatedAd['title']) > 18) {?>
                                 <h4> <?php echo Html::a(substr($relatedAd['title'],0,18).'...', ['/user/ad-detail/'.$relatedAd['slug']], ['class' => '']); ?></h4>
                                 <?php } else {?>
                                 <h4> <?php echo Html::a($relatedAd['title'], ['/user/ad-detail/'.$relatedAd['slug']], ['class' => '']); ?></h4>
                                 <?php } ?>
                                 <?php if(isset($relatedAd['category_id']['cat_id']) && !empty($relatedAd['category_id']['cat_id'])){?>
                                 <ol class="breadcrumb">
                                    <?php $cat_name = Categories::getCategoryNameById($relatedAd['category_id']['cat_id']);?>
                                    <li><a href=""><?php echo  $cat_name['title']?></a></li>
                                 </ol>
                                 <?php } ?>
                                 <ul class="item-details">
                                    <?php $city = Cities::getCityById($relatedAd['city']);?>
                                    <li><i class="fa fa-map-marker"></i><a href="javascript:void(0)"><?php echo $city['name']?></a></li>
                                    <?php $post_time =  AppHelper::getPostTime($relatedAd['created_at']); ?>
                                    <li><i class="fa fa-clock-o"></i><?php echo $post_time;?></li>
                                 </ul>
                                 <?php $wishlist = Olx::wishlistData($userId, $relatedAd['id']);?>
                                 <div class="ad_meta">
                                    <?php if(isset($relatedAd['price']) && !empty($relatedAd['price'])){?> 
                                    <span class="item_price"> <?php echo $relatedAd['price']?> ₹ </span> 
                                    <?php }?>
                                    <ul class="contact-options pull-right" id="set-favorite">
                                       <?php if($userId){?>
                                       <?php if(isset($wishlist['id']) && !empty($wishlist['id']) ){?>	  
                                       <li><a name="wishlist_btn" href="javascript:void(0)" attrId="<?php echo $relatedAd['id']?>" class="fa fa-heart last_icn wishBtnManageColor wishlist_btn_red " title="set favorite"></a></li>
                                       <?php } else {?>
                                       <li><a name="wishlist_btn" id="wishlist_btn" href="javascript:void(0)" attrId="<?php echo $relatedAd['id']?>" class="fa fa-heart wishlist_btn" title="set favorite"></a></li>
                                       <?php }}  else {?>
                                       <li><a href="" data-toggle="modal" data-target="#register-popup" class="fa fa-heart" title="set favorite"></a></li>
                                       <?php } ?>
                                    </ul>
                                 </div>
                              </div>
                           </div>
                        </div>
                     </div>
                     <?php } } ?>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
</section>
<div id="emailToSeller" class="modal fade email_formcontent" role="dialog">
   <div class="modal-dialog">
      <!-- Modal content-->
      <div class="modal-content">
         <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title"> <?php echo Yii::t('frontend', 'Send Email To')?> <?php echo $advertiser_detail['name']?> </h4>
         </div>
         <div class="modal-body">
            <p class="success_mail success"></p>
            <div class="login_formarea feed-back-form">
               <form method="post" id="email_contact_seller" action="email_contact_seller">
                  <div class="form-group">
                     <input class="form-control" type="text" id="mail_name" placeholder="<?php echo Yii::t('frontend', 'Full Name')?>" name="name" required="">
                  </div>
                  <div class="error_msg_name error"></div>
                  <div class="form-group">
                     <input class="form-control" type="email" id="mail_email" placeholder="<?php echo Yii::t('frontend', 'Email Address')?>" name="email" required="">
                  </div>
                  <div class="error_msg_email error"></div>
                  <div class="form-group">
                     <input class="form-control" type="text" id="mail_phone" placeholder="<?php echo Yii::t('frontend', 'Phone Number')?>" name="phone" required="">
                  </div>
                  <div class="error_msg_phone error"></div>
                  <span><?php echo Yii::t('frontend', 'Message')?></span>
                  <div class="form-group">
                     <textarea type="text" class="form-control" id="mail_message" name="message" placeholder="<?php echo Yii::t('frontend', 'Enter your message...')?>" required="" rows="2" style="width: 100%;height: 100px"></textarea>
                  </div>
                  <div class="error_msg_message error"></div>
                  <input type="hidden" class="form-control" id="advertise_id" name="id" value="<?php echo $adDetail['id'];?>">
                  <input type="hidden" class="form-control" name="sendemail" value="1">
                  <input type="hidden" class="form-control" name="advertise_title" id="advertise_title" value="<?php echo $adDetail['title']?>">
                  <?php foreach ($adDetail['category_id'] as $key=>$category){?>
                  <?php $cat_name = Categories::getCategoryNameById($category['cat_id']);?>
                  <input type="hidden" class="form-control" name="advertise_cat<?php echo $key?>" id="cat<?php echo$key+1?>" value="<?php echo $cat_name['title']?>">
                  <?php } ?>
                  <div class="form-group login_submit">
                     <button type="button" value="Send Mail" class="btn bt_blue" id="send_mail"> <?php echo Yii::t('frontend','Send Email')?></button>
                  </div>
               </form>
            </div>
         </div>
      </div>
   </div>
</div>

<?php
$jss ="   $('#send_mail').click(function(e) { 
    var name = $('#mail_name').val();
    var email = $('#mail_email').val();
    var phone = $('#mail_phone').val();
    var message = $('#mail_message').val();
    var ad_id = $('#advertise_id').val();
    var cat1 = $('#cat1').val();
    var cat2 = $('#cat2').val();
    var title = $('#advertise_title').val();

    if(name == ''){
     $('.error_msg_name').text('Please Enter name');
     $('.error_msg_email').text('');
     $('.error_msg_phone').text('');
     $('.error_msg_message').text('');
     
    return false;
    }else if(email==''){
     $('.error_msg_name').text('');
     $('.error_msg_email').text('Please Enter Email');
     $('.error_msg_phone').text('');
     $('.error_msg_message').text('');
     
     return false;
    }else if(IsEmail(email)==false){
     $('.error_msg_name').text('');
     $('.error_msg_email').text('Please Enter Valid Email Address');
     $('.error_msg_phone').text('');
     $('.error_msg_message').text('');
     
     return false;
    }else if(phone==''){
     $('.error_msg_name').text('');
     $('.error_msg_email').text('');
     $('.error_msg_phone').text('Please Enter Phone Number');
     $('.error_msg_message').text('');
     
     return false;
    }else if(!$('#mail_phone').val().match('[1-9]{10}')){
     $('.error_msg_name').text('');
     $('.error_msg_email').text('');
     $('.error_msg_phone').text('Please Enter 10 digit phone number');
     $('.error_msg_message').text('');
     
     return false;
    }else if(message==''){
     $('.error_msg_name').text('');
     $('.error_msg_email').text('');
     $('.error_msg_phone').text('');
     $('.error_msg_message').text('Please Enter message');
     return false;
    }else{
   var data = {name: name, email: email,phone:phone,message:message,ad_id:ad_id,user_id:'$userId',title:title,cat1:cat1,cat2:cat2};
   var ajaxurl= $('#uribase').val();
        $.ajax({
                url:  ajaxurl+'/user/replyemail',
                data: data,
                type: 'POST',
                success: function (data) {
                $('#email_contact_seller')[0].reset();
                $('.success_mail').html('Email Sent Successfully');
					setTimeout(function() { 
						$('#emailToSeller').modal('hide');
						$('.success_mail').html(''); 
					}, 3000);
                }
            });
		}
   });
   
   function IsEmail(email) {
        var regex = /^([a-zA-Z0-9_\.\-\+])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
        if(!regex.test(email)) {
           return false;
        }else{
           return true;
        }
    }
    
    $(document).ready(function(){
	 $('#myModal').modal('show'); 
	   setTimeout(function(){
		$('#myModal').modal('hide')
	}, 4000);
	});
   
   $('#submit_review').click(function(e) { 
    var comment = $('#comment').val();
    var ad_id = $('#ad_id').val();
    var user_id = $('#user_id').val();
    var rating =  $('input[name=rating]:checked').val();

    if(rating == undefined){
     $('.error_msg_rating').text('Please Select rating');
     $('.error_msg_comment').text('');
     
    return false;
    }else if(comment==''){
     $('.error_msg_rating').text('');
     $('.error_msg_comment').text('Please Enter Comment');
     
     return false;
    }else{
   var data = {rating: rating, ad_id: ad_id,user_id:user_id,comment:comment};
   var ajaxurl= $('#uribase').val();
        $.ajax({
                url:  ajaxurl+'/user/submit_review',
                data: data,
                type: 'POST',
                dataType: 'json',
                success: function (data) {
                   console.log(data)
                    $('#review_success').html(data);
                    $('.error_msg_rating').text('');
					$('.error_msg_comment').text('');
					$('#comment').val('');
                }
            });
		 }
   });
   ";
 echo $this->registerJs($jss, View::POS_END);
?>

