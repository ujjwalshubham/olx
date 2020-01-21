<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\PostAd */
/* @var $form yii\bootstrap\ActiveForm */
?>

<div class="post-ad-form">

    <?php $form = ActiveForm::begin(); ?>

    <?php echo $form->errorSummary($model); ?>

    <?php echo $form->field($model, 'user_id')->textInput() ?>

    <?php echo $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

    <?php echo $form->field($model, 'slug')->textInput(['maxlength' => true]) ?>

    <?php echo $form->field($model, 'description')->textarea(['rows' => 6]) ?>

    <?php echo $form->field($model, 'price')->textInput() ?>

    <?php echo $form->field($model, 'negotiate')->dropDownList([ 1 => '1', 0 => '0', ], ['prompt' => '']) ?>

    <?php echo $form->field($model, 'mobile')->textInput(['maxlength' => true]) ?>

    <?php echo $form->field($model, 'mobile_hidden')->dropDownList([ 1 => '1', 0 => '0', ], ['prompt' => '']) ?>

    <?php echo $form->field($model, 'tags')->textarea(['rows' => 6]) ?>

    <?php echo $form->field($model, 'country')->textInput() ?>

    <?php echo $form->field($model, 'state')->textInput() ?>

    <?php echo $form->field($model, 'city')->textInput() ?>

    <?php echo $form->field($model, 'address')->textarea(['rows' => 6]) ?>

    <?php echo $form->field($model, 'termCondition')->dropDownList([ 1 => '1', 0 => '0', ], ['prompt' => '']) ?>

    <?php echo $form->field($model, 'status')->dropDownList([ 'Pending' => 'Pending', 'Inactive' => 'Inactive', 'Active' => 'Active', 'Hidden' => 'Hidden', 'Expire' => 'Expire', ], ['prompt' => '']) ?>

    <?php echo $form->field($model, 'latitude')->textInput(['maxlength' => true]) ?>

    <?php echo $form->field($model, 'longitude')->textInput(['maxlength' => true]) ?>

    <?php echo $form->field($model, 'created_at')->textInput() ?>

    <?php echo $form->field($model, 'updated_at')->textInput() ?>

    <?php echo $form->field($model, 'created_by')->textInput() ?>

    <?php echo $form->field($model, 'updated_by')->textInput() ?>

    <div class="form-group">
        <?php echo Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
