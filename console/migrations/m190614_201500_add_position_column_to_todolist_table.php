<?php

use yii\db\Migration;

/**
 * Handles adding position to table `todolist`.
 */
class m190614_201500_add_position_column_to_todolist_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('todolist', 'max_bonus', $this->integer());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('todolist', 'max_bonus');
    }
}
