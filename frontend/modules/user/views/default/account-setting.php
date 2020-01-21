<?php
use common\components\SidenavWidget; 
use common\components\ProfileheaderWidget; 

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\web\View;
use yii\helpers\Url;
use kartik\file\FileInput;

$this->title = Yii::t('frontend', 'Account Setting') . ' | OLX';
?>

<section id="main" class="category-page mid_container">
  <div class="container"> 
    
    <!-- breadcrumb -->
    <div class="breadcrumb-section">
      <ol class="breadcrumb">
        <li><a href="<?php echo \Yii::$app->request->BaseUrl; ?>"><i class="fa fa-home"></i> Home</a></li>
        <li><?php echo $title?></li>
        <div class="ml-auto back-result">
			<a href=""><i class="fa fa-angle-double-left"></i> Back to Results</a> 
		</div>
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
          
          <div class="user-details section">
            <div class="my-details">
              <h2><?php echo Yii::t('frontend', 'Change Password') ?></h2>
              <div class="section-body">
                <?php $form = ActiveForm::begin(['id' => 'account-setting', 'options' => ['role' => 'form'], 'fieldConfig' => ['options' => ['tag' => 'span']]]); ?>
                <?php if (Yii::$app->session->hasFlash('success')): ?>
					<div class="alert alert-success alert-dismissable">
						<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
					<?php echo Yii::$app->session->getFlash('success') ?>
					</div>
				<?php endif; ?>
                
                
                  <div class="form-group row">
                    <label class="col-sm-3 control-label"><?php echo Yii::t('frontend', 'Old Password') ?> <span class="required">*</span></label>
                    <div class="col-sm-9">
                     <?php echo $form->field($model, 'oldpassword')->textInput(['type' => 'password', 'placeholder' => '**********'])->label(false); ?>
                    </div>
                  </div>
                  <div class="form-group row">
                    <label class="col-sm-3 control-label"><?php echo Yii::t('frontend', 'New Password') ?> <span class="required">*</span></label>
                    <div class="col-sm-9">
                     <?php echo $form->field($model, 'password')->textInput(['type' => 'password', 'placeholder' => '**********'])->label(false); ?>
                    </div>
                  </div>
					
					
                  <div class="form-group row">
                    <label class="col-sm-3 control-label"><?php echo Yii::t('frontend', 'Confirm New Password') ?> <span class="required">*</span></label>
                    <div class="col-sm-9">
                      <?php echo $form->field($model, 'cpassword')->textInput(['type' => 'password', 'placeholder' => '**********'])->label(false); ?>
                    </div>
                  </div>
					
                  <div class="form-group">
					  <div class="col-sm-9 offset-sm-3">
                  <?php echo Html::submitButton(Yii::t('frontend', 'Update Account'), ['class' => 'btn btn-outline']) ?>
                  <a href="#" class="btn btn-outline cancel"><?php echo Yii::t('frontend', 'Cancel') ?></a> </div>
					</div>
                  
               <?php ActiveForm::end(); ?>
              </div>
            </div>
           
           
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
