<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model frontend\modules\brsoftech\category\models\Category */

$this->title = Yii::t('backend','Update Service Type');

?>
<div class="category-update">
    <?php echo $this->render('add-service-type', ['model' => $model, 'parentCategory' => $parentCategory]) ?>
</div>
