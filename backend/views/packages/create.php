<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Packages */

$this->title = 'Create Package';
$this->params['breadcrumbs'][] = ['label' => 'Packages', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="packages-create">
    <?php echo $this->render('_form', [
        'model' => $model,
        'type'=>'Add',
        'ajaxurl'=>Yii::$app->urlManager->createAbsoluteUrl(['packages/create'])
    ]) ?>
</div>
