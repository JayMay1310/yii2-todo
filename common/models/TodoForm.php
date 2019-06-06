<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "word".
 *
 * @property int $id
 * @property string $word
 * @property string $translation
 * @property int $category_id
 * @property string $last_update
 * @property int $count
 *
 * @property Category $category
 */
class TodoForm extends \yii\db\ActiveRecord
{
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'todolist';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['category_id', 'status', 'count', 'done', 'max_day', 'min_day'], 'integer'],
            [['description'], 'string'],
            [['last_update'], 'safe'],
            [['title'], 'string', 'max' => 255],
            [['loop'], 'string', 'max' => 1],
            [['category_id'], 'exist', 'skipOnError' => true, 'targetClass' => Category::className(), 'targetAttribute' => ['category_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'category_id' => 'Category ID',
            'max_day' => 'Max Day',
            'min_day' => 'Min Day', 
            'title' => 'Title',
            'status' => 'Status',
            'description' => 'Description',
            'last_update' => 'Last Update',
            'loop' => 'Loop',
            'count' => 'Count',
            'done' => 'Done',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCategory()
    {
        return $this->hasOne(Category::className(), ['id' => 'category_id']);
    }
}
