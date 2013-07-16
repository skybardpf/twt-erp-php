<?php
/**
 *  Управление свободными документами.
 *
 *  User: Skibardin A.A.
 *  Date: 03.07.13
 */
class Free_documentsController extends Controller {
    public $layout = 'inner';
    public $menu_current = 'legal';

    /**
     *  Просмотр свободного документа с идентификатором $id.
     *
     *  @param  string $id
     *
     *  @throws CHttpException
     */
    public function actionView($id)
    {
        $doc = FreeDocument::model()->findByPk($id);
        if (!$doc){
            throw new CHttpException(404, 'Не найден свободный документ.');
        }
        $org = Organizations::model()->findByPk($doc->id_yur);
        if (!$org){
            throw new CHttpException(404, 'Не найдено юридическое лицо.');
        }

        $this->render('/my_organizations/show', array(
            'content' => $this->renderPartial('/free_documents/show',
                array(
                    'model'         => $doc,
                    'organization'  => $org
                ), true),
            'organization' => $org,
            'cur_tab' => 'documents',
        ));
    }

    /**
     *  Добавление нового свободного документа к указанному в $org_id юридическому лицу.
     *
     *  @param  string $org_id
     *
     *  @throws CHttpException
     */
    public function actionAdd($org_id)
    {
        $org = Organizations::model()->findByPk($org_id);
        if (!$org){
            throw new CHttpException(404, 'Не найдено юридическое лицо.');
        }

        $doc = new FreeDocument();
        $doc->id_yur    = $org->primaryKey;
        $doc->type_yur  = 'Организации';

        if ($_POST && !empty($_POST['FreeDocument'])) {
            $doc->setAttributes($_POST['FreeDocument']);
            if ($doc->validate()) {
                try {
                    $doc->save();
                    $this->redirect($this->createUrl('documents/list', array('org_id' => $org->primaryKey)));
                } catch (Exception $e) {
                    $doc->addError('id', $e->getMessage());
                }
            }
        }

        $this->render('/my_organizations/show', array(
            'content' => $this->renderPartial(
                '/free_documents/form',
                array(
                    'model'         => $doc,
                    'organization'  => $org,
                ),
                true
            ),
            'organization' => $org,
            'cur_tab' => 'documents',
        ));
    }

    /**
     *  Редактирование свободного документа с идентификатором $id.
     *
     *  @param  string $id
     *
     *  @throws CHttpException
     */
    public function actionEdit($id)
    {
        $doc = FreeDocument::model()->findByPk($id);
        if (!$doc){
            throw new CHttpException(404, 'Не найден свободный документ.');
        }
        $org = Organizations::model()->findByPk($doc->id_yur);
        if (!$org){
            throw new CHttpException(404, 'Не найдено юридическое лицо.');
        }

        if ($_POST && !empty($_POST['FreeDocument'])) {
            $doc->setAttributes($_POST['FreeDocument']);
            if ($doc->validate()) {
                try {
                    $doc->save();
                    $this->redirect($this->createUrl('view', array('id' => $doc->primaryKey)));
                } catch (Exception $e) {
                    $doc->addError('id', $e->getMessage());
                }
            }
        }

        $this->render('/my_organizations/show', array(
            'content' => $this->renderPartial(
                '/free_documents/form',
                array(
                    'model' => $doc,
                    'organization' => $org,
                ),
                true
            ),
            'organization' => $org,
            'cur_tab' => 'documents',
        ));
    }

    /**
     *  Удаление свободного документа с идентификатором $id.
     *
     *  @param  string $id
     *
     *  @throws CHttpException
     */
    public function actionDelete($id)
    {
        $doc = FreeDocument::model()->findByPk($id);
        if (!$doc){
            throw new CHttpException(404, 'Не найден свободный документ.');
        }
        $org = Organizations::model()->findByPk($doc->id_yur);
        if (!$org){
            throw new CHttpException(404, 'Не найдено юридическое лицо.');
        }

        if (Yii::app()->request->isAjaxRequest) {
            $ret = array();
            try {
                $doc->delete();
            } catch (Exception $e) {
                $ret['error'] = $e->getMessage();
            }
            echo CJSON::encode($ret);
            Yii::app()->end();
        } else {
            if (isset($_POST['result'])) {
                switch ($_POST['result']) {
                    case 'yes':
                        if ($doc->delete()) {
                            $this->redirect($this->createUrl('documents/list', array('org_id' => $org->primaryKey)));
                        } else {
                            throw new CHttpException(500, 'Не удалось удалить свободный документ');
                        }
                        break;
                    default:
                        $this->redirect($this->createUrl('view', array('id' => $doc->primaryKey)));
                    break;
                }
            }
//            $this->render('documents/free_documents/delete', array('model' => $doc));
        }
    }
}