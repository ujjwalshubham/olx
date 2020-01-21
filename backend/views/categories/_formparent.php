<?php

use common\models\User;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use common\models\Categories;
/* @var $this yii\web\View */
/* @var $model backend\models\UserForm */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $roles yii\rbac\Role[] */
/* @var $permissions yii\rbac\Permission[] */
?>
<header class="slidePanel-header overlay">
    <div class="overlay-panel overlay-background vertical-align">
        <div class="service-heading">
            <h2><?php echo Yii::t('backend', '{type} Category: ', ['type'=>$type]) ?></h2>
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
            <?php $form = ActiveForm::begin(['id'=>'sidePanel_form','action'=>'javascript:void(0)',
                'options'=>['data-ajax-action'=>$ajaxurl]]) ?>
            <?php echo $form->field($model, 'title')->textInput(['maxlength' => true]) ?>
            <?php echo $form->field($model, 'description')->textarea(['rows' => 6]) ?>
            <?php echo $form->field($model, 'status')->dropDownList(Categories::statuses()) ?>

            <div class="col-sm-12">
            <?php echo  $form->field($model, 'image')->fileInput([
                'options' => ['accept' => 'image/*'],
                'maxFileSize' => 5000000, // 5 MiB

            ]);   ?>
                <?php
                $imageCat=\common\components\AppHelper::getCategoryImage($model->id);
                if(!empty($imageCat)){  ?>
                    <div class="edit-image" style="margin-left: 200px;margin-top: -70px;">
                        <img  src="<?php echo $imageCat; ?>" width="75" height="75"/>
                    </div>
                <?php } ?>
            </div>

            <?php
            $model->parent_id=$parent;
            echo $form->field($model, 'parent_id')->hiddenInput()->label(false); ?>
            <div class="form-group" style="display:none">
                <?php echo Html::submitButton(Yii::t('backend', 'Save'), ['class' => 'btn btn-primary submit_form_btn', 'name' => 'signup-button']) ?>
            </div>
            <?php ActiveForm::end() ?>
        </div>
    </div>
</div>