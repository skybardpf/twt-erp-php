<?php
/**
 * Создание "Номинального акционера".
 *
 * @author Skibardin A.A. <webprofi1983@gmail.com>
 */
class CreateAction extends CAction
{
    /**
     * Создание "Номинального акционера".
     * @param $org_id   Идентификатор организации
     * @throws CHttpException
     */
    public function run($org_id)
    {
        /**
         * @var Interested_person_shareholderController $controller
         */
        $controller = $this->controller;
        $controller->pageTitle .= ' | Создание номинального акционера';

        $forceCached = (Yii::app()->request->getQuery('force_cache') == 1);
        $org = Organization::model()->findByPk($org_id, $forceCached);

        $model = new InterestedPersonShareholder();
        $model->id_yur = $org->primaryKey;
        $model->type_yur = MTypeOrganization::ORGANIZATION;
        $model->forceCached = $forceCached;

        $data = Yii::app()->request->getPost(get_class($model));
        if ($data) {
            $model->setAttributes($data);
            if ($model->validate()) {
                try {
                    $model->save();
                    $controller->redirect($controller->createUrl(
                        'index',
                        array(
                            'org_id' => $org->primaryKey,
                            'type' => $model->pageTypePerson
                        )
                    ));
                } catch (CException $e) {
                    $model->addError('id', $e->getMessage());
                }
            }
        }

        $controller->render('/organization/show', array(
            'content' => $controller->renderPartial('/interested_person_shareholder/form',
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