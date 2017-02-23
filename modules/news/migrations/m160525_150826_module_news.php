<?php
namespace app\modules\news\migrations;

use yii\db\Migration;

class m160525_150826_module_news extends Migration
{
    public function safeUp()
    {
        $tableOptions = null;

        if ($this->db->driverName === 'mysql')
        {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }

        $this->createTable('news', [
            'id' => $this->primaryKey(),
            'title' => $this->string()->notNull(),
            'slug' => $this->string()->notNull(),
            'text' => $this->text()->notNull(),
            'image' => $this->string(100)->notNull(),
            'seo_key' => $this->string()->notNull(),
            'seo_description' => $this->text()->notNull(),
            'is_active' => $this->boolean()->defaultValue(1),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull()
        ], $tableOptions);
    }

    public function safeDown()
    {
        $this->dropTable('news');
    }
}