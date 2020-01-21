<?php

namespace backend\controllers;

use common\components\AppHelper;
use common\models\AdsWarning;
use common\models\AdViews;
use common\models\PostAdCustomFields;
use common\models\PostAdImages;
use common\models\UserProfile;
use Yii;
use common\models\PostAd;
use backend\models\search\PostAdSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * PostAdController implements the CRUD actions for PostAd model.
 */
class PostAdController extends Controller
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
     * Lists all PostAd models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new PostAdSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->setSort([
            'defaultOrder' => ['id'=>SORT_DESC]
        ]);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionActive()
    {
        $searchModel = new PostAdSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams,PostAd::STATUS_ACTIVE);
        $dataProvider->setSort([
            'defaultOrder' => ['id'=>SORT_DESC]
        ]);
        return $this->render('active', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionPending()
    {
        $searchModel = new PostAdSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams,PostAd::STATUS_PENDING);
        $dataProvider->setSort([
            'defaultOrder' => ['id'=>SORT_DESC]
        ]);
        return $this->render('pending', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionHidden()
    {
        $searchModel = new PostAdSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams,PostAd::STATUS_HIDDEN);
        $dataProvider->setSort([
            'defaultOrder' => ['id'=>SORT_DESC]
        ]);
        return $this->render('hidden', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionResubmitted()
    {
        $searchModel = new PostAdSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams,PostAd::STATUS_RESUBMITTED);
        $dataProvider->setSort([
            'defaultOrder' => ['id'=>SORT_DESC]
        ]);
        return $this->render('resubmitted', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionModificationRequired()
    {
        $searchModel = new PostAdSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams,PostAd::STATUS_WARNING);
        $dataProvider->setSort([
            'defaultOrder' => ['id'=>SORT_DESC]
        ]);
        return $this->render('modification-required', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionRejected()
    {
        $searchModel = new PostAdSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams,PostAd::STATUS_REJECTED);
        $dataProvider->setSort([
            'defaultOrder' => ['id'=>SORT_DESC]
        ]);
        return $this->render('rejected', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionExpire()
    {
        $searchModel = new PostAdSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams,PostAd::STATUS_EXPIRE);
        $dataProvider->setSort([
            'defaultOrder' => ['id'=>SORT_DESC]
        ]);
        return $this->render('expire', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single PostAd model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $model=$this->findModel($id);
        $advertiser = $model->user_id;
        $advertiser_detail = UserProfile::getUserDetail($advertiser);
        $postAd=AppHelper::getPostAdDetail($id);

        $ad_views_count = AdViews::AdViewsCountByAdId($id);

        $ads_images=PostAdImages::find()->where(['ad_id'=>$id])->all();

        $custom_field_ids = (new \yii\db\Query())
            ->select(['id','ad_id','field_id','value'])
            ->from('post_ads_custom_fields	')
            ->where(['ad_id' => $id])
            ->all();
        // echo "<pre>"; print_r($custom_field_ids);exit;

        $b = 1;
        $ArrCustomFields = array();
        foreach($custom_field_ids as $key=>$custom_field_id){
            $custom_fields = (new \yii\db\Query())
                ->select(['custom_fields.id','custom_fields.field_type_id','custom_fields.label','custom_fields.isRequired','custom_field_types.id as custom_type_id','custom_field_types.type'])
                ->from('custom_fields')
                ->leftjoin('custom_field_types', 'custom_fields.field_type_id = custom_field_types.id')
                ->where(['custom_fields.id' => $custom_field_id['field_id']])
                ->all();
            $custom_field_id['custom_fields']	 = $custom_fields[0];
            $ArrCustomFields[$b] = $custom_field_id;
            $b++;
        }

        //echo "<pre>"; print_r($ArrCustomFields);exit;

        if(isset($ArrCustomFields)){
            $ArrCustomFields = $ArrCustomFields;
        }
        else{
            $ArrCustomFields = array();
        }

       // echo "<pre>"; print_r($adCustomFields);die;

        return $this->render('view', [
            'postAd' => $postAd,'advertiser_detail'=>$advertiser_detail,
            'ad_views_count'=>$ad_views_count,'adCustomFields'=>$ArrCustomFields,
            'ads_images'=>$ads_images
        ]);
    }

    /**
     * Updates an existing PostAd model.
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

    public function actionMarkapprove() {
        $post = Yii::$app->request->post();
        $id = $post['id'];
        $model = PostAd::findOne($id);
        $model->status = PostAd::STATUS_ACTIVE;
        if ($model->save()) {
            //send mail
        }
        return $this->redirect(Yii::$app->request->referrer);
    }

    /*
     * Select Warn reason type option
     */

    public function actionWarnmessage() {
        $model = new \common\models\WarningReasons();
        $reason_array = array('Please Select');
        if (Yii::$app->request->post()) {
            $post = Yii::$app->request->post();
            $value = $post['value'];

            $reasons = $model->find()->where(['type' => $value])->all();

            foreach ($reasons as $reason) {
                $reason_array[$reason['options']] = $reason['options'];
            }
        }
        echo json_encode($reason_array);
        die;
    }

    /*
     * Required modification or Denied message
     */

    public function actionRequest() {
        $post = Yii::$app->request->post();
        if (isset($post['postid']) && $post['reasonmodification'] != '') {
            $sharePageStatus = PostAd::STATUS_WARNING;
            $updateStatus = PostAd::findOne($post['postid']);
            $updateStatus->status = $sharePageStatus;
            if ($updateStatus->save()) {
                $userDetail = UserProfile::getUserDetail($updateStatus['user_id']);
                $FullMessage = $this->Message($post['warn_reason'], $post['warn_reason_next'], $post['reasonmodification']);
                //$subject = 'Admin Warn ' . $userDetail['firstname'] . ' about ' . $updateStatus['title'];
                $warning=new AdsWarning();
                $warning->ad_id=$updateStatus['id'];
                $warning->message=$FullMessage;
                $warning->save();
            }
            return $this->redirect(Yii::$app->request->referrer);
        }
    }

    /**
     * Deletes an existing PostAd model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $model= $this->findModel($id);
        $model->status=PostAd::STATUS_REJECTED;
        $model->save();

        return $this->redirect(Yii::$app->request->referrer);
    }

    /**
     * Finds the PostAd model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return PostAd the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = PostAd::findOne($id)) !== null) {
            return $model;
        }
        throw new NotFoundHttpException('The requested page does not exist.');
    }

    /*
    * Use Message format
    */

    public function Message($WarnReason = NULL, $WarnReasonNext = NULL, $description = NULL) {
        $Message = '';
        if (!empty($WarnReason)) {
            $Reason1 = AppHelper::get_warning_label($WarnReason);
            $Reason2 = $WarnReasonNext;
            $Message .= "<li>$Reason1</li><li>$Reason2</li>";
        }
        if (!empty($description)) {
            $Message .= "<li>$description</li>";
        }
        return $Message;
    }
}
