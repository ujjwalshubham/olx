<?php

namespace api\controllers;

use Yii;
use yii\web\Response;
use yii\helpers\Url;
use yii\rest\ActiveController;
use common\models\User;
use frontend\modules\user\models\LoginForm;
use common\models\UserProfile;
use yii\db\Query;
use yii\data\Pagination;


class UserController extends ActiveController {

    public $modelClass = 'common\models\User';

    public function actionLogin() { 
		echo "hi";exit;
        $response = array();
        $LoginForm = array();
        $params = Yii::$app->request->post();
        $model = new LoginForm();
        $LoginForm['LoginForm'] = Yii::$app->request->post();
        if (isset($params['token']) && isset($params['identity']) && isset($params['password'])) {
            if ($model->load($LoginForm) && $model->login()) {
                User::updateAll(array('token' => $params['token']), '(email = "' . $params['identity'] . '")  ');
                $userDetail = UserProfile::getUserDetail($model->user->id);

//$getCountryName = Country::find()->where(['iso2' => $userDetail['country']])->one();
                $getCountry = '---';
                if ($userDetail['location'] != '') {
                    $getCountry = explode(',', $userDetail['location']);
                    $getCountry = end($getCountry);
                }

                $isSellerUser = Gigs::find()->where(['userid' => $userDetail['id']])->count();
                $sellerStatus = $isSellerUser > 0 ? 1 : 0;

                $dataArray = array('userid' => $userDetail['id'], 'sellerStatus' => $sellerStatus, 'usertype' => $userDetail['usertype'], 'userstatus' => $userDetail['onlinestatus'], 'quickbloxid' => $userDetail['quickblox_id'], 'quickblox_username' => $userDetail['quickblox_username'], 'quickblox_password' => $userDetail['quickblox_password'], 'username' => $userDetail['username'], 'firstname' => $userDetail['firstname'], 'lastname' => $userDetail['lastname'], 'email' => $userDetail['email'], 'country' => '' . trim($getCountry) . '', 'avatar' => $userDetail['avatar_path'] != '' ? $userDetail['avatar_base_url'] . '/thumb_' . $userDetail['avatar_path'] : '', 'ordermsg' => $userDetail['order_notification'], 'orderstatus' => $userDetail['order_status_notification'],);

                $response = MyHelper::responsearray(0, false, Yii::t('frontend', 'Login Successful'), $dataArray);
            } else {
                $response = MyHelper::responsearray(1, true, Yii::t('frontend', 'Incorrect credential or your account not verify'));
            }
        } else {
            $response = MyHelper::responsearray(1, true, Yii::t('frontend', 'Email, password, token fields are required'));
        }
        \Yii::$app->response->format = Response::FORMAT_JSON;
        return $response;
    }


}
