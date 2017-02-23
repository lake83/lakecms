<?php
namespace app\modules\user\models;

use Yii;
use yii\base\Model;
use app\components\CmsHelper;

/**
 * Signup form
 */
class SignupForm extends Model
{
    public $username;
    public $email;
    public $password;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['username', 'match', 'pattern' => '/^[a-zA-Z0-9]\w+$/'],
            ['username', 'string', 'min' => 3, 'max' => 25],
            ['username', 'trim'],
            ['username', 'unique', 'targetClass' => User::className(), 'message' => Yii::t('user','Этот логин уже занят.')],

            ['email', 'required'],
            ['email', 'email'],
            [['email'], 'string', 'max' => 100],
            ['email', 'trim'],
            ['email', 'unique', 'targetClass' => User::className(), 'message' => Yii::t('user','Этот E-mail уже занят.')],

            ['password', 'required'],
            ['password', 'string', 'min' => 6],
        ];
    }
    
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'username' => Yii::t('user', 'Логин'),
            'password' => Yii::t('user', 'Пароль'),
            'email' => 'E-mail'
        ];
    }

    /**
     * Signs user up.
     *
     * @return User|null the saved model or null if saving fails
     */
    public function signup()
    {
        if ($this->validate()) {
            $user = new User;
            $user->username = $this->username;
            $user->email = $this->email;
            $user->setPassword($this->password);
            $user->status = 'polzovatel';
            $user->generateAuthKey();
            if ($user->save())  {
                if(!$user->activeAfterRegistration) {
                    CmsHelper::sendMail('aktivacia-akkaunta', $this->email, [
                        'user' => $user->username,
                        'link' => Yii::$app->urlManager->createAbsoluteUrl(['/user/frontend/accaunt-activation', 'token' => $user->auth_key])
                    ]);
                }
                return $user;
            }
        }
        return null;
    }
}