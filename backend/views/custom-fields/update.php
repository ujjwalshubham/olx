<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\CustomFields */

$this->title = 'Update Custom Fields: ' . ' ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Custom Fields', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="custom-fields-update">

    <?php echo $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
