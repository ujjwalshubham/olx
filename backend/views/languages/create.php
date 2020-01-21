<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\Languages */

$this->title = 'Create Languages';
$this->params['breadcrumbs'][] = ['label' => 'Languages', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="languages-create">

    <?php echo $this->render('_form', [
        'model' => $model,
        'type'=>'Add',
        'ajaxurl'=>Yii::$app->urlManager->createAbsoluteUrl(['languages/create'])
    ]) ?>

</div>
