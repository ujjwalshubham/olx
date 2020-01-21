<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Languages */

$this->title = 'Update Languages: ' . ' ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Languages', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="languages-update">

    <?php echo $this->render('_form', [
        'model' => $model,
        'type'=>'Edit',
        'ajaxurl'=>Yii::$app->urlManager->createAbsoluteUrl(['languages/update','id'=>$id])
    ]) ?>

</div>
