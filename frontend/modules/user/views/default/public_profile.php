<!-- header section start-->
<?php
   use common\components\SidenavWidget; 
   use common\components\ProfileheaderWidget; 
   use yii\helpers\Html;
   use yii\helpers\Url;
   use common\models\PostAd;
   use common\models\UserVisits;
   use common\components\AppHelper;
   
   if (Yii::$app->User->id) {
       $UserProfile = Yii::$app->user->identity->userProfile; 
       $userId = Yii::$app->User->id;
       $myadscount = PostAd::adsCountByUser($userId);  
       $premiumadscount = PostAd::premiumAdsCountByUser($userId); 
       $visitscount = UserVisits::visitsCountOfUser($userId);
   }
?>
<section id="main" class="category-page mid_container">
   <div class="container">
      <!-- breadcrumb -->
      <div class="breadcrumb-section">
         <ol class="breadcrumb">
            <li><a href="<?php echo \Yii::$app->request->BaseUrl; ?>"><i class="fa fa-home"></i> <?php echo Yii::t('frontend', 'Home')?></a></li>
            <li> <a href="javascript:void(0)"><?php echo $title?></a></li>
            <div class="ml-auto back-result"><a href=""><i class="fa fa-angle-double-left"></i> <?php echo Yii::t('frontend', 'Back to Results')?></a> </div>
         </ol>
      </div>
      <!-- breadcrumb -->
      <div class="row recommended-ads">
         <!-- side panel navigation -->
         <?php echo SidenavWidget::widget(); ?>
         <!-- side panel navigation -->
         <div class="col-md-8 col-lg-9">
            <div class="panel-user-details profile_public_view ">
               <div class="user-details section">
                  <div class="row">
                     <div class="col-sm-3 col-lg-3">
                        <?php $profile_image = AppHelper::getUserProfileImage($userId);?> 
                        <div class="user-img profile-img"> 
							<img src="<?php echo $profile_image;?>" alt="<?php echo $UserProfile['name']?>" class="img-responsive"> 
                        </div>
                     </div>
                     <div class="col-sm-12 col-lg-9">
                        <div class="user-admin">
                           <h3><?php echo $UserProfile->name?></h3>
                           <p><?php echo $UserProfile->about?></p>
                           <section class="contacts">
                              <?php if($UserProfile->address){?>
                              <figure class="social-links"><i class="fa fa-map-marker"></i><a href="" ><?php echo $UserProfile->address?></a></figure>
                              <?php } ?>
                              <figure class="social-links"><i class="fa fa-phone"></i><a href=""><?php echo Yii::$app->user->identity->mobile?></a></figure>
                              <figure class="social-links"><a href=""><i class="fa fa-envelope"></i><?php echo Yii::$app->user->identity->email?></a></figure>
                              <?php if($UserProfile->website){?>
                              <figure class="social-links"><i class="fa fa-globe"></i><a href="<?php echo $UserProfile->website?>" target="_blank"><?php echo $UserProfile->website?></a></figure>
                              <?php } ?>
                           </section>
                           <!-- end contacts --> 
                           <!-- social-links -->
                           <p><?php echo Yii::t('frontend', 'Social Profile') ?></p>
                           <ul class="social_share margin-top-100 pull-right">
                              <?php if(isset($UserProfile->facebook_url) && !empty($UserProfile->facebook_url)){?>  
                              <li><a href="<?php echo $UserProfile->facebook_url;?>" target="_blank" class="facebook jssocials-share-facebook"><i class="fa fa-facebook icon_padding"></i></a></li>
                              <?php } ?>
                              <?php if(isset($UserProfile->twitter_url) && !empty($UserProfile->twitter_url)){?>  
                              <li><a href="<?php echo $UserProfile->twitter_url;?>" target="_blank" class="twitter jssocials-share-twitter"><i class="fa fa-twitter icon_padding"></i></a></li>
                              <?php } ?>
                              <?php if(isset($UserProfile->google_plus_url) && !empty($UserProfile->google_plus_url)){?>  
                              <li><a href="<?php echo $UserProfile->google_plus_url;?>" target="_blank" class="googleplus jssocials-share-googleplus"><i class="fa fa-google-plus icon_padding"></i></a></li>
                              <?php } ?>
                              <?php if(isset($UserProfile->instagram_url) && !empty($UserProfile->instagram_url)){?>  
                              <li><a href="<?php echo $UserProfile->instagram_url;?>" target="_blank" class="instagram jssocials-share-instagram"><i class="fa fa-instagram icon_padding"></i></a></li>
                              <?php } ?>
                              <?php if(isset($UserProfile->linkedin_url) && !empty($UserProfile->linkedin_url)){?>  
                              <li><a href="<?php echo $UserProfile->linkedin_url;?>" target="_blank" class="linkedin jssocials-share-linkedin"><i class="fa fa-linkedin icon_padding"></i></a></li>
                              <?php } ?>
                              <?php if(isset($UserProfile->youtube_url) && !empty($UserProfile->youtube_url)){?>  
                              <li><a href="<?php echo $UserProfile->youtube_url;?>" target="_blank" class="youtube jssocials-share-youtube"><i class="fa fa-youtube icon_padding"></i></a></li>
                              <?php } ?>
                           </ul>
                           <!-- social-links --> 
                        </div>
                     </div>
                     <div class="col-sm-12 col-lg-9 offset-lg-3">
                        <div class="user-ads-details">
                           <div class="site-visit">
                              <h3><a href="javascript:void(0)"><?php echo $visitscount?></a></h3>
                              <small><?php echo Yii::t('frontend', 'Visits') ?></small> 
                           </div>
                           <div class="my-quickad">
                              <h3><a href="javascript:void(0)"><?php echo $premiumadscount?></a></h3>
                              <small><?php echo Yii::t('frontend', 'Premium Ads') ?></small> 
                           </div>
                           <div class="favourites">
                              <h3><a href="javascript:void(0)"><?php echo $myadscount?></a></h3>
                              <small><?php echo Yii::t('frontend', 'Total Ads') ?></small> 
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
</section>
