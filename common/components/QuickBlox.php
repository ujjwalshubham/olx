<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace common\components;

use Yii;
use common\models\User;

class QuickBlox {

    const QUICKBLOX_APPLICATION_ID = 79122;
    const QUICKBLOX_AUTH_KEY = 'jnNzXxfxzsud4TZ';
    const QUICKBLOX_AUTHSECRET = 'smj34VbyQVRYh7-';
    const QUICKBLOX_API_URL = 'https://api.quickblox.com';

    public static function quickboxToken() {
        // server
        $application_id = self::QUICKBLOX_APPLICATION_ID;
        $auth_key = self::QUICKBLOX_AUTH_KEY;
        $authSecret = self::QUICKBLOX_AUTHSECRET;
        $nonce = rand();
        $timestamp = time();


        $stringForSignature = "application_id=" . $application_id . "&auth_key=" . $auth_key . "&nonce=" . $nonce . "&timestamp=" . $timestamp;
        $signature = hash_hmac('sha1', $stringForSignature, $authSecret);

        $post_body = http_build_query(
                array(
                    'application_id' => $application_id,
                    'auth_key' => $auth_key,
                    'nonce' => $nonce,
                    'signature' => $signature,
                    'timestamp' => $timestamp,
                )
        );

        $tokenCurl = curl_init();
        curl_setopt($tokenCurl, CURLOPT_URL, self::QUICKBLOX_API_URL . '/session.json');
        curl_setopt($tokenCurl, CURLOPT_POST, true);
        curl_setopt($tokenCurl, CURLOPT_POSTFIELDS, $post_body);
        curl_setopt($tokenCurl, CURLOPT_RETURNTRANSFER, true);
        $responce = curl_exec($tokenCurl);
        // Check errors
        if ($responce) {
            $responceDecode = json_decode($responce);
            $token = $responceDecode->session->token;
            return $token;
        } else {
            $error = curl_error($tokenCurl) . '(' . curl_errno($tokenCurl) . ')';
            return true;
        }
        // Close connection
        curl_close($tokenCurl);
    }

    public static function quickboxTokenUser($quickBloxID) {
        // server
        $application_id = self::QUICKBLOX_APPLICATION_ID;
        $auth_key = self::QUICKBLOX_AUTH_KEY;
        $authSecret = self::QUICKBLOX_AUTHSECRET;
        $nonce = rand();
        $timestamp = time();

        if ($quickBloxID != '') {
            $userDetail = \common\models\UserProfile::getQuickBloxUser($quickBloxID);
            $stringForSignature = "application_id=" . $application_id . "&auth_key=" . $auth_key . "&nonce=" . $nonce . "&timestamp=" . $timestamp . "&user[login]=" . $userDetail['quickblox_username'] . "&user[password]=" . $userDetail['quickblox_password'];

            $signature = hash_hmac('sha1', $stringForSignature, $authSecret);

            $post_body = http_build_query(array(
                'application_id' => $application_id,
                'auth_key' => $auth_key,
                'timestamp' => $timestamp,
                'nonce' => $nonce,
                'signature' => $signature,
                'user[login]' => $userDetail['quickblox_username'],
                'user[password]' => $userDetail['quickblox_password']
            ));
        } else {
            $stringForSignature = "application_id=" . $application_id . "&auth_key=" . $auth_key . "&nonce=" . $nonce . "&timestamp=" . $timestamp;
            $signature = hash_hmac('sha1', $stringForSignature, $authSecret);

            $post_body = http_build_query(
                    array(
                        'application_id' => $application_id,
                        'auth_key' => $auth_key,
                        'nonce' => $nonce,
                        'signature' => $signature,
                        'timestamp' => $timestamp,
                    )
            );
        }
        $tokenCurl = curl_init();
        curl_setopt($tokenCurl, CURLOPT_URL, self::QUICKBLOX_API_URL . '/session.json');
        curl_setopt($tokenCurl, CURLOPT_POST, true);
        curl_setopt($tokenCurl, CURLOPT_POSTFIELDS, $post_body);
        curl_setopt($tokenCurl, CURLOPT_RETURNTRANSFER, true);
        $responce = curl_exec($tokenCurl);
        $responceDecode = json_decode($responce);
        //echo '<pre>'; print_r($responce);die;
        // Check errors
        if ($responce && isset($responceDecode->session)) {
            $responceDecode = json_decode($responce);
            $token = $responceDecode->session->token;
            return $token;
        } else {
            $error = curl_error($tokenCurl) . '(' . curl_errno($tokenCurl) . ')';
            return true;
        }
        // Close connection
        curl_close($tokenCurl);
    }

    public function quickboxSinupUser($userid) {
        $token = self::quickboxToken();  // genrate quickblox session
        $userData = User::getUserDetails($userid);
		//echo "<pre>";print_r($userData);exit;
        $post_body = http_build_query(
                array(
                    'user' => array(
                        'login' => $userData['username'],
                        'password' => $userData['quickblox_password'],
                        'email' => $userData['email'],
                        'external_user_id' => $userData['id'],
                        'facebook_id' => '',
                        'twitter_id' => '',
                        'full_name' => $userData['name'],
                        'phone' => '',
                        'website' => '',
                        'tag_list' => '',
                    )
                )
        );

        $signUpCurl = curl_init();
        curl_setopt($signUpCurl, CURLOPT_URL, self::QUICKBLOX_API_URL . '/users.json');
        curl_setopt($signUpCurl, CURLOPT_HTTPHEADER, array("QB-Token: " . $token));
        curl_setopt($signUpCurl, CURLOPT_POST, true);
        curl_setopt($signUpCurl, CURLOPT_POSTFIELDS, $post_body);
        curl_setopt($signUpCurl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($signUpCurl, CURLOPT_SSL_VERIFYPEER, FALSE);

        $responce = curl_exec($signUpCurl);
        //print_r($responce);die;
        // Check errors
        if ($responce) {
            $responceDecode = json_decode($responce);
            if ((!isset($responceDecode->errors))) { //updated code
                $quickblox_id = $responceDecode->user->id;
                //update quickblox id
                User::updateAll(['quickblox_id' => $quickblox_id, 'quickblox_username' => $userData['username']], 'id= "' . $userid . '" ');
                return true;
            } else {
                return false;
            }//updated code ends
        } else {
            $error = curl_error($signUpCurl) . '(' . curl_errno($signUpCurl) . ')';
            return true;
        }

        // Close connection
        curl_close($signUpCurl);
    }

    public function quickboxCreategroup($occ_ids = null, $postslug = NULL, $user_ids = NULL) {
        $orderDetail = new \frontend\modules\gloomme\gigs\models\GigsOrder();
        $token = self::quickboxTokenUser($occ_ids);  // genrate quickblox session
        if ($postslug != NULL) {
            $orderData = $orderDetail->find()->where(['orderID' => $postslug])->one();
            $gigUserData = User::getUserDetails($orderData['giguserid']);
            $postocc_ids = $gigUserData['quickblox_id'];
        } else {
            $gigUserData = User::getUserDetails($user_ids);
            $postocc_ids = $gigUserData['quickblox_id'];
            $postslug = 'test';
        }

        $userData = User::getUserDetails(Yii::$app->user->id);

        $post_body = array(
            'type' => 3,
            'name' => $gigUserData['username'] . ',' . $userData['username'],
            'occupants_ids' => $postocc_ids . ',' . $occ_ids,
            "data" => ["class_name" => 'post', "slug" => $postslug]
        );
        $postDAta = json_encode($post_body);
        //print_r($postDAta);die;

        $groupCurl = curl_init();
        curl_setopt($groupCurl, CURLOPT_URL, self::QUICKBLOX_API_URL . '/chat/Dialog.json');
        curl_setopt($groupCurl, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'Content-Length: ' . strlen($postDAta),
            "QB-Token: " . $token
                )
        );
        curl_setopt($groupCurl, CURLOPT_POST, true);
        curl_setopt($groupCurl, CURLOPT_POSTFIELDS, $postDAta);
        curl_setopt($groupCurl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($groupCurl, CURLOPT_SSL_VERIFYPEER, FALSE);

        $responce = curl_exec($groupCurl);
        if ($responce) {
            $responceDecode = json_decode($responce);
            //echo '<pre>'; print_r($responceDecode);die;
            if ((!isset($responceDecode->errors))) { //updated code
                return true;
            } else {
                return false;
            }//updated code ends
        } else {
            $error = curl_error($groupCurl) . '(' . curl_errno($groupCurl) . ')';
            return true;
        }
        // Close connection
        curl_close($groupCurl);
    }

    public function quickboxUpdateBlob2($userId, $imageType, $imageName) {
        $userData = \common\models\UserProfile::getUserDetail($userId);
        $token = self::quickboxTokenUser($userData['quickblox_id']);  // genrate quickblox session

        $post_body = array(
            'blob' => array(
                'content_type' => $imageType,
                'name' => $imageName,
                'public' => 'true'
            )
        );
        $fields_string = http_build_query($post_body);
        //echo $token;die;
        $updateCurl = curl_init();
        curl_setopt($updateCurl, CURLOPT_URL, self::QUICKBLOX_API_URL . '/blobs.json');
        curl_setopt($updateCurl, CURLOPT_HTTPHEADER, array("QB-Token: " . $token));
        curl_setopt($updateCurl, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($updateCurl, CURLOPT_POSTFIELDS, $fields_string);
        curl_setopt($updateCurl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($updateCurl, CURLOPT_SSL_VERIFYPEER, FALSE);

        $responce = curl_exec($updateCurl);
        $responceDecode = json_decode($responce);

        if ($responce) {
            $responceDecode = json_decode($responce);
            self::quickbloxUploadImage($responceDecode->blob, $imageName);

            if ((!isset($responceDecode->errors))) { //updated code
                return true;
            } else {
                return false;
            }//updated code ends
        } else {
            $error = curl_error($updateCurl) . '(' . curl_errno($updateCurl) . ')';
            return true;
        }

        // Close connection
        curl_close($updateCurl);
    }

    public function quickboxUpdateBlob($userId, $imageType, $imageName, $imagSize) {
        $userData = \common\models\UserProfile::getUserDetail($userId);
        $token = self::quickboxTokenUser($userData['quickblox_id']);  // genrate quickblox session

        $post_body = array(
            'blob' => array(
                'content_type' => $imageType,
                'name' => $imageName,
                'public' => 'true'
            )
        );
        $fields_string = http_build_query($post_body);
        $updateCurl = curl_init();
        curl_setopt($updateCurl, CURLOPT_URL, self::QUICKBLOX_API_URL . '/blobs.json');
        curl_setopt($updateCurl, CURLOPT_HTTPHEADER, array("QB-Token: " . $token));
        curl_setopt($updateCurl, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($updateCurl, CURLOPT_POSTFIELDS, $fields_string);
        curl_setopt($updateCurl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($updateCurl, CURLOPT_SSL_VERIFYPEER, FALSE);

        $responce = curl_exec($updateCurl);
        if ($responce) {
            $responceDecode = json_decode($responce);
            echo '<pre>';
            print_r($responceDecode);
            self::quickbloxUploadImage($responceDecode->blob, $imageName);
            self::imageUploadComplete($userData['quickblox_id'], $responceDecode->blob, $imagSize);
            self::updateUserBlob($userData['quickblox_id'], $responceDecode->blob);
            die;
            if ((!isset($responceDecode->errors))) { //updated code
                return true;
            } else {
                return false;
            }//updated code ends
        } else {
            $error = curl_error($updateCurl) . '(' . curl_errno($updateCurl) . ')';
            return true;
        }
    }

    public static function quickbloxUploadImage($responceBlob, $imageName) {
        $blobResponce = preg_split('/(&|=)/', $responceBlob->blob_object_access->params);
        $imgUrl = '@'.\yii\helpers\Url::to('@webroot') . '/uploads/user/photo/' . $imageName;

        $post_body = array(
            'Content-Type' => urldecode($blobResponce[1]),
            'Expires' => urldecode($blobResponce[3]),
            'acl' => "public-read",
            'key' => $blobResponce[7],
            'policy' => urldecode($blobResponce[9]),
            'success_action_status' => $blobResponce[11],
            'x-amz-algorithm' => $blobResponce[13],
            'x-amz-credential' => urldecode($blobResponce[15]),
            'x-amz-date' => $blobResponce[17],
            'x-amz-signature' => $blobResponce[19],
            'file' => $imgUrl
        );

        $updateCurl = curl_init();
        curl_setopt($updateCurl, CURLOPT_URL, 'https://s3.amazonaws.com/qbprod');
        curl_setopt($updateCurl, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($updateCurl, CURLOPT_POSTFIELDS, $post_body);
        curl_setopt($updateCurl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($updateCurl, CURLOPT_SSL_VERIFYPEER, FALSE);

        $responce = curl_exec($updateCurl);
        $responceDecode = json_decode($responce);
        echo '<pre>';
        print_r($responce);

        //$fileInfo = self::getImageDetail($responceBlob->id);
    }

    public static function imageUploadComplete($quickblox_id, $responceBlob, $imagSize) {
        $token = self::quickboxTokenUser($quickblox_id);  // genrate quickblox session
        $post_body = array(
            'size' => $imagSize,
        );
        $fields_string = json_encode($post_body);
        $userLogoutCurl = curl_init();
        curl_setopt($userLogoutCurl, CURLOPT_URL, self::QUICKBLOX_API_URL . '/blobs/' . $responceBlob->id . '/complete.json');
        curl_setopt($userLogoutCurl, CURLOPT_HTTPHEADER, array("QB-Token: " . $token));
        curl_setopt($userLogoutCurl, CURLOPT_CUSTOMREQUEST, "PUT");
        curl_setopt($userLogoutCurl, CURLOPT_POSTFIELDS, $post_body);
        curl_setopt($userLogoutCurl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($userLogoutCurl, CURLOPT_SSL_VERIFYPEER, FALSE);

        $responce = curl_exec($userLogoutCurl);
        echo '<pre>';
        print_r($responce);
        // Close connection
        curl_close($userLogoutCurl);
    }

    public static function updateUserBlob($quickblox_id, $responceBlob) {
        $token = self::quickboxTokenUser($quickblox_id);  // genrate quickblox session
        $post_body = array(
            'user' => array(
                'blob_id' => $responceBlob->id,
                'custome_data' => ["status" => "test", "avatar_url" => "https://api.quickblox.com/blobs/" . $responceBlob->uid . " "],
                'full_name' => 'Hitesh Shrimali'
            )
        );
        $fields_string = http_build_query($post_body);
        $userLogoutCurl = curl_init();
        curl_setopt($userLogoutCurl, CURLOPT_URL, self::QUICKBLOX_API_URL . '/users/' . $quickblox_id . '.json');
        curl_setopt($userLogoutCurl, CURLOPT_HTTPHEADER, array("QB-Token: " . $token));
        curl_setopt($userLogoutCurl, CURLOPT_CUSTOMREQUEST, "PUT");
        curl_setopt($userLogoutCurl, CURLOPT_POSTFIELDS, $fields_string);
        curl_setopt($userLogoutCurl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($userLogoutCurl, CURLOPT_SSL_VERIFYPEER, FALSE);

        $responce = curl_exec($userLogoutCurl);
        echo '<pre>';
        print_r($responce);
        // Close connection
        curl_close($userLogoutCurl);
    }

    public static function getImageDetail($blobID) {
        $token = self::quickboxToken();  // genrate quickblox session
        $userLogoutCurl = curl_init();
        curl_setopt($userLogoutCurl, CURLOPT_URL, self::QUICKBLOX_API_URL . '/blobs/' . $blobID . '.json');
        curl_setopt($userLogoutCurl, CURLOPT_HTTPHEADER, array("QB-Token: " . $token));
        curl_setopt($userLogoutCurl, CURLOPT_CUSTOMREQUEST, "GET");
        curl_setopt($userLogoutCurl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($userLogoutCurl, CURLOPT_SSL_VERIFYPEER, FALSE);

        $responce = curl_exec($userLogoutCurl);
        echo '<pre>';
        print_r($responce);
        die;
        // Close connection
        curl_close($userLogoutCurl);
    }

    public static function quickboxGetSession($quickblox_id) {
        $token = self::quickboxTokenUser($quickblox_id);  // genrate quickblox session
        $getSessionCurl = curl_init();
        curl_setopt($getSessionCurl, CURLOPT_URL, self::QUICKBLOX_API_URL . '/session.json');
        curl_setopt($getSessionCurl, CURLOPT_HTTPHEADER, array("QB-Token: " . $token));
        curl_setopt($getSessionCurl, CURLOPT_CUSTOMREQUEST, "GET");
        //curl_setopt($updateCurl, CURLOPT_POSTFIELDS, $post_body);
        curl_setopt($getSessionCurl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($getSessionCurl, CURLOPT_SSL_VERIFYPEER, FALSE);

        $responce = curl_exec($getSessionCurl);
        $responceDecode = json_decode($responce);

        if ($responce) {
            $responceDecode = json_decode($responce);
            //echo '<pre>';print_r($responceDecode);die;
            if ((!isset($responceDecode->errors))) { //updated code
                return $responceDecode;
            } else {
                return false;
            }//updated code ends
        } else {
            $error = curl_error($getSessionCurl) . '(' . curl_errno($getSessionCurl) . ')';
            return true;
        }

        // Close connection
        curl_close($getSessionCurl);
    }

    public function quickboxUserLogin($quickblox_id) {
        $token = self::quickboxTokenUser($quickblox_id);  // genrate quickblox session

        $userDetail = \common\models\UserProfile::getQuickBloxUser($quickblox_id);
        $post_body = http_build_query(array(
            'login' => $userDetail['quickblox_username'],
            'password' => $userDetail['quickblox_password']
        ));
        $userLoginCurl = curl_init();
        curl_setopt($userLoginCurl, CURLOPT_URL, self::QUICKBLOX_API_URL . '/login.json');
        curl_setopt($userLoginCurl, CURLOPT_HTTPHEADER, array("QB-Token: " . $token));
        curl_setopt($userLoginCurl, CURLOPT_POST, true);
        curl_setopt($userLoginCurl, CURLOPT_POSTFIELDS, $post_body);
        curl_setopt($userLoginCurl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($userLoginCurl, CURLOPT_SSL_VERIFYPEER, FALSE);

        $responce = curl_exec($userLoginCurl);
        if ($responce) {
            $responceDecode = json_decode($responce);
            $getSession = self::quickboxGetSession($responceDecode->user->id);  // genrate quickblox session
            if ((!isset($responceDecode->errors))) { //updated code
                return true;
            } else {
                return false;
            }//updated code ends
        } else {
            $error = curl_error($userLoginCurl) . '(' . curl_errno($userLoginCurl) . ')';
            return true;
        }

        // Close connection
        curl_close($userLoginCurl);
    }

    public function quickboxUserLogout($quickblox_id) {
        $token = self::quickboxTokenUser($quickblox_id);  // genrate quickblox session
        self::quickboxDestorySession($token);  // genrate quickblox session
        $userLogoutCurl = curl_init();
        curl_setopt($userLogoutCurl, CURLOPT_URL, self::QUICKBLOX_API_URL . '/login.json');
        curl_setopt($userLogoutCurl, CURLOPT_HTTPHEADER, array("QB-Token: " . $token));
        curl_setopt($userLogoutCurl, CURLOPT_CUSTOMREQUEST, "DELETE");
        curl_setopt($userLogoutCurl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($userLogoutCurl, CURLOPT_SSL_VERIFYPEER, FALSE);

        $responce = curl_exec($userLogoutCurl);
        // Close connection
        curl_close($userLogoutCurl);
    }

    public static function quickboxDestorySession($token) {
        $getSessionCurl = curl_init();
        curl_setopt($getSessionCurl, CURLOPT_URL, self::QUICKBLOX_API_URL . '/session.json');
        curl_setopt($getSessionCurl, CURLOPT_HTTPHEADER, array("QB-Token: " . $token));
        curl_setopt($getSessionCurl, CURLOPT_CUSTOMREQUEST, "DELETE");
        //curl_setopt($updateCurl, CURLOPT_POSTFIELDS, $post_body);
        curl_setopt($getSessionCurl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($getSessionCurl, CURLOPT_SSL_VERIFYPEER, FALSE);

        $responce = curl_exec($getSessionCurl);
        $responceDecode = json_decode($responce);
        // Close connection
        curl_close($getSessionCurl);
    }

}

?>
