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
   if (Yii::$app->User->id) {
    
   }
    $userId = Yii::$app->user->identity['id'];
    $baseurl =\Yii::$app->getUrlManager()->getBaseUrl(); 
    $categories = Olx::getAllCategory();
    $pan_id = $post['plan_id'];
    $plan_detail = Plans::findOne($pan_id);
    //echo "<pre>"; print_r($plan_detail);exit;
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
               <div class="">
				   
				 <form id="subscribeForm" novalidate="novalidate" method="POST">  
				   <input type="hidden" name="plan_id" id="plan_id" value="<?php echo $plan_detail->id?>">
				   <input type="hidden" name="user_id" id="user_id" value="<?php echo $userId?>">
                  <button type="button" class="btn bt_blue" name="submit subscribeNow" id="subscribeNow"><?php echo Yii::t('frontend', 'Confirm And Pay')?></button>
                  </form>
                  
               </div>
            </div>
         </div>
         <div class="col-sm-4">
            <div class="payment_rightcol">
               <h3><i class="fa fa-calendar-check-o" aria-hidden="true"></i> &nbsp; <?php echo Yii::t('frontend', 'Package Summery')?> </h3>
               <ul>
                  <li><label><?php echo Yii::t('frontend', 'Membership')?></label> <?php echo $plan_detail->name?></li>
                  <li><label><?php echo Yii::t('frontend', 'Start Date')?></label> <?php echo date('d-m-Y',time());?></li>
                  <?php if($plan_detail->plan_term=='WEEKLY'){?>
				  <li><label><?php echo Yii::t('frontend', 'Expiry date')?></label> <?php echo date('d-m-Y', strtotime("+7 days"));?></li>
                  <?php }elseif($plan_detail->plan_term=='MONTHLY'){?>
				  <li><label><?php echo Yii::t('frontend', 'Expiry date')?></label> <?php echo date('d-m-Y', strtotime("+30 days"));?></li> 
				  <?php }elseif($plan_detail->plan_term=='YEARLY'){?>
				  <li><label><?php echo Yii::t('frontend', 'Expiry date')?></label> <?php echo date('d-m-Y', strtotime("+365 days"));?></li>
				  <?php } ?>
                  <li><label><?php echo Yii::t('frontend', 'Total Cost')?></label> ₹<?php echo $plan_detail->amount?></li>
               </ul>
            </div>
         </div>
      </div>
   </div>
</section>

<?php
   $jss = "
    $('#subscribeNow').click(function(e) { 
    var plan_id = $('#plan_id').val();
    var user_id = $('#user_id').val();

    var data = {plan_id: plan_id, user_id: user_id};
    var ajaxurl= $('#uribase').val();
        $.ajax({
                url:  ajaxurl+'/user/upgrade-membership',
                data: data,
                type: 'POST',
                dataType: 'json',
                success: function (data) {
					window.location.replace(ajaxurl+'/user/membership');
                }
            });
   });
   
   
    $('li').click( function() {
		$(this).addClass('active').siblings().removeClass('active');
		$(this).find('.payment_radio').prop('checked', true);
	  });
   ";
   echo $this->registerJs($jss, View::POS_END);
?>
