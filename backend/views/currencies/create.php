<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\Currencies */

$this->title = 'Create Currencies';
$this->params['breadcrumbs'][] = ['label' => 'Currencies', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="currencies-create">

    <?php echo $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
