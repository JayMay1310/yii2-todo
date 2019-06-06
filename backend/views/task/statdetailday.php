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
            'id', 'title', 'last_update',     
        ],
    ]); ?>

</div>