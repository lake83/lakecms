<?php
namespace app\modules\contentblock\migrations;

use yii\db\Migration;

class m151221_171800_module_contentblock extends Migration
{
    public function safeUp()
    {
        $tableOptions = null;

        if ($this->db->driverName === 'mysql')
        {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }

        $this->createTable('contentblock', [
            'id' => $this->primaryKey(),
            'title' => $this->string()->notNull(),
            'text' => $this->text()->notNull(),
            'js' => $this->text()->notNull(),
            'css' => $this->text()->notNull(),
            'is_active' => $this->boolean()->defaultValue(1),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull()
        ], $tableOptions);
        
        $this->createTable('blocks', [
            'id' => $this->primaryKey(),
            'page_id' => $this->integer()->notNull(),
            'type' => $this->boolean()->notNull()->comment('1-контент,2-виджет'),
            'contentblock_id' => $this->integer()->defaultValue(null),
            'widget_action' => $this->string(100)->notNull(),
            'layout' => $this->string(50)->notNull(),
            'position' => $this->string(50)->notNull()
        ], $tableOptions);
        
        $this->createIndex('idx-contentblock_id', 'blocks', 'contentblock_id');
        $this->addForeignKey('blocks_ibfk_1', 'blocks', 'contentblock_id', 'contentblock', 'id', 'CASCADE');
        
        $this->createIndex('idx-page_id', 'blocks', 'page_id');
        $this->addForeignKey('blocks_ibfk_2', 'blocks', 'page_id', 'pages', 'id', 'CASCADE');
    }

    public function safeDown()
    {
        $this->dropForeignKey('blocks_ibfk_1', 'blocks');
        $this->dropIndex('idx-contentblock_id', 'blocks');
        
        $this->dropForeignKey('blocks_ibfk_2', 'blocks');
        $this->dropIndex('idx-page_id', 'blocks');
        
        $this->dropTable('contentblock');
        $this->dropTable('blocks');
    }
}