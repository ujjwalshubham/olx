

Home
Help
Application

CRUD Generator
Model Generator
Controller Generator
Form Generator
Module Generator
Extension Generator
CRUD Generator

This generator generates a controller and views that implement CRUD (Create, Read, Update, Delete) operations for the specified data model.
Model Class
Search Model Class
Controller Class
View Path
Base Controller Class
yii\web\Controller
Widget Used in Index Page
GridView
Enable I18N
Enable Pjax
Code Template
yii2-starter-kit (/var/www/html/olx/backend/views/_gii/templates)

Click on the above Generate button to generate the files selected below:
Code File 	Action
controllers/CustomFieldsController.php 	create
models/search/CustomFieldsSearch.php 	create
backend/views/custom-fields/_form.php 	create
backend/views/custom-fields/_search.php 	create
backend/views/custom-fields/create.php 	create
backend/views/custom-fields/index.php 	create
backend/views/custom-fields/update.php 	create
backend/views/custom-fields/view.php 	create

backend/views/custom-fields/view.php
CTRL+C to copy
<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\CustomFields */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Custom Fields', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="custom-fields-view">

    <p>
        <?php echo Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?php echo Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?php echo DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'field_type_id',
            'label',
            'status',
            'created_at',
            'updated_at',
            'created_by',
            'updated_by',
        ],
    ]) ?>

</div>

A Product of Yii Software LLC

Powered by Yii Framework
Yii
2.0.15.1 PHP 7.2.19-0ubuntu0.18.04.2
Status 200 Route gii/default/view
Log 37
Time 33 ms Memory 2.781 MB
DB 12 3 ms
Events 52
Asset Bundles 8
User 1
