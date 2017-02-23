<?php
namespace app\modules\user\migrations;

use Yii;
use yii\db\Migration;

class m151117_151132_module_user extends Migration
{
    public function safeUp()
    {
        $tableOptions = null;

        if ($this->db->driverName === 'mysql')
        {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }

        $this->createTable('user', [
            'id' => $this->primaryKey(),
            'username' => $this->string(25)->notNull(),
            'auth_key' => $this->string(32)->notNull(),
            'password_hash' => $this->string()->notNull(),
            'password_reset_token' => $this->string()->defaultValue(null),
            'email' => $this->string(100)->notNull(),
            'name' => $this->string(60)->notNull(),
            'surname' => $this->string(80)->notNull(),
            'status' => $this->string(50)->notNull(),
            'image' => $this->string(100)->notNull(),
            'is_active' => $this->boolean()->notNull(),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull()
        ], $tableOptions);
        
        $this->insert('user', [
            'username' => 'admin',
            'auth_key' => Yii::$app->security->generateRandomString(),
            'password_hash' => Yii::$app->security->generatePasswordHash('admin'),
            'password_reset_token' => null,
            'email' => 'lake83@mail.ru',
            'name' => '',
            'surname' => '',
            'status' => 'admin',
            'image' => '',
            'is_active' => 1,
            'created_at' => time(),
            'updated_at' => time()
        ]);
        
        $this->createTable('group', [
            'id' => $this->primaryKey(),
            'status' => $this->string(50)->notNull(),
            'title' => $this->string(50)->notNull(),
            'is_active' => $this->boolean()->notNull()
        ], $tableOptions);
        
        $this->createIndex('idx-status', 'group', 'status');
        
        $groups = [
            [
                'status' => 'admin',
                'title' => 'Админ',
                'is_active' => 1
            ],
            [
                'status' => 'polzovatel',
                'title' => 'Пользователь',
                'is_active' => 1
            ]
        ];
        Yii::$app->db->createCommand()->batchInsert('group', array_keys($groups[0]), $groups)->execute();
        
        $this->createTable('permissions', [
            'id' => $this->primaryKey(),
            'user_status' => $this->string(50)->notNull(),
            'module' => $this->string(50)->notNull(),
            'actions' => $this->text()->notNull()
        ], $tableOptions);
        
        $this->createIndex('idx-user_status', 'permissions', 'user_status');
        $this->addForeignKey('permissions_ibfk_1', 'permissions', 'user_status', 'group', 'status', 'CASCADE');
        
        $permissions = [
            [
                'user_status' => 'admin',
                'module' => 'user',
                'actions' => '{"user":["index","create","update","delete","login"],"group":["index","create","update","delete"]}'
            ],
            [
                'user_status' => 'admin',
                'module' => 'structure',
                'actions' => '{"structure":["index","create","update","delete","page","text","widget","clear"]}'
            ],
            [
                'user_status' => 'admin',
                'module' => 'contentblock',
                'actions' => '{"contentblock":["index","create","update","delete"]}'
            ],
            [
                'user_status' => 'admin',
                'module' => 'translations',
                'actions' => '{"translations":["index","scan","update","delete"],"translate-models":["index","list","translate","delete"]}'
            ],
            [
                'user_status' => 'admin',
                'module' => 'news',
                'actions' => '{"news":["index","create","update","delete"]}'
            ],
            [
                'user_status' => 'admin',
                'module' => 'menu',
                'actions' => '{"menu":["index","create","update","delete"],"menu-items":["index","create","update","delete"]}'
            ],
            [
                'user_status' => 'admin',
                'module' => 'event',
                'actions' => '{"mail-templates":["index","create","update","delete"],"mail-queue":["index","view","delete","delete-all"]}'
            ],
            [
                'user_status' => 'polzovatel',
                'module' => 'user',
                'actions' => '[]'
            ]
        ];
        Yii::$app->db->createCommand()->batchInsert('permissions', array_keys($permissions[0]), $permissions)->execute();
        
        $this->createTable('settings', [
            'id' => $this->primaryKey(),
            'name' => $this->string(50)->notNull(),
            'value' => $this->string()->notNull(),
            'label' => $this->string(100)->notNull(),
            'icon' => $this->string(50)->notNull(),
            'rules' => $this->string(50)->notNull(),
            'hint' => $this->string()->notNull()
        ], $tableOptions);
        
        $settings = [
            [
                'name' => 'adminEmail',
                'value' => 'admin@example.com',
                'label' => 'E-mail администратора',
                'icon' => 'fa-envelope-o',
                'rules' => 'email',
                'hint' => 'Используется для связи с администратором сайта.'
            ],
            [
                'name' => 'user.passwordResetTokenExpire',
                'value' => '86400',
                'label' => 'Время на восстановление пароля (сек.)',
                'icon' => 'fa-clock-o',
                'rules' => 'integer',
                'hint' => 'По истечении указанного срока запрос на смену пароля становится не действительным.'
            ],
            [
                'name' => 'page_layouts',
                'value' => 'homepage=>Главная страница, one_column=>Одна колонка, two_column=>Две колонки',
                'label' => 'Шаблоны страниц',
                'icon' => 'fa-list-alt',
                'rules' => 'safe',
                'hint' => 'Шаблоны отображения страниц сайта вида: название_файла=>Название шаблона, one_column=>Одна колонка, ...'
            ],
            [
                'name' => 'languages',
                'value' => 'ru=>Русский, uk=>Українська, en=>English',
                'label' => 'Языки',
                'icon' => 'fa-language',
                'rules' => 'safe',
                'hint' => 'Языки сайта (при добавлении более одного автоматически включается модуль Мультиязычность и переключение языков на сайте) вида: обозначение=>Название, en=>English, ...'
            ],
            [
                'name' => 'skin',
                'value' => 'skin-green',
                'label' => 'Тема админ панели',
                'icon' => 'fa-paint-brush',
                'rules' => 'safe',
                'hint' => 'Цветовое оформление. Варианты: skin-blue, skin-black, skin-red, skin-yellow, skin-purple, skin-green, skin-blue-light, skin-black-light, skin-red-light, skin-yellow-light, skin-purple-light, skin-green-light'
            ]
        ];
        Yii::$app->db->createCommand()->batchInsert('settings', array_keys($settings[0]), $settings)->execute();
    }

    public function safeDown()
    {
        $this->dropTable('user');
        $this->dropTable('group');
        $this->dropTable('permissions');
        $this->dropTable('settings');                
    }
}