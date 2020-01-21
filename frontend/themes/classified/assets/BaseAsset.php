<?php

namespace frontend\themes\classified\assets;

use yii\web\AssetBundle;

/**
 * Main backend application asset bundle.
 */
class BaseAsset extends AssetBundle
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
        'css/font-awesome.css',
        'css/bootstrap.css',
        'css/styles.css'
    ];
    public $js = [
        // 'js/jquery.min.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];
}
