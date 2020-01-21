<?php

use yii\helpers\Html;
use common\components\AppHelper;
use yii\web\View;
use common\models\WarningReasons;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use common\models\PostAd;

/* @var $this yii\web\View */
/* @var $model common\models\PostAd */

$this->title = $postAd['title'];
$this->params['breadcrumbs'][] = ['label' => 'Post Ads', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
$baseurl =\yii\helpers\Url::to('@frontendUrl');
$warnReson = WarningReasons::getReson();
$warnmessage_ajax=Url::to(['/post-ad/warnmessage']);

$this->registerJs("   
    
    $(document).ready(function () {
        $('.warn_reasons').change();
    });

    $(document).on('change', '.warn_reasons', function () {
        var value = this.value;
        var url = '$warnmessage_ajax';
        $.ajax({
            url: url,
            type: 'POST',
            data: {\"value\": value},
            success: function (data) {
                var optiondata = jQuery.parseJSON(data);
                $('#dependent_warn_msg').empty();
                $.each(optiondata, function (key, value) {
                    $('#dependent_warn_msg')
                            .append($(\"<option></option>\")
                                    .attr(\"value\", key)
                                    .text(value));
                });
            },
            error: function () {

            }
        });
    });
    
    $('.approve_review').click(function(){
      var check = confirm('Are you sure?');
      if (check == true) {
        var id =this.id;        
        $.ajax({
          type:'POST',
          dataType:'json',
          url:'markapprove',
          data:{'id':id},
          success:function(data){             
              
          }
        });
      }
      else{
        return false;
      }
    });
    
    $('.requireM').on('click', function (ev) {
        ev.preventDefault();
        $(\"#requireM\").modal(\"show\");
    });
    
     $('.denied').on('click', function (ev) {
        ev.preventDefault();
        if (confirm(\"Are you sure you want to denied this project?\")) {
            $(\"#denied\").modal(\"show\");
        }
    });
",View::POS_END);
?>
<div class="post-ad-view">

    <div class="card">
        <div class="card-header">
            <h4><?php echo $postAd['title']; ?>
                <span class="label-wrap hidden-sm hidden-xs"></span>
            </h4>

            <?php
            if($postAd['status'] == PostAd::STATUS_PENDING){
                $class="post_pending";
                $btn_class="btn-default";
                $label_ad='Pending';
            }
            elseif($postAd['status'] == PostAd::STATUS_WARNING){
                $class="post_warning";
                $btn_class="btn-warning";
                $label_ad='Modification Req.';
            }
            elseif($postAd['status'] == PostAd::STATUS_EXPIRE){
                $class="post_expire";
                $btn_class="btn-danger";
                $label_ad='Expired';
            }
            elseif($postAd['status'] == PostAd::STATUS_REJECTED){
                $class="post_rejected";
                $btn_class="btn-primary";
                $label_ad='Rejected';
            }
            elseif($postAd['status'] == PostAd::STATUS_RESUBMITTED){
                $class="post_resubmitted";
                $btn_class="btn-info";
                $label_ad='Resubmitted';
            }
            elseif($postAd['status'] == PostAd::STATUS_HIDDEN){
                $class="post_hidden";
                $btn_class="btn-primary";
                $label_ad='Hidden';
            }
            else{
                $class="post_active";
                $btn_class="btn-success";
                $label_ad='Active';
            }
            ?>
            <button type="button" class="postad_status btn btn-block <?php echo $btn_class; ?> <?php echo $class; ?>"><?php echo $label_ad ?></button>

        </div>
        <div class="card-block">

            <section class="content">
                <div class="row">
                    <div class="col-sm-8" style="border: 0px solid #000;">
                        <div class="item-box">
                            <?php if(count($ads_images) == 1){ ?>
                                <div id="" class="carousel slide">
                                    <?php
                                    $image = \common\models\MediaUpload::getImageByMediaId($ads_images[0]['media_id']);
                                    ?>
                                    <div class="carousel-inner" role="listbox">
                                        <div class="item active">
                                            <div class="carousel-image">
                                                <img src="<?php echo $image['url'].'/'. $image['upload_base_path'].'/'.$image['file_name']?>"
                                                     alt="<?php echo $image['name']; ?>"
                                                     class="img-responsive">
                                            </div>
                                        </div>
                                    </div><!-- carousel-inner -->

                                </div>
                            <?php } else { ?>
                                <div id="product-carousel" class="carousel slide" data-ride="carousel">
                                    <!-- Indicators -->
                                    <ol class="carousel-indicators">

                                        <?php
                                        $c=0;
                                        foreach($ads_images as $key=>$images){
                                            $image = \common\models\MediaUpload::getImageByMediaId($images['media_id']);
                                            if($c == 0){$activeclass='active';}
                                            else{$activeclass='';} ?>
                                            <li data-target="#product-carousel" data-slide-to="<?php echo $c; ?>"
                                                class="<?php echo $activeclass; ?>">
                                                <img src="<?php echo $image['url'].'/'. $image['upload_base_path'].'/'.$image['file_name']?>"
                                                     alt="<?php echo $image['name']; ?>" class="img-responsive">
                                            </li>
                                            <?php $c++;
                                        }?>
                                    </ol>
                                    <!-- Wrapper for slides -->
                                    <?php if(isset($postAd['price']) && !empty($postAd['price'])){?>
                                        <div class="ribbon ribbon-clip ribbon-reverse">
                                            <span class="ribbon-inner"><?php echo $postAd['price']; ?> ₹</span>
                                        </div>
                                    <?php } ?>
                                    <div class="carousel-inner" role="listbox">
                                        <?php
                                        $c=0;
                                        foreach($ads_images as $key=>$images){
                                            $image = \common\models\MediaUpload::getImageByMediaId($images['media_id']);
                                            if($c == 0){
                                                $activeclass='active';
                                            }
                                            else{
                                                $activeclass='';
                                            } ?>
                                            <div class="item <?php echo $activeclass; ?>">
                                                <div class="carousel-image">
                                                    <img src="<?php echo $image['url'].'/'. $image['upload_base_path'].'/'.$image['file_name']?>"
                                                         alt="<?php echo $image['name']; ?>"
                                                         class="img-responsive">
                                                </div>
                                            </div>
                                        <?php $c++; }?>
                                    </div><!-- carousel-inner -->
                                    <!-- Controls -->
                                    <a class="left carousel-control" href="#product-carousel" role="button" data-slide="prev">
                                        <i class="fa fa-chevron-left"></i>
                                    </a>
                                    <a class="right carousel-control" href="#product-carousel" role="button" data-slide="next">
                                        <i class="fa fa-chevron-right"></i>
                                    </a><!-- Controls -->
                                </div>
                            <?php } ?>
                        </div>
                        <div class="item-box" style="margin-top: 20px">
                            <div class="tab-content">
                                <div id="4" class="tab-pane active fade in">
                                    <div>

                                        <div class="quick-info">
                                            <div class="detail-title">
                                                <h2 class="title-left">Additional Details</h2>
                                            </div>
                                            <ul class="clearfix">
                                                <li>
                                                    <div class="inner clearfix">
                                                        <span class="label"><?php echo Yii::t('frontend', 'Ad ID')?></span>
                                                        <span class="desc"><?php echo $postAd['id']?></span>
                                                    </div>
                                                </li>
                                                <li>
                                                    <div class="inner clearfix">
                                                        <span class="label"><?php echo Yii::t('frontend', 'Posted On')?></span>
                                                        <span class="desc">
                                                            <?php
                                                            $post_time =  AppHelper::getPostTime($postAd['created_at']);
                                                            echo $post_time; ?>
                                                        </span>
                                                    </div>
                                                </li>

                                                <?php if(isset($postAd['tags']) && !empty($postAd['tags'])){?>
                                                    <li>
                                                        <div class="inner clearfix">
                                                            <span class="label"><?php echo Yii::t('frontend', 'tags')?></span>
                                                            <span class="desc"><?php echo $postAd['tags'];?></span>
                                                        </div>
                                                    </li>
                                                <?php }?>


                                                <?php if(!empty($postAd['mobile']) && $postAd['mobile_hidden']==0){?>
                                                    <li>
                                                        <div class="inner clearfix">
                                                            <span class="label"><?php echo Yii::t('frontend', 'Mobile')?></span>
                                                            <span class="desc"><?php echo $postAd['mobile'];?></span>
                                                        </div>
                                                    </li>
                                                <?php }?>


                                                <li>
                                                    <div class="inner clearfix">
                                                        <span class="label"><?php echo Yii::t('frontend', 'Ad Views')?></span>
                                                        <span class="desc"><?php echo $ad_views_count?></span>
                                                    </div>
                                                </li>
                                                <?php if(isset($postAd['price']) && !empty($postAd['price'])){?>
                                                    <li>
                                                        <div class="inner clearfix">
                                                            <span class="label"><?php echo Yii::t('frontend', 'Price')?></span>
                                                            <span class="desc"><?php echo $postAd['price']?> ₹
                                                                <?php if($postAd['negotiate']==1){?>negotiate<?php } ?>
                                                            </span>
                                                        </div>
                                                    </li>
                                                <?php }?>

                                                <?php //echo "<pre>"; print_r($adCustomFields);exit;?>
                                                <?php foreach($adCustomFields as $key=>$fields){?>
                                                    <?php if($fields['custom_fields']['type']=='checkbox'){
                                                        $array_values = explode(',',$fields['value']) ;
                                                        ?>
                                                        <li>
                                                            <div class="inner clearfix"><span class="label"><?php echo $fields['custom_fields']['label']?></span><span class="desc">
								<?php foreach($array_values as $key=>$val){
                                    $custom_field_value = Apphelper::getCustomFieldValue($val);
                                    echo $custom_field_value.',' ;
                                    ?>
                                <?php } ?></span></div>
                                                        </li>
                                                    <?php }else if($fields['custom_fields']['type']=='radio' || $fields['custom_fields']['type']=='select'){
                                                        $custom_field_value = Apphelper::getCustomFieldValue($fields['value']);
                                                        ?>
                                                        <li>
                                                            <div class="inner clearfix"><span class="label"><?php echo $fields['custom_fields']['label']?></span><span class="desc"><?php echo $custom_field_value?></span></div>
                                                        </li>
                                                    <?php } else {?>
                                                        <li>
                                                            <div class="inner clearfix"><span class="label"><?php echo $fields['custom_fields']['label']?></span><span class="desc"><?php echo $fields['value']?></span></div>
                                                        </li>
                                                    <?php } } ?>

                                                <li>
                                                    <div class="inner clearfix">
                                                        <span class="label"><?php echo Yii::t('frontend', 'Ad Type')?></span>
                                                        <span class="desc">
                                                            <?php
                                                            if($postAd['ad_type'] == 'free'){
                                                                echo 'Free';
                                                            }
                                                            else{
                                                                echo 'Premium ('.$postAd['ad_type'].')';
                                                            }
                                                            ?>
                                                        </span>
                                                    </div>
                                                </li>
                                            </ul>
                                        </div>
                                        <div class="description">
                                            <div class="detail-title">
                                                <h2 class="title-left">Description</h2>
                                            </div>
                                            <div class="user-html">
                                                <p><?php echo $postAd['description']; ?></p>
                                            </div>

                                        </div>

                                    </div>
                                </div>
                                <!--<div id="5" class="tab-pane fade">
                                    <p>0</p>
                                </div>
                                <div id="6" class="tab-pane fade">
                                    <p></p>
                                </div>-->
                            </div>

                        </div>
                    </div>
                    <!-- end col -->
                    <div class="col-sm-4" style="border: 0px solid #000;">
                        <?php if($postAd['status'] == \common\models\PostAd::STATUS_PENDING || $postAd['status'] == PostAd::STATUS_RESUBMITTED){ ?>
                            <div class="pad-bot-20" id="js-delete-single">
                                <div class="item-box fbold">
                                    <div class="button_section_top pad-20">
                                        <?php
                                            echo Html::a('Approve',
                                            'javascript:void(0);', ['id' => $postAd['id'],'class'=>'btn btn-success approve_review','title'=>'Approve Ad']);
                                        ?>
                                        <?php
                                            echo Html::a('Rejected',
                                            ['delete', 'id' => $postAd['id']], ['class'=>'btn btn-danger denied',
                                                'data-method'=> 'post',
                                                'data-confirm' => 'Are you sure you want to reject this item?',
                                            ]);
                                        ?>
                                        <?php
                                            echo Html::a('Modification Req.',
                                            'javascript:void(0);', ['id' => $postAd['id'],'class'=>'btn btn-primary requireM','title'=>'Post Modification Required']);
                                        ?>
                                    </div>
                                </div>
                            </div>
                        <?php } else{ ?>

                        <?php }?>

                        <div class="pad-top-20 pad-bot-20">
                            <div class="item-box">
                                <div class="pad-20">
                                    <div class="row">
                                        <div class="col-sm-3">
                                            <div class="profile-picture medium-profile-picture mpp XxGreen">
                                                <?php if(!empty($advertiser_detail['avatar_path'])){?>
                                                    <a href="<?php echo \yii\helpers\Url::to('@frontendUrl').'/user/my-profile'; ?>">
                                                        <img  width="70px" style="min-height:70px" src="<?php echo \yii\helpers\Url::to('@frontendUrl').'/'.$advertiser_detail['avatar_path'] ?>" alt="Demo">
                                                    </a>
                                                <?php } else {?>
                                                    <a href="<?php echo \yii\helpers\Url::to('@frontendUrl').'/user/my-profile'; ?>">
                                                        <img  width="70px" style="min-height:70px" src="<?php echo \yii\helpers\Url::to('@frontendUrl'); ?>/images/user-img.png" alt="Demo">
                                                    </a>
                                                <?php } ?>
                                                </div>
                                        </div>
                                        <div class="col-sm-9">
                                            <div>
                                                <div class="align-left fbold">
                                                    <a href="javascript:void(0)" style="text-decoration:none" target="_blank">
                                                        <?php echo $advertiser_detail['name']; ?>
                                                    </a>
                                                </div>
                                                <div class="align-left font13 pad-3"> <span class="flags flag-br"></span></div>
                                                <div class="align-left"><a href="<?php echo $baseurl.'/user/profile/'.$advertiser_detail['slug']; ?>" class="bylabel bylabelLarge" target="_blank">View Profile</a></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="item-box meta-attributes">
                            <div class="pad-20">
                                <table class="meta-attributes__table align-left" cellspacing="0" cellpadding="10" border="0">
                                    <tbody>

                                    <tr>
                                        <td class="meta-attributes__attr-name">Ad ID</td>
                                        <td class="meta-attributes__attr-detail"><?php echo $postAd['id'] ?></td>
                                    </tr>
                                    <tr>
                                        <td class="meta-attributes__attr-name">Views</td>
                                        <td class="meta-attributes__attr-detail"><?php echo $postAd['viewcount'] ?></td>
                                    </tr>
                                    <tr>
                                        <td class="meta-attributes__attr-name">Status</td>
                                        <td class="meta-attributes__attr-detail">
                                            <span class="label label-success"><?php echo $postAd['status'] ?></span></td>
                                    </tr>
                                    <tr>
                                        <td class="meta-attributes__attr-name">Posted</td>
                                        <td class="meta-attributes__attr-detail">
                                            <time itemprop="dateCreated">
                                                <?php echo \common\components\AppHelper::getPostTime($postAd['created_at']); ?>                                           </time>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td class="meta-attributes__attr-name">Updated On</td>
                                        <td class="meta-attributes__attr-detail">
                                            <time itemprop="dateCreated">
                                                <?php echo \common\components\AppHelper::getPostTime($postAd['updated_at']); ?>                                           </time>
                                        </td>
                                    </tr>
                                   <!-- <tr>
                                        <td class="meta-attributes__attr-name">Expire On</td>
                                        <td class="meta-attributes__attr-detail">
                                            <time itemprop="dateCreated" datetime="11-Dec-19">
                                                11-Dec-19                                                </time>
                                        </td>
                                    </tr>-->

                                    <tr>
                                        <td class="meta-attributes__attr-name">Category</td>
                                        <td class="meta-attributes__attr-detail">
                                            <?php echo $postAd['category']; ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="meta-attributes__attr-name">SubCategory</td>
                                        <td class="meta-attributes__attr-detail">
                                            <?php echo $postAd['subcategory']; ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="meta-attributes__attr-name">Location</td>
                                        <td class="meta-attributes__attr-detail"><?php echo $postAd['address']; ?></td>
                                    </tr>
                                    <tr>
                                        <td class="meta-attributes__attr-name">City</td>
                                        <td class="meta-attributes__attr-detail"><?php echo $postAd['city_name']; ?></td>
                                    </tr>
                                    <tr>
                                        <td class="meta-attributes__attr-name">State</td>
                                        <td class="meta-attributes__attr-detail"><?php echo $postAd['state_name']; ?></td>
                                    </tr>
                                    <tr>
                                        <td class="meta-attributes__attr-name">Country</td>
                                        <td class="meta-attributes__attr-detail"><?php echo $postAd['country_name']; ?></td>
                                    </tr>
                                    <tr>
                                        <td class="meta-attributes__attr-name">Phone</td>
                                        <td class="meta-attributes__attr-detail"><?php echo $postAd['mobile']; ?></td>
                                    </tr>
                                    <tr>
                                        <td class="meta-attributes__attr-name">Hide Phone</td>
                                        <td class="meta-attributes__attr-detail">
                                            <?php if($postAd['mobile_hidden'] == 1){
                                                echo 'True';
                                            }
                                            else{
                                                echo 'False';
                                            }?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="meta-attributes__attr-name">Price Negotiated</td>
                                        <td class="meta-attributes__attr-detail">
                                            <?php if($postAd['negotiate'] == 1){
                                                echo 'True';
                                            }
                                            else{
                                                echo 'False';
                                            }?>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td class="meta-attributes__attr-name">Tags</td>
                                        <td>
                                                    <span class="meta-attributes__attr-tags">
                                                        <?php echo $postAd['tags']; ?>
                                                    </span>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                    </div><!-- end col -->
                </div><!-- end row -->
            </section>

        </div>
        <!-- .card-block -->
    </div>

</div>

<div id="requireM" class="modal fade" role="dialog">
    <div class="modal-dialog modal-ku">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"> <i aria-hidden="true" class="fa fa-times"></i></button>
                <h4><?php echo Yii::t('frontend', 'Reason for Modification') ?></h4>
            </div>
            <div class="modal-body clearfix">
                <div class="col-sm-12">
                    <?php $form = ActiveForm::begin(['id' => 'form-message', 'action' => ['/post-ad/request'], 'enableAjaxValidation' => true,
                        'options' => ['role' => 'form', 'enctype' => 'multipart/form-data'], 'fieldConfig' => ['options' => ['tag' => 'span']]]); ?>
                    <input type="hidden" name="reasontype" value="modification">
                    <input type="hidden" name="postid" value="<?php echo $postAd['id']; ?>">

                    <div class="empty-submit">

                        <select class="warn_reasons form-control" name="warn_reason">
                            <option value="">Please Select</option>
                            <?php foreach ($warnReson as $reason) { ?>
                                <option value="<?php echo $reason['type']; ?>">
                                    <?php echo $reason['type']; ?>
                                </option>
                            <?php } ?>
                        </select>

                        <select id="dependent_warn_msg" name="warn_reason_next" class="warn_reason_next form-control">

                        </select>

                        <textarea class="form-control" name="reasonmodification" cols="5" rows="8" style="resize: none; margin-bottom: 20px;"></textarea>
                        <?php echo Html::submitButton(Yii::t('frontend', 'Submit'), ['class' => 'btn btn-primary submitmodification']) ?>
                    </div>
                    <?php ActiveForm::end(); ?>
                </div>
            </div>
        </div>
    </div>
</div>

