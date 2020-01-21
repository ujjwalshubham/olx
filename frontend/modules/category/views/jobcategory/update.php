<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model frontend\modules\brsoftech\category\models\Category */

$this->title = Yii::t('backend','Update category');

?>
<div class="category-update">
    <?php echo $this->render('_form', ['model' => $model, 'categories' => $categories]) ?>
</div>
