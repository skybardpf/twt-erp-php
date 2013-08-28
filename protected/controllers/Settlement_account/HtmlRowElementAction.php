<?php
/**
 * Only Ajax. Возращает html строку для вставки в грид управляющих персон.
 *
 * @author Skibardin A.A. <webprofi1983@gmail.com>
 */
class HtmlRowElementAction extends CAction
{
    /**
     * Только Ajax. Возращает html строку для вставки в таблицу организаций.
     */
    public function run() {
        if (Yii::app()->request->isAjaxRequest) {
            try {
                $id = Yii::app()->request->getPost('id');
                if (!$id){
                    throw new CException('Не указан идентификатор');
                }
                $name = Yii::app()->request->getPost('name');
                if (!$name){
                    throw new CException('Не указано название');
                }

                echo CJSON::encode(
                    array(
                        'success' => true,
                        'html' => $this->controller->renderPartial(
                            '/settlement_account/_html_row_element',
                            array(
                                'id' => $id,
                                'name' => $name,
                            ),
                            true
                        )
                    )
                );
                Yii::app()->end(200);

            } catch (CException $e){
                echo CJSON::encode(
                    array(
                        'success' => false,
                        'message' => $e->getMessage()
                    )
                );
                Yii::app()->end(400);
            }
        }
    }
}