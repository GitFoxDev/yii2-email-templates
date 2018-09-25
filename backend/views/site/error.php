<?php

/* @var $this yii\web\View */
/* @var $name string */
/* @var $message string */
/* @var $exception Exception */

use yii\helpers\Html;

$title = $name;
$this->title = $title . ' / Админ. панель / ' . Yii::$app->name;
?>
<div class="site-error">

    <h1><?= Html::encode($title) ?></h1>

    <div class="alert alert-danger">
        <?= nl2br(Html::encode($message)) ?>
    </div>

    <p>
        При попытке обработки Вашего запроса возникла ошибка.
    </p>
    <p>
        Попробуйте позже, если ошибка повторяется, пожалуйста, свяжитесь с нами. Спасибо.
    </p>

</div>
