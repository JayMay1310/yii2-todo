<?php

use yii\db\Migration;
use yii\db\Schema;
/**
 * Handles the creation of table `global_task`.
 */
class m190128_023534_create_global_task_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }
        
        $this->createTable('{{%global_task}}', [
            'id' => Schema::TYPE_PK,
            'title' => Schema::TYPE_STRING,
            'json_task' => Schema::TYPE_STRING,
        ], $tableOptions);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('global_task');
    }
}
