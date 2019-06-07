<?php
use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\CategorySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
$this->title = $model->title;
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="category-index">
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'rowOptions'=>function($data){
            if ($data['max_count'] <= $data['work']) {
                return ['class' => 'alert-success'];
            }
            else
            {
                return ['class' => 'alert-danger'];
            }
        },
        'columns' => [
            'title', 'max_count', 'work', 
        ],
    ]); ?>
</div>
