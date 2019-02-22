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
use app\models\EventType;
use app\models\ActivityLog;
use app\models\StatusType;
use yii\web\UploadedFile;
use yii2tech\csvgrid\CsvGrid;
use yii\data\ArrayDataProvider;
use yii\filters\AccessControl;

use yii\helpers\VarDumper;
use yii\helpers\ArrayHelper;

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
                            'signout' => ['post'],
                            'upload' => ['post'],
                            'save_admin' => ['post'],
                            'remove_admin' => ['post'],
                            'resend_email' => ['post'],
                            'activity_log' => ['post'],
                            'export_activity' => ['get'],
                            'user_log_details' => ['post'],
                            'save_event_type' => ['post'],
                            'save_status' => ['post'],
                            'remove_status' => ['post']
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

        if (Yii::$app->request->isPost) {
            $requestData = Yii::$app->request->post();
            $user = AdminUser::find()->where(['username' => $requestData['username']])->one();
            if (isset($user)) {
                if ($user->password == base64_encode($requestData['pass'])) {
                    Yii::$app->session->set('admin', 'adminUnlocked');
                    Yii::$app->session->set('username', $requestData['username']);

                    $activityLog = new ActivityLog();
                    $activityLog->username = $requestData['username'];
                    $activityLog->action = 'Login';
                    $activityLog->created_at = date('Y-m-d H:i:s');
                    $activityLog->save();

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
        $statusType = StatusType::find()->all();
        $vars = ['cookies' => Yii::$app->request->cookies,
            'model' => $model,
            'action' => Yii::$app->session->get('admin'),
            'dataProvider' => $dataProvider,
            'username' => Yii::$app->session->get('username'),
            'statusType' => $statusType
        ];

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
        $vars = ['cookies' => Yii::$app->request->cookies, 'model' => $model, 'action' => Yii::$app->session->get('admin'), 'dataProvider' => $dataProvider, 'username' => Yii::$app->session->get('username')];

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
        $userAnswers = UserAnswers::find()->all();
        $userAnswers = ArrayHelper::index($userAnswers, null, 'user_id');
        $eventTypes = EventType::find()->orderBy(['created_at' => SORT_DESC])->all();

//        $eventTypes = ArrayHelper::index($eventTypes, null, 'user_id');


        foreach ($userAnswers as $key => &$userAnswer) {
            forEach ($userAnswer as $row) {

                if ($row->value_id == 'eMail') {
                    forEach ($userAnswers as $compareKey => $compareAnswer) {
                        forEach ($compareAnswer as $compareRow) {

                            if ($compareRow->value_id == 'eMail') {

                                if ($compareRow->value == $row->value && $compareKey != $key) {
                                    $userAnswers[$key] = array_merge($userAnswers[$key], $userAnswers[$compareKey]);
                                    unset($userAnswers[$compareKey]);
                                }
                            }
                        }
                    }
                }
            }
        }

        foreach ($userAnswers as $key => &$userAnswer) {

            foreach ($userAnswer as $rowKey => $row) {
                $userAnswers[$key][$rowKey] = $this->object_to_array($userAnswers[$key][$rowKey]);
            }

        }

        $vars = [
            'cookies' => Yii::$app->request->cookies,
            'model' => $model,
            'action' => Yii::$app->session->get('admin'),
            'username' => Yii::$app->session->get('username'),
            'userAnswers' => $userAnswers,
            'eventTypes' => $eventTypes
        ];

        if (Yii::$app->session->get('admin') == null || Yii::$app->session->get('admin') == 'adminLocked') {
            return $this->render('adminLocked', $vars);
        } else {
            return $this->render('userlog', $vars);
        }
    }

    public function object_to_array($data)
    {
        if (is_array($data) || is_object($data)) {
            $result = array();

            foreach ($data as $key => $value) {
                $result[$key] = $this->object_to_array($value);
            }

            return $result;
        }

        return $data;
    }

    public function actionLogout()
    {

//         $model = new UploadForm;
//         $vars = ['cookies' => Yii::$app->request->cookies, 'model' => $model, 'action' => 'adminLocked'];
//         return $this->render('adminLocked', $vars);
    }

    public function actionUpload()
    {
        $model = new UploadForm;
        $vars = ['cookies' => Yii::$app->request->cookies, 'model' => $model, 'action' => Yii::$app->session->get('admin')];

        if (Yii::$app->request->isPost) {
            $requestData = Yii::$app->request->post();

            $attributes = Yii::$app->utils->getUploadCSVAttributes();

            foreach ($attributes as $attribute) {
                if ($file = UploadedFile::getInstance($model, $attribute)) {
                    $model->{$attribute} = UploadedFile::getInstance($model, $attribute);
                    if ($model->{$attribute}->extension == "csv" && $model->upload($attribute)) {
                        Yii::$app->session->setFlash('success', 'File uploaded.');
                    } else {
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

    public function actionSave_admin()
    {
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

    public function actionRemove_admin()
    {
        $data = Yii::$app->api->handleRequest();

        $adminUser = AdminUser::find()->where(['username' => $data['username']])->one();
        $adminUser->delete();

        return $this->redirect(['admin/settings']);
    }

    public function actionResend_email()
    {
        $data = Yii::$app->api->handleRequest();

        $vars = [
            'password' => $data['password'],
        ];

//        $success = Yii::$app->mailer
//            ->compose('resend_password', ['vars' => $vars])
//            ->setTo($data['username'])
//            ->setFrom('info@antwerpporttours.com')
//            ->setReplyTo('info@antwerpporttours.com')
//            ->send();

        $success = Yii::$app->mailer
            ->compose('resend_password')
            ->setGlobalMergeVars($vars)
            ->setTo($data['username'])
            ->setFrom('team@crowdfilms.be')
            ->setReplyTo('team@crowdfilms.be')
            ->send();

        return Yii::$app->api->_sendResponse(200, $success, true);
    }

    public function actionActivity_log()
    {
        $data = Yii::$app->api->handleRequest();

        $activityLog = new ActivityLog();
        $activityLog->username = $data['username'];
        $activityLog->action = $data['action'];
        $activityLog->created_at = date('Y-m-d H:i:s');
        $activityLog->save();

        return Yii::$app->api->_sendResponse(200, $activityLog, true);
    }

    public function actionExport_activity()
    {
        $exporter = new CsvGrid([
            'query' => ActivityLog::find()->orderBy('username DESC'),
            'columns' => [
                'username', 'action', 'created_at'
            ],
        ]);
        return $exporter->export()->send('activity_log.csv');
    }

    public function actionUser_log_details()
    {
        $model = new UploadForm;
        $data = Yii::$app->api->handleRequest();
        $vars = ['cookies' => Yii::$app->request->cookies, 'model' => $model, 'action' => Yii::$app->session->get('admin'), 'dataProvider' => $dataProvider, 'username' => Yii::$app->session->get('username')];
//        $vars['userAnswer'] = $data['userAnswer'];
        $vars['key'] = $data['key'];

        return $this->redirect(['admin/userlogdetails', 'key' => $data['key']]);
    }

    public function actionUserlogdetails()
    {
        $request = Yii::$app->request;
        $userId = $request->get('key');

        $statusType = StatusType::find()->all();

        $userAnswers = UserAnswers::find()->all();
        $userAnswers = ArrayHelper::index($userAnswers, null, 'user_id');

        $eventType = EventType::find()->where(['user_id' => $userId])->one();


        foreach ($userAnswers as $key => &$userAnswer) {
            forEach ($userAnswer as $row) {

                if ($row->value_id == 'eMail') {
                    forEach ($userAnswers as $compareKey => $compareAnswer) {
                        forEach ($compareAnswer as $compareRow) {

                            if ($compareRow->value_id == 'eMail') {

                                if ($compareRow->value == $row->value && $compareKey != $key) {
                                    $userAnswers[$key] = array_merge($userAnswers[$key], $userAnswers[$compareKey]);
                                    unset($userAnswers[$compareKey]);
                                }
                            }
                        }
                    }
                }
            }
        }

        foreach ($userAnswers as $key => &$userAnswer) {
            foreach ($userAnswer as $rowKey => $row) {
                $userAnswers[$key][$rowKey] = $this->object_to_array($userAnswers[$key][$rowKey]);
            }
        }

        $vars = ['cookies' => Yii::$app->request->cookies,
            'action' => Yii::$app->session->get('admin'),
            'username' => Yii::$app->session->get('username'),
            'userAnswer' => $userAnswers[$userId],
            'key' => $userId,
            'eventType' => $eventType,
            'statusType' => $statusType
        ];

        if (Yii::$app->session->get('admin') == null || Yii::$app->session->get('admin') == 'adminLocked') {
            return $this->render('adminLocked', $vars);
        } else {
            return $this->render('userlogdetails', $vars);
        }
    }

    public function actionSave_event_type() {
        $data = Yii::$app->api->handleRequest();
        $eventType = EventType::find()->where(['user_id' => $data['user_id']])->one();
        $eventType->event_status = $data['eventType'];
        $eventType->save();

        return $this->redirect(['admin/userlogdetails', 'key' => $data['user_id']]);
    }

    public function actionSave_status() {
        $data = Yii::$app->api->handleRequest();
        $statusType = new StatusType();
        $statusType->status_name = $data['statusName'];
        $statusType->status_description = $data['statusDescription'];
        $beforeStatusType = StatusType::find()->where(['status_name' => $data['beforeStatus']])->one();
        $allStatusType = StatusType::find()->all();
        $beforeKey = -1;
        $tempStatusType = [];
        foreach ($allStatusType as $key => $eachStatusType) {
            if ($eachStatusType->status_name == $data['beforeStatus']) {
                $beforeKey = $key;
            }
            if ($beforeKey != -1 && $key > $beforeKey) {
                $tempStatusType[] = $eachStatusType;
                $eachStatusType->delete();
            }
        }
        $statusType->save();
        foreach ($tempStatusType as $eachStatusType) {
            $statusType = new StatusType();
            $statusType->status_name = $eachStatusType['status_name'];
            $statusType->status_description = $eachStatusType['status_description'];
            $statusType->save();
        }

        return $this->redirect(['admin/settings']);
    }

    public function actionRemove_status() {
        $data = Yii::$app->api->handleRequest();
        $statusType = StatusType::find()->where(['status_name' => $data['statusName']])->one();
        $statusType->delete();

        return $this->redirect(['admin/settings']);
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
