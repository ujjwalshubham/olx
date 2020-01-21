<?php

namespace common\components;
use common\models\Categories;
use common\models\CategoriesMedia;
use common\models\MediaUpload;
use common\models\Settings;
use common\models\UserProfile;
use Yii;
use yii\helpers\Url;

class AppFileUploads {

    public static function updateProfileImage($user_id,$files){
		
		$s3Enable = Settings::getSettingByName('s3_bucket');
		$s3BucketPath = Settings::getSettingByName('s3_bucket_url');
		$localPath = Settings::getSettingByName('image_url_localpath');
				
        $model = UserProfile::findOne(['user_id' => $user_id]);
        $avatar=$model->avatar_path;
        
        $response = $file_tmp = $file_name = array();

        if (isset($files['UserProfile']['tmp_name']) && isset($files['UserProfile']['name'])) {
            $file_tmp = $files['UserProfile']['tmp_name'];
            $file_name = $files['UserProfile']['name'];
        }
        $path = Url::to('@storageUrl');
        $dir = Url::to('@storage');
        $dir=$dir.'/web/source/avator/';
     
        
        if (!is_dir("../../storage/web/source/avator/"))
		mkdir("../../storage/web/source/avator/", 0775, true);
		
        if (isset($file_tmp['avatar_path']) && !empty($file_tmp['avatar_path'])) {
		
            $extension = pathinfo($file_name['avatar_path'], PATHINFO_EXTENSION);
            $newimage = time() . '_avatar.' . $extension;
            if (!move_uploaded_file($file_tmp['avatar_path'], $dir . $newimage)) {
                $response["status"] = 0;
                $response["error"] = true;
                $response['message'] = Yii::t('frontend','Unable to upload image') ;
                return $response;
            }
            else{
				$s3 = Yii::$app->get('s3');
                $avatar = '/avator/' . $newimage;
                $result = $s3->commands()->upload('avator/'.$newimage, $dir.$newimage)->execute();
         
            }
        }
        $model->avatar_path = $avatar;
        $model->avatar_base_url = $path.'/web/source';
       
		
        if ($model->save()) {
            $response["status"] = 1;
            $response["error"] = false;
            $response['message'] = Yii::t('frontend', 'Profile image updated');
        }
        else{
            $response["status"] = 0;
            $response["error"] = true;
            $response["data"]=$model->getErrors();
            $response['message'] = Yii::t('frontend', 'Profile image not updated');
        }
        return $response;
    }

    public static function updateCategoryImage($category_id,$files){
        $model = Categories::findOne(['id' => $category_id]);
        $categoryImage = CategoriesMedia::find()->where(['category_id'=>$category_id])->one();
        if(!empty($categoryImage)){
            $mediaupload=MediaUpload::findOne($categoryImage->media_id);
        }
        else{
            $categoryImage=new CategoriesMedia();
            $mediaupload=new MediaUpload();
        }
        $files=$files['Categories'];

        $response = $file_tmp = $file_name = array();

        if(isset($files['name']['image']) &&
            !empty($files['name']['image']) && !empty($files['tmp_name']['image'])) {

            $file_tmp = $files['tmp_name']['image'];
            $file_name = $files['name']['image'];
            $uploadDir = Yii::getAlias('@storage/web/source/categories/');
            $extension = pathinfo($file_name, PATHINFO_EXTENSION);
            $newimage = time() .rand(). '_category.' . $extension;

            $filename=pathinfo($file_name, PATHINFO_FILENAME);
            $size=$files['size']['image'];

            if (!is_dir("../../storage/web/source/categories/"))
                mkdir("../../storage/web/source/categories", 0775, true);

            if (!move_uploaded_file($file_tmp, $uploadDir. $newimage)) {
                $response["status"] = 0;
                $response["error"] = true;
                $response['message'] = Yii::t('frontend', 'Unable to upload image');
            }
            $mediaupload->upload_base_path	 = '/storage/web/source/categories/';
            $mediaupload->url = Yii::getAlias('@frontendUrl');
            $mediaupload->file_name	=$newimage;
            $mediaupload->name=$filename;
            $mediaupload->size=(string)$size;
            $mediaupload->duration=0;
            $mediaupload->type='image';
            $mediaupload->file_type='image';

            if ($mediaupload->save()) {
                $categoryImage->category_id=$category_id;
                $categoryImage->media_id=$mediaupload->id;
                $categoryImage->save();

                $response["status"] = 1;
                $response["error"] = false;
                $response['message'] = Yii::t('frontend', 'Category image updated');
            }
            else{
                $response["status"] = 0;
                $response["error"] = true;
                $response['message'] = Yii::t('frontend', 'Category image not updated');
            }
        }
        return true;
    }
   
    
}
