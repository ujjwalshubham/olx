

<section id="main" class="category-page mid_container">
  <div class="container">
    <div class="membership_mainpart pricing-plans-carousel">
    <div class="section-title text-center mb-8 position-relative">
            <h1 class="font-weight-semibold"><?php echo Yii::t('frontend', 'Membership Plan')?></h1>
            <p class="font-weight-bold text-gray-600 quickad_lang_translator" data-quickad-lang="All Packages"><?php echo Yii::t('frontend', 'All Packages')?></p>
        </div>
      <div class="slider membership_slider">
		  
		<?php if(isset($packages) && !empty($packages)){?>  
		<?php foreach($packages as $key=>$package){?>  
        <div>
          <div class="p-3">
            <div class="pricing-plan recommended text-center border py-7 px-2 bg-light rounded-10">
              <h4 class="font-weight-bold text-gray-600 mb-4"><?php echo $package['name']?></h4>
              <p class="font-weight-bold font-size-20 mb-4">&#112;&#46;<span class="price-text font-size-35 align-middle">9.00</span>/<span class="font-size-14">Monthly </span></p>
              <ul class="list-unstyled mb-6">
				  
                <li class="mb-2"> <span class="icon-text yes"><i class="fa fa-check-circle mr-2"></i></span><?php echo Yii::t('frontend', 'Ad Post Limit')?>  : <span class="font-weight-bold"><?php echo $package['ad_limit']?> </span> </li>
                
                <li class="mb-2"> <span class="icon-text yes"><i class="fa fa-check-circle mr-2"></i></span><?php echo Yii::t('frontend', 'Ad expiry in')?>: <span class="font-weight-bold"><?php echo $package['ad_duration']?></span> days </li>
                
                <li class="mb-2"> <span class="icon-text yes"><i class="fa fa-check-circle mr-2"></i></span><?php echo Yii::t('frontend', ' Featured ad fee')?> <span class="font-weight-bold">&#112;&#46;<?php echo $package['featured_project_fee']?> for <?php echo $package['featured_duration']?>days</span> </li>
                
                <li class="mb-2"> <span class="icon-text yes"><i class="fa fa-check-circle mr-2"></i></span> <?php echo Yii::t('frontend', 'Urgent ad fee')?> <span class="font-weight-bold">&#112;&#46;<?php echo $package['urgent_project_fee']?> for <?php echo $package['urgent_duration']?> days</span> </li>
                
                <li class="mb-2"> <span class="icon-text yes"><i class="fa fa-check-circle mr-2"></i></span> <?php echo Yii::t('frontend', 'Highlight ad fee')?> <span class="font-weight-bold">&#112;&#46;<?php echo $package['highlight_project_fee']?> for <?php echo $package['highlight_duration']?> days</span> </li>
                
                
                <?php if($package['top_search_result'] ==1){?>
                <li class="mb-2"> <span class="icon-text yes"><i class="fa fa-check-circle mr-2"></i></span> <?php echo Yii::t('frontend', 'Top in search results and category')?></li>
                <?php } else {?>
				 <li class="mb-2"> <span class="icon-text no"><i class="fa fa-times-circle mr-2"></i></span> <?php echo Yii::t('frontend', 'Top in search results and category')?></li>
					
				<?php } ?>
                
                
                <?php if($package['show_on_home'] ==1){?>
                <li class="mb-2"> <span class="icon-text yes"><i class="fa fa-check-circle mr-2"></i></span> <?php echo Yii::t('frontend', 'Show ad on home page premium ad section')?></li>
                <?php } else {?>
				<li class="mb-2"> <span class="icon-text no"><i class="fa fa-times-circle mr-2"></i></span> <?php echo Yii::t('frontend', 'Show ad on home page premium ad section')?></li>
				<?php } ?>
                
                <?php if($package['show_in_home_search'] ==1){?>
                <li class="mb-2"> <span class="icon-text yes"><i class="fa fa-check-circle mr-2"></i></span> <?php echo Yii::t('frontend', 'Show ad on home page search result list')?></li>
                <?php } else {?>
				 <li class="mb-2"> <span class="icon-text no"><i class="fa fa-times-circle mr-2"></i></span> <?php echo Yii::t('frontend', 'Show ad on home page search result list')?></li>	
				<?php } ?>
                
              </ul>
              <div class="position-relative">
                <form name="form1" method="post" action="">
                  <input class="signup cursor" name="upgrade" type="hidden" value="1">
                  <input type="submit" class="btn btn-dark" name="Submitup" value="<?php echo Yii::t('frontend', 'Upgrade')?>">
                </form>
              </div>
            </div>
          </div>
        </div>
        <?php } ?>
        <?php } ?>
     
      </div>
    </div>
  </div>
</section>
