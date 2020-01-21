<?php
   use common\components\CategoryWidget;
   use common\models\Categories;
   use common\models\Cities;
   use common\models\MediaUpload;
   use common\components\Olx;
   use common\components\AppHelper;
   use yii\helpers\Html;
   use yii\widgets\ActiveForm;
   use yii\web\View;
   use yii\helpers\Url;
   $userId = Yii::$app->user->identity['id'];
   $categories = Olx::getAllCategory();
   $baseUrl= Yii::getAlias('@frontendUrl');
?>
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.1/jquery.min.js"></script>
<script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/jquery-ui.min.js"></script>
<script src="http://code.jquery.com/ui/1.10.2/jquery-ui.js"></script>
<input type="hidden" name="uribase" id="uribase" value="<?php echo $baseUrl; ?>"/>
<!-- Footer section start-->
<div class="footer-section">
   <div class="container">
      <div class="row">
         <!--About Us-->
         <div class="col-md-4 col-sm-12">
             <?php $logo=\common\components\AppHelper::getSiteLogo();?>
            <div class="ft-logo"><img src="<?php echo $logo; ?>" alt="Footer Logo" title="Footer Logo"></div>
            <p>Quickad offers free classified ads worldwide. Quickad is the next generation of free online classifieds. Quickad provides a simple solution to the complications involved in selling, buying, trading, discussing, organizing, and meeting people near you.</p>
         </div>
         <!--About us End--><!--Help Support-->
         <div class="col-md-2 col-sm-6">
            <h5><?php echo Yii::t('frontend', 'Help & Support')?></h5>
            <!--Help Support menu Start-->
            <ul class="helpMenu">
               <li><?php echo Html::a(\Yii::t('frontend', 'FAQ'), ['/faq'], ['class' => '']); ?></li>
               <li><?php echo Html::a(\Yii::t('frontend', 'Feedback'), ['/feedback'], ['class' => '']); ?></a></li>
               <li><?php echo Html::a(\Yii::t('frontend', 'Contact'), ['/contact'], ['class' => '']); ?></li>
            </ul>
         </div>
         <!--Help Support menu end--><!--Information-->
         <div class=" col-md-5 col-lg-2">
            <h5><?php echo Yii::t('frontend', 'Information')?></h5>
            <!--Information menu Start-->
            <ul class="helpMenu">
               <li><a href="<?php echo $baseUrl ?>/page/terms-conditions"><?php echo Yii::t('frontend', 'Terms & Conditions')?></a></li>
               <li><a href="<?php echo $baseUrl ?>/page/privacy-policy"><?php echo Yii::t('frontend', 'Privacy Policy')?></a></li>
               <li><?php echo Html::a(\Yii::t('frontend', 'Site-Map'), ['/sitemap'], ['class' => '']); ?></a></li>
            </ul>
            <!--Information menu End-->
            <div class="clear"></div>
         </div>
         <!--Contact Us-->
         <div class=" col-md-4 col-lg-3">
            <h5><?php echo Yii::t('frontend', 'Contact Us')?></h5>
            <div class="address"> 142, Aksakcw land, Triswas</div>
            <div class="phone"><a href="tel:1-972-8103-393">1-972-8103-393</a></div>
            <div class="email"><a href="javascript:void(0)">helpdesk.mshop@gmail.com</a></div>
            <!-- Social Icons -->
            <?php if(!empty($social_links)) { ?>
            <div class="social"> 
				<a href="" target="_blank"><i class="fa fa-facebook"></i></a> 
				<a href="" target="_blank"><i class="fa fa-twitter"></i></a> 
				<a href="" target="_blank"><i class="fa fa-google-plus"></i></a> 
				<a href="" target="_blank"><i class="fa fa-youtube"></i></a> 
		    </div>
		    <?php } ?>
            <!-- Social Icons end --> 
         </div>
      </div>
      <div class="col-md-12">
         <div class="copyright text-center">
            <p>2019 Mshop, All right reserved</p>
         </div>
      </div>
   </div>
</div>
<!-- Footer section End-->
<!--*********************************Modals*************************************-->

<div class="modal fade modalHasList" id="selectCountry" tabindex="-1" role="dialog" aria-labelledby="selectCountryLabel"
   aria-hidden="true">
   <div class="modal-dialog modal-lg">
      <div class="modal-content">
         <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">
            <span aria-hidden="true">&times;</span>
            <span class="sr-only">{LANG_CLOSE}</span>
            </button>
            <h4 class="modal-title uppercase font-weight-bold" id="selectCountryLabel">
               <i class="icon-map"></i> Select your country 
            </h4>
         </div>
         <div class="modal-body">
            <div class="row" style="padding: 0 20px">
               <ul class="column col-md-12 col-sm-12 cities">
                  <li><span class="flag flag-af"></span> <a href="" > Afghanistan</a></li>
                  <li><span class="flag flag-al"></span> <a href="" > Albania</a></li>
                  <li><span class="flag flag-dz"></span> <a href="" > Algeria</a></li>
                  <li><span class="flag flag-as"></span> <a href="" > American Samoa</a></li>
                  <li><span class="flag flag-eh"></span> <a href="" > Western Sahara</a></li>
                  <li><span class="flag flag-ye"></span> <a href="" > Yemen</a></li>
                  <li><span class="flag flag-zm"></span> <a href="" > Zambia</a></li>
                  <li><span class="flag flag-zw"></span> <a href="" > Zimbabwe</a></li>
               </ul>
            </div>
         </div>
      </div>
   </div>
</div>

<?php
   $jss = "
    var baseurl=$('#uribase').val();

    $(document).on('click', '.wishlist_btn', function () {
     var self = this;
     
      adId = $(this).attr('attrId');
      var html = [];
      
        $.ajax({
                type: 'POST',
                url: baseurl+'/addwishlist',
                cache: false,
                data: {ad_id: adId},
                success: function (msg) {
                    msg1 = JSON.parse(msg);
                    console.log(msg1);
              
                    var productlist = msg1.data;
                    $('#wishlist_dropdown').html(html);
                    setTimeout(function () {
                        $(self).addClass('wishlist-btn-color');
                        $(self).addClass('wishlist_btn_red');
                    }, 0)
                    $(self).removeClass('wishlist_btn');
                },
                error: function (data) {
                }
            });
        });
                
        $(document).on('click', '.wishlist_btn_red', function () {
            var self = this;
            console.log(self)
			adId = $(this).attr('attrId');
			var html = [];
            
            $.ajax({
                type: 'POST',
                url: baseurl+'/removewishlist',
                cache: false,
                data: {ad_id: adId},
                success: function (msg) {
                    msg1 = JSON.parse(msg);
                    console.log(msg1);
                    setTimeout(function () {
                        $(self).removeClass('wishlist_btn_red');
                        $(self).addClass('wishlist_btn');
                    }, 0)
                },
                error: function (data) {
                }
            });
            return false;
        });
   ";
   
   echo $this->registerJs($jss, View::POS_END);
   ?>
