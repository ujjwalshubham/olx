<?php

/**
 * @var $this \yii\web\View
 * @var $url \common\models\User
 */
use yii\helpers\Url;
use yii\helpers\Html;
use common\models\User;

$session = Yii::$app->session;
$userid = $session->get('USERID');
$userDeatil = User::getUserDetails($userid);
if ($userDeatil) {
    $userName = $userDeatil['username'];
} else {
    $userName = 'Easy-Com User';
}
/* @var $this yii\web\View */
/* @var $user common\models\User */
/* @var $token string */
?>
<table align="center" class="body-wrap" style="background: #e0e0e0; box-sizing: border-box; font-family: Roboto,sans-serif; margin: 0; padding: 0; width: 100%; word-break: break-word" bgcolor="#e0e0e0">
    <tr style="box-sizing: border-box; font-family: Roboto,sans-serif; margin: 0; padding: 0">
        <td align="center" style="box-sizing: border-box; font-family: Roboto,sans-serif; margin: 0 auto; padding: 0; vertical-align: top" valign="top">
            <table style="box-sizing: border-box; font-family: Roboto,sans-serif; margin: 0; padding: 0">
                <tr style="box-sizing: border-box; font-family: Roboto,sans-serif; margin: 0; padding: 0">
                    <td class="container" width="600" style="box-sizing: border-box; clear: both !important; font-family: Roboto,sans-serif; margin: 0 auto;  padding: 0; vertical-align: top" valign="top">
                        <div class="content" style="box-sizing: border-box; display: block; font-family: Roboto,sans-serif; margin: 0 auto; width: 800px; padding: 20px">
                            <table class="main" width="100%" cellpadding="0" cellspacing="0" style="background: #FFFFFF; box-sizing: border-box; font-family: Roboto,sans-serif; margin: 0; padding: 0" bgcolor="#FFFFFF">
                                <tr><td style="text-align: center; padding: 15px 20px 20px;border-bottom: 3px solid #0C4DA1;"><img src="<?php echo Url::to('@frontendUrl') ?>/images/logo.png" alt=""></td></tr>
                                <tr style="box-sizing: border-box; font-family: Roboto,sans-serif; margin: 0; padding: 0">
                                    <td class="content-wrap" style="box-sizing: border-box; font-family: Roboto,sans-serif; margin: 0 auto; padding: 0 20px 20px; vertical-align: top" valign="top">
                                        <table width="100%" cellpadding="0" cellspacing="0" style="box-sizing: border-box; font-family: Roboto,sans-serif; margin: 0; padding: 0">
                                            <tr style="box-sizing: border-box; font-family: Roboto,sans-serif; margin: 0; padding: 0">
                                                <td class="content-block" style="box-sizing: border-box; color: #2e2e2e; font-family: Roboto,sans-serif; font-size: 14px; line-height: 1.7; margin: 0 auto; padding: 20px 0;">Hello</td>
                                            </tr>
                                            <tr style="box-sizing: border-box; font-family: Roboto,sans-serif; margin: 0; padding: 0">
                                                <td class="content-block" style="box-sizing: border-box; color: #2e2e2e; font-family: Roboto,sans-serif; font-size: 16px; line-height: 1.7; margin: 0 auto; padding: 20px 0; vertical-align: top" valign="top">

                                                    <div style="box-sizing: border-box; font-family: Roboto,sans-serif; margin: 0; padding: 0"><?php echo Yii::t('frontend', 'New user registration on Gloomme.') ?></div>
                                                    <div style="box-sizing: border-box; font-family: Roboto,sans-serif; margin: 0; padding: 0"><?php echo Yii::t('frontend', 'Username') . ' ' . $userName ?></div>
                                                    <div style="box-sizing: border-box; font-family: Roboto,sans-serif; margin: 0; padding: 0"><?php echo Yii::t('frontend', 'Email') . ' ' . $userDeatil['email'] ?></div>
                                                    
                                                    <div class="wysiwyg-text-align-center" style="box-sizing: border-box; font-family: Roboto,sans-serif; margin: 0; padding: 0; text-align: center !important" align="center">
                                                        <br style="box-sizing: border-box; font-family: Roboto,sans-serif; margin: 0; padding: 0" />
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr style="box-sizing: border-box; font-family: Roboto,sans-serif; margin: 0; padding: 0">
                                                <td class="content-block" style="box-sizing: border-box; color: #2e2e2e; font-family: Roboto,sans-serif; font-size: 16px; line-height: 1.7; margin: 0 auto; padding: 20px 0; vertical-align: top" valign="top">
                                            <center style="box-sizing: border-box; font-family: Roboto,sans-serif; margin: 0; padding: 0">
                                                <table width="300" style="box-sizing: border-box; font-family: Roboto,sans-serif; margin: 0 auto; padding: 0">
                                                    <tr style="box-sizing: border-box; font-family: Roboto,sans-serif; margin: 0; padding: 0">
                                                        <td class="divider" style="background: none; border-color: #EA1266; border-style: solid; border-width: 2px 0 0; box-sizing: border-box; font-family: Roboto,sans-serif; font-size: 1px; height: 1px; line-height: 1; margin: 0px; padding: 0; vertical-align: top; width: 100%" valign="top"> 
                                                        </td>
                                                    </tr>
                                                </table>
                                            </center>

                                    </td>
                                </tr>
                                <tr style="box-sizing: border-box; font-family: Roboto,sans-serif; margin: 0; padding: 0">
                                    <td class="content-block" style="box-sizing: border-box; color: #2e2e2e; font-family: Roboto,sans-serif; font-size: 16px; line-height: 1.7; margin: 0 auto; padding: 20px 0; vertical-align: top" valign="top">
                                        <div style="box-sizing: border-box; font-family: Roboto,sans-serif; margin: 0; padding: 0"><br style="box-sizing: border-box; font-family: Roboto,sans-serif; margin: 0; padding: 0" /></div>
                                        <div style="box-sizing: border-box; font-family: Roboto,sans-serif; margin: 0; padding: 0"><?php echo Yii::t('frontend', 'Thank you!') ?></div>
                                        <div style="box-sizing: border-box; font-family: Roboto,sans-serif; margin: 0; padding: 0"><?php echo Yii::t('frontend', 'Gloomme Team') ?></div>
                                    </td>
                                </tr>
                            </table>
                    </td>
                </tr>
                <tr style="box-sizing: border-box; font-family: Roboto,sans-serif; margin: 0; padding: 0">
                    <td class="aligncenter mailer-info" style="background: #F5F5F5; border-top-color: #DBDADA; border-top-style: solid; border-top-width: 1px; box-sizing: border-box; color: #575454; font-family: Roboto,sans-serif; font-size: 12px; font-weight: normal; line-height: 1.7; margin: 0 auto; padding: 15px 20px 20px; text-align: center; vertical-align: top" align="center" bgcolor="#F5F5F5" valign="top">
                        <h2 class="wysiwyg-text-align-center" style="box-sizing: border-box; color: rgb(46, 46, 46); font-family: Roboto,sans-serif; font-size: 18px; font-weight: bold; line-height: 1.7; margin: 0; padding: 0; text-align: center !important" align="center"><?php echo Yii::t('frontend', 'Gloomme.com') ?></h2>

                    </td>
                </tr> 
            </table> 
            </div>
        </td>
    </tr>
</table>
</td>
</tr>
</table>
