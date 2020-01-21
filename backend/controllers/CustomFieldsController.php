<?php

namespace backend\controllers;

use common\models\Categories;
use common\models\CategoriesCustomFields;
use common\models\CategoriesLang;
use common\models\CustomFieldOptions;
use common\models\CustomFieldOptionsLang;
use common\models\CustomFieldsLang;
use common\models\CustomFieldTypes;
use common\models\PostAdCustomFields;
use Yii;
use common\models\CustomFields;
use backend\models\search\CustomFieldsSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * CustomFieldsController implements the CRUD actions for CustomFields model.
 */
class CustomFieldsController extends Controller
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
     * Lists all CustomFields models.
     * @return mixed
     */
    public function actionIndex(){
        $searchModel = new CustomFieldsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $categories=Categories::find()->where(['status'=>Categories::STATUS_ACTIVE,'parent_id'=>0])->all();

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,'categories'=>$categories
        ]);
    }

    public function actionCustomFieldSaved(){
        if(Yii::$app->request->post()){
            $post=Yii::$app->request->post();

            if(isset($post['fields'])){
                $fields=$post['fields'];
                $fields=json_decode($fields);

//                echo "<pre>"; print_r($fields);die;

                foreach($fields as $field){
                    $type=CustomFieldTypes::find()->where(['data_type'=>$field->type])->one();
                    if(!empty($type)){
                        $customField=CustomFields::findOne($field->id);
                        if(empty($customField)){
                            $customField= new CustomFields();
                        }
                        $customField->field_type_id=$type['id'];
                        $customField->label=$field->label;

                        if($field->required === true){
                            $customField->isRequired='true';
                        }
                        else{
                            $customField->isRequired='false';
                        }
                        $customField->status=1;
                        if($customField->save()){
                            CategoriesCustomFields::deleteAll(['field_id' => $customField->id]);
                            $parent_cat=array();
                            if(isset($field->services) && !empty($field->services)){
                                foreach($field->services as $service){
                                    $cat_field=new CategoriesCustomFields();
                                    $cat_field->field_id=$customField->id;
                                    $cat_field->category_id=$service;
                                    $cat_field->parent_check=0;
                                    $cat_field->save();

                                    $parent_id=Categories::findOne($service);
                                    $parent_cat[$parent_id->parent_id]=$parent_id->parent_id;
                                }
                            }
                            if(isset($field->maincat) && !empty($field->maincat)){
                                foreach($field->maincat as $maincat){
                                    if(!in_array($maincat,$parent_cat)){
                                        $cat_field=new CategoriesCustomFields();
                                        $cat_field->field_id=$customField->id;
                                        $cat_field->category_id=$maincat;
                                        $cat_field->parent_check=1;
                                        $cat_field->save();
                                    }
                                }
                            }

                            if(isset($field->items)){
                                foreach($field->items as $item){
                                    $custionFieldOption=CustomFieldOptions::findOne($item->id);
                                    if(empty($custionFieldOption)){
                                        $custionFieldOption= new CustomFieldOptions();
                                    }
                                    $custionFieldOption->field_id=$customField->id;
                                    $custionFieldOption->label=$item->value;
                                    $custionFieldOption->save();
                                }
                            }
                        }
                    }

                }
            }
        }
        echo "success"; exit();
    }

    public function actionDeleteCustomFields(){
        if(Yii::$app->request->post()){
            $post=Yii::$app->request->post();
            $field_id=$post['id'];
            $custom_field=CustomFields::findOne($field_id);
            if(!empty($custom_field)){
                $custom_field->delete();
                $custome_field_langs=CustomFieldsLang::find()->where(['field_id'=>$field_id])->all();
                if(!empty($custome_field_langs)){
                    foreach($custome_field_langs as $custome_field_lang){
                        $custome_field_lang->delete();
                    }
                }

                $custome_field_options=CustomFieldOptions::find()->where(['field_id'=>$field_id])->all();
                if(!empty($custome_field_options)){
                    foreach($custome_field_options as $custome_field_option){
                        $field_option_id=$custome_field_option->id;
                        $custome_field_option->delete();
                        $custome_field_options_langs=CustomFieldOptionsLang::find()
                            ->where(['field_option_id'=>$field_option_id])->all();
                        if(!empty($custome_field_options_langs)){
                            foreach($custome_field_options_langs as $custome_field_options_lang){
                                $custome_field_options_lang->delete();
                            }
                        }
                    }
                }

                $category_fields=CategoriesCustomFields::find()->where(['field_id'=>$field_id])->all();
                if(!empty($category_fields)){
                    foreach($category_fields as $category_field){
                        $category_field->delete();
                    }
                }

                $post_ad_custom_fields=PostAdCustomFields::find()->where(['field_id'=>$field_id])->all();
                if(!empty($post_ad_custom_fields)){
                    foreach($post_ad_custom_fields as $post_ad_custom_field){
                        $post_ad_custom_field->delete();
                    }
                }
                echo true; exit();
            }
            echo 1; exit;
        }
        echo 0; exit;
    }

    public function actionDeleteCustomOption(){
        if(Yii::$app->request->post()){
            $post=Yii::$app->request->post();
            $field_id=$post['field_id'];
            $option_id_id=$post['id'];
            $custom_field=CustomFields::findOne($field_id);
            if(!empty($custom_field)){
                $custome_field_options=CustomFieldOptions::find()->where(['field_id'=>$field_id,
                    'id'=>$option_id_id])->all();
                if(!empty($custome_field_options)){
                    foreach($custome_field_options as $custome_field_option){
                        $field_option_id=$custome_field_option->id;
                        $custome_field_option->delete();
                        $custome_field_options_langs=CustomFieldOptionsLang::find()
                            ->where(['field_option_id'=>$field_option_id])->all();
                        if(!empty($custome_field_options_langs)){
                            foreach($custome_field_options_langs as $custome_field_options_lang){
                                $custome_field_options_lang->delete();
                            }
                        }
                    }
                }
                echo 1; exit;
            }
            echo 1; exit;
        }
        echo 0; exit;
    }

    public function actionGetCustomfieldLangTranslationForm(){
        if (Yii::$app->request->post()) {
            $post=Yii::$app->request->post();
            $field_id=$post['id'];
            $customfield=CustomFields::findOne($field_id);
            $response=$this->renderPartial('_field_lang_form',['customfield'=>$customfield,
                'category_type'=>$post['cat_type']]);
            echo $response; exit;
        }
    }

    public function actionEditCustomfieldLangTranslationForm(){
        if (Yii::$app->request->post()) {
            $post=Yii::$app->request->post();
            $field_id=$post['id'];
            $values=$post['trans_name'];
            foreach($values as $key=>$value){
                $lan_code=$post['trans_lang'][$key];
                $field_lang=CustomFieldsLang::find()->where(['field_id'=>$field_id,
                    'locale'=>$lan_code])->one();
                if(empty($field_lang)){
                    $field_lang=new CustomFieldsLang();
                }
                $field_lang->field_id=$field_id;
                $field_lang->locale=$lan_code;
                $field_lang->label=$value;
                $field_lang->save();
            }
            echo 1; exit;
        }
        echo 0; exit;
    }

    public function actionGetOptionLangTranslationForm(){
        if (Yii::$app->request->post()) {
            $post=Yii::$app->request->post();
            $option_id=$post['id'];
            $optionfield=CustomFieldOptions::findOne($option_id);
            $response=$this->renderPartial('_option_lang_form',['optionfield'=>$optionfield,
                'category_type'=>$post['cat_type']]);
            echo $response; exit;
        }
    }

    public function actionEditOptionLangTranslationForm(){
        if (Yii::$app->request->post()) {
            $post=Yii::$app->request->post();
            $option_id=$post['id'];
            $values=$post['value'];
            foreach($values as $value){
                $lan_code=$value['code'];
                $option_lang=CustomFieldOptionsLang::find()->where([
                    'field_option_id'=>$option_id,'locale'=>$lan_code])->one();
                if(empty($option_lang)){
                    $option_lang=new CustomFieldOptionsLang();
                }
                $option_lang->field_option_id=$option_id;
                $option_lang->locale=$lan_code;
                $option_lang->label=$value['title'];
                $option_lang->save();
            }
            echo 1; exit;
        }
        echo 0; exit;
    }


    /**
     * Displays a single CustomFields model.
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
     * Creates a new CustomFields model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new CustomFields();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }
        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing CustomFields model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }
        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing CustomFields model.
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
     * Finds the CustomFields model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return CustomFields the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = CustomFields::findOne($id)) !== null) {
            return $model;
        }
        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
