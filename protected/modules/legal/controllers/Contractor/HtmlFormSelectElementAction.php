<?php
/**
 * Only Ajax. Возращает HTML форму с со списком подписантов и довереностей.
 *
 * @author Skibardin A.A. <webprofi1983@gmail.com>
 */
class HtmlFormSelectElementAction extends CAction
{
    /**
     * Только Ajax. Рендерим форму со списком подписантов и довереностей.
     * @param string $id Идентификатор организации/контрагента.
     */
    public function run($id) {
        if (Yii::app()->request->isAjaxRequest) {
            try {
                if (!isset($_POST['type']) || !in_array($_POST['type'], array('organization', 'contractor'))){
                    throw new OrganizationException('Указан неправильный тип организации');
                }
                if ($_POST['type'] == 'organization'){
                    $type = Organization::TYPE;
                } else {
                    $type = Contractor::TYPE;
                }

                $docs = PowerAttorneysLE::model()->getNamesByOrganizationId($type, $id);
                if (isset($_POST['ids']) && !empty($_POST['ids'])){
                    $sel = CJSON::decode($_POST['ids']);
                    if ($sel !== null){
                        foreach ($sel as $v){
                            if (isset($docs[$v['doc_id']])){
                                unset($docs[$v['doc_id']]);
                            }
                        }
                    }
                }
                $docs = array_merge(array('' => '--- Выберите довереность ---'), $docs);

                echo CJSON::encode(
                    array(
                        'success' => true,
                        'html' => $this->controller->renderPartial(
                            '/contractor/_html_form_select_element',
                            array(
                                'docs' => $docs
                            ),
                            true
                        )
                    )
                );
                Yii::app()->end();

            } catch (CException $e){
                echo CJSON::encode(
                    array(
                        'success' => false,
                        'message' => $e->getMessage()
                    )
                );
                Yii::app()->end();
            }
        }
    }
}