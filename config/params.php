<?php

Yii::setAlias('@base', realpath(dirname(__FILE__).'/../'));
Yii::setAlias('@CSVPath', realpath(dirname(__FILE__).'/../CSVs/'));

return [
    'adminEmail' => 'jovana.stefanovic.js@gmail.com',
    'supportEmail' => 'jovana.stefanovic.js@gmail.com',
    'user.passwordResetTokenExpire' => 3600,
    'admin.password' => '2?5z(+G>];zKW/',
    'adminTools' => [
        'upload' =>
            [
                // ['label' => 'Upload Fixed Values csv', 'attribute' => 'fixed_values_file'],
                ['label' => 'Upload Pricing Formulas csv', 'attribute' => 'pricings_file'],
            ],
        'export' => 
        [
            ['label' => 'Export User Data', 'url' => 'admin/export'],
        ]
        
    ],
    'menuItemsDemo' => [
    	['label' => 'Home', 'url' => "/crowdfilms"],
    	['label' => 'Questions', 'url' => 'questions'],
    	['label' => 'Pricing', 'url' => 'pricing'],
    	['label' => 'Admin Tools', 'url' => 'admin'],
    ]
];


