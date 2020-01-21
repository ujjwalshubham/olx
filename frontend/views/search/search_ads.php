<?php
   use common\components\CategoryWidget;
   use common\models\Categories;
   use common\models\Cities;
   use common\models\MediaUpload;
   use common\components\Olx;
   use common\components\AppHelper;
   use yii\helpers\Html;
   use yii\widgets\ActiveForm;
   use yii\web\View;
   use yii\helpers\Url;
   $userId = Yii::$app->user->identity['id'];
   $categories = Olx::getAllCategory();
   ?>
<section id="main" class="category-page mid_container">
   <div class="container">
      <!-- breadcrumb -->
      <div class="breadcrumb-section">
         <ol class="breadcrumb">
            <li><a href="<?php echo \Yii::$app->request->BaseUrl; ?>"><i class="fa fa-home"></i> <?php echo Yii::t('frontend', 'Home')?></a></li>
            <li><?php echo $category['title'] ?> </li>
            <div class="ml-auto back-result"><a href=""><i class="fa fa-angle-double-left"></i>
               <?php echo Yii::t('frontend', 'Back to Results')?></a>
            </div>
         </ol>
      </div>
      <!-- breadcrumb -->
      <!-- Inner Search section start -->
      <div class="section inner_p_search">
         <div id="ListingForm">
            <div class="row">
               <?php $cat_image = AppHelper::getCategoryImage($category['id']);
                  ?>
               <div class="col-md-4">
                  <div class="dropdown category-dropdown open">
                     <a data-toggle="dropdown" href="#" aria-expanded="true"><span class="change-text">
                     <?php if(isset($cat_image) && !empty($cat_image)){?>
                     <img src="<?php echo $cat_image?>" style="width: 20px;"> 
                     <?php } else {?>
                     <img src="<?php echo \Yii::$app->request->BaseUrl; ?>/images/no-image.png">
                     <?php } ?>
                     <?php echo $category['title']?></span><i class="fa fa-navicon"></i></a>
                     <ul class="dropdown-menu category-change " id="category-change">
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
               <input type="hidden" id="input-maincat" name="cat" value=""/>
               <input type="hidden" id="input-subcat" name="subcat" value=""/>
               <div class="col-md-3">
                  <input type="text" class="form-control" name="keywords" value="" placeholder="What ?">
               </div>
               <div class="col-md-3 banner-icon">
                  <i class="fa fa-map-marker"></i>
                  <div class="form-group live-location-search">
                     <input type="text" class="form-control location" id="searchStateCity" name="location" data-toggle="modal" data-target="#city_slct_modal" placeholder="<?php echo Yii::t('frontend', 'Where?')?>">
                     <input type="hidden" name="latitude" id="latitude" value="">
                     <input type="hidden" name="longitude" id="longitude" value="">
                     <input type="hidden" name="placetype" id="searchPlaceType" value="">
                     <input type="hidden" name="placeid" id="searchPlaceId" value="">
                     <input type="hidden" id="input-keywords" name="keywords" value="">
                     <input type="hidden" id="input-maincat" name="cat" value=""/>
                     <input type="hidden" id="input-subcat" name="subcat" value=""/>
                  </div>
               </div>
               <div class="col-md-2">
                  <button data-ajax-response="map" type="submit" name="Submit" class="form-control bt_blue"><i class="fa fa-search"></i>  <?php echo Yii::t('frontend', 'Search')?></button>
               </div>
               <!--<div class="col-md-6">
                  <div class="form-group live-location-search">
                  <label for="city" class="font-weight-bold"><?php echo Yii::t('frontend', 'Where')?>  </label>
                  <div data-option="no" class="loc-tracking"><i class="fa fa-crosshairs"></i></div>
                  
                  <input type="text" class="form-control border-0" id="searchStateCity" name="location" data-toggle="modal" data-target="#city_slct_modal" placeholder="<?php echo Yii::t('frontend', 'Your City')?>">
                  
                  <input type="hidden" name="latitude" id="latitude" value="">
                  <input type="hidden" name="longitude" id="longitude" value="">
                  <input type="hidden" name="placetype" id="searchPlaceType" value="">
                  <input type="hidden" name="placeid" id="searchPlaceId" value="">
                  <input type="hidden" id="input-keywords" name="keywords" value="">
                  <input type="hidden" id="input-maincat" name="cat" value=""/>
                  <input type="hidden" id="input-subcat" name="subcat" value=""/>
                  
                  <button type="button" class="btn btn-primary ml-auto search_btn_click">
                  <i class="fa fa-search"></i>
                  <span class="align-middle ml-2 dn-text-sm"><?php echo Yii::t('frontend', 'Search')?></span>
                  </button>
                  </div>
                  </div>-->
            </div>
         </div>
      </div>
      <!-- Inner Search section End -->
      <div class="row recommended-ads">
         <div class="col-sm-3 col-md-3">
            <div class="tg-sidebartitle">
               <h2> <?php echo Yii::t('frontend', 'Advance Search')?>:</h2>
            </div>
            <div id="custom-field-block" class="section ">
               <div id="ResponseCustomFields">
                  <div class="form-group">
                     <label class="label-title"><?php echo Yii::t('frontend', 'Ad Type')?></label><br>
                     <span>
                     <input type="radio" checked="" name="radio-group" id="test1">
                     <label for="test1">Yes</label>
                     </span>
                     <span>
                     <input type="radio" checked="" name="radio-group" id="test2">
                     <label for="test2">No</label>
                     </span>
                  </div>
                  <div class="inner">
                     <div class="form-group">
                        <label class="label-title"><?php echo Yii::t('frontend', 'Price')?></label>
                        <div class="range-widget">
                           <div class="range-inputs">
                              <input type="text" placeholder="From" name="range1" value="">
                              <input type="text" placeholder="To" name="range2" value="">
                           </div>
                           <button type="submit" class="bt_blue"><i class="fa fa-search"></i></button>
                        </div>
                     </div>
                  </div>
                  <button type="submit" name="Submit" class="btn tg-btn bt_blue" id="advance-search-btn" style="padding: 0 40px;"><?php echo Yii::t('frontend', 'Advance Search')?></button>
               </div>
            </div>
         </div>
         <div class="col-sm-9">
            <div class="section product_section ">
               <div class="row">
                  <div class="col-sm-12">
                     <div class="section-title featured-top">
                        <h4>  <?php echo Yii::t('frontend', 'All Ads')?></h4>
                     </div>
                     <div class="featured_slider h_products">
                        <?php if(isset($allAds) && !empty($allAds)){?>  
                        <div class="row">
                           <?php foreach($allAds as $key=>$myad){?>
                           <div class="col-12 col-sm-6 col-md-4 ">
                              <div class="sad_block">
                                 <div class="sad_container">
                                    <div class="item-image-box">
                                       <?php if($myad['ads_images']){?>
                                       <a href="<?php echo \Yii::$app->request->BaseUrl.'/user/ad-detail/'.$myad['slug'];?>"><img src="<?php echo \Yii::$app->request->BaseUrl.'/'. $myad['ads_images'][0]['upload_base_path'].'/'.$myad['ads_images'][0]['file_name']?>" alt="product" title="product"></a>
                                       <?php } else{?>
                                       <a href="<?php echo \Yii::$app->request->BaseUrl.'/user/ad-detail/'.$myad['slug'];?>"><img src="<?php echo \Yii::$app->request->BaseUrl; ?>/images/no-image.jpg">	</a>  
                                       <?php } ?>
                                    </div>
                                    <div class="ad_info">
                                       <h4> <?php echo Html::a($myad['title'], ['/user/ad-detail/'.$myad['slug']], ['class' => '']); ?></h4>
                                       <ol class="breadcrumb">
                                          <?php foreach ($myad['category_id'] as $category){?>
                                          <?php $cat_name = Categories::getCategoryNameById($category['cat_id']);?>
                                          <li><a href=""><?php echo  $cat_name['title']?></a></li>
                                          <?php } ?>
                                       </ol>
                                       <ul class="item-details">
                                          <li><i class="fa fa-map-marker"></i><a href=""><?php echo $myad['city_name']?></a></li>
                                          <?php
                                             $post_time =  AppHelper::getPostTime($myad['created_at']);
                                             ?>
                                          <li><i class="fa fa-clock-o"></i><?php echo $post_time?></li>
                                       </ul>
                                       <?php
                                          $wishlist = Olx::wishlistData($userId, $myad['id']);
                                          ?>
                                       <div class="ad_meta">
										  <?php if(isset($myad['price']) && !empty($myad['price'])){?>  
                                          <span class="item_price"> <?php echo $myad['price']?> ₹ </span> 
                                          <?php }?>
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
                        <?php } ?>
                     </div>
                     <ul class="pagination justify-content-end">
                        <li class="page-item"><a class="page-link" href="#">1</a></li>
                        <li class="page-item active"><a class="page-link" href="#">2</a></li>
                     </ul>
                  </div>
               </div>
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
                  <a href="javascript:void(0)"><span class="flag flag-in"></span> &nbsp;Change Country</a>
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
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.1/jquery.min.js"></script>
<!--<script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/jquery-ui.min.js"></script>-->
<!--<script src="http://code.jquery.com/ui/1.10.2/jquery-ui.js"></script>-->
<script>
   $(document).ready(function(){
       // Show hide popover
       $(".category-dropdown a").click(function(){
           $("#category-change").slideToggle("fast");
       });
   });
   
   
</script>
