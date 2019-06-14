<?php
use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
/* @var $this yii\web\View */
/* @var $searchModel backend\models\CategorySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
?>
<div class="category-index">

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            'id', 'title', 'max_bonus',
            [
                'format' => 'html',
                'value' => function($model) {
                    return Html::a(
                        '<span class="glyphicon glyphicon-repeat">',
                        Url::to(['globaltask/zeroing', 'id' => $model->id])
                    );
                }
            ]
        ],
    ]); ?>

</div>