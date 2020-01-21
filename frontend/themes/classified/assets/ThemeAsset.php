<?php

namespace frontend\themes\classified\assets;

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
        'css/bootstrap.css',
        'css/font-awesome.css',
        'css/slick.css',
        'css/flags/flags.min.css',
        'css/styles.css',
        'css/theme-blue.css',
        'css/responsive.css',
        'css/category-modal.css',
        'css/owl.post.carousel.css',
        'css/loader.css',
        'css/ajax-search.css',
        
       
    ];
    public $js = [
	   'js/bootstrap.min.js',
	   'js/slick.js',
	   'js/custom.js',
	   'js/user-ajax.js',
	   'js/search.js',
       'js/owl.carousel-category.min.js',
       'js/jquery.nicescroll.min.js',
       'js/jquery-ui.js',
       'js/navAccordion.min.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
    ];
}
