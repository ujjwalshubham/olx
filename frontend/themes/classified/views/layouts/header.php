<!-- header section start-->
<?php
   use yii\helpers\Html;
   use yii\helpers\Url;
   use yii\web\View;
   use common\models\Languages;
   use common\models\User;
   use common\models\Settings;
   use common\components\AppHelper;
   use yii\widgets\ActiveForm;
   use frontend\modules\user\models\SignupForm;
   use frontend\modules\user\models\LoginForm;
   
   $signup= new SignupForm();

   if (Yii::$app->User->id) {
       $UserProfile = Yii::$app->user->identity->userProfile;
   }
   
    $userId = Yii::$app->User->id;
    $userDetail = User::getUserDetails($userId);
    
    $languages = Languages::getAllLanguages();
    //echo "<pre>"; print_r($_COOKIE);exit;
    
	  $baseurl =\Yii::$app->getUrlManager()->getBaseUrl(); 
	  $baseUrl=Yii::getAlias('@frontendUrl');
	  $scriptbaseUrl="'".$baseUrl."'";
	  $signupOtp="'".$baseUrl."/ajax-signup'";

	  $login="'".$baseUrl."/login-ajax'";
	  $baseUrl=Yii::getAlias('@frontendUrl');
	  
	  $model = new LoginForm();
	  $model->scenario = 'otp';
	  
	  $page_size = Settings::getPageSize();
	  
   //$model->identity=$user->mobile;
   //$sendOtp="'".$baseUrl."/otp-verify'";
   ?>
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.1/jquery.min.js"></script>
<script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/jquery-ui.min.js"></script>
<header id="header">
   <div class="container">
      <nav class="navbar navbar-expand-md navbar-light header_nav ">
         <a class="navbar-brand" href="<?php echo $baseurl; ?>">
         <?php $logo=\common\components\AppHelper::getSiteLogo();?>
         <img src="<?php echo$logo; ?>" alt="logo" title="logo">
         </a>
         <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">  <span class="navbar-toggler-icon"></span>  </button>
         <input type="hidden" id="uribase" value="<?php echo $baseurl; ?>"> 
         <!--<div class="flag-menu">
            <button class="hamburger hamburger--collapse country-flag" id="#selectCountry" data-toggle="modal" data-target="#selectCountry">
            	<img src="<?php echo \Yii::$app->request->BaseUrl; ?>/images/flags/in.png">
            </button>
            </div>-->
         <div class="collapse navbar-collapse justify-content-end " id="navbarSupportedContent">
            <ul class="navbar-nav align-items-center">
               <?php if (Yii::$app->user->isGuest) { ?>	  
               <!-- <li class="nav-item ">
                  <a class="nav-link" data-toggle="modal" data-target="#login-popup" href="#"><i class="fa fa-sign-in"></i> <?php echo Yii::t('frontend', 'Login')?></a>
                  </li>-->
               <li class="nav-item">
                  <a class="nav-link" data-toggle="modal" data-target="#register-popup" href="#"><i class="fa fa-sign-in"></i><?php echo Yii::t('frontend', 'Register/Login')?></a>
               </li>
               <?php } else {?>
               <!-- <li class="nav-item mrgn_rt_prfl"> <a class="nav-link" href="#"><i class="fa fa-envelope" aria-hidden="true"></i> Message</a> </li>-->
               <li class="dropdown mrgn_rt_prfl">
				  <?php $profile_image = AppHelper::getUserProfileImage($userId);?>	 
                  <a href="javascript:void(0);" class="dropdown-toggle drp_tgl_no" data-toggle="dropdown">
                     <?php //echo "<pre>"; print_r($UserProfile);?>
                     <div class="drp_prf_mn">
                        <span class="img_spn_prfl"> 
                        <img src="<?php echo $profile_image?>" alt="<?php echo $UserProfile['name']?>"> 
                        </span> 
                        <span class="prsn_nm_prfl">
                           <!--<?php echo Yii::$app->user->identity->username?>-->
                           <?php echo  $UserProfile['name']?>
                        </span>
                        <i class="fa fa-angle-down" aria-hidden="true"></i> 
                     </div>
                  </a>
                  <ul class="dropdown-menu">
                     <?php if(!empty($userDetail['name'])  && (!empty($userDetail['email'])) && (!empty($userDetail['address']))){?>
                     <li><?php echo Html::a(Html::tag('i', '', ['class' => 'fa fa-plus-circle']) . '' .\Yii::t('frontend', 'Post Free Ad'), ['/user/post-ad'], ['class' => '']); ?></li>
                     <?php } else { ?>
                     <li><a class="a_pointer" onclick="alert('Please Update profile First')" ><i class="fa fa-plus-circle"></i><?php echo Yii::t('frontend', 'Post Free Ad')?> </a></li>
                     <?php }?>
                     <li><?php echo Html::a(Html::tag('i', '', ['class' => 'fa fa-home']) . '' .\Yii::t('frontend', 'Dashboard'), ['/user/dashboard'], ['class' => '']); ?></li>
                     <li><?php echo Html::a(Html::tag('i', '', ['class' => 'fa fa-book']) . '' .\Yii::t('frontend', 'My Ads'), ['/user/my-ads'], ['class' => '']); ?></li>
                     <li><?php echo Html::a(Html::tag('i', '', ['class' => 'fa fa-user']) . '' .\Yii::t('frontend', 'My Profile'), ['/user/my-profile'], ['class' => '']); ?></li>
                     <li><?php echo Html::a(Html::tag('i', '', ['class' => 'fa fa-shopping-bag']) . '' .\Yii::t('frontend', 'Membership'), ['/user/membership'], ['class' => '']); ?></li>
                     <li><?php echo Html::a(Html::tag('i', '', ['class' => 'fa fa-money']) . '' .\Yii::t('frontend', 'Transactions'), ['/user/transactions'], ['class' => '']); ?></li>
                     <!--<li><?php echo Html::a(Html::tag('i', '', ['class' => 'fa fa-cog']) . '' .\Yii::t('frontend', 'Account Setting'), ['/user/account-setting'], ['class' => '']); ?></li>-->
                     <li><?php echo Html::a(Html::tag('i', '', ['class' => 'fa fa-unlock']) . '' .\Yii::t('frontend', 'Logout'), ['/logout'], ['data-method' => 'post']); ?></li>
                  </ul>
               </li>
               <!--<li class="nav-item ">
                  <?php echo Html::a(\Yii::t('frontend', 'My Profile'), ['/user/' . Yii::$app->user->identity->username], ['class' => 'nav-link']); ?>
                  </li>
                  <li class="nav-item">
                  <?php echo Html::a(\Yii::t('frontend', 'Logout'), ['/logout'], ['class' => 'nav-link', 'data-method' => 'post']); ?>
                  </li>-->
               <?php } ?>
               <?php if(!empty($userId)){?>
               <?php if(!empty($userDetail['name'])  && (!empty($userDetail['email']))  && (!empty($userDetail['address']))){?>
               <li class="nav-item">
                  <?php echo Html::a(Html::tag('i', '', ['class' => 'fa fa-plus-circle']) . '' .\Yii::t('frontend', 'Post Free Ad'), ['/user/post-ad'], ['class' => 'nav-link post_ad_bt']); ?>
               </li>
               <?php } else {?>
               <li class="nav-item">
                  <a class="nav-link post_ad_bt a_pointer" onclick="alert('Please Update profile First')"><?php echo Yii::t('frontend', 'Post Free Ad')?> <i class="fa fa-plus-circle"></i></a>
               </li>
               <?php } ?>
               <?php } else {?>
               <li class="nav-item">
                  <a class="nav-link post_ad_bt a_pointer" data-toggle="modal" data-target="#register-popup"><?php echo Yii::t('frontend', 'Post Free Ad')?> <i class="fa fa-plus-circle"></i></a>
               </li>
               <?php } ?>
            </ul>
            <?php $cookies = Yii::$app->request->cookies;
               if(isset($cookies['_locale']) && !empty($cookies['_locale'])){
					$lang = $cookies['_locale']->value;
               }else{
					$lang = 'en';	
               }
               
               if($lang=='en'){
					$lang = 'English';
               }else if($lang=='pt-BR'){
					$lang = 'Português';
               }?>
            <div class="dropdown lang-dropdown" id="lang-dropdown">
               <button class="btn dropdown-toggle btn-default-lite" type="button" data-toggle="dropdown" ><span id="selected_lang" class="aaaa"><?php echo ucwords($lang);?></span></button>
               <ul class="dropdown-menu dropdown-menu-right" role="menu" aria-labelledby="dropdownMenu1">
                  <?php foreach($languages as $key=>$language){?>
                  <li><a role="menuitem" tabindex="-1" rel="alternate" class="select_lang" lang-code="<?php echo $language['code'] ?>" href="<?php echo Url::to('@frontendUrl') ?>/site/set-locale?locale=<?php echo $language['code'] ?>"><?php echo $language['title']?></a></li>
                  <?php } ?>
               </ul>
            </div>
         </div>
      </nav>
   </div>
</header>
<!-- login popup --->
<div id="login-popup" class="modal fade login_formcontent" role="dialog">
   <div class="modal-dialog">
      <!-- Modal content-->
      <div class="modal-content">
         <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title"> <?php echo Yii::t('frontend', 'Login')?></h4>
         </div>
         <div class="modal-body">
            <div class="login_formarea">
               <div class="form-group">
                  <input class="form-control" type="text" placeholder="Mobile Number">
               </div>
               <div class="form-group login_submit">
                  <input type="Submit" value="Submit" class="btn bt_blue">
               </div>
            </div>
         </div>
      </div>
   </div>
</div>
<!-- Register popup --->
<div id="register-popup" class="modal fade login_formcontent" role="dialog">
   <div class="modal-dialog">
      <?php $form = ActiveForm::begin(['id'=>"signupform",'action'=>'','method'=>"post",'enableAjaxValidation' => true,'class'=>'form-horizontal']); ?>
      <!-- Modal content-->
      <div class="modal-content">
         <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title"><?php echo Yii::t('frontend', 'Register/Login')?></h4>
         </div>
         <div class="modal-body">
            <div class="login_formarea">
               <div class="form-group">
                  <?php echo $form->field($signup, 'mobile', ['template' => '{input}{error}', 'inputOptions' => ['class' => 'form-control register-mobile','placeholder' => Yii::t('frontend', 'Mobile No'),  'maxlength'=>40]]) ?>
               </div>
               <p class="mob_error help-block"></p>
               <div class="form-group login_submit">
                  <?php echo Html::Button(Yii::t('frontend', 'Register'), ['id'=>'signup_from','class' => 'btn bt_blue', 'name' => 'signup-button']) ?>
               </div>
            </div>
         </div>
      </div>
      <?php ActiveForm::end(); ?>
   </div>
</div>
<!-- OTP popup --->
<div id="otp-popup" class="modal fade login_formcontent" role="dialog">
   <div class="modal-dialog">
      <!-- Modal content-->
      <div class="modal-content">
         <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title"> <?php echo Yii::t('frontend', 'Otp Verification')?></h4>
         </div>
         <div class="modal-body" id="otp-verify-form">
            <div class="login_formarea">
               <div class="form-group">
                  <?php echo $form->field($model, 'identity')->hiddenInput()->label(false);?>
                  <?php echo $form->field($model, 'otp')->textInput(['placeholder'=>'Please enter otp'])->label('Enter the 4-digit code send via SMS on ');?>
               </div>
               <div class="form-group login_submit">
                  <?php echo Html::submitButton(Yii::t('frontend', 'Verify'),
                     ['id'=>"otp-submit",'class' => 'login-sumbit btn bt_blue', 'name' => 'login-button']);?>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>
<?php
   $jss = "
   $('#register-popup').on('hidden.bs.modal', function () {
		$(this).find('form').trigger('reset');
		$(this).find('.mob_error').html('');
	});
   
    $('#signupform-mobile').keydown(function(event) {
     if (event.keyCode == 32) {
         event.preventDefault();
		 }
	 });
	
   
   $('#signup_from').on('click',function(){
   var mobNum = $('#signupform-mobile').val();
   
   var filter = /^\d*(?:\.\d{1,2})?$/;
   
   if(mobNum =='') {
		$('.mob_error').html('Please enter 10  digit mobile number');
		return false;
	}
   
   if(!mobNum.match(/^[1-9][0-9]*$/)) {
		$('.mob_error').html('Not a valid Number');
		return false;
	}
    
    if (filter.test(mobNum)) {
            if(mobNum.length==10){
            $.ajax({
            method: 'POST',
            url: $signupOtp,
            data: $('#signupform').serialize(),
        })
        .done(function( resJson ) { 
            if(resJson){
               var obj = jQuery.parseJSON(resJson);
               if(obj.status && obj.error){
			   }else{
                  $('#register-popup').modal('hide');
                  $('#otp-verify-form').html('');
                  $('#otp-verify-form').html(obj.data);
                  $('#otp-popup').modal({backdrop: 'static',keyboard: false,show: true})
               }
          }
      });
   } else {
     $('.mob_error').html('Please enter 10  digit mobile number');
     $('#folio-invalid').removeClass('hidden');
     $('#mobile-valid').addClass('hidden');
		return false;
    }
   }
   else {
    $('.mob_error').html('Not a valid Number');
		return false;
    }
   });
   
   
   $('#signupform-mobile').keypress(function(e){
	   if (this.value.length == 0 && e.which == 48 ){
		  return false;
	   }
	});
	
	$( '#signupform-mobile' ).keyup(function() {
		var length = this.value.length;
		if(length==10 && $.isNumeric(this.value)){
			$('.mob_error').html('');
			return false;
		}
	});
   
   $('.register-mobile').keypress(function (e) {
		if (e.which == 13) {
	  
	    var mobNum = $('#signupform-mobile').val();
	    var filter = /^\d*(?:\.\d{1,2})?$/;
	  
		if(mobNum =='') {
			$('.mob_error').html('Please enter 10  digit mobile number');
			return false;
		} else  if(!mobNum.match(/^[1-9][0-9]*$/)) {
			$('.mob_error').html('Not a valid Number');
			return false;
		} else if(mobNum.length != 10){
			$('.mob_error').html('Please enter 10  digit mobile number');
			return false;
		}else {
		   $.ajax({
				method: 'POST',
				url: $signupOtp,
				data: $('#signupform').serialize(),
			})
			.done(function( resJson ) { 
				if(resJson){ 
				   var obj = jQuery.parseJSON(resJson);
				   if(obj.status && obj.error){
					  
				  }else{
					  $('#register-popup').modal('hide');
					  $('#otp-verify-form').html('');
					  $('#otp-verify-form').html(obj.data);
					  $('#otp-popup').modal({backdrop: 'static',keyboard: false,show: true})
				  }
			  }
			});
		  }}
	});
   
   $(document).on('click', '.select_lang', function() {
   	var lang_code = $(this).attr('lang-code');
   	//$('.aaaa').text(lang_code);
   });";
   
   echo $this->registerJs($jss, View::POS_END);
   ?>
