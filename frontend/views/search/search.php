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
   use yii\widgets\LinkPager;
   $userId = Yii::$app->user->identity['id'];
   $categories = Olx::getAllCategory();
   if(isset($params['custom']) && !empty($params['custom'])){
   $custom_params = $params['custom'];
   } else {
	 $custom_params = array();	   
   } 
?>
<style>
.wishlist_btn_red{color:#fff !important;}
</style>
<section id="main" class="category-page mid_container">
	<?php $form = ActiveForm::begin([ 'method' => 'get','action' => ['listing/search'],'options' => []]) ?>
   <div class="container">
      <!-- breadcrumb -->
      <div class="breadcrumb-section">
         <ol class="breadcrumb">
            <li><a href="<?php echo \Yii::$app->request->BaseUrl; ?>"><i class="fa fa-home"></i> <?php echo Yii::t('frontend', 'Home')?></a></li>
            <li><?php echo $category->title?> </li>
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
               <?php $cat_image = AppHelper::getCategoryImage($category->id);?>
               <div class="col-md-4">
                  <div class="dropdown category-dropdown open">
                     <a data-toggle="dropdown" href="#" aria-expanded="true"><span class="change-text">
                     <?php if(isset($cat_image) && !empty($cat_image)){?>
                     <img src="<?php echo $cat_image?>" style="width: 20px;"> 
                     <?php } else if($category->id=='All'){?>
                    
                     <?php } else {?>
                     <img class="image-icon"src="<?php echo \Yii::$app->request->BaseUrl; ?>/images/no-image.png">
                     <?php } ?>
                     <?php echo $category->title?></span><i class="fa fa-navicon"></i></a>
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
              
               <div class="col-md-3">
				   <?php if(isset($params['keywords'])){?>
                  <input type="text" class="form-control" name="keywords" id="keywords" value="<?php echo $params['keywords']?>" placeholder="What ?">
                  <?php } else {?>
					  <input type="text" class="form-control" name="keywords" id="keywords" value="" placeholder="What ?">
				  <?php } ?>
               </div>
             
               <div class="col-md-3 banner-icon">
                  <i class="fa fa-map-marker"></i>
                  <div class="form-group live-location-search">
                     <input type="text" class="form-control location" id="searchStateCity" name="location" data-toggle="modal" data-target="#city_slct_modal" placeholder="<?php echo Yii::t('frontend', 'Where?')?>">
                     <input type="hidden" name="latitude" id="latitude" value="">
                     <input type="hidden" name="longitude" id="longitude" value="">
                     <input type="hidden" name="placetype" id="searchPlaceType" value="">
                     <input type="hidden" name="placeid" id="searchPlaceId" value="">
                     
                     <?php if(isset($params) && !empty($params)){?>
                     <input type="hidden" id="input-maincat" name="cat" value="<?php echo $params['cat'] ?> "/>
                     <?php } else {?>
					  <input type="hidden" id="input-maincat" name="cat" value="<?php echo $new_cat_id ?>"/>	 
					 <?php } ?>
					 
					 <?php if(isset($params) && !empty($params)){?>
					 <input type="hidden" id="input-subcat" name="subcat" value="<?php echo $params['subcat'] ?>"/>
					 <?php } else {?>
					 <input type="hidden" id="input-subcat" name="subcat" value=""/>	 
					 <?php } ?>
                  </div>
               </div>
               <div class="col-md-2">
                  <button data-ajax-response="map" type="submit" name="Submit" class="form-control bt_blue"><i class="fa fa-search"></i>  <?php echo Yii::t('frontend', 'Search')?></button>
               </div>
            </div>
         </div>
      </div>
      <!-- Inner Search section End -->
      <div class="row recommended-ads">
		  
		 <!-- Advance Search Custom Fields According to category --> 
		 
		 <?php if(isset($custom_fields) && !empty($custom_fields)){?>
         <div class="col-sm-3 col-md-3">
            <div class="tg-sidebartitle">
               <h2> <?php echo Yii::t('frontend', 'Advance Search')?>:</h2>
            </div>
            <div id="custom-field-block" class="section ">
               <div id="ResponseCustomFields">
				   
				   <?php $arr_keys = array_keys($custom_params);?>
				   <?php $arr_values = array_values($custom_params);?>
				   <?php foreach($custom_fields as $key=>$custom_field){?>
					  <?php if($custom_field['custom_fields_type']['type']=='text'){?>
					   <div class="form-group">
						<label class="label-title"><?php echo $custom_field['custom_fields']['label']?></label><br>
						<input type="text" value="<?php if(in_array($custom_field['custom_fields']['id'],$arr_keys)){?><?php echo $custom_params[$custom_field['custom_fields']['id']]?><?php }?>" placeholder="<?php echo $custom_field['custom_fields']['label']?>" class="form-control" name="custom[<?php echo$custom_field['custom_fields']['id']?>]" id="custom[<?php echo$custom_field['custom_fields']['id']?>]"> 
					  </div>
					  <?php } ?>
					  
					  <?php if($custom_field['custom_fields_type']['type']=='textarea'){?>
					  <div class="form-group">
							<label class="label-title"><?php echo $custom_field['custom_fields']['label']?></label><br>
							<textarea class="form-control border-form custom_field" rows="7" name="custom[<?php echo$custom_field['custom_fields']['id']?>]" id="custom[<?php echo$custom_field['custom_fields']['id']?>]" value="<?php if(in_array($custom_field['custom_fields']['id'],$arr_keys)){?><?php echo $custom_params[$custom_field['custom_fields']['id']]?><?php }?>"></textarea>
					  </div>
					  <?php } ?>
					  
					  <?php if($custom_field['custom_fields_type']['type']=='checkbox'){
						 //echo "<pre>"; print_r($params['custom']);exit;
						 $acustom = array();	
					  if(isset($params['custom']) && !empty($params['custom'])){  
						  foreach($params['custom'] as $Arr){
								if(is_array($Arr)){ //print_r($Arr);exit;
								$acustom = $Arr;
								}
							}
						}?>
					  <div class="form-group check_gapingbs">
							<label class="label-title"><?php echo $custom_field['custom_fields']['label']?></label><br>
							<?php foreach($custom_field['custom_fields_options'] as $key1=>$option){?>
							<div class="checkbox checkbox-primary">
								<input name="custom[<?php echo$custom_field['custom_fields']['id']?>][<?php echo $option['id']?>]" id="<?php echo $option['id']?>" value="<?php echo $option['id']?>" type="checkbox" <?php if(in_array($option['id'],$acustom)){?>checked<?php }?>>
								<label for="<?php echo $option['id']?>"><?php echo $option['label']?></label>
							</div>
							<?php } ?>
					  </div>
					  <?php } ?>					  
					  <?php if($custom_field['custom_fields_type']['type']=='radio'){
						  	$acustom = array();	 ?>
						   <div class="form-group">
							 <label class="label-title"><?php echo $custom_field['custom_fields']['label']?></label><br>
							 <?php foreach($custom_field['custom_fields_options'] as $key1=>$option){?>
							 <span>
							 <input type="radio"  name="custom[<?php echo $custom_field['custom_fields']['id']?>]" id="<?php echo $option['id']?>" value="<?php echo $option['id'] ?>" <?php if(in_array($option['id'],$arr_values)){?>checked<?php }?>>
							 <label for="<?php echo $option['id']?>"><?php echo $option['label']?></label>
							 </span>
							 <?php } ?>
						  </div>
					   <?php } ?>
				
					   <?php if($custom_field['custom_fields_type']['type']=='select'){
						   	$acustom = array();?>
						  <div class="form-group">
							<label class="label-title"><?php echo $custom_field['custom_fields']['label']?></label><br>
							<select class="form-control" name="custom[<?php echo$custom_field['custom_fields']['id']?>]">
								<option value="" selected="">Select <?php echo $custom_field['custom_fields']['label']?></option>
								<?php foreach($custom_field['custom_fields_options'] as $key1=>$option){?>
								<option value="<?php echo $option['id']?>"  <?php if(in_array($option['id'],$arr_values)){?>selected<?php }?>><?php echo $option['label']?></option>
								<?php } ?>
							</select>
						  </div>
					   <?php } } ?>
                  
                  <div class="inner">
                     <div class="form-group">
                        <label class="label-title"><?php echo Yii::t('frontend', 'Price')?></label>
                        <div class="range-widget">
                           <div class="range-inputs">
                              <input type="text" placeholder="From" name="range1" value="<?php if(isset($params['range1'])){ echo $params['range1']; } ?>">
                              <input type="text" placeholder="To" name="range2" value="<?php if(isset($params['range2'])){ echo $params['range2']; } ?>">
                           </div>
                           <button type="submit" class="bt_blue"><i class="fa fa-search"></i></button>
                        </div>
                     </div>
                  </div>
                  <button type="submit" name="Submit" class="btn tg-btn bt_blue" id="advance-search-btn" style="padding: 0 40px;"><?php echo Yii::t('frontend', 'Advance Search')?></button>
               </div>
            </div>
         </div>
        
        <?php } else {?>
		<div class="col-sm-3 col-md-3">
					<div class="tg-sidebartitle">
						<h2><?php echo Yii::t('frontend', 'Advanced Search')?>:</h2>
					</div>
					<div id="custom-field-block" class="section ">
						<div id="ResponseCustomFields">
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
		<?php } ?>
        <!-- Advance Search Custom Fields According to category --> 
         
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
                                       <a href="<?php echo \Yii::$app->request->BaseUrl.'/user/ad-detail/'.$myad['slug'];?>"><img src="<?php echo \Yii::$app->request->BaseUrl. $myad['ads_images'][0]['upload_base_path'].$myad['ads_images'][0]['file_name']?>" alt="product" title="product">
                                       
                                       <?php if($myad['ad_type']=='featured'){?>
                                       <div class="item-image-box-tag featured_bg"><?php echo $myad['ad_type']?></div>
                                       <?php } else if($myad['ad_type']=='highlight'){?>
									   <div class="item-image-box-tag hightlight_bg"><?php echo $myad['ad_type']?></div>   
									   <?php } else if($myad['ad_type']=='urgent'){?>
                                       <div class="item-image-box-tag urgent_bg"><?php echo $myad['ad_type']?></div> 
                                       <?php } ?>
                                       </a>
                                       <?php } else{?>
                                       <a href="<?php echo \Yii::$app->request->BaseUrl.'/user/ad-detail/'.$myad['slug'];?>"><img src="<?php echo \Yii::$app->request->BaseUrl; ?>/images/no-image.jpg">	
                                       <?php if($myad['ad_type']=='featured'){?>
                                       <div class="item-image-box-tag featured_bg"><?php echo $myad['ad_type']?></div>
                                       <?php } else if($myad['ad_type']=='highlight'){?>
									   <div class="item-image-box-tag hightlight_bg"><?php echo $myad['ad_type']?></div>   
									   <?php } else if($myad['ad_type']=='urgent'){?>
                                       <div class="item-image-box-tag urgent_bg"><?php echo $myad['ad_type']?></div> 
                                       <?php } ?>
                                       </a>  
                                       <?php } ?>
                                    </div>
                                    <div class="ad_info">
                                       <?php if (strlen($myad['title']) > 18) {?>
                                        <h4> <?php echo Html::a(substr($myad['title'],0,18).'...', ['/user/ad-detail/'.$myad['slug']], ['class' => '']); ?></h4>
                                       <?php } else {?>
									    <h4> <?php echo Html::a($myad['title'], ['/user/ad-detail/'.$myad['slug']], ['class' => '']); ?></h4> 
									   <?php } ?>
                                       <!--<ol class="breadcrumb">
                                          <?php foreach ($myad['category_id'] as $category){?>
                                          <?php $cat_name = Categories::getCategoryNameById($category['cat_id']);?>
                                          <li><a href=""><?php echo  $cat_name['title']?></a></li>
                                          <?php } ?>
                                       </ol>-->
                                       <ol class="breadcrumb cat_bred">
                                          <?php foreach ($myad['category_id'] as $category){?>
                                          <?php $cat_name = Categories::getCategoryNameById($category['cat_id']);?>
                                          <?php if (strlen($cat_name['title']) > 20) {?>
                                          <li><a href="javascript:void(0);"><?php echo  substr($cat_name['title'],0,20).'...'?></a></li>
                                          <?php } else {?>
                                          <li><a href="javascript:void(0);"><?php echo  $cat_name['title']?></a></li>
                                          <?php } }?>
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
                                          <span class="item_price"> <?php echo $myad['price']?> <i class="fa fa-inr" aria-hidden="true"></i> </span> 
                                          <?php }?>
                                          <ul class="contact-options pull-right" id="set-favorite">
                                             <?php if($userId){?>
                                             <?php if(isset($wishlist['id']) && !empty($wishlist['id']) ){?>	  
                                             <li><a name="wishlist_btn" href="javascript:void(0)" attrId="<?php echo $myad['id']?>" class="fa fa-heart last_icn wishBtnManageColor wishlist_btn_red " title="set favorite"></a></li>
                                             <?php } else {?>
                                             <li><a name="wishlist_btn" id="wishlist_btn" href="javascript:void(0)" attrId="<?php echo $myad['id']?>" class="fa fa-heart wishlist_btn" title="set favorite"></a></li>
                                             <?php } ?>
                                             <?php } else {?>
                                             <li><a href="" data-toggle="modal" data-target="#register-popup"class="fa fa-heart" title="set favorite"></a></li>
                                             <?php } ?>
                                          </ul>
                                       </div>
                                    </div>
                                 </div>
                              </div>
                           </div>
                           <?php } ?>
                        </div>
                        <?php } else{?>
							<div class="row aaaa">
								<p><?php echo Yii::t('frontend', 'No Ads Found')?></p>
							</div>
					    <?php } ?>
                     </div>
                  </div>
               </div>
            </div>
            <?php echo LinkPager::widget(['pagination' => $pages]);?>
         </div>
      </div>
   </div>


 <?php ActiveForm::end(); ?>
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
<script>
   $(document).ready(function(){
       // Show hide popover
       $(".category-dropdown a").click(function(){
           $("#category-change").slideToggle("fast");
       });
   });
</script>
