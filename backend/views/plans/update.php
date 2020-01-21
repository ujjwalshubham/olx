<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Plans */

$this->title = 'Update Plan: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Plans', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="plans-update">

    <?php echo $this->render('_form', [
        'model' => $model,
        'type'=>'Edit',
        'ajaxurl'=>Yii::$app->urlManager->createAbsoluteUrl(['plans/update','id'=>$model->id])
    ]) ?>

</div>
