<?php
/**
 * Only Ajax. Возращает HTML форму с со списком организаций.
 *
 * @author Skibardin A.A. <skybardpf@artektiv.ru>
 */
class HtmlFormSelectElementAction extends CAction
{
    /**
     * Только Ajax. Рендерим форму со списком организаций. Показываются только организации,
     * которые еще не привязанны к данному событию.
     */
    public function run() {
        if (Yii::app()->request->isAjaxRequest) {
            try {
                if (!isset($_POST['type']) || !in_array($_POST['type'], array('organization', 'country'))){
                    throw new CException('Передан неизвестный тип.');
                }
                if ($_POST['type'] == 'organization'){
                    $data = Organization::getValues();
                    $str = 'Выберите организацию';
                } else {
                    $data = Countries::getValues();
                    $str = 'Выберите страну';
                }
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
                $data = array_merge(array('' => $str), $data);

                $html = $this->controller->renderPartial(
                    '/my_events/_html_form_select_element',
                    array(
                        'data' => $data,
                        'type' => $_POST['type']
                    ),
                    true
                );

                echo CJSON::encode(
                    array(
                        'success' => true,
                        'html' => $html
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