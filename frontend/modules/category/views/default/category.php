<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

use yii\helpers\Html;
use yii\widgets\ListView;

$this->title = Yii::t('frontend', 'Project List');
$lang = substr(\Yii::$app->language, 0, 2);

$skillsArray = array();
foreach ($skills as $key => $skillsData) {
    $skillsArray[$skillsData['id']] = $skillsData['title_' . $lang];
}

//$values = array(1, 2, 3, 4, 5);
// $average = array_sum($values) / count($values);
// echo round($average);
?>
<section class="inner_bgclr">
    <div class="container">
        <div class="row">
            <div class="col-sm-6">
                <div class="banner_text">
                    <h1><?php echo Yii::t('frontend', 'Top jobs') ?></h1>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="banner_rightcol"> 
                    <?php echo Html::a(Yii::t('frontend', 'Post a Project'), Yii::getAlias('@web/user/post-project'), ['class' => 'banner_btn banner_btnbg']); ?>  
                </div>
            </div>
        </div>
    </div>
</section>

<!--Middle Block start-->
<section class="middle_blk middle_bgclr">
    <div class="container">
        <div class="row">
            <?php echo $this->render('_search', ['model' => $searchModel]); ?>

            <div class="col-sm-12 col-lg-12">
                <div class="right_panal clearfix">
                    <?php
                    echo ListView::widget([
                        'layout' => '<div class="find_worktextbox"><div class="col-sm-12"><div class="pagination_area">{pager}</div></div></div>{items}<div class="right_bottomarea"><div class="find_worktextbox"><div class="col-sm-12"><div class="pagination_area">{pager}</div></div></div></div>',
                        'dataProvider' => $dataProvider,
                        'itemView' => '_category-project-list',
                        'itemOptions' => [
                            'tag' => false
                        ],
                        'options' => [
                            'class' => 'news-v2 margin-bottom-50',
                            'id' => false
                        ]
                    ]);
                    ?>
                </div>
            </div>
        </div>
    </div>
</section>


