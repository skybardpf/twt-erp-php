<?php
/**
 * Список Заинтересованных лиц.
 *
 * @author Skibardin A.A. <skybardpf@artektiv.ru>
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
         * @var $org Organizations
         */
        $org = Organizations::model()->findByPk('000000001');
        if ($org === null) {
            throw new CHttpException(404, 'Не найдено юридическое лицо.');
        }

        $data = $controller->getIndexProviderModel($org_id);
        $controller->render('/my_organizations/show', array(
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