<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Templates */

$title = 'Редактирование шаблона "' . $model->title . '"';
$this->title = $title . ' / Админ. панель / ' . Yii::$app->name;
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Редактирование';
?>
<div class="templates-update">

    <h1><?= Html::encode($title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
