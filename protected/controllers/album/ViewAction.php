<?php

/**
 *  图集详情
 * 
 * @author        GoldHan.zhao <326196998@qq.com>
 * @copyright     Copyright (c) 2015. All rights reserved.
 */
class ViewAction extends CAction {

    public function run() {
        $id = Yii::app()->request->getParam('id');
        $post = Album::model()->with('content')->findByPk(intval($id));
        if (false == $post || $post->status == 'N') {
            throw new CHttpException(404, Yii::t('common', 'The requested page does not exist.'));        
        }
        //更新浏览次数
        $post->updateCounters(array('view_count' => 1), 'id=:id', array('id' => $id));
        //seo信息
        $this->controller->_seoTitle = empty($post->content->seo_title) ? $post->title . ' - ' . $this->controller->_setting['site_name'] : $post->content->seo_title;
        $this->controller->_seoKeywords = empty($post->content->seo_keywords) ? $post->tags : $post->content->seo_keywords;
        $this->controller->_seoDescription = empty($post->content->seo_description) ? $this->controller->_seoDescription : $post->content->seo_description;
        
        //最近的图集
        $last_images = Album::model()->findAll(array('condition' => 'catalog_id = ' . $post->catalog_id, 'order' => 'id DESC', 'limit' => 20,));

        //nav
        $navs = array();
        $navs[] = array('url' => $this->controller->createUrl('album/view', array('id' => $id)), 'name' => $post->title);

        //上一篇
        $pre_relation = Album::model()->find('id > ' . $id . ' AND `status` = "' . Album::STATUS_SHOW . '" ORDER BY id DESC');
        //下一篇
        $next_relation = Album::model()->find('id < ' . $id . ' AND `status` = "' . Album::STATUS_SHOW . '" ORDER BY id DESC');

        $tplVar = array(
            'post'          => $post,
            'navs'          => $navs,
            'last_images'   => $last_images,
            'pre_relation'  => $pre_relation,
            'next_relation' => $next_relation,
        );
        $this->controller->render('view', $tplVar);
    }
}
