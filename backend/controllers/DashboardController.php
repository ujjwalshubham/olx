<?php
namespace backend\controllers;

use common\models\PostAd;
use common\models\User;
use Yii;
use yii\web\Controller;

/**
 * Dashboard controller
 */
class DashboardController extends Controller
{

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        $activeAdsCount=PostAd::find()->where(['status'=>PostAd::STATUS_ACTIVE])->count();
        $newAdsCount=PostAd::find()->where(['status'=>PostAd::STATUS_PENDING])->count();
        $allusersCount=User::find()->where(['user_type'=>'user'])->count();
        $alltransaction=0;

        return $this->render('index',[
            'activeAdsCount'=>$activeAdsCount,
            'newAdsCount'=>$newAdsCount,
            'allusersCount'=>$allusersCount,
            'alltransaction'=>$alltransaction
        ]);
    }
}