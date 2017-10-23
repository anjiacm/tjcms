<?php

class PhChannelPageController extends Backend
{
    public $file_dir;
    public $_channel_list;
    public $speciallist;

    //初始化函数
    public function init()
    {
        parent::init();
        $this->file_dir = 'v';
        $this->_channel_list = PhChannel::getChannelList();
        $this->speciallist = PhSpecial::getSpecialListForJs();
    }


    public function actionIndex()
    {

        $model = new PhChannelPage;
        //条件
        $criteria = new CDbCriteria();

        $criteria->order = 't.id DESC';
        $count = $model->count($criteria);

        //分页
        $pages = new CPagination($count);
        $pages->pageSize = 20;
        $pages->applyLimit($criteria);

        //查询
        $result = $model->findAll($criteria);
        $app_list = PhApplication::getAppList();
        $this->render('index', array('model' => $model, 'datalist' => $result, 'pagebar' => $pages, 'app_list' => $app_list));
    }

    /**
     * create a particular model.
     * If create is successful, the browser will be redirected to the 'index' page.
     * @param integer $id the ID of the model to be created
     */
    public function actionCreate()
    {
        $model = new PhChannelPage;

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['PhChannelPage'])) {
            $model->attributes = $_POST['PhChannelPage'];
            $model->android_id = empty($_POST['PhChannelPage']['android_id']) ? '' : implode(',', $_POST['PhChannelPage']['android_id']);
            $model->ios_id = empty($_POST['PhChannelPage']['ios_id']) ? '' : implode(',', $_POST['PhChannelPage']['ios_id']);
            $model->statistics = empty($_POST['PhChannelPage']['statistics']) ? '' : implode(',', $_POST['PhChannelPage']['statistics']);
            $model->playactorlist = empty($_POST['PhChannelPage']['playactorlist']) ? '' : implode(',', $_POST['PhChannelPage']['playactorlist']);
            $model->createtime = time();
            $model->updatetime = time();
            if ($model->save()) {
                $model->page_url = $this->getFilePath($model->id);
                $this->createHtml($model->id);
                $model->save();
                $this->redirect(array('index'));
            }
        }

        $this->render('create', array(
            'model' => $model,
        ));
    }

    /**
     * Updates a particular model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id the ID of the model to be updated
     */
    public function actionUpdate($id)
    {
        $model = $this->loadModel($id);
        $model->android_id = explode(',', $model->android_id);
        $model->ios_id = explode(',', $model->ios_id);
        $model->statistics = explode(',', $model->statistics);
        if (isset($_POST['PhChannelPage'])) {
            $model->attributes = $_POST['PhChannelPage'];
            $model->android_id = empty($_POST['PhChannelPage']['android_id']) ? '' : implode(',', $_POST['PhChannelPage']['android_id']);
            $model->ios_id = empty($_POST['PhChannelPage']['ios_id']) ? '' : implode(',', $_POST['PhChannelPage']['ios_id']);
            $model->statistics = empty($_POST['PhChannelPage']['statistics']) ? '' : implode(',', $_POST['PhChannelPage']['statistics']);
            $model->playactorlist = empty($_POST['PhChannelPage']['playactorlist']) ? '' : implode(',', $_POST['PhChannelPage']['playactorlist']);
            $model->updatetime = time();
            if ($model->save())
                $this->redirect(array('index'));
        }

        $this->render('update', array(
            'model' => $model,
        ));
    }

    public function actionBatch()
    {
        $ids = Yii::app()->request->getParam('id');
        $command = Yii::app()->request->getParam('command');
        empty($ids) && $this->message('error', Yii::t('admin', 'No Select'));
        if (!is_array($ids)) {
            $ids = array($ids);
        }
        $criteria = new CDbCriteria();
        $criteria->addInCondition('id', $ids);
        switch ($command) {
            case 'delete':
                //删除
                PhChannelPage::model()->deleteAll($criteria);
                break;
            case 'show':
                //显示
                PhChannelPage::model()->updateAll(array('status' => 1), $criteria);
                break;
            case 'hide':
                //隐藏
                PhChannelPage::model()->updateAll(array('status' => 0), $criteria);
                break;
            case 'sortOrder':
                $sortOrder = $_POST['order'];
                foreach ((array)$ids as $id) {
                    $catalogModel = PhChannelPage::model()->findByPk($id);
                    if ($catalogModel) {
                        $catalogModel->order = $sortOrder[$id];
                        $catalogModel->save();
                    }
                }
                break;
            default:
                $this->message('error', Yii::t('admin', 'Error Operation'));
        }
        $this->message('success', Yii::t('admin', 'Batch Operate Success'));
    }


    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer $id the ID of the model to be loaded
     * @return PhChannelPage the loaded model
     * @throws CHttpException
     */
    public function loadModel($id)
    {
        $model = PhChannelPage::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param PhChannelPage $model the model to be validated
     */
    protected function performAjaxValidation($model)
    {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'ph-channel-page-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

    //生成静态页
    public function actionCreateHtml()
    {
        $id = empty($_REQUEST['id']) ? '' : intval($_REQUEST['id']);
        $model = new PhChannelPage();
        $cdb = new CDbCriteria();
        $cdb->compare('t.id', $id);
        $data = $model->find($cdb);
        if ($data->id) {
            $result = $this->createHtml($data->id);
            if ($result) {
                $this->message('success', "create success");
            } else {
                $this->message('error', "create faild");
            }
        }

    }

    public function actionCreateUrl()
    {
        $id = empty($_REQUEST['id']) ? '' : intval($_REQUEST['id']);
        if (empty($id)) {
            $this->message('error', "create faild");
        }
        $model = new PhChannelPage();
        $data = $model->findByPk($id);
        if ($data) {
            $data->page_url = $this->getFilePath($id);
            if ($data->save()) {
                $this->message('success', "create success");
            } else {
                $this->message('error', "create faild");
            }
        }
    }

    private function getFilePath($id)
    {
        $url = '';
        if (empty($id)) {
            return $url;
        }
        $model = new PhChannelPage();
        $cdb = new CDbCriteria();
        $cdb->compare('t.id', $id);
        $cdb->with = 'channel';
        $data = $model->find($cdb);
        if ($data) {
//            $host = Yii::app()->request->hostInfo;
            $host = $data->channel->domain != "" ? $data->channel->domain : $this->_setting['create_domain'];

            $dir = "/" . $this->file_dir . "/";
            /*暂时不要了
             if($data->channel->sub_title){
                $dir = "/".$this->file_dir."/".$data->channel->sub_title."/";
            }else{
                $dir = "/".$this->file_dir."/";
            }
            $filename = $data->content_sub_title.'_'.date("Ymd",$data->createtime).'.html?from='.$data->channel_title;
            */
            $filename = $data->content_sub_title . '.html';
            $url = $host . $dir . $filename;
        }
        return $url;
    }

    private function createHtml($id)
    {
        //获取飘花的内容页数据
        $result = false;
        if (empty($id)) {
            return $result;
        }
        $model = new PhChannelPage();
        $cdb = new CDbCriteria();
        $cdb->compare('t.id', $id);
        $cdb->with = 'channel';
        $data = $model->find($cdb);
        $json = Yii::app()->curl->get('http://api.qizhouqi.com/loading/?id=' . $data->page_id);
        $array = CJSON::decode($json, true);
        $theme = empty($data->theme) ? 1 : $data->theme;
        $ios_data = PhApplication::model()->findAllByPk(explode(',', $data->ios_id));
        $android_data = PhApplication::model()->findAllByPk(explode(',', $data->android_id));
        $ios = $android = array();
        if ($ios_data) {
            foreach ($ios_data as $item) {
                $ios[] = array(
                    'title' => $item->title,
                    'icon' => Helper::getFullUrl($item->icon),
                    'gif' => Helper::getFullUrl($item->animation),
                    'url' => $item->url,
                );
            }
        }
        if ($android_data) {
            foreach ($android_data as $item) {
                $android[] = array(
                    'title' => $item->title,
                    'icon' => Helper::getFullUrl($item->icon),
                    'gif' => Helper::getFullUrl($item->animation),
                    'url' => $item->url,
                );
            }
        }

        $app = array(
            "ios" => $ios,
            "android" => $android,
            "other" => array(
                'title' => $data->content_title,
                'showtime' => intval($data->showtime),
            ),
        );

        $statistics = PhStatistics::getStatisticsList($data->statistics);
        $special_list = $this->getSpecialPostList($data->speciallist);
        $playactors = PhPlayactor::getPlayactorList($data->playactorlist);
        $result = array();
        $result['channelpage'] = $data;
        $result['data'] = $array;
        $result['theme'] = $theme;
        $result['app'] = $app;
        $result['special'] = $special_list;
        $result['playactors'] = $playactors;
        $result['statistics'] = $statistics;
        $content = $this->renderPartial('create_html_' . $theme, $result, true);
        $file_title = $data->content_sub_title;
        /*if($data->channel->sub_title){
            $dir=$this->file_dir."/".$data->channel->sub_title;
        }else{
            $dir=$this->file_dir;
        }*/
        $dir = $this->file_dir;
        $result = Helper::writeHtml($content, $file_title, $dir);
        return $result;
    }

    /**
     * @throws Exception
     * 批量生成url
     */
    public function actionCreateUrls()
    {
        $page = empty($_REQUEST['page']) ? 0 : intval($_REQUEST['page']);
        $page_total = empty($_REQUEST['page_total']) ? 0 : intval($_REQUEST['page_total']);
        $size = empty($_REQUEST['size']) ? 10 : intval($_REQUEST['size']);
        $submit = isset($_REQUEST['submit']) ? $_REQUEST['submit'] : '';
        $model = new PhChannelPage();
        $cdb = new CDbCriteria();
        if ($submit != 'submit') {
            $count = $model->count();
            $page_total = ceil($count / $size);
            $submit = 'submit';
        }
        if (empty($page_total) || $page > $page_total) {
            $this->message('success', '完成', $this->createUrl('PhChannelPage/index'));
        }
        $cdb->limit = $size;
        $cdb->offset = $size * $page;
        $cdb->order = 'id DESC';
        $data = $model->findAll($cdb);
        if ($data) {
            foreach ($data as $item) {
                $page_url = $this->getFilePath($item->id);
                if ($page_url) {
                    $model->updateByPk($item->id, array('page_url' => $page_url));
                }
                unset($page_url);
            }
        }
        $page++;
        if ($page > $page_total) {
            $this->message('success', '完成', $this->createUrl('PhChannelPage/index'));
        } else {
            $this->message('success', $page . "/" . $page_total, $this->createUrl('PhChannelPage/CreateUrls', array('page' => $page, 'page_total' => $page_total, 'size' => $size, 'submit' => $submit)), 1);
        }
    }

    /**
     * @throws Exception
     * 批量生成HTML
     */
    public function actionCreateHtmls()
    {
        $page = empty($_REQUEST['page']) ? 0 : intval($_REQUEST['page']);
        $page_total = empty($_REQUEST['page_total']) ? 0 : intval($_REQUEST['page_total']);
        $size = empty($_REQUEST['size']) ? 10 : intval($_REQUEST['size']);
        $submit = isset($_REQUEST['submit']) ? $_REQUEST['submit'] : '';
        $model = new PhChannelPage();
        $cdb = new CDbCriteria();
        if ($submit != 'submit') {
            $count = $model->count();
            $page_total = ceil($count / $size);
            $submit = 'submit';
        }
        if (empty($page_total) || $page > $page_total) {
            $this->message('success', '完成', $this->createUrl('PhChannelPage/index'));
        }
        $cdb->limit = $size;
        $cdb->offset = $size * $page;
        $cdb->order = 'id DESC';
        $data = $model->findAll($cdb);
        if ($data) {
            foreach ($data as $item) {
                try {
                    $this->createHtml($item->id);
                } catch (Exception $e) {
                }
            }
        }
        $page++;
        if ($page > $page_total) {
            $this->message('success', '完成', $this->createUrl('PhChannelPage/index'));
        } else {
            $this->message('success', $page . "/" . $page_total, $this->createUrl('PhChannelPage/CreateHtmls', array('page' => $page, 'page_total' => $page_total, 'size' => $size, 'submit' => $submit)), 1);
        }
    }

    public function getSpecialPostList($ids)
    {
        //获取影片列表
        $ids_array = explode(',', $ids);
        $s_model = new PhSpecial();
        $s_cdb = new CDbCriteria();
        $s_cdb->compare('id', $ids_array);
        $s_cdb->order = "FIELD(`id`, {$ids})";
        $s_data = $s_model->findAll($s_cdb);
        $return = array();
        if ($s_data) {
            foreach ($s_data as $s_item) {
                $s_val = array();
                $s_val['id'] = $s_item->id;
                $s_val['title'] = $s_item->title;
                $s_val['sub_title'] = $s_item->sub_title;
                $s_val['showstyle'] = $s_item->showstyle;
                $s_val['description'] = $s_item->description;
                $s_val['cover_img'] = $s_item->cover_img;
                $s_val['postlist'] = array();
                if (!empty($s_item->postlist)) {
                    $p_ids_array = explode(',', $s_item->postlist);
                    $p_model = new PhPost();
                    $p_cdb = new CDbCriteria();
                    $p_cdb->compare('id', $p_ids_array);
                    $p_cdb->order = "FIELD(`id`, {$s_item->postlist})";
                    $p_data = $p_model->findAll($p_cdb);
                    if ($p_data) {
                        foreach ($p_data as $p_item) {
                            $p_val = array();
                            $p_val['id'] = $p_item->id;
                            $p_val['title'] = $p_item->title;
                            $p_val['sub_title'] = $p_item->sub_title;
                            $p_val['copyfrom'] = $p_item->copyfrom;
                            $p_val['hits'] = $p_item->hits;
                            $p_val['img_h'] = $p_item->img_h;
                            $p_val['img_v'] = $p_item->img_v;
                            $p_val['img_l'] = $p_item->img_l;
                            $p_val['img_s'] = $p_item->img_s;
                            $s_val['postlist'][] = $p_val;
                        }
                    }
                }
                $return[] = $s_val;
            }
        }
        return $return;
    }
}
