<?php

use yii\db\Migration;

/**
 * Handles adding average to table `todolist`.
 */
class m200712_095524_add_average_column_to_todolist_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('todolist', 'average', $this->float());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('todolist', 'average');
    }
}
