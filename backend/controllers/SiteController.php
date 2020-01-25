<?php
namespace backend\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\LoginForm;
use backend\models\SignupForm;
use backend\models\Countsmoking;
use \DateTime;

/**
 * Site controller
 */
class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout', 'signup'],
                'rules' => [
                    [
                        'actions' => ['login', 'error'],
                        'allow' => true,
                    ],
                    [
                        'actions' => ['signup'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    [
                        'actions' => ['logout', 'index'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    /**
     * Login action.
     *
     * @return string
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        } else {
            $model->password = '';

            return $this->render('login', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Logout action.
     *
     * @return string
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Signs user up.
     *
     * @return mixed
     */
    public function actionSignup()
    {
        $model = new SignupForm();
        if ($model->load(Yii::$app->request->post())) {
            if ($user = $model->signup()) {
                if (Yii::$app->getUser()->login($user)) {
                    return $this->goHome();
                }
            }
        }

        return $this->render('signup', [
            'model' => $model,
        ]);
    }
    public function actionAddNoForm()
    {
        $model = new Countsmoking();
        $today_day = date('Y-m-d');

        $last_element = Countsmoking::find()->where(['between', 'data', $today_day . " 00:00:01", $today_day . " 23:59:59" ])->orderBy(['data' => SORT_DESC])->one();
        if ($last_element != null)
        {
            $latest_date_time = strtotime($last_element->data);
            $latest_date_time = date('Y-m-d H:i:s', $latest_date_time);
            $datetime1 = new DateTime($latest_date_time);

            $interval = (int)$datetime1->diff(new DateTime())->format("%h");  
            
            $model->data = date('Y-m-d H:i:s'); 
            if ($interval < 2)
            {
                $model->count = $last_element->count + 1;
            }
            else
            {
                $model->count = $last_element->count;
            }            
        }
        else
        {
            $model->data = date('Y-m-d H:i:s'); 
            $model->count = 1;
        }
        $model->save(false);

        return $this->redirect(['/category/index']);
    }
}
