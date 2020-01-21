<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Packages */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="packages-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'ad_limit')->textInput() ?>

    <?= $form->field($model, 'ad_duration')->textInput() ?>

    <?= $form->field($model, 'featured_project_fee')->textInput() ?>

    <?= $form->field($model, 'featured_duration')->textInput() ?>

    <?= $form->field($model, 'urgent_project_fee')->textInput() ?>

    <?= $form->field($model, 'urgent_duration')->textInput() ?>

    <?= $form->field($model, 'highlight_project_fee')->textInput() ?>

    <?= $form->field($model, 'highlight_duration')->textInput() ?>

    <?= $form->field($model, 'group_removable')->textInput() ?>

    <?= $form->field($model, 'top_search_result')->textInput() ?>

    <?= $form->field($model, 'show_on_home')->textInput() ?>

    <?= $form->field($model, 'show_in_home_search')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
