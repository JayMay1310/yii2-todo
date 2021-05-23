<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\CategorySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

?>
    <div class="col-11">
        <p><?= $aimTask ?></p>
        <p><?= $aimDayTask ?></p>
    </div>
<div class="category-index">
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            [
                'label' => 'Data',
                'format' => 'raw',
                'value' => function ($data) {
                    return Html::a($data['data'], 'statdetailday?date=' . $data['data']);
                },
            ],
            'count',
        ],
    ]); ?>

</div>