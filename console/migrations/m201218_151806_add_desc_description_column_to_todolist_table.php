<?php

use yii\db\Migration;

/**
 * Handles adding desc_description to table `todolist`.
 */
class m201218_151806_add_desc_description_column_to_todolist_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('todolist', 'description', $this->string()->after('title'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('todolist', 'description');
    }
}
