<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Advertising */

$this->title = 'Update Advertising: ' . ' ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Advertisings', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="advertising-update">

    <?php echo $this->render('_form', [
        'model' => $model,
        'ajaxurl'=>Yii::$app->urlManager->createAbsoluteUrl(['advertising/update','id'=>$model->id])
    ]) ?>

</div>
