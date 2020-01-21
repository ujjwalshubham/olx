<?php

use common\grid\EnumColumn;
use common\models\Page;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\widgets\Pjax;

/**
 * @var $this         yii\web\View
 * @var $searchModel  \backend\models\search\PageSearch
 * @var $dataProvider yii\data\ActiveDataProvider
 * @var $model        common\models\Page
 */

$this->title = Yii::t('backend', 'Pages');

$this->params['breadcrumbs'][] = $this->title;

?>
<!-- Partial Table -->
<div class="card">
    <div class="card-header">
        <h4>Pages</h4>
        <div class="pull-right">
            <?php echo Html::a(Yii::t('backend', 'Add {modelClass}', [
                'modelClass' => 'Page',
            ]), 'javascript:void(0)', ['class' => 'btn btn-success','data-toggle'=>'slidePanel',
                'data-url'=>Yii::$app->urlManager->createAbsoluteUrl(['content/page/create'])]); ?>
        </div>
    </div>
    <div class="card-block">
        <?php Pjax::begin([ 'id' => 'pjax-grid', 'enablePushState'=>false, ]); ?>
            <?php echo GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'options' => [
                    'class' => 'grid-view table-responsive',
                ],
                'layout'=>"{items}\n<div class='pagination_section'>{summary}{pager}</div>",
                'columns' => [
                    [
                        'attribute' => 'id',
                    ],
                    [
                        'attribute' => 'slug',
                    ],
                    [
                        'attribute' => 'title',
                        'value' => function ($model) {
                            return Html::a($model->title, ['update', 'id' => $model->id]);
                        },
                        'format' => 'raw',
                    ],
                    [
                        'class' => EnumColumn::class,
                        'attribute' => 'status',
                        'enum' => Page::statuses(),
                        'filter' => Page::statuses(),
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
                    ],
                ],
            ]); ?>
        <?php Pjax::end(); ?>


    </div>
    <!-- .card-block -->
</div>
<!-- .card -->
<!-- End Partial Table -->
