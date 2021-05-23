<?php

use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use dosamigos\tinymce\TinyMce;

/* @var $this yii\web\View */
/* @var $model common\models\Category */
/* @var $categories common\models\Category[] */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="category-form">

    <?php $form = ActiveForm::begin(); ?>
    <?= $form->field($model, 'title')->textInput(['maxlength' => 1000]); ?>
    <?= $form->field($model, 'description')->widget(TinyMce::class, [
        'options' => ['rows' => 30],
        'language' => 'ru',
        'clientOptions' => [
            'plugins' => [
                'advlist autolink lists link charmap  print hr preview pagebreak',
                'searchreplace wordcount visualblocks visualchars code fullscreen nonbreaking',
                'save insertdatetime media table template paste image codesample'
            ],
            'toolbar' => 'undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image'
        ]
    ]);
    ?>
    <?= $form->field($model, 'aim_several_average')->textInput(['type' => 'string']); ?>
    <?= $form->field($model, 'min_day')->textInput(['type' => 'number']); ?>
    <?= $form->field($model, 'max_day')->textInput(['type' => 'number']); ?>
    <?= $form->field($model, 'loop')->checkbox(['checked ' => '']); ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>