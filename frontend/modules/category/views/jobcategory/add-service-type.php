<?php

use common\models\User;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use common\models\ServiceType;
use frontend\modules\gloomme\jobcategory\models\Jobcategory;

/* @var $this yii\web\View */
/* @var $model backend\models\UserForm */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $roles yii\rbac\Role[] */
/* @var $permissions yii\rbac\Permission[] */
$this->title = Yii::t('backend', 'Add {modelClass}', [
            'modelClass' => 'Service Type',
        ]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend', 'Service Type'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-form">
    <?php $form = ActiveForm::begin() ?>
    <?php echo $form->field($model, 'parentcatid')->dropDownList(\yii\helpers\ArrayHelper::map($parentCategory, 'id', 'title'), ['prompt' => Yii::t('backend', 'Select Category')]) ?>
    <?php
    if ($model->id > 0) {
        $subCatModel = Jobcategory::getSubCat($model->parentcatid);
        echo $form->field($model, 'subcatid')->dropDownList(\yii\helpers\ArrayHelper::map($subCatModel, 'id', 'title'), ['prompt' => 'Select Sub Category']);
    } else {
        echo $form->field($model, 'subcatid')->dropDownList(\yii\helpers\ArrayHelper::map(['empty' => 'Empty string'], 'id', 'title'), ['prompt' => 'Select Sub Category']);
    }
    ?>
    <?php echo $form->field($model, 'servicename') ?>
    <div class="form-group">
        <?php echo Html::submitButton(Yii::t('backend', 'Save'), ['class' => 'btn btn-primary', 'name' => 'signup-button']) ?>
    </div>
    <?php ActiveForm::end() ?>
</div>
