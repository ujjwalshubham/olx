<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Faqs */

$this->title = 'Update Faqs: ' . ' ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Faqs', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="faqs-update">

    <?php echo $this->render('_form', [
        'model' => $model,
        'type'=>'Edit',
        'ajaxurl'=>Yii::$app->urlManager->createAbsoluteUrl(['faqs/update','id'=>$id])
    ]) ?>

</div>
