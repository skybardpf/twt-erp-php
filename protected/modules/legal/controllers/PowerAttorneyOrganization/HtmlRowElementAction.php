<?php
/**
 * Only Ajax. Возращает html строку для вставки в таблицу видов договоров.
 *
 * @author Skibardin A.A. <skybardpf@artektiv.ru>
 */
class HtmlRowElementAction extends CAction
{
    /**
     * Только Ajax. Возращает html строку для вставки в таблицу видов договоров.
     */
    public function run() {
        if (Yii::app()->request->isAjaxRequest) {
            try {
                if (!isset($_POST['id']) || empty($_POST['id'])){
                    throw new CException('Не указан идентификатор');
                }
                if (!isset($_POST['name']) || empty($_POST['name'])){
                    throw new CException('Не указано название');
                }

                echo CJSON::encode(
                    array(
                        'success' => true,
                        'html' => $this->controller->renderPartial(
                            '/power_attorney_organization/_html_row_element',
                            array(
                                'id' => $_POST['id'],
                                'name' => $_POST['name'],
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