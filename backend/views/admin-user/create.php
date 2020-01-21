<?php
/* @var $this yii\web\View */
/* @var $model backend\models\UserForm */
/* @var $roles yii\rbac\Role[] */

?>
<div class="user-create">
    <?php echo $this->render('_form', [
        'model' => $model,
        'roles' => $roles,
        'type'=>'Add',
        'ajaxurl'=>Yii::$app->urlManager->createAbsoluteUrl(['admin-user/create'])

    ]) ?>
</div>
