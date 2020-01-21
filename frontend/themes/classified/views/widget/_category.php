<?php 
use common\models\Categories;
use common\components\Olx;
use common\components\AppHelper;

$categories = Olx::getAllCategory();
//echo "<pre>"; print_r($categories );

?>
<div class="section category-quickad text-center">
   <ul class="category-list">
	  <?php foreach($categories as $key=>$category){?> 
		  <?php $cat_image = AppHelper::getCategoryImage($category['id']);
		  ?>
      <li class="category-item">
         <a href="<?php echo \Yii::$app->request->BaseUrl.'/category/'.$category['slug']; ?>">
			<?php if(isset($cat_image) && !empty($cat_image)){?>
            <div class="category-icon"><img src="<?php echo $cat_image?>"></div>
            <?php } else {?>
			<div class="category-icon"><img src="<?php echo \Yii::$app->request->BaseUrl; ?>/images/no-image.png"></div>
			<?php } ?>
            <span class="category-title"><?php echo $category['title']?></span>
         </a>
      </li>
      <?php } ?> 
   </ul>
</div>
