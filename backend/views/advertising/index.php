<?php

use yii\helpers\Html;
use yii\grid\GridView;
use common\models\Advertising;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\search\AdvertisingSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Advertisings';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="card">
    <div class="card-header">
        <h4>Advertise</h4>
    </div>

    <div class="card-block">
        <?php Pjax::begin([ 'id' => 'pjax-grid', 'enablePushState'=>false, ]); ?>
            <?php echo GridView::widget([
                'dataProvider' => $dataProvider,
                //'filterModel' => $searchModel,
                'options' => [
                    'class' => 'grid-view table-responsive td_border_zero'
                ],
                'layout'=>"{items}\n<div class='pagination_section'>{summary}{pager}</div>",
                'columns' => [
                    //['class' => 'yii\grid\SerialColumn'],

                    'slug',
                    'provider_name',
                    // 'code_tablet_format:ntext',
                    // 'code_phone_format',
                    [
                        'class' => \common\grid\EnumColumn::class,
                        'attribute' => 'status',
                        'enum' => \common\models\Advertising::statuses(),
                        'filter' => \common\models\Advertising::statuses(),
                        'format'=>'raw',
                        'value' => function ($data) {
                            $html='';
                            if($data->status == Advertising::STATUS_NOT_ACTIVE){
                                $html.='<span class="label label-info">Not Active</span>';
                            }
                            elseif($data->status == Advertising::STATUS_ACTIVE){
                                $html.='<span class="label label-success">Active</span>';
                            }
                            else{
                                $html='';
                            }
                            return $html;
                        },
                    ],
                    // 'created_at',
                    // 'updated_at',
                    // 'created_by',
                    // 'updated_by',

                    [
                        'class' => 'yii\grid\ActionColumn',
                        'template' => '{update}',
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