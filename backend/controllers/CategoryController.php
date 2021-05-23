<?php

namespace backend\controllers;

use Yii;
use yii\data\ActiveDataProvider;
use yii\data\ArrayDataProvider;
use common\models\Category;
use common\models\TodoForm;
use yii\db\Expression;
use backend\models\CategorySearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use backend\models\Countsmoking;
use \DateTime;

/**
 * CategoryController implements the CRUD actions for Category model.
 */
class CategoryController extends Controller
{
    /**
     * Lists all Category models.
     * @return mixed
     */
    public function actionIndex()
    {
        $today = date('Y-m-d');
        $last_element = Countsmoking::find()->where(['between', 'data', $today . " 00:00:01", $today . " 23:59:59"])->orderBy(['data' => SORT_DESC])->one();
        if ($last_element != null) {
            $latest_date_time = strtotime($last_element->data);
            $latest_date_time = date('Y-m-d H:i:s', $latest_date_time);
            $datetime1 = new DateTime($latest_date_time);

            $interval = $datetime1->diff(new DateTime())->format("%d days, %h hours and %i minuts");
        } else {
            $interval = 'Нет данных';
        }

        $statisticDay = TodoForm::find()->andWhere('last_update >= CURDATE()')
            ->andWhere(['=', 'done', '1'])
            ->asArray()->all();

        $statisticOne = TodoForm::find()
            ->andWhere(['>', 'last_update', new Expression('LAST_DAY(CURDATE()) + INTERVAL 1 DAY - INTERVAL 1 MONTH')])
            ->andWhere(['<', 'last_update', new Expression('DATE_ADD(LAST_DAY(CURDATE()), INTERVAL 1 DAY)')])
            ->andWhere(['=', 'done', '1'])->asArray()->all();

        $statisticThree = TodoForm::find()
            ->andWhere(['>', 'last_update', new Expression('LAST_DAY(CURDATE()) + INTERVAL 1 DAY - INTERVAL 3 MONTH')])
            ->andWhere(['<', 'last_update', new Expression('DATE_ADD(LAST_DAY(CURDATE()), INTERVAL 1 DAY)')])
            ->andWhere(['=', 'done', '1'])->asArray()->all();

        $list_data_day = array();
        foreach ($statisticDay as $value) {
            $category_id = $value['category_id'];
            $category = Category::findOne($category_id);            
            
            if ($category->parent_id != 0 || $category->parent_id != null)
            {
                $parent_title = Category::findOne($category->parent_id)->title;  
                $list_data_day[$value['id']] = $parent_title;
            }
            else 
            {
                $list_data_day[$value['id']] = $category->title;
            }
        }

        $list_data = array();
        foreach ($statisticOne as $value) {
            $category_id = $value['category_id'];
            $category = Category::findOne($category_id);                       
            if ($category->parent_id != 0 || $category->parent_id != null)
            {
                $parent_title = Category::findOne($category->parent_id)->title;  
                $list_data[$value['id']] = $parent_title;
            }
            else 
            {
                $list_data[$value['id']] = $category->title;
            }
        }

        $list_data_three = array();
        foreach ($statisticThree as $value) {
            $category_id = $value['category_id'];
            $category = Category::findOne($category_id);                       
            if ($category->parent_id != 0 || $category->parent_id != null)
            {
                $parent_title = Category::findOne($category->parent_id)->title;  
                $list_data_three[$value['id']] = $parent_title;
            }
            else 
            {
                $list_data_three[$value['id']] = $category->title;
            }
        }

        $pie_data_day = array_count_values($list_data_day);
        $pie_data_one = array_count_values($list_data);
        $pie_data_three = array_count_values($list_data_three);

        $searchModel = new CategorySearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'interval_smoking' => $interval,
            'pie_data_day' => $pie_data_day,
            'pie_data_one' => $pie_data_one,
            'pie_data_three' => $pie_data_three,
        ]);
    }
    public function actionList()
    {
        $query = Category::find()->orderBy(['last_update' => SORT_ASC]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        return $this->render('list_category', [
            'dataProvider' => $dataProvider,
        ]);
    }
    
    public function actionSubCategory($parent_id)
    {
        $sub_category_query = Category::find()->where(['parent_id' => $parent_id]);

        $dataProvider = new ActiveDataProvider([
            'query' => $sub_category_query,
        ]);
        return $this->render('sub', [
            'dataProvider' => $dataProvider,
        ]);
    }
    /**
     * Displays a single Category model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $category = $this->findModel($id);
        $query = $category->getTodo($id)->where(['=', 'done', '0']);

        $statisticDay = $category->getTodo($id)->andWhere('last_update >= CURDATE()')
            ->andWhere(['=', 'done', '1'])
            ->asArray()->all();

        $statisticOne = $category->getTodo($id)
            ->andWhere(['>', 'last_update', new Expression('LAST_DAY(CURDATE()) + INTERVAL 1 DAY - INTERVAL 1 MONTH')])
            ->andWhere(['<', 'last_update', new Expression('DATE_ADD(LAST_DAY(CURDATE()), INTERVAL 1 DAY)')])
            ->andWhere(['=', 'done', '1'])->asArray()->all();

        $statisticThree = $category->getTodo($id)
            ->andWhere(['>', 'last_update', new Expression('LAST_DAY(CURDATE()) + INTERVAL 1 DAY - INTERVAL 3 MONTH')])
            ->andWhere(['<', 'last_update', new Expression('DATE_ADD(LAST_DAY(CURDATE()), INTERVAL 1 DAY)')])
            ->andWhere(['=', 'done', '1'])->asArray()->all();

        $list_data_day = array();
        foreach ($statisticDay as $value) {
            $task_name = $value['title'];
            $list_data_day[$value['id']] = $task_name;
        }

        $list_data = array();
        foreach ($statisticOne as $value) {
            $task_name = $value['title'];
            $list_data[$value['id']] = $task_name;
        }

        $list_data_three = array();
        foreach ($statisticThree as $value) {
            $task_name = $value['title'];
            $list_data_three[$value['id']] = $task_name;
        }

        $pie_data_day = array_count_values($list_data_day);
        $pie_data_one = array_count_values($list_data);
        $pie_data_three = array_count_values($list_data_three);

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
        
        arsort($pie_data_day);
        arsort($pie_data_one);
        arsort($pie_data_three);

        return $this->render('view', [
            'model' => $this->findModel($id),
            'dataProvider' => $provider,
            'pie_data_day' => $pie_data_day,
            'pie_data_one' => $pie_data_one,
            'pie_data_three' => $pie_data_three,
        ]);
    }

    public function actionRecommended($id)
    {
        $category = $this->findModel($id);
        $query1 = $category->getTodo($id)->where(['=', 'done', '0'])->limit(2)->orderBy('last_update ASC');
        $query2 = $category->getTodo($id)->where(['=', 'done', '0'])->limit(2)->orderBy('average ASC');
        $query1->union($query2);

        $provider = new ArrayDataProvider([
            'allModels' => $query1->all(),
            'pagination' => [
                //'pageSize' => 4,
            ],
            'sort' => [
                'defaultOrder' => [
                    //    'last_update' => SORT_ASC,
                ]
            ],
        ]);
        return $this->render('view', [
            'model' => $this->findModel($id),
            'dataProvider' => $provider,
        ]);
    }

    /**
     * Creates a new Category model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @param int $id id of the parent category
     * @return mixed
     */
    public function actionCreate($id = null)
    {
        $categories = Category::find()->all();
        $model = new Category();
        $model->parent_id = $id;
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
                'categories' => $categories,
            ]);
        }
    }

    /**
     * Updates an existing Category model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $categories = Category::find()->all();
        $model = $this->findModel($id);
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
                'categories' => $categories,
            ]);
        }
    }

    /**
     * Deletes an existing Category model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();
        return $this->redirect(['index']);
    }

    /**
     * Finds the Category model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Category the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Category::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
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
}
