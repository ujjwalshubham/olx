<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\search\PackagesSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="packages-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'name') ?>

    <?= $form->field($model, 'ad_limit') ?>

    <?= $form->field($model, 'ad_duration') ?>

    <?= $form->field($model, 'featured_project_fee') ?>

    <?php // echo $form->field($model, 'featured_duration') ?>

    <?php // echo $form->field($model, 'urgent_project_fee') ?>

    <?php // echo $form->field($model, 'urgent_duration') ?>

    <?php // echo $form->field($model, 'highlight_project_fee') ?>

    <?php // echo $form->field($model, 'highlight_duration') ?>

    <?php // echo $form->field($model, 'group_removable') ?>

    <?php // echo $form->field($model, 'top_search_result') ?>

    <?php // echo $form->field($model, 'show_on_home') ?>

    <?php // echo $form->field($model, 'show_in_home_search') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <?php // echo $form->field($model, 'updated_at') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
