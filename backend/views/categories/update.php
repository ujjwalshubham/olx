<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Categories */

$this->title = 'Update Categories: ' . ' ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Categories', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="categories-update">
    <?php echo $this->render('_formparent', [
        'model' => $model,
        'parent'=>$parent,
        'ajaxurl'=>Yii::$app->urlManager->createAbsoluteUrl(['categories/update','id'=>$model->id]),
        'type'=>'Edit',
    ]) ?>

</div>
