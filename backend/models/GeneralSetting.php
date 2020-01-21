<?php
namespace backend\models;

use Yii;

class GeneralSetting extends \yii\base\Model
{

    const DELETE_AD_A_EXPIRE_YES=1;
    const DELETE_AD_A_EXPIRE_NO=0;

    const USER_LANG_SELECT_YES=1;
    const USER_LANG_SELECT_NO=0;

    const USER_THEME_SELECT_YES=1;
    const USER_THEME_SELECT_NO=0;

    const THEME_COLOR_SWITCHER_ON=1;
    const THEME_COLOR_SWITCHER_OFF=0;

    const VALUE_ENABLE=1;
    const VALUE_DISABLE=0;

    public $site_url;
    public $site_title;
    public $google_api_key;
    public $featured_ad_fee=10;
    public $urgent_ad_fee=10;
    public $hightlight_ad_fee=10;
    public $delete_ad_after_expire;
    public $user_language_selection;
    public $user_theme_selection;
    public $theme_color_switcher;
    public $default_package;
    public $page_size;
    public $s3_bucket;
    public $s3_bucket_url;
    public $image_url_localpath;


    public function rules()
    {
        return [
            [['site_url', 'site_title','featured_ad_fee',
                'urgent_ad_fee','hightlight_ad_fee','page_size'], 'required'],
            [['featured_ad_fee','urgent_ad_fee','hightlight_ad_fee','page_size'],'number'],
            [['site_url','site_title','google_api_key'],'string'],
            [['google_api_key','delete_ad_after_expire','user_language_selection','user_theme_selection',
                'theme_color_switcher','default_package','s3_bucket','s3_bucket_url','image_url_localpath'],'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'site_url'=>'Site Url',
            'site_title'=>'Site Title',
            'page_size'=>'Page Size',
            's3_bucket'=>'S3 Bucket Upload',
            's3_bucket_url'=>'S3 bucket base url',
            'image_url_localpath'=>'Local Image Url',
            'featured_ad_fee'=>'Featured Ad Fee',
            'urgent_ad_fee'=>'Urgent Ad Fee',
            'hightlight_ad_fee'=>'Highlight Ad Fee',
            'google_api_key'=>'Google Map API Key',
            'delete_ad_after_expire'=>'Delete Ad after expire',
            'user_language_selection'=>'Allow User Language Selection',
            'user_theme_selection'=>'Allow User Theme Selection',
            'theme_color_switcher'=>'Theme/Color switcher',
            'default_package'=>'Registered user default package'
        ];
    }

    /**
     * @return array|mixed
     */
    public static function deleteadafterexpire()
    {
        return [
            self::DELETE_AD_A_EXPIRE_YES => Yii::t('common', 'Yes Delete'),
            self::DELETE_AD_A_EXPIRE_NO => Yii::t('common', 'No')
        ];
    }

    /**
     * @return array|mixed
     */
    public static function userlanguageselection()
    {
        return [
            self::USER_LANG_SELECT_YES => Yii::t('common', 'Yes'),
            self::USER_LANG_SELECT_NO => Yii::t('common', 'No')
        ];
    }

    /**
     * @return array|mixed
     */
    public static function userthemeselection()
    {
        return [
            self::USER_THEME_SELECT_YES => Yii::t('common', 'Yes'),
            self::USER_THEME_SELECT_NO => Yii::t('common', 'No')
        ];
    }

    /**
     * @return array|mixed
     */
    public static function themecolorswitcher()
    {
        return [
            self::THEME_COLOR_SWITCHER_ON => Yii::t('common', 'On'),
            self::THEME_COLOR_SWITCHER_OFF => Yii::t('common', 'Off')
        ];
    }

    /**
     * @return array|mixed
     */
    public static function enabledisable()
    {
        return [
            self::VALUE_ENABLE => Yii::t('common', 'Enable'),
            self::VALUE_DISABLE => Yii::t('common', 'Disable')
        ];
    }
}