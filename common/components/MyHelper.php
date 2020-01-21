<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace common\components;

use Yii;
use yii\helpers\Url;
use common\models\User;
use common\models\Skills;
use common\models\University;
use common\models\UserSkills;
use common\models\Courses;
use common\models\AttributeValue;
use common\models\GigMetadata;
use common\models\Transaction;
use common\components\MailSend;
use common\models\PaystackCustomer;
use common\models\PaystackAuthorization;
use frontend\modules\gloomme\gigs\models\GigsFaqs;
use frontend\modules\gloomme\gigs\models\GigsOrder;
use frontend\modules\gloomme\gigs\models\GigsPricing;
use common\models\SiteSettings;

class MyHelper {

    public function ip_details($IPaddress) {

        /* Get user ip address details with geoplugin.net */
        $geopluginURL = 'http://www.geoplugin.net/php.gp?ip=' . $IPaddress;
        $addrDetailsArr = unserialize(file_get_contents($geopluginURL));

        /* Get City name by return array */
        $city = $addrDetailsArr['geoplugin_city'];

        /* Get Country name by return array */
        $country = $addrDetailsArr['geoplugin_countryName'];
    }

    public function ip_info($ip = NULL, $purpose = "location", $deep_detect = TRUE) {
        $output = NULL;
        if (filter_var($ip, FILTER_VALIDATE_IP) === FALSE) {
            $ip = $_SERVER["REMOTE_ADDR"];
            if ($deep_detect) {
                if (filter_var(@$_SERVER['HTTP_X_FORWARDED_FOR'], FILTER_VALIDATE_IP))
                    $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
                if (filter_var(@$_SERVER['HTTP_CLIENT_IP'], FILTER_VALIDATE_IP))
                    $ip = $_SERVER['HTTP_CLIENT_IP'];
            }
        }
        $purpose = str_replace(array("name", "\n", "\t", " ", "-", "_"), NULL, strtolower(trim($purpose)));
        $support = array("country", "countrycode", "state", "region", "city", "location", "address");
        $continents = array(
            "AF" => "Africa",
            "AN" => "Antarctica",
            "AS" => "Asia",
            "EU" => "Europe",
            "OC" => "Australia (Oceania)",
            "NA" => "North America",
            "SA" => "South America"
        );
        if (filter_var($ip, FILTER_VALIDATE_IP) && in_array($purpose, $support)) {
            $ipdat = @json_decode(file_get_contents("http://www.geoplugin.net/json.gp?ip=" . $ip));
            if (@strlen(trim($ipdat->geoplugin_countryCode)) == 2) {
                switch ($purpose) {
                    case "location":
                        $output = array(
                            "city" => @$ipdat->geoplugin_city,
                            "state" => @$ipdat->geoplugin_regionName,
                            "country" => @$ipdat->geoplugin_countryName,
                            "country_code" => @$ipdat->geoplugin_countryCode,
                            "continent" => @$continents[strtoupper($ipdat->geoplugin_continentCode)],
                            "continent_code" => @$ipdat->geoplugin_continentCode
                        );
                        break;
                    case "address":
                        $address = array($ipdat->geoplugin_countryName);
                        if (@strlen($ipdat->geoplugin_regionName) >= 1)
                            $address[] = $ipdat->geoplugin_regionName;
                        if (@strlen($ipdat->geoplugin_city) >= 1)
                            $address[] = $ipdat->geoplugin_city;
                        $output = implode(", ", array_reverse($address));
                        break;
                    case "city":
                        $output = @$ipdat->geoplugin_city;
                        break;
                    case "state":
                        $output = @$ipdat->geoplugin_regionName;
                        break;
                    case "region":
                        $output = @$ipdat->geoplugin_regionName;
                        break;
                    case "country":
                        $output = @$ipdat->geoplugin_countryName;
                        break;
                    case "countrycode":
                        $output = @$ipdat->geoplugin_countryCode;
                        break;
                }
            }
        }
        return $output;

        //ip_info($_SERVER['REMOTE_ADDR'], "Country"); // India
        //ip_info("Visitor", "Country Code"); // IN
        //ip_info("Visitor", "State"); // Andhra Pradesh
        //ip_info("Visitor", "City"); // Proddatur
        //ip_info("Visitor", "Address"); // Proddatur, Andhra Pradesh, India
        //print_r(ip_info("Visitor", "Location")); // Array ( [city] => Proddatur [state] => Andhra Pradesh [country] => India [country_code] => IN [continent] => Asia [continent_code] => AS )
    }

    public function getTimeInfo($oldTime, $newTime, $timeType) {
        $timeCalc = strtotime($newTime) - strtotime($oldTime);
        if ($timeType == "x") {
            if ($timeCalc > 60) {
                $timeType = "m";
            }
            if ($timeCalc > (60 * 60)) {
                $timeType = "h";
            }
            if ($timeCalc > (60 * 60 * 24)) {
                $timeType = "d";
            }
        }
        if ($timeType == "s") {
            
        }
        if ($timeType == "m") {
            $timeCalc = round($timeCalc / 60) . " min ago";
        } elseif ($timeType == "h") {
            $timeCalc = round($timeCalc / 60 / 60) . " hours ago";
        } elseif ($timeType == "d") {
            $timeCalc = round($timeCalc / 60 / 60 / 24) . " days ago";
        } else {
            $timeCalc .= " sec ago";
        }
        return $timeCalc;
    }

    public static function responsearray($code, $error, $msg = '', $data = '') {
        $response = array();
        $response['code'] = $code;
        $response['error'] = $error;
        $response['message'] = $msg;
        if ($data != '') {
            $response['data'] = $data;
        }
        //echo "<pre>";print_r($response);exit;
        return $response;
    }

    public static function validationErrorMessage($getErrors) {
        $response = array();
        $errorkey_obj = array();
        foreach ($getErrors as $errorkey => $error) {
            $errorkey_obj[] = $errorkey;
        }
        $response["status"] = 0;
        $response["error"] = true;
        $fields_req = implode(',', $errorkey_obj);
        $response['data'] = $getErrors;
        $response['message'] = 'Validation error on field: ' . $fields_req;
        return $response;
    }

    public function addNewSkills($newSkill, $userId, $level) {
        $modelNewSkills = new Skills();
        $modelNewSkills->title = $newSkill;
        $modelNewSkills->status = 0;
        if ($modelNewSkills->save()) {
            $modelUserSkills = new UserSkills();
            $modelUserSkills->userid = $userId;
            $modelUserSkills->skills = $modelNewSkills->id;
            $modelUserSkills->skillslevel = $level;
            $modelUserSkills->status = 0;
            if ($modelUserSkills->save()) {
                return $modelUserSkills;
            }
        }
    }

    public function addNewUniversity($userId, $country, $newUniversity, $degree, $courses, $yearto, $checkCourses) {
        $modelNewUniversity = new University();
        $modelNewUniversity->universityname = $newUniversity;
        $modelNewUniversity->status = 0;
        if ($modelNewUniversity->save()) {
            $coursesId = $courses;
            if ($checkCourses == 0) {
                $modelCourses = new Courses();
                $modelCourses->universityid = $modelNewUniversity->id;
                $modelCourses->name = $courses;
                $modelCourses->status = 0;
                $modelCourses->save();
                $coursesId = $modelCourses->id;
            }

            $modelEducation = new \common\models\UserEducation();
            $modelEducation->userid = $userId;
            $modelEducation->country = $country;
            $modelEducation->universityid = $modelNewUniversity->id;
            $modelEducation->degree = $degree;
            $modelEducation->courses = $coursesId;
            $modelEducation->year_to = $yearto;
            if ($modelEducation->save()) {
                return $modelEducation;
            }
        }
    }

    public function savedFaqs($model) {

        $getFaqs = GigsFaqs::getFaq($model->id);
        $html = '';
        $html .= '<div class="panel panel-default">';
        $html .= '<div class="panel-heading">';
        $html .= '<h4 class="panel-title" data-toggle="collapse" data-parent="#accordion" href="#collapse' . $model->id . '">';
        $html .= '<span class="answertitle">' . $getFaqs['question'] . '</span>';
        $html .= '</h4>';
        $html .= '</div>';
        $html .= '<div id="collapse' . $model->id . '" class="panel-collapse collapse">';
        $html .= '<div class="panel-body">';
        $html .= '<input type="hidden" name="gigid" id="gigid" value="' . $getFaqs['gigId'] . '">';
        $html .= '<div class="form-group">';
        $html .= '<input type="text" class="form-control" id="faqquestionfill" placeholder="Add a Question: i.e. Do you translate to English as well?" value="' . $getFaqs['question'] . '">';
        $html .= '</div>';
        $html .= '<div class="form-group">';
        $html .= '<textarea class="form-control" id="faqanswerfill" placeholder="Add an Answer: i.e. Yes, I also translate from English to Hebrew.">' . $getFaqs['answer'] . '</textarea>';
        $html .= '<p class="text-muted text-right"><span id="rem_gigfill" title="300">300</span>max</p>';
        $html .= '</div>';
        $html .= '<div class="add-faqpart text-right">';
        $html .= '<input type="button" class="btn-theme bg-gray" id="cancelSavedFaq" value="Cancel">';
        $html .= '<input type="button" class="btn-theme" id="updateFaqs" value="Update">';
        $html .= '</div>';
        $html .= '</div>';
        $html .= '</div>';
        $html .= '</div>';
        return $html;
    }

    public function savedRequirement($model) {
        $getSavedData = $model->find()->where(['id' => $model->id])->one();
        $optionAns = $getSavedData['options'];
        $html = '<div class="form-group js-requirement requirement-temp-' . $model->id . '">';
        $html .= '<div class="buttons">';
        $html .= '<a class="hint--top" data-hint="Delete">';
        $html .= '<i class="fa fa-lg fa-trash-o requirements-delete" data-id="' . $model->id . '"></i>';
        $html .= '</a>';
        $html .= '<a class="hint--top" data-hint="Edit">';
        $html .= '<i class="fa fa-lg fa-pencil requirements-edit" data-id="' . $model->id . '"></i>';
        $html .= '</a>';
        $html .= '</div>';
        $html .= ' <label>' . $getSavedData['requirement'] . ' </label>';
        if ($getSavedData['answerType'] == 'text') {
            $html .= '<input type="text" class="form-control" placeholder="Enter your answer or upload file"  disabled="">';
            $html .= '<i class="fa fa-link link_icon"></i>';
            $html .= '</div >';
        } elseif ($getSavedData['answerType'] == 'multiple' && $getSavedData['optionsCount'] == 2 && $getSavedData['moreanswer'] == 0) {
            $optionAns = json_decode($getSavedData['options']);
            $html .= '<ul>';
            //option loop
            foreach ($optionAns as $optionData) {
                $html .= '<li>';
                $html .= '<span>';
                $html .= '<input name="radio-group" id="test1" type="radio" disabled="">';
                $html .= '<label for="test1">' . $optionData . '</label>';
                $html .= '</span>';
                $html .= '</li>';
            }
            $html .= '</ul>';
        } elseif ($getSavedData['answerType'] == 'multiple' && $getSavedData['optionsCount'] > 2 && $getSavedData['moreanswer'] == 0) {
            $optionAns = json_decode($getSavedData['options']);
            $html .= '<div class="select_box">';
            $html .= '<span>';
            $html .= '<select disabled="">';
            //option loop
            foreach ($optionAns as $optionData) {
                $html .= '<option>' . $optionData . '</option>';
            }
            $html .= '</select>';
            $html .= '</span>';
            $html .= '</div >';
        } elseif ($getSavedData['answerType'] == 'multiple' && $getSavedData['optionsCount'] >= 2 && $getSavedData['moreanswer'] == 1) {
            $optionAns = json_decode($getSavedData['options']);
            $html .= '<ul>';
//option loop
            foreach ($optionAns as $optionData) {
                $html .= '<li>';
                $html .= '<span>';
                $html .= '<input  disabled="" name="radio-group" id="test1" type="checkbox">';
                $html .= '<label for="test1">' . $optionData . '</label>';
                $html .= '</span>';
                $html .= '</li>';
            }
            $html .= '</ul>';
        } elseif ($getSavedData['answerType'] == 'attached') {
            $html .= '<button type="submit" class="btn btn-primary" disabled=""><i class="fa fa-link"></i>&nbsp; Attach Files</button>';
        }
        $html .= '</div>';
        return $html;
    }

    public function getRequirement($getSavedData, $params) {
        $ansTypeSeleted1 = $getSavedData['answerType'] == 'text' ? 'selected=""' : '';
        $ansTypeSeleted2 = $getSavedData['answerType'] == 'multiple' ? 'selected=""' : '';
        $ansTypeSeleted3 = $getSavedData['answerType'] == 'attached' ? 'selected=""' : '';
        $mandotrySeleted = $getSavedData['mandotry'] == 1 ? 'checked=""' : '';
        $moreAnswerSeleted = $getSavedData['moreanswer'] == 1 ? 'checked=""' : '';

        $html = '<div class="form-group">';
        $html .= '<label>' . Yii::t('frontend', 'Requirement') . '</label>';
        $html .= '<span class="field-gigsrequirement-requirement required">';
        $html .= '<textarea id="gigsrequirement-requirement-ajax" class="form-control" name="GigsRequirement[requirement]" aria-required="true">' . $getSavedData['requirement'] . '</textarea>';
        $html .= '<div class="help-block"></div>';
        $html .= '</span>';
        $html .= '<p class="text-muted text-right"><span id="rem_gig" title="450">450</span> ' . Yii::t('frontend', 'max') . '</p>';
        $html .= '</div>';
        $html .= '<div class="anwser-type">';
        $html .= '<ul>';
        $html .= '<li>';
        $html .= '<label>' . Yii::t('frontend', 'Answer Type') . '</label>';
        $html .= '<div class="select_box"> <span>';
        $html .= '<select id="answertype-ajax">';
        $html .= '<option value="text" ' . $ansTypeSeleted1 . '>' . Yii::t('frontend', 'Free Text') . '</option>';
        $html .= '<option value="multiple" ' . $ansTypeSeleted2 . '>' . Yii::t('frontend', 'Multiple Answer') . '</option>';
        $html .= '<option value="attached" ' . $ansTypeSeleted3 . '>' . Yii::t('frontend', 'Attached File') . '</option>';
        $html .= '</select>';
        $html .= '</span></div>';
        $html .= '</li>';
        $html .= '<li><span>';
        $html .= '<input ' . $mandotrySeleted . ' name="mandatory-ajax" id="test9" type="checkbox">';
        $html .= '<label for="test9">' . Yii::t('frontend', 'Answer is mandatory') . '</label>';
        $html .= '</span></li>';
        $html .= '</ul>';
        $html .= '</div>';
        if ($getSavedData['answerType'] == 'multiple') {
            $html .= '<div class="showMultiOption-ajax" style="display:block">';
        } else {
            $html .= '<div class="showMultiOption-ajax" style="display:none">';
        }
        $html .= '<div class="add_mores-ajax" id="add_mores-ajax">';
        $opnId = 0;
        if ($getSavedData['options'] != '') {
            $optionAns = json_decode($getSavedData['options']);
            foreach ($optionAns as $optiondata) {
                $opnId++;

                $html .= '<div class="form-group">';
                $html .= '<input type="text" class="form-control  optionsanswer" name="optionsanswer-ajax" id="option-ajax' . $opnId . '" value="' . $optiondata . '">';
                $html .= '<div class="buttons">';
                $html .= '<i class="btn fa fa-close remove-requirements-ajax" data-id="' . $opnId . '"></i>';
                $html .= '</div>';
                $html .= '</div>';
            }
        } else {

            $html .= '<div class="form-group">';
            $html .= '<input type="text" class="form-control  optionsanswer" name="optionsanswer-ajax" id="option-ajax1">';
            $html .= '</div>';


            $html .= '<div class="form-group">';
            $html .= '<input type="text" class="form-control  optionsanswer" name="optionsanswer-ajax" id="option-ajax2">';
            $html .= '</div>';
        }
        $html .= '</div>';
        $html .= '<div class="form-group inputborder-part">';
        $html .= '<a href="javascript:;" id="add-option-ajax"><i class="fa fa-plus"></i>' . Yii::t('frontend', 'Add Optional Answer') . '</a>';
        $html .= '</div>';

        $html .= '<div class="form-group">';
        $html .= '<span>';
        $html .= '<input name="moreanswer-ajax" id="test' . $params['reqId'] . '" type="checkbox" ' . $moreAnswerSeleted . '>';
        $html .= '<label for="test' . $params['reqId'] . '">' . Yii::t('frontend', 'Allow more than one answer to this question') . '<br> ' . Yii::t('frontend', '(Your buyer will see checkboxes)') . '</label>';
        $html .= '</span>';
        $html .= '</div>';
        $html .= '</div>';

        $html .= '<div class="add-faqpart text-right">';
        $html .= '<input class="btn-theme bg-gray cancel-update-requirement" data-id="' . $params['reqId'] . '" value="' . Yii::t('frontend', 'Cancel') . '" type="button">';
        $html .= '<input class="btn-theme update-requirement" data-id="' . $params['reqId'] . '" value="' . Yii::t('frontend', 'Update') . '" type="button">';
        $html .= '</div>';
        $html .= '<span class="black-error" style="display: none; color: red">* ' . Yii::t('frontend', 'You must add at least 1 requirement') . '</span>';
        return $html;
    }

    public function updateRequirement($reqId, $model) {
        $getSavedData = $model->find()->where(['id' => $reqId])->one();
        $optionAns = $getSavedData['options'];
        $html = '<div class="buttons">';
        $html .= '<a class="hint--top" data-hint="Delete">';
        $html .= '<i class="fa fa-lg fa-trash-o requirements-delete" data-id="' . $model->id . '"></i>';
        $html .= '</a>';
        $html .= '<a class="hint--top" data-hint="Edit">';
        $html .= '<i class="fa fa-lg fa-pencil requirements-edit" data-id="' . $model->id . '"></i>';
        $html .= '</a>';
        $html .= '</div>';
        $html .= '<p class="p-t-10 p-b-15 requirement-body">' . $getSavedData['requirement'] . '</p>';
        if ($getSavedData['answerType'] == 'text') {
            $html .= '<div class="free_text">';
            $html .= '<div class="form-row input-wrap free-text">';
            $html .= '<i class="fa fa-paperclip"></i><input placeholder="Enter your answer or upload file" readonly="" type="text">';
            $html .= '</div>';
            $html .= '</div >';
        } elseif ($getSavedData['answerType'] == 'multiple' && $getSavedData['optionsCount'] == 2 && $getSavedData['moreanswer'] == 0) {
            $optionAns = json_decode($getSavedData['options']);
            $html .= '<div class="select_one">';
//option loop
            foreach ($optionAns as $optionData) {
                $html .= '<div class="form-row">';
                $html .= '<label class="fake-radio-black radio-text">';
                $html .= $optionData;
                $html .= '<input id="temp-3" name="temp-3" disabled="disabled" type="radio">';
                $html .= '<span class="radio-img"></span>';
                $html .= '</label>';
                $html .= '</div>';
            }
            $html .= '</div >';
        } elseif ($getSavedData['answerType'] == 'multiple' && $getSavedData['optionsCount'] > 2 && $getSavedData['moreanswer'] == 0) {
            $optionAns = json_decode($getSavedData['options']);
            $html .= '<div class="filter-select cf">';
            $html .= '<div class="fake-dropdown" style="visibility: visible;">';
            $html .= '<a class="dropdown-toggle disabled no-uppercase" data-toggle="dropdown">' . $optionAns[0] . '</a>';
            $html .= '<ul class="dropdown-menu no-uppercase" role="menu" style="top: -2px; width: 180px;">';
//option loop
            foreach ($optionAns as $optionData) {
                $html .= '<li>';
                $html .= '<a data-val="' . $optionData . '">';
                $html .= '<span class="text-inner">' . $optionData . '</span>';
                $html .= '</a>';
                $html .= '</li>';
            }
            $html .= '</ul>';
            $html .= '</div>';
            $html .= '</div >';
        } elseif ($getSavedData['answerType'] == 'multiple' && $getSavedData['optionsCount'] >= 2 && $getSavedData['moreanswer'] == 1) {
            $optionAns = json_decode($getSavedData['options']);
            $html .= '<div class="select_many">';
            $html .= '<ul class="select">';
//option loop
            foreach ($optionAns as $optionData) {
                $html .= '<li>';
                $html .= '<label class="fake-check-black check-text">';
                $html .= $optionData;
                $html .= '<input disabled="disabled" type="checkbox">';
                $html .= '<span class="chk-img"></span>';
                $html .= '</label>';
                $html .= '</li>';
            }
            $html .= '</ul>';
            $html .= '</div >';
        } elseif ($getSavedData['answerType'] == 'attached') {
            $html .= '<div class="file_upload">';
            $html .= '<div class="btn-standard btn-attach-files">';
            $html .= '<i class="fa fa-paperclip"></i> Attach Files';
            $html .= '</div>';
            $html .= '</div>';
        }
        return $html;
    }

    public function getGigsMetadata($getMetaData, $gigId) {
        $html = '';
        if ($getMetaData['Attribute']) {
            $html = '<div id="parentVerticalTab">';
            $html .= '<ul class="resp-tabs-list hor_1">';
            foreach ($getMetaData['Attribute'] as $mataData) {

                $html .= '<li data-id="' . $mataData['id'] . '">' . $mataData['name'] . '<span class="righrplus_icon pull-right"> + </span> </li>';
            }
            $html .= '</ul>';
            $html .= '<div class="resp-tabs-container hor_1">';
            foreach ($getMetaData['Attribute'] as $mataData) {
                $getAttribute = AttributeValue::find()->where(['attribute_id' => $mataData['id']])->all();
                $inputType = $mataData['input_type'] == 1 ? 'checkbox' : 'radio';
                $inputTypeID = $mataData['input_type'] == 1 ? 'c' : 'r';

                $html .= '<div>';
                $html .= '<h4> ' . $mataData['comment'] . ': </h4>';
                $html .= '<ul class="overview-tab-radio">';
                $html .= '<input name="GigMetaData[gigMetaId][]" type="hidden" value="' . $mataData['id'] . '">';
                $sh = 0;
                $checked = array();
                foreach ($getAttribute as $key => $mataValueData) {
                    $sh++;
                    $getMetaValueData = GigMetadata::find()->where(['gigId' => base64_decode($gigId), 'gigMetaId' => $mataData['id']])->one();
                    $checked[$mataValueData['id']] = '';
                    if (!empty($getMetaValueData)) {
                        $strw = explode(',', $getMetaValueData['gigMetaValueId']);
                        if (in_array($mataValueData['id'], $strw)) {
                            $checked[$mataValueData['id']] = 'checked=""';
                        } else {
                            $checked[$mataValueData['id']] = '';
                        }
                    }
                    $html .= '<li><span>';
                    $html .= '<input name="GigMetaData[gigMetaValueId][]" id="test' . $inputTypeID . $sh . '" type="' . $inputType . '" value="' . $mataValueData['id'] . '" ' . $checked[$mataValueData['id']] . '>';
                    $html .= '<label for="test' . $inputTypeID . $sh . '">' . $mataValueData['value'] . '</label>';
                    $html .= '</span></li>';
                }
                $html .= '</ul></div>';
            }
            $html .= '</div></div>';
        }
        return $html;
    }

    public function getExtraServices($extras) {
        $abcArr = array();
        $abArr = json_decode($extras);
        foreach ($abArr as $key => $data) {
            $serviceName = $data->servicename;
            unset($data->servicename);
            foreach ($data as $keys => $tsData) {
                $abcArr[$serviceName][$keys][0] = $tsData;
            }
        }
        return serialize($abcArr);
    }

    public function payment($params, $userDetail, $type = 'WEB') {
        if (isset($params['authCode']) && $params['authCode'] != '') {
            $amount = 100 * $params['amount'];
            $authoCode = $params['authCode'];
            $userEmail = $userDetail['email'];
            $userName = $userDetail['firstname'] . ' ' . $userDetail['lastname'];
            $data = "{\n  \"email\":\"$userEmail\",\n  \"amount\":\"$amount\",\n \"authorization_code\":\"$authoCode\"\n}";
        } else {
            $cardName = $params['cardName'];
            $cardNumber = $params['cardNumber'];
            $cardExpiryMonth = $params['cardExpiryMonth'];
            $cardExpiryYear = $params['cardExpiryYear'];
            $cardCvv = $params['cardCvv'];
            $amount = 100 * $params['amount'];
            $userEmail = $userDetail['email'];
            $userName = $userDetail['firstname'] . ' ' . $userDetail['lastname'];
            $data = "{\n  \"email\":\"$userEmail\",\n  \"amount\":\"$amount\",\n  \"metadata\":{\n    \"custom_fields\":[\n      {\n        \"value\":\"987654314\",\n        \"display_name\": \"$cardName\",\n        \"variable_name\": \"$userName\"\n      }\n    ]\n  },\n  \"card\":{\n    \"cvv\":\"$cardCvv\",\n    \"number\":\"$cardNumber\",\n    \"expiry_month\":\"$cardExpiryMonth\",\n    \"expiry_year\":\"$cardExpiryYear\"\n  }}";
        }

        //echo $data;die;
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://api.paystack.co/charge",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => $data,
            CURLOPT_HTTPHEADER => array(
                "Authorization: Bearer sk_test_fbe92f265a763fcbe99f57533ecb74c926ab1de4",
                "Content-Type: application/json"
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($type == 'WEB') {
            if ($err) {
                echo "cURL Error #:" . $err;
            } else {
                echo $response;
            }
        } else {
            return $response;
        }
    }

    public function cardPin($params, $type = 'WEB') {
        $pin = $params['cardpin'];
        $reference = $params['reference'];

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://api.paystack.co/charge/submit_pin",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => "{\n  \"pin\":\"$pin\",\n  \"reference\":\"$reference\"\n}",
            CURLOPT_HTTPHEADER => array(
                "Authorization: Bearer sk_test_fbe92f265a763fcbe99f57533ecb74c926ab1de4",
                "Content-Type: application/json"
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($type == 'WEB') {
            if ($err) {
                echo "cURL Error #:" . $err;
            } else {
                echo $response;
            }
        } else {
            return $response;
        }
    }

    public function cardPhn($params, $type = 'WEB') {
        $phnnumber = $params['phnnumber'];
        $reference = $params['reference'];

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://api.paystack.co/charge/submit_phone",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => "{\n  \"phone\":\"$phnnumber\",\n  \"reference\":\"$reference\"\n}",
            CURLOPT_HTTPHEADER => array(
                "Authorization: Bearer sk_test_fbe92f265a763fcbe99f57533ecb74c926ab1de4",
                "Content-Type: application/json"
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($type == 'WEB') {
            if ($err) {
                echo "cURL Error #:" . $err;
            } else {
                echo $response;
            }
        } else {
            return $response;
        }
    }

    public function confirmOtp($params, $type = 'WEB') {
        $otp = $params['otp'];
        $reference = $params['reference'];

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://api.paystack.co/charge/submit_otp",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => "{\n  \"otp\":\"$otp\",\n  \"reference\":\"$reference\"\n}",
            CURLOPT_HTTPHEADER => array(
                "Authorization: Bearer sk_test_fbe92f265a763fcbe99f57533ecb74c926ab1de4",
                "Content-Type: application/json"
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($type == 'WEB') {
            if ($err) {
                echo "cURL Error #:" . $err;
            } else {
                echo $response;
            }
        } else {
            return $response;
        }
    }

    public function saveTransaction($params) {
        $gigsOrder = new GigsOrder();
        $transactionModel = new Transaction();
        $paystackUser = new PaystackCustomer();
        $paystackAuth = new PaystackAuthorization();

        if (isset($params['data']['id']) && $params['data']['id'] != '') {
            $transactionModel->orderID = $params['orderId'];
            $transactionModel->userid = $params['userId'];
            $transactionModel->receiverid = $params['receiverId'];
            $transactionModel->gigId = $params['gigId'];
            $transactionModel->packageID = $params['packageid'];
            $transactionModel->transactionID = $params['data']['id'];
            $transactionModel->reference = $params['data']['reference'];
            $transactionModel->amount = $params['data']['amount'] / 100;
            $transactionModel->status = $params['data']['status'];
            $transactionModel->gateway_response = $params['data']['gateway_response'];
            if ($transactionModel->save()) {
                $checkPaystackAuth = $paystackAuth->find()->where(['signature' => $params['data']['authorization']['signature'], 'userid' => $params['userId']])->count();
                if ($checkPaystackAuth == 0) {
                    $paystackAuth->userid = $params['userId'];
                    $paystackAuth->authorization_code = $params['data']['authorization']['authorization_code'];
                    $paystackAuth->bin = $params['data']['authorization']['bin'];
                    $paystackAuth->card_last4 = $params['data']['authorization']['last4'];
                    $paystackAuth->exp_month = $params['data']['authorization']['exp_month'];
                    $paystackAuth->exp_year = $params['data']['authorization']['exp_year'];
                    $paystackAuth->country_code = $params['data']['authorization']['country_code'];
                    $paystackAuth->card_type = $params['data']['authorization']['card_type'];
                    $paystackAuth->reusable = $params['data']['authorization']['reusable'];
                    $paystackAuth->signature = $params['data']['authorization']['signature'];
                    if ($paystackAuth->save()) {
                        $paystackUser->userid = $params['userId'];
                        $paystackUser->paystack_customerID = $params['data']['customer']['id'];
                        $paystackUser->first_name = $params['data']['customer']['first_name'];
                        $paystackUser->last_name = $params['data']['customer']['last_name'];
                        $paystackUser->email = $params['data']['customer']['email'];
                        $paystackUser->customer_code = $params['data']['customer']['customer_code'];
                        if ($paystackUser->save()) {
                            $gigsOrder->updateAll([
                                'order_status' => GigsOrder::ORDER_NEW,
                                'payment_status' => GigsOrder::PAYMENT_COMPLETED,
                                'invoiceno' => $params['data']['id'],
                                'totalamt' => $params['data']['amount'] / 100,
                                'paymentDate' => time()
                                    ], 'orderID= "' . $params['orderId'] . '" ');

                            $userAdmin = User::getUserDetails(1);
                            $userD = User::getUserDetails($params['userId']);
                            $userS = User::getUserDetails($params['receiverId']);
                            MailSend::sendOrderDetail($userD['id'], $userD['email'], $userAdmin['email'], $userS['email'], $userS['username'], $params['orderId']);
                            $str = array('status' => 'success');
                            return json_encode($str);
                        } else {
                            $str = array('status' => 'error');
                            echo json_encode($str);
                        }
                    } else {
                        $str = array('status' => 'error');
                        echo json_encode($str);
                    }
                } else {
                    $gigsOrder->updateAll([
                        'order_status' => GigsOrder::ORDER_NEW,
                        'payment_status' => GigsOrder::PAYMENT_COMPLETED,
                        'invoiceno' => $params['data']['id'],
                        'totalamt' => $params['data']['amount'] / 100,
                        'paymentDate' => time()
                            ], 'orderID= "' . $params['orderId'] . '" ');

                    $userAdmin = User::getUserDetails(1);
                    $userD = User::getUserDetails($params['userId']);
                    $userS = User::getUserDetails($params['receiverId']);
                    MailSend::sendOrderDetail($userD['id'], $userD['email'], $userAdmin['email'], $userS['email'], $userS['username'], $params['orderId']);
                    $str = array('status' => 'success');
                    return json_encode($str);
                }
            } else {
                $str = array('status' => 'error');
                echo json_encode($str);
            }
        } else {
            $str = array('status' => 'error');
            echo json_encode($str);
        }
    }

    public function saveTransactionAPI($params, $packagId, $userid, $orderid) {
        $gigsOrder = new GigsOrder();
        $transactionModel = new Transaction();
        $paystackUser = new PaystackCustomer();
        $paystackAuth = new PaystackAuthorization();
        $getOrderDetail = $gigsOrder->find()->where(['orderID' => $orderid])->one();
        if (isset($params->data->id) && $params->data->id != '') {
            $transactionModel->orderID = $orderid;
            $transactionModel->userid = $userid;
            $transactionModel->receiverid = $getOrderDetail['giguserid'];
            $transactionModel->gigId = $getOrderDetail['gigid'];
            $transactionModel->packageID = $packagId;
            $transactionModel->transactionID = $params->data->id;
            $transactionModel->reference = $params->data->reference;
            $transactionModel->amount = $params->data->amount / 100;
            $transactionModel->status = $params->data->status;
            $transactionModel->gateway_response = $params->data->gateway_response;
            if ($transactionModel->save()) {
                $checkPaystackAuth = $paystackAuth->find()->where(['authorization_code' => $params->data->authorization->authorization_code])->count();
                if ($checkPaystackAuth == 0) {
                    $paystackAuth->userid = $userid;
                    $paystackAuth->authorization_code = $params->data->authorization->authorization_code;
                    $paystackAuth->bin = $params->data->authorization->bin;
                    $paystackAuth->card_last4 = $params->data->authorization->last4;
                    $paystackAuth->exp_month = $params->data->authorization->exp_month;
                    $paystackAuth->exp_year = $params->data->authorization->exp_year;
                    $paystackAuth->country_code = $params->data->authorization->country_code;
                    $paystackAuth->card_type = $params->data->authorization->card_type;
                    $paystackAuth->reusable = $params->data->authorization->reusable;
                    $paystackAuth->signature = $params->data->authorization->signature;
                    if ($paystackAuth->save()) {
                        $paystackUser->userid = $userid;
                        $paystackUser->paystack_customerID = $params->data->customer->id;
                        $paystackUser->first_name = $params->data->customer->first_name;
                        $paystackUser->last_name = $params->data->customer->last_name;
                        $paystackUser->email = $params->data->customer->email;
                        $paystackUser->customer_code = $params->data->customer->customer_code;
                        if ($paystackUser->save()) {
                            $gigsOrder->updateAll([
                                'order_status' => GigsOrder::ORDER_NEW,
                                'payment_status' => GigsOrder::PAYMENT_COMPLETED,
                                'invoiceno' => $params->data->id,
                                'totalamt' => $params->data->amount / 100,
                                'paymentDate' => time()
                                    ], 'orderID= "' . $orderid . '" ');

                            $userAdmin = User::getUserDetails(1);
                            $userD = User::getUserDetails($userid);
                            $userS = User::getUserDetails($getOrderDetail['giguserid']);
                            MailSend::sendOrderDetail($userD['id'], $userD['email'], $userAdmin['email'], $userS['email'], $userS['username'], $orderid);
                            return true;
                        } else {
                            return false;
                        }
                    } else {
                        return false;
                    }
                } else {
                    $gigsOrder->updateAll([
                        'order_status' => GigsOrder::ORDER_NEW,
                        'payment_status' => GigsOrder::PAYMENT_COMPLETED,
                        'invoiceno' => $params->data->id,
                        'totalamt' => $params->data->amount / 100,
                        'paymentDate' => time()
                            ], 'orderID= "' . $orderid . '" ');

                    $userAdmin = User::getUserDetails(1);
                    $userD = User::getUserDetails($userid);
                    $userS = User::getUserDetails($getOrderDetail['giguserid']);
                    MailSend::sendOrderDetail($userD['id'], $userD['email'], $userAdmin['email'], $userS['email'], $userS['username'], $orderid);
                    return true;
                }
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    public function commissionRate() {
        $getData = SiteSettings::findOne(1);
        return $getData;
    }

    public function getDecimalNumber($number) {
        if (is_float($number)) {
            $number = number_format((float) $number, 2, '.', '');
        }
        return $number;
    }

    public function getSecurityQue($queSlug) {
        $question = '';
        if ($queSlug == 'first_pet') {
            $question = 'What was the name of your first pet?';
        } elseif ($queSlug == 'first_school') {
            $question = 'What was the name of your elementary school?';
        } elseif ($queSlug == 'childhood_nickname') {
            $question = 'What was your childhood nickname?';
        } elseif ($queSlug == 'city_parents_meet') {
            $question = 'In what city did your parents meet?';
        } elseif ($queSlug == 'childhood_friend_name') {
            $question = 'What is the name of your favorite childhood friend?';
        }
        return $question;
    }

    public function getRandomOrderID($length) {
        $token = "";
        $codeAlphabet = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
        $codeAlphabet .= "abcdefghijklmnopqrstuvwxyz";
        //$codeAlphabet .= "0123456789";
        $max = strlen($codeAlphabet); // edited

        for ($i = 0; $i < $length; $i++) {
            $token .= strtoupper($codeAlphabet[random_int(0, $max - 1)]);
        }

        return $token;
    }

    public function countOrder($userId) {
        $totalActiveOrder = GigsOrder::find()->where(['userid' => $userId])->andWhere(['order_status' => GigsOrder::ORDER_ACTIVE])->count();
        $totalWaitingOrder = GigsOrder::find()->where(['userid' => $userId])->andWhere(['order_status' => GigsOrder::ORDER_NEW])->count();
        $totalDeliveredOrder = GigsOrder::find()->where(['userid' => $userId])->andWhere(['order_status' => GigsOrder::ORDER_DELIVERED])->count();
        $totalCompletedOrder = GigsOrder::find()->where(['userid' => $userId])->andWhere(['order_status' => GigsOrder::ORDER_COMPLETED])->count();
        $totalCancelledOrder = GigsOrder::find()->where(['userid' => $userId])->andWhere(['order_status' => GigsOrder::ORDER_CANCELLED])->count();
        $totalAllOrder = GigsOrder::find()->where(['userid' => $userId])->andWhere('order_status !=' . GigsOrder::ORDER_PENDING . ' ')->count();

        $keyArray = array('active' => $totalActiveOrder, 'waiting-for-start' => $totalWaitingOrder, 'delivered' => $totalDeliveredOrder, 'completed' => $totalCompletedOrder, 'cancelled' => $totalCancelledOrder, 'all' => $totalAllOrder);

        return $keyArray;
    }

    public function buyerOrder($userId, $status) {
        $dataArray = array();
        if ($status == 'active') {
            $getOders = GigsOrder::getBuyerActiveOrder($userId);
        } elseif ($status == 'waiting-for-start') {
            $getOders = GigsOrder::getBuyerWaitingOrder($userId);
        } elseif ($status == 'delivered') {
            $getOders = GigsOrder::getBuyerDeliveredOrder($userId);
        } elseif ($status == 'completed') {
            $getOders = GigsOrder::getBuyerCompletedOrder($userId);
        } elseif ($status == 'cancelled') {
            $getOders = GigsOrder::getBuyerCancelledOrder($userId);
        } else {
            $getOders = GigsOrder::getBuyerAllOrder($userId);
        }
        foreach ($getOders as $orderData) {
            $getGigs = \frontend\modules\gloomme\gigs\models\Gigs::getDetail($orderData['gigid']);
            $userName = \common\models\UserProfile::getUserDetail($getGigs['userid']);
            $userImage = \common\models\UserProfile::getUserAvatar($getGigs['userid']);
            $getGigImage = \frontend\modules\gloomme\gigs\models\GigsGallery::find()->where(['gigsId' => $getGigs['id']])->one();
            $getDeliverDays = GigsPricing::find()->where(['id' => $orderData['packageid']])->one();
            $totalDeliveryDays = $getDeliverDays['delivery'] + $orderData['extra_delivery_day'];
            $getDueDate = strtotime("+" . $totalDeliveryDays . " days", $orderData['order_date']);
            $row['username'] = $userName['username'];
            $row['userimage'] = $userImage;
            $row['orderid'] = $orderData['orderID'];
            $row['image'] = Url::to('@frontendUrl') . '/uploads/gigs/images/small_' . $getGigImage['gigs_image'];
            $row['title'] = $getGigs['gigsTitle'];
            $row['orderdate'] = $orderData['order_date'];
            $row['duedate'] = $getDueDate;
            $row['totalamt'] = $orderData['totalamt'];
            $row['packageid'] = $orderData['packageid'];
            $dataArray[] = $row;
        }
        if ($dataArray) {
            $str = array('code' => '0', 'error' => 'false', 'message' => 'success', 'data' => $dataArray);
        } else {
            $str = array('code' => '0', 'error' => 'false', 'message' => 'No ' . $status . ' orders to show', 'data' => array());
        }
        return json_encode($str);
    }

    public function getStartRating($averageReview) {
        $star = '<ul>';
        if ($averageReview >= '1' && $averageReview <= '1.4') {
            $star .= '<li><i class="fa fa-star" aria-hidden="true"></i></li>';
            $star .= '<li><i class="fa fa-star-o" aria-hidden="true"></i></li>';
            $star .= '<li><i class="fa fa-star-o" aria-hidden="true"></i></li>';
            $star .= '<li><i class="fa fa-star-o" aria-hidden="true"></i></li>';
            $star .= '<li><i class="fa fa-star-o" aria-hidden="true"></i></li>';
        } elseif ($averageReview >= '1.5' && $averageReview <= '1.9') {
            $star .= '<li><i class="fa fa-star" aria-hidden="true"></i></li>';
            $star .= '<li><i class="fa fa-star-half-o" aria-hidden="true"></i></li>';
            $star .= '<li><i class="fa fa-star-o" aria-hidden="true"></i></li>';
            $star .= '<li><i class="fa fa-star-o" aria-hidden="true"></i></li>';
            $star .= '<li><i class="fa fa-star-o" aria-hidden="true"></i></li>';
        } elseif ($averageReview >= '2' && $averageReview <= '2.4') {
            $star .= '<li><i class="fa fa-star" aria-hidden="true"></i></li>';
            $star .= '<li><i class="fa fa-star" aria-hidden="true"></i></li>';
            $star .= '<li><i class="fa fa-star-o" aria-hidden="true"></i></li>';
            $star .= '<li><i class="fa fa-star-o" aria-hidden="true"></i></li>';
            $star .= '<li><i class="fa fa-star-o" aria-hidden="true"></i></li>';
        } elseif ($averageReview >= '2.5' & $averageReview <= '2.9') {
            $star .= '<li><i class="fa fa-star" aria-hidden="true"></i></li>';
            $star .= '<li><i class="fa fa-star" aria-hidden="true"></i></li>';
            $star .= '<li><i class="fa fa-star-half-o" aria-hidden="true"></i></li>';
            $star .= '<li><i class="fa fa-star-o" aria-hidden="true"></i></li>';
            $star .= '<li><i class="fa fa-star-o" aria-hidden="true"></i></li>';
        } elseif ($averageReview >= '3' && $averageReview <= '3.4') {
            $star .= '<li><i class="fa fa-star" aria-hidden="true"></i></li>';
            $star .= '<li><i class="fa fa-star" aria-hidden="true"></i></li>';
            $star .= '<li><i class="fa fa-star" aria-hidden="true"></i></li>';
            $star .= '<li><i class="fa fa-star-o" aria-hidden="true"></i></li>';
            $star .= '<li><i class="fa fa-star-o" aria-hidden="true"></i></li>';
        } elseif ($averageReview >= '3.5' && $averageReview <= '3.9') {
            $star .= '<li><i class="fa fa-star" aria-hidden="true"></i></li>';
            $star .= '<li><i class="fa fa-star" aria-hidden="true"></i></li>';
            $star .= '<li><i class="fa fa-star" aria-hidden="true"></i></li>';
            $star .= '<li><i class="fa fa-star-half-o" aria-hidden="true"></i></li>';
            $star .= '<li><i class="fa fa-star-o" aria-hidden="true"></i></li>';
        } elseif ($averageReview >= '4' && $averageReview <= '4.4') {
            $star .= '<li><i class="fa fa-star" aria-hidden="true"></i></li>';
            $star .= '<li><i class="fa fa-star" aria-hidden="true"></i></li>';
            $star .= '<li><i class="fa fa-star" aria-hidden="true"></i></li>';
            $star .= '<li><i class="fa fa-star" aria-hidden="true"></i></li>';
            $star .= '<li><i class="fa fa-star-o" aria-hidden="true"></i></li>';
        } elseif ($averageReview >= '4.5' && $averageReview <= '4.9') {
            $star .= '<li><i class="fa fa-star" aria-hidden="true"></i></li>';
            $star .= '<li><i class="fa fa-star" aria-hidden="true"></i></li>';
            $star .= '<li><i class="fa fa-star" aria-hidden="true"></i></li>';
            $star .= '<li><i class="fa fa-star" aria-hidden="true"></i></li>';
            $star .= '<li><i class="fa fa-star-half-o" aria-hidden="true"></i></li>';
        } elseif ($averageReview == '5') {
            $star .= '<li><i class="fa fa-star" aria-hidden="true"></i></li>';
            $star .= '<li><i class="fa fa-star" aria-hidden="true"></i></li>';
            $star .= '<li><i class="fa fa-star" aria-hidden="true"></i></li>';
            $star .= '<li><i class="fa fa-star" aria-hidden="true"></i></li>';
            $star .= '<li><i class="fa fa-star" aria-hidden="true"></i></li>';
        } else {
            $star .= '<li><i class="fa fa-star-o" aria-hidden="true"></i></li>';
            $star .= '<li><i class="fa fa-star-o" aria-hidden="true"></i></li>';
            $star .= '<li><i class="fa fa-star-o" aria-hidden="true"></i></li>';
            $star .= '<li><i class="fa fa-star-o" aria-hidden="true"></i></li>';
            $star .= '<li><i class="fa fa-star-o" aria-hidden="true"></i></li>';
        }
        $star .= '</ul>';
        echo $star;
    }

    public function getOrderCompletePercentage($userID) {
        $getAllOder = GigsOrder::getSellerTotalOrder($userID);
        $getCompletedOder = GigsOrder::getSellerCompletedOrder($userID);
        if ($getAllOder != 0 && $getCompletedOder != 0) {
            $percent = (int) $getCompletedOder / (int) $getAllOder;
            return number_format($percent * 100, 0) . '%';
        } else {
            return '0%';
        }
    }

    public function getOntimeDeliveryPercentage($userID) {
        $getAllOder = GigsOrder::getSellerTotalOrder($userID);
        $getCompletedOder = GigsOrder::getSellerCompletedOrder($userID);
        if ($getAllOder != 0 && $getCompletedOder != 0) {
            $percent = (int) $getCompletedOder / (int) $getAllOder;
            return number_format($percent * 100, 0) . '%';
        } else {
            return '0%';
        }
    }

    public function getPositiveRating($userID) {
        $communication = $service = $recommend = $totalAvg = 0;
        $countReview = 1;
        $getReview = \common\models\UserReview::find()->where(['recevierID' => $userID])->all();
        $countReviews = \common\models\UserReview::find()->where(['recevierID' => $userID])->count();
        if ($countReviews > 0) {
            $countReview = $countReviews;
        } else {
            $countReview = 1;
        }
        if ($getReview) {
            foreach ($getReview as $dataReview) {
                $communication += $dataReview['communication'];
                $service += $dataReview['serviceDescribed'];
                $recommend += $dataReview['recommend'];
                $totalAvg += $dataReview['average'];
            }
        }
        return number_format($totalAvg / $countReview, 0) . '.0';
    }

    public function getGigsViews($userID) {
        $getAllGigs = \frontend\modules\gloomme\gigs\models\Gigs::getUserGigs($userID, 2);
        $totalView = array();
        $tst = 0;
        if ($getAllGigs) {

            foreach ($getAllGigs as $viewData) {
                $totalView = (new \yii\db\Query())->select(['*'])->from('gig_number_of_views')->where(['gigid' => $viewData['id']])->count();
                $tst += $totalView;
            }
        }
        return $tst;
    }

    public function getSellerNotification($userid) {
        $data = array();
        $getReview = \common\models\UserReview::getSellerReviews($userid);
        $notificationlist = \common\models\UserNotification::find()->where(['receiverID' => $userid])->orderBy(['id' => SORT_DESC])->all();
        if ($notificationlist) {
            foreach ($notificationlist as $notiList) {
                $notiMess = unserialize($notiList['message']);
                $row['message'] = $notiMess['message'];
                $row['notitime'] = $notiList['created_at'];
                $data[] = $row;
            }
            if ($getReview) {
                foreach ($getReview as $reviewData) {
                    $userDetail = \common\models\UserProfile::getUserDetail($reviewData['senderID']);
                    $row2['message'] = $userDetail['username'] . ' left a ' . $reviewData['average'] . ' star review.';
                    $row2['notitime'] = $reviewData['created_at'];
                    $data[] = $row2;
                }
            }
            return $data;
        } else {
            return $data;
        }
    }

    public function getSellerOrder($userId) {
        $dataP['priorityOrder'] = $dataN['newOrder'] = $dataA['activeOrder'] = $dataD['deliverdOrder'] = $dataC['completeOrder'] = $dataCan['cancelledOrder'] = $dataFinal = array();
        $getPriorityOders = GigsOrder::getUserPriorityOrder($userId);
        $getNewOders = GigsOrder::getUserNewOrder($userId);
        $getActiveOders = GigsOrder::getUserActiveOrder($userId);
        $getDeliveredOders = GigsOrder::getUserDeliveredOrder($userId);
        $getCompletedOders = GigsOrder::getUserCompletedOrder($userId);
        $getCancelledOders = GigsOrder::getUserCancelledOrder($userId);
        if ($getPriorityOders) {
            foreach ($getPriorityOders as $priorityOrder) {

                $getGigs = \frontend\modules\gloomme\gigs\models\Gigs::getDetail($priorityOrder['gigid']);
                $title = $getGigs['gigsTitle'];

                $userName = \common\models\UserProfile::getUserDetail($priorityOrder['userid']);
                $userimage = \common\models\UserProfile::getUserAvatar($priorityOrder['userid']);
                $getDeliverDays = GigsPricing::find()->where(['id' => $priorityOrder['packageid']])->one();
                $totalDeliveryDays = $getDeliverDays['delivery'] + $priorityOrder['extra_delivery_day'];
                $getDueDate = strtotime("+" . $totalDeliveryDays . " days", $priorityOrder['order_date']);
                $rowP['orderid'] = $priorityOrder['orderID'];
                $rowP['duedate'] = $getDueDate;
                $rowP['gigtitle'] = $title;
                $rowP['amount'] = $priorityOrder['order_amount'];
                $rowP['userimage'] = $userimage;
                $rowP['username'] = $userName['username'];
                $rowP['status'] = 'IN PROGRESS';
                $dataP['priorityOrder'][] = $rowP;
            }
            $dataFinal[] = $dataP;
        } else {
            $dataFinal[] = $dataP;
        }
        if ($getNewOders) {
            foreach ($getNewOders as $newOrder) {

                $getGigs = \frontend\modules\gloomme\gigs\models\Gigs::getDetail($newOrder['gigid']);
                $title = $getGigs['gigsTitle'];

                $userName = \common\models\UserProfile::getUserDetail($newOrder['userid']);
                $userimage = \common\models\UserProfile::getUserAvatar($newOrder['userid']);
                $getDeliverDays = GigsPricing::find()->where(['id' => $newOrder['packageid']])->one();
                $totalDeliveryDays = $getDeliverDays['delivery'] + $newOrder['extra_delivery_day'];
                $getDueDate = strtotime("+" . $totalDeliveryDays . " days", $newOrder['order_date']);
                $rowN['orderid'] = $newOrder['orderID'];
                $rowN['duedate'] = $getDueDate;
                $rowN['gigtitle'] = $title;
                $rowN['amount'] = $newOrder['order_amount'];
                $rowN['orderdate'] = $newOrder['order_date'];
                $rowN['userimage'] = $userimage;
                $rowN['username'] = $userName['username'];
                $rowN['status'] = 'NEW';
                $dataN['newOrder'][] = $rowN;
            }
            $dataFinal[] = $dataN;
        } else {
            $dataFinal[] = $dataN;
        }
        if ($getActiveOders) {
            foreach ($getActiveOders as $activeOrder) {

                $getGigs = \frontend\modules\gloomme\gigs\models\Gigs::getDetail($activeOrder['gigid']);
                $title = $getGigs['gigsTitle'];

                $userName = \common\models\UserProfile::getUserDetail($activeOrder['userid']);
                $userimage = \common\models\UserProfile::getUserAvatar($activeOrder['userid']);
                $getDeliverDays = GigsPricing::find()->where(['id' => $activeOrder['packageid']])->one();
                $totalDeliveryDays = $getDeliverDays['delivery'] + $activeOrder['extra_delivery_day'];
                $getDueDate = strtotime("+" . $totalDeliveryDays . " days", $activeOrder['order_date']);
                $rowA['orderid'] = $activeOrder['orderID'];
                $rowA['duedate'] = $getDueDate;
                $rowA['gigtitle'] = $title;
                $rowA['amount'] = $activeOrder['order_amount'];
                $rowA['orderdate'] = $activeOrder['order_date'];
                $rowA['userimage'] = $userimage;
                $rowA['username'] = $userName['username'];
                $rowA['status'] = 'ACTIVE';
                $dataA['activeOrder'][] = $rowA;
            }
            $dataFinal[] = $dataA;
        } else {
            $dataFinal[] = $dataA;
        }
        if ($getDeliveredOders) {
            foreach ($getDeliveredOders as $deliverdOrder) {

                $getGigs = \frontend\modules\gloomme\gigs\models\Gigs::getDetail($deliverdOrder['gigid']);
                $title = $getGigs['gigsTitle'];

                $userName = \common\models\UserProfile::getUserDetail($deliverdOrder['userid']);
                $userimage = \common\models\UserProfile::getUserAvatar($deliverdOrder['userid']);
                $getDeliverDays = GigsPricing::find()->where(['id' => $deliverdOrder['packageid']])->one();
                $totalDeliveryDays = $getDeliverDays['delivery'] + $deliverdOrder['extra_delivery_day'];
                $getDueDate = strtotime("+" . $totalDeliveryDays . " days", $deliverdOrder['order_date']);
                $rowD['orderid'] = $deliverdOrder['orderID'];
                $rowD['duedate'] = $getDueDate;
                $rowD['gigtitle'] = $title;
                $rowD['amount'] = $deliverdOrder['order_amount'];
                $rowD['orderdate'] = $deliverdOrder['order_date'];
                $rowD['userimage'] = $userimage;
                $rowD['username'] = $userName['username'];
                $rowD['status'] = 'DELIVERED';
                $dataD['deliverdOrder'][] = $rowD;
            }
            $dataFinal[] = $dataD;
        } else {
            $dataFinal[] = $dataD;
        }
        if ($getCompletedOders) {
            foreach ($getCompletedOders as $completeOrder) {

                $getGigs = \frontend\modules\gloomme\gigs\models\Gigs::getDetail($completeOrder['gigid']);
                $title = $getGigs['gigsTitle'];

                $userName = \common\models\UserProfile::getUserDetail($completeOrder['userid']);
                $userimage = \common\models\UserProfile::getUserAvatar($completeOrder['userid']);
                $getDeliverDays = GigsPricing::find()->where(['id' => $completeOrder['packageid']])->one();
                $totalDeliveryDays = $getDeliverDays['delivery'] + $completeOrder['extra_delivery_day'];
                $getDueDate = strtotime("+" . $totalDeliveryDays . " days", $completeOrder['order_date']);
                $rowC['orderid'] = $completeOrder['orderID'];
                $rowC['duedate'] = $getDueDate;
                $rowC['gigtitle'] = $title;
                $rowC['amount'] = $completeOrder['order_amount'];
                $rowC['orderdate'] = $completeOrder['order_date'];
                $rowC['userimage'] = $userimage;
                $rowC['username'] = $userName['username'];
                $rowC['status'] = 'COMPLETED';
                $dataC['completeOrder'][] = $rowC;
            }
            $dataFinal[] = $dataC;
        } else {
            $dataFinal[] = $dataC;
        }
        if ($getCancelledOders) {
            foreach ($getCancelledOders as $cancelledOrder) {

                $getGigs = \frontend\modules\gloomme\gigs\models\Gigs::getDetail($cancelledOrder['gigid']);
                $title = $getGigs['gigsTitle'];

                $userName = \common\models\UserProfile::getUserDetail($cancelledOrder['userid']);
                $userimage = \common\models\UserProfile::getUserAvatar($cancelledOrder['userid']);
                $getDeliverDays = GigsPricing::find()->where(['id' => $cancelledOrder['packageid']])->one();
                $totalDeliveryDays = $getDeliverDays['delivery'] + $cancelledOrder['extra_delivery_day'];
                $getDueDate = strtotime("+" . $totalDeliveryDays . " days", $cancelledOrder['order_date']);
                $rowCan['orderid'] = $cancelledOrder['orderID'];
                $rowCan['duedate'] = $getDueDate;
                $rowCan['gigtitle'] = $title;
                $rowCan['amount'] = $cancelledOrder['order_amount'];
                $rowCan['orderdate'] = $cancelledOrder['order_date'];
                $rowCan['userimage'] = $userimage;
                $rowCan['username'] = $userName['username'];
                $rowCan['status'] = 'COMPLETED';
                $dataCan['completeOrder'][] = $rowCan;
            }
            $dataFinal[] = $dataCan;
        } else {
            $dataFinal[] = $dataCan;
        }
        $arr = array('status' => 'false', 'data' => $dataFinal);
        return json_encode($arr);
    }

    public function getEarning($userid, $filterDay, $overviewtype) {
        $totalEarning = GigsOrder::getEarningsInDays($userid, $filterDay, $overviewtype);
        return $totalEarning;
    }

    public function getSellerGigs($userid, $status) {
        $data = array();
        $getAllGigs = \frontend\modules\gloomme\gigs\models\Gigs::getUserGigs($userid, $status);
        if ($getAllGigs) {
            foreach ($getAllGigs as $gigData) {
                $gigReview = self::getGigReview($gigData['id']);
                $gigReviews = json_decode($gigReview);
                $gigGallery = \frontend\modules\gloomme\gigs\models\GigsGallery::getOneImages($gigData['id']);
                $gigsPricing = GigsPricing::getStartingPrice($gigData['id']);
                $gigsFav = \frontend\modules\gloomme\gigs\models\GigsSaved::getSaved($userid, $gigData['id']);
                $favFlag = $gigsFav > 0 ? 1 : 0;

                $row['id'] = $gigData['id'];
                $row['gigsTitle'] = $gigData['gigsTitle'];
                $row['image'] = Yii::getAlias('@frontendUrl') . '/uploads/gigs/images/' . $gigGallery['gigs_image'];
                $row['amount'] = $gigsPricing['price'];
                $row['starreview'] = $gigReviews->starReview;
                $row['totalreview'] = $gigReviews->starReview > 0 ? $gigReviews->totalReview : 0;
                $row['favflag'] = $favFlag;
                $data[] = $row;
            }
            return $data;
        } else {
            return $data;
        }
    }

    public function getGigReview($gigID) {
        $countReview = 0;
        $getOrderS = GigsOrder::getGigOrder($gigID);
        $communication = $service = $recommend = $totalAvg = 0;
        if ($getOrderS) {
            foreach ($getOrderS as $orderData) {
                $getReview = \common\models\UserReview::find()->where(['orderID' => $orderData['orderID']])->all();
                $countReviews = \common\models\UserReview::find()->where(['orderID' => $orderData['orderID']])->count();
                $countReview += $countReviews;
                if ($getReview) {
                    //$communication = $service = $recommend = $totalAvg = 0;
                    foreach ($getReview as $dataReview) {
                        //echo $dataReview['average'];
                        $communication += $dataReview['communication'];
                        $service += $dataReview['serviceDescribed'];
                        $recommend += $dataReview['recommend'];
                        $totalAvg += $dataReview['average'];
                    }
                }
            }
        }

        $starReview = $countReview == 0 ? 0 : $totalAvg / $countReview;
        $arr = array('starReview' => $starReview, 'totalReview' => $countReview, 'communication' => $communication, 'serviceDescribed' => $service, 'recommend' => $recommend);
        return json_encode($arr);
    }

    public function getUserReview($userID) {
        $communication = $service = $recommend = $totalAvg = 0;
        $countReview = 1;
        $getReview = \common\models\UserReview::find()->where(['recevierID' => $userID])->all();
        $countReviews = \common\models\UserReview::find()->where(['recevierID' => $userID])->count();
        if ($countReviews > 0) {
            $countReview = $countReviews;
        } else {
            $countReview = 1;
        }
        if ($getReview) {

            foreach ($getReview as $dataReview) {
                $communication += $dataReview['communication'];
                $service += $dataReview['serviceDescribed'];
                $recommend += $dataReview['recommend'];
                $totalAvg += $dataReview['average'];
            }
        }
        $arr = array('starReview' => $totalAvg / $countReview, 'totalReview' => $countReview, 'communication' => $communication, 'serviceDescribed' => $service, 'recommend' => $recommend, 'getReviews' => $getReview);
        return $arr;
    }

    public function getBuyerReview($userID) {
        $totalAvg = 0;
        $countReview = 1;
        $getReview = \common\models\BuyerReview::find()->where(['recevierID' => $userID])->all();

        $arr = array('getReviews' => $getReview);
        return $arr;
    }

    public function getBuyerRequest($userid) {
        $data = array();
        $checkUserGigs = \frontend\modules\gloomme\gigs\models\Gigs::find()->where(['userid' => $userid])->all();
        $gigCatID = array();
        if ($checkUserGigs) {
            foreach ($checkUserGigs as $gigData) {
                $gigCatID[] = $gigData['subcatid'];
            }
        }
        $gigOrder = GigsOrder::find()->where(['!=', 'requestID', ''])->andWhere(['!=', 'order_status', 0])->all();
        $gigReqID = array();
        if ($gigOrder) {
            foreach ($gigOrder as $reqData) {
                $getRequestSent = \common\models\RequestSent::find()->where(['id' => $reqData['requestID']])->one();
                $gigReqID[] = $getRequestSent['requestID'];
            }
        }
        $getRequest = \common\models\PostRequest::getAllRequest();
        if ($getRequest) {
            foreach ($getRequest as $requestData) {
                $getRequestSent = \common\models\RequestSent::find()->where(['sendfromID' => $userid])->andWhere(['requestID' => $requestData->id])->count();
                $hourdiff = round((time() - $requestData['created_at']) / 3600, 1);
                if (in_array($requestData->subcatid, $gigCatID) && $hourdiff <= 1 && !in_array($requestData->id, $gigReqID) && $requestData->requestfromID != $userid || $requestData->requesttoID == $userid && $hourdiff <= 1 && !in_array($requestData->id, $gigReqID)) {

                    //if (in_array($requestData->subcatid, $gigCatID) || $requestData->requesttoID == $userid) {
                    $userName = \common\models\UserProfile::getUserDetail($requestData->requestfromID);
                    $userImage = \common\models\UserProfile::getUserAvatar($requestData->requestfromID);
                    $attachment = '';
                    if ($requestData->attachment != '') {
                        $attachFileName = explode('/', $requestData->attachment);
                        $attachment = '<span class="attachment brequest"><a href="' . Yii::getAlias('@frontendUrl') . ' / ' . $requestData->attachment . '"><i class="fa fa-arrow-circle-down"></i>' . $attachFileName[2] . '</a></span>';
                    }
                    $catName = \frontend\modules\gloomme\jobcategory\models\Jobcategory::findOne($requestData->catid);
                    $subcatName = \frontend\modules\gloomme\jobcategory\models\Jobcategory::findOne($requestData->subcatid);
                    $deliveryTime = $requestData->delivertime > 1 ? $requestData->delivertime . ' days' : $requestData->delivertime . ' day';
                    $budget = $requestData->budget > 0 ? '$' . $requestData->budget : '--';

                    $row['id'] = $requestData->id;
                    $row['requestdate'] = $requestData->created_at;
                    $row['username'] = $userName['username'];
                    $row['useriamge'] = $userImage;
                    $row['userid'] = $userName['id'];
                    $row['describe'] = $requestData->describe;
                    $row['catname'] = $catName['title'];
                    $row['subcatname'] = $subcatName['title'];
                    $row['delivertime'] = $requestData->delivertime == 1 ? $requestData->delivertime . ' Day' : $requestData->delivertime . ' Days';
                    $row['budget'] = $requestData->budget != '' ? '$' . $requestData->budget : '---';
                    $row['requestsent'] = $getRequestSent;
                    $data[] = $row;
                }
            }
        }
        return $data;
    }

    public function getBuyerRequestSent($userid) {
        $data = array();
        $getRequestSent = \common\models\RequestSent::find()->where(['sendfromID' => $userid])->all();
        if ($getRequestSent) {
            foreach ($getRequestSent as $requestData) {
                $deliveryTime = $requestData->deliveryTime > 1 ? $requestData->deliveryTime . ' days' : $requestData->deliveryTime . ' day';
                $budget = $requestData->budget > 0 ? '$' . $requestData->budget : '--';
                $row['requestdate'] = $requestData->created_at;
                $row['delivertime'] = $deliveryTime;
                $row['budget'] = $budget;
                $row['describe'] = $requestData->requestdescribe;
                $data[] = $row;
            }
        }
        return $data;
    }

    public function getUserLang($userID) {
        $userLang = \common\models\UserLanguage::find()->where(['userid' => $userID])->all();
        $lang = array();
        if ($userLang) {
            foreach ($userLang as $langData) {
                $languages = \common\models\Languages::findOne($langData['languageid']);
                $languageLevel = $langData['languagelevel'] == 'native_or_bilingual' ? 'Native/Bilingual' : ($langData['languagelevel'] == 'fluent' ? 'Fluent' : ($langData['languagelevel'] == 'conversational' ? 'Conversational' : 'Basic'));
                $row['name'] = $languages['title'];
                $row['level'] = $languageLevel;
                $lang[] = $row;
            }
            return $lang;
        } else {
            return $lang;
        }
    }

    public function getOrderDetail($orderId, $userID) {
        $params = array('orderid' => $orderId, 'userid' => $userID);
        $orderDetail = GigsOrder::getOrderDetail($params['orderid']);
        if ($orderDetail) {
            $gigDetail = \frontend\modules\gloomme\gigs\models\Gigs::getDetail($orderDetail['gigid']);
            $gigsImage = \frontend\modules\gloomme\gigs\models\GigsGallery::getOneImages($orderDetail['gigid']);
            $packageDetail = GigsPricing::getOnePackage($orderDetail['packageid']);
            $gigUserDetail = \common\models\UserProfile::getUserDetail($orderDetail['giguserid']);
            $userDetail = \common\models\UserProfile::getUserDetail($orderDetail['userid']);
            $userType = \common\models\UserProfile::getUserDetail($params['userid']);
            $orderMessageFinal = \common\models\OrderMessage::getOrderMessagesFinal($params['orderid'], $params['userid']);
            $orderMessage = \common\models\OrderMessage::getOrderMessages($params['orderid'], $params['userid']);
            $orderRequirement = \common\models\OrderRequirement::getOrderRequirement($params['orderid'], $params['userid']);
            $orderRequirementAttach = \common\models\OrderRequirementAttach::getOrderRequirementAttach($orderRequirement['id']);
            $userReview = \common\models\UserReview::getUserOrderReviews($params['orderid'], $params['userid']);
            $buyerReview = \common\models\BuyerReview::getBuyerOrderReviews($params['orderid'], $params['userid']);

            $userId = $params['userid'] == $orderDetail['userid'] ? $gigUserDetail['id'] : $orderDetail['userid'];
            $username = $params['userid'] == $orderDetail['userid'] ? $gigUserDetail['username'] : $userDetail['username'];
            $data['usertype'] = $userType['usertype'];
            $orderAmount = $params['userid'] == $orderDetail['userid'] ? $orderDetail['totalamt'] : $orderDetail['order_amount'];
            $row1['orderid'] = $params['orderid'];
            $row1['image'] = Yii::getAlias('@frontendUrl') . '/uploads/gigs/images/' . $gigsImage['gigs_image'];
            $row1['gigtitle'] = $gigDetail['gigsTitle'];
            $row1['userid'] = $userId;
            $row1['giguser'] = $username;
            $row1['totalamt'] = $orderAmount;
            $row1['orderqty'] = $orderDetail['order_qty'] + $orderDetail['extra_order_qty'];
            $row1['deliveryday'] = $packageDetail['delivery'] + $orderDetail['extra_delivery_day'];
            $row1['orderdate'] = $orderDetail['order_date'];
            $data['orderdetail'] = $row1;

            $row2['questionone'] = '1. What industry does this order relate to? (optional)';
            $row2['answerone'] = $orderRequirement['answerOne'];
            $row2['questiontwo'] = '2. Please send your order information';
            $row2['answertwo'] = $orderRequirement['answerTwo'];
            $row2['questionthree'] = 'Attached your order sample';
            $attach = array();
            foreach ($orderRequirementAttach as $attachData) {
                $filename = explode('.', $attachData['attachFile']);
                $rowAttach['attachimg'] = Yii::getAlias('@frontendUrl') . '/uploads/gigs/order/' . $attachData['attachFile'];
                $rowAttach['attachimgname'] = $filename[0];
                $rowAttach['attachimgextension'] = $filename[1];
                $attach[] = $rowAttach;
            }
            $row2['attachimg'] = $attach;
            $data['orderreq'] = $row2;

            if ($orderDetail['orderStatTime'] != 0) {
                $deliveryDate = $orderDetail['orderEndTime'];
                $orderStartTime = $orderDetail['orderStatTime'];
                $orderEndTime = $orderDetail['orderEndTime'];
                $row3['deliveryDate'] = $deliveryDate;
                $row3['orderStartTime'] = $orderStartTime;
                $row3['orderEndTime'] = $orderEndTime;
                $row3['currentTime'] = time();
                $data['expecteddelivery'] = $row3;
            }

            if ($orderMessageFinal) {
                $messageData = $orderMessageFinal;
                $userDetail = \common\models\UserProfile::getUserDetail($messageData['senderID']);
                $userImage = \common\models\UserProfile::getUserAvatar($messageData['senderID']);
                $getOrderAttach = \common\models\OrderMessageAttach::find()->where(['messID' => $messageData['id']])->all();

                $row4['receiverid'] = $userDetail['id'];
                $row4['userimage'] = $userImage;
                $row4['username'] = $userDetail['username'];
                $row4['message'] = $messageData['message'];
                if ($getOrderAttach) {
                    $orderAttachArray = array();
                    foreach ($getOrderAttach as $messAttach) {
                        $filename = explode('.', $messAttach['attachFile']);
                        $row5['attachimage'] = Yii::getAlias('@frontendUrl') . '/uploads/gigs/order/' . $messAttach['attachFile'];
                        $row5['imagename'] = $filename[0];
                        $row5['imageextension'] = $filename[1];
                        $orderAttachArray[] = $row5;
                    }
                    $row4['messattach'] = $orderAttachArray;
                } else {
                    $row4['messattach'] = array();
                }
                $orderArray = $row4;


                $data['message'] = $orderArray;
            }

            if ($userReview) {
                $userDetail = \common\models\UserProfile::getUserDetail($userReview['senderID']);
                $userImage = \common\models\UserProfile::getUserAvatar($userReview['senderID']);
                $row8['userimage'] = $userImage;
                $row8['username'] = $userDetail['username'];
                $row8['message'] = $userReview['reviewDes'];
//                    $row8['communication'] = $userReview['communication'];
//                    $row8['serviceDescribed'] = $userReview['serviceDescribed'];
//                    $row8['recommend'] = $userReview['recommend'];
                $row8['average'] = number_format($userReview['average'], 1);
                if ($userType['usertype'] != 2) {
                    $row8['label'] = 'My Review';
                    $data['buyerreview'] = $row8;
                } else {
                    $row8['label'] = 'Buyer Review';
                    $data['buyerreview'] = $row8;
                }
            }
            if ($buyerReview) {
                $userDetail = \common\models\UserProfile::getUserDetail($buyerReview['senderID']);
                $userImage = \common\models\UserProfile::getUserAvatar($buyerReview['senderID']);
                $row9['receiverid'] = $userDetail['id'];
                $row9['userimage'] = $userImage;
                $row9['username'] = $userDetail['username'];
                $row9['message'] = $buyerReview['reviewDes'];
                $row9['buyerreview'] = $buyerReview['buyerreview'];
                if ($userType['usertype'] == 2) {
                    $row9['label'] = 'My Review';
                    $data['sellerreview'] = $row9;
                } else {
                    $row9['label'] = 'Seller Review';
                    $data['sellerreview'] = $row9;
                }
            }
            return $data;
        }
    }

    public static function search_permute($items, $perms = array()) {
        $back = array();
        if (empty($items)) {
            $back[] = join(' ', $perms);
        } else {
            for ($i = count($items) - 1; $i >= 0; --$i) {
                $newitems = $items;
                $newperms = $perms;
                list($foo) = array_splice($newitems, $i, 1);
                array_unshift($newperms, $foo);
                $back = array_merge($back, MyHelper::search_permute($newitems, $newperms));
            }
        }
        return $back;
    }

    public static function getUserSearchListArray($words) {
        $query = new \yii\db\Query();
        $usersearch = array();
        foreach ($words as $word) {
            $usersearch[] = "user.username LIKE '%" . $word . "%'";
        }
        $v1 = implode(' or ', $usersearch);

        $query->select(['profile.user_id as id', 'user.username as query', 'CONCAT(profile.firstname," ",profile.lastname) as label', 'CONCAT(profile.avatar_base_url,"",profile.avatar_path) as avator'])
                ->from(['user_profile profile', 'user user'])
                ->where('profile.user_id = user.id')
                ->andWhere(['user.status' => User::STATUS_ACTIVE])
                ->andFilterWhere(['or', $v1])
                ->limit(20);

        $command = $query->createCommand();
        $results = $command->queryAll();
        return $results;
    }

    public static function getServiceSearchListArray($words) {
        $gigsearch = array();
        foreach ($words as $word) {
            $gigsearch[] = "gig.gigsTags LIKE '%" . $word . "%'";
            //$gigsearch[] = "gig.gigsDescription LIKE '%" . $word . "%'";
        }
        $v2 = implode(' or ', $gigsearch);

        $query = new \yii\db\Query();

        $query->select(['gig.id as id', 'gig.gigsTags as label', 'gig.gigsTags as query'])
                ->from(['gigs gig'])
                ->where(['gig.status' => 2])
                ->andFilterWhere(['or', $v2])
                ->groupBy(['gig.gigsTags'])
                ->orderBy('gig.id')
                ->limit(20);
        $command = $query->createCommand();
        $gigs = $command->queryAll();
        return $gigs;
    }

}
