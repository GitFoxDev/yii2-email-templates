<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $list \common\models\Templates[] */
/* @var $pages \yii\data\Pagination */

$title = 'Список шаблонов';
$this->title = $title . ' / ' . Yii::$app->name;
?>
<div class="templates-list">
    <h1><?= Html::encode($title) ?></h1>

    <div class="row">
        <?php foreach ($list as $item): ?>

            <div class="col-md-4">
                <div class="panel panel-primary">
                    <div class="panel-heading">
                        <h2 class="panel-title"><?= $item->title ?></h2>

                    </div>
                    <div class="panel-body">
                        <p class="pull-left"><i><?= $item->filename ?>.html</i></p>
                        <p class="pull-right"><a href="<?= \yii\helpers\Url::to(['templates/view', 'id' => $item->id]) ?>" class="btn btn-primary">Посмотреть</a></p>
                    </div>
                </div>
            </div>

        <?php endforeach; ?>
    </div>

    <?= \yii\widgets\LinkPager::widget([
        'pagination' => $pages,
    ]); ?>
</div>
