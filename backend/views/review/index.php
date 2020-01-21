<?php

use yii\helpers\Html;
use yii\grid\GridView;
use common\models\Review;
use yii\web\View;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\search\ReviewSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Reviews';
$this->params['breadcrumbs'][] = $this->title;


$this->registerJs("   

    $('.approve_review').click(function(){
      var check = confirm('Are you sure?');
      if (check == true) {
        var id =this.id;        
        $.ajax({
          type:'POST',
          dataType:'json',
          url:'markapprove',
          data:{'id':id},
          success:function(data){             
              
          }
        });
      }
      else{
        return false;
      }
    });
",View::POS_END);
?>
<div class="review-index">

    <?php echo GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'options' => [
            'class' => 'grid-view table-responsive td_border_zero'
        ],
        'layout'=>"{items}\n<div class='pagination_section'>{summary}{pager}</div>",
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            [
                'label'=>'Name',
                'attribute'=> 'user_id',
                'format' => 'raw',
                'value'=>function ($data) {
                    $client= \common\components\AppHelper::getUser_fullName($data->user_id);
                    return $client;
                },
            ],

            [
                'label'=>'Title',
                'attribute'=> 'ad_id',
                'format' => 'raw',
                'value'=>function ($data) {
                    $client= \common\components\AppHelper::getAdTitle($data->ad_id);
                    return Html::a($client, ['post-ad/view' ,'id'=>$data->ad_id]);
                },
            ],
            'comment:ntext',
            [
                'attribute' => 'rating',
                'format'=>'raw',
                'value' => function ($data) {
                    $basepath= \Yii::$app->request->BaseUrl;
                    $html='';
                    if($data->rating == 5){
                        $html.='<img src="'.$basepath.'/img/starreviews/rating-star5.png">';
                    }
                    elseif($data->rating == 4){
                        $html.='<img src="'.$basepath.'/img/starreviews/rating-star4.png">';
                    }
                    elseif($data->rating == 3){
                        $html.='<img src="'.$basepath.'/img/starreviews/rating-star3.png">';
                    }
                    elseif($data->rating == 2){
                        $html.='<img src="'.$basepath.'/img/starreviews/rating-star2.png">';
                    }
                    elseif($data->rating == 1){
                        $html.='<img src="'.$basepath.'/img/starreviews/rating-star1.png">';
                    }
                    else{
                        $html.='<img src="'.$basepath.'/img/starreviews/rating-star0.png">';
                    }
                    return $html;
                },
            ],
            [
                'class' => \common\grid\EnumColumn::className(),
                'attribute'=>'status',
                'enum' => \common\models\Review::statuses(),
                'filter' => \common\models\Review::statuses(),
                'content' => function ($model, $key, $index, $column) {
                    if($model->status == Review::STATUS_NOT_ACTIVE){
                        return Html::a('<span class="glyphicon glyphicon-ok"></span>',
                            'javascript:void(0);',['id'=> $model->id,'class'=>'approve_review','title'=>'Approve Review']);
                    }
                    else{
                        return $model->status;
                    }
                }
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '<div class="btn-group">{delete}</div>',
                'visibleButtons' => [
                    'login' => Yii::$app->user->can('administrator')
                ]

            ],
        ],
    ]); ?>

</div>
