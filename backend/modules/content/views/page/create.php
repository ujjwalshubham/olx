<?php

/**
 * @var $this  yii\web\View
 * @var $model common\models\Page
 */

$this->title = Yii::t('backend', 'Add {modelClass}: ', [
        'modelClass' => 'Page',
    ]) . ' ' . $model->title;

$this->params['breadcrumbs'][] = ['label' => Yii::t('backend', 'Pages'), 'url' => ['index']];
$this->params['breadcrumbs'][] = Yii::t('backend', 'Add');

?>

<?php echo $this->render('_form', [
    'model' => $model,
    'type'=>'Add',
    'ajaxurl'=>Yii::$app->urlManager->createAbsoluteUrl(['content/page/create'])
]) ?>