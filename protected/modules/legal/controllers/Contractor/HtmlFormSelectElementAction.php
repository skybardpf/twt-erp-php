<?php
/**
 * Only Ajax. Возращает HTML форму с со списком подписантов и довереностей.
 *
 * @author Skibardin A.A. <skybardpf@artektiv.ru>
 */
class HtmlFormSelectElementAction extends CAction
{
    /**
     * Только Ajax. Рендерим форму со списком подписантов и довереностей.
     */
    public function run() {
        if (Yii::app()->request->isAjaxRequest) {
            try {
//                if (isset($_POST['ids']) && !empty($_POST['ids'])){
//                    $sel = CJSON::decode($_POST['ids']);
//                    if ($sel !== null){
//                        foreach ($sel as $k){
//                            if (isset($data[$k])){
//                                unset($data[$k]);
//                            }
//                        }
//                    }
//                }
                $persons = Individuals::getValues();
                $docs   = PowerAttorneysLE::model()->getAllNames();
                $persons = array_merge(array('' => '--- Выберите подписанта ---'), $persons);
                $docs = array_merge(array('' => '--- Выберите довереность ---'), $docs);

                $html = $this->controller->renderPartial(
                    '/contractor/_html_form_select_element',
                    array(
                        'persons' => $persons,
                        'docs' => $docs
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