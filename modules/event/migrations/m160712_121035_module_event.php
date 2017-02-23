<?php
namespace app\modules\event\migrations;

use Yii;
use yii\db\Migration;

class m160712_121035_module_event extends Migration
{
    public function safeUp()
    {
        $tableOptions = null;

        if ($this->db->driverName === 'mysql')
        {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }

        $this->createTable('mail_templates', [
            'id' => $this->primaryKey(),
            'title' => $this->string()->notNull(),
            'slug' => $this->string()->notNull(),
            'subject' => $this->string()->notNull(),
            'text' => $this->text()->notNull(),
            'is_active' => $this->boolean()->defaultValue(1)
        ], $tableOptions);
        
        $templates = [
            [
                'title' => 'Активация аккаунта',
                'slug' => 'aktivacia-akkaunta',
                'subject' => 'Активация аккаунта',
                'text' => '<div class="password-reset">
                               <p>Здравствуйте [user],</p>
                               <p>Перейдите по следующей ссылке для активации аккаунта:</p>
                               <p><a href="[link]">[link]</a></p>
                           </div>',
                'is_active' => 1
            ],
            [
                'title' => 'Смена пароля',
                'slug' => 'smena-parola',
                'subject' => 'Смена пароля',
                'text' => '<div class="password-reset">
                               <p>Здравствуйте [user],</p>
                               <p>Перейдите по следующей ссылке для смены пароля:</p>
                               <p><a href="[link]">[link]</a></p>
                           </div>',
                'is_active' => 1
            ]
        ];
        Yii::$app->db->createCommand()->batchInsert('mail_templates', array_keys($templates[0]), $templates)->execute();
        
        $this->createTable('mail_queue', [
            'id' => $this->primaryKey(),
            'subject' => $this->string()->notNull(),
            'text' => $this->text()->notNull(),
            'to' => $this->string()->notNull(),
            'created_at' => $this->integer()->notNull()
        ], $tableOptions);
    }

    public function safeDown()
    {
        $this->dropTable('mail_templates');
        $this->dropTable('mail_queue');
    }
}
