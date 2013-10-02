<?php
/**
 * Only Ajax. Возращает html форму для выбора подписанта договора.
 *
 * @author Skibardin A.A. <webprofi1983@gmail.com>
 */
class HtmlModalSelectSignatoryAction extends CAction
{
    /**
     * Only Ajax. Возращает html форму для выбора подписанта договора.
     */
    public function run()
    {
        if (Yii::app()->request->isAjaxRequest) {
            $type = Yii::app()->request->getPost('type', '');
            if (!in_array($type, array('organization_signatories', 'contractor_signatories'))){
                echo 'Передан неизвестный тип подписанта';
                Yii::app()->end();
            }
            $data = Individual::model()->listNames();
            $ids = Yii::app()->request->getPost('ids');
            if (!empty($ids)){
                $sel = CJSON::decode($ids);
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
                    'type' => $type,
                ),
                false
            );
            Yii::app()->end();
        }
    }
}