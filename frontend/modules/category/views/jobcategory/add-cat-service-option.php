<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use common\models\ServiceType;

/* @var $this yii\web\View */
/* @var $model backend\models\UserForm */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $roles yii\rbac\Role[] */
/* @var $permissions yii\rbac\Permission[] */
$this->title = 'Category Services';
?>
<div class="user-form">
    <?php $form = ActiveForm::begin() ?>
    <?php echo $form->field($model, 'parentcatname')->textInput(['value' => $parentcatname['title'], 'readonly' => true])->label('Category'); ?>
    <?php echo $form->field($model, 'childcatname')->textInput(['value' => $childcatname['title'], 'readonly' => true])->label('Sub Category'); ?>
    <?php echo $form->field($model, 'parentcatid')->textInput(['value' => $parentcatname['id'], 'type' => 'hidden'])->label(false); ?>
    <?php echo $form->field($model, 'subcatid')->textInput(['value' => $childcatname['id'], 'type' => 'hidden'])->label(false); ?>
    <?php echo $form->field($model, 'service_type_id')->textInput(['value' => 0, 'type' => 'hidden'])->label(false); ?>
    <?php echo $form->field($model, 'option_type')->dropDownList(ServiceType::typeList()) ?>
    <?php echo $form->field($model, 'option_name') ?>
    <?php echo $form->field($model, 'additionalDay')->label('Additional Days(e.g. 10)') ?>
    <div class="optionvalue" style="display: none">
        <?php echo $form->field($model, 'option_value') ?>
    </div>
    <div class="form-group">
        <?php echo Html::submitButton(Yii::t('backend', 'Save'), ['class' => 'btn btn-primary', 'name' => 'signup-button']) ?>
    </div>
    <?php ActiveForm::end() ?>
</div>