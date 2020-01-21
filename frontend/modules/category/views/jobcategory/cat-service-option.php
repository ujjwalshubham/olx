<?php

use yii\grid\GridView;
use yii\helpers\Html;
use frontend\modules\gloomme\jobcategory\models\Jobcategory;

/**
 * @var $this         yii\web\View
 * @var $searchModel  \backend\models\search\PageSearch
 * @var $dataProvider yii\data\ActiveDataProvider
 * @var $model        common\models\Page
 */
$this->title = Yii::t('backend', 'Service Option');

$this->params['breadcrumbs'][] = $this->title;
?>

<div class="box box-success collapsed-box">
    <div class="box-header with-border">
        <h3 class="box-title"><?php echo Yii::t('backend', 'Create {modelClass}', ['modelClass' => 'Service Option']) ?></h3>
        <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i></button>
        </div>
    </div>
    <div class="box-body">
        <?php
        echo $this->render('add-cat-service-option', [
            'model' => $model,
            'parentcatname' => $parentcatname,
            'childcatname' => $childcatname,
        ])
        ?>
    </div>
</div>

<?php
echo GridView::widget([
    'dataProvider' => $dataProvider,
    'filterModel' => $searchModel,
    'options' => [
        'class' => 'grid-view table-responsive',
    ],
    'columns' => [
        ['class' => 'yii\grid\SerialColumn'],
        [
            'attribute' => 'parentcatid',
            'label' => 'Category',
            'value' => function ($model) {
                $serviceName = Jobcategory::findOne($model->parentcatid);
                return $serviceName['title'];
            },
        ],
        [
            'attribute' => 'subcatid',
            'label' => 'Sub Category',
            'value' => function ($model) {
                $serviceName = Jobcategory::findOne($model->subcatid);
                return $serviceName['title'];
            },
        ],
        [
            'attribute' => 'option_type',
        ],
        [
            'attribute' => 'option_name',
        ],
        [
            'attribute' => 'option_value',
        ],
        [
            'attribute' => 'additionalDay',
        ],
        [
            'class' => 'yii\grid\ActionColumn',
            'template' => '{update}{delete}',
            'contentOptions' => ['style' => 'width: 150px'],
            'buttons' => [
                'update' => function($url, $model) {
                    return Html::a('<span class="glyphicon glyphicon-pencil"></span>', $url, [
                                'title' => Yii::t('app', 'Update'), 'class' => 'actionurl btn btn-success',]);
                },
                'delete' => function($url, $model) {
                    return Html::a('<span class="glyphicon glyphicon-trash"></span>', $url, [
                                'title' => Yii::t('app', 'Delete'), 'class' => 'actionurl btn btn-danger',]);
                }
            ],
            'urlCreator' => function ($action, $model, $key, $index) {
                if ($action === 'delete') {
                    $url = \yii\helpers\Url::to('@backendUrl') . '/category/jobcategory/delete-cat-option?id=' . $model->id.'&catid='.Yii::$app->request->get('catid').'&subcatid='.Yii::$app->request->get('subcatid');
                    return $url;
                }
                if ($action === 'update') {
                    $url = \yii\helpers\Url::to('@backendUrl') . '/category/jobcategory/update-cat-service-option?id=' . $model->id.'&catid='.Yii::$app->request->get('catid').'&subcatid='.Yii::$app->request->get('subcatid');
                    return $url;
                }
            }
        ],
    ],
]);
?>

