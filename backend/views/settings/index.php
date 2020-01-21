<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use backend\models\GeneralSetting;

/* @var $this yii\web\View */
/* @var $model common\models\Settings */

$this->title = 'Settings';
$this->params['breadcrumbs'][] = ['label' => 'Settings', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

?>

<div class="card">
    <div class="card-header">
        <h4>Site setting</h4>
    </div>
    <div class="card-block">
        <!-- /row -->
        <div class="row">
            <div class="col-sm-12">


                <div id="quickad-tbs" class="wrap">
                    <div class="quickad-tbs-body">

                        <div class="row">
                            <div id="quickad-sidebar" class="col-sm-4">
                                <?php echo $this->render('_menu_sidebar',['tabview'=>$tabview]); ?>
                            </div>

                            <div id="quickad_settings_controls" class="col-sm-8">
                                <div class="panel panel-default quickad-main">
                                    <div class="panel-body">
                                        <div class="tab-content">

                                            <div class="tab-pane active" id="quickad_settings_general">
                                                <div class="settings-create">
                                                    <div id="detail_form_data" class="settings-form">

                                                        <?php

                                                        if($tabview == 'ad_post'){
                                                            echo $this->render('_ad_post',['model' => $model]);
                                                        }
                                                        elseif($tabview == 'logo_watermark'){
                                                            echo $this->render('_logo_watermark',['model' => $model]);
                                                        }
                                                        else{
                                                            echo $this->render('_general',['model' => $model]);
                                                        }
                                                        ?>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

        </div>
        <!-- /.row -->
    </div>
    <!-- .card-block -->
</div>

