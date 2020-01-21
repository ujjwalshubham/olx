<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\Categories */

$this->title = 'Create Categories';
$this->params['breadcrumbs'][] = ['label' => 'Categories', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="categories-create">
    <?php echo $this->render('_formparent', [
        'model' => $model,
        'parent'=>$parent,
        'ajaxurl'=>Yii::$app->urlManager->createAbsoluteUrl(['categories/create']),
        'type'=>'Add',
    ]) ?>
</div>
