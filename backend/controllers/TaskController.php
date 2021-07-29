<?php

namespace backend\controllers;

use Yii;
use yii\data\ActiveDataProvider;
use common\models\Category;
use common\models\TodoForm;
use backend\models\GlobalTask;


use yii\web\Controller;
use yii\data\ArrayDataProvider;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\db\Expression;

class TaskController extends Controller
{
    public function actionDetail($task_id)
    {
        $model = TodoForm::findOne($task_id);
        return $this->render('detail', ['model' => $model,]);
    }
    public function actionCreatetask()
    {
        $model = new TodoForm();

        if (Yii::$app->request->isPost) {
            $formdata = Yii::$app->request->post();

            if ($model->load(Yii::$app->request->post()) && $model->validate()) {

                $category = Category::find()->where(['id' => $formdata['TodoForm']['category']])->one();

                $isTodo = TodoForm::find()->where(['title' => $formdata['TodoForm']['title']])->one();
                if (empty($isTodo)) {
                    $title = $formdata['TodoForm']['title'];
                    $loop = $formdata['TodoForm']['loop'];

                    $model = new TodoForm();
                    $model->title = $title;
                    $model->description = $formdata['TodoForm']['description'];
                    $model->done = false;
                    $model->last_update = new Expression('NOW()');
                    $model->loop = $loop;
                    $model->category_id = $category->id;
                    $model->save(false);
                    return $this->redirect(['task/createtask',]);
                }
            }
        }

        return $this->render('create', ['model' => $model,]);
    }

    public function actionDone()
    {
        $selection = (array) Yii::$app->request->post('selection');
        $model_redirect = TodoForm::findOne((int) $selection[0]);
        $category_redirect = $model_redirect->category_id;

        $action = Yii::$app->request->post('action');

        if ($action === 'Done') {
            foreach ($selection as $id) {
                $model = TodoForm::findOne((int) $id); //make a typecasting
                $category = Category::findOne((int) $model->category_id);
                $category->last_update = new Expression('NOW()');
                $category->count = $category->count + 1;
                $category->save(false);
                //Прверяем есть ли у категорий родительская категория, если есть увеличиваем ее.
                if ($category->parent_id != 0 || $category->parent_id != null)
                {
                    $root_category = Category::findOne((int) $category->parent_id);
                    $root_category->last_update = new Expression('NOW()');
                    $root_category->count = $root_category->count + 1;
                    $root_category->save(false);
                }

                //if loop == true, then create new task, else no
                if ($model->loop == 1) {
                    $model->last_update = new Expression('NOW()');
                    $model->count = $model->count + 1;
                    $model->done = 1;
                    $model->save(false);

                    $newModel = new TodoForm();
                    $newModel->title = $model->title;
                    $newModel->count = $model->count;
                    $newModel->loop = 1;
                    $newModel->last_update = $model->last_update;
                    $newModel->done = 0;
                    $newModel->description = $model->description;
                    $newModel->max_bonus = $model->max_bonus + 1;
                    $newModel->aim_several_average = $model->aim_several_average;
                    $newModel->category_id = $category->id;
                    $newModel->save(false);

                } else {
                    $model->last_update = new Expression('NOW()');
                    $model->count = $model->count + 1;
                    $model->done = 1;
                    $model->save(false);
                }
            }
        }

        if ($action === 'Delete') {
            foreach ($selection as $id) {
                $model = TodoForm::findOne((int) $id);
                $model->delete();
            }
        }

        $statistic = TodoForm::find()->where('last_update >= CURDATE()')->andWhere(['=', 'done', '1'])->count();

        $aim = 15 - (int) $statistic;
        Yii::$app->session->setFlash('success', 'Осталось выполнить ' . $aim . ' задач');

        return $this->redirect(['category/view', 'id' => $category_redirect]);
    }

    public function actionWarrningtask()
    {
        $time_from = strtotime('-10 day', time());
        $delta_from = date('Y-m-d H:i:s', $time_from);

        $query = TodoForm::find()->where(['=', 'done', '0'])->andWhere(['<=', 'last_update', $delta_from]);
        $provider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 50,
            ],
            'sort' => [
                'defaultOrder' => [
                    'last_update' => SORT_ASC,
                ]
            ],
        ]);

        return $this->render('warrning', [
            'dataProvider' => $provider,
        ]);
    }

    public function actionStatdetailday($date)
    {
        $query = TodoForm::find()
            ->andWhere(['like', 'last_update', $date])
            ->andWhere(['=', 'done', '1']);

        $provider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 30,
            ],

            'sort' => [
                'defaultOrder' => ['last_update' => SORT_ASC]
            ],
        ]);

        return $this->render('statdetailday', ['dataProvider' => $provider,]);
    }

    public function actionDelete()
    {
    }

    function date_sort($a, $b)
    {
        return strtotime($a) - strtotime($b);
    }

    public function actionStatistic()
    {
        $year = date("Y");
        $month = date("m");
        $day = date("d");

        $count_day = cal_days_in_month(CAL_GREGORIAN, $month, $year); // 31
        $aim_task = $count_day * 15;
        $aim_day_task = $day * 15; 
        
        //last_update
        $statistic = TodoForm::find()
            ->andWhere(['>', 'last_update', new Expression('LAST_DAY(CURDATE()) + INTERVAL 1 DAY - INTERVAL 1 MONTH')])
            ->andWhere(['<', 'last_update', new Expression('DATE_ADD(LAST_DAY(CURDATE()), INTERVAL 1 DAY)')])
            ->andWhere(['=', 'done', '1'])->asArray()->all();

        $list_data = array();
        foreach ($statistic as $value) {
            $date = date('Y-m-d', strtotime($value['last_update']));
            $list_data[] = $date;
        }

        usort($list_data, array($this, "date_sort"));

        $data_raw = array_count_values($list_data);
        $result = [];
        $aim_total = 0;
        foreach ($data_raw as $key => $value) {
            $result[] = ['data' => $key, 'count' => $value];
            $aim_total += $value;
        }
        $aimTask = 'Выполнено - ' . $aim_total . ' из ' . $aim_task;
        $aimDayTask = 'Выполнено за текущие дни - ' . $aim_total . ' из ' . $aim_day_task;
        $provider = new ArrayDataProvider([
            'allModels' => $result,
            'pagination' => [
                'pageSize' => 30,
            ],
            'sort' => [
                'attributes' => ['data',],
            ],
        ]);

        return $this->render('statistiс', ['dataProvider' => $provider, 
                                           'aimTask' => $aimTask,
                                           'aimDayTask' => $aimDayTask,
        ]);
    }

    public function actionVuetest()
    {
        return $this->render('vuetest');
    }

    public function actionUpdate($id)
    {
        $categories = Category::find()->all();
        $model = $this->findModel($id);
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['update', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }


    public function beforeAction($action)
    {
        if (!parent::beforeAction($action)) {
            return false;
        }

        if (!Yii::$app->user->isGuest) {
            return true;
        } else {
            Yii::$app->getResponse()->redirect(Yii::$app->getHomeUrl());
            //для перестраховки вернем false
            return false;
        }
    }

    protected function findModel($id)
    {
        if (($model = TodoForm::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
