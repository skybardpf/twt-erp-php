<?php
/**
 * Список Заинтересованных лиц.
 *
 * @author Skibardin A.A. <webprofi1983@gmail.com>
 */
class IndexAction extends CAction
{
    /**
     * @param string $org_id
     * @throws CHttpException
     */
    public function run($org_id)
    {
        /**
         * @var $controller Interested_personsController
         */
        $controller = $this->controller;
        $controller->pageTitle .= ' - Список заинтересованных лиц';

        /**
         * @var $org Organization
         */
        $org = Organization::model()->findByPk('000000001');
        if ($org === null) {
            throw new CHttpException(404, 'Не найдено юридическое лицо.');
        }

        $data = $controller->getIndexProviderModel($org_id);
        $controller->render('/organization/show', array(
            'content' => $controller->renderPartial('/interested_persons/index',
                array(
                    'data' => $data,
                    'organization' => $org,
                ), true),
            'organization' => $org,
            'cur_tab' => $controller->current_tab,
        ));
    }
}