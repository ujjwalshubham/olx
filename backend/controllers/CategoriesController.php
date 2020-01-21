<?php

namespace backend\controllers;

use common\components\AppFileUploads;
use common\components\AppHelper;
use common\models\CategoriesLang;
use Intervention\Image\ImageManagerStatic;
use trntv\filekit\actions\DeleteAction;
use trntv\filekit\actions\UploadAction;
use Yii;
use common\models\Categories;
use backend\models\search\CategoriesSearch;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;
use yii\widgets\ActiveForm;

/**
 * CategoriesController implements the CRUD actions for Categories model.
 */
class CategoriesController extends Controller
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

    public function actions()
    {
        return [
            'avatar-upload' => [
                'class' => UploadAction::class,
                'deleteRoute' => 'avatar-delete',
                'on afterSave' => function ($event) {
                    /* @var $file \League\Flysystem\File */
                    $file = $event->file;
                    $img = ImageManagerStatic::make($file->read())->fit(215, 215);
                    $file->put($img->encode());
                }
            ],
            'avatar-delete' => [
                'class' => DeleteAction::class
            ]
        ];
    }

    /**
     * Lists all Categories models.
     * @return mixed
     */
    public function actionIndex()
    {
        $addCategory=new Categories();
        $categories=Categories::find()->where(['parent_id'=>0])->all();
        $subcategories=Categories::find()->where(['>','parent_id', 0])->all();
        return $this->render('index', [
            'categories' => $categories,'addCategory'=>$addCategory,'subcategories'=>$subcategories
        ]);
    }

    /**
     * Displays a single Categories model.
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
     * Creates a new Categories model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate(){
        $model = new Categories();
        $params=Yii::$app->request->queryParams;
        $parent=0;
        if(isset($params['id'])){
            $parent=$params['id'];
        }
        if ($model->load(Yii::$app->request->post())) {
            if($model->save()){
                if(!empty($_FILES)){
                    $imageUpload=AppFileUploads::updateCategoryImage($model->id,$_FILES);
                }
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
            'model' => $model, 'parent'=>$parent
        ]);
    }

    /**
     * Updates an existing Categories model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        if ($model->load(Yii::$app->request->post())) {
            if($model->save()){
                if(!empty($_FILES)){
                    $imageUpload=AppFileUploads::updateCategoryImage($id,$_FILES);
                }
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
            'model' => $model,'parent'=>$model->parent_id
        ]);
    }

    public function actionGetSubCat(){
        $response=0;
        if(Yii::$app->request->queryParams){
            $params=Yii::$app->request->queryParams;
            if(isset($params['category_id']) && $params['category_id'] > 0){
                $categories=Categories::find()->where(['parent_id'=>$params['category_id']])->all();
                if(!empty($categories)){
                    $response=$this->renderPartial('_subcategory_append',['categories'=>$categories,'parent'=>$params['category_id']]);
                }
                else{
                    $response=$this->renderPartial('_subcategory_append',['categories'=>array(),'parent'=>$params['category_id']]);
                }
            }
        }
        else{
            $categories=Categories::find()->where(['>','parent_id', 0])->all();
            if(!empty($categories)){
                $response=$this->renderPartial('_subcategory_append',['categories'=>$categories,'parent'=>'all']);
            }
            else{
                $response=$this->renderPartial('_subcategory_append',['categories'=>array(),'parent'=>0]);
            }
        }
        echo $response; exit;
    }

    public function actionGetLangTranslationForm(){
        if (Yii::$app->request->post()) {
            $post=Yii::$app->request->post();
            $cat_id=$post['id'];
            $category=Categories::findOne($cat_id);
            $response=$this->renderPartial('_category_lang_form',['category'=>$category,
                'category_type'=>$post['cat_type']]);
            echo $response; exit;
        }
    }

    public function actionEditLangTranslation(){
        if (Yii::$app->request->post()) {
            $post=Yii::$app->request->post();
            $cat_id=$post['id'];
            $values=$post['value'];
            foreach($values as $value){
                $lan_code=$value['code'];
                $category_lang=CategoriesLang::find()->where(['category_id'=>$cat_id,'locale'=>$lan_code])->one();
                if(empty($category_lang)){
                    $category_lang=new CategoriesLang();
                }
                $category_lang->category_id=$cat_id;
                $category_lang->locale=$lan_code;
                $category_lang->title=$value['title'];
                $category_lang->slug=$value['slug'];
                $category_lang->description=$value['title'];
                $category_lang->save();
            }
            echo 1; exit;
        }
        echo 0; exit;
    }

    /**
     * Deletes an existing Categories model.
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
     * Finds the Categories model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Categories the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Categories::findOne($id)) !== null) {
            return $model;
        }
        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
