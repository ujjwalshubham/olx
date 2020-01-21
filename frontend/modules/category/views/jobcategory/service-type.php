<?php

use frontend\modules\gloomme\jobcategory\models\Jobcategory;
use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\\models\search\ArticleCategorySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('backend', 'Service Type');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="article-category-index">
    <p>
        <?php
        echo Html::a(Yii::t('backend', 'Add Service Type', [
                    'modelClass' => 'Service Type',
                ]), ['add-service-type'], ['class' => 'btn btn-success'])
        ?>
    </p>
    <?php
    echo GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'attribute' => 'parentcatid',
                'value' => function ($model) {
                    $getParentCatName = Jobcategory::getParentTitle($model->parentcatid);
                    return $getParentCatName;
                },
                'filter' => \yii\helpers\ArrayHelper::map(Jobcategory::find()->where(['parentid' => 0])->all(), 'id', 'title')
            ],
            [
                'attribute' => 'subcatid',
                'value' => function ($model) {
                    $getChildCatName = Jobcategory::getParentTitle($model->subcatid);
                    return $getChildCatName;
                },
                'filter' => \yii\helpers\ArrayHelper::map(Jobcategory::find()->where(['!=', 'parentid', '0'])->all(), 'id', 'title')
            ],
            [
                'attribute' => 'servicename'
            ],
            [
                'label' => 'Add Option',
                'format' => 'html',
                'value' => function ($model) {
                    return '<a href="' . \yii\helpers\Url::to('@backendUrl') . '/category/jobcategory/service-option/?serviceid=' . $model->id . '" class="btn btn-success">Add Option</a>';
                },
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{update}{delete}',
                //'contentOptions' => ['style' => 'width: 100'],
                'buttons' => [
                    'update' => function($url, $model) {
                        return Html::a('<span class="glyphicon glyphicon-pencil"></span>', $url, [
                                    'title' => Yii::t('app', 'Update'), 'class' => 'actionurl btn btn-success',]);
                    },
                    'delete' => function($url, $model) {
                        return Html::a('<span class="glyphicon glyphicon-trash "></span>', $url, [
                                    'title' => Yii::t('app', 'Delete'), 'class' => 'actionurl btn btn-danger',]);
                    },
                ],
                'urlCreator' => function ($action, $model, $key, $index) {
                    if ($action === 'delete') {
                        $url = \yii\helpers\Url::to('@backendUrl') . '/category/jobcategory/delete-service-type?id=' . $model->id;
                        return $url;
                    }
                    if ($action === 'update') {
                        $url = \yii\helpers\Url::to('@backendUrl') . '/category/jobcategory/update-service-type?id=' . $model->id;
                        return $url;
                    }
                }
            ],
        ],
    ]);
    ?>

</div>