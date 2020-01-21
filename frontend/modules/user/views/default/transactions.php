<?php
   use common\components\SidenavWidget; 
   use common\components\ProfileheaderWidget; 
   use common\components\AppHelper;
   use common\models\PostAd;
   use common\models\Plans;
   use yii\widgets\LinkPager;
   use yii\helpers\Html;
   
   $this->title = Yii::t('frontend', 'My Ads') . ' | OLX';
   $baseurl =\Yii::$app->getUrlManager()->getBaseUrl(); 
   ?>
<section id="main" class="category-page mid_container">
   <div class="container">
      <!-- breadcrumb -->
      <div class="breadcrumb-section">
         <ol class="breadcrumb">
            <li><a href="<?php echo $baseurl; ?>"><i class="fa fa-home"></i> <?php echo Yii::t('frontend', 'Home')?></a></li>
            <li>Transaction </li>
            <div class="ml-auto back-result"><a href=""><i class="fa fa-angle-double-left"></i> <?php echo Yii::t('frontend', 'Back to Results')?></a> </div>
         </ol>
         <h2 class="title"><?php echo Yii::t('frontend', 'Transaction')?></h2>
      </div>
      <!-- breadcrumb -->
      <div class="row recommended-ads">
         <div class="col-sm-12">
            <div class="section transaction_table">
               <div class="tran_filter">
                  <div id="myTable_filter" class="dataTables_filter"><label>Search<input type="search" ></label></div>
               </div>
               <table  class="table table-striped">
                  <thead>
                     <tr>
                        <th><?php echo Yii::t('frontend', 'Title for your advertise')?></th>
                        <th><?php echo Yii::t('frontend', 'Amount')?></th>
                        <th><?php echo Yii::t('frontend', 'Premium')?></th>
                        <th><?php echo Yii::t('frontend', 'Payment Method')?></th>
                        <th><?php echo Yii::t('frontend', 'Date')?></th>
                        <th><?php echo Yii::t('frontend', 'Status')?></th>
                     </tr>
                  </thead>
                  
                  <?php if(isset($transactions) && !empty($transactions)){?>
                  <tbody>
					 <?php foreach($transactions as $key=>$transaction){?>
                     <tr>
						<?php if(isset($transaction->ad_id) && !empty($transaction->ad_id)){
						$ad_detail = PostAd::FindOne($transaction->ad_id);?> 
                        <td>
                        
                        <?php echo Html::a($ad_detail->title, ['/user/ad-detail/'.$ad_detail->slug], ['class' => '']); ?>
                        
                        </td>
                        <?php } ?>
                        
                        <?php if(isset($transaction->plan_id) && !empty($transaction->plan_id)){
						$plan_detail = Plans::FindOne($transaction->plan_id);
						//echo "<pre>"; print_r($plan_detail);
						?> 
                        <td><?php echo $plan_detail->name?> Membership Plan</td>
                        <?php } ?>
                        
                        <td><?php echo number_format($transaction->txn_amount,2) ?></td>
                        <?php if(isset($transaction->ad_id) && !empty($transaction->ad_id)){
						$ad_detail = PostAd::FindOne($transaction->ad_id);	
						?>
					   <?php if($ad_detail->ad_type =='featured'){?>
					   <td><span class="featured_bg ad_label"><?php echo ucfirst($ad_detail->ad_type) ?></span></td>
					   <?php } else if($ad_detail->ad_type=='highlight'){?>
					   <td><span class="hightlight_bg ad_label"><?php echo ucfirst($ad_detail->ad_type) ?></span></td>
					   <?php } else if($ad_detail->ad_type=='urgent'){?>
					   <td><span class="urgent_bg ad_label"><?php echo ucfirst($ad_detail->ad_type) ?></span></td>
					   <?php } ?>
                        
                        <?php } else{?>
						<td><span class="membership_bg ad_label"><?php echo ucfirst($transaction->type) ?></span></td>
						<?php } ?>
                        <td><?php echo ucfirst($transaction->payment_type) ?></td>
                        
                        <?php $txn_date = strtotime($transaction->txn_date);?>
                        <td><?php echo date('d M Y',$txn_date)?></td>
                        <td><?php echo $transaction->status?></td>
                     </tr>
                     <?php } ?>
                  </tbody>
                  <?php }else {?>
				  <tbody>
					<tr>
					<td colspan="6"><p class="text-center"><?php echo Yii::t('frontend', 'No Transaction Found')?></p></td>
					</tr>
				  
				  </tbody>  
					  
				  <?php } ?>
               </table>
               <?php echo LinkPager::widget(['pagination' => $pages]);?>
            </div>
         </div>
      </div>
   </div>
</section>
