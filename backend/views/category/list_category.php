<?php

use yii\helpers\Html;
use yii\grid\GridView;
use common\models\Category;

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
        'columns' => [
            'id',
            [
                'attribute' => 'parent_id',
                'value' => function ($model) {
                    $category = Category::findOne($model->parent_id);
                    return $category['title'] ?? 'root';
                },                
                'format' => 'raw',
            ], 
            'title',
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