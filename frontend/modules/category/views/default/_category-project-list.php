<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

use yii\helpers\Html;
use yii\helpers\Url;
use frontend\modules\gloomme\jobcategory\models\Jobcategory;
use yii\helpers\BaseStringHelper;
use common\models\ProjectBudget;
use common\models\Currencies;
use yii\widgets\LinkPager;
use kartik\select2\Select2;
use common\models\ProjectBids;

$this->title = Yii::t('frontend', 'Project List');
$lang = substr(\Yii::$app->language, 0, 2);
$skillsData = Jobcategory::find()->where('parentid != 0')->all();
$skillsArray = array();
foreach ($skillsData as $key => $skillsData) {
    $skillsArray[$skillsData['id']] = $skillsData['title_' . $lang];
}
//$getCategoryId = Jobcategory::find()->where(['slug' => $slug])->one();
//$values = array(1, 2, 3, 4, 5);
// $average = array_sum($values) / count($values);
// echo round($average);
?>
<div class="find_worktextbox">
    <div class="col-sm-9">
        <h2><a href="<?php echo \Yii::$app->getUrlManager()->getBaseUrl() . '/user/project-detail/' . $model['slug'] ?>"><?php echo $model['title'] ?></a> </h2>
        <p> <?php echo BaseStringHelper::truncate($model['description'], 299, '...') ?> </p>
        <ul>
            <?php
            $getProjectBudget = ProjectBudget::find()->where(['id' => $model['projectBudgetId']])->one();
            $getCurrency = Currencies::find()->where(['id' => $getProjectBudget['currencyId']])->one();
            $checkTotalBid = ProjectBids::find()->where(['projectid' => $model['id']])->count();

            $skillsId = explode(',', $model['subCategoryId']);
            $getSkills = array();
            foreach ($skillsId as $skillsData) {
                $getSkills[] = Jobcategory::find()->where(['id' => $skillsData])->one();
            }
            $myArray = array();
            foreach ($getSkills as $dataSkills) {
                $myArray[] = '<li><a href="' . \Yii::$app->getUrlManager()->getBaseUrl() . '/category?JobSearch[title]=&JobSearch[subCategoryId]=&JobSearch[subCategoryId][]=' . $dataSkills['id'] . '">' . $dataSkills['title_' . $lang] . '</a> </li>';
            }
            echo implode(', ', $myArray);
            ?>
        </ul>
    </div>
    <div class="col-sm-3">
        <h2> <?php echo $getCurrency['currenciesSymbol'] . ' ' . $getProjectBudget['minBudget'] . ' - ' . $getCurrency['currenciesSymbol'] . ' ' . $getProjectBudget['maxBudget'] . ' ' . $getCurrency['currenciesName'] ?></h2>
        <p><?php echo $checkTotalBid . ' ' . Yii::t('frontend', 'Bid ') ?></p>
    </div>
</div>




