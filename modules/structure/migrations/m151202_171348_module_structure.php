<?php
namespace app\modules\structure\migrations;

use yii\db\Migration;

class m151202_171348_module_structure extends Migration
{
    public function safeUp()
    {
        $tableOptions = null;

        if ($this->db->driverName === 'mysql')
        {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }

        $this->createTable('pages', [
            'id' => $this->primaryKey(),
            'title' => $this->string()->notNull(),
            'seo_key' => $this->string()->notNull(),
            'seo_description' => $this->text()->notNull(),
            'parent_id' => $this->integer()->defaultValue(0),
            'url' => $this->string()->notNull(),
            'layout' => $this->string(50)->notNull(),
            'is_active' => $this->boolean()->defaultValue(1)
        ], $tableOptions);
        
        $this->insert('pages', [
            'title' => 'Index',
            'seo_key' => '',
            'seo_description' => '',
            'parent_id' => 0,
            'url' => '',
            'layout' => 'homepage',
            'is_active' => 1
        ]);
    }

    public function safeDown()
    {
        $this->dropTable('pages');
    }
}