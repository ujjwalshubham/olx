<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use backend\models\GeneralSetting;
use common\components\AppHelper;
?>
<?php $form = ActiveForm::begin(['id' => 'generalsetting','action'=>['settings/index']]); ?>

<?php echo $form->errorSummary($model); ?>

<?php echo $form->field($model, 'site_url')->textInput() ?>

<?php echo $form->field($model, 'site_title')->textInput(['maxlength' => true]) ?>

<?php echo $form->field($model, 'google_api_key')->textInput(['maxlength' => true]) ?>

<?php echo $form->field($model, 'featured_ad_fee')->textInput(['maxlength' => true]) ?>

<?php echo $form->field($model, 'urgent_ad_fee')->textInput(['maxlength' => true]) ?>

<?php echo $form->field($model, 'hightlight_ad_fee')->textInput(['maxlength' => true]) ?>

<?php echo $form->field($model, 'delete_ad_after_expire')->dropDownList(GeneralSetting::deleteadafterexpire()) ?>

<?php echo $form->field($model, 'user_language_selection')->dropDownList(GeneralSetting::userlanguageselection()) ?>

<?php echo $form->field($model, 'user_theme_selection')->dropDownList(GeneralSetting::userthemeselection()) ?>

<?php echo $form->field($model, 'theme_color_switcher')->dropDownList(GeneralSetting::themecolorswitcher()) ?>

<?php
$packages=AppHelper::getAllPackagesList();
echo $form->field($model, 'default_package')->dropDownList($packages) ?>

<?php echo $form->field($model, 'page_size')->textInput(['maxlength' => true]) ?>

<?php echo $form->field($model, 's3_bucket')->dropDownList(GeneralSetting::enabledisable()) ?>

<?php echo $form->field($model, 's3_bucket_url')->textInput(['maxlength' => true]) ?>

<?php echo $form->field($model, 'image_url_localpath')->textInput(['maxlength' => true]) ?>



<div class="form-group">
    <?php echo Html::submitButton('Save', ['class' => 'btn btn-primary']) ?>
</div>

<?php ActiveForm::end(); ?>