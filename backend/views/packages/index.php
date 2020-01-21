<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\search\PackagesSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Packages';
$this->params['breadcrumbs'][] = $this->title;
?>
<!-- Partial Table -->
<div class="card">
    <div class="card-header">
        <h4>Packages</h4>
        <div class="pull-right">
            <?php echo Html::a(Yii::t('backend', 'Add {modelClass}', [
                'modelClass' => 'Package',
            ]), 'javascript:void(0)', ['class' => 'btn btn-success','data-toggle'=>'slidePanel',
                'data-url'=>Yii::$app->urlManager->createAbsoluteUrl(['packages/create'])]); ?>
        </div>
    </div>

    <div class="card-block">
        <?php Pjax::begin([ 'id' => 'pjax-grid', 'enablePushState'=>false, ]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        //'filterModel' => $searchModel,
            'options' => [
                'class' => 'grid-view table-responsive td_border_zero'
            ],
            'layout'=>"{items}\n<div class='pagination_section'>{summary}{pager}</div>",
        'columns' => [

            'name',
            ['label' =>'Duration',
                'attribute'=>'ad_duration',
                'value' => function ($data) {
                    return $data->ad_duration.' '.'days';
                },
            ],
            ['label' =>'Removable',
                'attribute'=>'group_removable',
                'value' => function ($data) {
                    if($data->group_removable == 0){
                        return 'No';
                    }
                    else{
                        return 'Yes';
                    }
                },
            ],


            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '<div class="btn-group">{update}</div>',
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

