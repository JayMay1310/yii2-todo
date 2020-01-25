<?php

use yii\db\Migration;

/**
 * Handles adding position to table `todolist`.
 */
class m190723_000045_add_position_column_to_todolist_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('todolist', 'aim_several_average', $this->string()->defaultValue('0.3'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('todolist', 'aim_several_average');
    }
}
