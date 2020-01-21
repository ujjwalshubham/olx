<?php

namespace backend\controllers;

use common\models\Plans;
use Yii;
use common\models\Packages;
use backend\models\search\PackagesSearch;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;
use yii\widgets\ActiveForm;
use common\components\AppHelper;

/**
 * PackagesController implements the CRUD actions for Packages model.
 */
class PackagesController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Packages models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new PackagesSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Packages model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Packages model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Packages();

        if ($model->load(Yii::$app->request->post())) {
            $post=Yii::$app->request->post();
            if(isset($post['Packages']['show_on_home'])){
                $model->show_on_home=1;
            }
            else{
                $model->show_on_home=0;
            }

            if(isset($post['Packages']['show_in_home_search'])){
                $model->show_in_home_search=1;
            }
            else{
                $model->show_in_home_search=0;
            }

            if(isset($post['Packages']['top_search_result'])){
                $model->top_search_result=1;
            }
            else{
                $model->top_search_result=0;
            }
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
        ]);

    }

    /**
     * Updates an existing Packages model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        if ($model->load(Yii::$app->request->post())) {
            $post=Yii::$app->request->post();
            if(isset($post['Packages']['show_on_home'])){
                $model->show_on_home=1;
            }
            else{
                $model->show_on_home=0;
            }

            if(isset($post['Packages']['show_in_home_search'])){
                $model->show_in_home_search=1;
            }
            else{
                $model->show_in_home_search=0;
            }

            if(isset($post['Packages']['top_search_result'])){
                $model->top_search_result=1;
            }
            else{
                $model->top_search_result=0;
            }
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
            'model' => $model
        ]);

    }

    /**
     * Deletes an existing Packages model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Packages model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Packages the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Packages::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
