<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "categories".
 *
 * @property int $id
 * @property int $parent_id
 * @property string $title
 * @property string $slug
 * @property string $description
 * @property int $status
 * @property int $created_at
 * @property int $updated_at
 * @property int $created_by
 * @property int $updated_by
 */
class MediaUpload extends \yii\db\ActiveRecord
{
	const STATUS_NOT_ACTIVE = 0;
    const STATUS_ACTIVE = 1;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'media_upload';
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::class,
            BlameableBehavior::class,
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
             [['name','file_name','url','upload_base_path','size','file_type'], 'string'],
             [['created_at','updated_at','created_by','updated_by'],'safe']
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'cat_id' => 'Category ID',
            'city' => 'City',
            'address' => 'Address',
            'title' => 'Title',
            'tags' => 'tags',
            'slug' => 'Slug',
            'description' => 'Description',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
    
     public function uploadAdImage($ad_id,$files){
		 
        $model = new MediaUpload();
        $avatar=$model->upload_base_path;
        $response = $file_tmp = $file_name = array();
		$typename = 'images';
		
        if (isset($files[$typename]['tmp_name']) && isset($files[$typename]['name'])) {
                $file_tmps  = $files[$typename]['tmp_name'];
                $file_names = $files[$typename]['name'];
                $file_sizes = $files[$typename]['size'];
                $file_error = $files[$typename]['error'];
            
            if (!is_dir("../../storage/web/source/advertisement/"))
                mkdir("../../storage/web/source/advertisement", 0775, true);
            $uploadDir = Yii::getAlias('@storage/web/source/advertisement/');

            foreach($file_names as $key=>$file_name){
                if($file_error[$key] == 0){
                    if(!empty($file_name)){
                        $extension = pathinfo($file_name, PATHINFO_EXTENSION);
                        $filename=pathinfo($file_name, PATHINFO_FILENAME);
                        $newimage = time() .rand(). '_add.' . $extension;
                        $file_tmp=$file_tmps[$key];
                        $size=$file_sizes[$key];

                        if (!move_uploaded_file($file_tmp, $uploadDir. $newimage)) {
                            $response["status"] = 0;
                            $response["error"] = true;
                            $response['message'] = 'Unable to upload image';
                        }
                        $mediaupload =new MediaUpload();
                        $mediaupload->upload_base_path	 = '/storage/web/source/advertisement/';
                        $mediaupload->url = Yii::getAlias('@frontendUrl');
                        $mediaupload->file_name	=$newimage;
                        $mediaupload->name=$filename;
                        $mediaupload->size=(string)$size;
                        $mediaupload->duration=0;
                        $mediaupload->type='image';
                        $mediaupload->file_type='image';

                        if ($mediaupload->save()) {							
							$adImageModel=new PostAdImages();
							$adImageModel->ad_id=$ad_id;
							$adImageModel->media_id=$mediaupload->id;
							$adImageModel->save();
							
                            $response[$key]["status"] = 1;
                            $response[$key]["error"] = false;
                            $response[$key]['message'] = 'Ads image updated';
                        }
                        else{
                            $response[$key]["status"] = 0;
                            $response[$key]["error"] = true;
                            $response[$key]["data"]=$mediaupload->getErrors();
                            $response[$key]['message'] = 'Ads image not updated';
                        }
                    }
                    else{
                        $response[$key]["status"] = 0;
                        $response[$key]["error"] = true;
                        $response[$key]["data"]='';
                        $response[$key]['message'] = 'Temp file not found';
                    }
                }
                else{
                    $response[$key]["status"] = 0;
                    $response[$key]["error"] = true;
                    $response[$key]["data"]='';
                    $response[$key]['message'] = 'Error file found';
                }
            }
        }
        else{
            $response["status"] = 0;
            $response["error"] = true;
            $response['message'] = 'Image Required';
        }
        return $response;
    }
        
    public static function getImageByMediaId($mediaId)
    {
		$image = (new \yii\db\Query())
					->select(['*'])
					->from('media_upload')
					->where(['id' => $mediaId])
					->one();
		return  $image;
    }    
}
