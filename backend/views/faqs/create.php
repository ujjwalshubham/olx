<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\Faqs */

$this->title = 'Create Faqs';
$this->params['breadcrumbs'][] = ['label' => 'Faqs', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="faqs-create">

    <?php echo $this->render('_form', [
        'model' => $model,
        'type'=>'Add',
        'ajaxurl'=>Yii::$app->urlManager->createAbsoluteUrl(['faqs/create'])
    ]) ?>

</div>
