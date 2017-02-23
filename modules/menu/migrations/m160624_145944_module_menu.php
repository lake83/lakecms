<?php
namespace app\modules\menu\migrations;

use yii\db\Migration;

class m160624_145944_module_menu extends Migration
{
    public function safeUp()
    {
        $tableOptions = null;

        if ($this->db->driverName === 'mysql')
        {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }

        $this->createTable('menu', [
            'id' => $this->primaryKey(),
            'title' => $this->string()->notNull(),
            'slug' => $this->string()->notNull(),
            'is_active' => $this->boolean()->defaultValue(1)
        ], $tableOptions);
        
        $this->createTable('menu_items', [
            'id' => $this->primaryKey(),
            'title' => $this->string()->notNull(),
            'menu_id' => $this->integer()->notNull(),
            'parent_id' => $this->integer()->defaultValue(0),
            'page_id' => $this->integer()->notNull(),
            'link' => $this->string()->notNull(),
            'options' => $this->string()->notNull(),
            'before_link' => $this->string()->notNull(),
            'after_link' => $this->string()->notNull(),
            'only_guest_show' => $this->boolean()->defaultValue(0),
            'guest_not_show' => $this->boolean()->defaultValue(0),
            'is_active' => $this->boolean()->defaultValue(1)
        ], $tableOptions);
        
        $this->createIndex('idx-menu_id', 'menu_items', 'menu_id');
        $this->addForeignKey('menu_items_ibfk_1', 'menu_items', 'menu_id', 'menu', 'id', 'CASCADE');
    }

    public function safeDown()
    {
        $this->dropForeignKey('menu_items_ibfk_1', 'menu_items');
        $this->dropIndex('idx-menu_id', 'menu_items');
        
        $this->dropTable('menu');
        $this->dropTable('menu_items');
    }
}
