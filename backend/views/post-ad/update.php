<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\PostAd */

$this->title = 'Update Post Ad: ' . ' ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Post Ads', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="post-ad-update">

    <?php echo $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
