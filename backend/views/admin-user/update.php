<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\User */
/* @var $roles yii\rbac\Role[] */

?>
<div class="user-update">
    <?php echo $this->render('_form', [
        'model' => $model,
        'roles' => $roles,
        'type'=>'Edit',
        'ajaxurl'=>Yii::$app->urlManager->createAbsoluteUrl(['admin-user/update','id'=>$userid])
    ]) ?>

</div>
