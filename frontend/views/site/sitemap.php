<?php
   use yii\helpers\Html;
   use yii\widgets\ActiveForm;
   use common\components\Olx;
   use common\components\AppHelper;
    use common\components\BreadcrumbWidget; 
   
   $this->title = 'Site-Map';
   $this->params['breadcrumbs'][] = $this->title;
   $baseurl =\Yii::$app->getUrlManager()->getBaseUrl(); 
   
   $categories = Olx::getAllCategory();
   
   ?>
<section id="main" class="category-page mid_container">
   <div class="container">
      <!-- breadcrumb -->
       <?php echo BreadcrumbWidget::widget(); ?>
      <!-- breadcrumb -->
      <div class="row recommended-ads">
         <div class="col-sm-12">
            <div class="section faq_page ">
               <h5><?php echo Yii::t('frontend', 'List of Categories and Sub-categories')?></h5>
               <ul class="sitemap_category">
                  <?php foreach($categories as $key=>$category){?>  
                  <?php $cat_image = AppHelper::getCategoryImage($category['id']); ?>
                  <li>
                     <a href="#anchor<?php echo $key+1;?>">
                        <div class="icon"> 
                           <?php if(isset($cat_image) && !empty($cat_image)){?>
                           <img class="image-icon" src="<?php echo $cat_image?>">
                           <?php }else{?>
                           <img class="image-icon" src="<?php echo \Yii::$app->request->BaseUrl; ?>/images/no-image.png">	 
                           <?php } ?>
                        </div>
                        <span><?php echo $category['title']?></span>
                     </a>
                  </li>
                  <?php } ?>
               </ul>
               <ul class="sitemap_listing" >
                  <?php foreach($categories as $key=>$category){?>  
                  <?php $cat_image = AppHelper::getCategoryImage($category['id']); ?>
                  <li>
                     <span id="anchor<?php echo $key+1;?>">
                        <h3>
                           <?php if(isset($cat_image) && !empty($cat_image)){?>
                           <img class="image-icon" src="<?php echo $cat_image?>">
                           <?php }else{?>
                           <img class="image-icon" src="<?php echo \Yii::$app->request->BaseUrl; ?>/images/no-image.png">	 
                           <?php } ?>
                           <?php echo $category['title']?>
                        </h3>
                     </span>
                     <div class="sub-item-wrapper">
                        <h4> 
						<a href="<?php echo \Yii::$app->request->BaseUrl.'/category/'.$category['slug']; ?>">
                        <?php echo $category['title']?></a>
                        
                        <?php $ads_count = AppHelper::getAdsCountByCategory($category['id']); ?>
						<span>(<?php echo $ads_count?>)</span> 
						
						
                        </h4>
                        <?php $sub_categories = AppHelper::getSubCategory($category['id']);?>
                        <ul class="sub-item-cont">
                           <?php foreach($sub_categories as $key=>$sub_cat){?> 
							  <?php $sub_ads_count = AppHelper::getAdsCountByCategory($sub_cat['id']); ?>
                           <li><a href="<?php echo \Yii::$app->request->BaseUrl.'/category/'.$sub_cat['slug']; ?>" data-ajax-id="<?php echo $sub_cat['id']?>" data-cat-type="subcat"><?php echo $sub_cat['title']?> (<?php echo $sub_ads_count?>)</a></li>
                           <?php } ?>
                        </ul>
                     </div>
                  </li>
                  <?php } ?>
               </ul>
            </div>
         </div>
      </div>
   </div>
</section>
<style>
   img.image-icon {
   height: 25px;
   }
</style>
