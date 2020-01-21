<?php



/* @var $this yii\web\View */
/* @var $searchModel backend\models\search\PostAdSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Post Ads';
$this->params['breadcrumbs'][] = $this->title;


?>
<div class="post-ad-index">

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?php  echo $this->render('_grid_ad', ['searchModel' => $searchModel,'dataProvider'=>$dataProvider]); ?>



</div>
