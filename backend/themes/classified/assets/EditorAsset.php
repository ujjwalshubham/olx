<?php

namespace backend\themes\classified\assets;

use yii\web\AssetBundle;

/**
 * Editor backend application asset bundle.
 */
class EditorAsset extends AssetBundle
{
    // public $basePath = '@app';
    public $sourcePath = '@app/themes/classified/web';

    public $publishOptions = [
        'forceCopy' => true,
    ];
    public $jsOptions = [
        'async' => 'async',
        // 'position' => \yii\web\View::POS_HEAD
    ];

    public $css = [
        'js/plugins/simditor/styles/simditor.css'
    ];
    public $js = [
        'js/plugins/simditor/scripts/mobilecheck.js',
        'js/plugins/simditor/scripts/module.js',
        'js/plugins/simditor/scripts/uploader.js',
        'js/plugins/simditor/scripts/hotkeys.js',
        'js/plugins/simditor/scripts/simditor.js'

    ];
    public $depends = [
        'backend\themes\classified\assets\ThemeAsset',
    ];

}
