<?php
use yii\helpers\Html;
use yii\grid\GridView;
/* @var $this yii\web\View */
/* @var $searchModel backend\models\CategorySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
?>
<div class="category-index">

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            'id', 'title',
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{create} {view} {delete}',
                'buttons' => [
                    'create' => function ($url, $model, $key) {
                         return Html::a('<span class="glyphicon glyphicon-plus" aria-hidden="true"></span>', $url);
                    }
                ],
            ], 
        ],
    ]); ?>

</div>