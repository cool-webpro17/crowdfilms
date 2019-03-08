<?php

namespace app\controllers;

use phpDocumentor\Reflection\DocBlock\Tags\Param;
use Yii;
use yii\web\Controller;
use yii\web\Response;

use app\models\Question;
use app\models\UserAnswers;
use app\models\UploadForm;
use app\models\AdminUser;
use app\models\FixedValues;
use app\models\EventStatus;
use app\models\ActivityLog;
use app\models\StatusType;
use app\models\EventType;
use app\models\FilmType;
use app\models\CustomerType;
use app\models\FormulaRule;
use app\models\Project;
use app\models\ProjectStatus;

use yii\web\UploadedFile;
use yii2tech\csvgrid\CsvGrid;
use yii\data\ArrayDataProvider;
use yii\filters\AccessControl;

use yii\helpers\VarDumper;
use yii\helpers\ArrayHelper;
use yii\helpers\FileHelper;

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
                            'remove_status' => ['post'],
                            'save_type' => ['post'],
                            'remove_type' => ['post'],
                            'save_formula_rule' => ['post'],
                            'remove_formula_rule' => ['post'],
                            'film_formula' => ['post'],
                            'convert_project' => ['post'],
                            'project_details' => ['post'],
                            'add_project_status' => ['post'],
                            'remove_project_status' => ['post'],
                            'save_project' => ['post']
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

    public function actionFormula()
    {
        $model = new UploadForm;
        $eventTypes = EventType::find()->all();
        $filmTypes = FilmType::find()->all();
        $customerTypes = CustomerType::find()->all();
        $files = scandir(Yii::$app->basePath."/formula/");
        $formulas = [];
        foreach($files as $file) {
            if (is_file(Yii::$app->basePath."/formula/" . $file)) {
                $formulas[] = $file;
            }
        }
//        $formulas = FileHelper::findFiles(Yii::$app->basePath."/formula");
        $vars = ['cookies' => Yii::$app->request->cookies,
            'model' => $model,
            'action' => Yii::$app->session->get('admin'),
            'eventTypes' => $eventTypes,
            'filmTypes' => $filmTypes,
            'customerTypes' => $customerTypes,
            'formulas' => $formulas,
            'username' => Yii::$app->session->get('username')];

        if (Yii::$app->session->get('admin') == null || Yii::$app->session->get('admin') == 'adminLocked') {
            return $this->render('adminLocked', $vars);
        } else {
            return $this->render('formula', $vars);
        }
    }

    public function actionFormula_rule() {
        $model = new UploadForm;
        $eventTypes = EventType::find()->all();
        $filmTypes = FilmType::find()->all();
        $customerTypes = CustomerType::find()->all();
        $formulaRules = FormulaRule::find()->all();
        $files = scandir(Yii::$app->basePath."/formula/");
        $formulas = [];
        foreach($files as $file) {
            if (is_file(Yii::$app->basePath."/formula/" . $file)) {
                $formulas[] = $file;
            }
        }
        $vars = ['cookies' => Yii::$app->request->cookies,
            'model' => $model,
            'action' => Yii::$app->session->get('admin'),
            'eventTypes' => $eventTypes,
            'filmTypes' => $filmTypes,
            'customerTypes' => $customerTypes,
            'formulas' => $formulas,
            'username' => Yii::$app->session->get('username'),
            'formulaRules' => $formulaRules
        ];

        if (Yii::$app->session->get('admin') == null || Yii::$app->session->get('admin') == 'adminLocked') {
            return $this->render('adminLocked', $vars);
        } else {
            return $this->render('formulaRule', $vars);
        }
    }

    public function actionUserlog()
    {
        $model = new UploadForm;
        $userAnswers = UserAnswers::find()->where(['answer_type' => 'Event'])->all();
        $userAnswers = ArrayHelper::index($userAnswers, null, 'group_id');
        $eventTypes = EventStatus::find()->orderBy(['created_at' => SORT_DESC])->all();

        foreach ($userAnswers as &$eachGroup) {
            $eachGroup['exist'] = false;
            foreach ($eachGroup as $row) {
                if ($row->value_id == 'eMail' && UserAnswers::find()->where(['value_id' => 'eMail', 'value' => $row->value, 'answer_type' => 'Project'])->one()) {
                    $eachGroup['exist'] = true;
                }
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

    public function actionProject()
    {
        $model = new UploadForm;
        $userAnswers = UserAnswers::find()->where(['answer_type' => 'Project'])->all();
        $userAnswers = ArrayHelper::index($userAnswers, null, 'group_id');
        $projects = Project::find()->orderBy(['created_at' => SORT_DESC])->all();
        $projectStatus = ProjectStatus::find()->all();

        $vars = [
            'cookies' => Yii::$app->request->cookies,
            'model' => $model,
            'action' => Yii::$app->session->get('admin'),
            'username' => Yii::$app->session->get('username'),
            'userAnswers' => $userAnswers,
            'projects' => $projects,
            'projectStatus' => $projectStatus
        ];

        if (Yii::$app->session->get('admin') == null || Yii::$app->session->get('admin') == 'adminLocked') {
            return $this->render('adminLocked', $vars);
        } else {
            return $this->render('project', $vars);
        }
    }

    public function actionProject_details()
    {
        $data = Yii::$app->api->handleRequest();
        $vars['key'] = $data['key'];

        return $this->redirect(['admin/projectdetails', 'key' => $data['key']]);
    }

    public function actionProjectdetails() {
        $request = Yii::$app->request;
        $userId = $request->get('key');
        $project = Project::find()->where(['user_id' => $userId])->one();
        $projectStatus = ProjectStatus::find()->all();
        $vars = [
            'user_id' => $userId,
            'project' => $project,
            'projectStatus' => $projectStatus
        ];

        if (Yii::$app->session->get('admin') == null || Yii::$app->session->get('admin') == 'adminLocked') {
            return $this->render('adminLocked', $vars);
        } else {
            return $this->render('projectdetails', $vars);
        }
    }

    public function actionAdd_project_status() {
        $data = Yii::$app->api->handleRequest();

        if ($data['statusId'] == 0) {
            $projectStatus = new ProjectStatus();
            $projectStatus->status = $data['status'];
            $projectStatus->save();
        } else {
            $projectStatus = ProjectStatus::find()->where(['id' => $data['statusId']])->one();
            $projectStatus->status = $data['status'];
            $projectStatus->save();
        }
        return $this->redirect(['admin/project']);
    }

    public function actionRemove_project_status() {
        $data = Yii::$app->api->handleRequest();
        $projectStatus = ProjectStatus::find()->where(['id' => $data['statusId']])->one();
        $projectStatus->delete();
        return $this->redirect(['admin/project']);
    }

    public function actionSave_project() {
        $data = Yii::$app->api->handleRequest();

        $project = Project::find()->where(['user_id' => $data['user_id']])->one();

        $project->project_title = $data['project_title'];
        $project->live_status = $data['live_status'];
        $project->contact_name = $data['contact_name'];
        $project->contact_email = $data['contact_email'];
        $project->contact_phone = $data['contact_phone'];
        $project->contact_comment = $data['contact_comment'];
        $project->project_description = $data['project_description'];
        $project->project_status = $data['project_status'];
        $project->total_price = $data['total_price'];
        $project->price_description = $data['price_description'];
        $project->payment_due = $data['payment_due'];
        $project->already_paid = $data['already_paid'];
        $project->created_at = date('Y-m-d H:i:s');
        $project->save();

        return $this->redirect(['admin/project']);
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
        $vars = ['cookies' => Yii::$app->request->cookies,
            'model' => $model,
            'action' => Yii::$app->session->get('admin'),
            'username' => Yii::$app->session->get('username')];
//        $vars['userAnswer'] = $data['userAnswer'];
        $vars['key'] = $data['key'];

        return $this->redirect(['admin/userlogdetails', 'key' => $data['key']]);
    }

    public function actionUserlogdetails()
    {
        $request = Yii::$app->request;
        $userId = $request->get('key');

        $statusType = StatusType::find()->all();

        $userAnswers = UserAnswers::find()->where(['group_id' => $userId])->all();
        $eventType = EventStatus::find()->where(['user_id' => $userId])->one();

//        $userAnswers = ArrayHelper::index($userAnswers, null, 'user_id');
//
//
//        foreach ($userAnswers as $key => &$userAnswer) {
//            forEach ($userAnswer as $row) {
//
//                if ($row->value_id == 'eMail' && $row->answer_type == 'Event') {
//                    forEach ($userAnswers as $compareKey => $compareAnswer) {
//                        forEach ($compareAnswer as $compareRow) {
//
//                            if ($compareRow->value_id == 'eMail' && $row->answer_type == 'Event') {
//
//                                if ($compareRow->value == $row->value && $compareKey != $key) {
//                                    $userAnswers[$key] = array_merge($userAnswers[$key], $userAnswers[$compareKey]);
//                                    unset($userAnswers[$compareKey]);
//                                }
//                            }
//                        }
//                    }
//                }
//            }
//        }
//
//        foreach ($userAnswers as $key => &$userAnswer) {
//            foreach ($userAnswer as $rowKey => $row) {
//                $userAnswers[$key][$rowKey] = $this->object_to_array($userAnswers[$key][$rowKey]);
//            }
//        }


        $vars = ['cookies' => Yii::$app->request->cookies,
            'action' => Yii::$app->session->get('admin'),
            'username' => Yii::$app->session->get('username'),
            'userAnswer' => $userAnswers,
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
        $eventType = EventStatus::find()->where(['user_id' => $data['user_id']])->one();
        $eventType->event_status = $data['eventType'];
        $eventType->save();

        return $this->redirect(['admin/userlogdetails', 'key' => $data['user_id']]);
    }

    public function actionSave_formula_rule() {
        $data = Yii::$app->api->handleRequest();
        $formulaRule = FormulaRule::find()->where(['eventType' => $data['eventType'], 'filmType' => $data['filmType'], 'customerType' => $data['customerType']])->one();
        if ($formulaRule != null) {
            $formulaRule->F1ID = $data['F1ID'];
            $formulaRule->F2ID = $data['F2ID'];
            $formulaRule->F3ID = $data['F3ID'];
            $formulaRule->F4ID = $data['F4ID'];
            $formulaRule->save();
        } else {
            $formulaRule = new FormulaRule();
            $formulaRule->eventType = $data['eventType'];
            $formulaRule->filmType = $data['filmType'];
            $formulaRule->customerType = $data['customerType'];
            $formulaRule->F1ID = $data['F1ID'];
            $formulaRule->F2ID = $data['F2ID'];
            $formulaRule->F3ID = $data['F3ID'];
            $formulaRule->F4ID = $data['F4ID'];
            $formulaRule->save();
        }
        return $this->redirect(['admin/formula_rule']);

    }

    public function actionRemove_formula_rule() {
        $data = Yii::$app->api->handleRequest();
        $formulaRule = FormulaRule::find()->where(['eventType' => $data['eventType'], 'filmType' => $data['filmType'], 'customerType' => $data['customerType']])->one();
        $formulaRule->delete();
        return $this->redirect(['admin/formula_rule']);
    }

    public function actionFilm_formula() {
        $data = Yii::$app->api->handleRequest();
        $cookies = Yii::$app->request->cookies;
        $user_id = $cookies['user_id']->value;

        $eventType = UserAnswers::find()->where(['user_id' => $user_id, 'value_id' => 'eventType'])->orderBy(['created_at' => SORT_DESC])->one()->value;
        $filmType = UserAnswers::find()->where(['user_id' => $user_id, 'value_id' => 'filmType'])->orderBy(['created_at' => SORT_DESC])->one()->value;
        $customerType = UserAnswers::find()->where(['user_id' => $user_id, 'value_id' => 'customerType'])->orderBy(['created_at' => SORT_DESC])->one()->value;

        $formulaRule = FormulaRule::find()->where(['eventType' => $eventType, 'filmType' => $filmType, 'customerType' => $customerType])->one();

        $arrayFormulaRules = [];
        if ($formulaRule == null) {
            //$allFormulaFiles = FileHelper::findFiles(Yii::$app->basePath."/formula");
			//$allFormulaFiles = scandir(Yii::$app->basePath."/formula/");;
			$files = scandir(Yii::$app->basePath."/formula/");
			$allFormulaFiles = [];
			foreach($files as $file) {
				if (is_file(Yii::$app->basePath."/formula/" . $file)) {
					$allFormulaFiles[] = $file;
				}
			}
			//VarDumper::dump($allFormulaFiles);
			//exit;
            foreach ($allFormulaFiles as $eachFormulaFile) {
                $arrayFormulaRules[] = file_get_contents(Yii::$app->basePath . "/formula/" . $eachFormulaFile);
            }
        } else {
            foreach ($formulaRule as $key => $eachRule) {
                if ($key != 'eventType' && $key != 'filmType' && $key != 'customerType' && $eachRule != null) {
                    $arrayFormulaRules[] = file_get_contents(Yii::$app->basePath . "/formula/" . $eachRule);

                }
            }
        }

        return Yii::$app->api->_sendResponse(200, ['data' => $arrayFormulaRules], true);
    }

    public function actionSave_type() {
        $data = Yii::$app->api->handleRequest();

        if ($data['type'] == 'film') {
            $type = FilmType::find()->where(['value' => $data['value']])->one();
            if ($type == null) {
                $type = new FilmType();
            }
            $type->value = $data['value'];
            $type->text = $data['text'];
            $type->save();
        } else if ($data['type'] == 'event') {
            $type = EventType::find()->where(['value' => $data['value']])->one();
            if ($type == null) {
                $type = new EventType();
            }
            $type->value = $data['value'];
            $type->text = $data['text'];
            $type->save();
        } else if ($data['type'] == 'customer') {
            $type = CustomerType::find()->where(['value' => $data['value']])->one();
            if ($type == null) {
                $type = new CustomerType();
            }
            $type->value = $data['value'];
            $type->text = $data['text'];
            $type->save();
        }
        return $this->redirect(['admin/formula']);

    }

    public function actionRemove_type() {
        $data = Yii::$app->api->handleRequest();

        if ($data['type'] == 'film') {
            $type = FilmType::find()->where(['value' => $data['value']])->one();
            $type->delete();
        } else if ($data['type'] == 'event') {
            $type = EventType::find()->where(['value' => $data['value']])->one();
            $type->delete();
        } else if ($data['type'] == 'customer') {
            $type = CustomerType::find()->where(['value' => $data['value']])->one();
            $type->delete();
        }

        return $this->redirect(['admin/formula']);
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

    public function actionConvert_project() {
        $data = Yii::$app->api->handleRequest();

        $event = EventStatus::find()->where(['user_id' => $data['user_id']])->one();
        $project = new Project();
        $project->user_id = $event->user_id;
        $project->project_status = 'No';
        $project->contact_id = $event->contact_id;
        $project->created_at = date('Y-m-d H:i:s');
        $project->save();
        $event->delete();

        $userAnswers = UserAnswers::find()->where(['group_id' => $data['user_id']])->all();

        foreach ($userAnswers as $eachAnswer) {
            $eachAnswer->answer_type = 'Project';
            $eachAnswer->save();
        }

        return $this->redirect(['admin/userlog']);
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
