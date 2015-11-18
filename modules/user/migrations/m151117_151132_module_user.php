<?php

use yii\db\Migration;

class m151117_151132_module_user extends Migration
{
    public function up()
    {
        $sql="
			CREATE TABLE IF NOT EXISTS `user` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `username` varchar(25) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
            `auth_key` varchar(32) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
            `password_hash` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
            `password_reset_token` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
            `email` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
            `name` varchar(60) NOT NULL,
            `surname` varchar(80) NOT NULL,
            `status` smallint(6) NOT NULL DEFAULT '10',
            `image` varchar(255) NOT NULL,
            `is_active` tinyint(1) NOT NULL,
            `created_at` int(11) NOT NULL,
            `updated_at` int(11) NOT NULL,
            PRIMARY KEY (`id`)
            ) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

            INSERT INTO `user` (`id`, `username`, `auth_key`, `password_hash`, `password_reset_token`, `email`, `name`, `surname`, `status`, `is_active`, `created_at`, `updated_at`) VALUES
            (1, 'admin', 'e_zOOmzwIQz6djKkuHIJqM-68CA17jUH', '$2y$13$LwB9MkhkfHrHdLqg7vBYjea55r23/UghBImYny.osHM5Oa5Xdyk.6', NULL, 'lake83@mail.ru', '', '', 10, 1, 1434654653, 1435262772);
            
            CREATE TABLE IF NOT EXISTS `user_group` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `status` smallint(6) NOT NULL,
            `title` varchar(50) NOT NULL,
            `is_active` tinyint(1) NOT NULL,
            PRIMARY KEY (`id`)
            ) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

            INSERT INTO `user_group` (`id`, `status`, `title`, `is_active`) VALUES
            (1, 10, 'Админ', 1);
            
            CREATE TABLE IF NOT EXISTS `user_permitted_action` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `user_status` smallint(6) NOT NULL,
            `module` blob NOT NULL,
            `action` blob NOT NULL,
            PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
		";
        
		try {
            $this->execute($sql);
        } catch (Exception $e) {
			echo  $e->getMessage() . "\n";
		}
    }

    public function down()
    {
        echo "m151117_151132_module_user cannot be reverted.\n";

        return false;
    }
}
