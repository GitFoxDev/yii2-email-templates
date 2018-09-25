<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\Templates */

$title = 'Создать шаблон';
$this->title = $title . ' / Админ. панель / ' . Yii::$app->name;
$this->params['breadcrumbs'][] = $title;
?>
<div class="templates-create">

    <h1><?= Html::encode($title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
