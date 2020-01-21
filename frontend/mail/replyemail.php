<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $user common\models\User */
/* @var $token string */

?>

<!DOCTYPE html>
<html>
<body>

<h3>Hello <?php echo Html::encode($userProfile->name) ?></h3>
<p>You have a enquiry Email</p>
<p>Name : <?php echo Html::encode($name) ?></p>
<p>Email : <?php echo Html::encode($email) ?></p>
<p>Mobile No. : <?php echo Html::encode($phone) ?></p>
<p>Message : <?php echo Html::encode($message) ?></p>
<p>Ad ID : <?php echo Html::encode($ad_id) ?></p>
<p>Ad Title : <?php echo Html::encode($title) ?></p>
<p>Category: <?php echo Html::encode($cat1) ?></p>
<p>Sub Category: <?php echo Html::encode($cat2) ?></p>

</body>
</html>









