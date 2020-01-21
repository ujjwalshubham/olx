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
use common\components\AppHelper;
use yii\widgets\LinkPager;

$this->title = Yii::t('frontend', 'Pending Ads') . ' | OLX';
$baseurl =\Yii::$app->getUrlManager()->getBaseUrl(); 
?>
<section id="main" class="category-page mid_container">
  <div class="container"> 
    
    <!-- breadcrumb -->
    <div class="breadcrumb-section">
      <ol class="breadcrumb">
        <li><a href="<?php echo \Yii::$app->request->BaseUrl; ?>"><i class="fa fa-home"></i> <?php echo Yii::t('frontend', 'Home')?></a></li>
        <li><?php echo $title?> </li>
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
                <h2><?php echo Yii::t('frontend', 'Pending Ads')?></h2>
              </div>
              <div class="featured_slider h_products table-responsive">
				  
				  <table class="table_my_ads">
				  	<thead>
					  	<tr>
							<th><i class="fa fa-file-text"></i> <?php echo Yii::t('frontend', 'Item Details')?></th>
							<th><i class="fa fa-bell"></i> <?php echo Yii::t('frontend', 'Status')?></th>
							<th><i class="fa fa-cog"></i> <?php echo Yii::t('frontend', 'Option')?></th>
						</tr>
					  </thead>
					  <?php if(!empty($pending_ads)){?>  
					  <tbody>
						  
						  
					<?php foreach($pending_ads as $key=>$myad){?>
					  	<tr>
							<td class="title-container">
								<div class="my_ads_ot">
								<?php if($myad['ads_images']){?>
								<?php $media_id = $myad['ads_images'][0]['media_id'];
								$image = MediaUpload::getImageByMediaId($media_id);
								?>									
								<img src="<?php echo $image['url']. $image['upload_base_path'].$image['file_name']?>">
							    <?php } else {?>
								<img src="<?php echo \Yii::$app->request->BaseUrl; ?>/images/no-image.jpg">	
								<?php } ?>
							    <div class="item-title new-item-title">
                                        <h4> <?php echo Html::a($myad['title'], ['/user/ad-detail/'.$myad['slug']], ['class' => '']); ?></h4>
                                        
                                        <ol class="breadcrumb">
											<?php foreach ($myad['category_id'] as $category){?>
												<?php $cat_name = Categories::getCategoryNameById($category['cat_id']);?>
                                            <li><a href=""><?php echo  $cat_name['title']?></a></li>
                                            <?php } ?>
                                        </ol>
                                        <ul class="item-details">
                                            <li><i class="fa fa-map-marker"></i><a href="javascript:void(0)"><?php echo $myad['city_name']?></a></li>
                                            <?php $post_time =  AppHelper::getPostTime($myad['created_at']);?>
                                            <li><i class="fa fa-clock-o"></i><?php echo $post_time;?></li>
                                        </ul>
                                         <?php if(isset($myad['price']) && !empty($myad['price'])){?>  
                                         <span class="table-item-price"> <?php echo $myad['price']?> <i class="fa fa-inr" aria-hidden="true"></i> </span>
                                         <?php } ?>
                                         </div>
                                          <?php if($myad['ad_type']=='featured'){?>
									   <div class="item-image-box-tag featured_bg"><?php echo $myad['ad_type']?></div>
									   <?php } else if($myad['ad_type']=='highlight'){?>
									   <div class="item-image-box-tag hightlight_bg"><?php echo $myad['ad_type']?></div>   
									   <?php } else if($myad['ad_type']=='urgent'){?>
									   <div class="item-image-box-tag urgent_bg"><?php echo $myad['ad_type']?></div> 
									   <?php } ?>
                               </div>        
                                         
							</td>
							<td class="item_status"><span class="label"><?php echo $myad['status']?></span></td>
							<td class="action">
								<a href="<?php echo $baseurl.'/user/edit-ad/'.$myad['id']; ?>"><i class="fa fa-pencil"></i> <?php echo Yii::t('frontend', 'Edit')?></a><br>
								
							</td>
						</tr>
						  <?php } ?>
						</tbody>
						<?php } else{?>
						<tbody>
							<tr>
								<td colspan="3" class="no-ads"><h3><?php echo Yii::t('frontend', 'No Ads Found')?></h3></td>
							</tr>
						</tbody>
						<?php } ?>
				  </table>
              </div>
            </div>
          </div>
           <?php echo LinkPager::widget(['pagination' => $pages]);?>
        </div>
      </div>
    </div>
  </div>
</section>

