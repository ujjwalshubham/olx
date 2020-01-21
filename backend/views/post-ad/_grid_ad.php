<?php

use yii\helpers\Html;
use yii\grid\GridView;
use common\models\PostAd;
use yii\web\View;
use common\components\AppHelper;

?>

<?php echo GridView::widget([
    'dataProvider' => $dataProvider,
    'filterModel' => $searchModel,
    'columns' => [
        ['class' => 'yii\grid\SerialColumn'],

        [
            'attribute' => 'title',
            'value' => function($data) {
                return $data->title;
            },
            'enableSorting' => false
        ],
        [
            'attribute' => 'user_name',
            'format' => 'raw',
            'label'=>'Posted By',
            'value' => function($data) {
                return \common\components\AppHelper::getUser_fullName($data->user_id);
            },
            'enableSorting' => false
        ],
        [
            'attribute' => 'user_mobile',
            'format' => 'raw',
            'label'=>'Mobile',
            'value' => function($data) {
                $user=AppHelper::getUserDetail($data->user_id);
                return $user->mobile;
            },
            'enableSorting' => false
        ],
        [
            'attribute' => 'user_email',
            'format' => 'raw',
            'label'=>'Email',
            'value' => function($data) {
                $user=AppHelper::getUserDetail($data->user_id);
                return $user->email;
            },
            'enableSorting' => false
        ],
        [
            'attribute' => 'category',
            'format' => 'raw',
            'label'=>'Category',
            'value' => function($data) {
                $categories=AppHelper::getPostAdCategories($data->id);
                return $categories['category'];
            },
            'enableSorting' => false
        ],
        [
            'attribute' => 'subcategory',
            'format' => 'raw',
            'label'=>'SubCategory',
            'value' => function($data) {
                $categories=AppHelper::getPostAdCategories($data->id);
                return $categories['subcategory'];
            },
            'enableSorting' => false
        ],
        [
            'attribute' => 'city',
            'format' => 'raw',
            'label'=>'Location',
            'value' => function($data) {
                $city= \common\models\Cities::getCityById($data->city);
                if(!empty($city)){
                    return $city['name'];
                }
                else{
                    return '';
                }

            },
            'enableSorting' => false
        ],
        [
            'attribute' => 'created_at',
            'format' => 'raw',
            'label'=>'Posted On',
            'value' => function($data) {
                $time= \common\components\AppHelper::getPostTime($data->created_at);
                return $time;
            },
            'enableSorting' => false
        ],

        [
            'content' => function ($model, $key, $index, $column) {
                $html='';
                $html=Html::a('<span class="glyphicon glyphicon-eye-open"></span>', ['view', 'id' => $model->id]);
                return $html;
            }
        ],

        /*[
            'content' => function ($model, $key, $index, $column) {
                $html='';
                $html.=Html::a('<span class="glyphicon glyphicon-ok"></span>',
                    'javascript:void(0);', ['id' => $model->id,'class'=>'approve_review','title'=>'Approve Ad']);
                $html.=' ';
                $html.=Html::a('<span class="glyphicon glyphicon-eye-open"></span>', ['view', 'id' => $model->id]);
                $html.=' ';
                $html.=Html::a('<span class="glyphicon glyphicon-trash"></span>',
                    ['delete', 'id' => $model->id], [
                        'data-method'=> 'post',
                        'data-confirm' => 'Are you sure you want to delete this item?',
                    ]);
                return $html;
            }
        ],*/
    ],
]); ?>