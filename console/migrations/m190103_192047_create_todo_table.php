<?php

use yii\db\Schema;
use yii\db\Migration;

/**
 * Handles the creation of table `todo`.
 */
class m190103_192047_create_todo_table extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }
        $this->createTable('{{%category}}', [
            'id' => Schema::TYPE_PK,
            'parent_id' => Schema::TYPE_INTEGER,
            'title' => Schema::TYPE_STRING,
            'slug' => Schema::TYPE_STRING,
            'last_update' => Schema::TYPE_DATETIME,
            'count' => Schema::TYPE_INTEGER,
        ], $tableOptions);
        
        $this->addForeignKey('fk-category-parent_id-category-id', '{{%category}}', 'parent_id', '{{%category}}', 'id', 'CASCADE');

        $this->createTable('{{%todolist}}', [
            'id' => Schema::TYPE_PK,
            'category_id' => Schema::TYPE_INTEGER,
            'title' => Schema::TYPE_STRING,
            'status' => Schema::TYPE_INTEGER,
            'description' => Schema::TYPE_TEXT,      
            'last_update' => Schema::TYPE_DATETIME,
            'loop' => Schema::TYPE_BOOLEAN,
            'count' => Schema::TYPE_INTEGER,
            'done' => Schema::TYPE_INTEGER,

        ], $tableOptions);

        //$this->addColumn('todolist', 'global_task_id', $this->integer());

        $this->addForeignKey('fk-todolist-category_id-category_id', '{{%todolist}}', 'category_id', '{{%category}}', 'id', 'RESTRICT');
    }

    public function down()
    {
        $this->dropTable('{{%todolist}}');
        $this->dropTable('{{%category}}');
    }
}
