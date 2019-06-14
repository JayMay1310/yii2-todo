<?php

namespace backend\controllers;

use Yii;
use yii\data\ActiveDataProvider;
use backend\models\GlobalTask;
use common\models\TodoForm;
use yii\data\ArrayDataProvider;
use yii\web\NotFoundHttpException;

class GlobaltaskController extends \yii\web\Controller
{
    public function actionIndex()
    {
        $query = GlobalTask::find()->orderBy(['title' => SORT_ASC]);
        
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        return $this->render('index', [

            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionCreate()
    {
        $model = new GlobalTask();
        
        if ($model->load(Yii::$app->request->post())) {

            $task_list['id1'] = Yii::$app->request->post('task1');
            $task_list['input1'] = Yii::$app->request->post('input1');
            $task_list['work1'] = 0;
            $task_list['id2'] = Yii::$app->request->post('task2');
            $task_list['input2'] = Yii::$app->request->post('input2');
            $task_list['work2'] = 0;
            $task_list['id3'] = Yii::$app->request->post('task3');
            $task_list['input3'] = Yii::$app->request->post('input3');
            $task_list['work3'] = 0;
            $task_list['id4'] = Yii::$app->request->post('task4');
            $task_list['input4'] = Yii::$app->request->post('input4');
            $task_list['work4'] = 0;
            $task_list['id5'] = Yii::$app->request->post('task5');
            $task_list['input5'] = Yii::$app->request->post('input5');
            $task_list['work5'] = 0;

            $json=json_encode($task_list);
            
            //$model = new GlobalTask();
            $model->title = $model->title;
            $model->json_task = $json; 
            $model->save(false);

            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    public function actionAll()
    {
        $query = TodoForm::find()->where(['=', 'done', '0']);

        $provider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 100,
            ],
            'sort' => [
                'defaultOrder' => [
                    'last_update' => SORT_ASC,
                ]
            ],
        ]);

        return $this->render('all', ['dataProvider' => $provider,]);
    }

    public function actionZeroing($id)
    {
        $model = $this->findToDoModel($id);
        $model->max_bonus = 0; 
        $model->save(false);

        return $this->redirect(['all']); 
    }

    public function actionView($id = '')
    {
        $model = GlobalTask::findOne((int)$id);
        $list_task = json_decode($model->json_task);
        $task1 = TodoForm::findOne((int)$list_task->id1);
        $task2 = TodoForm::findOne((int)$list_task->id2);
        $task3 = TodoForm::findOne((int)$list_task->id3);
        $task4 = TodoForm::findOne((int)$list_task->id4);
        $task5 = TodoForm::findOne((int)$list_task->id5);

        $list_task->title1 = $task1->title ?? 'default';
        $list_task->title2 = $task2->title ?? 'default';
        $list_task->title3 = $task3->title ?? 'default';
        $list_task->title4 = $task4->title ?? 'default';
        $list_task->title5 = $task5->title ?? 'default';

        $result = [];
        array_push($result, array('title'=>$list_task->title1, 'max_count'=>$list_task->input1, 'work' => $list_task->work1));
        array_push($result, array('title'=>$list_task->title2, 'max_count'=>$list_task->input2, 'work' => $list_task->work2));
        array_push($result, array('title'=>$list_task->title3, 'max_count'=>$list_task->input3, 'work' => $list_task->work3));
        array_push($result, array('title'=>$list_task->title4, 'max_count'=>$list_task->input4, 'work' => $list_task->work4));
        array_push($result, array('title'=>$list_task->title5, 'max_count'=>$list_task->input5, 'work' => $list_task->work5));
        
        $provider = new ArrayDataProvider([
            'allModels' => $result,
            'pagination' => [
                'pageSize' => 10,
            ],
            'sort' => [
                
            ],
        ]);

        return $this->render('view', ['dataProvider' => $provider, 'model' => $model,]);
    }

    public function actionDelete($id)
    {
        $this->findModel($id)->delete();
        return $this->redirect(['index']);
    }

    protected function findModel($id)
    {
        if (($model = GlobalTask::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    protected function findToDoModel($id)
    {
        if (($model = TodoForm::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
