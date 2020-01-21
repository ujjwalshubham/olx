<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Packages */

$this->title = 'Update Package: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Packages', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="packages-update">

    <?php echo $this->render('_form', [
        'model' => $model,
        'type'=>'Edit',
        'ajaxurl'=>Yii::$app->urlManager->createAbsoluteUrl(['packages/update','id'=>$model->id])
    ]) ?>

</div>
