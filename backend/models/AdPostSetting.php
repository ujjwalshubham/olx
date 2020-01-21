<?php
namespace backend\models;

use Yii;

class AdPostSetting extends \yii\base\Model
{

    const VALUE_ENABLE=1;
    const VALUE_DISABLE=0;

    public $auto_approve;
    public $premium_listing_option;
    public $max_image_upload=3;
    public $watermark;
    public $description_editor;
    public $address_field;
    public $tags_field;
    public $terms_condition_link;
    public $privacy_page_link;
    public $price_field;


    public function rules()
    {
        return [
            [['auto_approve','premium_listing_option','max_image_upload','watermark','description_editor',
               'address_field','tags_field' ],'number'],
            [['terms_condition_link','privacy_page_link'],'string'],
            [['max_image_upload','price_field'],'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'auto_approve'=>'Ads Auto Approve',
            'premium_listing_option'=>'Premium Listing Option',
            'max_image_upload'=>'Max Image upload',
            'watermark'=>'Watermark',
            'description_editor'=>'Description Editor',
            'address_field'=>'Address Field',
            'tags_field'=>'Tags Field',
            'terms_condition_link'=>'Term & Condition Page Link',
            'privacy_page_link'=>'Privacy Page Link',
            'price_field'=>'Price Field'
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