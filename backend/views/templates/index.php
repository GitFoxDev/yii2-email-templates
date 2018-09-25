<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\TemplatesSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$title = 'Список шаблонов';
$this->title = $title . ' / Админ. панель / ' . Yii::$app->name;

\common\models\Templates::getDirectoryPath();
?>
<div class="templates-index">

    <h1><?= Html::encode($title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Добавить шаблон', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'title',
            [
                'attribute' => 'filename',
                'value' => function ($model, $key, $index, $column) {
                    return $model->filename . '.html';
                },
            ],
            //'created_at:datetime',
            //'updated_at:datetime',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
