<?php


use backend\themes\classified\assets\ThemeAsset;
use yii\helpers\Html;

$bundle=ThemeAsset::register($this);


?>
<?php $this->beginPage(); ?>

<html class="app-ui">

<head>

    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Quickad - Admin Dashboard" />
    <meta name="author" content="Bylancer" />
    <meta name="robots" content="noindex, nofollow" />
    <?php $this->registerCsrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <!-- Favicons -->
    <link rel="icon" type="image/png" sizes="16x16" href="../storage/logo/favicon.png">
    <?php $this->head() ?>


</head>

<body class="app-ui layout-has-drawer layout-has-fixed-header">

<?php $this->beginBody() ?>

<div class="app-layout-canvas">
    <div class="app-layout-container">

        <!-- Drawer -->
            <?php echo $this->render('@app/themes/classified/views/layouts/menu_drawer') ?>
        <!-- End drawer -->

        <!-- Header -->
            <?php echo $this->render('@app/themes/classified/views/layouts/header',['bundle'=>$bundle]) ?>
        <!-- End header -->


        <main class="app-layout-content">
            <!-- Page Content -->
            <div class="container-fluid p-y-md">
                <?= $content ?>
            </div>
            <!-- .container-fluid -->
            <!-- End Page Content -->
        </main>

    </div>
    <!-- .app-layout-container -->
</div>
<!-- .app-layout-canvas -->

<!-- Apps Modal -->
<!-- Opens from the button in the header -->
<div id="apps-modal" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-sm modal-dialog modal-dialog-top">
        <div class="modal-content">
            <!-- Apps card -->
            <div class="card m-b-0">
                <div class="card-header bg-app bg-inverse">
                    <h4>Apps</h4>
                    <ul class="card-actions">
                        <li>
                            <button data-dismiss="modal" type="button"><i class="ion-close"></i></button>
                        </li>
                    </ul>
                </div>
                <div class="card-block">
                    <div class="row text-center">
                        <div class="col-xs-6">
                            <a class="card card-block m-b-0 bg-app-secondary bg-inverse" href="index.html">
                                <i class="ion-speedometer fa-4x"></i>
                                <p>Admin</p>
                            </a>
                        </div>
                        <div class="col-xs-6">
                            <a class="card card-block m-b-0 bg-app-tertiary bg-inverse" target="_blank" href="../home">
                                <i class="ion-laptop fa-4x"></i>
                                <p>Frontend</p>
                            </a>
                        </div>
                    </div>
                </div>
                <!-- .card-block -->
            </div>
            <!-- End Apps card -->
        </div>
    </div>
</div>
<!-- End Apps Modal -->

<div class="app-ui-mask-modal"></div>








    <input type="hidden" name="baseurl" id="ajaxbase_url" value="<?php echo \Yii::$app->request->BaseUrl; ?>"/>
    <?php $this->endBody(); ?>
    </body>
</html>
<?php $this->endPage(); ?>
