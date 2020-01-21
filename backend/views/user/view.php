<?php

use yii\helpers\Html;
use common\components\AppHelper;

/* @var $this yii\web\View */
/* @var $model common\models\User */

$this->title = $model->getPublicIdentity();
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend', 'Users'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;


?>

<div class="app-contacts">
    <header class="slidePanel-header overlay" style="background-image: url(assets/images/user-bg.jpg)">
        <div class="overlay-panel overlay-background vertical-align">
            <div class="vertical-align-middle">
                <a class="avatar" href="#" id="emp_img_uploader">
                    <?php if(!empty($profile->avatar_path)){?>
                        <img src="<?php echo $profile->avatar_base_url.'/'.$profile->avatar_path?>"
                             alt="<?php echo $profile->name ?>" style="min-height: 100px; min-width: 100px">
                    <?php } else {?>
                        <img src="<?php echo \yii\helpers\Url::to('@frontendUrl') ?>/images/user-img.png" alt="<?php echo $profile->name ?>" style="min-height: 100px; min-width: 100px">
                    <?php } ?>
                </a>

                <h3 class="name"><?php echo $profile->name;?></h3>
            </div>
        </div>
    </header>
    <div class="slidePanel-actions">
        <div class="btn-group-flat">
            <button type="button" class="btn btn-pure btn-inverse slidePanel-close icon ion-android-close font-size-20"
                    aria-hidden="true"></button>
        </div>
    </div>
    <div class="slidePanel-inner">
        <div class="user-btm-box">
            <!-- .row -->
            <div class="row text-center m-t-10">
                <div class="col-md-6 b-r"><strong>Email</strong><p><?php echo $model->email;?></p></div>
                <div class="col-md-6"><strong>Mobile</strong><p><?php echo $model->mobile; ?></p></div>
            </div>
            <!-- /.row -->
            <hr>
            <!-- .row -->
            <div class="row text-center m-t-10">
                <div class="col-md-6 b-r"><strong>Website</strong><p><?php echo $profile->website; ?></p></div>
                <div class="col-md-6"><strong>Address</strong><p><?php echo $profile->address; ?></p></div>
            </div>
            <!-- /.row -->
            <hr>
            <div class="row text-center m-t-10">
                <div class="col-md-12"><strong>Joined</strong>
                    <p class="m-t-30">
                        <?php echo date('Y-m-d H:i:s a',$model->created_at);?>
                    </p>
                </div>

            </div>
            <!-- /.row -->
            <hr>
            <!-- .row -->
            <div class="row text-center m-t-10">
                <div class="col-md-12"><strong>About</strong>
                    <p class="m-t-30">
                        <?php echo $profile->about;?>
                    </p>
                </div>

            </div>

        </div>
    </div>
</div>