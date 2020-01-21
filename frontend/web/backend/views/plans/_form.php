<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Plans */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="plans-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'alias')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'package_id')->textInput() ?>

    <?= $form->field($model, 'plan_term')->dropDownList([ 'DAILY' => 'DAILY', 'WEEKLY' => 'WEEKLY', 'MONTHLY' => 'MONTHLY', 'YEARLY' => 'YEARLY', ], ['prompt' => '']) ?>

    <?= $form->field($model, 'pay_mode')->dropDownList([ 'one_time' => 'One time', 'recurring' => 'Recurring', ], ['prompt' => '']) ?>

    <?= $form->field($model, 'amount')->textInput() ?>

    <?= $form->field($model, 'image')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'recommended')->textInput() ?>

    <?= $form->field($model, 'active')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
