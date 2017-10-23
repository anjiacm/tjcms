<?php
/**
 * 网站配置文件
 *
 * @author        GoldHan.zhao <326196998@qq.com>
 * @copyright     Copyright (c) 2014-2016 . All rights reserved.
 *
 */
$config = array(
	'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',	
	'name' =>'yiifcms',
	'language'=>'zh_cn',
	'theme'=>'default',
	'timeZone'=>'Asia/Shanghai',
    
    //开启日志记录
	'preload'=>array(
        'log'
    ),

	//自动加载
	'import'=>array(
		'application.models.*',
		'application.components.*',
		'application.extensions.*'
	),
    
    //模块配置
	'modules'=>array(
		'gii'   => array(
			'class'     => 'system.gii.GiiModule',
			'password'  => 'abcdefg',
			// If removed, Gii defaults to localhost only. Edit carefully to taste.
			'ipFilters' => array('127.0.0.1', '::1'),
		),
		'admin'=>array(
			'class' => 'application.modules.admin.AdminModule'		
		),
        'home',
		'api',
        'tjall',
	),

	//组件配置
	'components'=>array(
		'user'=>array(
			//开启自动登录
			'allowAutoLogin'=>true,
		),
		//配置路由规则
		'urlManager'=>array(			
			//'urlFormat'=>'path',
			'showScriptName'=>false,
			'urlSuffix'=>'/',
			'rules'=>require(dirname(__FILE__) . DIRECTORY_SEPARATOR . 'urlrules.php'),
		),		
		//数据库配置

		'db' => require(dirname(__FILE__) . DIRECTORY_SEPARATOR . 'db.php'),
		//'db_zbdindan' => require(dirname(__FILE__) . DIRECTORY_SEPARATOR . 'db_zbdindan.php'),

		//配置session
		'session'=>array(
                'class'=>'CDbHttpSession',
                'connectionID' => 'db',
                'sessionTableName' => 'y_tj_session',
				'autoStart'=>true,
				'sessionName'=>'YFCMSSN',
                'timeout'=>3600*24,
		),
		//配置错误页面
		'errorHandler'=>array(			
			'errorAction'=>'site/error',
		),
        //配置日志等级
		'log'=>array(
			'class'=>'CLogRouter',
			'routes'=>array(
				array(
					'class'=>'CFileLogRoute',
					'levels'=>'error, warning',
				),				
			),
		),
		'curl' => array(
			'class' => 'ext.YRCurl.Curl',
		),
	),
	/*'cache' => array (
		'class' => 'system.caching.CFileCache'
	),*/
	'params' => require(dirname(__FILE__) . DIRECTORY_SEPARATOR .'/params.php'),
);
//配置缓存
$cache =  require(dirname(__FILE__) . DIRECTORY_SEPARATOR . 'cache.php');
if (!empty($cache)) {
	$config['components'] = CMap::mergeArray($config['components'],$cache);
}
return $config;
