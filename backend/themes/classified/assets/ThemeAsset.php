<?php

namespace backend\themes\classified\assets;

use yii\web\AssetBundle;

/**
 * Main backend application asset bundle.
 */
class ThemeAsset extends AssetBundle
{
   // public $basePath = '@app';
    public $sourcePath = '@app/themes/classified/web';

    public $publishOptions = [
        'forceCopy' => true,
    ];
    public $jsOptions = [
        'async' => 'async',
    ];

    public $css = [
        '//fonts.googleapis.com/css?family=Roboto:300,400,400italic,500,900%7CRoboto+Slab:300,400%7CRoboto+Mono:400',
        'css/slick.min.css',
        'css/slick-theme.min.css',
        'css/font-awesome.css',
        'css/ionicons.css',
        'css/bootstrap.css',
        'css/app.css',
        'css/app-custom.css',
        'css/animation.css',
        'css/category.css',
        'css/asScrollable.min.css',
        'css/slidePanel.min.css',
        'css/jquery.dataTables.min.css',
        'css/sweetalert.css',
        'css/alertify.min.css',
        'css/custom.css',
    ];
    public $js = [
       // 'js/jquery.min.js',
        'js/jquery-ui.min.js',
        'js/bootstrap.min.js',
        'js/jquery.slimscroll.min.js',
        'js/jquery.scrollLock.min.js',
        'js/jquery.placeholder.min.js',
        'js/app.js',
        'js/app-custom.js',
        'js/admin-ajax.js',
        'js/jquery.form.js',
        'js/sweetalert.min.js',
        'js/jquery.sweet-alert.custom.js',
        'js/jquery.dataTables.min.js',
        'js/base_tables_datatables.js',
        'js/select2.full.min.js',
        'js/jquery.asScrollable.all.min.js',
        'js/jquery-slidePanel.min.js',
        'js/bootbox.js',
        'js/core.min.js',
        'js/alertify.js',
        'js/action-btn.min.js',
        'js/selectable.min.js',
        'js/components.js',
        'js/app.min.js',
        'js/slick.min.js',
        'js/Chart.min.js',
        'js/chartcustom.js',
        'js/index.js',
        'js/category.js',
        'js/jquery-ui.min.js'
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];
}
