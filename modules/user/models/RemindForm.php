<?php
namespace app\modules\user\models;

use Yii;
use app\modules\user\models\User;
use yii\base\Model;
use app\components\CmsHelper;

/**
 * Password reset request form
 */
class RemindForm extends Model
{
    public $email;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['email', 'filter', 'filter' => 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'exist', 'targetClass' => User::className(), 'filter' => ['is_active' => 1], 'message' => Yii::t('user','Пользователь с таким e-mail не существует.')]
        ];
    }
    
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'email' => 'E-mail'
        ];
    }

    /**
     * Sends an email with a link, for resetting the password.
     * @return boolean whether the email was send
     */
    public function sendEmail($frontend)
    {
        $user = User::findOne(['is_active' => 1, 'email' => $this->email]);

        if ($user) {
            if (!User::isPasswordResetTokenValid($user->password_reset_token)) {
                $user->generatePasswordResetToken();
            }
            if ($user->save()) {
                return CmsHelper::sendMail('smena-parola', $this->email, [
                    'user' => $user->username,
                    'link' => Yii::$app->urlManager->createAbsoluteUrl([!$frontend ? '/user/user/reset' : '/reset', 'token' => $user->password_reset_token])
                ]);
            }
        }
        return false;
    }
}