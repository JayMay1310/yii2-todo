<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "global_task".
 *
 * @property int $id
 * @property string $title
 * @property string $json_task
 */
class GlobalTask extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'global_task';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title', 'json_task'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Title',
            'json_task' => 'Json Task',
        ];
    }
}
