<?php
namespace app\modules\translations\migrations;

use yii\db\Migration;

class m160211_144629_module_translations extends Migration
{
    public function safeUp()
    {
        $tableOptions = null;

        if ($this->db->driverName === 'mysql')
        {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }

        $this->createTable('message', [
            'id' => $this->integer()->defaultValue(0),
            'language' => $this->string(16)->notNull(),
            'translation' => $this->text()->notNull()
        ], $tableOptions);
        
        $this->createTable('source_message', [
            'id' => $this->primaryKey(),
            'category' => $this->string(32)->defaultValue(null),
            'message' => $this->text()->notNull()
        ], $tableOptions);
        
        $this->addPrimaryKey('id', 'message', ['id', 'language']);
        $this->addForeignKey('message_ibfk_1', 'message', 'id', 'source_message', 'id', 'CASCADE');
        
        $this->createTable('translate_models', [
            'id' => $this->primaryKey(),
            'module' => $this->string(50)->notNull(),
            'model' => $this->string(50)->notNull(),
            'owner_id' => $this->integer()->notNull(),
            'column' => $this->string(50)->notNull(),
            'lang' => $this->string(5)->notNull(),
            'value' => $this->text()->notNull()
        ], $tableOptions);
    }

    public function safeDown()
    {
        $this->dropTable('message');
        $this->dropTable('source_message');
        $this->dropTable('translate_models');
    }
}