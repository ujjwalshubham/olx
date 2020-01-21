<?php
use yii\helpers\Html;
use yii\helpers\Url;
use common\models\PostAd;
use common\models\User;
use common\models\UserSubscription;
use common\components\AppHelper;

if (Yii::$app->User->id) {
    $UserProfile = Yii::$app->user->identity->userProfile;  
    $userId = Yii::$app->User->id;
    $userSubscription = UserSubscription::getUserSubscription($userId);
   	$myadscount = PostAd::adsCountByUser($userId);
   	$user = User::getUserDetails($userId);
   	$favouriteAds = PostAd::favouriteAdsCount($userId);
}
?>

<div class="user-details section">
            <div class="user-img">
			    <?php $profile_image = AppHelper::getUserProfileImage($userId);?>
				<img src="<?php echo $profile_image?>" alt="<?php echo $UserProfile['name']?>" class="img-responsive">
			</div>
            <div class="user-admin">
              <h3><a href="javascript:void(0)"><?php echo Yii::t('frontend', 'Hello')?> <?php echo $UserProfile->name?></a></h3>
              <?php if(isset($userSubscription) && !empty($userSubscription)){?>
              <span><?php echo Yii::t('frontend', 'Membership')?>: <?php echo $userSubscription['plan_name']?></span> 
              <?php } else {?>
			  <span><?php echo Yii::t('frontend', 'Membership')?>: Free</span>   
			  <?php } ?>
              <small><?php echo Yii::t('frontend', 'You last logged in at')?>: <?php echo date('Y-m-d h:i:s',$user['logged_at'])?></small> </div>
            <div class="user-ads-details">
              <div class="my-quickad">
                <h3><a href="<?php echo \Yii::$app->request->BaseUrl.'/user/my-ads'; ?>"><?php echo $myadscount?></a></h3>
                <small><?php echo Yii::t('frontend', 'My Ads')?></small> </div>
              <div class="favourites">
                <h3><a href="<?php echo \Yii::$app->request->BaseUrl.'/user/favourite-ads'; ?>"><?php echo $favouriteAds?></a></h3>
                <small><?php echo Yii::t('frontend', 'Favourites')?></small> </div>
            </div>
          </div>
