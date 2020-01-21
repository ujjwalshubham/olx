<?php

use Sitemaped\Sitemap;

return [
    'class' => 'yii\web\UrlManager',
    'enablePrettyUrl' => true,
    'showScriptName' => false,
    'rules' => [
        // Pages
        ['pattern' => 'page/<slug>', 'route' => 'page/view'],

        // Articles
        ['pattern' => 'article/index', 'route' => 'article/index'],
        ['pattern' => 'article/attachment-download', 'route' => 'article/attachment-download'],
        ['pattern' => 'article/<slug>', 'route' => 'article/view'],

        // Sitemap
        ['pattern' => 'sitemap.xml', 'route' => 'site/sitemap', 'defaults' => ['format' => Sitemap::FORMAT_XML]],
        ['pattern' => 'sitemap.txt', 'route' => 'site/sitemap', 'defaults' => ['format' => Sitemap::FORMAT_TXT]],
        ['pattern' => 'sitemap.xml.gz', 'route' => 'site/sitemap', 'defaults' => ['format' => Sitemap::FORMAT_XML, 'gzip' => true]],
		
		['pattern' => 'contact', 'route'=>'site/contact'],
		['pattern' => 'faq', 'route'=>'site/faq'],
		['pattern' => 'feedback', 'route'=>'site/feedback'],
		['pattern' => 'sitemap', 'route'=>'site/sitemap'],
		['pattern' => 'report', 'route'=>'site/report'],
		
		['pattern' => 'signup', 'route' => 'user/sign-in/signup'],
		['pattern' => 'ajax-signup', 'route'=>'site/ajax-signup'],
		['pattern' => 'ajax-unique', 'route'=>'site/ajax-unique'],
		['pattern' => 'email-unique', 'route'=>'site/email-unique'],
		['pattern' => 'email-otp-verify', 'route'=>'site/email-otp-verify'],
		['pattern' => 'check-email-otp', 'route'=>'site/check-email-otp'],
		
		['pattern' => 'otp-verification', 'route' => 'site/otp-verification'],
		['pattern' => 'otp-verify', 'route'=>'site/otp-verify'],
		['pattern' => 'login', 'route' => 'user/sign-in/login'],
		['pattern' => 'logout', 'route' => 'user/sign-in/logout'],
		['pattern' => 'forgot-password', 'route'=>'user/sign-in/request-password-reset'],
		
		['pattern' => 'country_change/<id>', 'route' => 'user/default/country-change'],
		['pattern' => 'state_change/<id>', 'route' => 'user/default/state-change'],
		
		['pattern' => 'user/post-ad', 'route' => 'user/postad/index'],
		['pattern' => 'get-category', 'route' => 'user/postad/get-sub-category'],
		['pattern' => 'get-fields', 'route' => 'user/postad/get-fields'],
		['pattern' => 'get-category-custom-field', 'route' => 'user/postad/get-category-custom-field'],
		
		['pattern' => 'category/<slug>', 'route' => 'search/category'],
		['pattern' => 'get-cities', 'route' => 'search/get-cities'],
		['pattern' => 'get-states', 'route' => 'search/get-states'],
		['pattern' => 'get-cities-by-state', 'route' => 'search/get-cities-by-state'],
		['pattern' => 'listing/search', 'route' => 'search/get-search'],
		['pattern' => 'listing', 'route' => 'search/listing'],
		['pattern' => 'user/my-profile', 'route' => 'user/default/profile'],
		['pattern' => 'user/profile-field-edit', 'route'=>'user/default/profile-field-edit'],
		['pattern' => 'user/send-otp', 'route'=>'user/default/send-otp'],
		['pattern' => 'user/replyemail', 'route'=>'user/default/reply-by-email'],
		['pattern' => 'user/payment/<slug>/<id>', 'route' => 'user/postad/ad-payment'],
		['pattern' => 'user/transactions', 'route' => 'user/default/transactions'],
		['pattern' => 'user/profile/<slug>', 'route' => 'site/user-profile'],
		['pattern' => 'user/public-profile', 'route' => 'user/default/public-profile'],
		['pattern' => 'user/update-profile', 'route' => 'user/default/update-profile'],
		['pattern' => 'user/account-setting', 'route' => 'user/default/account-setting'],
		['pattern' => 'user/my-ads', 'route' => 'user/default/my-ads'],
		['pattern' => 'user/pending-ads', 'route' => 'user/default/pending-ads'],
		['pattern' => 'user/hidden-ads', 'route' => 'user/default/hidden-ads'],
		['pattern' => 'user/resubmit-ads', 'route' => 'user/default/resubmit-ads'],
		['pattern' => 'user/active-ads', 'route' => 'user/default/active-ads'],
		['pattern' => 'user/rejected-ads', 'route' => 'user/default/rejected-ads'],
		['pattern' => 'user/warning-ads', 'route' => 'user/default/warning-ads'],
		['pattern' => 'user/favourite-ads', 'route' => 'user/default/favourite-ads'],
		['pattern' => 'user/hide-ad', 'route' => 'user/default/hide-ad'],
		['pattern' => 'user/unhide-ad', 'route' => 'user/default/unhide-ad'],
		['pattern' => 'user/membership', 'route' => 'user/default/membership'],
		['pattern' => 'user/upgrade-membership', 'route' => 'user/default/upgrade-membership'],
		['pattern' => 'user/advertise_plan', 'route' => 'user/postad/advertise-plan'],
		['pattern' => 'user/membership/changeplan', 'route' => 'user/default/changeplan'],
		['pattern' => 'user/ad-detail/<slug>', 'route' => 'site/ad-detail'],
		['pattern' => 'user/submit_review', 'route' => 'user/postad/submit-review'],
		['pattern' => 'user/edit-ad/<id>', 'route' => 'user/postad/edit-ad'],
		['pattern' => 'user/delete-advertise-media', 'route' => 'user/postad/delete-media'],
		['pattern' => 'addwishlist', 'route' => 'site/add-wishlist'],
		['pattern' => 'removewishlist', 'route' => 'site/remove-wishlist'],
		['pattern' => 'user/dashboard', 'route' => 'user/default/dashboard'],
   
    ]
];
