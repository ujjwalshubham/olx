<?php
   use common\components\SidenavWidget; 
   use common\components\ProfileheaderWidget; 
   
   use yii\helpers\Html;
   use yii\widgets\ActiveForm;
   use yii\web\View;
   use yii\helpers\Url;
   use kartik\file\FileInput;
   use common\models\Categories;
   use common\models\Cities;
   use common\models\MediaUpload;
   use common\components\AppHelper;
   
   $this->title = Yii::t('frontend', 'Ad Detail') . ' | OLX';
   
   $userId = Yii::$app->user->identity['id'];
   
 
   
   ?>
   <link rel="stylesheet" href="//netdna.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css">
   <style>
   div.stars {
 
  display: inline-block;
}

input.star { display: none; }

label.star {
  float: right;
  padding: 10px;
  font-size: 36px;
  color: #444;
  transition: all .2s;
}

input.star:checked ~ label.star:before {
  content: '\f005';
  color: #FD4;
  transition: all .25s;
}



input.star-1:checked ~ label.star:before { color: #F62; }

label.star:hover { transform: rotate(-15deg) scale(1.3); }

label.star:before {
  content: '\f006';
  font-family: FontAwesome;
}
   
   </style>
   
<section id="main" class="category-page mid_container">
   <div class="container">
      <!-- breadcrumb -->
      <div class="breadcrumb-section">
         <ol class="breadcrumb">
            <li><a href="<?php echo \Yii::$app->request->BaseUrl; ?>"><i class="fa fa-home"></i> <?php echo Yii::t('frontend', 'Home')?></a></li>
            <li>Home &amp; Lifestyle </li>
            <div class="ml-auto back-result"><a href=""><i class="fa fa-angle-double-left"></i>
               <?php echo Yii::t('frontend', 'back to results')?></a>
            </div>
         </ol>
      </div>
      <!-- breadcrumb -->
      <!-- Inner Search section start -->
      <div class="section inner_p_search">
         <div id="ListingForm">
            <div class="row">
               <div class="col-md-4">
                  <div class="dropdown category-dropdown">
                     <a data-toggle="dropdown" href="#"><span class="change-text"> Home &amp; Lifestyle</span><i class="fa fa-navicon"></i></a>
                  </div>
               </div>
               <div class="col-md-3">
                  <input type="text" class="form-control" name="keywords" value="" placeholder="What ?">
               </div>
               <div class="col-md-3 banner-icon"><i class="fa fa-map-marker"></i>
                  <input type="text" class="form-control location"  placeholder="Where ?">
               </div>
               <div class="col-md-2">
                  <button data-ajax-response="map" type="submit" name="Submit" class="form-control bt_blue"><i class="fa fa-search"></i> Search</button>
               </div>
            </div>
         </div>
      </div>
      <!-- Inner Search section End -->
      <div class="section product_section ">
         <div class="row">
            <div class="col-md-8">
               <div class="ad-details">
                  <h1 class="title"><?php echo $adDetail['title']?></h1>
                  <?php
                     $post_time =  AppHelper::getPostTime($adDetail['created_at']);
                     ?>
                  <span class="icon"><i class="fa fa-clock-o"></i><a href="#"><?php echo $post_time?></a></span>
                  <?php $city = Cities::getCityById($adDetail['city']);?>
                  <span class="icon"><i class="fa fa-map-marker"></i><a href="#"><?php echo $city['name']?></a></span>
                  <span class="icon"><i class="fa fa-eye"></i><a href="#"><?php echo Yii::t('frontend', 'Ad Views')?>:1.6k</a></span>
                  <span> <?php echo Yii::t('frontend', 'Ad ID')?>:<a href="#" class="time"> <?php echo $adDetail['id']?></a></span>
               </div>
               <div class="pd_slider">
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
                        <i class="nav-link" data-toggle="tab" href="#menu1"><?php echo Yii::t('frontend', 'Reviews')?> (0)</i>
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
                              <li>
                                 <div class="inner clearfix"><span class="label"><?php echo Yii::t('frontend', 'Ad Views')?></span><span class="desc">1.6k</span></div>
                              </li>
                           </ul>
                        </div>
                        <div class="description">
                           <div class="detail-title">
                              <h2 class="title-left"><?php echo Yii::t('frontend', 'Additionals Details')?></h2>
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
                        <div class="reviewbox">
                           <div class="reviewimgbox"><img src="<?php echo \Yii::$app->request->BaseUrl; ?>/images/products/product3.jpg" alt="img"></div>
                           <div class="reviewbox_detail">
                              <h3>
                                 <i class="fa fa-star active" aria-hidden="true"></i><i class="fa fa-star active" aria-hidden="true"></i><i class="fa fa-star active" aria-hidden="true"></i><i class="fa fa-star active" aria-hidden="true"></i><i class="fa fa-star active" aria-hidden="true"></i>
                                 <span>30 November -0001</span>
                              </h3>
                              <span>
                                 by 
                                 <name> Demo User</name>
                              </span>
                              Lorem Ipsum
                           </div>
                        </div>
                        
                        
                        
                        
                        <div class="review_area">
                           <h4>There are currently no reviews for this product.</h4>
                           <h3>Add Your Revies </h3>
                           <p>How would you rate this product?</p>
                          
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
						
                           <div class="row">
                              <div class="col-sm-12 form-group">
                                 <label><sup class="text-red">*</sup> Reviews</label>
                                 <textarea class="form-control" placeholder="Your Review"></textarea>
                              </div>
                              <div class="col-sm-12 form-group">
                                 <input class="btn btn-outline" type="submit" value="Submit Review"/>
                              </div>
                           </div>
                        </div>
                        
                        
                        
                        
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
                              <a href="user-profile.html"><img  src="<?php echo \Yii::$app->request->BaseUrl; ?>/images/Demo_46307128644.png" alt="Demo"></a>
                           </div>
                           <h4><a href="user-profile.html"> Demo User </a>
                           </h4>
                           <p><strong>Joined: </strong><a href="#">2 years ago</a></p>
                        </div>
                        <!-- short-info -->
                        <!-- contact-advertiser -->
                        <div class="contact-advertiser">
                           <a class="modal-trigger btn btn-warning" href="#loginPopUp"><i class="fa fa-comment-o"></i>Login to chat</a>
                           <a href="#" class="btn btn-info bt_blue"><i class="fa fa-envelope"></i>Reply by email</a>
                        </div>
                        <!-- contact-advertiser -->
                        <!-- social-links -->
                        <div class="social-links text-center">
                           <h4><?php echo Yii::t('frontend', 'Share this Ad')?></h4>
                           <div class="social-share jssocials">
                              <div class="jssocials-shares">
                                 <div class="jssocials-share jssocials-share-email">
                                    <a href="" class="jssocials-share-link"><i class="fa fa-at jssocials-share-logo"></i></a>
                                 </div>
                                 <div class="jssocials-share jssocials-share-twitter"><a  href=""  class="jssocials-share-link"><i class="fa fa-twitter jssocials-share-logo"></i></a></div>
                                 <div class="jssocials-share jssocials-share-facebook"><a  href=""  class="jssocials-share-link"><i class="fa fa-facebook jssocials-share-logo"></i></a></div>
                                 <div class="jssocials-share jssocials-share-googleplus"><a href="" class="jssocials-share-link"><i class="fa fa-google jssocials-share-logo"></i></a></div>
                                 <div class="jssocials-share jssocials-share-linkedin"><a href="" class="jssocials-share-link"><i class="fa fa-linkedin jssocials-share-logo"></i></a></div>
                                 <div class="jssocials-share jssocials-share-pinterest"><a href="" class="jssocials-share-link"><i class="fa fa-pinterest jssocials-share-logo"></i></a></div>
                                 <div class="jssocials-share jssocials-share-whatsapp"><a href="" class="jssocials-share-link"><i class="fa fa-whatsapp jssocials-share-logo"></i></a></div>
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
                                 <p><strong>0</strong> average based on <strong>0</strong> Reviews.</p>
                                 <div class="rating-passive" data-rating="0">
                                    <span class="stars">
                                       <figure class="fa fa-star-o"></figure>
                                       <figure class="fa fa-star-o"></figure>
                                       <figure class="fa fa-star-o"></figure>
                                       <figure class="fa fa-star-o"></figure>
                                       <figure class="fa fa-star-o"></figure>
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
                           <ul id="set-favorite">
                              <li><a href="#" class="fav_464 fa fa-heart "><span >Save ad as Favorite</span></a>
                              </li>
                           </ul>
                           <ul>
                              <li><i class="fa fa-user-plus"></i><a href="user-profile.html">More ads by<span>Demo</span><span class="uf"> View Profile </span></a></li>
                              <li><i class="fa fa-exclamation-triangle"></i><a href="">Report this ad</a></li>
                           </ul>
                           <!-- social-icon -->
                        </div>
                     </div>
                  </div>
                  <!-- short-info -->
                  <!-- Advertise-Box -->
                  <div class="quickad-section" id="quickad-right">
                     <div class="text-center visible-md visible-lg">
                        <div class="text-center d-none d-lg-block hidden-sm visible-md "><a href=""><img src="<?php echo \Yii::$app->request->BaseUrl; ?>/images/right.jpg"></a></div>
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
                  <h4>Premium Ad</h4>
               </div>
               <div class="featured_slider">
                  <div class="slider detail_ad_slider">
                     <div>
                        <div class="sad_block">
                           <div class="sad_container">
                              <div class="item-image-box">
                                 <a href=""><img src="<?php echo \Yii::$app->request->BaseUrl; ?>/images/products/audi-1-760x410.jpg"></a>
                              </div>
                              <div class="ad_info">
                                 <h4><a href="">Sports Car</a></h4>
                                 <ol class="breadcrumb">
                                    <li><a href="">Cars &amp; Bikes</a></li>
                                 </ol>
                                 <ul class="item-details">
                                    <li><i class="fa fa-map-marker"></i><a href="">Ranchi</a></li>
                                    <li><i class="fa fa-clock-o"></i>1 month ago</li>
                                 </ul>
                                 <div class="ad_meta">
                                    <span class="item_price"> 2,500 ₹ </span> 
                                    <ul class="contact-options pull-right" id="set-favorite">
                                       <li><a href="#" data-item-id="2722" data-userid="" data-action="setFavAd" class="fav_2722 fa fa-heart "></a></li>
                                    </ul>
                                 </div>
                              </div>
                           </div>
                        </div>
                     </div>
                     <div>
                        <div class="sad_block">
                           <div class="sad_container">
                              <div class="item-image-box">
                                 <a href=""><img src="<?php echo \Yii::$app->request->BaseUrl; ?>/images/products/product1.jpg"></a>
                              </div>
                              <div class="ad_info">
                                 <h4><a href="">Sports Car</a></h4>
                                 <ol class="breadcrumb">
                                    <li><a href="">Cars &amp; Bikes</a></li>
                                 </ol>
                                 <ul class="item-details">
                                    <li><i class="fa fa-map-marker"></i><a href="">Ranchi</a></li>
                                    <li><i class="fa fa-clock-o"></i>1 month ago</li>
                                 </ul>
                                 <div class="ad_meta">
                                    <span class="item_price"> 2,500 ₹ </span> 
                                    <ul class="contact-options pull-right" id="set-favorite">
                                       <li><a href="#" data-item-id="2722" data-userid="" data-action="setFavAd" class="fav_2722 fa fa-heart "></a></li>
                                    </ul>
                                 </div>
                              </div>
                           </div>
                        </div>
                     </div>
                     <div>
                        <div class="sad_block">
                           <div class="sad_container">
                              <div class="item-image-box">
                                 <a href=""><img src="<?php echo \Yii::$app->request->BaseUrl; ?>/images/products/default.jpg"></a>
                              </div>
                              <div class="ad_info">
                                 <h4><a href="">Sports Car</a></h4>
                                 <ol class="breadcrumb">
                                    <li><a href="">Cars &amp; Bikes</a></li>
                                 </ol>
                                 <ul class="item-details">
                                    <li><i class="fa fa-map-marker"></i><a href="">Ranchi</a></li>
                                    <li><i class="fa fa-clock-o"></i>1 month ago</li>
                                 </ul>
                                 <div class="ad_meta">
                                    <span class="item_price"> 2,500 ₹ </span> 
                                    <ul class="contact-options pull-right" id="set-favorite">
                                       <li><a href="#" data-item-id="2722" data-userid="" data-action="setFavAd" class="fav_2722 fa fa-heart "></a></li>
                                    </ul>
                                 </div>
                              </div>
                           </div>
                        </div>
                     </div>
                     <div>
                        <div class="sad_block">
                           <div class="sad_container">
                              <div class="item-image-box">
                                 <a href=""><img src="<?php echo \Yii::$app->request->BaseUrl; ?>/images/products/product1.jpg"></a>
                              </div>
                              <div class="ad_info">
                                 <h4><a href="">Sports Car</a></h4>
                                 <ol class="breadcrumb">
                                    <li><a href="">Cars &amp; Bikes</a></li>
                                 </ol>
                                 <ul class="item-details">
                                    <li><i class="fa fa-map-marker"></i><a href="">Ranchi</a></li>
                                    <li><i class="fa fa-clock-o"></i>1 month ago</li>
                                 </ul>
                                 <div class="ad_meta">
                                    <span class="item_price"> 2,500 ₹ </span> 
                                    <ul class="contact-options pull-right" id="set-favorite">
                                       <li><a href="#" data-item-id="2722" data-userid="" data-action="setFavAd" class="fav_2722 fa fa-heart "></a></li>
                                    </ul>
                                 </div>
                              </div>
                           </div>
                        </div>
                     </div>
                     <div>
                        <div class="sad_block">
                           <div class="sad_container">
                              <div class="item-image-box">
                                 <a href=""><img src="<?php echo \Yii::$app->request->BaseUrl; ?>/images/products/product1.jpg"></a>
                              </div>
                              <div class="ad_info">
                                 <h4><a href="">Sports Car</a></h4>
                                 <ol class="breadcrumb">
                                    <li><a href="">Cars &amp; Bikes</a></li>
                                 </ol>
                                 <ul class="item-details">
                                    <li><i class="fa fa-map-marker"></i><a href="">Ranchi</a></li>
                                    <li><i class="fa fa-clock-o"></i>1 month ago</li>
                                 </ul>
                                 <div class="ad_meta">
                                    <span class="item_price"> 2,500 ₹ </span> 
                                    <ul class="contact-options pull-right" id="set-favorite">
                                       <li><a href="#" data-item-id="2722" data-userid="" data-action="setFavAd" class="fav_2722 fa fa-heart "></a></li>
                                    </ul>
                                 </div>
                              </div>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
</section>
