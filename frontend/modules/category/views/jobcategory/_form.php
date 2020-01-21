<?php

use trntv\filekit\widget\Upload;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use kartik\file\FileInput;

/* @var $this yii\web\View */
/* @var $model common\models\Article */
/* @var $categories common\models\ArticleCategory[] */
/* @var $form yii\bootstrap\ActiveForm */
?>

<div class="article-form">
    <p><strong><?php echo Yii::t('backend', 'Note: When you want to create a Main Category don\'t select Parent category. And if you want create Sub Category then you select Parent Category.') ?></strong></p>
    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>
    <?php echo $form->field($model, 'parentid')->dropDownList(\yii\helpers\ArrayHelper::map($categories, 'id', 'title'), ['prompt' => Yii::t('backend', 'Select Category')]) ?>
    <?php echo $form->field($model, 'title')->textInput(['maxlength' => true]) ?>
    <?php echo $form->field($model, 'shortdecription')->textInput(['maxlength' => true]) ?>
    <?php
    if ($model['id'] > 0) {
        echo $form->field($model, 'image')->widget(FileInput::classname(), [
            'options' => [
                'multiple' => false,
                'class' => 'form-control'
            ],
            'pluginOptions' => [
                'initialPreview' => [
                    $model['base_url'] . '/' . $model['fullimage']
                ],
                'initialPreviewAsData' => true,
                'overwriteInitial' => true,
                'showUpload' => false,
                'showRemove' => true,
                'showCancel' => false,
                'initialPreviewShowDelete' => false,
                'dropZoneEnabled' => false,
                'browseIcon' => '<i class="glyphicon glyphicon-camera"></i> ',
                'browseLabel' => '&nbsp;&nbsp;Attach File ',
                'browseClass' => 'btn btn-primary',
            ]
        ]);
    } else {
        echo $form->field($model, 'image')->widget(FileInput::classname(), [
            'options' => [
                'multiple' => false,
                'class' => 'form-control'
            ],
            'pluginOptions' => [
                'overwriteInitial' => true,
                'showUpload' => false,
                'showRemove' => true,
                'showCancel' => false,
                'dropZoneEnabled' => false,
                'browseIcon' => '<i class="glyphicon glyphicon-camera"></i> ',
                'browseLabel' => '&nbsp;&nbsp;Attach File ',
                'browseClass' => 'btn btn-primary',
            ]
        ]);
    }
    ?>
    <?php echo $form->field($model, 'metatitle')->textInput() ?>
    <?php echo $form->field($model, 'metadescription')->textarea() ?>
    <div class="form-group">
        <?php echo Html::submitButton(Yii::t('backend', 'Save'), ['class' => 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
