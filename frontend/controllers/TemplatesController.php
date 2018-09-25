<?php
namespace frontend\controllers;

use Codeception\Util\Template;
use Yii;
use yii\data\Pagination;
use yii\web\Controller;
use common\models\Templates;
use yii\data\ActiveDataProvider;

class TemplatesController  extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Список всех шаблонов
     *
     * @return string
     */
    public function actionList()
    {
        $query = Templates::find();
        $pages = new Pagination([
            'pageSize'   => 6,
            'totalCount' => $query->count()
        ]);
        $list  = $query->offset($pages->offset)
            ->limit($pages->limit)
            ->all();

        /*$dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);*/

        return $this->render('list', [
            'list'  => $list,
            'pages' => $pages,
        ]);
    }

    /**
     * Отображение одного шаблона
     *
     * @return string|\yii\web\Response
     */
    public function actionView($id)
    {
        if ($id) {
            $item = Templates::findOne($id);

            return $this->render('view', ['item' => $item]);
        } else {
            return $this->redirect(['/templates/list']);
        }


    }
}
