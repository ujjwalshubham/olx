<?php

namespace backend\themes\classified\assets;

use yii\web\AssetBundle;

/**
 * CustomFields backend application asset bundle.
 */
class FieldsAsset extends AssetBundle
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

    ];
    public $js = [
        'js/custom-manage/jquery-ui.min.js',
        'js/custom-manage/custom_fields.js',
        'js/custom-manage/spin.min.js',
        'js/custom-manage/ladda.min.js',
        'js/custom-manage/alert.js'
    ];
    public $depends = [
        'backend\themes\classified\assets\ThemeAsset',
    ];

}
