<?php
/**
 * Only Ajax. Возращает HTML форму с со списком организаций.
 *
 * @author Skibardin A.A. <skybardpf@artektiv.ru>
 */
class HtmlFormSelectOrganizationAction extends CAction
{
    /**
     * Только Ajax. Рендерим форму со списком организаций. Показываются только организации,
     * которые еще не привязанны к данному событию.
     */
    public function run() {
        if (Yii::app()->request->isAjaxRequest) {
            try {
                $data = Organizations::getValues();
                if (isset($_POST['ids']) && !empty($_POST['ids'])){
                    $sel = CJSON::decode($_POST['ids']);
                    if ($sel !== null){
                        foreach ($sel as $k){
                            if (isset($data[$k])){
                                unset($data[$k]);
                            }
                        }
                    }
                }
                $data = array_merge(array('' => 'Выберите'), $data);

                $this->controller->renderPartial(
                    '/my_events/get_list_organizations',
                    array(
                        'data' => $data,
                    ),
                    false
                );

            } catch (CException $e){
                echo $e->getMessage();
            }
        }
    }
}