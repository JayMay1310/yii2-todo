<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use common\models\TodoForm;

/* @var $this yii\web\View */
/* @var $model backend\models\GlobalTask */
/* @var $form ActiveForm */
?>
<div class="create">

<?php
    $items = TodoForm::find()->select(['title', 'id'])->andWhere(['=', 'done', '0'])->indexBy('id')->column();
    $params = [
        'prompt' => 'Выберите задачу...'
    ];
?>

    <?php $form = ActiveForm::begin(); ?>

        <?= $form->field($model, 'title') ?>
        <?= Html::dropDownList('task1', 0, $items, $params) ?>
        <?= Html::textInput('input1'); ?>
        <?= Html::dropDownList('task2', 0, $items, $params) ?>
        <?= Html::textInput('input2'); ?>
        <?= Html::dropDownList('task3', 0, $items, $params) ?>
        <?= Html::textInput('input3'); ?>
        <?= Html::dropDownList('task4', 0, $items, $params) ?>
        <?= Html::textInput('input4'); ?>
        <?= Html::dropDownList('task5', 0, $items, $params) ?>
        <?= Html::textInput('input5'); ?>

        <div class="form-group">
            <?= Html::submitButton('Сохранить', ['class' => 'btn btn-primary']) ?>
        </div>
    <?php ActiveForm::end(); ?>

</div><!-- create -->
