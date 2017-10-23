<?php

class PhSpecialController extends Backend
{
    public $postlist;
    //初始化函数
    public function init()
    {
        parent::init();
        $this->postlist=PhPost::getPostListForJs();
    }


    public function actionIndex()
    {

        $model = new PhSpecial;
        //条件
        $criteria = new CDbCriteria();

        $criteria->order = 't.id ASC';
        $count = $model->count($criteria);

        //分页
        $pages = new CPagination($count);
        $pages->pageSize = 20;
        $pages->applyLimit($criteria);

        //查询
        $result = $model->findAll($criteria);

        $show_style_list = $model->getShowStyle();
        $this->render('index', array('model' => $model, 'datalist' => $result, 'pagebar' => $pages,'show_style_list'=>$show_style_list));
    }

    /**
     * create a particular model.
     * If create is successful, the browser will be redirected to the 'index' page.
     * @param integer $id the ID of the model to be created
     */
    public function actionCreate()
    {
        $model = new PhSpecial;
        if (isset($_POST['PhSpecial'])) {
            $model->attributes = $_POST['PhSpecial'];
            $model->createtime = time();
            $model->updatetime = time();
            if ($model->save()){
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
        if (isset($_POST['PhSpecial'])) {
            $model->attributes = $_POST['PhSpecial'];
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
                PhSpecial::model()->deleteAll($criteria);
                break;
            case 'show':
                //显示
                PhSpecial::model()->updateAll(array('status' => 1), $criteria);
                break;
            case 'hide':
                //隐藏
                PhSpecial::model()->updateAll(array('status' => 0), $criteria);
                break;
            case 'sortOrder':
                $sortOrder = $_POST['order'];
                foreach ((array)$ids as $id) {
                    $catalogModel = PhSpecial::model()->findByPk($id);
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
     * @return PhSpecial the loaded model
     * @throws CHttpException
     */
    public function loadModel($id)
    {
        $model = PhSpecial::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param PhSpecial $model the model to be validated
     */
    protected function performAjaxValidation($model)
    {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'ph-special-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

    public function actionUploadSimple(){
        $uploader = new Uploader();
        if(Yii::app()->request->isPostRequest) {
            //普通上传
            $uploader->initSimple('specialcover')->uploadSimple('simple_file');
            $error = $uploader->getError();
            if (!$error) {
                $data = array(
                    'file_name' => $uploader->file_name,
                    'file_path' => $uploader->file_path,
                    'thumb_path'=> $uploader->thumb_path,
                    'file_ext'  => $uploader->file_ext
                );
                App::response(200, 'success', $data);
            } else {
                App::response(101 , $error);
            }
        }
    }
}
