<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $item common\models\Templates */

$title = 'Шаблон "' . $item->title . '"';
$this->title = $title . ' / ' . Yii::$app->name;
$this->params['breadcrumbs'][] = $title;
?>
<div class="templates-view">

    <h1><?= Html::encode($title) ?></h1>
    <code class="content"><?= nl2br(Html::encode($item->content)) ?></code>
</div>
