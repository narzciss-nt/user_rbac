<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\SignupForm;
use app\models\ContactForm;
use yii\rbac\DbManager;

class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */

 public function behaviors()
    {
        /*.......................................Одна роль создается один раз! Одинаковых ролей быть не может........................................*/

         //$role = Yii::$app->authManager->createRole('admin'); #...Создаем новую роль (юзер)
         // $role->description = 'admin'; #..........................Можем указать описание и другие параметры (в таблице auth_item они есть)
         // Yii::$app->authManager->add($role); #...................Сохраняем роль в бд (auth_item)

        /*.......................................Одна роль создается один раз! Одинаковых ролей быть не может........................................*/

        /*return [                                    #
            // ...                                    #
            'components' => [                         #  Это нужно прописать в 
                'authManager' => [                    #  web.php в папке config
                    'class' => 'yii\rbac\DbManager',  #  Чтобы можно было создовать и назначать роли
                ],                                    #
                // ...                                #
            ],                                        #
        ];*/                                          #

        // $role = Yii::$app->authManager->createRole('uch');
        // $role->description = 'Ученик';
        // Yii::$app->authManager->add($role);

        // $role = Yii::$app->authManager->createRole('prepod');
        // $role->description = 'Преподаватель';
        // Yii::$app->authManager->add($role);

        // $role = Yii::$app->authManager->createRole('admin');
        // $role->description = 'admin';
        // Yii::$app->authManager->add($role);

        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [   #...............................................................Здесь прописываются правила для ролей...................
                    [   'allow' => true, #......................................впускать (false - не впускать).........................................
                        'actions' => ['login', 'index', 'signup','about','contact'], #....................на эти акшены..................................................
                        'roles' => ['?'], #...................неавторизированным пользователям (?) можно заходить на акшены логин, индекс и регистрация
                    ], #................можно прописать и для других ролей.............................................................................
                    [   'allow' => true, 
                        'actions' => ['index','login','signup'], 
                        'roles' => ['@'],
                    ],
                    [   'allow' => true, 
                        'actions' => ['index', 'logout', 'login','signup'], 
                        'roles' => ['prepod'],
                    ],
                    [   'allow' => true, 
                        'actions' => ['index', 'logout', 'login','signup'], 
                        'roles' => ['uch'],
                    ],
                    [   'allow' => true, 
                        'roles' => ['admin'],
                    ],
                    
                ],
            ],
            'verbs' => [
                'class' => verbFilter::className(),
                'actions' => [
                    'logout' => [''],
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
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
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
     * @return Response|string
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }

        $model->password = '';
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }


    public function actionSignupForm(){
   
    $model = new SignupForm();
    if($model->load(Yii::$app->request->post()) && $model->validate()){
        $user = new User();
        $user->username = $model->username;
        $user->firstname = $model->firsname;
        $user->lastname = $model->lastname;
        $user->password = \Yii::$app->security->generatePasswordHash($model->password);
        if($user->save()){
            return $this->goHome();
        }
    }
 
    return $this->render('signup',compact('model'));
}
    /**
     * Displays contact page.
     *
     * @return Response|string
     */
    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
            Yii::$app->session->setFlash('contactFormSubmitted');

            return $this->refresh();
        }
        return $this->render('contact', [
            'model' => $model,
        ]);
    }

    /**
     * Displays about page.
     *
     * @return string
     */
    public function actionAbout()
    {
        return $this->render('about');
    }
}
