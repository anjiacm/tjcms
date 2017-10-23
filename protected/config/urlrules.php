<?php
return array(
    'sitemap\.xml' => 'site/sitemap', //网站地图
    'sitemap\.xsl' => 'site/sitemapxsl', //网站索引
    'page/<id:\w+>'=>'page/index', //单页
    '<controller:\w+>/<action:\w+>/cat_<catalog_id:\d+>/page_<page:\d+>'=>'<controller>/<action>', //分页
    '<controller:\w+>/<action:\w+>/page_<page:\d+>'=>'<controller>/<action>', //分页
    '<controller:\w+>/<action:\w+>/cat_<catalog_id:\d+>' => '<controller>/<action>',  //内容列表
    'tag/index/<tag:\w+>' => 'tag/index',            //标签搜索页
    '<controller:\w+>/<id:\d+>'=>'<controller>/view',
    '<controller:\w+>/<action:\w+>/<id:\d+>'=>'<controller>/<action>',
    '<controller:\w+>/<action:\w+>'=>'<controller>/<action>',
    '<module:\w+>/<controller:\w+>/<action:\w+>'=>'<module>/<controller>/<action>',
);
?>