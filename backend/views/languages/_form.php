<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Languages */
/* @var $form yii\bootstrap\ActiveForm */
?>

<header class="slidePanel-header overlay">
    <div class="overlay-panel overlay-background vertical-align">
        <div class="service-heading">
            <h2><?php echo Yii::t('backend', '{type} Language: ', ['type'=>$type]) ?></h2>
        </div>
        <div class="slidePanel-actions">
            <div class="btn-group-flat">
                <button type="button" class="submit_form_tick btn btn-floating btn-warning btn-sm waves-effect waves-float waves-light margin-right-10" id="post_sidePanel_data">
                    <i class="icon ion-android-done" aria-hidden="true"></i></button>
                <button type="button" class="btn btn-pure btn-inverse slidePanel-close icon ion-android-close font-size-20" aria-hidden="true"></button>
            </div>
        </div>
    </div>
</header>
<div class="slidePanel-inner">
    <div class="panel-body">
        <div class="languages-form">

            <?php $form = ActiveForm::begin(['id'=>'sidePanel_form','action'=>'javascript:void(0)',
                'options'=>['data-ajax-action'=>$ajaxurl]]) ?>

                <?php echo $form->errorSummary($model); ?>

                <?php echo $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

                <?php echo $form->field($model, 'code')->textInput(['maxlength' => true]) ?>

                <?php echo $form->field($model, 'direction')->dropDownList([ 'ltr' => 'Ltr', 'rtl' => 'Rtl', ], ['prompt' => '']) ?>

                <?php echo $form->field($model, 'status')->textInput() ?>

                <div class="form-group" style="display: none;">
                    <?php echo Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
                </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
