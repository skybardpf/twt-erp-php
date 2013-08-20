<?php
/**
 * Only Ajax. Возращает HTML форму с со списком видов договоров.
 *
 * @author Skibardin A.A. <skybardpf@artektiv.ru>
 */
class HtmlFormSelectElementAction extends CAction
{
    /**
     * Только Ajax. Рендерим форму со списком видов договоров.
     */
    public function run()
    {
        if (Yii::app()->request->isAjaxRequest) {
            try {
                $data = ContractType::model()->listNames();
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
//                $data = array_merge(array('' => $str), $data);

                $html = $this->controller->renderPartial(
                    '/power_attorney_organization/_html_form_select_element',
                    array(
                        'data' => $data,
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