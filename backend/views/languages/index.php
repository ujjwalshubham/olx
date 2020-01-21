<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\search\LanguagesSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Languages';
$this->params['breadcrumbs'][] = $this->title;
?>
<!-- Partial Table -->
<div class="card">
    <div class="card-header">
        <h4>Languages</h4>
        <div class="pull-right">
            <?php echo Html::a(Yii::t('backend', 'Add {modelClass}', [
                'modelClass' => 'Language',
            ]), 'javascript:void(0)', ['class' => 'btn btn-success','data-toggle'=>'slidePanel',
                'data-url'=>Yii::$app->urlManager->createAbsoluteUrl(['languages/create'])]); ?>
        </div>
    </div>
    <div class="card-block">
        <?php Pjax::begin([ 'id' => 'pjax-grid', 'enablePushState'=>false, ]); ?>
            <?php echo GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'options' => [
                    'class' => 'grid-view table-responsive td_border_zero'
                ],
                'layout'=>"{items}\n<div class='pagination_section'>{summary}{pager}</div>",
                'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],
                    'title',
                    'code',
                    'direction',
                    /*[
                        'class' => 'yii\grid\DataColumn', // can be omitted, as it is the default
                        'label' => 'Status',
                        'value' => function ($data) {
                           return ($data->status?"<strong style='color: green'>Active</strong>":"Deactive");
                        }
                    ],*/
                    [
                        'attribute'=>'active',
                        'header'=>'Status',
                        'filter' => ['1'=>'Active', '0'=>'Deactive'],
                        'format'=>'raw',    
                        'value' => function($model, $key, $index)
                        {   
                            if($model->status)
                            {
                                return '<button class="btn btn-success">Active</button>';
                            }
                            else
                            {   
                                return '<button class="btn btn-danger">Deactive</button>';
                            }
                        },
                    ],
                    [
                        'class' => 'yii\grid\ActionColumn',
                        'template' => '{update} {delete}',
                        'buttons' => [
                            'update' => function ($url) {
                                return Html::a(
                                    '<span class="glyphicon glyphicon-pencil"></span>',
                                    'javascript:void(0)',
                                    [
                                        'data-toggle'=>"slidePanel",
                                        'data-url'=>$url,
                                        'title' => Yii::t('backend', 'Update')
                                    ]
                                );
                            },
                        ],
                        'visibleButtons' => [
                            'login' => Yii::$app->user->can('administrator')
                        ]

                    ],
                ],
            ]); ?>
        <?php Pjax::end(); ?>
    </div>
</div>
