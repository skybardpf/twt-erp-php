<?php
/**
 *  Документы юридического лица.
 *
 *  User: Skibardin A.A.
 *  Date: 03.07.13
 */
class DocumentsController extends Controller{
    public $layout = 'inner';
    public $menu_current = 'legal';

    /**
     *  Выводим список документов юридического лица $org_id.
     *
     *  @param string $org_id
     */
    public function actionIndex($org_id)
    {
        $this->redirect($this->createUrl('list', array('org_id' => $org_id)));
    }

    /**
     *  Выводим список документов юридического лица $org_id.
     *
     *  @param string $org_id
     *
     *  @throws CHttpException
     */
    public function actionList($org_id)
    {
        $org = Organization::model()->findByPk($org_id);
        if (!$org) {
            throw new CHttpException(404, 'Не найдено юридическое лицо.');
        }

        // Учредительные документы
        $founding_docs = FoundingDocument::model()
            ->where('deleted', false)
            ->where('id_yur',  $org->primaryKey)
            ->where('type_yur', 'Организации')
            ->findAll();

        // получаем набор документов типа "Доверенность"
        $power_attorneys_docs = PowerAttorneysLE::model()
            ->where('deleted', false)
            ->where('id_yur', $org->primaryKey)
            ->where('type_yur', 'Организации')
            ->findAll();

        $free_docs = FreeDocument::model()
            ->where('deleted', false)
            ->where('id_yur', $org->primaryKey)
            ->findAll();

        $this->render('/organization/show', array(
            'content' => $this->renderPartial('/documents/list',
                array(
                    'free_docs'         => $free_docs,
                    'founding_docs'     => $founding_docs,
                    'power_attorneys_docs' => $power_attorneys_docs,
                    'organization'      => $org
                ), true),
            'organization' => $org,
            'cur_tab' => 'documents',
        ));
    }
}