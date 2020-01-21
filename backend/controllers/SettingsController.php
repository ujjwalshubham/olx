<?php

namespace backend\controllers;

use backend\models\AdPostSetting;
use backend\models\GeneralSetting;
use Yii;
use common\models\Settings;
use backend\models\search\SettingsSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * SettingsController implements the CRUD actions for Settings model.
 */
class SettingsController extends Controller
{

    /** @inheritdoc */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Lists all Settings models.
     * @return mixed
     */
    public function actionIndex()
    {
        $tabview='general';
        $model = new GeneralSetting();
        if (Yii::$app->request->post()) {
            $post=Yii::$app->request->post();
            if(isset($post['GeneralSetting'])){
                $this->updateGeneral($post);
                return $this->redirect(['index']);
            }
        }

        $postmeta=array();
        $settings=Settings::find()->where(['category'=>1])->all();
        if(!empty($settings)){
            foreach($settings as $setting){
                $postmeta['GeneralSetting'][$setting->name]=$setting->value;
            }
        }
        $model->load($postmeta);


        return $this->render('index', [
            'model' => $model,'tabview'=>$tabview
        ]);


    }

    public function actionLogoWatermark(){
        $tabview='logo_watermark';
        $model = array();
        if (Yii::$app->request->post()) {
            $post=Yii::$app->request->post();
            $files=$_FILES;

            echo "<pre>"; print_r($files);die;

        }

        $settings=Settings::find()->where(['category'=>2])->all();
        if(!empty($settings)){
            foreach($settings as $setting){
                $model[$setting->name]=$setting->value;
            }
        }
        return $this->render('index', [
            'model' => $model,'tabview'=>$tabview
        ]);
    }

    public function actionAdPost()
    {
        $tabview='ad_post';
        $model = new AdPostSetting();
        if (Yii::$app->request->post()) {
            $post=Yii::$app->request->post();
            if(isset($post['AdPostSetting'])){
                $this->updateAdPost($post);
                return $this->redirect(['ad-post']);
            }
        }

        $postmeta=array();
        $settings=Settings::find()->where(['category'=>5])->all();
        if(!empty($settings)){
            foreach($settings as $setting){
                if($setting->name == 'price_field'){
                    $postmeta['AdPostSetting'][$setting->name]=explode(',',$setting->value);
                }
                else{
                    $postmeta['AdPostSetting'][$setting->name]=$setting->value;
                }

            }
        }
        $model->load($postmeta);
        return $this->render('index', [
            'model' => $model,'tabview'=>$tabview
        ]);
    }

    function updateGeneral($post){
        $setting_g=$post['GeneralSetting'];
        foreach($setting_g as $key=>$value){
            $setting=Settings::find()->where(['category'=>1,'name'=>$key])->one();
            if(empty($setting)){
                $setting=new Settings();
                $setting->category=1;
            }
            $setting->name=$key;
            $setting->value=$value;
            $setting->save();
        }
        return true;
    }

    function updateAdPost($post){
        $setting_g=$post['AdPostSetting'];
        foreach($setting_g as $key=>$value){
            $setting=Settings::find()->where(['category'=>5,'name'=>$key])->one();
            if(empty($setting)){
                $setting=new Settings();
                $setting->category=5;
            }
            $setting->name=$key;

            if($key == 'price_field'){
                $setting->value=implode(',',$value);
            }
            else{
                $setting->value=$value;
            }

            $setting->save();
        }
        return true;
    }

    /**
     * Finds the Settings model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Settings the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Settings::findOne($id)) !== null) {
            return $model;
        }
        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
