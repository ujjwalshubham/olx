<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Plans */

$this->title = 'Create Plan';
$this->params['breadcrumbs'][] = ['label' => 'Plans', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="plans-create">
    <?php echo $this->render('_form', [
        'model' => $model,
        'type'=>'Add',
        'ajaxurl'=>Yii::$app->urlManager->createAbsoluteUrl(['plans/create'])
    ]) ?>

</div>
