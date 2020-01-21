<?php

echo 'gfggg';die;
use yii\helpers\Html;

BaseAsset::register($this);
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

<body class="app-ui">

<?php $this->beginBody() ?>
<div class="col-sm-12 col-md-12 loader" style="display: none"></div>
<div class="app-layout-canvas">
    <div class="app-layout-container">


        <main class="app-layout-content">
            <!-- Page Content -->

                <?= $content ?>

            <!-- .container-fluid -->
            <!-- End Page Content -->
        </main>

    </div>
    <!-- .app-layout-container -->
</div>
<!-- .app-layout-canvas -->


<?php $this->endBody(); ?>
</body>
</html>
<?php $this->endPage(); ?>
