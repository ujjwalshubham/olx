<section id="main" class="category-page mid_container">
  <div class="container">
    <div class="membership_mainpart pricing-plans-carousel">
    <div class="section-title text-center mb-8 position-relative">
            <h1 class="font-weight-semibold"><?php echo Yii::t('frontend', 'Membership Plan')?></h1>
            <p class="font-weight-bold text-gray-600 quickad_lang_translator" data-quickad-lang="All Packages"><?php echo Yii::t('frontend', 'All Packages')?></p>
        </div>
      <div class="slider membership_slider">
		<?php if(isset($plans) && !empty($plans)){?>  
		<?php foreach($plans as $key=>$plan){?>  
        <div>
          <div class="p-3">
            <div class="pricing-plan recommended text-center border py-7 px-2 bg-light rounded-10">
              <h4 class="font-weight-bold text-gray-600 mb-4"><?php echo $plan['name']?></h4>
              <p class="font-weight-bold font-size-20 mb-4"><i class="fa fa-inr" aria-hidden="true"></i><span class="price-text font-size-35 align-middle"><?php echo $plan['amount']?></span>/<span class="font-size-14"><?php echo $plan['plan_term']?> </span></p>
              <ul class="list-unstyled mb-6">
				  
                <li class="mb-2"> <span class="icon-text yes"><i class="fa fa-check-circle mr-2"></i></span><?php echo Yii::t('frontend', 'Ad Post Limit')?>  : <span class="font-weight-bold"><?php echo $plan['ad_limit']?> </span> </li>
                
                <li class="mb-2"> <span class="icon-text yes"><i class="fa fa-check-circle mr-2"></i></span><?php echo Yii::t('frontend', 'Ad expiry in')?>: <span class="font-weight-bold"><?php echo $plan['ad_duration']?></span> days </li>
                
                <li class="mb-2"> <span class="icon-text yes"><i class="fa fa-check-circle mr-2"></i></span><?php echo Yii::t('frontend', ' Featured ad fee')?> <span class="font-weight-bold"><i class="fa fa-inr" aria-hidden="true"></i><?php echo $plan['featured_project_fee']?> for <?php echo $plan['featured_duration']?>days</span> </li>
                
                <li class="mb-2"> <span class="icon-text yes"><i class="fa fa-check-circle mr-2"></i></span> <?php echo Yii::t('frontend', 'Urgent ad fee')?> <span class="font-weight-bold"><i class="fa fa-inr" aria-hidden="true"></i><?php echo $plan['urgent_project_fee']?> for <?php echo $plan['urgent_duration']?> days</span> </li>
                
                <li class="mb-2"> <span class="icon-text yes"><i class="fa fa-check-circle mr-2"></i></span> <?php echo Yii::t('frontend', 'Highlight ad fee')?> <span class="font-weight-bold"><i class="fa fa-inr" aria-hidden="true"></i><?php echo $plan['highlight_project_fee']?> for <?php echo $plan['highlight_duration']?> days</span> </li>
                
                <?php if($plan['top_search_result'] ==1){?>
                <li class="mb-2"> <span class="icon-text yes"><i class="fa fa-check-circle mr-2"></i></span> <?php echo Yii::t('frontend', 'Top in search results and category')?></li>
                <?php } else {?>
				 <li class="mb-2"> <span class="icon-text no"><i class="fa fa-times-circle mr-2"></i></span> <?php echo Yii::t('frontend', 'Top in search results and category')?></li>
				<?php } ?>
                
                <?php if($plan['show_on_home'] ==1){?>
                <li class="mb-2"> <span class="icon-text yes"><i class="fa fa-check-circle mr-2"></i></span> <?php echo Yii::t('frontend', 'Show ad on home page premium ad section')?></li>
                <?php } else {?>
				<li class="mb-2"> <span class="icon-text no"><i class="fa fa-times-circle mr-2"></i></span> <?php echo Yii::t('frontend', 'Show ad on home page premium ad section')?></li>
				<?php } ?>
                
                <?php if($plan['show_in_home_search'] ==1){?>
                <li class="mb-2"> <span class="icon-text yes"><i class="fa fa-check-circle mr-2"></i></span> <?php echo Yii::t('frontend', 'Show ad on home page search result list')?></li>
                <?php } else {?>
				 <li class="mb-2"> <span class="icon-text no"><i class="fa fa-times-circle mr-2"></i></span> <?php echo Yii::t('frontend', 'Show ad on home page search result list')?></li>	
				<?php } ?>
              </ul>
              
              <?php if(isset($user_subscription) && !empty($user_subscription)){?>
			  <?php if($user_subscription->plan_id==$plan['id']){?>
              <div class="position-relative">
					<a href="javascript:void(0)" class="btn btn-primary">
						<i class="fa fa-paper-plane mr-2"></i>
						<?php echo Yii::t('frontend', 'Current Plan')?>
					</a>
			  </div>
			  <?php } else{?>
			  <div class="position-relative">
                <form name="form1" method="post">
                  <input class="signup cursor" name="plan_id" type="hidden" value="<?php echo $plan['id']?>">
                  <input type="submit" class="btn btn-dark" name="Submitup" value="<?php echo Yii::t('frontend', 'Upgrade')?>">
                </form>
              </div>
			  <?php } }else{?>
              <div class="position-relative">
                <form name="form1" method="post">
                  <input class="signup cursor" name="plan_id" type="hidden" value="<?php echo $plan['id']?>">
                  <input type="submit" class="btn btn-dark" name="Submitup" value="<?php echo Yii::t('frontend', 'Upgrade')?>">
                </form>
              </div>
              
              <?php } ?>
            </div>
          </div>
        </div>
        <?php } ?>
        <?php } ?>
     
      </div>
    </div>
  </div>
</section>
