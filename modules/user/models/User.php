<?php
namespace app\modules\user\models;

use Yii;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;

class User extends ActiveRecord implements IdentityInterface
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%user}}';
    }
    
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
            [
                'class' => '\yiidreamteam\upload\ImageUploadBehavior',
                'attribute' => 'image',
                'thumbs' => ['thumb' => ['width' => 48, 'height' => 48]],
                'thumbPath' => '@webroot/images/users/thumbs/[[basename]]',
                'thumbUrl' => '/images/users/thumbs/[[basename]]',
                'filePath' => '@webroot/images/users/[[basename]]',
                'fileUrl' => '/images/users/[[basename]]'
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['username', 'required'],
            ['username', 'match', 'pattern' => '/^[a-zA-Z0-9]\w+$/'],
            ['username', 'string', 'min' => 3, 'max' => 25],
            ['username', 'trim'],
            ['username', 'unique', 'message' => Yii::t('modules/user/main', 'Этот логин уже занят.')],
            
            ['email', 'required'],
            ['email', 'email'],
            [['email'], 'string', 'max' => 100],
            ['email', 'trim'],
            ['email', 'unique', 'message' => Yii::t('modules/user/main', 'Этот E-mail уже занят.')],
            
            ['password_hash', 'required', 'on' => 'createUser'],
            ['password_hash', 'string', 'min' => 6],
            
            [['name'], 'string', 'max' => 60],
            [['surname'], 'string', 'max' => 80],
            
            [['status', 'is_active'], 'integer'],
            ['status', 'required'],
            
            [['image'], 'file', 'skipOnEmpty' => true, 'extensions' => 'png, jpeg, jpg, gif']
        ];
    }
    
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'username' => Yii::t('modules/user/main', 'Логин'),
            'email' => 'E-mail',
            'password_hash' => Yii::t('modules/user/main', 'Пароль'),
            'name' => Yii::t('modules/user/main', 'Имя'),
            'surname' => Yii::t('modules/user/main', 'Фамилия'),
            'status' => 'Статус',
            'image' => 'Фото',
            'is_active' => 'Активно',
            'created_at' => 'Создан',
            'updated_at' => 'Обновлен',
        ];
    }
    
    /**
     * @inheritdoc
     */
    public function beforeSave($insert)
    {
        if ($this->scenario == 'createUser')
        {
            $this->generateAuthKey();
            $this->setPassword($this->password_hash);
        }
        return parent::beforeSave($insert);
    }

    /**
     * @inheritdoc
     */
    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id, 'is_active' => 1]);
    }

    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        return static::findOne(['username' => $username, 'is_active' => 1]);
    }

    /**
     * Finds user by password reset token
     *
     * @param string $token password reset token
     * @return static|null
     */
    public static function findByPasswordResetToken($token)
    {
        if (!static::isPasswordResetTokenValid($token)) {
            return null;
        }

        return static::findOne([
            'password_reset_token' => $token,
            'is_active' => 1,
        ]);
    }

    /**
     * Finds out if password reset token is valid
     *
     * @param string $token password reset token
     * @return boolean
     */
    public static function isPasswordResetTokenValid($token)
    {
        if (empty($token)) {
            return false;
        }
        $expire = Yii::$app->params['user.passwordResetTokenExpire'];
        $parts = explode('_', $token);
        $timestamp = (int) end($parts);
        return $timestamp + $expire >= time();
    }

    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }

    /**
     * @inheritdoc
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return boolean if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }

    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }

    /**
     * Generates "remember me" authentication key
     */
    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    /**
     * Generates new password reset token
     */
    public function generatePasswordResetToken()
    {
        $this->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    /**
     * Removes password reset token
     */
    public function removePasswordResetToken()
    {
        $this->password_reset_token = null;
    }
    
    public function getGroup()
    {
        return $this->hasOne(UserGroup::className(), ['status' => 'status']);
    }
}