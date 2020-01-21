<?php

namespace common\models;

use trntv\filekit\behaviors\UploadBehavior;
use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\SluggableBehavior;
use yii\db\Query;
use yii\helpers\Url;

/**
 * This is the model class for table "user_profile".
 *
 * @property integer $user_id
 * @property integer $locale
 * @property string $name
 * @property string $slug
 * @property string $picture
 * @property string $avatar
 * @property string $avatar_path
 * @property string $avatar_base_url
 * @property integer $gender
 * @property integer $created_at
 * @property integer $updated_at
 *
 * @property User $user
 */
class UserProfile extends ActiveRecord
{
    const GENDER_MALE = 1;
    const GENDER_FEMALE = 2;
    
    const SCENARIO_UPROFILE='updateprofile';


    /**
     * @var
     */
    public $picture;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%user_profile}}';
    }

    /**
     * @return array
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
            'slug' => [
                'class' => SluggableBehavior::className(),
                'attribute' => 'name',
                'ensureUnique' => true,
                'immutable' => true,
            ],
            /*'picture' => [
                'class' => UploadBehavior::class,
                'attribute' => 'picture',
                'pathAttribute' => 'avatar_path',
                'baseUrlAttribute' => 'avatar_base_url'
            ]*/
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id','name'], 'required'],
            [['facebook_url','twitter_url','google_plus_url','instagram_url','linkedin_url','youtube_url','website'], 'url','defaultScheme' => 'http'],
            [['avatar_path'], 'safe'],
            [['avatar_base_url'], 'safe'],
            [['newsletter'], 'safe'],
            [['about'], 'safe'],
            [['address'], 'safe'],
            //[['phone'],'required','on'=>self::SCENARIO_UPROFILE ],
            //[['address'],'required','on'=>self::SCENARIO_UPROFILE ],
            [['website'],'url','on'=>self::SCENARIO_UPROFILE ],
            [['user_id', 'gender'], 'integer'],
            [['gender'], 'in', 'range' => [NULL, self::GENDER_FEMALE, self::GENDER_MALE]],
            [['name', 'slug', 'avatar_path', 'avatar_base_url'], 'string', 'max' => 255],
            ['locale', 'default', 'value' => Yii::$app->language],
            ['locale', 'in', 'range' => array_keys(Yii::$app->params['availableLocales'])],
            [['created_by', 'updated_by','slug','picture'],'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'user_id' => Yii::t('common', 'User ID'),
            'name' => Yii::t('common', 'Fullname'),
            'locale' => Yii::t('common', 'Locale'),
            'picture' => Yii::t('common', 'Picture'),
            'gender' => Yii::t('common', 'Gender'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }

    /**
     * @return null|string
     */
    public function getFullName()
    {
        if ($this->name) {
          return $this->name;
        }
        return null;
    }
    /**
     * @param null $default
     * @return bool|null|string
     */
    public function getAvatar($default = null)
    {
        return $this->avatar_path
            ? Yii::getAlias($this->avatar_base_url . '/' . $this->avatar_path)
            : $default;
    }
     public function setModel($model) {
        return $model;
    }
    
     public static function getByUsername($username) {
        $query = new Query();
        $query->select(['*'])->from('user')
                ->join('LEFT JOIN', 'user_profile', 'user_profile.user_id = user.id')
                ->andWhere(['user.username' => $username]);

        $command = $query->createCommand();
        return $command->queryOne();
    }
    
      public static function getUserDetail($userId) {
        $query = new Query();
        $query->select(['*'])->from('user')
                ->join('LEFT JOIN', 'user_profile', 'user_profile.user_id = user.id')
                ->andWhere(['user_profile.user_id' => $userId]);

        $command = $query->createCommand();
        return $command->queryOne();
    }
}
