<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\grid\GridView;
use yii\helpers\Url;
use yii\db\Expression;
use common\models\TodoForm;

/* @var $this yii\web\View */
/* @var $model common\models\Category */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Categories', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="category-view">
  <h1><?= Html::encode($this->title) ?></h1>
</div>

<?= Html::beginForm(['task/done'], 'post'); ?>

<?= Html::dropDownList('action', '', ['Done' => 'Выполнил', 'Delete' => 'Удалить'], ['class' => 'dropdown',]) ?>

<?= GridView::widget([
  'options' => ['class' => 'table-responsive table-sm'],
  'tableOptions' => ['class' => 'table table-sm'],
  'dataProvider' => $dataProvider,
  'rowOptions' => function ($model) {
    if (isset($model->max_day)) {
      $max_day = '-' . $model->max_day . ' day';
      $red_time = strtotime($max_day, time());
    } else {
      $red_time = strtotime('-15 day', time());
    }

    $red_delta = date('Y-m-d H:i:s', $red_time);

    if ($model->last_update <= $red_delta) {
      return ['class' => 'alert-danger'];
    }
  },
  'columns' => [
    [
      'class' => 'yii\grid\CheckboxColumn',
      'checkboxOptions' => function ($model, $key, $index, $widget) {
        return ['value' => $model['id']];
      },
    ],
    'id',
    [
      'attribute' => 'title',
      'value' => function ($model) {  
          return Html::a($model->title, ['task/detail', 'task_id' => $model->id, []]);
      },
      'format' => 'raw',
    ],
    'last_update', 'count',
    [
      'attribute' => 'average',
      'visible' => false,
    ],
    [
      //Логика на первое время вынесена в view, надо перенести в контроллер
      'label' => 'average(3month)',
      'value' => function ($model) {
        $count = TodoForm::find()
          ->andWhere(['like', 'title', $model->title])
          ->andWhere(['>', 'last_update', new Expression('LAST_DAY(CURDATE()) + INTERVAL 1 DAY - INTERVAL 3 MONTH')])
          ->andWhere(['<', 'last_update', new Expression('DATE_ADD(LAST_DAY(CURDATE()), INTERVAL 1 DAY)')])
          ->andWhere(['=', 'done', '1'])
          ->asArray()->count();
        $count = $count / 90;
        $model->average = round($count, 2);
        $model->save(false);
        return round($count, 2);
      }
    ],
    [
      'label' => 'Aim Several Average',
      'value' => function ($model) {
        if ($model->aim_several_average == null) {
          return "0.3";
        } else {
          return $model->aim_several_average;
        }
      }
    ],

    [
      'class' => 'yii\grid\ActionColumn',
      'template' => '{update_task}',
      'buttons' => [
        'update_task' => function ($url, $model, $key) {
          return Html::a('<i class="fas fa-pen"></i>', $url);
        }
      ],
      'urlCreator' => function ($action, $model, $key, $index) {
        if ($action === 'update_task') {
          return Url::to(['task/update', 'id' => $model->id]);
        }
      }
    ],
  ],
]); ?>

<?= Html::submitButton('Применить', ['class' => 'btn btn-info',]); ?>

<?= Html::endForm(); ?>
<div class="container">
  <div class="row">
    <div class="col-sm-4">
      <h4 class="text-center">One Day</h4>
    </div>
    <div class="col-sm-4">
      <h4 class="text-center">One Month</h4>
    </div>
    <div class="col-sm-4">
      <h4 class="text-center">Three Month</h4>
    </div>
  </div>
  <div class="row">
    <?= $this->render('_one_day_bar', ['pie_data' => $pie_data_day]); ?>
    <?= $this->render('_one_month_bar', ['pie_data' => $pie_data_one]); ?>
    <?= $this->render('_three_month_bar', ['pie_data' => $pie_data_three]); ?>
  </div>
</div>