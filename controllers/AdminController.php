<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\web\Response;
use app\models\Question;
use app\models\UserAnswers;
use app\models\UploadForm;
use app\models\AdminUser;
use app\models\FixedValues;
use yii\web\UploadedFile;
use yii2tech\csvgrid\CsvGrid;
use yii\data\ArrayDataProvider;
use yii\filters\AccessControl;

use yii\helpers\VarDumper;

class AdminController extends Controller
{
    public $enableCsrfValidation = false;
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => [
                            'index',
                            'login' => ['post'],
                            'upload' => ['post'],
                            'save_admin' => ['post'],
                            'remove_admin' => ['post'],
                            'resend_email' => ['post'],
                            'activity_log' => ['post']
                        ],
                        'allow' => false,
                    ]
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
        $model = new UploadForm;
        $vars = ['cookies' => Yii::$app->request->cookies, 'model' => $model, 'action' => 'adminLocked'];

        if (Yii::$app->session->get('admin') != null) {
            $vars['action'] = Yii::$app->session->get('admin');
        } else {
            Yii::$app->session->set('admin', 'adminLocked');
        }

        if (Yii::$app->request->isPost)
        {
            $requestData = Yii::$app->request->post();
            $user = AdminUser::find()->where(['username' => $requestData['username']])->one();
            if (isset($user)) {
                if ($user->password == base64_encode($requestData['pass']))
                {
                    Yii::$app->session->set('admin', 'adminUnlocked');
                    $vars['action'] = 'adminUnlocked';
                    
                }
            }
        }
        

        // VarDumper::dump($vars);
        // exit;

        if ($vars['action'] == 'adminLocked') {
            return $this->render('adminLocked', $vars);
        } else {
            return $this->redirect(['admin/pricing']);
            // return $this->render('pricing', $vars);
        }
    }

    public function actionSettings()
    {
        $model = new UploadForm;
        $dataProvider = AdminUser::find()->all();
        $vars = ['cookies' => Yii::$app->request->cookies, 'model' => $model, 'action' => Yii::$app->session->get('admin'), 'dataProvider' => $dataProvider];

        if (Yii::$app->session->get('admin') == null || Yii::$app->session->get('admin') == "adminLocked") {
            return $this->render('adminLocked', $vars);
        } else {
            return $this->render('settings', $vars);
        }
    }

    public function actionPricing()
    {
        $model = new UploadForm;
        $dataProvider = FixedValues::find()->all();
        $vars = ['cookies' => Yii::$app->request->cookies, 'model' => $model, 'action' => Yii::$app->session->get('admin'), 'dataProvider' => $dataProvider];

//        VarDumper::dump($dataProvider);
//        exit;

        if (Yii::$app->session->get('admin') == null || Yii::$app->session->get('admin') == 'adminLocked') {
            return $this->render('adminLocked', $vars);
        } else {
            return $this->render('pricing', $vars);
        }
    }

    public function actionUserlog()
    {
        $model = new UploadForm;
        $vars = ['cookies' => Yii::$app->request->cookies, 'model' => $model, 'action' => Yii::$app->session->get('admin')];

        if (Yii::$app->session->get('admin') == null || Yii::$app->session->get('admin') == 'adminLocked') {
            return $this->render('adminLocked', $vars);
        } else {
            return $this->render('userlog', $vars);
        }
    }

    public function actionLogout() {
        // $model = new UploadForm;
        // $vars = ['cookies' => Yii::$app->request->cookies, 'model' => $model, 'action' => 'adminLocked'];

        // $attributes = Yii::$app->utils->getUploadCSVAttributes();

        // foreach($attributes as $attribute)
        // {
        //     if($file = UploadedFile::getInstance($model, $attribute))
        //     {
        //         $model->{$attribute} = UploadedFile::getInstance($model, $attribute);
        //         if ($model->{$attribute}->extension == "csv" && $model->upload($attribute)) {
        //             Yii::$app->session->setFlash('success', 'File uploaded.');
        //         }
        //         else
        //         {
        //             $model->addErrors();
        //             Yii::$app->session->setFlash('error', 'There was an error uploading your file.');
        //         }
        //     }
        // }
        // $vars['model'] = $model;

        // Yii::$app->session->set('admin', 'adminLocked');
        // $this->actionIndex();

        // return $this->render('adminLocked', $vars);
    }

    public function actionUpload()
    {
        $model = new UploadForm;
        $vars = ['cookies' => Yii::$app->request->cookies, 'model' => $model, 'action' => Yii::$app->session->get('admin')];

        if (Yii::$app->request->isPost) {
            $requestData = Yii::$app->request->post();

            $attributes = Yii::$app->utils->getUploadCSVAttributes();

            foreach($attributes as $attribute)
            {
                if($file = UploadedFile::getInstance($model, $attribute))
                {
                    $model->{$attribute} = UploadedFile::getInstance($model, $attribute);
                    if ($model->{$attribute}->extension == "csv" && $model->upload($attribute)) {
                        Yii::$app->session->setFlash('success', 'File uploaded.');
                    }
                    else
                    {
                        $model->addErrors();
                        Yii::$app->session->setFlash('error', 'There was an error uploading your file.');
                    }
                }
            }

            $vars['model'] = $model;
        }

        return $this->redirect(['admin/pricing']);


        // return $this->render('pricing', $vars);
    }

    public function actionSave_admin() {
        $data = Yii::$app->api->handleRequest();

        $adminUser = AdminUser::find()->where(['username' => $data['username']])->one();

        if ($adminUser) {
            $adminUser->password = base64_encode($data['password']);
            $adminUser->save();
        } else {
            $adminUser = new AdminUser();
            $adminUser->username = $data['username'];
            $adminUser->password = base64_encode($data['password']);
            $adminUser->save();
        }

        return $this->redirect(['admin/settings']);
    }

    public function actionRemove_admin() {
        $data = Yii::$app->api->handleRequest();

        $adminUser = AdminUser::find()->where(['username' => $data['username']])->one();
        $adminUser->delete();

        return $this->redirect(['admin/settings']);
    }

    public function actionResend_email() {
        $data = Yii::$app->api->handleRequest();

        $vars = [
            'password' => $data['password'],
        ];

        Yii::$app->mailer
            ->compose('resend_password')
            ->setGlobalMergeVars($vars)
            ->setTo($data['username'])
            ->setFrom('team@crowdfilms.be')
            ->setReplyTo('team@crowdfilms.be')
            ->send();
    }

    public function actionActivity_log() {
        $data = Yii::$app->api->handleRequest();

    }

    public function actionExport()
    {
        $exporter = new CsvGrid([
            'query' => UserAnswers::find(),
            'columns' => [
                'user_id', 'created_at', 'value', 'value_id'
            ],
        ]);
        return $exporter->export()->send('answers.csv');
    }
}
