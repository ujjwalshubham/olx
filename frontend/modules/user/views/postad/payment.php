<!-- header section start-->
<?php
   use yii\helpers\Html;
   use yii\helpers\Url;
   use common\models\PostAd;
   use common\models\MediaUpload;
   use common\models\Categories;
   use common\models\Cities;
   use common\models\Plans;
   use yii\web\View;
   use common\components\AppHelper;
   use yii\widgets\ActiveForm;
   use common\components\Olx;
 
    $userId = Yii::$app->user->identity['id'];
    $baseurl =\Yii::$app->getUrlManager()->getBaseUrl(); 
   ?>
  
<section id="main" class="category-page mid_container">
   <div class="container">
      <!-- breadcrumb -->
      <div class="breadcrumb-section">
         <ol class="breadcrumb">
            <li><a href=""><i class="fa fa-home"></i> <?php echo Yii::t('frontend', 'Home')?></a></li>
            <li><?php echo Yii::t('frontend', 'Payment Method')?> </li>
            <div class="ml-auto back-result"><a href=""><i class="fa fa-angle-double-left"></i> <?php echo Yii::t('frontend', 'Back to Results')?></a> </div>
         </ol>
      </div>
      <!-- breadcrumb -->
      <div class="row recommended-ads">
         <div class="col-sm-8">
            <div class="payment_leftcol">
               <h2>Payment Method</h2>
               <div class="payment_box">
                  <ul>
                     <li  class="active">
                        <div class="checkbox checkbox-primary">
                           <input name="radio" id="01" value="1"  type="radio" checked class="payment_radio">
                           <label for="01">Paypal</label>
                        </div>
                        <div class="right_paylogo"> <span><img src="<?php echo $baseurl?>/images/paypal.png" alt="img"></span> </div>
                        <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry.</p>
                     </li>
                     <li>
                        <div class="checkbox checkbox-primary">
                           <input name="radio" id="02" value="1"  type="radio" class="payment_radio">
                           <label for="02">Credit / Debit Card</label>
                        </div>
                        <div class="right_paylogo"> <span><img src="<?php echo $baseurl?>/images/master_card.jpg" alt="img"></span> <span><img src="<?php echo $baseurl?>/images/amex.jpg" alt="img"></span> 
                           <span><img src="<?php echo $baseurl?>/images/visa.jpg" alt="img"></span> 
                        </div>
                        <div class="payment-form-field">
                           <div class="form-group clearfix">
                              <div class="row">
                                 <div class="col-sm-12">
                                    <label>Card Number</label>
                                    <input class="form-control" type="text" placeholder="Valid card number">
                                 </div>
                              </div>
                           </div>
                           <div class="form-group clearfix">
                              <div class="row">
                                 <div class="col-sm-8">
                                    <label>Expiration Date</label>
                                    <input class="form-control" type="text" placeholder="MM/YY">
                                 </div>
                                 <div class="col-sm-4">
                                    <label>CV Code</label>
                                    <input class="form-control" type="text" placeholder="CVC">
                                 </div>
                              </div>
                           </div>
                        </div>
                     </li>
                     
                     <li>
                        <div class="checkbox checkbox-primary">
                           <input name="radio" id="03" value="1"  type="radio" class="payment_radio">
                           <label for="03">Bank Deposit (offline Payment)</label>
                        </div>
                        <div class="right_paylogo"> <span><img src="<?php echo $baseurl?>/images/wire.jpg" alt="img"></span>  
                        </div>
                        <div class="payment-form-field">
                           <div class="payment-detail-box">
                              <ul>
                                 <h3>Bank Account details</h3>
                                 <li> <label>A/C Name:</label> BYLANCER TECHNOLOGIES</li>
                                 <li> <label>Account#:</label> 04822320000000</li>
                                 <li> <label>Bank Name:</label> HDFC</li>
                                 <li> <label>Branch:</label> ANANDLOK</li>
                                 <li> <label>Branch Address:</label> 14 ANANDLOK CA-110049</li>
                                 <li> <label>SWIFT:</label> HDFCINBB</li>
                                 <li> <label>IFSC:</label> HDFC0000482</li>
                              </ul>
                              <ul>
                                 <h3> Bank Cheque sending details</h3>
                                 <li> <label>Recipient:</label>  Bylancer</li>
                                 <li> <label>Address:</label></li>
                                 <li>  0001 East 4th Street. </li>
                                 <li>  Santa Ana, CA 95555</li>
                                 <li>  United States </li>
                                 <li> <label>SWIFT:</label> HDFCINBB</li>
                                 <li> <label>IFSC:</label> HDFC0000482</li>
                              </ul>
                           </div>
                           <div class="payment-detail-box gray-bg">
                              <ul>
                                 <h3>Reference</h3>
                                 <li> <label>Membership Plan:</label> Xyz</li>
                                 <li> <label>Username:</label> Demo</li>
                                 <li> Include a note with Reference so that we know which account to credit. </li>
                              </ul>
                           </div>
                           <div class="payment-detail-box">
                              <ul>
                                 <h3>Amount to send</h3>
                                 <li>  $2.00 </li>
                              </ul>
                           </div>
                        </div>
                     </li>
                     <li>
                        <div class="checkbox checkbox-primary">
                           <input name="radio" id="04" value="1"  type="radio" class="payment_radio">
                           <label for="04">2 Checkout</label>
                        </div>
                        <div class="right_paylogo"> <span><img src="<?php echo $baseurl?>/images/checkout.jpg" alt="img"></span>  
                        </div>
                        <div class="payment-form-field">
                           <div class="form-group clearfix">
                              <div class="row">
                                 <div class="col-sm-12">
                                    <label>Card Number</label>
                                    <input class="form-control" type="text" placeholder="Valid card number">
                                 </div>
                              </div>
                           </div>
                           <div class="form-group clearfix">
                              <div class="row">
                                 <div class="col-sm-8">
                                    <label>Expiration Date</label>
                                    <input class="form-control" type="text" placeholder="MM/YY">
                                 </div>
                                 <div class="col-sm-4">
                                    <label>CV Code</label>
                                    <input class="form-control" type="text" placeholder="CVC">
                                 </div>
                              </div>
                           </div>
                           <div class="form-group clearfix">
                              <div class="row">
                                 <div class="col-sm-8">
                                    <label>First Name</label>
                                    <input class="form-control" type="text" placeholder="First Name">
                                 </div>
                                 <div class="col-sm-4">
                                    <label>Last Name</label>
                                    <input class="form-control" type="text" placeholder="Last Name">
                                 </div>
                              </div>
                           </div>
                           <div class="form-group clearfix">
                              <div class="row">
                                 <div class="col-sm-8">
                                    <label>Address</label>
                                    <input class="form-control" type="text" placeholder="Address">
                                 </div>
                                 <div class="col-sm-4">
                                    <label>City</label>
                                    <input class="form-control" type="text" placeholder="City">
                                 </div>
                              </div>
                           </div>
                           <div class="form-group clearfix">
                              <div class="row">
                                 <div class="col-sm-4">
                                    <label>State</label>
                                    <input class="form-control" type="text" placeholder="State">
                                 </div>
                                 <div class="col-sm-4">
                                    <label>Country</label>
                                    <input class="form-control" type="text" placeholder="Country">
                                 </div>
                                 <div class="col-sm-4">
                                    <label>Zip Code</label>
                                    <input class="form-control" type="text" placeholder="Zip Code">
                                 </div>
                              </div>
                           </div>
                        </div>
                     </li>
                     <li>
                        <div class="checkbox checkbox-primary">
                           <input name="radio" id="05" value="1"  type="radio" class="payment_radio">
                           <label for="05">Paystack</label>
                        </div>
                        <div class="right_paylogo"> <span><img src="<?php echo $baseurl?>/images/paystack.jpg" alt="img"></span>  
                        </div>
                     </li>
                     <li>
                        <div class="checkbox checkbox-primary">
                           <input name="radio" id="06" value="1"  type="radio" class="payment_radio">
                           <label for="06">Payumoney</label>
                        </div>
                        <div class="right_paylogo"> <span><img src="<?php echo $baseurl?>/images/paystack.jpg" alt="img"></span>  
                        </div>
                     </li>
                  </ul>
               </div>
               <div class="payment_success success"></div>
               <div class="">
				 <form id="subscribeForm" novalidate="novalidate" method="POST">  
				   <input type="hidden" name="plan_id" id="plan_id" value="">
				   <input type="hidden" name="ad_id" id="ad_id" value="<?php echo $ad_detail['id']?>">
				   <input type="hidden" id="ad_slug" value="<?php echo $ad_detail['slug']?>">
				   <input type="hidden" name="user_id" id="user_id" value="<?php echo $userId?>">
                  <button type="button" class="btn bt_blue" name="submit subscribeNow" id="subscribeNow"><?php echo Yii::t('frontend', 'Confirm And Pay')?></button>
                  </form>
               </div>
            </div>
         </div>
         
         <?php //echo "<pre>"; print_r($packages);exit;?>
         <div class="col-sm-4">
            <div class="payment_rightcol">
               <h3><i class="fa fa-calendar-check-o" aria-hidden="true"></i> &nbsp; <?php echo Yii::t('frontend', 'Package Summery')?> </h3>
               <ul>
				  <li><label><?php echo Yii::t('frontend', 'Title')?></label> <?php echo $ad_detail['title']?></li>
				  <li><label><?php echo Yii::t('frontend', 'Order')?></label> <?php echo Yii::t('frontend', 'Package')?> <?php echo ucfirst($ad_detail['ad_type'])?></li>
				  <?php if($ad_detail['ad_type']=='urgent'){?>
				  <li><label><?php echo Yii::t('frontend', 'Total Cost')?></label> ₹<span class="amount"><?php echo $packages['urgent_project_fee']?></span></li>
				  <?php }else if($ad_detail['ad_type']=='featured'){?>
				  <li><label><?php echo Yii::t('frontend', 'Total Cost')?></label> ₹<span class="amount"><?php echo $packages['featured_project_fee']?></span></li>  
				  <?php }else if($ad_detail['ad_type']=='highlight'){?>
				  <li><label><?php echo Yii::t('frontend', 'Total Cost')?></label> ₹<span class="amount"><?php echo $packages['highlight_project_fee']?></span></li>    
				  <?php } ?>
			 </ul>
            </div>
         </div>
      </div>
   </div>
</section>

 <?php if (Yii::$app->session->hasFlash('success')){ ?>
		 <div class="modal fade" id="myModal" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header header-modal">
                        <button type="button" class="close" data-dismiss="modal" aria-label=""><span>×</span></button>
                     </div>
					
                    <div class="modal-body">
                       
						<div class="thank-you-pop">
							<img src="http://goactionstations.co.uk/wp-content/uploads/2017/03/Green-Round-Tick.png" alt="">
							<h1><?php echo Yii::t('frontend', 'Success!')?></h1>
							<p><?php echo Yii::t('frontend', 'Your advertise successfully uploaded.Please wait for approval.')?></p>
							<p><?php echo Yii::t('frontend', 'Thanks')?></p>
 						</div>
                    </div>
                </div>
            </div>
        </div>
<?php } ?>


<?php
   $jss = "
    $('#subscribeNow').click(function(e) { 
    var user_id = $('#user_id').val();
    var amount = $('.amount').text();
    var ad_id = $('#ad_id').val();
    var ad_slug = $('#ad_slug').val();
	
    var data = {user_id: user_id, amount: amount, ad_id: ad_id};
    var ajaxurl= $('#uribase').val();
        $.ajax({
                url:  ajaxurl+'/user/advertise_plan',
                data: data,
                type: 'POST',
                dataType: 'json',
                success: function (data) {
                
                $('.payment_success').html('Payment is Successfull');
					setTimeout(function(){
						window.location.replace(ajaxurl+'/user/ad-detail/'+ad_slug);
					}, 3000);
         
                }
            });
   });
   
    $(document).ready(function(){
	   $('#myModal').modal('show'); 
	   setTimeout(function(){
		$('#myModal').modal('hide')
	}, 4000);
	});
   
   
    $('li').click( function() {
		$(this).addClass('active').siblings().removeClass('active');
		$(this).find('.payment_radio').prop('checked', true);
	  });
   ";
   echo $this->registerJs($jss, View::POS_END);
?>
