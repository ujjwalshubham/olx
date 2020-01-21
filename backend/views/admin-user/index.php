<?php

use common\grid\EnumColumn;
use common\models\User;
use trntv\yii\datetime\DateTimeWidget;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\web\JsExpression;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\search\UserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('backend', 'Users');
$this->params['breadcrumbs'][] = $this->title;
?>

<!-- Partial Table -->
<div class="card">
    <div class="card-header">
        <h4>Admin users</h4>
        <div class="pull-right">
            <?php echo Html::a(Yii::t('backend', 'Add Admin {modelClass}', [
                'modelClass' => 'User',
            ]), 'javascript:void(0)', ['class' => 'btn btn-success','data-toggle'=>'slidePanel',
                'data-url'=>Yii::$app->urlManager->createAbsoluteUrl(['admin-user/create'])]); ?>
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
                // 'id',
                'username',
                'email:email',
                [
                    'class' => EnumColumn::class,
                    'attribute' => 'status',
                    'enum' => User::statuses(),
                    'filter' => User::statuses(),
                    'format'=>'raw',
                    'value' => function ($data) {
                        $html='';
                        if($data->status == User::STATUS_NOT_ACTIVE){
                            $html.='<span class="label label-info">Not Active</span>';
                        }
                        elseif($data->status == User::STATUS_ACTIVE){
                            $html.='<span class="label label-success">Active</span>';
                        }
                        elseif($data->status == User::STATUS_DELETED){
                            $html.='<span class="label label-danger">Delete</span>';
                        }
                        else{
                            $html='';
                        }
                        return $html;
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
    <!-- .card-block -->
</div>
<!-- .card -->
<!-- End Partial Table -->