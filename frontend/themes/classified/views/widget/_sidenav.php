<?php
   use yii\helpers\Html;
   use yii\helpers\Url;
   use common\models\PostAd;
   use common\models\User;
   if (Yii::$app->User->id) {
   	$userId = Yii::$app->User->id;
   	$userDetail = User::getUserDetails($userId);
   	$myadscount = PostAd::adsCountByUser($userId);
   	$pendingAds = PostAd::pendingAdsCount($userId);
    $activeAds = PostAd::activeAdsCount($userId);
    $rejectedAds = PostAd::rejectedAdsCount($userId);
   	$hiddenAds = PostAd::hiddenAdsCount($userId);
   	$warningAds = PostAd::warningAdsCount($userId);
   	$resubmitAds = PostAd::resubmitAdsCount($userId);
   	$favouriteAds = PostAd::favouriteAdsCount($userId);
    $UserProfile = Yii::$app->user->identity->userProfile; 
   	$active_url =  basename(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
   }
   ?>
<!-- side panel navigation -->
<div class=" col-sm-12 col-md-4 col-lg-3">
   <div class="user-panel-sidebar">
      <div class="panel-group wrap" id="accordion" role="tablist" aria-multiselectable="true">
         <div class="panel">
            <div class="panel-heading" role="tab" id="headingOne">
               <h4 class="panel-title no-border"> <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseOne"><?php echo Yii::t('frontend', 'My Classified')?></a></h4>
            </div>
            <div id="collapseOne" class="panel-collapse collapse in show" role="tabpanel" aria-labelledby="headingOne">
               <ul class="acc-list">
                  <li class="<?php if($active_url=='dashboard'){?>active<?php }?>">
					  <?php echo Html::a(Html::tag('i', '', ['class' => 'fa fa-home']) . ' ' .\Yii::t('frontend', 'Dashboard'), ['/user/dashboard'], ['class' => 'waves-effect']); ?>
				  </li>
                  <li class="<?php if($active_url=='public-profile'){?>active<?php }?>">
					   <?php echo Html::a(Html::tag('i', '', ['class' => 'fa fa-user']) . ' ' .\Yii::t('frontend', 'Profile public view'), ['/user/public-profile'], ['class' => '']); ?>
				  </li>
				  <?php if(!empty($userDetail['name'])  && (!empty($userDetail['email']))  && (!empty($userDetail['address']))){?>
                  <li class="<?php if($active_url=='post-ad'){?>active<?php }?>">
                     <?php echo Html::a(Html::tag('i', '', ['class' => 'fa fa-pencil']) . ' ' .\Yii::t('frontend', 'Post An Ad'), ['/user/post-ad'], ['class' => '']); ?>
                  </li>
                  <?php } else{?>
				  <li><a onclick="alert('Please Update profile First')" href=""><i class="fa fa-pencil"></i> <?php echo Yii::t('frontend', 'Post An Ad')?></a></li>  
				  <?php } ?>
                  <li class="<?php if($active_url=='membership'){?>active<?php }?>">
					  <a href="<?php echo \Yii::$app->request->BaseUrl.'/user/membership'; ?>"><i class="fa fa-shopping-bag"></i> <?php echo Yii::t('frontend', 'Membership')?> </a>
				  </li>
               </ul>
            </div>
         </div>
         <div class="panel">
            <div class="panel-heading"  id="headingOne">
               <h4 class="panel-title"> <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseTwo"><?php echo Yii::t('frontend', 'My Ads')?></a></h4>
            </div>
            <div id="collapseTwo" class="panel-collapse collapse in show" role="tabpanel" aria-labelledby="headingTwo">
               <ul class="acc-list">
                  <li class="<?php if($active_url=='my-ads'){?>active<?php }?>">
						<a href="<?php echo \Yii::$app->request->BaseUrl.'/user/my-ads'; ?>"><i class="fa fa-book"></i> <?php echo Yii::t('frontend', 'My Ads')?><span class="badge"><?php echo $myadscount?></span> </a>
                  </li>
                  <li class="<?php if($active_url=='active-ads'){?>active<?php }?>">
						<a href="<?php echo \Yii::$app->request->BaseUrl.'/user/active-ads'; ?>"><i class="fa fa-book"></i> <?php echo Yii::t('frontend', 'Active Ads')?><span class="badge"><?php echo $activeAds?></span> </a>
                  </li>
                  <li class="<?php if($active_url=='favourite-ads'){?>active<?php }?>">
					  <a href="<?php echo \Yii::$app->request->BaseUrl.'/user/favourite-ads'; ?>"><i class="fa fa-heart"></i> <?php echo Yii::t('frontend', 'Favourite Ads')?> <span class="badge"><?php echo $favouriteAds?></span> </a>
				  </li>
                  <li class="<?php if($active_url=='pending-ads'){?>active<?php }?>">
					  <a href="<?php echo \Yii::$app->request->BaseUrl.'/user/pending-ads'; ?>"><i class="fa fa-info-circle"></i> <?php echo Yii::t('frontend', 'Pending ads')?><span class="badge"><?php echo $pendingAds?></span></a>
				  </li>
				  <li class="<?php if($active_url=='hidden-ads'){?>active<?php }?>">
					  <a href="<?php echo \Yii::$app->request->BaseUrl.'/user/hidden-ads'; ?>"><i class="fa fa-eye-slash"></i> <?php echo Yii::t('frontend', 'Hidden ads')?><span class="badge"><?php echo $hiddenAds?></span></a>
				  </li>
				  <li class="<?php if($active_url=='warning-ads'){?>active<?php }?>">
					  <a href="<?php echo \Yii::$app->request->BaseUrl.'/user/warning-ads'; ?>"><i class="fa fa-exclamation-triangle"></i> <?php echo Yii::t('frontend', 'Warning ads')?><span class="badge"><?php echo $warningAds?></span></a>
				  </li>
				  <li class="<?php if($active_url=='rejected-ads'){?>active<?php }?>">
					  <a href="<?php echo \Yii::$app->request->BaseUrl.'/user/rejected-ads'; ?>"><i class="fa fa-window-close"></i> <?php echo Yii::t('frontend', 'Rejected ads')?><span class="badge"><?php echo $rejectedAds?></span></a>
				  </li>
                  <li>
					  <a href="javascript:void(0)"><i class="fa fa-calendar-times-o"></i> <?php echo Yii::t('frontend', 'Expire ads')?> <span class="badge">0</span></a>
				  </li>
				  <li class="<?php if($active_url=='resubmit-ads'){?>active<?php }?>">
					  <a href="<?php echo \Yii::$app->request->BaseUrl.'/user/resubmit-ads'; ?>"><i class="fa fa-briefcase"></i> <?php echo Yii::t('frontend', 'Re-submitted Ads')?><span class="badge"><?php echo $resubmitAds?></span></a>
				  </li>
               </ul>
            </div>
         </div>
         <div class="panel">
            <div class="panel-heading" role="tab" id="headingOne">
               <h4 class="panel-title"> <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseThree"><?php echo Yii::t('frontend', 'My Account')?></a></h4>
            </div>
            <div id="collapseThree" class="panel-collapse collapse in show" role="tabpanel" aria-labelledby="headingThree">
               <ul class="acc-list">
                  <li class="<?php if($active_url=='transactions'){?>active<?php }?>">
					  <a href="<?php echo \Yii::$app->request->BaseUrl.'/user/transactions'; ?>"><i class="fa fa-money"></i> <?php echo Yii::t('frontend', 'Transactions')?></a>
				  </li>
                  <!--<li class="<?php if($active_url=='account-setting'){?>active<?php }?>">
                     <?php echo Html::a(Html::tag('i', '', ['class' => 'fa fa-cog']) . '' .\Yii::t('frontend', 'Account Setting'), ['/user/account-setting'], ['class' => '']); ?>
                  </li>-->
                  <li>
                     <?php echo Html::a(Html::tag('i', '', ['class' => 'fa fa-unlock']) . ' ' .\Yii::t('frontend', 'Logout'), ['/logout'], ['data-method' => 'post']); ?>
                  </li>
               </ul>
            </div>
         </div>
      </div>
   </div>
</div>
<!-- side panel navigation -->
