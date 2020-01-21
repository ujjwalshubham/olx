<?php

namespace backend\controllers;

use common\components\AppHelper;
use Yii;
use common\models\Faqs;
use backend\models\search\FaqsSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;
use yii\widgets\ActiveForm;

/**
 * FaqsController implements the CRUD actions for Faqs model.
 */
class FaqsController extends Controller
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
     * Lists all Faqs models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new FaqsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Faqs model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Faqs model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Faqs();

        if ($model->load(Yii::$app->request->post())) {
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

        return $this->renderAjax('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Faqs model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())) {
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

        return $this->renderAjax('update', [
            'model' => $model,'id'=>$id
        ]);
    }

    /**
     * Deletes an existing Faqs model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Faqs model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Faqs the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Faqs::findOne($id)) !== null) {
            return $model;
        }
        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
