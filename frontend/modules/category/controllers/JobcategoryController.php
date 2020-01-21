<?php

namespace frontend\modules\gloomme\jobcategory\controllers;

use Yii;
use frontend\modules\gloomme\jobcategory\models\JobcategorySearch;
use frontend\modules\gloomme\jobcategory\models\Jobcategory;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\NotFoundHttpException;
use frontend\modules\gloomme\jobcategory\models\JobCategoryOption;
use common\traits\FormAjaxValidationTrait;
use yii\imagine\Image;
use common\models\ServiceType;
use common\models\ServiceTypeOption;
use common\models\ServiceTypeOptionValue;
use backend\models\search\ServiceTypeSearch;
use backend\models\search\ServiceTypeOptionSearch;
use backend\models\search\ServiceTypeOptionValueSearch;

class JobcategoryController extends \yii\web\Controller {

    use FormAjaxValidationTrait;

    public function behaviors() {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['index', 'create', 'delete'],
                        'allow' => true,
                        'roles' => ['@']
                    ],
                    [
                        'actions' => ['index', 'store'],
                        'allow' => true,
                        'roles' => ['?']
                    ],
                ]
            ]
        ];
    }

    public function actionIndex() {
        $searchModel = new JobcategorySearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->pagination->pageSize = 10;
        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Creates a new Article model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $deleteCatModel = new JobCategoryOption();
        $model = new Jobcategory();
        $this->performAjaxValidation($model);
        $params = Yii::$app->request->post();
        if ($model->load(Yii::$app->request->post())) {
            if ($model->parentid == '') {
                $model->parentid = 0;
            }
            if (isset($_FILES['Jobcategory']['tmp_name']['image']) && !empty($_FILES['Jobcategory']['tmp_name']['image'])) {

                if (!is_dir("../../frontend/web/uploads/category"))
                    mkdir("../../frontend/web/uploads/category", 0775, true);

                $extension = pathinfo($_FILES['Jobcategory']['name']['image'], PATHINFO_EXTENSION);
                $newimage = "cat_" . time() . "." . $extension;
                $thumbImage = "thumb_cat_" . time() . "." . $extension;
                $smallImage = "small_cat_" . time() . "." . $extension;

                if (!move_uploaded_file($_FILES['Jobcategory']['tmp_name']['image'], '../../frontend/web/uploads/category/' . $newimage)) {
                    $output['error'] = Yii::t('frontend', 'Unable to upload image');
                } else {
//                    $imagine = new Image();
//                    $imagine->thumbnail('../../frontend/web/uploads/category/' . $newimage, 100, 100)
//                            ->save(Yii::getAlias('../../frontend/web/uploads/category/' . $thumbImage), ['quality' => 70]);
//
//                    $imagine->thumbnail('../../frontend/web/uploads/category/' . $newimage, 264, 167)
//                            ->save(Yii::getAlias('../../frontend/web/uploads/category/' . $smallImage), ['quality' => 70]);

                    $model->base_url = \yii\helpers\Url::to('@frontendUrl') . '/uploads/category';
                    $path = time() . "." . $extension;
                    $model->fullimage = $newimage;
//                    $model->smallimage = $smallImage;
//                    $model->thumbimage = $thumbImage;
                }
            }
            if ($model->save()) {
                Yii::$app->getSession()->setFlash('alert', [
                    'body' => Yii::t('backend', 'Created successfully'),
                    'options' => ['class' => 'alert-success']
                ]);
                return $this->redirect(['index']);
            }
        } else {
            return $this->render('create', [
                        'model' => $model,
                        'categories' => Jobcategory::find()->where('parentid = 0 ')->all(),
                        'subCatModel' => ''
            ]);
        }
    }

    /**
     * Updates an existing Page model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id) {
        $model = $this->findModel($id);
        $params = Yii::$app->request->post('Jobcategory');

        if ($model->load(Yii::$app->request->post())) {
            if ($model->parentid == '') {
                $model->parentid = 0;
            }

            if (isset($_FILES['Jobcategory']['tmp_name']['image']) && !empty($_FILES['Jobcategory']['tmp_name']['image'])) {

                if (!is_dir("../../frontend/web/uploads/category"))
                    mkdir("../../frontend/web/uploads/category", 0775, true);

                $extension = pathinfo($_FILES['Jobcategory']['name']['image'], PATHINFO_EXTENSION);
                $newimage = "cat_" . time() . "." . $extension;
                $thumbImage = "thumb_cat_" . time() . "." . $extension;
                $smallImage = "small_cat_" . time() . "." . $extension;

                if (!move_uploaded_file($_FILES['Jobcategory']['tmp_name']['image'], '../../frontend/web/uploads/category/' . $newimage)) {
                    $output['error'] = Yii::t('frontend', 'Unable to upload image');
                } else {
//                    $imagine = new Image();
//                    $imagine->thumbnail('../../frontend/web/uploads/category/' . $newimage, 100, 100)
//                            ->save(Yii::getAlias('../../frontend/web/uploads/category/' . $thumbImage), ['quality' => 70]);
//
//                    $imagine->thumbnail('../../frontend/web/uploads/category/' . $newimage, 264, 167)
//                            ->save(Yii::getAlias('../../frontend/web/uploads/category/' . $smallImage), ['quality' => 70]);

                    $model->base_url = \yii\helpers\Url::to('@frontendUrl') . '/uploads/category';
                    $path = time() . "." . $extension;
                    $model->fullimage = $newimage;
                }
            }
            if ($model->save()) {
                Yii::$app->getSession()->setFlash('alert', [
                    'body' => Yii::t('backend', 'Updated successful.'),
                    'options' => ['class' => 'alert-success']
                ]);
                return $this->redirect(['index']);
            }
        } else {

            return $this->render('update', [
                        'model' => $model,
                        'categories' => Jobcategory::find()->all(),
            ]);
        }
    }

    /**
     * Deletes an existing Page model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id) {
        $this->findModel($id)->delete();
        Yii::$app->getSession()->setFlash('alert', [
            'body' => Yii::t('backend', 'Deleted successful.'),
            'options' => ['class' => 'alert-success']
        ]);
        return $this->redirect(['index']);
    }

    public function actionJobCategoryOption() {
        $model = JobCategoryOption::find()->all();
        return $this->render('job-category-option', [
                    'model' => $model,
                    'categories' => Jobcategory::find()->all(),
        ]);
    }

    /**
     * Finds the Page model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Page the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = Jobcategory::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionServiceType() {
        $searchModel = new ServiceTypeSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('service-type', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    public function actionAddServiceType() {
        $model = new ServiceType();
        $this->performAjaxValidation($model);
        $parentCategory = Jobcategory::getParentCat();
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['service-type']);
        } else {
            return $this->render('add-service-type', [
                        'model' => $model,
                        'parentCategory' => $parentCategory,
            ]);
        }
    }

    public function actionUpdateServiceType($id) {
        $model = ServiceType::findOne($id);
        $this->performAjaxValidation($model);
        $parentCategory = Jobcategory::getParentCat();
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['service-type']);
        }
        return $this->render('update-service-type', [
                    'model' => $model,
                    'parentCategory' => $parentCategory,
        ]);
    }

    public function actionServiceTypeOption() {
        $searchModel = new ServiceTypeOptionSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('service-type-option', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    public function actionServiceOption() {
        $model = new ServiceTypeOption();
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->refresh();
        } else {
            $params = Yii::$app->request->get();
            $searchModel = new ServiceTypeOptionSearch();
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams, $params['serviceid']);
            return $this->render('service-option', [
                        'searchModel' => $searchModel,
                        'dataProvider' => $dataProvider,
                        'model' => $model,
                        'servicename' => ServiceType::findOne($params['serviceid']),
            ]);
        }
    }

    public function actionUpdateServiceOption($id) {
        $model = ServiceTypeOption::findOne($id);
        $this->performAjaxValidation($model);
        $params = Yii::$app->request->get();
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['service-option/?serviceid=' . $params['serviceid']]);
        }
        return $this->render('update-service-option', [
                    'model' => $model,
                    'servicename' => ServiceType::findOne($params['serviceid']),
        ]);
    }

    public function actionCatServiceOption() {
        $model = new ServiceTypeOption();
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->refresh();
        } else {
            $params = Yii::$app->request->get();
            $searchModel = new ServiceTypeOptionSearch();
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams, '', $params['catid'], $params['subcatid']);
            return $this->render('cat-service-option', [
                        'searchModel' => $searchModel,
                        'dataProvider' => $dataProvider,
                        'model' => $model,
                        'parentcatname' => Jobcategory::findOne($params['catid']),
                        'childcatname' => Jobcategory::findOne($params['subcatid']),
            ]);
        }
    }

    public function actionUpdateCatServiceOption($id) {
        $model = ServiceTypeOption::findOne($id);
        $this->performAjaxValidation($model);
        $params = Yii::$app->request->get();
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['/category/jobcategory/cat-service-option/?catid=' . $params['catid'] . '&subcatid=' . $params['subcatid'] . ' ']);
        }
        return $this->render('update-cat-service-option', [
                    'model' => $model,
                    'parentcatname' => Jobcategory::findOne($params['catid']),
                    'childcatname' => Jobcategory::findOne($params['subcatid']),
        ]);
    }

    public function actionDeleteCatOption($id, $catid, $subcatid) {
        $model = ServiceTypeOption::findOne($id)->delete();
        Yii::$app->getSession()->setFlash('alert', [
            'body' => Yii::t('backend', 'Deleted successful.'),
            'options' => ['class' => 'alert-success']
        ]);
        return $this->redirect(['/category/jobcategory/cat-service-option/?catid=' . $catid . '&subcatid=' . $subcatid . '']);
    }

}
