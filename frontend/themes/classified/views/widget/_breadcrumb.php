<?php
   use yii\helpers\Html;
   use yii\helpers\Url;
   use common\models\PostAd;
   if (Yii::$app->User->id) {
   	$userId = Yii::$app->User->id;
    $UserProfile = Yii::$app->user->identity->userProfile; 
   	$active_url =  basename(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
   	$this->title = 'Site-Map';
    $this->params['breadcrumbs'][] = $this->title;
    $baseurl =\Yii::$app->getUrlManager()->getBaseUrl(); 
   }
   ?>
<!-- breadcrumb -->
      <div class="breadcrumb-section">
         <ol class="breadcrumb">
            <li><a href="<?php echo $baseurl?>"><i class="fa fa-home"></i> <?php echo Yii::t('frontend', 'Home')?></a></li>
            <li><?php echo $this->title ?> </li>
            <div class="ml-auto back-result"><a href=""><i class="fa fa-angle-double-left"></i>
               <?php echo Yii::t('frontend', 'Back to Results')?></a>
            </div>
         </ol>
         <h2 class="title"><?php echo $this->title ?></h2>
      </div>
      
      
      <!-- breadcrumb -->
