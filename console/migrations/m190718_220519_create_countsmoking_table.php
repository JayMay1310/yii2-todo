<?php

use yii\db\Migration;

/**
 * Handles the creation of table `countsmoking`.
 */
class m190718_220519_create_countsmoking_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('countsmoking', [
            'id' => $this->primaryKey(),
            'data' => $this->timestamp(),
            'count' => $this->integer(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('countsmoking');
    }
}
