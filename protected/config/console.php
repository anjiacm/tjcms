<?php

// This is the configuration for yiic console application.
// Any writable CConsoleApplication properties can be configured here.
return array(
	'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
    'name' => 'My Console Application',
    'import' => array(
        'application.extensions.Mailer.EmailerSender',
        'application.models.*',
        'application.components.*',
        'application.extensions.*',
    ),
    'components' => array(
        // uncomment the following to use a MySQL database
        'db' => require(dirname(__FILE__) . '/db.php'),
//        'curl' => array(
//            'class' => 'ext.Curl.Curl',
//            'options' => array(/* additional curl options */),
//        ),
        'curl' => array(
            'class' => 'ext.YRCurl.Curl',
        ),
        'mailer' => array(
            'class' => 'application.extensions.Mailer.EMailer',
            'pathViews' => 'application.views.email',
            'pathLayouts' => 'application.views.email.layouts'
        ),
        'filecache' => array(
            'class' => 'system.caching.CFileCache',
            //我们使用CFileCache实现缓存,缓存文件存放在runtime文件夹中
            'directoryLevel' => '2',   //缓存文件的目录深度
        ),
        'memcache' => array(
            'class' => 'CMemCache',
            'servers' => array(
                array(
                    'host' => '127.0.0.1',
                    'port' => 11311,
                    'weight' => 100,
                ),),
        ),
    ),
    // application-level parameters that can be accessed
    // using Yii::app()->params['paramName']
    //'params' => require(dirname(__FILE__) . '/params_console.php'),
);