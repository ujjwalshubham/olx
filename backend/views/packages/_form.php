<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Packages */
/* @var $form yii\widgets\ActiveForm */
?>

<header class="slidePanel-header overlay">
    <div class="overlay-panel overlay-background vertical-align">
        <div class="service-heading">
            <h2><?php echo Yii::t('backend', '{type} Package: ', ['type'=>$type]) ?></h2>
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

                <?= $form->field($model, 'ad_limit')->textInput(['type'=>'number']) ?>

                <?= $form->field($model, 'ad_duration')->textInput(['type'=>'number']) ?>

                <?= $form->field($model, 'featured_project_fee')->textInput(['type'=>'number']) ?>

                <?= $form->field($model, 'featured_duration')->textInput(['type'=>'number']) ?>

                <?= $form->field($model, 'urgent_project_fee')->textInput(['type'=>'number']) ?>

                <?= $form->field($model, 'urgent_duration')->textInput(['type'=>'number']) ?>

                <?= $form->field($model, 'highlight_project_fee')->textInput(['type'=>'number']) ?>

                <?= $form->field($model, 'highlight_duration')->textInput(['type'=>'number']) ?>

                <div class="form-group">
                    <label class="col-sm-4 control-label">Removable</label>
                    <div class="col-sm-6">
                        <label class="css-input css-checkbox css-checkbox-primary">
                            <input type="checkbox" name="Packages[group_removable]" value="1"><span></span>
                        </label>
                    </div>
                </div>

                <h3 class="heading">Package Option (Check it if you want to allow)</h3>
                <div class="form-group">
                    <div class="inside" style="padding: 0 20px">
                        <?php
                        if($model->top_search_result == 1){
                            $checked="checked=''";
                            $value=1;
                        } else{
                            $checked="";
                            $value=0;
                        }?>
                        <label class="css-input css-checkbox css-checkbox-primary">
                            <input type="checkbox" name="Packages[top_search_result]"
                                   value="<?php echo $value; ?>" <?php echo $checked; ?>><span></span>
                            Top in search results and category.
                        </label>
                        <br>
                        <?php
                        if($model->show_on_home == 1){
                            $checked="checked=''";
                            $value=1;
                        } else{
                            $checked="";
                            $value=0;
                        }?>
                        <label class="css-input css-checkbox css-checkbox-primary">
                            <input type="checkbox" name="Packages[show_on_home]" value="<?php echo $value; ?>" <?php echo $checked; ?>><span></span>
                            Show ad on home page premium ad section.
                        </label>
                        <br>
                        <?php
                        if($model->show_in_home_search == 1){
                            $checked="checked=''";
                            $value=1;
                        } else{
                            $checked="";
                            $value=0;
                        }?>
                        <label class="css-input css-checkbox css-checkbox-primary">
                            <input type="checkbox" name="Packages[show_in_home_search]" value="<?php echo $value; ?>" <?php echo $checked; ?>><span></span>
                            Show ad on home page search result list.
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