<?php
/**
 * Only Ajax. Возращает html форму для выбора подписанта договора.
 *
 * @author Skibardin A.A. <skybardpf@artektiv.ru>
 */
class HtmlModalSelectSignatoryAction extends CAction
{
    /**
     * Only Ajax. Возращает html форму для выбора подписанта договора.
     */
    public function run()
    {
        if (Yii::app()->request->isAjaxRequest) {
            if (!isset($_POST['type']) || !in_array($_POST['type'], array('signatory', 'signatory_contractor'))){
                echo 'Передан неизвестный тип подписанта';
                Yii::app()->end();
            }
            $data = Individual::getValues();
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
                '/contract/_form_select_signatory',
                array(
                    'data' => $data,
                    'type' => $_POST['type']
                ),
                false
            );
            Yii::app()->end();
        }
    }
}