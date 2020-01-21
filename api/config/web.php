<?php
$config = [
    'homeUrl' => Yii::getAlias('@apiUrl'),
    'controllerNamespace' => 'api\controllers',
    'defaultRoute' => 'site/index',
    'bootstrap' => ['maintenance'],
    'modules' => [
        'v1' => \api\modules\v1\Module::class,
        'user' => [
            'class' => frontend\modules\user\Module::class,
            'shouldBeActivated' => true,
            'enableLoginByPass' => false,
        ],
    ],
    'components' => [
        'errorHandler' => [
            'errorAction' => 'site/error'
        ],
        'maintenance' => [
            'class' => common\components\maintenance\Maintenance::class,
            'enabled' => function ($app) {
                if (env('APP_MAINTENANCE') === '1') {
                    return true;
                }
                return $app->keyStorage->get('frontend.maintenance') === 'enabled';
            }
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
                [
                    'class' => 'yii\log\FileTarget',
                    'categories' => ['yii\db\Command::execute'],
                    'levels' => ['info'],
                    'logFile' => '@runtime/logs/info.log',
                ],
                [
                    'class' => 'yii\log\FileTarget',
                    'categories' => ['yii\mail\BaseMailer::send'],
                    'logVars' => [null],
                    'logFile' => '@runtime/logs/email.log',
                ],
                [
                    'class' => 'yii\log\FileTarget',
                    'categories' => ['yii\web\HttpException:404'],
                    'levels' => ['error', 'warning'],
                    'logFile' => '@runtime/logs/404.log',
                ],
                [
                    'class' => 'yii\log\FileTarget',
                    'categories' => [
                        'api\modules\v1\controllers\ArticleController::actionUpdateprofile',
                        'api\modules\v1\controllers\ArticleController::actionAddPost'

                    ],
                    'levels' => ['info'],
                    'logFile' => '@runtime/logs/apilogs_'.env('LOG_FILE').'.log',
                ],
            ],
        ],
        'request' => [
            'class' => 'common\components\Request',
            'web' => 'api/web',
            'adminUrl' => 'api',
            'enableCookieValidation' => false,
        ],
        'user' => [
            'class' => yii\web\User::class,
            'identityClass' => common\models\User::class,
            'loginUrl' => ['/user/sign-in/login'],
            'enableAutoLogin' => true,
            'as afterLogin' => common\behaviors\LoginTimestampBehavior::class
        ],

    ]
];

return $config;
