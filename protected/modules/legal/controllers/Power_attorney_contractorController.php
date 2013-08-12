<?php
/**
 * Управление довереностями для контрагента.
 *
 * @author Skibardin A.A. <skybardpf@artektiv.ru>
 */
class Power_attorney_contractorController extends Controller {
    public $layout = 'inner';
    public $menu_current = 'contractor';
    public $pageTitle = 'TWT Consult | Контрагенты | Доверенности';

    /**
     * Распределение экшенов.
     * @return array
     */
    public function actions()
    {
        return array(
            'list' => 'application.modules.legal.controllers.Power_attorney_contractor.ListAction',
            'view' => 'application.modules.legal.controllers.Power_attorney_contractor.ViewAction',
            'edit' => 'application.modules.legal.controllers.Power_attorney_contractor.UpdateAction',
            'add' => 'application.modules.legal.controllers.Power_attorney_contractor.CreateAction',
            'delete' => 'application.modules.legal.controllers.Power_attorney_contractor.DeleteAction',
        );
    }

//    /**
//     *  Просмотр доверености с идентификатором $id.
//     *
//     *  @param  string  $id
//     *
//     *  @throws CHttpException
//     */
//    public function actionView($id)
//    {
//        $doc =  PowerAttorneyForOrganization::model()->findByPk($id);
//        if (!$doc){
//            throw new CHttpException(404, 'Не найдена довереность.');
//        }
//        $org = Organization::model()->findByPk($doc->id_yur);
//        if (!$org){
//            throw new CHttpException(404, 'Не найдено юридическое лицо.');
//        }
//
//        $this->render('/organization/show', array(
//            'content' => $this->renderPartial('/power_attorney_le/show',
//                array(
//                    'model'         => $doc,
//                    'organization'  => $org
//                ), true),
//            'organization' => $org,
//            'cur_tab' => 'documents',
//        ));
//    }
//
//    /**
//     *  Добавление новой доверености к указанному в $org_id юридическому лицу.
//     *
//     *  @param  string $org_id
//     *
//     *  @throws CHttpException
//     */
//    public function actionAdd($org_id)
//    {
//        $org = Organization::model()->findByPk($org_id);
//        if (!$org){
//            throw new CHttpException(404, 'Не найдено юридическое лицо.');
//        }
//
//        $doc = new  PowerAttorneyForOrganization();
//        $doc->id_yur = $org->primaryKey;
//
//        $error = '';
//        if ($_POST && !empty($_POST[' PowerAttorneyForOrganization'])) {
//            $doc->setAttributes($_POST[' PowerAttorneyForOrganization']);
//
//            $doc->upload_scans  = CUploadedFile::getInstancesByName('upload_scans');
//            $doc->upload_files  = CUploadedFile::getInstancesByName('upload_files');
//
//            if ($doc->validate()) {
//                try {
//                    $doc->save();
//                    $this->redirect($this->createUrl('documents/list', array('org_id' => $org->primaryKey)));
//                } catch (Exception $e) {
//                    $error = $e->getMessage();
//                }
//            }
//        }
//
//        $this->render('/organization/show', array(
//            'content' => $this->renderPartial(
//                '/power_attorney_le/form',
//                array(
//                    'model'         => $doc,
//                    'error'         => $error,
//                    'organization'  => $org
//                ),
//                true
//            ),
//            'organization' => $org,
//            'cur_tab' => 'documents',
//        ));
//    }
//
//    /**
//     *  Редактирование доверености с идентификатором $id.
//     *
//     *  @param  string $id
//     *
//     *  @throws CHttpException
//     */
//    public function actionEdit($id)
//    {
//        $doc =  PowerAttorneyForOrganization::model()->findByPk($id);
//        if (!$doc){
//            throw new CHttpException(404, 'Не найдена довереность.');
//        }
//        $org = Organization::model()->findByPk($doc->id_yur);
//        if (!$org){
//            throw new CHttpException(404, 'Не найдено юридическое лицо.');
//        }
//
//        $error = '';
//        if ($_POST && !empty($_POST[' PowerAttorneyForOrganization'])) {
//            $doc->setAttributes($_POST[' PowerAttorneyForOrganization']);
//
//            $doc->upload_scans  = CUploadedFile::getInstancesByName('upload_scans');
//            $doc->upload_files  = CUploadedFile::getInstancesByName('upload_files');
//
//            if ($doc->validate()) {
//                try {
//                    $doc->save();
//                    $this->redirect($this->createUrl('view', array('id' => $doc->primaryKey)));
//                } catch (Exception $e) {
//                    $error = $e->getMessage();
//                }
//            }
//        }
//
//        $this->render('/organization/show', array(
//            'content' => $this->renderPartial(
//                '/power_attorney_le/form',
//                array(
//                    'model'         => $doc,
////                    'scans'         => $scans,
////                    'files'         => $files,
//                    'error'         => $error,
//                    'organization'  => $org
//                ),
//                true
//            ),
//            'organization' => $org,
//            'cur_tab' => 'documents',
//        ));
//    }
//
//    /**
//     *  Удаление доверености с идентификатором $id.
//     *
//     *  @param  string $id
//     *
//     *  @throws CHttpException
//     */
//    public function actionDelete($id)
//    {
//        $doc =  PowerAttorneyForOrganization::model()->findByPk($id);
//        if (!$doc){
//            throw new CHttpException(404, 'Не найдена довереность.');
//        }
//        $org = Organization::model()->findByPk($doc->id_yur);
//        if (!$org){
//            throw new CHttpException(404, 'Не найдено юридическое лицо.');
//        }
//
//        if (Yii::app()->request->isAjaxRequest) {
//            $ret = array();
//            try {
//                $doc->delete();
//            } catch (Exception $e) {
//                $ret['error'] = $e->getMessage();
//            }
//            echo CJSON::encode($ret);
//            Yii::app()->end();
//        } else {
//            if (isset($_POST['result'])) {
//                switch ($_POST['result']) {
//                    case 'yes':
//                        if ($doc->delete()) {
//                            $this->redirect($this->createUrl('documents/list', array('org_id' => $org->primaryKey)));
//                        } else {
//                            throw new CHttpException(500, 'Не удалось удалить довереность.');
//                        }
//                    break;
//                    default:
//                        $this->redirect($this->createUrl('view', array('id' => $doc->primaryKey)));
//                    break;
//                }
//            }
////            $this->render('documents/power_attorney_le/delete', array('model' => $doc));
//        }
//    }
//
//    /**
//     *  Скачать архив с файлами для определенного файла.
//     *
//     *  @param  string $id
//     *  @param  string $type
//     *
//     *  @throws CHttpException
//     */
//    public function actionDownload_archive($id, $type)
//    {
//        $uf = new UploadFile();
//        $uf->download_archive(UploadFile::CLIENT_ID, get_class( PowerAttorneyForOrganization::model()), $id, $type);
//    }
//
//    /**
//     *  Скачать файл по его $id.
//     *
//     *  @param  int $id
//     *
//     *  @throws CHttpException
//     */
//    public function actionDownload_file($id)
//    {
//        $uf = new UploadFile();
//        $uf->download($id);
//    }
//
//    /**
//     *  Удалить файл по его $id.
//     *
//     *  @param  int $id
//     *
//     *  @throws CHttpException
//     */
//    public function actionDelete_file($id)
//    {
//        try {
//            $uf = new UploadFile();
//            $uf->delete_file($id);
//
//            if (Yii::app()->request->isAjaxRequest) {
//                echo CJSON::encode(
//                    array(
//                        'success' => true,
//                    )
//                );
//                Yii::app()->end();
//            } else {
//                echo 'Файл успешно удален.';
//            }
//
//        } catch(UploadFileException $e) {
//            if (Yii::app()->request->isAjaxRequest) {
//                echo CJSON::encode(
//                    array(
//                        'success' => false,
//                        'message' => $e->getMessage()
//                    )
//                );
//                Yii::app()->end();
//            } else {
//                throw new CHttpException(500, $e->getMessage());
//            }
//        }
//    }
}
