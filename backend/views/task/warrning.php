<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\grid\GridView;



/* @var $this yii\web\View */
/* @var $model common\models\Category */
$this->title = "Задачи требующие решения (>10 дней)";
$this->params['breadcrumbs'][] = ['label' => 'Categories', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="category-view">
    <h1>Задачи требующие решения min day </h1>
</div>

<?=Html::beginForm(['task/done'],'post');?>

<?=Html::dropDownList('action','',['Done'=>'Выполнил', 'Delete'=>'Удалить'],['class'=>'dropdown',])?>

<?=GridView::widget([
    'options' => ['class' => 'table-responsive'],
    'tableOptions' => ['class' => 'table table-condensed'],
    'dataProvider' => $dataProvider,
    'rowOptions'=>function($model){        
        $red_time = strtotime('-10 day', time());
        $red_delta = date('Y-m-d H:i:s', $red_time);

        if ($model->last_update <= $red_delta) {
            return ['class' => 'alert-danger'];
        }
    },
    'columns' => [
       [
         'class' => 'yii\grid\CheckboxColumn',
         'checkboxOptions' => function($model, $key, $index, $widget) {
            return ['value' => $model['id'] ];
          },
        ],
       'id', 'title', 'last_update', 'count'],
  ]); ?>

<?=Html::submitButton('Применить', ['class' => 'btn btn-info',]); ?>

<?= Html::endForm(); ?> 