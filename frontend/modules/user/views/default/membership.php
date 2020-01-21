<?php
use common\components\SidenavWidget; 
use common\components\ProfileheaderWidget; 

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\web\View;
use yii\helpers\Url;
use kartik\file\FileInput;
use common\models\Categories;
use common\models\Cities;
use common\models\MediaUpload;
use common\models\PostAd;
use common\models\Plans;

$this->title = Yii::t('frontend', 'My Ads') . ' | OLX';
$planDetail = Plans::getPlanDetail($user_subscription['plan_id']);
//echo "<pre>"; print_r($user_subscription);exit;
?>
<section id="main" class="category-page mid_container">
  <div class="container"> 
    
    <!-- breadcrumb -->
    <div class="breadcrumb-section">
      <ol class="breadcrumb">
        <li><a href="<?php echo \Yii::$app->request->BaseUrl; ?>"><i class="fa fa-home"></i> <?php echo Yii::t('frontend', 'Home')?></a></li>
        <li><?php echo $title?></li>
        <div class="ml-auto back-result"><a href=""><i class="fa fa-angle-double-left"></i> <?php echo Yii::t('frontend', 'Back to Results')?></a> </div>
      </ol>
    </div>
    <!-- breadcrumb -->
    <div class="row recommended-ads"> 
      <!-- side panel navigation -->
      <?php echo SidenavWidget::widget(); ?>
      <!-- side panel navigation -->
      <div class="col-md-8 col-lg-9">
        <div class="section product_section ">
          <div class="row">
            <div class="col-sm-12">
              <div class="section-title my_ads_title ">
                <h2><?php echo Yii::t('frontend', 'Current Plan')?></h2>
              </div>
              <div class="featured_slider h_products table-responsive">
				  <table class="table_my_ads">
				  	<thead>
					  	<tr>
							<th><i class="fa fa-file-text"></i><?php echo Yii::t('frontend', 'Membership')?> </th>
							<th><i class="fa fa-bell"></i> <?php echo Yii::t('frontend', 'Cost')?></th>
							<th><i class="fa fa-cog"></i> <?php echo Yii::t('frontend', 'Term')?></th>
							<th><i class="fa fa-cog"></i> <?php echo Yii::t('frontend', 'Status')?></th>
							<th><i class="fa fa-cog"></i> <?php echo Yii::t('frontend', 'Start Date')?></th>
							<th><i class="fa fa-cog"></i> <?php echo Yii::t('frontend', 'Expiry Date')?></th>
						</tr>
					  </thead>
					  <tbody>
					  	<tr>
							<td class="item_status"><?php echo $planDetail['name']?></td>
							<td class="item_status"><i class="fa fa-inr" aria-hidden="true"></i><?php echo $planDetail['amount']?></td>
							<td class="item_status"><?php echo $planDetail['plan_term']?></td>
							<td class="item_status"><?php echo $user_subscription->status?></td>
							<td class="item_status"><?php echo date('d-m-Y',$user_subscription->from_date)?></td>
							<td class="item_status"><?php echo date('d-m-Y',$user_subscription->to_date)?></td>
						</tr>
						<tr>
							<td align="right" colspan="7"><button type="button" class="btn btn-primary" onclick="window.location.href='<?php echo \Yii::$app->request->BaseUrl.'/'.'user/membership/changeplan' ?>'"><?php echo Yii::t('frontend', 'Change Plan')?></button></td>
						</tr>
					  </tbody>
				  </table>
              </div>
            </div>
          </div>
         
        </div>
      </div>
    </div>
  </div>
</section>
