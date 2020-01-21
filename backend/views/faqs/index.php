<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\search\FaqsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Faqs';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="card">
    <div class="card-header">
        <h4>FAQ Entries</h4>
        <div class="pull-right">
            <?php echo Html::a(Yii::t('backend', 'Add {modelClass}', [
                'modelClass' => 'Entry',
            ]), 'javascript:void(0)', ['class' => 'btn btn-success','data-toggle'=>'slidePanel',
                'data-url'=>Yii::$app->urlManager->createAbsoluteUrl(['faqs/create'])]); ?>
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
                'title:ntext',
                [
                    'attribute' => 'description',
                    'format' => 'raw',
                    'label'=>'Content',
                    'value' => function($data) {
                        return $data->description;
                    },
                    'enableSorting' => false
                ],
                [
                    'attribute'=>'status',
                    'header'=>'Status',
                    'filter' => ['1'=>'Active', '0'=>'InActive'],
                    'format'=>'raw',
                    'value' => function($model, $key, $index)
                    {
                        if($model->status == 1) {
                            return '<button class="btn btn-success">Active</button>';
                        }
                        else
                        {
                            return '<button class="btn btn-danger">InActive</button>';
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