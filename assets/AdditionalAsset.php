<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\assets;

use yii\web\AssetBundle;

/**
 * Main application asset bundle.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class AdditionalAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'css/site.css',
        'css/crowdfilmtest.webflow.css',
        'css/normalize.css',
        'css/webflow.css',
    ];
    public $js = [
        'js/apiFunctions.js',
    ];
    public $depends = [
        'app\assets\AppAsset',
    ];
}
