<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\components\AppHelper;

/* @var $this yii\web\View */
/* @var $model common\models\Plans */
/* @var $form yii\widgets\ActiveForm */
?>

<header class="slidePanel-header overlay">
    <div class="overlay-panel overlay-background vertical-align">
        <div class="service-heading">
            <h2><?php echo Yii::t('backend', '{type} Plan: ', ['type'=>$type]) ?></h2>
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
        <div class="user-form">
            <?php $form = ActiveForm::begin(['id'=>'sidePanel_form',
                'action'=>'javascript:void(0)',
                'options'=>['data-ajax-action'=>$ajaxurl,'class'=>'form-horizontal'],
                'fieldConfig' =>
                    [
                        'template' => "{label}\n<div class=\"col-sm-6\">{input}</div>",
                        'labelOptions' => ['class' => 'col-sm-4 control-label'],
                    ]
            ]) ?>
            <div class="form-body">

            <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

                <?php $packages=AppHelper::getAllPackagesList();?>
            <?= $form->field($model, 'package_id')->dropDownList($packages) ?>
            <?= $form->field($model, 'plan_term')->dropDownList([ 'DAILY' => 'DAILY', 'WEEKLY' => 'WEEKLY', 'MONTHLY' => 'MONTHLY', 'YEARLY' => 'YEARLY', ]) ?>

            <?= $form->field($model, 'pay_mode')->dropDownList([ 'one_time' => 'One time', 'recurring' => 'Recurring', ]) ?>

            <?= $form->field($model, 'amount')->textInput() ?>

            <?= $form->field($model, 'image')->textInput(['maxlength' => true]) ?>


            <div class="form-group">
                <label class="col-sm-4 control-label">Recommended</label>
                <div class="col-sm-6">
                    <label class="css-input css-checkbox css-checkbox-primary">
                        <input type="checkbox" name="Plans[recommended]" value="1" checked=""><span></span>
                    </label>
                </div>
            </div>

                <div class="form-group">
                    <label class="col-sm-4 control-label">Activate</label>
                    <div class="col-sm-6">
                        <label class="css-input switch switch-sm switch-success">
                            <input name="Plans[active]" type="checkbox" value="1" checked=""><span></span>
                        </label>
                    </div>
                </div>



            <div class="form-group" style="display:none">
                <?php echo Html::submitButton(Yii::t('backend', 'Save'), ['class' => 'btn btn-primary submit_form_btn', 'name' => 'signup-button']) ?>
            </div>
            </div>
            <?php ActiveForm::end() ?>
        </div>
    </div>
</div>