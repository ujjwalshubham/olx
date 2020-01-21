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
   $baseurl =\Yii::$app->getUrlManager()->getBaseUrl(); 
?>

<!-- Banner section start-->
<div id="home_banner">
   <div class="banner_inner d-flex align-items-center h-100">
      <div class="container">
         <div class="row text-center">
            <div class="col-sm-12 ">
               <div class="banner">
                  <h1 class="title"><?php echo Yii::t('frontend', 'Buy And Sell')?></h1>
                  <h3><?php echo Yii::t('frontend', 'Search thousands of classifieds all in one place')?></h3>
					<?php $form = ActiveForm::begin([ 'method' => 'get','action' => ['listing/search'],'options' => []]) ?>
                     <div class="search-banner-wrapper">
                        <div class="search-banner row justify-content-center no-gutters">
                           <div class="col-md-6">
                              <div class="form-group what_looking d-flex align-items-center  border-right">
                                 <label for="textwords" class="font-weight-bold"><?php echo Yii::t('frontend', 'What')?> </label>
                                 <input type="text" class="form-control border-0 qucikad-ajaxsearch-input" id="search-legal-users" name="category" placeholder="<?php echo Yii::t('frontend', 'What are you looking for?')?>">
                              </div>
                              <div id="qucikad-ajaxsearch-dropdownn" size="0" tabindex="0" style="display: none; overflow-y: scroll; outline: none; cursor: -webkit-grab;">
                                 <ul>
                                    <?php foreach($categories as $key=>$category){
									 $cat_image = AppHelper::getCategoryImage($category['id']); ?>
                                    <li class="qucikad-ajaxsearch-li-cats" data-catid="<?php echo $category['slug']?>">
                                       <?php if(isset($cat_image) && !empty($cat_image)){?>
                                       <img src="<?php echo $cat_image?>">
                                       <?php }else{?>
                                       <img src="<?php echo \Yii::$app->request->BaseUrl; ?>/images/no-image.png">	 
                                       <?php } ?>
                                       <span class="qucikad-as-cat"><?php echo $category['title']?></span>
                                    </li>
                                    <?php } ?>
                                 </ul>
                              </div>
                           </div>
                           <div class="col-md-6">
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
                                 <button type="submit" class="btn btn-primary ml-auto">
                                 <i class="fa fa-search"></i>
                                 <span class="align-middle ml-2 dn-text-sm"><?php echo Yii::t('frontend', 'Search')?></span>
                                 </button>
                              </div>
                           </div>
                        </div>
                     </div>
                 <?php ActiveForm::end(); ?>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>
<!-- Banner section end-->
<section class="mid_container">
   <div class="container">
      <div class="row">
         <div class="col-lg-2 d-none d-lg-block text-center">
			<?php $left_ad =  AppHelper::getAdsByPosition('left_sidebar');?>
            <div class="advertisement" id="quickad-left">
              <?php echo $left_ad['code_large_format'];?>
            </div>
         </div>
         <div class="col-lg-8">
            <!--   Section Start sectionarea country_footerblk -->
            <?php echo CategoryWidget::widget(); ?>
            <!--   Section End sectionarea country_footerblk -->
            <?php $top_ad =  AppHelper::getAdsByPosition('top');?>
            <div class="quickad-section text-center">
            <?php echo $top_ad['code_large_format'];?>
            </div>
            <div class="section product_section ">
               <div class="row">
                  <div class="col-sm-12">
                     <div class="section-title featured-top">
                        <h4><?php echo Yii::t('frontend', 'All Ad')?></h4>
                     </div>
                     <div class="featured_slider h_products">
                        <div class="row">
                           <?php if(!empty($myads)){
							    foreach($myads as $key=>$myad){
							    if($myad['ads_images']){
                              	$media_id = $myad['ads_images'][0]['media_id'];
                              	$image = MediaUpload::getImageByMediaId($media_id);
                              }?>	
                           <div class="col-12 col-sm-6 col-md-4 ">
                              <div class="sad_block">
                                 <div class="sad_container">
                                    <div class="item-image-box">
                                       <a href="<?php echo  Yii::$app->urlManager->createAbsoluteUrl(['user/ad-detail/'.$myad['slug']]);  ?>">
                                       <?php if($myad['ads_images']){?>
                                       <img src="<?php echo $image['url']. $image['upload_base_path'].$image['file_name']?>" alt="<?php echo $myad['title']?>" title="<?php echo $myad['title']?>">
                                       <?php if($myad['ad_type']=='featured'){?>
                                       <div class="item-image-box-tag featured_bg"><?php echo $myad['ad_type']?></div>
                                       <?php } else if($myad['ad_type']=='highlight'){?>
									   <div class="item-image-box-tag hightlight_bg"><?php echo $myad['ad_type']?></div>   
									   <?php } else if($myad['ad_type']=='urgent'){?>
                                       <div class="item-image-box-tag urgent_bg"><?php echo $myad['ad_type']?></div> 
                                       <?php } ?>
                                       <?php } else {?>
                                       <img src="images/products/default.jpg" alt="product" title="<?php echo $myad['title']?>"> 
                                       
                                       <?php if($myad['ad_type']=='featured'){?>
                                       <div class="item-image-box-tag featured_bg"><?php echo $myad['ad_type']?></div>
                                       <?php } else if($myad['ad_type']=='highlight'){?>
									   <div class="item-image-box-tag hightlight_bg"><?php echo $myad['ad_type']?></div>   
									   <?php } else if($myad['ad_type']=='urgent'){?>
                                       <div class="item-image-box-tag urgent_bg"><?php echo $myad['ad_type']?></div> 
                                       <?php }} ?>
                                       </a>
                                    </div>
                                    <div class="ad_info">
										<?php if (strlen($myad['title']) > 18) {?>
                                        <h4> <?php echo Html::a(substr($myad['title'],0,18).'...', ['/user/ad-detail/'.$myad['slug']], ['class' => '']); ?></h4>
                                       <?php } else {?>
									    <h4> <?php echo Html::a($myad['title'], ['/user/ad-detail/'.$myad['slug']], ['class' => '']); ?></h4> 
									   <?php } ?>
                                                                              
                                       <?php if(isset($myad['category_id'][0]['cat_id']) && !empty($myad['category_id'][0]['cat_id'])){?>
                                       <ol class="breadcrumb">
                                          <?php $cat_name = Categories::getCategoryNameById($myad['category_id'][0]['cat_id']);?>
                                          <li><a href=""><?php echo  $cat_name['title']?></a></li>
                                       </ol>
                                       <?php } ?>
                                       <ul class="item-details">
                                          <?php $city = Cities::getCityById($myad['city']);?>
                                          <li><i class="fa fa-map-marker"></i><a href="javascript:void(0)"><?php echo $city['name']?></a></li>
                                          <?php $post_time =  AppHelper::getPostTime($myad['created_at']);?>
                                          <li><i class="fa fa-clock-o"></i><?php echo $post_time;?></li>
                                       </ul>
                                       <?php $wishlist = Olx::wishlistData($userId, $myad['id']);?>
                                       <div class="ad_meta">
										  <?php if(isset($myad['price']) && !empty($myad['price'])){?> 
                                          <span class="item_price"> <?php echo $myad['price']?> <i class="fa fa-inr" aria-hidden="true"></i> </span> 
                                          <?php }?>
                                          <ul class="contact-options pull-right" id="set-favorite">
                                             <?php if($userId){?>
                                             <?php if(isset($wishlist['id']) && !empty($wishlist['id']) ){?>	  
                                             <li><a name="wishlist_btn" href="javascript:void(0)" attrId="<?php echo $myad['id']?>" class="fa fa-heart last_icn wishBtnManageColor wishlist_btn_red " title="set favorite"></a></li>
                                             <?php } else {?>
                                             <li><a name="wishlist_btn" id="wishlist_btn" href="javascript:void(0)" attrId="<?php echo $myad['id']?>" class="fa fa-heart wishlist_btn" title="set favorite"></a></li>
                                             <?php } } else {?>
                                             <li><a href="" data-toggle="modal" data-target="#register-popup" class="fa fa-heart" title="set favorite"></a></li>
                                             <?php } ?>
                                          </ul>
                                       </div>
                                    </div>
                                 </div>
                              </div>
                           </div>
                           <?php } }?>
                        </div>
                     </div>
                  </div>
                  
                 <?php if($adsCount>9){?>
                 <div class="col-sm-12 view_all"> 
					<button type="button" class="btn bt_blue"><a href="<?php echo $baseurl.'/listing' ?>" style="color:#fff"><?php echo Yii::t('frontend', 'View All')?></a></button>
                 </div>
                 <?php } ?>
               </div>
            </div>
         </div>
         <div class="col-lg-2 d-none d-lg-block hidden-sm text-center">
			<?php $right_ad =  AppHelper::getAdsByPosition('right_sidebar');?>
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
                  <a href="javascript:void(0)"><span class="flag flag-in"></span> &nbsp;Change Country</a>
                  <div id="searchDisplay" style="display:none;"></div>
               </div>
               <div class="btm_stts_mn">
                  <div class="viewport">
                     <div class="row full" id="getCities">
                        <div class="col-sm-3 col-md-3 loader" style="display: none"></div>
                        <div id="results" class="animate-bottom">
                           <ul class="column col-md-12 col-sm-12 cities">
                              <li class="active">
                                 <a href="#" class="selectme" data-id="113" data-name="All India" data-type="country">All India<i class="fa fa-angle-right" aria-hidden="true"></i></a>
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
img.image-icon {height: 30px;}
.wishlist_btn_red{color:#fff !important;}
</style>
