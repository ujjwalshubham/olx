<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model frontend\modules\brsoftech\category\models\Category */

$this->title = Yii::t('backend','Update Service Option');

?>
<div class="category-update">
    <?php echo $this->render('add-cat-service-option', ['model' => $model, 'parentcatname' => $parentcatname, 'childcatname' => $childcatname]) ?>
</div>
