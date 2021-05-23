<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use common\models\Category;
use dosamigos\tinymce\TinyMce;
?>

<?php
    $items = Category::find()->select(['title', 'id'])->indexBy('id')->column();
    $params = [
        'prompt' => 'Выберите категорию...'
    ];
?>
    <?php $form = ActiveForm::begin(); ?>
        <?= $form->field($model, 'category')->dropDownList($items, $params) ?>
        <?= $form->field($model, 'title')->textInput()->hint('Название задачи')->label('Задача'); ?>
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
        <?= $form->field($model, 'loop')->checkbox(['checked ' => '']); ?>

        <button>Создать</button>
<?php ActiveForm::end() ?>