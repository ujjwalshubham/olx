<?php
   use common\components\SidenavWidget;
   use common\components\ProfileheaderWidget; 
   use yii\helpers\Html;
   use yii\widgets\ActiveForm;
   use common\components\AppHelper;
   use common\models\Plans;
   use common\models\Packages;
   use yii\web\View;
   use yii\helpers\Url;
   $this->title = Yii::t('frontend', 'Post An Ad') . ' | OLX';
   $session = Yii::$app->session;
   $userId = Yii::$app->user->identity['id'];
   ?>
 <style>
 .required .redactor-editor{color:#000!important}
 </style>  
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.1/jquery.min.js"></script>
<script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/jquery-ui.min.js"></script>
<section id="main" class="category-page mid_container">
   <div class="container">
      <!-- breadcrumb -->
      <div class="breadcrumb-section">
         <ol class="breadcrumb">
            <li><a href="<?php echo \Yii::$app->request->BaseUrl; ?>"><i class="fa fa-home"></i> <?php echo Yii::t('frontend', 'Home')?></a></li>
            <li> <a href="">Profile</a></li>
            <li><?php echo Yii::t('frontend', 'Post An Ad')?> </li>
            <div class="ml-auto back-result"><a href=""><i class="fa fa-angle-double-left"></i><?php echo Yii::t('frontend', 'Back to Results')?></a> </div>
         </ol>
      </div>
      <!-- breadcrumb --> 
      <div class="row recommended-ads">
         <!-- side panel navigation -->
         <?php echo SidenavWidget::widget(); ?>
         <!-- side panel navigation -->
         <div class="col-md-8 col-lg-9">
            <div class="panel-user-details">
               <?php if (Yii::$app->session->hasFlash('success')): ?>
               <div class="alert alert-success alert-dismissable">
                  <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                  <?= Yii::$app->session->getFlash('success') ?>
               </div>
               <?php endif; ?>
               <?php if (Yii::$app->session->hasFlash('fail')): ?>
               <div class="alert alert-danger alert-dismissable">
                  <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                  <?= Yii::$app->session->getFlash('fail') ?>
               </div>
               <?php endif; ?>
               <?php $form = ActiveForm::begin(['id' => 'post-ad',
				'options' => ['enableAjaxValidation' => true,'enableClientValidation' => false,'role' => 'form','enctype' => 'multipart/form-data'], 'fieldConfig' => ['options' => ['tag' => 'span']]]); ?>
              
               <div class="user-details section post_ad">
                  <div class="my-details">
                     <div class="section-title">
                        <h2><?php echo Yii::t('frontend', 'Post An Advertise')?></h2>
                        <p><?php echo Yii::t('frontend', 'Get free quotes from target people within minutes, email contact, ratings and chat with them.')?></p>
                     </div>
                     <div class="spt_bt" id="select_cat">
                        <a class="btn bt_green ch_category" id="#selectcategory" data-toggle="modal" data-target="#selectCategory"><?php echo Yii::t('frontend', 'Choose Category')?></a>
                     </div>
                     <div class="error_msg_cat error"></div>
                     <div class="form-group selected-product" id="change-category-btn" style="display:none;">
                        <ul class="select-category list-inline">
                           <li id="main-category-text"></li>
                           <li id="sub-category-text"></li>
                           <li class="active"><a href="#" id="#selectcategory" data-toggle="modal" data-target="#selectCategory"><i class="fa fa-pencil-square-o"></i> Edit</a></li>
                        </ul>
                        <!--<input type="hidden" id="input-maincatid" name="catid" value="3">
                           <input type="hidden" id="input-subcatid" name="subcatid" value="10">-->
                     </div>
                     <div class="section-body">
                        <div class="row form-group" id="select_title">
                           <div class="col-sm-12">
                              <label class="control-label"><?php echo Yii::t('frontend', 'Title for your Advertise')?> <span class="required">*</span></label>
                              <?php echo $form->field($model, 'title', ['inputOptions' => ['class' => 'form-control border-form','placeholder'=>Yii::t('frontend', 'Title for your Advertise')]])->label(false) ?>
                           </div>
                        </div>
                        <div class="error_msg_title error"></div>
                        <div class="row form-group" id="select_description">
                           <div class="col-sm-12">
                              <label class="control-label"><?php echo Yii::t('frontend', 'Description')?> <span class="required">*</span></label>
								<?php echo $form->field($model, 'description')->widget(
									\yii\imperavi\Widget::class,
									[
										'options' => [
											'minHeight' => 200,
											'maxHeight' => 200,
											'buttonSource' => true,
											'convertDivs' => false,
											'removeEmptyTags' => true,
										],
									]
								)->label(false) ?>
							   </div>
							</div>
                        <?php echo $form->field($post_cat_model, 'cat_id[]')->hiddenInput(['value'=> '','id'=>'cat_id'])->label(false);?>
                        <input type="hidden" id="sub_cat_id_val" value="">
                        <div class="container" id="select_image">
                           <div class="row">
                              <div class="col-sm-3 imgUp">
                                 <div class="imagePreview"></div>
                                 <label class="btn btn-primary">
                                 <?php echo Yii::t('frontend', 'Upload')?>
                                 <input type="file" class="uploadFile img" name="images[]" id="images" value="Upload Photo" style="width: 0px;height: 0px;overflow: hidden;" accept="image/*">
                                 </label>
                              </div>
                              <!-- col-2 -->
                              <i class="fa fa-plus imgAdd"></i>
                           </div>
                           <!-- row -->
                        </div>
                       <div class="error_msg_image error"></div>
                        <div class="row form-group">
                           <div class="col-sm-12">
                              <h4><?php echo Yii::t('frontend', 'Additional info')?></h4>
                           </div>
                        </div>
                        <div class="custom_fields">
                        </div>
                        <div class="form-group row" id="price_div">
                           <label class="col-sm-3 control-label "><?php echo Yii::t('frontend', 'Price')?></label>
                           <div class="col-sm-9 aa">
                              <div class="input-group mb-2">
                                 <div class="input-group-prepend">
                                    <div class="input-group-text">₹</div>
                                 </div>
                                 <!-- <input type="text" class="form-control" placeholder="Username">-->
                                 <?php echo $form->field($model, 'price', ['inputOptions' => ['class' => 'form-control ','placeholder'=>Yii::t('frontend', 'Price')]])->label(false) ?>
                                 <div class="input-group-append">
                                    <div class="input-group-text">
                                       <div class="checkbox ">
                                          <?php
                                             $newsletter=Yii::t('frontend','Negotiate');
                                             echo $form->field($model, 'negotiate',['template'=>'{input}{label}'])->checkbox(['label' => null])->label($newsletter); ?>
                                       </div>
                                    </div>
                                 </div>
                              </div>
                           </div>
                        </div>
                        <div class="form-group row">
                           <label class="col-sm-3 control-label "><?php echo Yii::t('frontend', 'Mobile Number')?> </label>
                           <div class="col-sm-9">
                              <div class="input-group mb-2">
                                 <div class="input-group-prepend">
                                    <div class="input-group-text"><span class="flag flag-in"></span></div>
                                 </div>
                                 <?php echo $form->field($model, 'mobile', ['inputOptions' => ['class' => 'form-control ','placeholder'=>Yii::t('frontend', 'Mobile No.')]])->label(false) ?>
                                 <div class="input-group-append">
                                    <div class="input-group-text">
                                       <div class="checkbox ">
                                          <?php
                                             $hide=Yii::t('frontend','Hide');
                                             echo $form->field($model, 'mobile_hidden',['template'=>'{input}{label}'])->checkbox(['label' => null])->label($hide); ?>
                                       </div>
                                    </div>
                                 </div>
                              </div>
                           </div>
                        </div>
                        <div class="form-group row">
                           <label class="col-sm-3 control-label "><?php echo Yii::t('frontend', 'Tags')?> </label>
                           <div class="col-sm-9">
                              <?php echo $form->field($model, 'tags', ['inputOptions' => ['class' => 'form-control ','placeholder'=>'Tags']])->label(false) ?>
                           </div>
                        </div>
                        <!--<div class="form-group row mr_top_45" id="select_country">
                           <label class="col-sm-3  control-label"><?php echo Yii::t('frontend', 'Country')?> <span class="required">*</span> </label>
                           <div class="col-sm-9">
                              <div class="slects_box">
                                 <span>
                                 <?php  echo $form->field($model, 'country')
                                    ->dropDownList($countries,['prompt'=>Yii::t('frontend', 'Select Country')])->label(false);
                                    ?>
                                 </span>
                              </div>
                              <div class="error_msg_country error"></div>
                           </div>
                        </div>-->
                        <div class="form-group row mr_top_45" id="select_state">
                           <label class="col-sm-3  control-label"><?php echo Yii::t('frontend', 'State')?> <span class="required">*</span> </label>
                           <div class="col-sm-9">
                              <div class="slects_box">
                                 <span>
                                 <?php  echo $form->field($model, 'state')
                                    ->dropDownList($states,['prompt'=>Yii::t('frontend', 'Select State')])->label(false);
                                    ?>
                                 </span>
                              </div>
                              <div class="error_msg_state error"></div>
                           </div>
                        </div>
                        <div class="form-group row mr_top_45" id="select_city">
                           <label class="col-sm-3  control-label"><?php echo Yii::t('frontend', 'City')?> <span class="required">*</span> </label>
                           <div class="col-sm-9">
                              <div class="slects_box">
                                 <span>
                                 <?php  echo $form->field($model, 'city')
                                    ->dropDownList($cities,['prompt'=>Yii::t('frontend', 'Select City')])->label(false);
                                    ?>
                                 </span>
                              </div>
                              <div class="error_msg_city error"></div>
                           </div>
                        </div>
                        <div class="form-group row">
                           <label class="col-sm-3 control-label"><?php echo Yii::t('frontend', 'Address')?> <span class="required">*</span>  </label>
                           <div class="col-sm-9">
                              <?= $form->field($model, 'address',['template' => '{input}{error}','inputOptions' => ['class' => 'form-control border-form']])->textarea(['rows' => '5'])->label(false) ?>
                           </div>
                        </div>
                     </div>
                  </div>
                  <div class="my-details">
                     <h4><?php echo Yii::t('frontend', 'Make Your Ad Premium Optional')?></h4>
                     <div class="section-body">
                        <form>
                        <div class="form-group row">
                           <div class=" col-sm-12">
                              <div class="fr_ad active hidediv">
                                 <div class="free_ad_img">
                                    <img src="<?php echo \Yii::$app->request->BaseUrl; ?>/images/free_ad_icon.jpg">
                                 </div>
                                 <div class="free_ad_content">
                                    <h5><?php echo Yii::t('frontend', 'Free Ad')?> <strong><?php echo Yii::t('frontend', 'Free')?> </strong></h5>
                                    <p><?php echo Yii::t('frontend', 'Your ad will go live after check by reviewer.')?></p>
                                 </div>
                              </div>
                           </div>
                           <div class=" col-sm-12">
                              <div class="fr_ad showdiv">
                                 <div class="free_ad_img">
                                    <img src="<?php echo \Yii::$app->request->BaseUrl; ?>/images/free_ad_icon2.jpg">
                                 </div>
                                 <div class="free_ad_content">
                                    <h5><?php echo Yii::t('frontend', 'Premium')?> <span><?php echo Yii::t('frontend', 'Recommended')?></span></h5>
                                    <p><?php echo Yii::t('frontend', 'you can optionally select some upgrades to get the best results.')?></p>
                                 </div>
                                 <?php
									 $userplan = AppHelper::getUserSubscription($userId);
									 if(isset( $userplan ) && !empty( $userplan )){
										$userplan['package_detail'] = Plans::getPlanDetail($userplan['plan_id']);
									 }else{
										$default_subscription = AppHelper::getUserDefaultSubscription(); 
										$userplan['package_detail'] = Packages::getPackageDescription($default_subscription['value']);
									//	echo "<pre>"; print_r($userplan['package_detail']);exit;
									 }
                                 ?>                                 
                                 <div class="add_collapspart" style="display:none">
                                    <div class="inner_collapsboxlist">
                                       <ul>
                                          <li>
                                             <div class="featured_detailsm">
                                                <div class="checkbox_detailssm">
                                                   <div class="checkbox checkbox-primary">
                                                      <input name="ad_type" id="featured" value="featured" type="radio" onclick="fillPrice(this,<?php echo $userplan['package_detail']['featured_project_fee']?>);">
                                                      <label for="featured"><span class="features_btns"><?php echo Yii::t('frontend', 'Featured')?></span></label>
                                                   </div>
                                                </div>
                                                <div class="featured_content"> <?php echo Yii::t('frontend', 'Featured ads attract higher-quality viewer and are displayed prominently in the Featured ads section home page.')?> </div>
                                                 <div class="features_pricesm"> ₹<?php echo $userplan['package_detail']['featured_project_fee']?><?php echo Yii::t('frontend', 'for')?> <?php echo $userplan['package_detail']['featured_duration']?> <?php echo Yii::t('frontend', 'days')?> </div>
                                             </div>
                                          </li>
                                          <li>
                                             <div class="featured_detailsm">
                                                <div class="checkbox_detailssm">
                                                   <div class="checkbox checkbox-primary">
                                                      <input name="ad_type" id="urgent" value="urgent" type="radio" onclick="fillPrice(this,<?php echo $userplan['package_detail']['urgent_project_fee']?>);"> 
                                                      <label for="urgent"> <span class="features_btns urgent"><?php echo Yii::t('frontend', 'Urgent')?></span></label>
                                                   </div>
                                                </div>
                                                <div class="featured_content"> <?php echo Yii::t('frontend', 'Make your ad stand out and let viewer know that your advertise is time sensitive.')?></div>
                                                <div class="features_pricesm"> ₹<?php echo $userplan['package_detail']['urgent_project_fee']?> <?php echo Yii::t('frontend', 'for')?> <?php echo $userplan['package_detail']['urgent_duration']?><?php echo Yii::t('frontend', 'days')?> </div>
                                             </div>
                                          </li>
                                          <li>
                                             <div class="featured_detailsm">
                                                <div class="checkbox_detailssm">
                                                   <div class="checkbox checkbox-primary">
                                                      <input name="ad_type" id="highlight" value="highlight" type="radio" onclick="fillPrice(this,<?php echo $userplan['package_detail']['highlight_project_fee']?>);">
                                                      <label for="highlight"> <span class="features_btns high"><?php echo Yii::t('frontend', 'Highlight')?></span>  </label>
                                                   </div>
                                                </div>
                                                <div class="featured_content"><?php echo Yii::t('frontend', 'Make your ad highlighted with border in listing search result page. Easy to focus.')?>  </div>
                                               <div class="features_pricesm"> ₹<?php echo $userplan['package_detail']['highlight_project_fee']?> <?php echo Yii::t('frontend', 'for')?> <?php echo $userplan['package_detail']['highlight_duration']?> <?php echo Yii::t('frontend', 'days')?></div>
                                             </div>
                                          </li>
                                       </ul>
                                    </div>
                                 </div>
                              </div>
                           </div>
                           <input type="hidden" id="max_image" value="<?php echo $max_image_upload;?>">
                           <input type="hidden" id="ad_amount" value="" name="ad_amount">
                           <div class=" col-sm-12">
                              <div class="checkbox checkbox-primary">
                                 <!-- <input name="notify" id="01" value="1" type="checkbox">
                                    <label for="01"><?php /*echo Yii::t('frontend', 'By clicking create Ad you agree to our ')*/?><a href=""><?php /*echo Yii::t('frontend', 'Terms & Condition')*/?></a> <?php /*echo Yii::t('frontend', 'And')*/?> <a href=""><?php /*echo Yii::t('frontend', 'Privacy')*/?></a></label>-->
                                 <?php
                                   $termlink=Yii::t('frontend','By clicking create Ad you agree to our');
                                    echo $form->field($model, 'termCondition',
                                        ['template'=>'<div class="checkbox-btn">{input}{label}<div class="help-block"></div></div>'])
                                        ->checkbox(['label' => null])
                                        ->label($termlink.' <a href="'.Url::to(['/page/terms-conditions']).'">'.Yii::t('frontend','Terms and Conditions').'</a> '.Yii::t('frontend','And').' <a href="'.Url::to(['/page/privacy-policy']).'">'.Yii::t('frontend','Privacy Policy').'</a>.'); ?>
                              </div>
                              
                               <div class="form-group row">
								   <label class="col-sm-3 control-label ">  <?php echo Html::submitButton(Yii::t('frontend', 'Post Your Ad'),
								['id'=>"submit_btn",'class' => 'btn bt_blue']);?> </label>
								   <div class="col-sm-9">
									  <div id="ad_total_cost_container" class="PagePostProject-totalCost" style="display:none;">
                                            <strong>
                                               <?php echo Yii::t('frontend', 'Total:')?>
                                                <span class="currency-sign">₹</span>
                                                <span id="totalPrice"></span>
                                            </strong>
                                    </div>
								   </div>
								</div>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
               <?php ActiveForm::end(); ?>
            </div>
         </div>
      </div>
   </div>
</section>
<div class="modal fade modalHasList" id="selectCategory" tabindex="-1" role="dialog" aria-labelledby="selectCategoryLabel"
   aria-hidden="true">
   <div class="modal-dialog modal-lg">
      <div class="modal-content">
         <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">
            <span aria-hidden="true">&times;</span>
            <span class="sr-only">{LANG_CLOSE}</span>
            </button>
            <h4 class="modal-title uppercase font-weight-bold" id="selectCountryLabel">
               <i class="icon-map"></i><?php echo Yii::t('frontend', ' Select your Category ')?>
            </h4>
         </div>
         <div class="modal-body">
            <div id="tg-dbcategoriesslider" class="tg-dbcategoriesslider tg-categories owl-carousel select-category post-option owl-loaded owl-drag">
               <div class="owl-stage-outer">
                  <div class="owl-stage" style="transform: translate3d(0px, 0px, 0px); transition: all 0.25s ease 0s; width: 1500px;">
                     <?php foreach($categories as $key=>$category){?> 
					 <?php $cat_image = AppHelper::getCategoryImage($category['id']);?>
                     <div class="owl-item" style="width: 187.5px;">
                        <div class="category-item" >
                           <div class="tg-categoryholder" attr-id="<?php echo $category['id'];?>" attr-name="<?php echo $category['title'];?>">
							  <?php if(isset($cat_image) && !empty($cat_image)){?>
                              <div class="category-icon">
								  <img src="<?php echo $cat_image?>">
							  </div>
							   <?php } else {?>
							  <div class="category-icon"><img src="<?php echo \Yii::$app->request->BaseUrl; ?>/images/no-image.png"></div>
								<?php } ?>
                              <span class="category-title"><?php echo $category['title'];?></span>
                           </div>
                        </div>
                     </div>
                     <?php } ?>
                  </div>
               </div>
               <div class="tg-slidernav">
                  <div class="tg-prev disabled"><span class="icon-chevron-left"></span></div>
                  <div class="tg-next"><span class="icon-chevron-right"></span></div>
               </div>
               <div class="tg-sliderdots disabled"></div>
            </div>
            <div class="section category-quickad text-center">
               <div class="cat_div"></div>
            </div>
         </div>
      </div>
   </div>
</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"></script>
<script>
   $(document).ready(function () {
       (function ($) {
         
       })(jQuery);
   });
   	
   	
   if(jQuery('#tg-dbcategoriesslider').length > 0){
       var _tg_postsslider = jQuery('#tg-dbcategoriesslider');
       _tg_postsslider.owlCarousel({
           items : 4,
           nav: true,
           loop: false,
           dots: false,
           autoplay: false,
           dotsClass: 'tg-sliderdots',
           navClass: ['tg-prev', 'tg-next'],
           navContainerClass: 'tg-slidernav',
           navText: ['<span class="icon-chevron-left"></span>', '<span class="icon-chevron-right"></span>'],
           responsive:{
               0:{ items:2, },
               640:{ items:3, },
               768:{ items:4, },
           }
       });
   }
</script>
<?php
   $jss = "$(document).on('click', '.tg-categoryholder', function() {
	var baseurl= $('#uribase').val();
   $('#sub_cat_id_val').val('');
   $('.ch_category').html('Edit Category');
   $('.ch_category').addClass('cat_change');
   $('#sub-category-text').text('--');
    var cat_id = $(this).attr('attr-id');
   	
    var cat_name = $(this).attr('attr-name');
   	
   	$('#change-category-btn').css('display', 'block');
   	$('#main-category-text').text(cat_name);
   
   	 $('.tg-categoryholder').not(this).removeClass('intro');
     $(this).addClass('intro');
   	
   	$('#sub-category-loader').css('visibility', 'visible');
   	
   	 $.ajax({
              url: baseurl+'/get-category',
              type: 'POST',
               data: {cat_id: cat_id}, 
               success: function(categories) {
   	 if(categories) {
   		setTimeout(function() {
   		
   		$('.cat_div').html(categories);
   		}, 1000);
   	 }
	   }
	});
	
	
	$.ajax({
              url: baseurl+'/get-fields',
              type: 'POST',
               data: {cat_id: cat_id}, 
               success: function(fieldsdata) {
   	 if(fieldsdata) {
		fieldsdata  = JSON.parse(fieldsdata);
		
		console.log(fieldsdata)
   	    $.each(fieldsdata, function (key, value) {
          if(value.name=='price_field'){
           var values = value.value;
		   var array_fields = values.split(',');	
		 
			if(jQuery.inArray(cat_id, array_fields) == -1) {
				console.log('is NOT in array');
				$('#price_div').hide();
				$('#postad-price').val('');
			} else {
					console.log('is in array');
					$('#price_div').show();
			}
		  }
		  if(value.name=='max_image_upload'){
		       $('#max_image').val(value.value)
		  }       
       });
   		
   	 }
	   }
	});
   });
   
   
   $('#postad-mobile').keypress(function(e){ 
	   if (this.value.length == 0 && e.which == 48 ){
		  return false;
	   }
  });
   
   
   
   $(document).on('click', '.sub-cat-title', function() {
   var cat_id = $('.intro').attr('attr-id');
   $('#sub_cat_id_val').val(cat_id);
   
   var sub_cat_id = $(this).attr('sub_cat_id');
   var sub_cat_title = $(this).text();
   /* category Custom Field */
   $.ajax({
    url: baseurl+'/get-category-custom-field',
    type: 'POST',
     data: {cat_id: sub_cat_id}, 
     success: function(customfield) {
     $('.custom_fields').html(customfield);
        addCustomFieldValidation();
     }
    });
   
   
   $('.sub-cat-title').not(this).removeClass('sub_title_color');
   $(this).addClass('sub_title_color');
   $('#sub-category-text').text(sub_cat_title);
   $('#selectCategory').modal('hide');
   
   var elems = [];
   elems.push(cat_id);
   elems.push(sub_cat_id);
    $('#cat_id').val(elems);
   });
   
   $('#submit_btn').click(function(e) { 
   
    var images = $('#images').val();
    var cats = $('#cat_id').val();
    var sub_cats = $('#sub_cat_id_val').val();
   
    if(cats==''){
     $('.error_msg_cat').text('Please Select category');
     $('.error_msg_title').text('');
     $('.error_msg_image').text('');
     
   $('html, body').animate({
   	scrollTop: $('#select_cat').offset().top
   }, 1000);
     
     
    return false;
    }else if(sub_cats==''){
     $('.error_msg_cat').text('Please Select Sub category');
     $('.error_msg_title').text('');
     $('.error_msg_image').text('');
   
   $('html, body').animate({
   	scrollTop: $('#select_cat').offset().top
   }, 1000);
     
     return false;
    }else if(images==''){
     $('.error_msg_cat').text('');
     $('.error_msg_title').text('');
     $('.error_msg_image').text('Please Select at least one image');
   
   $('html, body').animate({
   	scrollTop: $('#select_image').offset().top
   }, 1000);
     
     return false;
    }else{
	 $('.error_msg_cat').text('');
     $('.error_msg_title').text('');
     $('.error_msg_image').text('');
    }
   });
   
   $('.showdiv').click(function(){
    $('.add_collapspart').slideDown();
    
   });
   
   
   $('.hidediv').click(function(){
	$('input[name=notify]').prop('checked',false);
    $('.add_collapspart').slideUp();
    $('#ad_total_cost_container').hide();
    $('#totalPrice').text('');
    
   });
   
   $('.fr_ad').click( function(){
    if ( $(this).hasClass('active') ) {
        $(this).removeClass('active');
    } else {
        $('.fr_ad.active').removeClass('active');
        $(this).addClass('active');    
    }
	});
	function fillPrice(obj, val) {

    if ($(obj).is(':checked')) {
        var a = $('#totalPrice').text(val);
    }
	
	$('#ad_amount').val(val);

    $('#ad_total_cost_container').show();
    if(a==0){
        $('#ad_total_cost_container').hide();
    }
	}";
   
   echo $this->registerJs($jss, View::POS_END);
   ?>
<script>
   $(".imgAdd").click(function(){
	   var max_image = $('#max_image').val();
	   var max_upload =  $(".imagePreview").length ;
		if(max_image == max_upload){
			$('.error_msg_image').html('You can Upload Only '+max_image+' Images');
			return false;
		}
	   
   $(this).closest(".row").find('.imgAdd').before('<div class="col-sm-3 imgUp"><div class="imagePreview"></div><label class="btn btn-primary">Upload<input type="file" name="images[]" class="uploadFile img" value="Upload Photo" style="width:0px;height:0px;overflow:hidden;"></label><i class="fa fa-times del"></i></div>');
   });
   $(document).on("click", "i.del" , function() {
   $(this).parent().remove();
   });
   $(function() {
     $(document).on("change",".uploadFile", function()
     {
     	 var uploadFile = $(this);
         var files = !!this.files ? this.files : [];
         if (!files.length || !window.FileReader) return; // no file selected, or no FileReader support
   
         if (/^image/.test( files[0].type)){ // only image file
             var reader = new FileReader(); // instance of the FileReader
             reader.readAsDataURL(files[0]); // read the local file
   
             reader.onloadend = function(){ // set image data as background of div
				//alert(uploadFile.closest(".upimage").find('.imagePreview').length);
				uploadFile.closest(".imgUp").find('.imagePreview').css("background-image", "url("+this.result+")");
             }
         }
     });
   });  
    
   $( document ).ready(function() {
   var ajaxurl= $('#uribase').val();
         var countryId = 113;
         if (countryId) {
             $.ajax({
                 url:  ajaxurl+'/country_change/' + countryId,
                 type: "POST",
                 dataType: "json",
                 success: function (data) {
                     $('select[id="postad-state"]').empty();
                      $('select[name="PostAd[state]"]').append('<option value="">Select State</option>');
                     $.each(data, function (key, value) {
                         $('select[name="PostAd[state]"]').append('<option value="' + key + '">' + value + '</option>');
                     });
                 }
             });
         } else {
             //$('select[name="city"]').empty();
         }
     });
     
     
   $('select[id="postad-state"]').on('change', function () {
   var ajaxurl= $('#uribase').val();
         var stateId = $(this).val();
         if (stateId) {
             $.ajax({
                 url:  ajaxurl+'/state_change/' + stateId,
                 type: "POST",
                 dataType: "json",
                 success: function (data) {
                     $('select[id="postad-city"]').empty();
                      $('select[name="PostAd[city]"]').append('<option value="">Select City</option>');
                     $.each(data, function (key, value) {
                         $('select[name="PostAd[city]"]').append('<option value="' + key + '">' + value + '</option>');
                     });
                 }
             });
         } else {
             //$('select[name="city"]').empty();
         }
     });
</script>
