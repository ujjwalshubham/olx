<?php

use frontend\modules\gloomme\jobcategory\models\Jobcategory;
use common\grid\EnumColumn;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\web\View;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel backend\\models\search\ArticleCategorySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('backend', 'Manage Category');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="article-category-index">
    <p>
        <?php
        echo Html::a(Yii::t('backend', 'Create Category', [
                    'modelClass' => 'Category',
                ]), ['create'], ['class' => 'btn btn-success'])
        ?>
    </p>
    <?php
    echo GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'title',
            [
                'attribute' => 'parentid',
                'value' => function ($model) {
                    return $model->getParentTitle($model->parentid) != '' ? $model->getParentTitle($model->parentid) : 'Parent category';
                },
                'filter' => \yii\helpers\ArrayHelper::map(Jobcategory::find()->where(['parentid' => 0])->all(), 'id', 'title')
            ],
            [
                'label' => 'Add Service',
                'format' => 'html',
                'value' => function ($model) {
                    if ($model->parentid != 0) {
                        return '<a href="' . \yii\helpers\Url::to('@backendUrl') . '/category/jobcategory/cat-service-option/?catid=' . $model->parentid . '&subcatid=' . $model->id . '" class="btn btn-success">Add Service</a>';
                    }else{
                        return '---';
                    }
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
                                    'title' => Yii::t('app', 'Delete'), 'class' => 'actionurl btn btn-danger', 'data' => ['confirm' => 'Are you sure you want to delete this category?'], 'data-method' => 'post']);
                    },
                ],
                'urlCreator' => function ($action, $model, $key, $index) {
                    if ($action === 'delete') {
                        $url = \yii\helpers\Url::to('@backendUrl') . '/category/jobcategory/delete?id=' . $model->id;
                        return $url;
                    }
                    if ($action === 'update') {
                        $url = \yii\helpers\Url::to('@backendUrl') . '/category/jobcategory/update?id=' . $model->id;
                        return $url;
                    }
                }
            ],
        ],
    ]);
    ?>

</div>