<?php
/**
 * Создание "Бенефициара".
 *
 * @author Skibardin A.A. <webprofi1983@gmail.com>
 */
class CreateAction extends CAction
{
    /**
     * Создание "Бенефициара".
     * @param string $org_id    Идентификатор организации
     * @param string $org_type  Тип организации
     * @throws CHttpException
     */
    public function run($org_id, $org_type)
    {
        /**
         * @var Interested_person_shareholderController $controller
         */
        $controller = $this->controller;
        $controller->pageTitle .= ' | Создание номинального акционера';

        $forceCached = (Yii::app()->request->getQuery('force_cache') == 1);
        if ($org_type === MTypeOrganization::ORGANIZATION){
            $org = Organization::model()->findByPk($org_id, $forceCached);
            $render_page = '/organization/show';
            $controller->menu_current = 'legal';
        } elseif ($org_type === MTypeOrganization::CONTRACTOR){
            $org = Contractor::model()->findByPk($org_id, $forceCached);
            $render_page = '/contractor/menu_tabs';
            $controller->menu_current = 'contractors';
        } else
            throw new CHttpException(500, 'Указан неизвестный тип организации');

        $model = new InterestedPersonBeneficiary();
        $model->id_yur = $org->primaryKey;
        $model->type_yur = $org_type;
        $model->forceCached = $forceCached;

        $data = Yii::app()->request->getPost(get_class($model));
        if ($data) {
            $model->setAttributes($data);
            if ($model->validate()) {
                try {
                    $model->save();
                    $controller->redirect($controller->createUrl(
                        'interested_person_beneficiary/index',
                        array(
                            'org_id' => $org->primaryKey,
                            'org_type' => $org->type,
                        )
                    ));
                } catch (CException $e) {
                    $model->addError('id', $e->getMessage());
                }
            }
        }

        $controller->render($render_page, array(
            'content' => $controller->renderPartial('/interested_person_beneficiary/form',
                array(
                    'model' => $model,
                    'organization' => $org,
                ), true
            ),
            'organization' => $org,
            'cur_tab' => $controller->current_tab,
        ));
    }
}