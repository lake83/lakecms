<?php
namespace app\modules\user\models;

use Yii;
use yii\db\Query;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;
use yii\base\NotSupportedException;
use yii\web\ForbiddenHttpException;
use yii\behaviors\TimestampBehavior;

class User extends ActiveRecord implements IdentityInterface
{
    /**
     * @var string Используется при смене пароля в профиле пользователя.
     */
    public $new_password;
    
    /**
     * @var boolean Если false, после регистрации пользователю будет отослан email для подтверждения.
     */
    public $activeAfterRegistration = false;
    
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
            TimestampBehavior::className()
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['username', 'match', 'pattern' => '/^[a-zA-Z0-9]\w+$/'],
            ['username', 'string', 'min' => 3, 'max' => 25],
            ['username', 'trim'],
            ['username', 'unique', 'message' => Yii::t('user', 'Этот логин уже занят.')],
            
            ['email', 'required'],
            ['email', 'email'],
            [['email', 'image'], 'string', 'max' => 100],
            ['email', 'trim'],
            ['email', 'unique', 'message' => Yii::t('user', 'Этот E-mail уже занят.')],
            
            ['password_hash', 'required', 'on' => 'createUser'],
            [['password_hash' , 'new_password'], 'string', 'min' => 6],
            
            [['name'], 'string', 'max' => 60],
            [['surname'], 'string', 'max' => 80],
            
            [['is_active'], 'integer'],
            ['is_active', 'default', 'value' => $this->activeAfterRegistration ? 1 : 0],
            ['status', 'required']
        ];
    }
    
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'username' => Yii::t('user', 'Логин'),
            'email' => 'E-mail',
            'password_hash' => Yii::t('user', 'Пароль'),
            'new_password' => Yii::t('user', 'Пароль'),
            'name' => Yii::t('user', 'Имя'),
            'surname' => Yii::t('user', 'Фамилия'),
            'status' => 'Статус',
            'image' => 'Фото',
            'is_active' => 'Активно',
            'created_at' => 'Создан',
            'updated_at' => 'Обновлен'
        ];
    }
    
    /**
     * @inheritdoc
     */
    public function beforeSave($insert)
    {
        if (!$this->isNewRecord && $this->status == 'admin' && $this->is_active == 0 && (self::find()->where(['status' => 'admin', 'is_active' => 1])->count()) == 1) {
            throw new ForbiddenHttpException('Должен быть хотя бы один действующий администратор.');
        }
        if ($this->scenario == 'createUser') {
            $this->generateAuthKey();
            $this->setPassword($this->password_hash);
            if (empty($this->username)) {
                $this->generateUsername();
            } 
        }
        if (!$this->isNewRecord) {
            if (!empty($this->new_password)) {
                $this->setPassword($this->new_password);
            }
        } 
        return parent::beforeSave($insert);
    }
    
    /**
     * @inheritdoc
     */
    public function afterSave($insert, $changedAttributes)
    {
        if (!$this->isNewRecord && $this->id == Yii::$app->user->id && $this->is_active == 0) {
            Yii::$app->response->redirect('/admin')->send();
        }
        if (Yii::$app->cache->get('user_' . $this->id) !== false) {
            Yii::$app->cache->delete('user_' . $this->id);
        }
        parent::afterSave($insert, $changedAttributes);
    }
    
    /**
     * Генерация нового логина из email или создается вида "user1".
     */
    public function generateUsername()
    {
        $this->username = explode('@', $this->email)[0];
        if ($this->validate(['username'])) {
            return $this->username;
        }
        while (!$this->validate(['username'])) {
            $row = (new Query())->from('{{%user}}')->select('MAX(id) as id')->one();
            $this->username = 'user' . ++$row['id'];
        }
        return $this->username;
    }

    /**
     * @inheritdoc
     */
    public static function findIdentity($id)
    {
        if (!$user = Yii::$app->cache->get('user_' . $id)) {
            $user = static::findOne(['id' => $id, 'is_active' => 1]);
            Yii::$app->cache->set('user_' . $id, $user);
        } 
        return $user;
    }

    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }

    /**
     * Finds user by username or email
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        return static::find()->where(['is_active' => 1])->andWhere(['or', ['username' => $username],['email' => $username]])->one();
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
        $query = $this->hasOne(Group::className(), ['status' => 'status']);
        if (!Yii::$app->user->isGuest) {
            $query->andWhere(['is_active' => 1]);
        }            
        return $query;
    }
}