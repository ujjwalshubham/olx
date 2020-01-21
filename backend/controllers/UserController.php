<?php

namespace backend\controllers;

use backend\models\search\UserSearch;
use backend\models\UserForm;
use common\components\AppHelper;
use common\models\AdViews;
use common\models\MediaUpload;
use common\models\PostAd;
use common\models\PostAdCategory;
use common\models\PostAdCustomFields;
use common\models\PostAdImages;
use common\models\User;
use common\models\UserProfile;
use common\models\UserToken;
use Yii;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\widgets\ActiveForm;

/**
 * UserController implements the CRUD actions for User model.
 */
class UserController extends Controller
{
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
     * Lists all User models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new UserSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams,User::ROLE_USER);
        $dataProvider->pagination->pageSize=20;
        $dataProvider->setSort([
            'defaultOrder' => ['id'=>SORT_DESC]
        ]);
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single User model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $profile=UserProfile::findOne(['user_id'=>$id]);
        return $this->renderPartial('view', [
            'model' => $this->findModel($id),'profile'=>$profile
        ]);
    }

    /**
     * @param $id
     * @return \yii\web\Response
     * @throws \yii\base\Exception
     * @throws NotFoundHttpException
     */
    public function actionLogin($id)
    {
        $model = $this->findModel($id);
        $tokenModel = UserToken::create(
            $model->getId(),
            UserToken::TYPE_LOGIN_PASS,
            60
        );

        return $this->redirect(
            Yii::$app->urlManagerFrontend->createAbsoluteUrl(['user/sign-in/login-by-pass', 'token' => $tokenModel->token])
        );
    }

    /**
     * Creates a new User model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new UserForm();
        //$model->setScenario('create');
        $model->roles=array('user');
        if ($model->load(Yii::$app->request->post())) {
            $model->roles=array(User::ROLE_USER);
            if($model->save()){
                $response['status']='success';
                $response['message']='Saved Successfully';
                Yii::$app->response->format = trim(Response::FORMAT_JSON);
                return $response;
            }
            else{
                $error = ActiveForm::validate($model);
                $error=AppHelper::errorMessageList($error);
                Yii::$app->response->format = trim(Response::FORMAT_JSON);
                return $error;
            }
        }
        return $this->renderPartial('create', [
            'model' => $model,
            'roles' => ArrayHelper::map(Yii::$app->authManager->getRoles(), 'name', 'name')
        ]);
    }

    /**
     * Updates an existing User model.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = new UserForm();
        $model->setModel($this->findModel($id));

        $profile=UserProfile::findOne(['user_id'=>$id]);
        if(!empty($profile)){
            $model->name=$profile->name;
        }


        if ($model->load(Yii::$app->request->post())) {
            $model->roles=array(User::ROLE_USER);
            if($model->save()){
                $response['status']='success';
                $response['message']='Saved Successfully';
                Yii::$app->response->format = trim(Response::FORMAT_JSON);
                return $response;
            }
            else{
                $error = ActiveForm::validate($model);
                $error=AppHelper::errorMessageList($error);
                Yii::$app->response->format = trim(Response::FORMAT_JSON);
                return $error;
            }
        }

        return $this->renderPartial('update', [
            'model' => $model,'userid'=>$id,
            'roles' => ArrayHelper::map(Yii::$app->authManager->getRoles(), 'name', 'name')
        ]);
    }

    /**
     * Deletes an existing User model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        Yii::$app->authManager->revokeAll($id);
        if($this->findModel($id)->delete()){
            $deleteAllPosts=PostAd::find()->where(['user_id' =>$id])->all();
            foreach ($deleteAllPosts as $post){
                if($post->delete()){
                    PostAdCategory::deleteAll(['ad_id' =>$id]);
                    PostAdCustomFields::deleteAll(['ad_id' =>$id]);
                    AdViews::deleteAll(['ad_id' =>$id]);
                    $post_images=PostAdImages::find()->where(['ad_id' =>$id])->all();
                    foreach ($post_images as $post_image) {
                        if($post_image->delete()){
                            $media_delete=MediaUpload::find()->where(['id' =>$post_image->media_id])->delete();
                        }
                    }
                }
            }
        }
        return $this->redirect(['index']);
    }

    /**
     * Finds the User model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return User the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = User::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
