<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\CategorySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Categories';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="category-index">
    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); 
    ?>

    <p>
        <?= Html::a('Create Category', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'rowOptions'   => function ($model, $index, $widget, $grid){
      
        },
        'columns' => [
            'id', 
            [
                'attribute' => 'title',
                'value' => function ($model) {
                    if ($model->parent_id == 0 || $model->parent_id == null)
                    {
                        return Html::a($model->title, ['category/sub-category', 'parent_id' => $model->parent_id]);
                    }
                    else
                    {
                        return $model->title;
                    }    
                },                
                'format' => 'raw',
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{create} {view} {recommended} {update} {delete}',
                'buttons' => [
                    'create' => function ($url, $model, $key) {
                        return Html::a('<i class="fas fa-plus"></i>', $url);
                    },
                    'view' => function ($url, $model, $key) {
                        return Html::a('<i class="fas fa-eye"></i>', $url);
                    },
                    'recommended' => function ($url, $model, $key) {
                        return Html::a('<i class="fas fa-star"></i>', $url);
                    },
                    'update' => function ($url, $model, $key) {
                        return Html::a('<i class="fas fa-pen"></i>', $url);
                    },
                    'delete' => function ($url, $model, $key) {
                        return Html::a('<i class="fas fa-trash"></i>', $url);
                    }
                ],
            ], 'last_update', 'count'
        ],
    ]); ?>

</div>