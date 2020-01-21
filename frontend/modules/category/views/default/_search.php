<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use kartik\select2\Select2;
use frontend\modules\gloomme\jobcategory\models\Jobcategory;
use common\models\Currencies;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model backend\models\search\ArticleSearch */
/* @var $form yii\bootstrap\ActiveForm */

$skills = Jobcategory::find()->where('parentid != 0')->all();

$currencyData = Currencies::find()->where(1)->all();
$listData = ArrayHelper::map($currencyData, 'id', 'currenciesName');
$lang = substr(\Yii::$app->language, 0, 2);
$skillsArray = array();
foreach ($skills as $key => $skillsData) {
    $skillsArray[$skillsData['id']] = $skillsData['title_' . $lang];
}
?>
<div class="col-sm-12">
    <div class="top_searchbar">
        <?php $form = ActiveForm::begin(['id' => '', 'method' => 'get', 'options' => ['role' => 'form'], 'fieldConfig' => ['options' => ['tag' => 'div']]]); ?>
        <?php echo $form->field($model, 'title', ['template' => '{input}{error}', 'inputOptions' => ['placeholder' => Yii::t('frontend', 'Search for projects'), 'class' => 'inpt_field']]) ?>
        <?php
        echo $form->field($model, 'subCategoryId')->widget(Select2::classname(), [
            'data' => $skillsArray,
            'language' => 'en',
            'options' => ['placeholder' => 'e.g. php, css, java', 'multiple' => true, 'id' => 'skills'],
            'toggleAllSettings' => [
                'selectLabel' => '',
                'unselectLabel' => '',
                'selectOptions' => ['class' => 'text-success'],
                'unselectOptions' => ['class' => 'text-danger'],
            ],
            'pluginOptions' => [
                'tags' => true,
                'tokenSeparators' => [',', ' '],
                'maximumInputLength' => 10
            ],
        ])->label(false);
        ?>                    
        <?php echo Html::submitButton(Yii::t('frontend', 'Search'), ['class' => 'serachpro']) ?>
        <?php ActiveForm::end(); ?>
    </div>
</div>
