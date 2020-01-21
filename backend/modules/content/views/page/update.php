<?php

/**
 * @var $this  yii\web\View
 * @var $model common\models\Page
 */

$this->title = Yii::t('backend', 'Edit {modelClass}: ', [
        'modelClass' => 'Page',
    ]) . ' ' . $model->title;

$this->params['breadcrumbs'][] = ['label' => Yii::t('backend', 'Pages'), 'url' => ['index']];
$this->params['breadcrumbs'][] = Yii::t('backend', 'Edit');

?>

<?php echo $this->render('_form', [
    'model' => $model,
    'type'=>'Edit',
    'ajaxurl'=>Yii::$app->urlManager->createAbsoluteUrl(['content/page/update','id'=>$pageid])
]) ?>
