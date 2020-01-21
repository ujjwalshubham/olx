<?php
use common\components\SidenavWidget; 
use common\components\ProfileheaderWidget; 

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\web\View;
use yii\helpers\Url;
use kartik\file\FileInput;

$this->title = Yii::t('frontend', 'Update Profile') . ' | OLX';
$session = Yii::$app->session;
$baseUrl=Yii::getAlias('@frontendUrl');
?>

<section id="main" class="category-page mid_container">
  <div class="container"> 
    <!-- breadcrumb -->
    <div class="breadcrumb-section">
      <ol class="breadcrumb">
        <li><a href="<?php echo \Yii::$app->request->BaseUrl; ?>"><i class="fa fa-home"></i> <?php echo Yii::t('frontend', 'Home') ?></a></li>
        <li> <?php echo $title?> </li>
        <div class="ml-auto back-result"><a href="javascript:void(0)"><i class="fa fa-angle-double-left"></i> <?php echo Yii::t('frontend', 'Back to Results') ?></a> </div>
      </ol>
    </div>
    <!-- breadcrumb -->
    
    <div class="row recommended-ads"> 
      <!-- side panel navigation -->
      <?php echo SidenavWidget::widget(); ?>
       <!-- side panel navigation -->
      
      <div class="col-md-8 col-lg-9">
        <div class="panel-user-details">
           <?php echo ProfileheaderWidget::widget(); ?>
             <?php if (Yii::$app->session->hasFlash('success')): ?>
              <div class="alert alert-success alert-dismissable">
					 <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
					 <?= Yii::$app->session->getFlash('success') ?>
				</div>
			<?php endif; ?>
			
			<?php if (Yii::$app->session->hasFlash('error')): ?>
				  <div class="alert alert-danger alert-dismissable">
						 <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
						 <?= Yii::$app->session->getFlash('error') ?>
				  </div>
			<?php endif; ?>
           
   
           <div class="user-details section">
			   <div class="my-details">
				  <div class="form-group row">
					 <label class="col-sm-3 control-label"><?php echo Yii::t('frontend', 'Email Address') ?><span class="required">*</span></label>
					 <div class="col-sm-9">
						 <div class="varify_mainpart">
								<?php if($user['email_verify']==1){?>
								<div class="varifyinnerbtn"> <button type="button" id="email_<?php echo $user['id']?>" data-keyid="<?php echo $user['id']?>" class="btn btn_verifyind profile_edit_input" id="add_email" data-id="<?php echo $user['id']?>"> <?php echo Yii::t('frontend', 'Update Email') ?> </button> </button></a></div>
								<?php } else {?>
									
								<div class="varifyinnerbtn"> <button type="button" id="email_<?php echo $user['id']?>" data-keyid="<?php echo $user['id']?>" class="btn btn_verifyind profile_edit_input" id="add_email" data-id="<?php echo $user['id']?>"> <?php echo Yii::t('frontend', 'Add Email') ?> </button> </div>
								<?php } ?>
						 </div>
							<input class="form-control border-form" placeholder="Email" value="<?php echo $user['email']?>" disabled>
						<div class="err_email" style="color:#f95858"></div>
					 </div>
					 
				  </div>
				  
				  <div class="form-group row">
					 <label class="col-sm-3 control-label"><?php echo Yii::t('frontend', 'Phone Number') ?><span class="required">*</span></label>
					 <div class="col-sm-9">
						<input class="form-control border-form" value="<?php echo $user['mobile']?>"  type="text" disabled>
					 </div>
				  </div>
			   </div>
			</div>
          
          <?php $form = ActiveForm::begin(['id' => 'update-profile', 'options' => ['role' => 'form','enctype' => 'multipart/form-data'], 'fieldConfig' => ['options' => ['tag' => 'span']]]); ?>
          <div class="user-details section">
            <div class="my-details">
              <h2><?php echo Yii::t('frontend', 'My Details') ?></h2>
              
              <div class="section-body">
                  <div class="form-group row">
                    <label class="col-sm-3 control-label"><?php echo Yii::t('frontend', 'Full Name') ?>  <span class="required">*</span></label>
                    <div class="col-sm-9">
                       <?php echo $form->field($userDetail, 'name', ['template' => '{input}{error}','inputOptions' => ['class' => 'form-control border-form']])->label(false) ?>
                    </div>
                  </div>
                
                <div class="form-group row">
					<label class="col-sm-3 control-label"><?php echo Yii::t('frontend', 'Avatar') ?> </label>
					<div class="col-sm-9">
						<div class="file-upload">
						  <div class="file-select">
							  <?php if(!empty($userDetail['avatar_path'])){
								$array_path = explode('/',$userDetail['avatar_path']);
							   ?>
							  <div class="file-select-name newfile"><?php echo end($array_path)?> </div> 
							  <?php } else {?>
							    <div class="file-select-name" id="noFile"><?php echo Yii::t('frontend', 'No file chosen...') ?> </div> 
							  <?php } ?>
							<div class="file-select-button" id="fileName"><?php echo Yii::t('frontend', 'Choose File') ?></div>
							<?php echo $form->field($userDetail, 'avatar_path', ['template' => '{input}{error}','inputOptions' => ['class' => 'filestyle','id'=>'chooseFile']])->fileInput()->label(false) ;?>
						  </div>
						</div>
					</div>
			     </div>
                  
                  <div class="form-group row">
                    <label class="col-sm-3 control-label"><?php echo Yii::t('frontend', 'Address') ?>  <span class="required">*</span></label>
                    <div class="col-sm-9">
                      <?php echo $form->field($userDetail, 'address', ['template' => '{input}{error}','inputOptions' => ['class' => 'form-control border-form']])->label(false) ?>
                    </div>
                  </div>
                  
                  <div class="form-group row">
                    <label class="col-sm-3 control-label"><?php echo Yii::t('frontend', 'Website') ?> </label>
                    <div class="col-sm-9">
                      <?php echo $form->field($userDetail, 'website', ['template' => '{input}{error}','inputOptions' => ['class' => 'form-control border-form']])->label(false) ?>
                    </div>
                  </div>
                  
                   <div class="form-group row">
                    <label class="col-sm-3 control-label"><?php echo Yii::t('frontend', 'About') ?> </label>
                    <div class="col-sm-9">
						<?php echo $form->field($userDetail, 'about')->widget(
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
              </div>
            </div>
           
            <div class="my-details">
              <h2><?php echo Yii::t('frontend', 'Social Networks') ?></h2>
              <div class="section-body">
                  <div class="form-group row">
                    <label class="col-sm-3 control-label"><?php echo Yii::t('frontend', 'Facebook') ?> </label>
                    <div class="col-sm-9">
                       <?php echo $form->field($userDetail, 'facebook_url', ['template' => '{input}{error}','inputOptions' => ['class' => 'form-control border-form']])->label(false) ?>
                    </div>
                  </div>
                  <div class="form-group row">
                    <label class="col-sm-3 control-label"><?php echo Yii::t('frontend', 'Twitter') ?> </label>
                    <div class="col-sm-9">
                       <?php echo $form->field($userDetail, 'twitter_url', ['template' => '{input}{error}','inputOptions' => ['class' => 'form-control border-form']])->label(false) ?>
                    </div>
                  </div>
                  <div class="form-group row">
                    <label class="col-sm-3 control-label"><?php echo Yii::t('frontend', 'Google+') ?></label>
                    <div class="col-sm-9">
                       <?php echo $form->field($userDetail, 'google_plus_url', ['template' => '{input}{error}','inputOptions' => ['class' => 'form-control border-form']])->label(false) ?>
                    </div>
                  </div>
                  <div class="form-group row">
                    <label class="col-sm-3 control-label"><?php echo Yii::t('frontend', 'Instagram') ?> </label>
                    <div class="col-sm-9">
                       <?php echo $form->field($userDetail, 'instagram_url', ['template' => '{input}{error}','inputOptions' => ['class' => 'form-control border-form']])->label(false) ?>
                    </div>
                  </div>
                  <div class="form-group row">
                    <label class="col-sm-3 control-label"><?php echo Yii::t('frontend', 'Linkedin') ?> </label>
                    <div class="col-sm-9">
                       <?php echo $form->field($userDetail, 'linkedin_url', ['template' => '{input}{error}','inputOptions' => ['class' => 'form-control border-form']])->label(false) ?>
                    </div>
                  </div>
                  <div class="form-group row">
                    <label class="col-sm-3 control-label"><?php echo Yii::t('frontend', 'Youtube') ?></label>
                    <div class="col-sm-9">
                       <?php echo $form->field($userDetail, 'youtube_url', ['template' => '{input}{error}','inputOptions' => ['class' => 'form-control border-form']])->label(false) ?>
                    </div>
                  </div>
              </div>
            </div>
            
            <div class="my-details">
              <h2><?php echo Yii::t('frontend', 'Newsletter') ?></h2>
              <div class="section-body">
                <div class="checkbox checkbox-primary">
                   <?php
					$newsletter=Yii::t('frontend','Notify me by e-mail when a ad gets posted that is relevant to my choice.');
					echo $form->field($userDetail, 'newsletter',['template'=>'{input}{label}'])->checkbox(['label' => null])->label($newsletter); ?>
                </div>
                <div class="form-group">
					<?php echo Html::submitButton(Yii::t('frontend', 'Update'),
					['id'=>"otp-submit",'class' => 'btn btn-outline', 'name' => 'login-button']);?>
                  <a href="javascript:void(0)" class="btn btn-outline cancel"><?php echo Yii::t('frontend', 'Cancel') ?> </a> 
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

<!-- OTP popup --->
<div id="email-otp-popup" class="modal fade login_formcontent" role="dialog">
  <div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title"><?php echo Yii::t('frontend', 'Otp Verification') ?></h4>
      </div>
      <div class="modal-body" id="otp-verify-form">
        <div class="login_formarea">
			<div class="form-group">
				<div class="err_email_new" style="color:#f95858"></div>
				<input type="text" class="form-control border-form" placeholder="Please Enter otp" name="email_otp" id="email_otp">
			</div>
			<div class="form-group login_submit">
				<?php echo Html::submitButton(Yii::t('frontend', 'Verify'),
				['id'=>"otp-submit-otp",'class' => 'verify-otp btn bt_blue', 'name' => 'login-button']);?>
			</div>
		</div>
      </div>
    </div>
  </div>
</div>

<?php $form = ActiveForm::begin(['id' => 'update-profile', 'options' => ['role' => 'form','enctype' => 'multipart/form-data'], 'fieldConfig' => ['options' => ['tag' => 'span']]]); ?>
<!--Email popup --->
<div id="email-popup" class="modal fade login_formcontent" role="dialog">
  <div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title"><?php echo Yii::t('frontend', 'Update Email') ?></h4>
      </div>
      <div class="modal-body" id="otp-verify-form">
        <div class="login_formarea">
			<div class="form-group">
				<div class="err_email_new" style="color:#f95858"></div>
					<?php echo $form->field($user, 'email', ['inputOptions' => ['class' => 'form-control border-form','placeholder'=>'Email']])->label(false) ?> 
			</div>
			<div class="form-group login_submit">
				<?php echo Html::submitButton(Yii::t('frontend', 'Save'),
				['id'=>"email-submit",'class' => 'verify-email btn bt_blue']);?>
			</div>
		</div>
      </div>
    </div>
  </div>
</div>

<div class="register-section">
    <div id="edit-input-modal" class="modal fade model_opacity"  role="dialog">
    </div>
</div>
<?php ActiveForm::end();?>

<?php
   $jss = "$(document).on('click','.profile_edit_input',function(){
 
    var data=this.id;
    var splitid=data.split('_');
    var type=splitid[0];
    var user_id=$(this).attr('data-keyid');
    baseurl= $('#uribase').val();
  
    $.ajax({
        url: baseurl + '/user/profile-field-edit',
        dataType: 'html',
        method: 'POST',
        data: { type:type,user_id :user_id,user_id:user_id},
        beforeSend: function() {
            $('#edit-input-modal').css('opacity','0.8');
            $('#main-js-preloader').css('display','block');
        },
        success: function (response) {
            $('#edit-input-modal').css('opacity','1');
            $('#edit-input-modal').html('');
            $('#edit-input-modal').append(response);
            $('span.select2-container').css('z-index','999');
            $('#edit-input-modal').modal({backdrop: 'static',keyboard: false,show: true})
            $('#main-js-preloader').css('display','none');
        }
    });
});
   
   
   
   $(document).on('click', '#add_email', function() {
		$('#email-popup').modal('show'); 
   });
   
   $(document).on('click', '#verify_btn', function() {
   var email = $('#user-email').val();
	  if( $('.field-user-email').hasClass('has-error')){
		 
	  }
	  else {
		   if(email==''){
			 $('.err_email').html('Please Enter Email');
			 return false;
	   }
	   
	   if(IsEmail(email)==false){
			 $('.err_email').html('Please Enter Valid Email Address');
			 return false;
		}
		
		 $.ajax({
				  url: '$baseUrl/email-unique',
				  type: 'POST',
				  data: {email: email}, 
				  success: function(data) {
				  var obj = JSON.parse(data);
				   
				  if(obj.email==true){
					  $('.err_email').html('This Email is already taken.');
					  return false;  
				   }
				  else {        
					 $('#email-otp-popup').modal('show'); 
						 $.ajax({
								  url: '$baseUrl/email-otp-verify',
								  type: 'POST',
								   data: {email: email}, 
								   success: function(data) {
									 $('.err_email_new').html('Please Check Your Email for otp.');
								   }
							   });
						}
				   }
			   });
		}
   });
   
   
   $(document).on('click', '.verify-email', function() {
   var otp = $('#email_otp').val();
   var email = $('#user-email').val();
   
   if(otp==''){
	   $('.err_email_new').html('Please Enter Otp');
	   return false;
   }
   
   if(!$.isNumeric(otp)){
		 $('.err_email_new').html('Please Enter Valid Otp');
	     return false;
	}
	else{
   	 $.ajax({
              url: '$baseUrl/check-email-otp',
              type: 'POST',
               data: {email: email,otp:otp}, 
               success: function(data) {
                var obj = JSON.parse(data);
				if(obj.message==true){
					$('.err_email').html('Email Verified Successfully');
					$('#email-otp-popup').modal('hide'); 
					
					setTimeout(function(){
					   location.reload();
				   },5000);
			   }else{
				    $('.err_email_new').html('Invalid Otp');
				    return false;  
				  }
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
   ";
   
   echo $this->registerJs($jss, View::POS_END);
   ?>
   
<script>$('#chooseFile').bind('change', function () {
	
 var filename = $("#chooseFile").val();
  
 var name = filename.replace("C:\\fakepath\\", "");
 $('.newfile').html(name);
  if (/^\s*$/.test(filename)) {
    $(".file-upload").removeClass('active');
    $("#noFile").text("No file chosen..."); 
  }
  else {
    $(".file-upload").addClass('active');
    $("#noFile").text(filename.replace("C:\\fakepath\\", "")); 
  }
});
</script>
