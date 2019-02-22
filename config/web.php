<?php

$params = require __DIR__ . '/params.php';
$db = require __DIR__ . '/db.php';

$config = [
    'id' => 'crowdfilms',
    'name' => 'Crowdfilms',
    'basePath' => dirname(__DIR__),
    'bootstrap' => [
        'log',
        'gii',
        'hasher'
    ],
    'modules' => [
        'gii' => [
            'class' => 'yii\gii\Module',
        ],
    ],
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
        '@CSVPath' => '@app/csv/'
    ],
    'components' => [
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'UimQnVn_ZOOKiO-Vor3_W2WRCAoa6qkj',
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
//        'mailer' => [
//            'class' => 'nickcv\mandrill\Mailer',
//            'apikey' => 'QAlM_nvZRLs4W8w3QYZWxg',
//            'useMandrillTemplates' => true,
//            'useTemplateDefaults' => false,
//        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            'transport' => [
                'class' => 'Swift_SmtpTransport',
                'host' => 'send.one.com',
                'username' => 'info@antwerpporttours.com',
                'password' => 'HeyDevelopment',
                'port' => '465',
                'encryption' => 'ssl',
            ],
        ],
        /*
	'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
	*/
        'db' => $db,
        'user' => [
            'identityClass' => 'app\models\Users',
        ],
        'hasher' => [
            'class' => 'app\components\PasswordHash'

        ],
        'api' => [
            'class' => 'app\components\Api'

        ],
        'enums' => [
            'class' => 'app\components\Enums'

        ],
        'utils' => [
            'class' => 'app\components\Utils'

        ],
        'authManager' => [
            'class' => 'yii\rbac\DbManager',
        ],
        'urlManager' => [
			'class' => 'yii\web\UrlManager',
			// Disable index.php
			'showScriptName' => false,
			// Disable r= routes
			'enablePrettyUrl' => true,
			'rules' => array(
                    'admin' => 'admin/index',
                    '<alias:\w+>' => 'site/<alias>',
					'<alias:\w+>/<id:\d+>' => 'site/<alias>',
					'<controller:\w+>/<id:\d+>' => '<controller>/view',
					'<controller:\w+>/<action:\w+>/<id:\d+>' => '<controller>/<action>',
					'<controller:\w+>/<action:\w+>' => '<controller>/<action>',
			),
		],
        'assetManager' => [
            'bundles' => [
                'app\assets\AppAsset',
                'app\assets\AdditionalAsset',
                'yii\web\YiiAsset',
                'yii\bootstrap\BootstrapAsset',
            ],
        ],
        
    ],
    'params' => $params,
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        'allowedIPs' => ['87.116.180.29', '::1'],
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        'allowedIPs' => ['87.116.180.29', '::1'],
    ];
}

return $config;
