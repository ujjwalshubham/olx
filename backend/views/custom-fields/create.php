<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\CustomFields */

$this->title = 'Create Custom Fields';
$this->params['breadcrumbs'][] = ['label' => 'Custom Fields', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="custom-fields-create">

    <?php echo $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
