<?php

namespace frontend\modules\gloomme\jobcategory\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use frontend\modules\gloomme\jobcategory\models\Jobcategory;
use frontend\models\search\JobSearch;

class DefaultController extends Controller {

    public function behaviors() {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    public function actionIndex() {
        return $this->render('index');
    }

    public function actionCategory() {
        $skillsData = Jobcategory::find()->where('parentid != 0')->all();
        $searchModel = new JobSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->pagination->pageSize = 10;
        return $this->render('category', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
                    'skills' => $skillsData,
        ]);
    }

}
