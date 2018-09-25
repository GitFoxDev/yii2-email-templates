<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Templates */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="templates-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="row">
        <div class="col-sm-8"><?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?></div>
        <div class="col-sm-4">
            <?= $form->field($model, 'filename', [
                'template' => "{label}\n<div class='input-group'>{input}<div class=\"input-group-addon\">.html</div></div>\n{hint}\n{error}"
            ])->textInput(['maxlength' => true])->hint('Англ. буквы, цифры, тире, нижнее подчеркивание.') ?>
        </div>
    </div>

    <?= $form->field($model, 'content')->textarea(['rows' => 10]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Добавить' : 'Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
