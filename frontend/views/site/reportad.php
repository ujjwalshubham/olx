<?php
   use yii\helpers\Html;
   use yii\widgets\ActiveForm;
   use common\models\PostAd;
   use common\models\User;
   
   
   /* @var $this yii\web\View */
   /* @var $form yii\widgets\ActiveForm */
   /* @var $model \frontend\models\ContactForm */
   
   $this->title = 'Report Ad';
   $this->params['breadcrumbs'][] = $this->title;
   $baseurl =\Yii::$app->getUrlManager()->getBaseUrl(); 
   
   $url = $_SERVER['HTTP_REFERER'];
   $link_array = explode('/',$url);
   $slug = end($link_array);
   
   $adDetail = (new \yii\db\Query())
   			->select(['*'])
   			->from('post_ads')
   			->where(['slug' => $slug])
   			->andWhere(['status' => PostAd::STATUS_ACTIVE])
   			->one();
   $userId = Yii::$app->user->identity['id'];
   $userDetail = User::getUserDetails($userId);
   
   $adUserDetail = User::getUserDetails($adDetail['user_id']);
   ?>
<section id="main" class="category-page mid_container">
   <div class="container">
      <?php if (Yii::$app->session->hasFlash('alert')):
         $alert = Yii::$app->session->getFlash('alert');
         ?>
      <div class="alert <?php echo isset($alert['class']) ? $alert['class'] : ""; ?> alert-dismissable">
         <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
         <b><i class="icon fa fa-check"></i>Saved!</b>
         <?php echo isset($alert['body']) ? $alert['body'] : ""; ?>
      </div>
      <?php
         endif;
         ?>
      <div class="row recommended-ads">
         <div class="col-sm-8 offset-sm-2">
            <div class="section report_ad ">
               <h3 class="text-center"><strong><?php echo Yii::t('frontend', 'Report Violation')?></strong></h3>
               <br>
               <?php $form = ActiveForm::begin(['id' => 'reportad-form']); ?>
               <div class="row form-group">
                  <label class="col-sm-3 control-label"><?php echo Yii::t('frontend', 'Your Name')?></label>
                  <div class="col-sm-9">
                     <?php echo $form->field($model, 'name', ['inputOptions' => ['class' => 'form-control border-form','value'=> $userDetail['name'],'readonly'=> true,'placeholder'=>Yii::t('frontend', 'Full Name')]])->label(false) ?>
                  </div>
               </div>
               <div class="row form-group">
                  <label class="col-sm-3 control-label"><?php echo Yii::t('frontend', 'Your E-Mail')?></label>
                  <div class="col-sm-9">
                     <?php echo $form->field($model, 'email', ['inputOptions' => ['class' => 'form-control border-form','value'=> $userDetail['email'],'readonly'=> true,'placeholder'=>Yii::t('frontend', 'Email')]])->label(false) ?>
                  </div>
               </div>
               <div class="row form-group">
                  <label class="col-sm-3 control-label"><?php echo Yii::t('frontend', 'Your Username')?></label>
                  <div class="col-sm-9">
                     <?php echo $form->field($model, 'username', ['inputOptions' => ['class' => 'form-control border-form','value'=> $userDetail['username'],'readonly'=> true,'placeholder'=>Yii::t('frontend', 'Username')]])->label(false) ?>
                  </div>
               </div>
               <div class="row form-group">
                  <label class="col-sm-3 control-label"><?php echo Yii::t('frontend', 'Violation Type')?></label>
                  <div class="col-sm-9">
                     <div class="slects_box">
                        <span>
                        <?php $options = ['Posting contact information' => 'Posting contact information', 'Advertising another website' => 'Advertising another website','Fake ad posted'=>'Fake ad posted','Non-featured ad posted requiring abnormal bidding'=>'Non-featured ad posted requiring abnormal bidding','other'=>'other'];?>	
                        <?php  echo $form->field($model, 'violation_type')
                           ->dropDownList($options,['prompt'=>Yii::t('frontend', 'Select Violation Type')])->label(false);
                           ?>
                        </span>
                     </div>
                  </div>
               </div>
               <div class="row form-group">
                  <label class="col-sm-3 control-label"><?php echo Yii::t('frontend', 'Username of other person')?></label>
                  <div class="col-sm-9">
                     <?php echo $form->field($model, 'username_other_person', ['inputOptions' => ['class' => 'form-control border-form','value'=> $adUserDetail['username'],'readonly'=> true,'placeholder'=>Yii::t('frontend', 'Username of other person')]])->label(false) ?>
                  </div>
               </div>
               <div class="row form-group">
                  <label class="col-sm-3 control-label"><?php echo Yii::t('frontend', 'URL of voilation')?></label>
                  <div class="col-sm-9">
                     <?php echo $form->field($model, 'url', ['inputOptions' => ['class' => 'form-control border-form','value'=>$url,'readonly'=> true,'placeholder'=>Yii::t('frontend', 'Url of violation')]])->label(false) ?>
                  </div>
               </div>
               <div class="row form-group">
                  <label class="col-sm-3 control-label"><?php echo Yii::t('frontend', 'Voilation Detail')?></label>
                  <div class="col-sm-9">
                     <?= $form->field($model, 'violation_detail',['template' => '{input}{error}','inputOptions' => ['class' => 'form-control']])->textarea(['rows' => '5'])->label(false) ?>
                  </div>
               </div>
               <div class="row form-group">
                  <div class="col-sm-9 offset-sm-3">
                     <?php echo Html::submitButton(Yii::t('frontend', 'Submit'), ['class' => 'btn bt_blue', 'name' => 'contact-button']) ?>
                  </div>
               </div>
               <?php ActiveForm::end(); ?>     
            </div>
         </div>
      </div>
   </div>
</section>
