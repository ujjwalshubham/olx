<?php
   use yii\helpers\Html;
   use yii\widgets\ActiveForm;
   
   /* @var $this yii\web\View */
   /* @var $form yii\widgets\ActiveForm */
   /* @var $model \frontend\modules\user\models\LoginForm */
   
   $this->title = Yii::t('frontend', 'User Login');
   $this->params['breadcrumbs'][] = $this->title;
   ?>
<section id="main" class="category-page mid_container">
   <div class="container">
      <?php if (Yii::$app->session->hasFlash('success')): ?>
      <div class="alert alert-success alert-dismissable">
         <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
         <?php echo Yii::$app->session->getFlash('success') ?>
      </div>
      <?php endif; ?>
      <?php if (Yii::$app->session->hasFlash('danger')): ?>
      <div class="alert alert-danger alert-dismissable">
         <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
         <?php echo Yii::$app->session->getFlash('danger') ?>
      </div>
      <?php endif; ?>
      <div class="user_account">
         <div class="col-sm-8 col-md-6 offset-sm-2 offset-md-3">
            <div class="user-account">
               <h2><?php echo Html::encode($this->title) ?></h2>
               <div class="social-signup" style="padding-bottom: 20px;">
                  <div class="row">
                     <div class="col-sm-6"><a class="loginBtn loginBtn--facebook" onclick="fblogin()"><i class="fa fa-facebook"></i> <span><?php echo Yii::t('frontend', 'Facebook')?></span></a></div>
                     <div class="col-sm-6"><a class="loginBtn loginBtn--google" onclick="gmlogin()"><i class="fa fa-google"></i> <span><?php echo Yii::t('frontend', 'Google')?></span></a></div>
                  </div>
                  <div class="clear"></div>
               </div>
               <!-- form -->
               <?php $login = ActiveForm::begin(['id' => 'form-login', 'options' => ['class' => 'login_con'], 'enableAjaxValidation' => true]); ?>
               <div class="form-group">
                  <?php echo $login->field($model, 'identity', ['template' => '{input}{error}', 'inputOptions' => ['class' => 'form-control','placeholder'=>Yii::t('frontend', 'Mobile / E-mail')]]) ?>
               </div>
               <div class="form-group">
                  <?php echo $login->field($model, 'password', ['template' => '{input}{error}', 'inputOptions' => ['class' => 'form-control','placeholder'=>Yii::t('frontend', 'Password')]])->passwordInput(); ?>
               </div>
               <button type="submit" name="submit" id="submit" class="btn bt_blue "><?php echo Yii::t('frontend', 'Login')?></button>
               <?php ActiveForm::end(); ?>
               <!-- form -->
               <!-- forgot-password -->
               <div class="user-option">
                  <div class="checkbox pull-left">
                     <label for="logged"><input type="checkbox" name="logged" id="logged"><?php echo Yii::t('frontend', 'Keep me logged in')?></label>
                  </div>
                  <div class="pull-right forgot-password"><a href="<?php echo  Yii::$app->urlManager->createAbsoluteUrl(['forgot-password']);  ?>"><?php echo Yii::t('frontend', 'Forgot Password?')?></a>
                  </div>
               </div>
               <!-- forgot-password -->
            </div>
            <a href="<?php echo  Yii::$app->urlManager->createAbsoluteUrl(['signup']);  ?>" class="btn bt_blue create_bt"><?php echo Yii::t('frontend', 'Create a New Account')?></a>
         </div>
      </div>
   </div>
</section>
