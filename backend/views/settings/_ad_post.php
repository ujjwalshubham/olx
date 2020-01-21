<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use backend\models\AdPostSetting;
use yii\web\JsExpression;
use kartik\select2\Select2;
use common\models\Categories;

?>
<?php $form = ActiveForm::begin(['id' => 'postsetting','action'=>['settings/ad-post']]); ?>

<?php echo $form->errorSummary($model); ?>

<?php echo $form->field($model, 'auto_approve')->dropDownList(AdPostSetting::enabledisable()) ?>

<?php echo $form->field($model, 'premium_listing_option')->dropDownList(AdPostSetting::enabledisable()) ?>

<?php echo $form->field($model, 'max_image_upload')->textInput(['maxlength' => true]) ?>

<?php echo $form->field($model, 'watermark')->dropDownList(AdPostSetting::enabledisable()) ?>

<?php echo $form->field($model, 'description_editor')->dropDownList(AdPostSetting::enabledisable()) ?>

<?php echo $form->field($model, 'address_field')->dropDownList(AdPostSetting::enabledisable()) ?>

<?php echo $form->field($model, 'tags_field')->dropDownList(AdPostSetting::enabledisable()) ?>

<div class="row">
    <div class="form-group col-sm-12" id="price-field-value">
        <?php
        $categories=array();
        $lists = Categories::find()->where(['parent_id'=>0,'status'=>Categories::STATUS_ACTIVE])->all();
        foreach($lists as $list){
            $categories[$list->id]=$list->title;
        }
        echo  $form->field($model, 'price_field')->widget(Select2::classname(),
            [
                'data' => $categories,
                'size' => Select2::SMALL,
                'options' => ['placeholder' => 'Select Categories ...', 'multiple' => true,'class'=>'estate_list'],
                'pluginOptions' => [
                    'tags' => false,
                    'allowClear' => true,
                    'multiple' => true,
                ],
            ])->label('Price Field');
        ?>
    </div>
</div>

<?php echo $form->field($model, 'terms_condition_link')->textInput(['maxlength' => true]) ?>

<?php echo $form->field($model, 'privacy_page_link')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?php echo Html::submitButton('Save', ['class' => 'btn btn-primary']) ?>
    </div>

<?php ActiveForm::end(); ?>