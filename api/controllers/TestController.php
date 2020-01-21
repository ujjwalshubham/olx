<?php declare(strict_types=1);

namespace api\controllers;

use Yii;
use yii\web\Controller;
use yii\rest\ActiveController;
use yii\web\NotFoundHttpException;
use yii\helpers\Url;

class TestController extends ActiveController
{
    public function beforeAction($action) {

                if($action->id == 'index'){

                    $this->enableCsrfValidation = false;

                    $cookies = Yii::$app->response->cookies;

                    $cookies->remove('_csrf');

                    unset($cookies['_csrf']);

                }

                return parent::beforeAction($action);

        }
        
    public function actionTestd() {
        die('dfgkjdflgkjldfgjlkdfgdfhgkjdgkjhdfkghdfkjghdgkjh');
        /*$response = array();
        $cData = array();
        $countryData = Country::find()->where("dialcode != '' ")->all();
        foreach ($countryData as $dataCou) {
            $rows['id'] = $dataCou['id'];
            $rows['isocode'] = $dataCou['iso2'];
            $rows['name'] = $dataCou['name'];
            $rows['dialcode'] = $dataCou['dialcode'];
            $cData[] = $rows;
        }
        $response = MyHelper::responsearray(0, false, Yii::t('frontend', 'Success'), $cData);

        \Yii::$app->response->format = Response::FORMAT_JSON;
        return $response;*/
    }

    
}
